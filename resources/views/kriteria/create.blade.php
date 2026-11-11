@extends('layouts.app')

@section('content')
    <section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 max-w-2xl">
        <div class="mb-6">
            <h2 class="text-xl font-semibold">Tambah Kriteria</h2>
            <p class="text-sm text-textmuted mt-1">
                Tambahkan kriteria baru beserta bobot dan atribut
            </p>
        </div>

        <form action="{{ route('kriteria.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Kode Kriteria</label>
                <select name="kode_kriteria_id" class="w-full rounded-lg border border-black/10 px-4 py-2">
                    <option value="">-- Pilih Kode --</option>
                    @foreach ($kodeKriterias as $kode)
                        <option value="{{ $kode->id }}" @selected(old('kode_kriteria_id') == $kode->id)>
                            {{ $kode->kode_kriteria }}
                        </option>
                    @endforeach
                </select>
                @error('kode_kriteria_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Nama Kriteria</label>
                <input type="text" name="nama_kriteria" class="w-full rounded-lg border border-black/10 px-4 py-2"
                    value="{{ old('nama_kriteria') }}">
                @error('nama_kriteria')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Atribut</label>
                <select name="atribut" class="w-full rounded-lg border border-black/10 px-4 py-2">
                    <option value="">-- Pilih Atribut --</option>
                    <option value="benefit" @selected(old('atribut') === 'benefit')>
                        Benefit
                    </option>
                    <option value="cost" @selected(old('atribut') === 'cost')>
                        Cost
                    </option>
                </select>
                @error('atribut')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Bobot</label>
                <input type="number" step="0.0001" name="bobot"
                    class="w-full rounded-lg border border-black/10 px-4 py-2" value="{{ old('bobot') }}">
                @error('bobot')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-textmuted mt-1">
                    Normalisasi dihitung otomatis oleh sistem
                </p>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('kriteria.index') }}" class="px-4 py-2 rounded-lg border border-black/10 text-sm">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-accent text-white text-sm font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </section>
@endsection
