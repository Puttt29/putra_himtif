@extends('layouts.app')

@section('content')
    <section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 max-w-2xl">
        <div class="mb-6">
            <h2 class="text-xl font-semibold">Tambah User Baru</h2>
            <p class="text-sm text-textmuted mt-1">
                Isi data user dan tentukan role akses
            </p>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Nama</label>
                <input type="text" name="name" class="w-full rounded-lg border border-black/10 px-4 py-2"
                    value="{{ old('name') }}">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" class="w-full rounded-lg border border-black/10 px-4 py-2"
                    value="{{ old('email') }}">
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" class="w-full rounded-lg border border-black/10 px-4 py-2">
                @error('password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Role</label>
                <select name="role" class="w-full rounded-lg border border-black/10 px-4 py-2">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    <option value="dosen" @selected(old('role') === 'dosen')>Dosen</option>
                    <option value="pewawancara" @selected(old('role') === 'pewawancara')>Pewawancara</option>
                </select>
                @error('role')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-lg border border-black/10 text-sm">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-accent text-white text-sm font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </section>
@endsection
