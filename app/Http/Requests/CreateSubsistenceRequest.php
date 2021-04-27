<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubsistenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'code' => 'required|unique:eform_subsistence',
            'absc_absent_to' => 'required|date',
            'absc_absent_from' => 'required|date',
            'absc_visited_place_reason' => 'required',
            'absc_visited_place' => 'required',
            'absc_allowance_per_night' => 'required',
            'station' => 'required',
        ];
    }
}
