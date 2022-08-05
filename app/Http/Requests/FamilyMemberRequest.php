<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FamilyMemberRequest extends FormRequest
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
        return [
            'family_card_id' => ['required'],
            'nama' => ['required', 'string'],
            'nik' => ['required', 'string'],
            'tanggal_lahir' => ['required'],
            'tempat_lahir' => ['required'],
            'jenis_kelamin' => ['required', 'string'],
            'agama' => ['required', 'string'],
            'pendidikan' => ['required', 'string'],
            'pekerjaan' => ['required', 'string'],
            'golongan_darah' => ['required', 'string'],
            'isFamilyHead' => ['required'],
        ];
    }
}
