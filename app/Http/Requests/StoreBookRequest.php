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
        $isExisting = $this->filled('existing_book_id');

        return [
            'accession_no'     => 'required|string|unique:book_copies,accession_no',
            'type'             => 'required|in:physical,digital',
            'existing_book_id' => 'nullable|integer|exists:books,id',

            // Only required when adding a brand new book
            'title'            => $isExisting ? 'nullable|string|max:255' : 'required|string|max:255',
            'author'           => $isExisting ? 'nullable|string|max:255' : 'required|string|max:255',
            'price'            => $isExisting ? 'nullable|numeric|min:0'  : 'required|numeric|min:0',

            'category'         => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'cover_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'file_path'        => 'nullable|mimes:pdf|max:51200' . ($isExisting ? '' : '|required_if:type,digital'),
        ];
    }
}