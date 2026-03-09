<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'              => 'required|string|max:255',
            'author'             => 'required|string|max:255',
            'category'           => 'nullable|string|max:255',
            'type'               => 'required|in:physical,digital',
            'price'              => 'required|numeric|min:0',
            'cover_image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description'        => 'nullable|string',
            'accession_no'       => 'required|string|unique:books,accession_no',
            'file_path'          => 'nullable|mimes:pdf|max:51200|required_if:type,digital',
        ];
    }
}