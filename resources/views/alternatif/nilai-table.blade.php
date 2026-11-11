@extends('layouts.app')

@section('content')
    <section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 space-y-6">

        <!-- Header -->
        <div>
            <h2 class="text-xl font-semibold">Input Nilai Alternatif</h2>
            <p class="text-sm text-textmuted mt-1">
                Masukkan nilai alternatif berdasarkan kriteria yang tersedia
            </p>
        </div>

        <!-- Filter Angkatan -->
        <form method="GET" action="{{ route('alternatif-nilai.index') }}">
            <div class="max-w-sm">
                <label class="block text-sm font-medium mb-1">Pilih Angkatan</label>
                <select name="angkatan_id"
                    class="w-full rounded-lg border border-black/10 px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-accent"
                    onchange="this.form.submit()">
                    <option value="">-- Pilih Angkatan --</option>
                    @foreach ($angkatans as $angkatan)
                        <option value="{{ $angkatan->id }}" {{ $angkatanId == $angkatan->id ? 'selected' : '' }}>
                            {{ $angkatan->nama_angkatan }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        @if ($angkatanId)
            <!-- Action (ADMIN / DOSEN ONLY) -->
            @if (auth()->user()->role !== 'pewawancara')
                <div class="flex justify-end">
                    <button type="button" onclick="openModal()"
                        class="px-4 py-2 rounded-lg bg-base border border-black/10
                       text-sm font-medium hover:bg-base/70">
                        + Tambah Alternatif
                    </button>
                </div>
            @endif

            <!-- FORM NILAI -->
            <form method="POST" action="{{ route('alternatif-nilai.store') }}" id="form-nilai">
                @csrf

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-base text-textmuted">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium w-64">
                                    Nama Alternatif
                                    <div class="text-xs">NIM</div>
                                </th>

                                @foreach ($kriterias as $kriteria)
                                    <th class="px-4 py-3 text-center font-medium">
                                        {{ $kriteria->kodeKriteria->kode }}
                                        <div class="text-xs">
                                            ({{ $kriteria->nama_kriteria }} | {{ $kriteria->bobot }})
                                        </div>
                                    </th>
                                @endforeach

                                <th class="px-4 py-3 text-center font-medium w-36">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($alternatifs as $alternatif)
                                <tr class="border-t border-black/5 hover:bg-base/60 transition">

                                    <!-- Nama -->
                                    <td class="px-4 py-3 font-medium">
                                        {{ $alternatif->nama_alternatif }}
                                        <div class="text-xs text-textmuted">
                                            {{ $alternatif->nim ?? '-' }}
                                        </div>
                                    </td>

                                    <!-- Nilai -->
                                    @foreach ($kriterias as $kriteria)
                                        @php
                                            $existing = $nilaiMap[$alternatif->id][$kriteria->id][0]->nilai ?? null;
                                        @endphp
                                        <td class="px-4 py-2">
                                            <select name="nilai[{{ $alternatif->id }}][{{ $kriteria->id }}]"
                                                class="w-full text-center rounded-lg border border-black/10
           px-2 py-1 text-sm
           focus:outline-none focus:ring-2 focus:ring-accent">

                                                <option value="">--</option>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <option value="{{ $i }}"
                                                        {{ (int) $existing === $i ? 'selected' : '' }}>
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                            <script>
                                                // bisa dihapus jika semua sudah select
                                                document.getElementById('form-nilai')?.addEventListener('submit', function() {
                                                    this.querySelectorAll('input[type="number"]').forEach(input => {
                                                        input.value = parseInt(input.value || 0);
                                                    });
                                                });
                                            </script>
                                        </td>
                                    @endforeach

                                    <!-- Aksi -->
                                    <td class="px-4 py-2 text-center space-x-2">

                                        <a href="{{ route('alternatif-nilai.preview', $alternatif->id) }}" target="_blank"
                                            class="inline-flex px-3 py-1 rounded-lg border
              text-xs hover:bg-base/70">
                                            Preview
                                        </a>

                                        <a href="{{ route('alternatif-nilai.cetak', $alternatif->id) }}"
                                            class="inline-flex px-3 py-1 rounded-lg
              bg-accent text-white text-xs hover:bg-accentHover">
                                            Cetak
                                        </a>

                                        {{-- 🔥 ASSIGN (ADMIN / DOSEN) --}}
                                        @if (auth()->user()->role !== 'pewawancara')
                                            <a href="{{ route('alternatif.assign', $alternatif->id) }}"
                                                class="inline-flex px-3 py-1 rounded-lg
                  border border-black/10 text-xs
                  hover:bg-base/70">
                                                Assign
                                            </a>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $kriterias->count() + 2 }}" class="text-center py-6 text-textmuted">
                                        Tidak ada data alternatif untuk angkatan ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Submit -->
                @if ($alternatifs->count() > 0)
                    <div class="pt-4">
                        <button type="submit"
                            class="px-5 py-2 rounded-lg bg-accent text-white
                           hover:bg-accentHover transition text-sm font-medium">
                            Simpan Nilai
                        </button>
                    </div>
                @endif
            </form>
        @endif
    </section>

    <!-- MODAL TAMBAH ALTERNATIF (ADMIN / DOSEN ONLY) -->
    @if (auth()->user()->role !== 'pewawancara')
        <div id="modal-alternatif" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
            <div class="bg-white rounded-2xl w-full max-w-md p-6 space-y-4">
                <h3 class="text-lg font-semibold">Tambah Alternatif</h3>

                <form method="POST" action="{{ route('alternatif.store') }}">
                    @csrf
                    <input type="hidden" name="angkatan_id" value="{{ $angkatanId }}">

                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium">Nama Alternatif</label>
                            <input type="text" name="nama_alternatif" required
                                class="w-full rounded-lg border border-black/10 px-3 py-2 text-sm">
                        </div>

                        <div>
                            <label class="text-sm font-medium">NIM</label>
                            <input type="text" name="nim"
                                class="w-full rounded-lg border border-black/10 px-3 py-2 text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg border text-sm">
                            Batal
                        </button>

                        <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-white text-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- SCRIPT -->
    <script>
        function openModal() {
            const modal = document.getElementById('modal-alternatif');
            modal?.classList.remove('hidden');
            modal?.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('modal-alternatif');
            modal?.classList.add('hidden');
            modal?.classList.remove('flex');
        }

        // Sanitizer nilai
        document.getElementById('form-nilai')?.addEventListener('submit', function() {
            this.querySelectorAll('input[type="number"]').forEach(input => {
                input.value = parseInt(input.value || 0);
            });
        });
    </script>
@endsection
