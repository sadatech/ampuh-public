<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Ba;
use App\BaHistory;
use App\BaSummary;
use App\Brand;
use App\Notification;
use App\Replace;
use App\Store;
use App\Traits\ConfigTrait;
use App\Traits\StringTrait;
use App\WIP;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RollingBaRequest extends FormRequest
{

    use StringTrait, ConfigTrait;


    protected $oldStore;

    protected $newStore;

    protected $ba;

    protected $differentReo;

    protected $brandUpdate;

    protected $replaceBa;

    protected $alasanBa;

    protected $isJoinBack = false;

    protected $headCount;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Rolling Ba
     *
     */
    public function rolling()
    {
        $this->oldStore = $this->get('tokoId');
        $this->newStore = $this->get('newStore');
        $this->ba = Ba::withCount('store')->with('store')->find($this->get('id'));
        $this->differentReo = $this->get('newReo')['userId'] != Auth::user()->id && $this->get('newReo')['userId'] != 0;
        $this->replaceBa = ($this->moveFromAllStore()) ? $this->decideReplaceBa($this->bulk['wip'])
                                                     : $this->decideReplaceBa($this->get('replaceBaId'));
        $this->brandUpdate = (count($this->replaceBa) != 0) ? $this->replaceBa[0]['brand_id'] : $this->get('newBrand');
        $this->headCount = 1 / count($this->ba->store);
        $newStore = Store::find($this->newStore) ? Store::find($this->newStore)->store_name_1 : '';
        if (is_array($this->get('replaceBaId'))) {
            $newStore = collect($this->replaceBa)->reduce(function ($start, $item) {
                $start .= $item->store->store_name_1 . ', ';
                return $start;
            }, '');
        }
        $this->alasanBa = $this->alasanBa($this->get('nama'), $this->rollingStatus($this->oldStore), $newStore, $this->get('statusRolling'));

        DB::transaction(function () {
            if ($this->get('statusRolling') == 'takeout') {
                $this->takeoutRolling();
                return;
            }
            if ($this->get('statusRolling') == 'switch') {
                $this->switchRolling();
                return;
            }

            $this->ba->update(['brand_id' => $this->brandUpdate]);

            if ($this->moveBrand()) {
                $this->brandMoveRolling();
            } else if ($this->isAddMoreStore()) {
                $this->addMoreStoreToBa();
            } else if ($this->moveFromAllStore()) {
                $this->bulkRolling();
            } else {
                $this->basicRolling();
            }

            $this->fulfilledWipIfShould();

            $this->ba->update(['status' => $this->decideStatus($this, $this->ba)]);

            if ($this->moveToMdsStore()) {
                $this->ba->update(['join_date_mds' => $this->get('joinDateMds')['joinDate']]);
            }

        });
    }

    /**
     * Rolling when New Ba comeback to the game._.
     *
     */
    public function joinBack()
    {
        DB::transaction(function () {
            $this->ba = Ba::withCount('store')->with('store')->find($this->get('id'));
            $this->newStore = $this->get('newStore');
            $this->replaceBa = $this->decideReplaceBa($this->get('replaceBaId'));
            $this->brandUpdate = (count($this->replaceBa) != 0) ? $this->replaceBa[0]['brand_id'] : $this->get('newBrand');
            $replaceBaCheck = Ba::find(collect($this->replaceBaId)->first())
                ? Ba::find(collect($this->replaceBaId)->first())->name
                : null;
            $this->alasanBa = $this->alasanRejoinBa($this->ba, $replaceBaCheck);
            $this->differentReo = $this->get('newReo')['userId'] != Auth::user()->id && $this->get('newReo')['userId'] != 0;
            $updateData = ['approval_id' => 9, 'join_date' => $this->newJoinDate, 'brand_id' => $this->brandUpdate];
            if ($this->file('attachment') != null) {
                $foto_akte = $this->file('attachment');
                $foto_akte_orig = time() . '-' . $foto_akte->getClientOriginalName();
                $foto_akte->move('attachment/akte', $foto_akte_orig);
                $updateData['foto_akte'] = $foto_akte_orig;
                $updateData['anak_lahir_date'] = $this->birthDateChild;
            }
            $this->isJoinBack = true;

            $this->ba->update($updateData);

            $this->addMoreStoreToBa();

            $this->fulfilledWipIfShould(false);

        });

    }

    /**
     * Handle Takeout Rolling
     *
     */
    private function takeoutRolling()
    {
        $reduceStoreAllocation = Store::find($this->oldStore);
        $reduce = 1;
        $allocation = $this->alokasiBa($this->get('brand'));
        $this->ba->store()->detach($this->oldStore);
        if ($this->ba->store_count > 1) {
            $reduce = 1 / $this->ba->store_count;
        }
        $reduceStoreAllocation->update([$allocation => ($reduceStoreAllocation[$allocation] - $reduce)]);
        $this->removeTakeoutFromConfiguration($this->oldStore, $this->ba->brand_id, $this->ba->id);
        $this->removeForStoreConfiguration($this->oldStore);

        $this->ba->fresh()->store->map(function ($item) use ($reduce, $allocation) {
            Store::find($item->id)->update([$allocation => $item[$allocation] + $reduce]);
        });


        if (count($this->ba->fresh()->store) == 1) $this->ba->update(['status' => 'stay']);
    }

    /**
     * Deciding which replace Ba should be called
     *
     * @param $replaceBaId
     * @return mixed
     */
    private function decideReplaceBa($replaceBaId)
    {
        if (is_array($replaceBaId) && $this->moveFromAllStore()) {
            return WIP::whereIn('id', $replaceBaId)
                ->where('fullfield', 'not like', 'fullfield')
                ->with('store', 'ba')
                ->get();
        }
        if (is_array($replaceBaId)) {
            return WIP::whereIn('id', $replaceBaId)->whereNull('ba_id')
                ->where('fullfield', 'not like', 'fullfield')
                ->with('store', 'ba')
                ->get();
        }
        return WIP::where('ba_id', $replaceBaId)->where('store_id', $this->newStore)
            ->where('fullfield', 'not like', 'fullfield')
            ->with('store', 'ba')
            ->orWhere(function ($query) use ($replaceBaId) {
                return $query->where('id', $replaceBaId)
                    ->whereNull('ba_id')
                    ->where('fullfield', 'not like', 'fullfield');
            })
            ->get();
    }

    /**
     * Rolling to add more store to Ba usually Ba Stay to Mobile
     *
     */
    private function addMoreStoreToBa()
    {
        $attachStore = is_array($this->get('replaceBaId')) ? $this->replaceBa->pluck('store_id')->toArray()
            : $this->newStore;
        $this->ba->store()->attach($attachStore);
        $baCount = count($this->ba->fresh()->store);
        ($baCount > 1) ? $this->ba->update(['status' => 'mobile'])
            : $this->ba->update(['status' => 'stay']);
        $this->ba->fresh()->store->map(function ($item) use ($baCount, $attachStore) {
            $this->syncStoreAllocation($item, collect($attachStore), $baCount);
        });
    }

    /**
     * Basic Rolling Stuff ^.^
     *
     */
    private function basicRolling()
    {
        $brandName = Brand::find($this->brandUpdate)->name;
        $updateBrand = $this->alokasiBa($brandName);
        $brandCount = $this->alokasiCount($brandName);

        if ( !is_array($this->get('replaceBaId'))) {
            $newStoreAllocation = Store::actualBa($this->newStore)->first();

            if ($newStoreAllocation[$updateBrand] - $newStoreAllocation[$brandCount] == 0 && count($this->replaceBa) == 0){
                // bagian ini kalau butuh tambah alokasi ketika rolling cuman udah ga mungkin lagi karena harus ada WIP
                // cuman disimpen aja jaga jaga

                $newStoreAllocation->update([$updateBrand => $newStoreAllocation[$updateBrand] += 1]);
                $this->triggerRolling($this->oldStore, $this->newStore, $this->ba, true, true);
            } else {
                $this->triggerRolling($this->oldStore, $this->newStore, $this->ba);
            }
            $this->ba->store()->updateExistingPivot($this->oldStore, ['store_id' => $this->newStore]);
        } else {
            BaHistory::create(['ba_id' => $this->ba->id, 'status' => 'rolling_out', 'store_id' => $this->oldStore]);
            BaSummary::where('store_id', $this->oldStore)->where('ba_id', $this->ba->id)
                ->where('month', Carbon::now()->month)
                ->where('year', Carbon::now()->year)
                ->first()
                ->update(['ba_id' => 0]);

            $this->ba->store()->sync($this->replaceBa->pluck('store_id')->toArray());
            $this->ba->fresh()->store->map(function ($item) use ($updateBrand, $brandCount) {
                $newStoreAllocation = Store::actualBa($item->id)->first();
                if ($newStoreAllocation[$updateBrand] - $newStoreAllocation[$brandCount] == 0 && count($this->replaceBa) == 0) {
                    $newStoreAllocation->update([$updateBrand => $newStoreAllocation[$updateBrand] += 1]);
                    $this->triggerRolling($this->oldStore, $item->id, $this->ba, false, true);
                } else {
                    $this->triggerRolling($this->oldStore, $item->id, $this->ba, false);
                }
            });
        }

        if ($this->isTakeoutMobile()) {
            // kenapa 0  karena di atas udah kedeteksi vacant dan ba id nya dirubah jadi 0
            $this->removeTakeoutFromConfiguration($this->oldStore, $this->ba->brand_id, 0);
            $this->removeForStoreConfiguration($this->oldStore);
            return;
        }

        $wip = WIP::create(['store_id' => $this->oldStore, 'ba_id' => $this->ba->id, 'status' => 'replacement',
            'brand_id' => $this->get('brandId'), 'fullfield' => 'hold', 'reason' => $this->alasanBa,
            'filling_date' => $this->get('pengajuanRequest'), 'effective_date' => $this->get('firstDate'),
            'head_count' => $this->headCount]);
        if ($this->differentReo && Auth::user()->role == 'reo') {
            WIP::where('id', $wip->id)->update(['pending' => 1]);
        }
    }

    /**
     * Bulk Rolling when rolling from all stores
     */
    public function bulkRolling()
    {
        $newStores = $this->bulk['storesid'];

        $oldStores = $this->ba->store->map(function ($item) {
            $wip = WIP::create(['store_id' => $item->id, 'ba_id' => $this->ba->id, 'status' => 'replacement',
                'brand_id' => $this->get('brandId'), 'fullfield' => 'hold', 'reason' => $this->alasanBa,
                'filling_date' => $this->get('pengajuanRequest'), 'effective_date' => $this->get('firstDate'),
                'head_count' => $this->headCount, 'pending' => 0]);
            if ($this->differentReo && Auth::user()->role == 'reo') {
                WIP::where('id', $wip->id)->update(['pending' => 1]);
            }
            return $item->id;
        })->toArray();
        (count($oldStores) > 1) ? $this->ba->update(['status' => 'mobile'])
                                 : $this->ba->update(['status' => 'stay']);

        $this->triggerBulkRolling($oldStores, $newStores, $this->ba);
        $this->ba->store()->sync($newStores);
    }

    /**
     * Fulfilled Wip if should
     *
     * @param bool $basicRolling if not basic rolling do fulfilled when rejoining ba
     */
    private function fulfilledWipIfShould($basicRolling = true)
    {
        if (count($this->replaceBa) != 0) {
            $this->replaceBa->filter(function ($item) {
                return $item->brand_id == $this->brandUpdate;
            })->map(function ($item) use ($basicRolling) {
                if ($basicRolling) {
                    return $this->fulfilledWip($item, $this->ba, $this, $this->differentReo);
                }
                return $this->fulfilledWipFromRejoin($item, $this->ba);

            });
        } else if (count($this->replaceBa) == 0 && isset($wip)) {
            Notification::create([
                'name' => 'Perollingan BA Butuh Approval Anda',
                'message' => '',
                'status' => 'rolling',
                'ba_id' => $this->ba->id,
                'role' => 'reo',
                'wip_id' => $wip->id,
                'isReplacing' => 1,
                'read' => 0]);
        }
    }

    /**
     * Helper for WIP fulfilled
     *
     * @param $replaceBa wip nya
     * @param $ba
     * @param $request
     * @param $differentReo
     */
    public function fulfilledWip($replaceBa, $ba, $request, $differentReo)
    {
        $name =  isset($replaceBa->ba) ? $replaceBa->ba->name : ' ';
        $replaceName =  $this->replaceName($replaceBa->ba, $name);

        $reason = $replaceName == 'Mengisi Toko Baru' ? 'Rolling untuk ' . $replaceName
                                                      : 'Rolling dari ' . $request->tokoName . $replaceName . ' Dengan Status ' . $request->statusRolling;

        $replace = Replace::create(['ba_id' => $ba->id, 'description' => $reason, 'status' => 'Rolling dari Toko lain']);
        $updateArray = ['replace_id' => $replace->id, 'fullfield' => 'fullfield', 'ba_id' => $ba->id, 'reason' => $reason, 'effective_date' => $this->get('firstDate')];
        if ($differentReo && Auth::user()->role == 'reo') {
            $updateArray['pending'] = 1;
            Notification::create([
                'name' => 'Perollingan BA Butuh Approval Anda',
                'message' => '',
                'status' => 'rolling',
                'ba_id' => $ba->id,
                'role' => 'reo',
                'wip_id' => $replaceBa->id,
                'read' => 0]);
        }
        WIP::find($replaceBa->id)
            ->update($updateArray);
    }

    /**
     * Fulfilling WIP if the rolling came from rejoining
     *
     * @param $replaceBa
     * @param $ba
     */
    private function fulfilledWipFromRejoin($replaceBa, $ba)
    {
        $replace = Replace::create(['ba_id' => $ba->id, 'description' => $this->alasanBa, 'status' => 'Ba Join Kembali']);

        $updateArray = ['replace_id' => $replace->id, 'fullfield' => 'fullfield', 'ba_id' => $ba->id, 'reason' => $this->alasanBa, 'effective_date' => $this->get('newJoinDate')];

        WIP::find($replaceBa->id)->update($updateArray);

    }


    /**
     * Trigger when ba rolling and update the ba summary table
     *
     * @param $oldStore
     * @param $newStore
     * @param $ba
     * @param bool $removeOldStore
     * @param bool $createNewConfig
     * @param bool $changeOldStoreBrand
     */
    private function triggerRolling($oldStore, $newStore, $ba, $removeOldStore = true, $createNewConfig = false, $changeOldStoreBrand = false)
    {
        if ($newStore != 0) BaHistory::create(['ba_id' => $ba->id, 'status' => 'rolling_in', 'store_id' => $newStore]);
        if (!$createNewConfig) {
            $cek = BaSummary::where('store_id', $newStore)
                ->where('brand_id', $ba->brand_id)->where('ba_id', 0)
                ->where('month', Carbon::now()->month)
                ->where('year', Carbon::now()->year)
                ->first()
                ->update(['ba_id' => $ba->id]);
        } else if ($createNewConfig && !$changeOldStoreBrand) {
            $as = BaSummary::create(['store_id' => $newStore, 'ba_id' => $ba->id, 'brand_id' => $ba->brand_id, 'month' => Carbon::now()->month, 'year' => Carbon::now()->year]);
        }
        if ($changeOldStoreBrand && !$this->isJoinBack) {
            BaSummary::where('store_id', $oldStore)
                ->where('ba_id', $ba->id)
                ->where('month', Carbon::now()->month)
                ->where('year', Carbon::now()->year)
                ->first()
                ->update(['brand_id' => $ba->brand_id]);
        }
        if ($removeOldStore && !$this->isJoinBack) {
            BaHistory::create(['ba_id' => $ba->id, 'status' => 'rolling_out', 'store_id' => $oldStore]);
            $a = BaSummary::where('store_id', $oldStore)->where('ba_id', $ba->id)
                ->where('month', Carbon::now()->month)
                ->where('year', Carbon::now()->year)
                ->firstOrFail()
                ->update(['ba_id' => 0]);
        }
    }

    /**
     * Triggering when do bulk rolling *rolling all old stores
     *
     * @param $oldStores
     * @param $newStores
     * @param $ba
     */
    private function triggerBulkRolling($oldStores, $newStores, $ba)
    {
        collect($newStores)->map(function ($item) use ($ba) {
            BaSummary::where('store_id', $item)
                ->where('brand_id', $ba->brand_id)->where('ba_id', 0)
                ->where('month', Carbon::now()->month)
                ->where('year', Carbon::now()->year)
                ->first()
                ->update(['ba_id' => $ba->id]);
        });

        collect($oldStores)->map(function ($item) use ($ba) {
            BaSummary::where('store_id', $item)
                ->where('ba_id', $ba->id)
                ->where('month', Carbon::now()->month)
                ->where('year', Carbon::now()->year)
                ->firstOrFail()
                ->update(['ba_id' => 0]);
        });

    }

    /**
     * Sync Allocation whether mobile or stay with help of map
     *
     * @param Store $store
     * @param Collection $newStore
     * @param $baCount
     */
    private function syncStoreAllocation(Store $store, Collection $newStore, $baCount)
    {
        $newStore->map(function ($newStore) use ($store, $baCount) {
            $brandId = Brand::find($this->brandUpdate);
            $updateBrand = $this->alokasiBa($brandId->name);
            $hasEmptySpot = BaSummary::hasEmptySpot($store->id, $brandId->id)->first() != null;

            if ($store->id == $newStore && $hasEmptySpot) {
                $updateAllocation = (1 / $baCount != 1) ? $store[$updateBrand] - (1 / $baCount) : $store[$updateBrand];
            } else {
                $updateAllocation = ($store[$updateBrand] == 0) ? 1 / $baCount : (1 / $baCount) + $store[$updateBrand];
            }

            $updateData[$updateBrand] = $updateAllocation;

            if ($brandId->id == $this->get('brandId')) $updateData[$updateBrand] -= 1;

            if ($updateBrand != $this->get('brandId') && $store->id != $newStore) {
                $reduceBrand = $this->alokasiBa($this->get('brand'));
                $updateData[$reduceBrand] = $store[$reduceBrand] -= 1 / $baCount;
                $this->triggerRolling($store->id, 0, $this->ba, false, true, true);
            } else if ($store->id == $newStore && $updateBrand != $this->get('brandId') && !$hasEmptySpot) {
                $this->triggerRolling(0, $newStore, $this->ba, false, true);
            } else if ($store->id == $newStore && $hasEmptySpot) {
                $this->triggerRolling(0, $newStore, $this->ba, false);
            }

            Store::find($store->id)->update($updateData);
        });
    }

    /**
     * If the old Store is some strange integer that mean code to add more store
     *
     * @return bool
     */
    private function isAddMoreStore()
    {
        return $this->oldStore == 012344321;
    }

    /**
     * Check whether the rolling stat is takeout mobile
     *
     * @return bool
     */
    private function isTakeoutMobile()
    {
        return $this->get('statusRolling') == 'takeoutMobile';
    }

    /**
     * check if want to role to all store
     *
     * @return mixed
     */
    private function moveFromAllStore()
    {
        return $this->bulkRolling;
    }

    /**
     * Switching Role don't bother anything just swap
     *
     */
    private function switchRolling()
    {
        $switchBa = Ba::withCount('store')->with('store')->find($this->get('switch')['id']);

        $this->ba->update(['brand_id' => $switchBa->brand_id]);
        $switchBa->update(['brand_id' => $this->ba->brand_id]);

        $this->switchSummaryTable($this->ba->id, $this->get('switch')['id'], $this->ba->store->pluck('id')->toArray());
        $this->switchSummaryTable($switchBa->id, $this->ba->id, $switchBa->store->pluck('id')->toArray());

        $this->ba->store()->sync($switchBa->store->pluck('id')->toArray());
        $switchBa->store()->sync($this->ba->store->pluck('id')->toArray());
    }


    /**
     * Switching Old And New store BA from switch rolling
     *
     * @param $oldId
     * @param Int $newId
     * @param $storeIds
     * @return
     */
    private function switchSummaryTable($oldId, $newId, $storeIds)
    {
        return BaSummary::where('ba_id', $oldId)
            ->where('month', Carbon::now()->month)
            ->where('year', Carbon::now()->year)
            ->whereIn('store_id', $storeIds)
            ->update(['ba_id' => $newId]);
    }

    /**
     * Rolling when moving from same store but different brand
     */
    public function brandMoveRolling()
    {
        BaSummary::where('ba_id', $this->ba->id)
                  ->where('brand_id', $this->get('brandId'))
                  ->where('month', Carbon::now()->month)
                  ->where('year', Carbon::now()->year)
                  ->first()
                  ->update(['brand_id' => $this->replaceBa[0]['brand_id']]);

        if ($this->isTakeoutMobile()) {
            $this->removeTakeoutFromConfiguration($this->oldStore, $this->ba->brand_id, $this->ba->id);
            $this->removeForStoreConfiguration($this->oldStore);
            return;
        }

         WIP::create(['store_id' => $this->oldStore, 'ba_id' => $this->ba->id, 'status' => 'replacement',
            'brand_id' => $this->get('brandId'), 'fullfield' => 'hold', 'reason' => $this->alasanBa,
            'filling_date' => $this->get('pengajuanRequest'), 'effective_date' => $this->get('firstDate'),
            'head_count' => $this->headCount]);
    }

    /**
     * Detect whether rolling to MDS Store
     *
     * @return bool
     */
    private function moveToMdsStore()
    {
        return $this->get('joinDateMds')['show'] == true;
    }

    private function moveBrand()
    {
        return $this->oldStore == $this->newStore;
    }

}
