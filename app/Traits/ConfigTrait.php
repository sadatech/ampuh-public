<?php

namespace App\Traits;

use App\Ba;
use App\BaKonfigLog;
use App\Console\Commands\BaConfiguration;
use App\Helper\ExcelHelper;
use App\Store;
use App\BaSummary;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;

trait ConfigTrait
{

    /**
     * Like the function said traveling to all store
     *
     * @param bool $needSaveStoreCount
     */
    public function travelAllStore($needSaveStoreCount = false)
    {
        Store::travelToAllStore()->get()->map(function ($item) use ($needSaveStoreCount) {
            if (ceil($item->cons) != 0) {
                $this->calculateBaConfig($item, 'cons', 1, $needSaveStoreCount);
            }
            if (ceil($item->oap) != 0) {
                $this->calculateBaConfig($item, 'oap', 2, $needSaveStoreCount);
            }
            if (ceil($item->gar) != 0) {
                $this->calculateBaConfig($item, 'gar', 4, $needSaveStoreCount);
            }
            if (ceil($item->myb) != 0) {
                $this->calculateBaConfig($item, 'myb', 5, $needSaveStoreCount);
            }
            if (ceil($item->mix) != 0) {
                $this->calculateBaConfig($item, 'mix', 6, $needSaveStoreCount);
            }
        });
    }

    /**
     * Kalkulasi dan showing data BA yang ada dan yang vacant
     *
     * @param $item
     * @param $brandName
     * @param $brandId
     * @param $needSaveStoreCount
     * @return string
     */
    private function calculateBaConfig($item, $brandName, $brandId, $needSaveStoreCount)
    {
        $month = $needSaveStoreCount ? Carbon::now()->subMonth()->month : Carbon::now()->month;
        $year = $needSaveStoreCount && $month == 1 ? Carbon::now()->subYear()->year : Carbon::now()->year;
        $i = intval(ceil($item[$brandName]));


        $countBrand = Store::baFromStore($brandId, $item->storeId)->first();
        $storeData = ($countBrand->count() > 0) ? collect($countBrand) : collect(Store::with('city', 'reo.user', 'region', 'account')->find($item->storeId));
        $countBrand->haveBa->filter(function ($item) use ($brandId) {
            return Ba::find($item->pivot->ba_id)->brand_id == $brandId;
        })->map(function ($item) use ($brandId, $needSaveStoreCount, $countBrand, $month, $year) {
            if (!$needSaveStoreCount) {
                $data = ['ba_id' => $item->pivot->ba_id, 'store_id' => $item->pivot->store_id, 'brand_id' => $brandId, 'month' => $month, 'year' => $year];
            } else {
                $data = ['ba_id' => $item->pivot->ba_id, 'store_id' => $item->pivot->store_id, 'brand_id' => $brandId, 'month' => $month, 'year' => $year, 'store_count_static' => $countBrand['ba_count']];
            }
            BaSummary::create($data);
        });
        while ($i - $countBrand->ba_count != 0) {
            BaSummary::create(['ba_id' => 0, 'store_id' => $storeData['id'], 'brand_id' => $brandId, 'month' => $month, 'year' => $year]);
            --$i;
        }
        return 'berhasil jalan semua toko';
    }

    /**
     * Do Loop for every brand in the new inserted store
     *
     * @param $storeId
     * @return string
     */
    public function travelNewStore ($storeId) {
        $newStore = Store::travelOneStore($storeId)->first();
        if (ceil($newStore->cons) != 0) {
            $this->addAllocationForNewStore($newStore, 'cons', 1);
        }
        if (ceil($newStore->oap) != 0) {
            $this->addAllocationForNewStore($newStore, 'oap', 2);
        }
        if (ceil($newStore->gar) != 0) {
            $this->addAllocationForNewStore($newStore, 'gar', 4);
        }
        if (ceil($newStore->myb) != 0) {
            $this->addAllocationForNewStore($newStore, 'myb', 5);
        }
        if (ceil($newStore->mix) != 0) {
            $this->addAllocationForNewStore($newStore, 'mix', 6);
        }
        return 'traveling satu store saja';
    }

    /**
     * Kalkulasi dan showing data BA yang ada dan yang vacant pada toko baru
     *
     * @param $item
     * @param $brandName
     * @param $brandId
     * @return string
     */
    private function addAllocationForNewStore($item, $brandName, $brandId)
    {
        $i = intval(ceil($item[$brandName]));
        while ($i  != 0) {
            BaSummary::create(['ba_id' => 0, 'store_id' => $item['storeId'], 'brand_id' => $brandId, 'month' => Carbon::now()->month, 'year' => Carbon::now()->year]);
            --$i;
        }
        return 'berhasil jalan semua toko';
    }

    /**
     * Input data trigger ba ketika dari sdf melebihi quota
     *
     * @param $item
     * @param $count
     * @param $brandId
     * @return string
     */
    private function addAllocationFromSdf($item, $count, $brandId)
    {
        while ($count  != 0) {
            BaSummary::create(['ba_id' => 0, 'store_id' => $item->id, 'brand_id' => $brandId, 'month' => Carbon::now()->month, 'year' => Carbon::now()->year]);
            --$count;
        }
        return 'berhasil jalan semua toko';
    }

    /**
     * Save history data to excel
     *
     */
    public function saveConfigData()
    {
        $excelHelper = new ExcelHelper();
        $fileName = 'Data Konfigurasi BA_'. Carbon::now()->month . '_' . Carbon::now()->year;
        $configurationData = Store::HistoryConfiguration()->get();
        Excel::create($fileName, function ($excel) use ($configurationData)  {
            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->sheet('Konfig Ba',function ($sheet) use ($configurationData)  {
                $sheet->setAutoFilter('A1:AC1');
                $sheet->setHeight(1,25);
                $sheet->fromModel($this->excelHelper->mapForExcel($configurationData), null, 'A1', true, true);
                $sheet->row(1, function ($row) {
                    $row->setBackground('#82abde');
                });
                $sheet->cells('A1:AC1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setBorder('A1:AC1', 'thin');
            });
        })->store('xlsx', false, true);
        BaKonfigLog::insert($excelHelper->saveBaConfigLog($configurationData, Carbon::now()->year, Carbon::now()->month, $fileName .'.xlsx'));
    }

    /**
     * Delete the takeout data where the BA is being takeout from the Store and reduce the allocation
     *
     * @param $storeId
     * @param $brandId
     * @param $baId
     */
    public function removeTakeoutFromConfiguration($storeId, $brandId, $baId)
    {
       return BaSummary::where('store_id', $storeId)
                      ->where('brand_id', $brandId)
                      ->where('ba_id', $baId)
                      ->where('month', Carbon::now()->month)
                      ->where('year', Carbon::now()->year)
                      ->delete();

    }

    /**
     * Copy old month configuration and make it the current month configuration
     *
     * @param $newMonth
     * @param $newYear
     * @return mixed
     */
    public function updateCurrentConfiguration($newMonth, $newYear)
    {
        $oldMonth = $newMonth - 1;

        if ($newMonth == 1) {
            $newYear -= 1;
            $oldMonth = 12;
        }

        return BaSummary::where('month',$oldMonth)
                        ->where('year', $newYear)
                        ->update([
                            'month' => $newMonth,
                            'year' => $newYear
                        ]);
    }

    /**
     * Remove the Store for the Store Configuration
     *
     * @param $storeId
     * @return mixed
     */
    public function removeForStoreConfiguration($storeId)
    {
        return Store::find($storeId)->update(['isHold' => 1]);
    }
}