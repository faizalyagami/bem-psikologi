<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequestPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-posts');
    }

    public function rules(): array
    {
        $postId = $this->route('post')->id;
        $idTranslationId = $this->route('post')->translations()->where('locale', 'id')->first()?->id;
        $enTranslationId = $this->route('post')->translations()->where('locale', 'en')->first()?->id;

        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['array'],
            'tags.*' => ['exists:tags,id'],

            'id.title' => ['required', 'string', 'max:255'],
            'id.slug' => ['nullable', 'string', 'max:255', Rule::unique('post_translations', 'slug')->ignore($idTranslationId)],
            'id.content' => ['required', 'string'],
            'id.excerpt' => ['nullable', 'string', 'max:500'],
            'id.meta_title' => ['nullable', 'string', 'max:255'],
            'id.meta_description' => ['nullable', 'string', 'max:500'],

            'en.title' => ['nullable', 'string', 'max:255'],
            'en.slug' => ['nullable', 'string', 'max:255', Rule::unique('post_translations', 'slug')->ignore($enTranslationId)],
            'en.content' => ['nullable', 'string'],
            'en.excerpt' => ['nullable', 'string', 'max:500'],
            'en.meta_title' => ['nullable', 'string', 'max:255'],
            'en.meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
