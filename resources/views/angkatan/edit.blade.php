@extends('layouts.app')

@section('content')
    <section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 max-w-2xl">
        <div class="mb-6">
            <h2 class="text-xl font-semibold">Edit Angkatan</h2>
            <p class="text-sm text-textmuted mt-1">
                Perbarui data angkatan
            </p>
        </div>

        <form action="{{ route('angkatan.update', $angkatan->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">
                    Nama Angkatan
                </label>
                <input type="text" name="nama_angkatan" class="w-full rounded-lg border border-black/10 px-4 py-2"
                    value="{{ old('nama_angkatan', $angkatan->nama_angkatan) }}">
                @error('nama_angkatan')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('angkatan.index') }}" class="px-4 py-2 rounded-lg border border-black/10 text-sm">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-accent text-white text-sm font-medium">
                    Update
                </button>
            </div>
        </form>
    </section>
@endsection
