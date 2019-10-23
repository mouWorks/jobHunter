<?php

namespace App\Http\Requests\AWSHack;


use Illuminate\Foundation\Http\FormRequest;

Class Job104Request extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'cat' => 'nullable|array',
            'cat.*' => 'nullable|integer',
            'area' => 'nullable|array',
            'area.*' => 'nullable|integer',
            'role' => 'nullable|array',
            'role.*' => 'nullable|integer',
            'exp' => 'nullable|integer',
            'kws' => 'nullable|string',
            'kwop' => 'nullable|integer',
        ];
    }
}
