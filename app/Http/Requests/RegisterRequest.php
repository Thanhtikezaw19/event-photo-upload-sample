<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'username' => 'required|string',
            'password' => 'required|string|min:6',
            'email' => 'required|string|email|unique:users',
            'role' => 'required|string',
        ];
    }

    public function messages() {
        return [
            'username.required' => 'Username is required',
            'username.string' => 'Username must be string',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be string',
            'password.min' => 'Password must be at least 6 characters',
            'role.required' => 'Role is required',
            'role.string' => 'Role must be string',
            'emaill.required' => 'Email is required',
            'email.string' => 'Email must be string',
            'email.email' => 'Email must be email',
            'eemail.unique' => 'Email must be unique',
        ];
    }
}
