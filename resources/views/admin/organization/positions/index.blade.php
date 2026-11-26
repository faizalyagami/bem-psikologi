@extends('layouts.admin')
@section('title', 'Jabatan')
@section('breadcrumb', 'Organisasi › Jabatan')

@section('content')

    <x-admin.page-header title="Manajemen Jabatan" description="Kelola jabatan dalam struktur organisasi.">
    </x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-900/20 border border-red-900/40 text-red-400 text-sm rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Tambah --}}
    <div class="admin-card p-5 mb-6" x-data="{ open: false }">
        <button @click="open = !open" class="flex items-center gap-2 text-white font-heading font-semibold w-full">
            <i data-lucide="plus-circle" class="w-5 h-5 text-brand-400"></i> Tambah Jabatan
            <i data-lucide="chevron-down" class="w-4 h-4 ml-auto" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <form x-show="open" x-cloak action="{{ route('admin.positions.store') }}" method="POST"
            class="mt-4 pt-4 border-t border-dark-border grid grid-cols-1 md:grid-cols-2 gap-3">
            @csrf
            <div>
                <label class="admin-label">Nama Jabatan</label>
                <input type="text" name="name" required class="admin-input" placeholder="Misal: Ketua">
            </div>
            <div>
                <label class="admin-label">Slug <span class="text-gray-500 font-normal">(opsional)</span></label>
                <input type="text" name="slug" class="admin-input font-mono text-xs" placeholder="ketua">
            </div>
            <div>
                <label class="admin-label">Level</label>
                <select name="level" required class="admin-input">
                    <option value="pimpinan">Pimpinan</option>
                    <option value="pengurus_inti">Pengurus Inti</option>
                    <option value="kepala_departemen">Kepala Departemen</option>
                    <option value="staf">Staf</option>
                </select>
            </div>
            <div>
                <label class="admin-label">Urutan</label>
                <input type="number" name="sort_order" value="0" class="admin-input">
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="admin-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-dark-surface border-b border-dark-border">
                <tr class="text-left text-xs font-semibold text-gray-400 uppercase">
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Level</th>
                    <th class="px-4 py-3">Anggota</th>
                    <th class="px-4 py-3">Urutan</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-border">
                @forelse($positions as $pos)
                    <tr class="hover:bg-dark-hover" x-data="{ edit: false }">
                        <td class="px-4 py-3 font-medium text-white">{{ $pos->name }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="text-[11px] px-2 py-0.5 rounded-full bg-brand-900/30 text-brand-300 capitalize">{{ str_replace('_', ' ', $pos->level) }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-400">{{ $pos->members_count }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $pos->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="edit = true"
                                    class="p-2 rounded-lg hover:bg-dark-hover text-gray-400 hover:text-brand-400">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('admin.positions.destroy', $pos) }}" method="POST"
                                    onsubmit="return confirm('Hapus jabatan {{ $pos->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 rounded-lg hover:bg-red-900/20 text-gray-400 hover:text-red-400">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>

                            {{-- Modal Edit --}}
                            <div x-show="edit" x-cloak
                                class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4"
                                @click.away="edit = false">
                                <div class="admin-card p-6 w-full max-w-md">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="font-heading font-semibold text-white">Edit Jabatan</h3>
                                        <button @click="edit = false" class="text-gray-500 hover:text-white"><i
                                                data-lucide="x" class="w-5 h-5"></i></button>
                                    </div>
                                    <form action="{{ route('admin.positions.update', $pos) }}" method="POST"
                                        class="space-y-3">
                                        @csrf @method('PUT')
                                        <div>
                                            <label class="admin-label">Nama</label>
                                            <input type="text" name="name" value="{{ $pos->name }}" required
                                                class="admin-input">
                                        </div>
                                        <div>
                                            <label class="admin-label">Slug</label>
                                            <input type="text" name="slug" value="{{ $pos->slug }}"
                                                class="admin-input font-mono text-xs">
                                        </div>
                                        <div>
                                            <label class="admin-label">Level</label>
                                            <select name="level" class="admin-input">
                                                <option value="pimpinan" @selected($pos->level === 'pimpinan')>Pimpinan</option>
                                                <option value="pengurus_inti" @selected($pos->level === 'pengurus_inti')>Pengurus Inti
                                                </option>
                                                <option value="kepala_departemen" @selected($pos->level === 'kepala_departemen')>Kepala
                                                    Departemen</option>
                                                <option value="staf" @selected($pos->level === 'staf')>Staf</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="admin-label">Urutan</label>
                                            <input type="number" name="sort_order" value="{{ $pos->sort_order }}"
                                                class="admin-input">
                                        </div>
                                        <div class="flex justify-end gap-2 pt-2 border-t border-dark-border">
                                            <button type="button" @click="edit = false"
                                                class="btn-secondary">Batal</button>
                                            <button type="submit" class="btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada jabatan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
