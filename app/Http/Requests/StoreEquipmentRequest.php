<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentRequest extends FormRequest
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
        return [
            'equipments' => 'present|array',
            'equipments.*.type' => 'required|unique:equipment',
            'equipments.*.mask' => [
                'required','min:10','string',
                'regex:/^[A-Z0-9]{2}[A-Z]{5}[A-Z0-9][A-Z]{2}|[0-9][A-Z0-9]{2}[A-Z]{2}[A-Z0-9][-_,@][A-Z0-9][a-z]{2}|[0-9][A-Z]{4}[A-Z0-9][-_,@][A-Z0-9]{3}/',
            ],
        ];
    }
}
