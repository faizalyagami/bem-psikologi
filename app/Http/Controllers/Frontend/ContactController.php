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

        // Generate ticket code unik: ASP-YYYYMMDD-XXXX
        $lastId = \App\Models\Aspiration::max('id') + 1;
        $data['ticket_code'] = 'ASP-' . now()->format('Ymd') . '-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

        $aspiration = Aspiration::create($data);

        ActivityLogger::log('aspiration.received', $aspiration, [
            'subject' => $aspiration->subject,
        ]);

        return redirect()
            ->route('frontend.contact', ['locale' => app()->getLocale()])
            ->with('success', 'Aspirasi Anda telah terkirim dengan kode tiket: ' . $aspiration->ticket_code . '. Simpan kode ini untuk melacak status.')
            ->with('ticket_code', $aspiration->ticket_code);
    }

    public function checkStatus()
    {
        return view('frontend.contact.check');
    }

    public function showStatus(Request $request)
    {
        $request->validate([
            'ticket_code' => ['required', 'string'],
        ]);

        $aspiration = Aspiration::with('replies.user')
            ->where('ticket_code', $request->ticket_code)
            ->first();

        if (!$aspiration) {
            return back()->withErrors(['ticket_code' => 'Kode tiket tidak ditemukan.'])->withInput();
        }

        return view('frontend.contact.status', compact('aspiration'));
    }
}
