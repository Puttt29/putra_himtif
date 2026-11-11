<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>

    @include('layouts.partials.styles')
</head>

<body class="bg-base text-textmain antialiased overflow-x-hidden">
    <!-- Overlay -->
    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

    @include('layouts.partials.sidebar')

    <!-- Main -->
    <div class="min-h-screen flex flex-col lg:pl-64">
        <!-- Topbar -->
        @include('layouts.partials.navbar')

        <!-- Content -->
        <main class="flex-1 bg-surfaceSoft p-6 space-y-6">
            <!-- Header Card -->
            @yield('content')
            {{-- <section
                class="bg-brand rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-sm">
                <div>
                    <h2 class="text-xl font-semibold">Projects Overview</h2>
                    <p class="text-sm text-textmuted mt-1">
                        Summary of ongoing projects and performance
                    </p>
                </div>

                <button class="px-5 py-2 rounded-lg bg-accent text-white hover:bg-accentHover transition font-medium">
                    Create New Project
                </button>
            </section>

            <!-- KPI -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-surface rounded-xl p-5 shadow-sm border border-black/5">
                    <p class="text-sm text-textmuted">Projects</p>
                    <h3 class="text-2xl font-bold mt-1">18</h3>
                </div>
                <div class="bg-surface rounded-xl p-5 shadow-sm border border-black/5">
                    <p class="text-sm text-textmuted">Active Tasks</p>
                    <h3 class="text-2xl font-bold mt-1">132</h3>
                </div>
                <div class="bg-surface rounded-xl p-5 shadow-sm border border-black/5">
                    <p class="text-sm text-textmuted">Teams</p>
                    <h3 class="text-2xl font-bold mt-1">12</h3>
                </div>
                <div class="bg-surface rounded-xl p-5 shadow-sm border border-black/5">
                    <p class="text-sm text-textmuted">Productivity</p>
                    <h3 class="text-2xl font-bold mt-1">76%</h3>
                </div>
            </section>

            <!-- Table -->
            <section class="bg-surface rounded-xl shadow-sm border border-black/5 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-base text-textmuted">
                        <tr>
                            <th class="px-6 py-4 text-left font-medium">
                                Project
                            </th>
                            <th class="px-6 py-4 text-left font-medium">
                                Hours
                            </th>
                            <th class="px-6 py-4 text-left font-medium">
                                Progress
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t border-black/5 hover:bg-base/60 transition">
                            <td class="px-6 py-4 font-medium">
                                Design System
                            </td>
                            <td class="px-6 py-4">120</td>
                            <td class="px-6 py-4">
                                <div class="h-2 bg-base rounded-full">
                                    <div class="h-2 w-3/4 bg-accent rounded-full"></div>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-t border-black/5 hover:bg-base/60 transition">
                            <td class="px-6 py-4 font-medium">
                                Web Application
                            </td>
                            <td class="px-6 py-4">89</td>
                            <td class="px-6 py-4">
                                <div class="h-2 bg-base rounded-full">
                                    <div class="h-2 w-1/2 bg-accent rounded-full"></div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section> --}}
        </main>

          @include('layouts.partials.footer')
    </div>

     @include('layouts.partials.scripts')
</body>

</html>

<!-- Pertama, bagian dasboard grafik lingkaran isi nya jumlah pendaftar dari semester 1-2-3,

Lanjut ke manajemen user, kayanya gausah masuk di navigasi, masuk di kanan atas aja tempat buat setting Admin atau author

Lanjut kode kriteria sama Kriteria samain aja kaya ripdah

Lanjut Alternatif pas tambah alternatif tampilan nya nanti gua gambarin tapi bentar dulu

Lanjut di perhitungan nanti ada klasifikasi nya yan, ada Layak - Perlu Pembinaan - Tidak Layak, nanti di sample gua udah ada range nya buat yang layak sampe tidak layak nya -->
