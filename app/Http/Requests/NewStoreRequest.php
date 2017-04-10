<?php

namespace App\Http\Requests;

use App\Activities;
use App\Notification;
use App\SDF;
use App\Store;
use App\WIP;
use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NewStoreRequest extends FormRequest
{
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
        return
            [
                '*.no_sdf' => 'sometimes|required|unique:sdfs',
                '*.request_date' => 'sometimes|required',
                '*.first_date' => 'sometimes|required',
                '*.store_no' => 'sometimes|required|unique:stores',
                '*.customer_id' => 'sometimes|required',
                '*.store_name_1' => 'sometimes|required|unique:stores',
                '*.channel' => 'sometimes|required',
                '*.account' => 'sometimes|required',
                '*.city' => 'sometimes|required',
                '*.attachment' => 'sometimes|required|mimes:pdf'
            ];
    }

    /**
     * Custom error message untuk validasi data new store and sdf
     *
     * @return array
     */
    public function messages()
    {
        return [
            '*.no_sdf.unique' =>  'Nomor Sdf Tersebut Telah digunakan',
            '*.no_sdf.required' => 'Harap mengisi Nomor Sdf',
            '*.store_name_1.unique' => 'Nama Toko Tersebut Telah digunakan',
            '*.store_name_1.required' => 'Harap mengisi Nama Toko',
            '*.store_no.unique' => 'Nama Store No Tersebut Telah digunakan',
            '*.store_no.required' => 'Harap mengisi Nama Toko',
            '*.request_date.required' => 'Harap mengisi Tanggal Request Date',
            '*.first_date.required' => 'Harap mengisi Tanggal First_date',
            '*.customer_id.required' => 'Harap mengisi Nomor Customer Id',
            '*.channel.required' => 'Harap mengisi Channel Toko',
            '*.account.required' => 'Harap mengisi Account Toko',
            '*.city.required' => 'Harap mengisi Kota Toko',
            '*.attachment.mimes' => 'Hanya Dapat menginput PDf untuk Attachment Sdf',
            '*.attachment.required' => 'Harap mengupload attachment Sdf'
        ];
    }

    /**
     * Creating New Store and integrate with Sdf And Wip
     *
     */
    public function newStore()
    {
        $requestSize = $this->get('size');
        $token = str_random(20);

        foreach (range(1, intval($requestSize)) as $storeData) {
            $fillableStore = $this->get($storeData);
            $fillableStore['account_id'] = $fillableStore['account'];
            $fillableStore['city_id'] = $fillableStore['city'];
            $fillableStore['region_id'] = $fillableStore['region'];
            $fillableStore['created_by'] = Auth::id();
            $fillableStore['updated_by'] = 0;
            $fillableStore['deleted_by'] = 0;
            $fillableStore['request_date'] = DateTime::createFromFormat('d/m/Y', $fillableStore['request_date'])->format('Y-m-d');
            $fillableStore['first_date'] = DateTime::createFromFormat('d/m/Y', $fillableStore['first_date'])->format('Y-m-d');
            $fillableStore['shipping_id'] = 0;

            if ($requestSize > 1) {
                $fillableStore['alokasi_ba_oap'] = ($fillableStore['alokasi_ba_oap'] != '') ? $fillableStore['alokasi_ba_oap'] / $requestSize : 0;
                $fillableStore['alokasi_ba_myb'] = ($fillableStore['alokasi_ba_myb'] != '') ? $fillableStore['alokasi_ba_myb'] / $requestSize : 0;
                $fillableStore['alokasi_ba_gar'] = ($fillableStore['alokasi_ba_gar'] != '') ? $fillableStore['alokasi_ba_gar'] / $requestSize : 0;
                $fillableStore['alokasi_ba_cons'] = ($fillableStore['alokasi_ba_cons'] != '') ? $fillableStore['alokasi_ba_cons'] / $requestSize : 0;
                $fillableStore['alokasi_ba_mix'] = ($fillableStore['alokasi_ba_mix'] != '') ? $fillableStore['alokasi_ba_mix'] / $requestSize : 0;
            }
            else if (isset($fillableStore['sdfPairId'])) {
                $sdf = SDF::with('brand')->find($fillableStore['sdfPairId']);
                $fillableStore['alokasi_ba_oap'] = $this->sdfSameBrand(2, $sdf->brand);
                $fillableStore['alokasi_ba_myb'] = $this->sdfSameBrand(5, $sdf->brand);
                $fillableStore['alokasi_ba_gar'] = $this->sdfSameBrand(4, $sdf->brand);
                $fillableStore['alokasi_ba_cons'] = $this->sdfSameBrand(1, $sdf->brand);
                $fillableStore['alokasi_ba_mix'] = $this->sdfSameBrand(6, $sdf->brand);
            }

            DB::transaction(function () use ($fillableStore, $storeData, $token) {

                //Create Store
                $store = Store::create($fillableStore);

                //Create SDF
                if (isset($fillableStore['sdfPairId'])) {

                    $pairSdf = SDF::with('brand')->find($fillableStore['sdfPairId']);

                    $sdf = $this->createSDF($fillableStore, $store->id, $this->file($storeData)['attachment'], $pairSdf->token);

                } else {

                    $sdf = $this->createSDF($fillableStore, $store->id, $this->file($storeData)['attachment'], $token);

                }

                //Make relation between sdf and brands
                $this->attachBrands($sdf, $store);

            });
        }
    }

    /**
     *  Bikin SDF Berdasarkan Store yang dibuat
     *
     * @param $sdfs
     * @param $storeId
     * @param $file
     * @param $token
     * @return mixed
     */
    private function createSDF($sdfs, $storeId, $file, $token)
    {
        $sdfs['store_id'] = $storeId;
        $sdfAttachment = $file;
        $sdfAttachmentOrig = time() . '-' . $sdfAttachment->getClientOriginalName();
        $sdfAttachment->move('attachment/sdf', $sdfAttachmentOrig);
        $sdfs['attachment'] = $sdfAttachmentOrig;
        $sdfs['token'] = $token;
        $sdf = SDF::create($sdfs);
        $this->broadcastToArea($sdf);
        return $sdf;
    }
    public function broadcastToArea($sdf)
    {
        $store = Store::find($sdf->store_id)->store_name_1;
        $store_id = $sdf->store_id;
        $first_date = $sdf->first_date;
        $city = Store::with('city')->find($store_id)->city->id;

        $notification = Notification::create([
            'name' => "New SDF ". $store,
            'message' => 'SDF Baru telah di buat untuk toko '. $store,
            'status' => 'new',
            'role' => 'aro',
            'read' => 0,
            'firs_date' => $first_date,
            'icon' => 'fa fa-plus'
        ]);
        $act = Activities::create([
            'activity' => 'Create new Store  ',
            'type' => 'App\Store',
            'relations_id' => $store_id,
            'user_id' => Auth::id()
        ]);
        $notification->sdf()->attach($sdf, ['city_id' => $city]);
    }

    /**
     * Nyambungin antara SDF sama Brands
     *
     * @param $sdfs
     * @param $store
     */
    private function attachBrands($sdfs, $store)
    {
        //Define static brands
        $brands[1] = $store['alokasi_ba_cons'];
        $brands[2] = $store['alokasi_ba_oap'];
        $brands[4] = $store['alokasi_ba_gar'];
        $brands[5] = $store['alokasi_ba_myb'];
        $brands[6] = $store['alokasi_ba_mix'];

        $sdf = SDF::find($sdfs->id);
        foreach ($brands as $id => $brand) {
            if ($brand != 0) {
                $sb['brand_id'] = $id;
                $sb['numberOfBa'] = $brand;
                $sdf->brand()->attach($id, $sb);
                for ($i = 0; $i < intval(ceil($brand)); $i++) {
                    $headCount = ($this->hasMobileBa($brand)) ? $brand : 1;
                    $this->createWip($sdf, $sb['brand_id'], $sdfs->request_date, $sdfs->first_date, $headCount);
                }
            }
        }
    }

    /**
     * Bikin WIP
     *
     * @param $sdf
     * @param $brand_id
     * @param $request_date
     * @param $first_date
     * @param $brand
     */
    private function createWIP($sdf, $brand_id, $request_date, $first_date, $brand)
    {
        $wip = new WIP;
        $data = [
            'store_id' => $sdf->store_id,
            'brand_id' => $brand_id,
            'sdf_id' => $sdf->id,
            'status' => 'NEW STORE',
            'fullfield' => 'hold',
            'reason' => 'Alokasi BA baru',
            'effective_date' => $first_date,
            'filling_date' => $request_date,
            'head_count' => $brand,
            'pending' => 0
        ];
        $wip->create($data);
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
}
