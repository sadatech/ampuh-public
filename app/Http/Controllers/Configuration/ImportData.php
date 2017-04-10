<?php

namespace App\Http\Controllers\Configuration;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ImportData extends Controller
{
    /**
     * @var Buat nampung data dari excel nya
     */
    protected $hcs;

    public function index()
    {
        return view('configuration.baImport');
    }

    /**
     * Olah data dari excel ke db nya
     *
     * @param Request $request
     * @return mixed
     */
    public function import(Request $request)
    {
        $file = $request->file('file');
        $file_orig = time() . '-' . $file->getClientOriginalName();
        $hcFile = $file->move('attachment/hc', $file_orig);

        $HCs = Excel::selectSheets('Alldata')->load($hcFile)->get();
        return $HCs;
        $data = [];
        foreach ($HCs->first() as $key => $hc) {
            $data['nik'] = $hc->nik;
            $data['cost_center'] = $hc->cost_center; //Ini divisi ya ?
            $data['position'] = $hc->position;

            /*Tentuin dia ba, employee atau distritor*/
            $data['ba'] = $hc->ba;
            $data['non_ba'] = $hc->non_ba;
            $data['distributor'] = $hc->distributor;


            $data['gender'] = $hc->gender;
            $data['birth_date'] = $hc->birth_date;
            $data['join_date'] = $hc->join_date;

            /**
             * ??
             */
            $data['ba_in'] = $hc->ba_in;
            $data['non_ba_in'] = $hc->non_ba_in;
            $data['departure_date'] = $hc->departure_date;
            $data['ba_out'] = $hc->ba_out;
            $data['non_ba_out'] = $hc->non_ba_out;


            $data['marketing'] = $hc->marketing;
            $data['sales'] = $hc->sales;
            $data['finance'] = $hc->finance;
            $data['supply_chain'] = $hc->supply_chain;
            $data['others'] = $hc->others;

            $data['dss'] = $hc->dss;
            $data['ss'] = $hc->ss;
            $data['traffic'] = $hc->traffic;
            $data['fa'] = $hc->fa;
            $data['strad'] = $hc->strad;
            $data['staff'] = $hc->staff;

            $data['gen_education'] = $hc->gen_education;
            $data['edu_ba'] = $hc->edu_ba;
            $data['edu_dss'] = $hc->edu_dss;
            $data['edu_ss'] = $hc->edu_ss;
            $data['edu_traffic'] = $hc->edu_traffic;
            $data['edu_fa'] = $hc->edu_fa;
            $data['edu_strad'] = $hc->edu_strad;
            $data['edu_staff'] = $hc->edu_staff;


            $data['division'] = $hc->division;
            $data['brand'] = $hc->brand;


            $data['marketing_cpd_loreal_paris'] = $hc->marketing_cpd_loreal_paris;
            $data['marketing_cpd_maybelline_ny'] = $hc->marketing_cpd_maybelline_ny;
            $data['marketing_cpd_garnier'] = $hc->marketing_cpd_garnier;
            $data['marketing_ppd_loreal_profesional'] = $hc->marketing_ppd_loreal_profesional;
            $data['marketing_ppd_kerastase'] = $hc->marketing_ppd_kerastase;
            $data['marketing_ppd_matrix'] = $hc->marketing_ppd_matrix;

            $data['sales_cpd'] = $hc->sales_cpd;
            $data['sales_loreal_profesional'] = $hc->sales_loreal_profesional;
            $data['sales_kerastase'] = $hc->sales_kerastase;
            $data['sales_matrix'] = $hc->sales_matrix;

            $data['ba_loreal_paris'] = $hc->ba_loreal_paris;
            $data['ba_maybelline_ny'] = $hc->ba_maybelline_ny;
            $data['ba_garnier'] = $hc->ba_garnier;
            $data['ba_lancome'] = $hc->ba_lancome;
            $data['ba_pci'] = $hc->ba_pci;
            $data['ba_urban_decay'] = $hc->ba_urban_decay;
            $data['ba_shu_uemura'] = $hc->ba_shu_uemura;
            $data['ba_ysl'] = $hc->ba_ysl;
            $data['ba_khiels'] = $hc->ba_khiels;

            $data['marketing_lancome'] = $hc->marketing_lancome;
            $data['marketing_pci'] = $hc->marketing_pci;
            $data['marketing_biotherm'] = $hc->marketing_biotherm;
            $data['marketing_shu_uemura'] = $hc->marketing_shu_uemura;
            $data['marketing_ysl'] = $hc->marketing_ysl;
            $data['marketing_khiels'] = $hc->marketing_khiels;

            $data['sales_lancome'] = $hc->sales_lancome;
            $data['sales_pci'] = $hc->sales_pci;
            $data['sales_biotherm'] = $hc->sales_biotherm;
            $data['sales_shu_uemura'] = $hc->sales_shu_uemura;
            $data['sales_ysl'] = $hc->sales_ysl;
            $data['sales_khiels'] = $hc->sales_khiels;

            $data['others_kerastase'] = $hc->others_kerastase;
            $data['others_matrix'] = $hc->others_matrix;
            $data['others_cpd'] = $hc->others_cpd;

            $data['out_date'] = $hc->out_date;
            $data['status'] = $hc->status;
            $data['area'] = $hc->area;

            if( $data['ba'] == 1) {
                echo ('bas');
            }

            echo ('employee');
            echo '<hr>';
        }

        return true;
    }
}
