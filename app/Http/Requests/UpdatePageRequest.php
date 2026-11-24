<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-pages');
    }

    public function rules(): array
    {
        $page = $this->route('page');
        $idTransId = $page->translations()->where('locale', 'id')->first()?->id;
        $enTransId = $page->translations()->where('locale', 'en')->first()?->id;

        return [
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:draft,published'],

            'id.title' => ['required', 'string', 'max:255'],
            'id.slug' => ['nullable', 'string', 'max:255', Rule::unique('page_translations', 'slug')->ignore($idTransId)],
            'id.content' => ['required', 'string'],
            'id.meta_title' => ['nullable', 'string', 'max:255'],
            'id.meta_description' => ['nullable', 'string', 'max:500'],

            'en.title' => ['nullable', 'string', 'max:255'],
            'en.slug' => ['nullable', 'string', 'max:255', Rule::unique('page_translations', 'slug')->ignore($enTransId)],
            'en.content' => ['nullable', 'string'],
            'en.meta_title' => ['nullable', 'string', 'max:255'],
            'en.meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
