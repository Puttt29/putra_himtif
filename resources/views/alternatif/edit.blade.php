@extends('layouts.app')

@section('content')
<section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 max-w-2xl">
    <div class="mb-6">
        <h2 class="text-xl font-semibold">Edit Alternatif</h2>
        <p class="text-sm text-textmuted mt-1">
            Perbarui data alternatif
        </p>
    </div>

    <form action="{{ route('alternatif.update', $alternatif->id) }}"
          method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Nama Alternatif</label>
            <input type="text" name="nama_alternatif"
                   class="w-full rounded-lg border border-black/10 px-4 py-2"
                   value="{{ old('nama_alternatif', $alternatif->nama_alternatif) }}">
            @error('nama_alternatif')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">NIM (Opsional)</label>
            <input type="text" name="nim"
                   class="w-full rounded-lg border border-black/10 px-4 py-2"
                   value="{{ old('nim', $alternatif->nim) }}">
            @error('nim')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Angkatan</label>
            <select name="angkatan_id"
                    class="w-full rounded-lg border border-black/10 px-4 py-2">
                @foreach ($angkatans as $angkatan)
                    <option value="{{ $angkatan->id }}"
                        {{ old('angkatan_id', $alternatif->angkatan_id) == $angkatan->id ? 'selected' : '' }}>
                        {{ $angkatan->nama_angkatan }}
                    </option>
                @endforeach
            </select>
            @error('angkatan_id')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('alternatif.index') }}"
               class="px-4 py-2 rounded-lg border border-black/10 text-sm">
                Batal
            </a>
            <button class="px-5 py-2 rounded-lg bg-accent text-white text-sm font-medium">
                Update
            </button>
        </div>
    </form>
</section>
@endsection
