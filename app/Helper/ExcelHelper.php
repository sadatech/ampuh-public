<?php

namespace App\Helper;


use App\Ba;
use App\WIP;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ExcelHelper
{
    /**
     * ExcelHelper constructor.
     */
    public function __construct()
    {

    }


    /**
     * Merubah format data hasil query menjadi hasil yang di download di excel
     *
     * @param Collection $collection
     * @return \Illuminate\Support\Collection
     */
    public function mapForExcel(Collection $collection)
    {
        return $collection->map(function ($item) {
            return [
                'No' => @$item->id,
                'Year' => @$item->year,
                'Month' => $this->changeMonthFormat(@$item->month),
                'Nip' => $this->exist(@$item['ba'], @$item['ba']['nik']),
                'No Ktp' => $this->exist(@$item['ba'], @$item['ba']['no_ktp'] . ''),
                'Provinsi' => @$item->store->city->province_name,
                'Nama Ba' => $this->exist(@$item['ba'], @$item['ba']['name'], 'vacant'),
                'No Hp' => $this->exist(@$item['ba'], @$item['ba']['no_hp']),
                'Kota' => @$item->store->city->city_name,
                'Cabang Arina' => $this->exist(@$item['ba'], @$item['ba']['arinaBrand']['cabang']),
                'Nama Reo' => @$item['store']['reo']['user']['name'],
                'Region' => @$item->store->region->name,
                'Brand' => @$item->brand->name,
                'Store_no' => @$item->store->store_no,
                'Customer Id' => @$item->store->customer_id,
                'Store Name 1' => @$item->store->store_name_1,
                'Store Name 2' => @$item->store->store_name_2,
                'Channel' => @$item->store->channel,
                'Account' => @$item->store->account->name,
                'Status' => $this->exist(@$item['ba'], @$item['ba']['status']),
                'Join Date' => $this->exist(@$item['ba'], $this->readableDateFormat(@$item['ba']['join_date'])),
                'Join Date MDS' => $this->exist(@$item['ba'], $this->joinDateMdsCheck(@$item['ba'])),
                'Size Baju' => $this->exist(@$item['ba'], @$item['ba']['uniform_size']),
                'Keterangan' => $this->defineStore(@$item),
                'Masa Kerja' => (isset($item['ba'])) ? $this->exactTime(Carbon::now()->diff(new \DateTime(@$item['ba']['join_date']))) : '-',
                'Class' => $this->exist(@$item['ba'], @$item['ba']['class']),
                'Jenis Kelamin' => $this->exist(@$item['ba'], @$item['ba']['genders']),
                'Status Sp' => ($item['ba']['sp_approval'] == 'approve' && isset($item['ba'])) ? @$item['ba']['status_sp'] : '',
                'Tanggal Sp' => ($item['ba']['sp_approval'] == 'approve' && isset($item['ba'])) ? $this->readableDateFormat(@$item['ba']['tanggal_sp']) : '',
                'Hc' => ($item->store_count > 0) ? round(1 / $item->store_count, 2) : '-'
            ];
        });
    }

    /**
     * Merubah format data hasil query menjadi hasil yang akan disimpan di log db
     *
     * @param Collection $collection
     * @param $year
     * @param $month
     * @param $configurationFile
     * @return array
     */
    public function saveBaConfigLog(Collection $collection, $year, $month, $configurationFile)
    {
        return $collection->map(function ($item) use ($year, $month, $configurationFile) {
            return [
                'no' => $this->isVacant($item->globalId),
                'year' => $year,
                'month' => $month,
                'konfigurasi' => $configurationFile,
                'nik' => $this->isVacant($item->nik),
                'no_ktp' => $this->isVacant($item->no_ktp),
                'provinsi' => $item->province_name,
                'name' => $this->isVacant($item->name),
                'no_hp' => $this->isVacant($item->no_hp),
                'kota' => $item->city_name,
                'nama_reo' => $item->reo_name,
                'region' => $item->region_name,
                'brand' => $this->isVacant($item->brand_name),
                'store_no' => $item->store_no,
                'customer_id' => $item->customer_id,
                'store_name_1' => $item->store_name_1,
                'store_name_2' => $item->store_name_2,
                'channel' => $item->channel,
                'account' => $item->account_name,
                'status' => $this->isVacant($item->status),
                'join_date' => $this->isVacant($item->join_date),
                'size_baju' => $this->isVacant($item->uniform_size),
                'jumlah_seragam' => $this->isVacant($item->total_uniform),
                'keterangan' => $this->isVacant($item->description),
                'masa_kerja' => (isset($item->join_date)) ? $this->exactTime(Carbon::now()->diff(new \DateTime($item->join_date))) : 'vacant',
                'class' => $this->isVacant($item->class),
                'jenis_kelamin' => $this->isVacant($item->gender),
                'hc' => ($item->store_count > 0) ? round(1 / $item->store_count, 2) : 'vacant',
                'ba_id' => $this->isVacant($item->ba_id),
                'store_id' => $this->isVacant($item->store_id),
                'agency' => $this->isVacant($item->agencyName),
                'shipping_id' => $item->shipping_id,
                'status_sp' => $item->status_sp,
                'tanggal_sp' => $item->tanggal_sp,
                'sp_approval' => $item->sp_approval,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon::now('Asia/Jakarta')
            ];
        })->toArray();
    }

    /**
     * Set field untuk di export menuju excel
     *
     * @param Collection $collection
     * @param $year
     * @param $month
     * @return \Illuminate\Support\Collection
     */
    public function mapForStoreExcel(Collection $collection, $year, $month)
    {
        return $collection->map(function ($item) use ($year, $month) {
            return [
                'No' => $item->id,
                'Tahun' => $year,
                'Bulan' => $this->changeMonthFormat($month),
                'Store No' => $item->store_no,
                'Cust Id' => $item->customer_id,
                'Shipping Id' => ' ',
                'Store Name 1' => $item->store_name_1,
                'Store Name 2' => $item->store_name_2,
                'Channel' => $item->channel,
                'Account' => $this->exist($item['account'], $item['account']['name'], ' '),
                'Provinsi' => $item->city->province_name,
                'Alokasi Ba OAP' => $item->alokasi_ba_oap,
                'Alokasi Ba MYB' => $item->alokasi_ba_myb,
                'Alokasi Ba GAR' => $item->alokasi_ba_gar,
                'Alokasi Ba CONS' => $item->alokasi_ba_cons,
                'Alokasi Ba MIX' => $item->alokasi_ba_mix,
                'Actual Ba OAP' => $this->actualBrandCount($item->alokasi_ba_oap, $item->oap_count),
                'Actual Ba MYB' => $this->actualBrandCount($item->alokasi_ba_myb, $item->myb_count),
                'Actual Ba GAR' => $this->actualBrandCount($item->alokasi_ba_gar, $item->gar_count),
                'Actual Ba CONS' => $this->actualBrandCount($item->alokasi_ba_cons, $item->cons_count),
                'Actual Ba MIX' => $this->actualBrandCount($item->alokasi_ba_mix, $item->mix_count),
                'Kota' => $item->city->city_name,
                'Nama Supervisor' => (isset($item->reo)) ? $item->reo->user->name : ' ',
                'Region' => $item->region->name
            ];
        });
    }


    /**
     * Mapping data konfigurasi untuk disimpan ke dalam database history
     *
     * @param Collection $collection
     * @param $year
     * @param $month
     * @param $konfigurasiName
     * @return array
     */
    public function saveStoreConfigLog(Collection $collection, $year, $month, $konfigurasiName)
    {
        return $collection->map(function ($item) use ($year, $month, $konfigurasiName) {
            return [
                'created_at' => Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon::now('Asia/Jakarta'),
                'year' => $year,
                'month' => $month,
                'konfigurasi' => $konfigurasiName,
                'store_id' => $item->id,
                'alokasi_ba_oap' => $item->alokasi_ba_oap,
                'alokasi_ba_myb' => $item->alokasi_ba_myb,
                'alokasi_ba_gar' => $item->alokasi_ba_gar,
                'alokasi_ba_cons' => $item->alokasi_ba_cons,
                'alokasi_ba_mix' => $item->alokasi_ba_mix,
                'oap_count' => $item->oap_count,
                'myb_count' => $item->myb_count,
                'gar_count' => $item->gar_count,
                'cons_count' => $item->cons_count,
                'mix_count' => $item->mix_count,
            ];
        })->toArray();
    }

    /**
     * Check kosong ga valuenya kalau kosong berarti vacant karena pake right join di toko
     *
     * @param $value
     * @return string
     */
    public function isVacant($value)
    {
        return ($value) ?: 'vacant';
    }

    /**
     * Helper untuk mengganti format bulan dari angka menjadi readable untuk di excel
     *
     * @param $value
     * @return \Illuminate\Support\Collection
     */
    public function changeMonthFormat($value)
    {
        $monthCollection = [
            ['name' => 'Januari', 'id' => 1], ['name' => 'Februari', 'id' => 2], ['name' => 'Maret', 'id' => 3], ['name' => 'April', 'id' => 4], ['name' => 'Mei', 'id' => 5], ['name' => 'Juni', 'id' => 6], ['name' => 'Juli', 'id' => 7], ['name' => 'Agustus', 'id' => 8], ['name' => 'September', 'id' => 9], ['name' => 'Oktober', 'id' => 10], ['name' => 'November', 'id' => 11], ['name' => 'Desember', 'id' => 12]
        ];
        return collect($monthCollection)->filter(function ($month) use ($value) {
            return $month['id'] == $value;
        })->map(function ($month) {
            return $month['name'];
        })->first();
    }

    public function mapWIPtoExcel(Collection $collection)
    {
        return $collection->map(function ($item) {
            return [
                'Store' => @$item->store->store_name_1,
                'Region' => @$item->store->city->region_id,
                'City' => @$item->store->city->city_name,
                'Brand' => @$item->brand->name,
                'Account' => (empty($item->store->account->name)) ? '' : $item->store->account->name,
                'Channel' => (empty($item->store->channel)) ? '' : $item->store->channel,
                'Status' => @($item->status == 'new store') ? 'New Allocation' : $item->reason,
                'fulfilled' => $this->fulfilledCheck($item),
                'Filling date' => @$this->readableDateFormat($item->filling_date),
                'Effective date' => @$this->readableDateFormat($item->effective_date),
                'Interview Date' => (empty($item->replacement->interview_date)  || $item->status == 'replacement' ) ?'' : $this->readableDateFormat($item->replacement->interview_date),
                'Status Interview' => (empty($item->replacement->status)) ?'': $item->replacement->status,
                'Candidate' => (empty($item->replacement)) ?'': $item->replacement->candidate,
                'Interview Description' => (empty($item->replacement->description)) ?'': $item->replacement->description,
                'BA' => (empty($item->ba->name)) ?'Vacant':$item->ba->name,
                'Head Count' => @$this->countHeadCountWip($item),
            ];
        });
    }

    /**
     * count how many head count is needed in the store
     *
     * @param $item
     * @return string
     */
    public function countHeadCountWip($item)
    {
        return number_format($item->head_count, 4) + 0;
    }

    /**
     * Cek jika sebuah item exist kalau ga exist maka bakal return data default
     *
     * @param $checkedItem
     * @param $item
     * @param string $returnNotes
     * @return string
     */
    public function exist($checkedItem, $item, $returnNotes = '-')
    {
        return (isset($checkedItem)) ? $item : $returnNotes;
    }

    /**
     * Format timestamp menjadi format carbon baru
     *
     * @param $date
     * @return string
     */
    public function readableDateFormat($date)
    {
        return Carbon::parse($date)->format('d-M-y');
    }


    /**
     * Elapsed Time yang jelas dari dateinterval dan di convert menjadi readable String
     *
     * @param \DateInterval $dateInterval
     * @return string
     */
    public function exactTime(\DateInterval $dateInterval)
    {
        return $dateInterval->y . ' Tahun ' . $dateInterval->m . ' Bulan ' . $dateInterval->d . ' Hari ';
    }

    /**
     * Check Whether the store in the brand has already got a mobile Ba by checking the allocation if decimal
     *
     * @param $allocation
     * @return bool
     */
    public function hasMobileBa($allocation)
    {
        return is_numeric($allocation) && floor($allocation) != $allocation;
    }

    /**
     * Helper for get the actual count following the allocation if there is mobile count and do nothing if there isn't a mobile Ba
     *
     * @param $allocation
     * @param $realCount
     * @return mixed
     */
    public function actualBrandCount($allocation, $realCount)
    {
        if ($this->hasMobileBa($allocation) && $realCount != 0 && $allocation < 1) {
            return $realCount * $allocation;
        }
        if ($this->hasMobileBa($allocation) && $realCount != 0 && $allocation > 1) {
            return ($realCount * $allocation) - ($allocation - floor($allocation));
        }
        return $realCount;
    }

    /**
     * Showing Keterangan which store the ba work in mobile
     *
     * @param $item
     * @return string
     */
    private function defineStore($item)
    {
        if (!isset($item->ba)) {
            return '';
        }
        $stores = Ba::with('store')->find($item->ba->id)->store;
        $keterangan = $item->ba->extra_keterangan;

        if (count($stores) > 1) {
            return $stores->reduce(function ($tally, $item) {
                    $tally .= $item->store_name_1 . ' , ';
                    return $tally;
                }, 'Ba Mobile di Toko ') . $keterangan;
        }
        return $keterangan;
    }

    private function fulfilledCheck($item)
    {
        if ($item->store->isHold == 1) {
            return 'Hold';
        }
        return ($item->fullfield != 'fullfield') ? ' ' : $item->fullfield;
    }

    /**
     * Mapping data for turnover
     *
     * @param $collection
     * @return mixed
     */
    public function mapForTurnOver($collection)
    {
        return $collection->map(function ($item) {
            return [
                'MONTH' => substr($this->readableDateFormat($item['resign_at']), 3),
                'NAMA' => $item['name'],
                'REGION' => $item['city']['region_id'],
                'KOTA' => $item['city']['city_name'],
                'STORE NO' => $this->implodeStoreData(collect($item['history']), 'store', ',', 'store_no'),
                'CUST ID' => $this->implodeStoreData(collect($item['history']), 'store', ',', 'customer_id'),
                'STORE NAME 1' => $this->implodeStoreData(collect($item['history']), 'store', ',', 'store_name_1'),
                'JOIN DATE' => $this->readableDateFormat($item['join_date']),
                'JOIN DATE MDS' => $this->readableDateFormat($item['join_date_mds']),
                'RESIGN DATE' => $this->readableDateFormat($item['resign_at']),
                'LAST DAY DI TOKO' => $this->readableDateFormat($item['resign_at']),
                'ALASAN RESIGN/DIRESIGNKAN' => $item['resign_reason'],
                'NAMA REO' => $this->implodeStoreData(collect($item['history']), 'store', ',', 'reo', 'user', 'name'),
                'KETERANGAN' => $item['resign_info'],
                'CHANNEL' => $this->implodeStoreData(collect($item['history']), 'store', ',', 'channel'),
                'ACCOUNT' => $this->implodeStoreData(collect($item['history']), 'store', ',', 'account', 'name'),
            ];
        });
    }

    /**
     * Mapping data for SP history Excel
     *
     * @param $collection
     * @return \Illuminate\Support\Collection
     */
    public function mapForSp($collection)
    {
        return $collection->map(function ($item) {
            return [
                'Nik' => $item['nik'],
                'Nama' => $item['name'],
                'Kota' => $item['city']['city_name'],
                'Provinsi' => $item['city']['province_name'],
                'Nama Toko' => $this->implodeStoreData(collect($item['store']), 'store_name_1'),
                'Account' => $this->implodeStoreData(collect($item['store']), 'account', ',', 'name'),
                'channel' => $this->implodeStoreData(collect($item['store']), 'channel'),
                'Status SP' => $item['status_sp'],
                'Tanggal SP' => $this->readableDateFormat($item['tanggal_sp'])
            ];
        });
    }

    /**
     * Implode store data to joined da
     *
     * @param $item
     * @param $key
     * @param string $implode
     * @param null $extraKey1
     * @return mixed
     */
    public function implodeStoreData($item, $key, $implode = ' , ', $extraKey1 = null, $extraKey2 = null, $extraKey3 = null)
    {
        return $item->map(function ($item) use ($key, $extraKey1, $extraKey2, $extraKey3) {
            if ($extraKey1 == null) {
                return $item[$key];
            } else if ($extraKey2 == null) {
                return $item[$key][$extraKey1];
            } else if ($extraKey3 == null) {
                return $item[$key][$extraKey1][$extraKey2];
            } else {
                return $item[$key][$extraKey1][$extraKey2][$extraKey3];
            }
        })->implode($implode);
    }


    /**
     * Get the MDS join date if exists
     *
     * @param $ba
     * @return string
     */
    private function joinDateMdsCheck($ba)
    {
        if ($ba['join_date_mds'] != null && $ba['join_date_mds'] != '0000-00-00') {
            return $this->readableDateFormat($ba['join_date_mds']);
        }
        return '';
    }

    public function mapForSDF($collection)
    {
        return $collection->get()->map(function ($item) {
            return [
                'NO SDF' => $item->no_sdf,
                'Store' => $item->store_name_1,
                'City' => $item->city_name,
                'Brand' => $item->brandName,
                'Number Of BA' => $item->numberOfBa,
                'Request Date' => $this->readableDateFormat($item->request_date),
                'First Day' => $this->readableDateFormat($item->first_date)
            ];
        });
    }

    public function mapAllBAExport(Collection $collection)
    {
        return $collection->map(function ($item) {
            return [
                'No' => @$item->id,
                'Nik' => $this->exist(@$item->nik, @$item->nik),
                'Nama BA' => $this->exist(@$item->name, @$item->name, 'vacant'),
                'No Ktp' => $this->exist(@$item->no_ktp,  @$item->no_ktp . ''),
                'Toko' => $this->implodeStoreData(collect($item->store), 'store_name_1'),
                'Channel' => @$item->store->first()->channel,
                'Account' =>  $this->implodeStoreData(collect($item->store), 'account', ',', 'name'),
                'Provinsi' => @$item->store->first()->city->province_name,
                'Kota' => @$item->store->first()->city->city_name,
                'No Hp' => $this->exist(@$item->no_hp, @$item->no_hp),
                'Jenis Kelamin' => $this->exist(@$item->gender, @$item->gender),
                'Brand' => @$item->brand->name,
                'Status' => $this->exist(@$item->status, @$item->status),
                'Join Date' => $this->exist(@$item->join_date, $this->readableDateFormat(@$item->join_date)),
                'Join Date MDS' => $this->exist(@$item->join_date_mds, $this->joinDateMdsCheck(@$item->join_date_mds)),
                'Size Baju' => $this->exist(@$item->uniform_size, @$item->uniform_size),
                'Masa Kerja' => $this->exactTime(Carbon::now()->diff(new \DateTime(@$item->join_date))),
                'Class' => $item->class,
            ];
        });
    }

}