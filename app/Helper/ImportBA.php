<?php
/**
 * Created by PhpStorm.
 * User: achson
 * Date: 08/12/2016
 * Time: 14.05
 */

namespace App\Helper;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ImportBA extends \Maatwebsite\Excel\Files\ExcelFile
{
    public function getFile()
    {
        $file = Input::file('file');
        $file_orig = time() . '-' . $file->getClientOriginalName();
        $hcFile = $file->move('attachment/hc', $file_orig);
        return $hcFile;
    }

    public function getFilters()
    {
        return [
            'chunk'
        ];
    }
}