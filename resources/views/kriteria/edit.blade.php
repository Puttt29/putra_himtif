@extends('layouts.app')

@section('content')
    <section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 max-w-2xl">
        <div class="mb-6">
            <h2 class="text-xl font-semibold">Edit Kriteria</h2>
            <p class="text-sm text-textmuted mt-1">
                Perbarui data kriteria dan bobot
            </p>
        </div>

        <form action="{{ route('kriteria.update', $kriteria->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Kode Kriteria</label>
                <select name="kode_kriteria_id" class="w-full rounded-lg border border-black/10 px-4 py-2">
                    @foreach ($kodeKriterias as $kode)
                        <option value="{{ $kode->id }}" @selected($kriteria->kode_kriteria_id == $kode->id)>
                            {{ $kode->kode_kriteria }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Nama Kriteria</label>
                <input type="text" name="nama_kriteria" class="w-full rounded-lg border border-black/10 px-4 py-2"
                    value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Atribut</label>
                <select name="atribut" class="w-full rounded-lg border border-black/10 px-4 py-2">
                    <option value="benefit" @selected($kriteria->atribut === 'benefit')>
                        Benefit
                    </option>
                    <option value="cost" @selected($kriteria->atribut === 'cost')>
                        Cost
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Bobot</label>
                <input type="number" step="0.0001" name="bobot"
                    class="w-full rounded-lg border border-black/10 px-4 py-2"
                    value="{{ old('bobot', rtrim(rtrim($kriteria->bobot, '0'), '.')) }}">
                <p class="text-xs text-textmuted mt-1">
                    Normalisasi akan diperbarui otomatis
                </p>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('kriteria.index') }}" class="px-4 py-2 rounded-lg border border-black/10 text-sm">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-accent text-white text-sm font-medium">
                    Update
                </button>
            </div>
        </form>
    </section>
@endsection
