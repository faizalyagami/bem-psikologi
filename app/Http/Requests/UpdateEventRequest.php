<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-events');
    }

    public function rules(): array
    {
        $event = $this->route('event');
        $idTransId = $event->translations()->where('locale', 'id')->first()?->id;
        $enTransId = $event->translations()->where('locale', 'en')->first()?->id;

        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'location' => ['nullable', 'string', 'max:255'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:draft,published'],

            'id.title' => ['required', 'string', 'max:255'],
            'id.slug' => ['nullable', 'string', 'max:255', Rule::unique('event_translations', 'slug')->ignore($idTransId)],
            'id.description' => ['nullable', 'string'],

            'en.title' => ['nullable', 'string', 'max:255'],
            'en.slug' => ['nullable', 'string', 'max:255', Rule::unique('event_translations', 'slug')->ignore($enTransId)],
            'en.description' => ['nullable', 'string'],
        ];
    }
}
