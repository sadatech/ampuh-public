<?php

namespace App\Traits;

trait StringTrait {

    /**
     * Get alokasi ba with brand name
     *
     * @param $brandName
     * @return string
     */
    public function alokasiBa($brandName)
    {
        return 'alokasi_ba_' . strtolower($brandName);
    }

    /**
     * Decide Rolling Status
     *
     * @param $oldStore
     * @return string
     */
    public function rollingStatus($oldStore)
    {
        return  ($oldStore == 012344321) ? 'penambahan toko baru menuju ' : ' rolling menuju ';
    }

    /**
     * Alasan Perollingan Ba
     *
     * @param $nama
     * @param $status
     * @param $storeName
     * @param $statusRolling
     * @return string
     */
    public function alasanBa($nama, $status, $storeName, $statusRolling)
    {
//        return 'Ba ' . $nama . $status . $storeName . ' dengan status '. $statusRolling;
        return 'Replace BA ' . $nama ;
    }

    /**
     * Get rejoin Ba Reason
     *
     * @param $ba
     * @param $replaceName
     * @return string
     */
    public function alasanRejoinBa($ba, $replaceName)
    {
        return $replaceName != null ? 'Ba ' . $ba->name . ' Masuk kembali Menggantikan ' . $replaceName
                                    : 'Ba ' . $ba->name . ' Masuk kembali';
    }

    /**
     * Decide Ba status from rolling activity
     *
     * @param $request
     * @param $ba
     * @return string
     */
    public function decideStatus($request, $ba)
    {
        if (count($ba->fresh()->store) == 1 && $ba->status != 'rotasi') {
            return 'stay';
        }
        if (count($ba->fresh()->store) > 1 && $ba->status != 'rotasi') {
            return 'mobile';
        }
        if ($request['newReo']['userId'] == 'rotasi' && $ba->status != 'rotasi') {
            return 'rotasi';
        }
        if ($ba->status == 'rotasi' && is_numeric($request['newReo']['userId'])) {
            return 'stay';
        }
    }

    /**
     * Get brand Allocation Count
     *
     * @param $brandName
     * @return string
     */
    public function alokasiCount($brandName)
    {
        return strtolower($brandName) . '_count';
    }

    /**
     * Replace Name in WIP
     *
     * @param $ba
     * @param $name
     * @return string
     */
    public function replaceName($ba, $name)
    {
        return ($ba == null) ? ' Mengisi Toko Baru' : ' Menggantikan ' . $name;
    }

    /**
     * Transform resign reason ID into readable string format
     *
     * @param $id
     * @return string
     */
    public function decideResignReason($id)
    {
        switch ($id) {
            case 1: return 'Cut/Diresignkan';
            case 2: return 'Hamil';
            case 3: return 'Keperluan Keluarga/Keperluan Pribadi';
            case 4: return 'Sakit';
            case 5: return 'Mendapat Pekerjaan Baru';
            default: return 'Tanpa Input Alasan';
        }
    }

}