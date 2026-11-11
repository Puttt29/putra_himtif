<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        // =========================
        // TABEL ANGKATAN
        // =========================
        Schema::create('angkatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_angkatan')->unique();
            $table->timestamps();
        });

        // =========================
        // TABEL KODE KRITERIA
        // =========================
        Schema::create('kode_kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kriteria')->unique();
            $table->timestamps();
        });

        // =========================
        // TABEL KRITERIA
        // =========================
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kode_kriteria_id')
                ->constrained('kode_kriteria')
                ->cascadeOnDelete();

            $table->string('nama_kriteria');
            $table->enum('atribut', ['benefit', 'cost']);
            $table->decimal('bobot', 8, 4);
            $table->decimal('normalisasi', 8, 4)->nullable();
            $table->timestamps();

            $table->unique(['kode_kriteria_id', 'nama_kriteria']);
        });

        // =========================
        // TABEL ALTERNATIF
        // =========================
        Schema::create('alternatif', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alternatif');
            $table->string('catatan_pewawancara')->nullable();
            $table->string('nim')->nullable();

            $table->foreignId('angkatan_id')
                ->constrained('angkatan')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique('nama_alternatif');
        });

        // =========================
        // TABEL NILAI ALTERNATIF
        // =========================
        Schema::create('nilai_alternatif', function (Blueprint $table) {
            $table->id();

            $table->foreignId('alternatif_id')
                ->constrained('alternatif')
                ->cascadeOnDelete();

            $table->foreignId('kriteria_id')
                ->constrained('kriteria')
                ->cascadeOnDelete();

            $table->decimal('nilai', 8, 4)->default(0);
            $table->string('catatan')->nullable();
            $table->timestamps();

            $table->unique(['alternatif_id', 'kriteria_id']);
        });

        // =========================
        // TABEL PERHITUNGAN SESSIONS
        // =========================
        Schema::create('perhitungan_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perhitungan');

            $table->foreignId('angkatan_id')
                ->constrained('angkatan')
                ->cascadeOnDelete();

            $table->timestamp('tanggal_hitung');
            $table->timestamps();
        });

        // =========================
        // TABEL PERHITUNGAN
        // =========================
        Schema::create('perhitungan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('perhitungan_session_id')
                ->constrained('perhitungan_sessions')
                ->cascadeOnDelete();

            $table->foreignId('alternatif_id')
                ->constrained('alternatif')
                ->cascadeOnDelete();

            $table->foreignId('kriteria_id')
                ->constrained('kriteria')
                ->cascadeOnDelete();

            $table->decimal('nilai_utilitas', 8, 4)->nullable();
            $table->decimal('nilai_akhir', 8, 4)->nullable();
            $table->timestamps();

            $table->unique(
                ['perhitungan_session_id', 'alternatif_id', 'kriteria_id'],
                'uq_perhitungan_session_alt_krit'
            );
        });

        // =========================
        // TABEL PIVOT ALTERNATIF - PEWAWANCARA
        // =========================
        Schema::create('alternatif_pewawancara', function (Blueprint $table) {
            $table->id();

            $table->foreignId('alternatif_id')
                ->constrained('alternatif')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['alternatif_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternatif_pewawancara');
        Schema::dropIfExists('perhitungan');
        Schema::dropIfExists('perhitungan_sessions');
        Schema::dropIfExists('nilai_alternatif');
        Schema::dropIfExists('alternatif');
        Schema::dropIfExists('kriteria');
        Schema::dropIfExists('kode_kriteria');
        Schema::dropIfExists('angkatan');
    }
};
