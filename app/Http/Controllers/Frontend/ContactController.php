<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
            'is_anonymous' => ['nullable', 'boolean'],
        ]);

        $data['is_anonymous'] = $request->boolean('is_anonymous');

        $aspiration = Aspiration::create($data);

        ActivityLogger::log('aspiration.received', $aspiration, [
            'subject' => $aspiration->subject,
        ]);

        return redirect()
            ->route('frontend.contact', ['locale' => app()->getLocale()])
            ->with('success', 'Aspirasi Anda telah terkirim. Terima kasih!');
    }
}
