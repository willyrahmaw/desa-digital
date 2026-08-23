<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRwRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('rw') ?? $this->rw;
        return [
            'dusun_id' => ['required', 'exists:dusun,id'],
            'nomor' => [
                'required',
                'string',
                'max:10',
                Rule::unique('rw')->where(function ($query) {
                    return $query->where('dusun_id', $this->dusun_id);
                })->ignore($id)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'dusun_id.required' => 'Dusun wajib dipilih.',
            'dusun_id.exists' => 'Dusun yang dipilih tidak valid.',
            'nomor.required' => 'Nomor RW wajib diisi.',
            'nomor.unique' => 'Nomor RW ini sudah ada pada dusun yang dipilih.',
        ];
    }
}
