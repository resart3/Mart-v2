<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        if($this->isMethod('post')){
            return [
                'family_card_id' =>['required'],
                'jumlah'         =>['required'],
                'tahun'          =>['required'],
                'bulan'          =>['required'],
            ];
        }else {
            return [
                'receipt'        =>[''],
                'status'         =>[''],
                'family_card_id' =>[''],
                'jumlah'         =>[''],
                'tahun'          =>[''],
                'bulan'          =>[''],
            ];
        }
    }
}
