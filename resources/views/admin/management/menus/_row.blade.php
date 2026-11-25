<tr class="hover:bg-dark-hover" x-data="{ edit: false }">
    <td class="px-4 py-3">
        <span class="{{ $level > 0 ? 'pl-6 text-gray-400' : 'font-medium text-white' }}">
            {{ $level > 0 ? '└ ' : '' }}{{ $menu->title }}
        </span>
    </td>
    <td class="px-4 py-3 text-gray-400 text-xs font-mono">
        {{ $menu->route_name ?? ($menu->url ?? '—') }}
    </td>
    <td class="px-4 py-3 text-gray-400 capitalize">{{ $menu->location }}</td>
    <td class="px-4 py-3">
        @if ($menu->is_active)
            <span class="text-[11px] px-2 py-0.5 rounded-full bg-green-900/30 text-green-400">Aktif</span>
        @else
            <span class="text-[11px] px-2 py-0.5 rounded-full bg-gray-800 text-gray-400">Nonaktif</span>
        @endif
    </td>
    <td class="px-4 py-3">
        <div class="flex items-center justify-end gap-1">
            <button type="button" @click="edit = true"
                class="p-2 rounded-lg hover:bg-dark-hover text-gray-400 hover:text-brand-400" title="Edit">
                <i data-lucide="pencil" class="w-4 h-4"></i>
            </button>
            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST"
                onsubmit="return confirm('Hapus menu {{ $menu->title }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="p-2 rounded-lg hover:bg-red-900/20 text-gray-400 hover:text-red-400"
                    title="Hapus">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </form>
        </div>

        {{-- Modal Edit --}}
        <div x-show="edit" x-cloak class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4"
            @click.away="edit = false">
            <div class="admin-card p-6 w-full max-w-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-heading font-semibold text-white">Edit Menu</h3>
                    <button @click="edit = false" class="text-gray-500 hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form action="{{ route('admin.menus.update', $menu) }}" method="POST" class="space-y-3">
                    @csrf @method('PUT')
                    <div>
                        <label class="admin-label">Judul</label>
                        <input type="text" name="title" value="{{ $menu->title }}" required class="admin-input">
                    </div>
                    <div>
                        <label class="admin-label">Induk (opsional)</label>
                        <select name="parent_id" class="admin-input">
                            <option value="">— Tanpa induk —</option>
                            @foreach (\App\Models\Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->orderBy('title')->get() as $m)
                                <option value="{{ $m->id }}" @selected($menu->parent_id == $m->id)>{{ $m->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="admin-label">Route Name</label>
                            <input type="text" name="route_name" value="{{ $menu->route_name }}"
                                class="admin-input font-mono text-xs" placeholder="frontend.contact">
                        </div>
                        <div>
                            <label class="admin-label">URL Manual</label>
                            <input type="text" name="url" value="{{ $menu->url }}"
                                class="admin-input font-mono text-xs" placeholder="/id/visi-misi">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="admin-label">Lokasi</label>
                            <select name="location" class="admin-input">
                                <option value="header" @selected($menu->location === 'header')>Header</option>
                                <option value="footer" @selected($menu->location === 'footer')>Footer</option>
                            </select>
                        </div>
                        <div>
                            <label class="admin-label">Target</label>
                            <select name="target" class="admin-input">
                                <option value="_self" @selected($menu->target === '_self')>Same Tab</option>
                                <option value="_blank" @selected($menu->target === '_blank')>New Tab</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 items-end">
                        <div>
                            <label class="admin-label">Urutan</label>
                            <input type="number" name="sort_order" value="{{ $menu->sort_order }}"
                                class="admin-input">
                        </div>
                        <label class="flex items-center gap-2 text-sm h-[42px]">
                            <input type="checkbox" name="is_active" value="1" @checked($menu->is_active)
                                class="rounded bg-dark-bg border-dark-border text-brand-600">
                            Aktif
                        </label>
                    </div>
                    <div class="flex justify-end gap-2 pt-3 border-t border-dark-border">
                        <button type="button" @click="edit = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </td>
</tr>
