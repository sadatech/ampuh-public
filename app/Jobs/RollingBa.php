<?php

namespace App\Jobs;

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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RollingBa
{
    use StringTrait, ConfigTrait;

    protected $request;

    protected $oldStore;

    protected $newStore;

    protected $ba;

    protected $differentReo;

    protected $brandUpdate;

    protected $replaceBa;

    protected $alasanBa;

    /**
     * Create a new job instance.
     * initiating require variable for this job
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request ?: request();
        $this->oldStore = $this->request->get('tokoId');
        $this->newStore = $this->request->get('newStore');
        $this->ba = Ba::withCount('store')->with('store')->find($this->request->get('id'));
        $this->differentReo = $this->request->get('newReo')['userId'] != Auth::user()->id && $this->request->get('newReo')['userId'] != 0;
        $this->replaceBa = $this->decideReplaceBa($this->request->get('replaceBaId'));
        $this->brandUpdate = (count($this->replaceBa) != 0) ? $this->replaceBa[0]['brand_id'] : $this->request->get('newBrand');
        $this->alasanBa = $this->alasanBa($this->request->get('nama'), $this->rollingStatus($this->oldStore), Store::find($this->newStore)->store_name_1, $this->request->get('statusRolling'));
    }

    /**
     * Execute the job for ba Rolling.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            if ($this->request->get('statusRolling') == 'takeout') {
                $this->takeoutRolling();
                return;
            }
            $this->ba->update([
                'brand_id' => $this->brandUpdate,
                'status' => $this->decideStatus($this->request, $this->ba)
            ]);

            if ($this->oldStore == 012344321) {
                $this->addMoreStoreToBa();
            } else {
                $this->basicRolling();
            }

            $this->fulfilledWipIfShould();
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
        $allocation = $this->alokasiBa($this->request->get('brand'));
        $this->ba->store()->detach($this->oldStore);
        if ($this->ba->store_count > 1) {
            $reduce = 1 / $this->ba->store_count;
        }
        $reduceStoreAllocation->update([$allocation => ($reduceStoreAllocation[$allocation] - $reduce)]);
        $this->removeTakeoutFromConfiguration($this->oldStore, $this->ba->brand_id, $this->ba->id);

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
        if (is_array($replaceBaId)) {
            return WIP::whereIn('id', $replaceBaId)->whereNull('ba_id')
                ->where('fullfield', 'not like', 'fullfield')
                ->get();
        }
        return WIP::where('ba_id', $replaceBaId)->where('store_id', $this->newStore)
                ->where('fullfield', 'not like', 'fullfield')
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
        $this->ba->store()->attach($this->newStore);
        $baCount = count($this->ba->fresh()->store);
        if ($baCount > 1) $this->ba->update(['status' => 'mobile']);
        $this->ba->fresh()->store->map(function ($item) use ($baCount) {
            $updateBrand = $this->alokasiBa(Brand::find($this->brandUpdate)->name);
            $updateData = [];
            $updateAllocation = ( $item[$updateBrand] == 0 ) ? 1 / $baCount :  (1 / $baCount) + ($item[$updateBrand] - 1 );
            $updateData[$updateBrand] = $updateAllocation;

            if ($updateBrand != $this->request->get('brandId') && $item->id != $this->newStore) {
                $reduceBrand =  $this->alokasiBa($this->request->get('brand'));
                $updateData[$reduceBrand] = $item[$reduceBrand] -= 1;
                $this->triggerRolling($item->id, 0, $this->ba, false, true, true);
            } else if ($item->id == $this->newStore && $updateBrand != $this->request->get('brandId') && BaSummary::hasEmptySpot($item->id, $updateBrand)->first() == null ) {
                $this->triggerRolling(0, $this->newStore, $this->ba, false, true);
            } else if ($item->id == $this->newStore && BaSummary::hasEmptySpot($item->id, $updateBrand)->first() != null) {
                $this->triggerRolling(0, $this->newStore, $this->ba, false);
            }

            Store::find($item->id)->update($updateData);
        });
    }

    /**
     * Basic Rolling Stuff ^.^
     *
     */
    private function basicRolling ()
    {
        $brandName = Brand::find($this->brandUpdate)->name;
        $updateBrand = $this->alokasiBa($brandName);
        $brandCount = $this->alokasiCount($brandName);
        $newStoreAllocation = Store::actualBa($this->newStore)->first();
        if ($newStoreAllocation[$updateBrand] - $newStoreAllocation[$brandCount] == 0 && count($this->replaceBa) == 0){
            $newStoreAllocation->update([$updateBrand => $newStoreAllocation[$updateBrand] += 1]);
            $this->triggerRolling($this->oldStore, $this->newStore, $this->ba, true, true);
        } else {
            $this->triggerRolling($this->oldStore, $this->newStore, $this->ba);
        }
        $this->ba->store()->updateExistingPivot($this->oldStore, ['store_id' => $this->newStore]);


        $wip = WIP::create(['store_id' => $this->oldStore, 'ba_id' => $this->ba->id, 'status' => 'replacement',
                            'brand_id' => $this->request->get('brandId'), 'fullfield' => 'hold', 'reason' => $this->alasanBa ,
                            'filling_date' => $this->request->get('pengajuanRequest'), 'effective_date' => $this->request->get('firstDate')]);
        if ($this->differentReo) {
            WIP::where('id', $wip->id)->update(['pending' => 1]);
        }
    }

    /**
     * Fulfilled Wip if should
     *
     */
    private function fulfilledWipIfShould()
    {
        if (count($this->replaceBa) != 0) {
            $this->replaceBa->filter(function ($item)  {
                return $item->brand_id == $this->brandUpdate;
            })->map(function ($item)  {
                $this->fulfilledWip($item, $this->ba, $this->request, $this->differentReo);
            });
        } else if (count($this->replaceBa) == 0 &&isset($wip)) {
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
     * @param $replaceBa
     * @param $ba
     * @param $request
     * @param $differentReo
     */
    public function fulfilledWip($replaceBa, $ba, $request, $differentReo)
    {
        $replaceName =  $this->replaceName($replaceBa->ba, Ba::findOrFail($request->replaceBaId)->name);
        $reason = 'Rolling dari ' . $request->tokoName . $replaceName . ' Dengan Status ' . $request->statusRolling;
        $replace = Replace::create(['ba_id' => $ba->id, 'description' => $reason, 'status' => 'Rolling dari Toko lain']);
        $updateArray = ['replace_id' => $replace->id, 'fullfield' => 'fullfield', 'ba_id' => $ba->id, 'reason' => $reason];
        if ($differentReo) {
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
        BaHistory::create(['ba_id' => $ba->id, 'status' => 'rolling_in', 'store_id' => $newStore]);
        if (!$createNewConfig) {
            BaSummary::where('store_id', $newStore)
                ->where('brand_id', $ba->brand_id)->where('ba_id', 0)
                ->where('month', Carbon::now()->month)
                ->where('year', Carbon::now()->year)
                ->first()
                ->update(['ba_id' => $ba->id]);
        } else if ($createNewConfig && !$changeOldStoreBrand) {
            BaSummary::create(['store_id' => $newStore, 'ba_id'  => $ba->id ,'brand_id' => $ba->brand_id, 'month' => Carbon::now()->month, 'year' => Carbon::now()->year]);
        }
        if ($changeOldStoreBrand) {
            BaSummary::where('store_id', $oldStore)
                ->where('ba_id', $ba->id)
                ->where('month', Carbon::now()->month)
                ->where('year', Carbon::now()->year)
                ->first()
                ->update(['brand_id' => $ba->brand_id]);
        }
        if ($removeOldStore) {
            BaHistory::create(['ba_id' => $ba->id, 'status' => 'rolling_out', 'store_id' => $oldStore]);
            BaSummary::where('store_id', $oldStore)->where('ba_id', $ba->id)
                ->where('month', Carbon::now()->month)
                ->where('year', Carbon::now()->year)
                ->first()
                ->update(['ba_id' => 0]);
        }
    }
}
