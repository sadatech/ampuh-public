<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewBaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO bikin yang bisa input hanya REO
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
            'nik' => 'required',
            'name' => 'required',
            'no_ktp' => 'required',
            'no_hp' => 'required',
            'rekening' => 'required',
            'city_id' => 'required',
            'bank_name' => 'required',
            'status' => 'required',
            'join_date' => 'required',
            'agency_id' => 'required',
            'uniform_size' => 'required',
            'total_uniform' => 'required',
            'description' => 'required',
            'class' => 'required',
            'education' => 'required',
            'birth_date' => 'required',
        ];
    }
}
