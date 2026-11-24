<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-events');
    }

    public function rules(): array
    {
        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'location' => ['nullable', 'string', 'max:255'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:draft,published'],

            'id.title' => ['required', 'string', 'max:255'],
            'id.slug' => ['nullable', 'string', 'max:255', 'unique:event_translations,slug'],
            'id.description' => ['nullable', 'string'],

            'en.title' => ['nullable', 'string', 'max:255'],
            'en.slug' => ['nullable', 'string', 'max:255', 'unique:event_translations,slug'],
            'en.description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.title.required' => 'Judul agenda (Indonesia) wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ];
    }
}
