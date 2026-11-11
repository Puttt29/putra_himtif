@extends('layouts.app')

@section('content')
    <section class="bg-white p-6 rounded-2xl shadow space-y-4 max-w-xl">

        <h2 class="text-lg font-semibold">
            Assign Pewawancara: {{ $user->name }}
        </h2>

        <form method="POST" action="{{ route('users.assign.store', $user->id) }}">
            @csrf

            <label class="block text-sm font-medium mb-1">
                Pilih Alternatif (Mahasiswa)
            </label>

            <select name="alternatif_ids[]" multiple class="w-full rounded-lg border px-3 py-2 text-sm h-48">
                @foreach ($alternatifs as $alt)
                    <option value="{{ $alt->id }}"
                        {{ $user->alternatifYangDinilai->contains($alt->id) ? 'selected' : '' }}>
                        {{ $alt->nama_alternatif }} ({{ $alt->nim }})
                    </option>
                @endforeach
            </select>

            <div class="flex justify-end pt-4">
                <button class="px-4 py-2 bg-accent text-white rounded-lg text-sm">
                    Simpan Assignment
                </button>
            </div>
        </form>

    </section>
@endsection
