<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Support\ActivityLogger;
use App\Support\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-events');
    }

    public function index(Request $request)
    {
        $query = Event::with(['translations', 'user'])->orderByDesc('start_date');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('translations', fn($q) => $q->where('title', 'like', "%$s%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->paginate(10)->withQueryString();

        return view('admin.content.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.content.events.create');
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = ImageUploader::upload($request->file('featured_image'), 'media');
        }

        $event = Event::create([
            'user_id' => auth()->id(),
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            'location' => $data['location'] ?? null,
            'featured_image' => $imagePath,
            'status' => $data['status'],
        ]);

        $event->translations()->create([
            'locale' => 'id',
            'title' => $data['id']['title'],
            'slug' => $data['id']['slug'] ?: Str::slug($data['id']['title']),
            'description' => $data['id']['description'] ?? null,
        ]);

        if (!empty($data['en']['title'])) {
            $event->translations()->create([
                'locale' => 'en',
                'title' => $data['en']['title'],
                'slug' => $data['en']['slug'] ?: Str::slug($data['en']['title']),
                'description' => $data['en']['description'] ?? null,
            ]);
        }

        ActivityLogger::log('event.created', $event, ['title' => $event->translation('id')->title]);

        return redirect()->route('admin.events.index')
            ->with('success', "Agenda \"{$event->translation('id')->title}\" berhasil ditambahkan.");
    }

    public function edit(Event $event)
    {
        $event->load('translations');
        return view('admin.content.events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $data = $request->validated();

        $imagePath = $event->featured_image;
        if ($request->hasFile('featured_image')) {
            $imagePath = ImageUploader::upload($request->file('featured_image'), 'media', $event->featured_image);
        }

        $event->update([
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            'location' => $data['location'] ?? null,
            'featured_image' => $imagePath,
            'status' => $data['status'],
        ]);

        $event->translations()->updateOrCreate(
            ['locale' => 'id'],
            [
                'title' => $data['id']['title'],
                'slug' => $data['id']['slug'] ?: Str::slug($data['id']['title']),
                'description' => $data['id']['description'] ?? null,
            ]
        );

        if (!empty($data['en']['title'])) {
            $event->translations()->updateOrCreate(
                ['locale' => 'en'],
                [
                    'title' => $data['en']['title'],
                    'slug' => $data['en']['slug'] ?: Str::slug($data['en']['title']),
                    'description' => $data['en']['description'] ?? null,
                ]
            );
        } else {
            $event->translations()->where('locale', 'en')->delete();
        }

        ActivityLogger::log('event.updated', $event, ['title' => $event->translation('id')->title]);

        return redirect()->route('admin.events.index')
            ->with('success', "Agenda \"{$event->translation('id')->title}\" berhasil diperbarui.");
    }

    public function destroy(Event $event)
    {
        $title = $event->translation('id')?->title ?? 'Agenda';
        ImageUploader::delete($event->featured_image);
        $event->delete();
        ActivityLogger::log('event.deleted', null, ['title' => $title]);

        return redirect()->route('admin.events.index')
            ->with('success', "Agenda \"{$title}\" berhasil dihapus.");
    }
}
