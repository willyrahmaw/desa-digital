<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('rt') ?? $this->rt;
        return [
            'rw_id' => ['required', 'exists:rw,id'],
            'nomor' => [
                'required',
                'string',
                'max:10',
                Rule::unique('rt')->where(function ($query) {
                    return $query->where('rw_id', $this->rw_id);
                })->ignore($id)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'rw_id.required' => 'RW wajib dipilih.',
            'rw_id.exists' => 'RW yang dipilih tidak valid.',
            'nomor.required' => 'Nomor RT wajib diisi.',
            'nomor.unique' => 'Nomor RT ini sudah ada pada RW yang dipilih.',
        ];
    }
}
