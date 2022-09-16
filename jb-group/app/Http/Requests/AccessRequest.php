<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessRequest extends FormRequest
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
            'access_id' => 'unique:role_accesses,access_id,NULL,id,role_id,' . $this->get('role_id'),
            'role_id'   => 'unique:role_accesses,role_id,NULL,id,access_id,' . $this->get('access_id'),
        ];
    }
}
