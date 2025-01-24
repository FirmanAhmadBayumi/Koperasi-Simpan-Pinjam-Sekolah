<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_tlp' => ['required', 'digits_between:1,15'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa string.',
            'email.lowercase' => 'Email harus menggunakan huruf kecil.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string' => 'Alamat harus berupa string.',
            'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter.',

            'no_tlp.required' => 'Nomor telepon wajib diisi.',
            'no_tlp.digits_between' => 'Nomor telepon harus berupa angka',
        ];
    }
}
