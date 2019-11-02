<?php

namespace App\Http\Requests\AWSHack;


use Illuminate\Http\Request;

Class Job104Request extends Request
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
            'kws' => 'required|string',
            'kwop' => 'nullable|integer',
        ];
    }
}
