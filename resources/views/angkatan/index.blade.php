@extends('layouts.app')

@section('content')
    <section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">Manajemen Angkatan</h2>
                <p class="text-sm text-textmuted mt-1">
                    Kelola data angkatan yang digunakan dalam sistem
                </p>
            </div>

            <a href="{{ route('angkatan.create') }}"
                class="px-4 py-2 rounded-lg bg-accent text-white hover:bg-accentHover transition text-sm font-medium">
                + Tambah Angkatan
            </a>
        </div>

        @if (session('success'))
            <div class="px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="px-4 py-3 rounded-lg bg-red-50 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-base text-textmuted">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">#</th>
                        <th class="px-4 py-3 text-left font-medium">Nama Angkatan</th>
                        <th class="px-4 py-3 text-left font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($angkatan as $index => $a)
                        <tr class="border-t border-black/5 hover:bg-base/60 transition">
                            <td class="px-4 py-3">
                                {{ ($angkatan->currentPage() - 1) * $angkatan->perPage() + $index + 1 }}
                            </td>
                            <td class="px-4 py-3 font-medium">
                                {{ $a->nama_angkatan }}
                            </td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="{{ route('angkatan.edit', $a->id) }}" class="text-sm text-accent hover:underline">
                                    Edit
                                </a>

                                <form action="{{ route('angkatan.destroy', $a->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus angkatan ini?')"
                                        class="text-sm text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-textmuted">
                                Belum ada data angkatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div>
            {{ $angkatan->links() }}
        </div>
    </section>
@endsection
