@extends('layouts.app')

@section('content')
    <section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 space-y-6">

        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Assignment Pewawancara</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-base text-textmuted">
                    <tr>
                        <th class="px-4 py-3 text-left">Alternatif</th>
                        <th class="px-4 py-3 text-left">Angkatan</th>
                        <th class="px-4 py-3 text-left">Pewawancara</th>
                        <th class="px-4 py-3 text-center w-32">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($alternatifs as $alt)
                        <tr class="border-t">
                            <td class="px-4 py-3 font-medium">
                                {{ $alt->nama_alternatif }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $alt->angkatan->nama_angkatan ?? '-' }}
                            </td>

                            <!-- 🔥 LIST PEWAWANCARA -->
                            <td class="px-4 py-3 space-y-1">
                                @forelse($alt->pewawancaras as $user)
                                    <div class="flex justify-between items-center">
                                        <span>{{ $user->name }}</span>

                                        <form method="POST"
                                            action="{{ route('alternatif-assignment.destroy', [$alt->id, $user->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-xs text-red-600">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <span class="text-xs text-textmuted">
                                        Belum di-assign
                                    </span>
                                @endforelse
                            </td>

                            <!-- AKSI -->
                            <td class="px-4 py-3 text-center">
                                <button
                                    onclick="openModal(
                                {{ $alt->id }},
                                '{{ $alt->nama_alternatif }}',
                                @json($alt->pewawancaras->pluck('id'))
                            )"
                                    class="px-3 py-1 rounded-lg border text-xs">
                                    Assign / Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $alternatifs->links() }}
    </section>

    <!-- ================= MODAL ================= -->
    <div id="assignModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 space-y-4">

            <h3 class="text-lg font-semibold" id="modalTitle"></h3>

            <form method="POST" action="{{ route('alternatif-assignment.store') }}">
                @csrf
                <input type="hidden" name="alternatif_id" id="alternatif_id">

                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @foreach ($pewawancaras as $user)
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="pewawancara_ids[]" value="{{ $user->id }}"
                                class="checkbox-pewawancara">
                            {{ $user->name }}
                        </label>
                    @endforeach
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

    <!-- ================= SCRIPT ================= -->
    <script>
        function openModal(id, nama, assignedIds) {
            document.getElementById('alternatif_id').value = id;
            document.getElementById('modalTitle').innerText =
                'Assign Pewawancara – ' + nama;

            document.querySelectorAll('.checkbox-pewawancara').forEach(cb => {
                cb.checked = assignedIds.includes(parseInt(cb.value));
            });

            document.getElementById('assignModal').classList.remove('hidden');
            document.getElementById('assignModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('assignModal').classList.add('hidden');
            document.getElementById('assignModal').classList.remove('flex');
        }
    </script>
@endsection
