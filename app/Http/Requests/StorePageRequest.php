<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-pages');
    }

    public function rules(): array
    {
        return [
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:draft,published'],

            'id.title' => ['required', 'string', 'max:255'],
            'id.slug' => ['nullable', 'string', 'max:255', 'unique:page_translations,slug'],
            'id.content' => ['required', 'string'],
            'id.meta_title' => ['nullable', 'string', 'max:255'],
            'id.meta_description' => ['nullable', 'string', 'max:500'],

            'en.title' => ['nullable', 'string', 'max:255'],
            'en.slug' => ['nullable', 'string', 'max:255', 'unique:page_translations,slug'],
            'en.content' => ['nullable', 'string'],
            'en.meta_title' => ['nullable', 'string', 'max:255'],
            'en.meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.title.required' => 'Judul (Indonesia) wajib diisi.',
            'id.content.required' => 'Konten (Indonesia) wajib diisi.',
        ];
    }
}
