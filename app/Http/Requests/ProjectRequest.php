<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'logo'=>'required',
            'name'=>'required',
            'location'=>'required',
            'city_id'=>'required',
            'description'=>'required',
            'developed_by'=>'required',
            'developer_contact'=>'required',
        ];
    }
}
