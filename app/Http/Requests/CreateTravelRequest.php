<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTravelRequest extends FormRequest
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
            'traveler_id' => 'required',
            'reporting_user_id' => 'required',
            'approval_user_id' => 'required',
            'departure_branch_id' => 'required',
            'destination_branch_id' => 'required',
            'project_name' => 'required',
            'client_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'client_id.required' => "Client is required",
            'traveler_id.required' => "Traveler is required",
            'approval_user_id.required' => "Approval user is required",
            'departure_branch_id.required' => "Departure is required",
            'destination_branch_id.required' => "Destination type is required",
            'project_name.required' => "Project name is required"
        ];
    }
}
