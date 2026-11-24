<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-posts');
    }

    public function rules(): array
    {
        return [
            // Identitas
            'category_id' => ['nullable', 'exists:categories,id'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['array'],
            'tags.*' => ['exists:tags,id'],

            // Terjemahan Indonesia (wajib)
            'id.title' => ['required', 'string', 'max:255'],
            'id.slug' => ['nullable', 'string', 'max:255', 'unique:post_translations,slug'],
            'id.content' => ['required', 'string'],
            'id.excerpt' => ['nullable', 'string', 'max:500'],
            'id.meta_title' => ['nullable', 'string', 'max:255'],
            'id.meta_description' => ['nullable', 'string', 'max:500'],

            // Terjemahan English (opsional)
            'en.title' => ['nullable', 'string', 'max:255'],
            'en.slug' => ['nullable', 'string', 'max:255', 'unique:post_translations,slug'],
            'en.content' => ['nullable', 'string'],
            'en.excerpt' => ['nullable', 'string', 'max:500'],
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
