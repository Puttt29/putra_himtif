@extends('layouts.app')

@section('content')
<section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 space-y-6 max-w-2xl">

    <!-- Header -->
    <div>
        <h2 class="text-xl font-semibold">Assign Pewawancara</h2>
        <p class="text-sm text-textmuted mt-1">
            Tentukan pewawancara untuk alternatif berikut
        </p>
    </div>

    <!-- Info Alternatif -->
    <div class="p-4 rounded-xl bg-base border border-black/10">
        <div class="font-medium text-lg">
            {{ $alternatif->nama_alternatif }}
        </div>
        <div class="text-sm text-textmuted">
            NIM: {{ $alternatif->nim ?? '-' }}
        </div>
    </div>

    <!-- FORM ASSIGN -->
    <form method="POST"
          action="{{ route('alternatif.assign.update', $alternatif->id) }}"
          class="space-y-4">
        @csrf
        @method('PUT')

        <div class="space-y-3">
            @forelse ($pewawancaras as $user)
                <label class="flex items-center gap-3 p-3 rounded-lg
                              border border-black/10 cursor-pointer
                              hover:bg-base/60">
                    <input type="checkbox"
                        name="pewawancara_ids[]"
                        value="{{ $user->id }}"
                        class="rounded"
                        {{ $alternatif->pewawancaras->contains($user->id) ? 'checked' : '' }}
                    >
                    <div>
                        <div class="font-medium">{{ $user->name }}</div>
                        <div class="text-xs text-textmuted">{{ $user->email }}</div>
                    </div>
                </label>
            @empty
                <p class="text-sm text-textmuted">
                    Tidak ada user pewawancara.
                </p>
            @endforelse
        </div>

        <!-- Action -->
        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ url()->previous() }}"
               class="px-4 py-2 rounded-lg border text-sm">
                Kembali
            </a>

            <button type="submit"
                class="px-4 py-2 rounded-lg bg-accent
                       text-white text-sm hover:bg-accentHover">
                Simpan Assignment
            </button>
        </div>
    </form>

</section>
@endsection
