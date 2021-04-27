<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountsChartsRequest extends FormRequest
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
            'name' => 'required|unique:config_chart_of_accounts',
            'code' => 'required',
            'description' => 'required',
        ];
    }
}
