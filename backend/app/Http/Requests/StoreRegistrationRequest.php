<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'social_name' => 'nullable|max:255',
            'cpf' => 'required|size:14|unique:registrations',
            'father_name' => 'nullable|max:255',
            'mother_name' => 'nullable|max:255',
            'phone' => 'required|max:15',
            'email' => 'required|email|max:255|unique:registrations',
        ];
    }
}
