<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use App\Models\AspirationReply;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class AspirationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-aspirations');
    }

    public function index(Request $request)
    {
        $query = Aspiration::withCount('replies')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('subject', 'like', "%$s%")
                    ->orWhere('name', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%");
            });
        }

        $aspirations = $query->paginate(15)->withQueryString();

        $stats = [
            'pending' => Aspiration::where('status', 'pending')->count(),
            'processing' => Aspiration::where('status', 'processing')->count(),
            'replied' => Aspiration::where('status', 'replied')->count(),
            'closed' => Aspiration::where('status', 'closed')->count(),
        ];

        return view('admin.content.aspirations.index', compact('aspirations', 'stats'));
    }

    public function show(Aspiration $aspiration)
    {
        $aspiration->load(['replies.user']);
        return view('admin.content.aspirations.show', compact('aspiration'));
    }

    public function reply(Request $request, Aspiration $aspiration)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'status' => ['required', 'in:pending,processing,replied,closed'],
        ]);

        AspirationReply::create([
            'aspiration_id' => $aspiration->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        $aspiration->update(['status' => $request->status]);

        ActivityLogger::log('aspiration.replied', $aspiration, ['subject' => $aspiration->subject]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function updateStatus(Request $request, Aspiration $aspiration)
    {
        $request->validate([
            'status' => ['required', 'in:pending,processing,replied,closed'],
        ]);

        $aspiration->update(['status' => $request->status]);
        ActivityLogger::log('aspiration.status_updated', $aspiration, ['status' => $request->status]);

        return back()->with('success', 'Status aspirasi diperbarui.');
    }

    public function destroy(Aspiration $aspiration)
    {
        $subject = $aspiration->subject;
        $aspiration->delete();
        ActivityLogger::log('aspiration.deleted', null, ['subject' => $subject]);

        return redirect()->route('admin.aspirations.index')
            ->with('success', "Aspirasi \"{$subject}\" dihapus.");
    }
}
