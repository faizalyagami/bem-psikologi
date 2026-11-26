<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-translations');
    }

    public function index(Request $request)
    {
        $query = Translation::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('key', 'like', "%$s%")
                    ->orWhere('value', 'like', "%$s%");
            });
        }

        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        $translations = $query->orderBy('group')->orderBy('key')->paginate(20)->withQueryString();
        $groups = Translation::select('group')->distinct()->orderBy('group')->pluck('group');

        return view('admin.management.translations.index', compact('translations', 'groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'group' => ['required', 'string', 'max:50'],
            'key' => ['required', 'string', 'max:100'],
            'locale' => ['required', 'in:id,en'],
            'value' => ['required', 'string'],
        ]);

        Translation::updateOrCreate(
            ['group' => $data['group'], 'key' => $data['key'], 'locale' => $data['locale']],
            ['value' => $data['value']]
        );

        ActivityLogger::log('translation.saved', null, $data);

        return back()->with('success', 'Terjemahan berhasil disimpan.');
    }

    public function destroy(Translation $translation)
    {
        $translation->delete();
        ActivityLogger::log('translation.deleted', null, ['key' => $translation->key]);

        return back()->with('success', 'Terjemahan dihapus.');
    }
}
