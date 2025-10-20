@extends('layouts.guru-layout')

@section('title', 'Tambah Ujian')

@section('content')
    {{-- Tips --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-600 text-sm"></i>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-blue-800 mb-1">Tips Penambahan Ujian</h4>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Masukkan Nama Ujian</li>
                    <li>• Masukkan Password Masuk dan Keluar Ujian</li>
                    <li>• Pilih Tipe Ujian</li>
                    <li>• Pilih Waktu Mulai dan Selesai Ujian</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
      <div class="mb-6 p-4 border border-red-300 bg-red-50 rounded text-sm text-red-700">
        <ul class="list-disc pl-5 space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="form-container mx-auto bg-white p-8 rounded-lg shadow-lg">
        <form action="{{ route('Guru.Ujian.store') }}" method="POST">
            @csrf

            {{-- Judul Ujian --}}
            <div class="mb-6">
                <label for="nama_ujian" class="block font-bold mb-2">Judul Ujian</label>
                <input type="text" id="nama_ujian" name="nama_ujian"
                       class="block w-full p-2 border border-gray-300 rounded-md"
                       value="{{ old('nama_ujian') }}" required>
                @error('nama_ujian')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Masuk --}}
            <div class="mb-6">
                <label for="password_masuk" class="block font-bold mb-2">Password Masuk</label>
                <input type="password" id="password_masuk" name="password_masuk"
                       class="block w-full p-2 border border-gray-300 rounded-md" required>
                @error('password_masuk')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Keluar --}}
            <div class="mb-6">
                <label for="password_keluar" class="block font-bold mb-2">Password Keluar</label>
                <input type="password" id="password_keluar" name="password_keluar"
                       class="block w-full p-2 border border-gray-300 rounded-md" required>
                @error('password_keluar')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- id_kursus wajib dikirim --}}
            <input type="hidden" name="id_kursus" value="{{ $id_kursus }}">

            {{-- Tipe Ujian --}}
            <div class="mb-6">
                <label class="block font-bold mb-2">Tipe Ujian</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="id_tipe_ujian" value="1" class="form-radio"
                               {{ old('id_tipe_ujian') == 1 ? 'checked' : '' }} required>
                        <span class="ml-2">Kuis</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="id_tipe_ujian" value="2" class="form-radio"
                               {{ old('id_tipe_ujian') == 2 ? 'checked' : '' }} required>
                        <span class="ml-2">Ujian Tengah Semester</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="id_tipe_ujian" value="3" class="form-radio"
                               {{ old('id_tipe_ujian') == 3 ? 'checked' : '' }} required>
                        <span class="ml-2">Ujian Akhir Semester</span>
                    </label>
                </div>
                @error('id_tipe_ujian')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Waktu Mulai --}}
            <div class="mb-6">
                <label for="waktu_mulai" class="block font-bold mb-2">Waktu Mulai</label>
                <input type="datetime-local" id="waktu_mulai" name="waktu_mulai"
                       class="block w-full p-2 border border-gray-300 rounded-md"
                       value="{{ old('waktu_mulai') }}" required>
                @error('waktu_mulai')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Waktu Selesai --}}
            <div class="mb-6">
                <label for="waktu_selesai" class="block font-bold mb-2">Waktu Selesai</label>
                <input type="datetime-local" id="waktu_selesai" name="waktu_selesai"
                       class="block w-full p-2 border border-gray-300 rounded-md"
                       value="{{ old('waktu_selesai') }}" required>
                @error('waktu_selesai')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-6 border-t border-gray-100 mt-8">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-info-circle"></i>
                    <span>Semua field yang bertanda (*) wajib diisi</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-3 mt-4 sm:mt-0">
                    <a href="{{ route('Guru.Ujian.index', ['id_kursus' => $course->id_kursus]) }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>

                    <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Flash messages tetap --}}
    @if (session('success')) ... @endif
    @if (session('error')) ... @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const waktuMulai = document.getElementById('waktu_mulai');
    const waktuSelesai = document.getElementById('waktu_selesai');

    // Ambil waktu sekarang (local time)
    const now = new Date();
    const timezoneOffset = now.getTimezoneOffset() * 60000; // ubah offset timezone ke ms
    const localISOTime = new Date(now - timezoneOffset).toISOString().slice(0, 16);

    // Set agar tanggal sebelum hari ini tidak bisa dipilih
    waktuMulai.min = localISOTime;
    waktuSelesai.min = localISOTime;

    // Jika waktu mulai diubah, waktu selesai minimal harus >= waktu mulai
    waktuMulai.addEventListener('change', function() {
        waktuSelesai.min = this.value;
    });
});
        
      // Batasi minimal waktu dari client
      document.addEventListener('DOMContentLoaded', function() {
        const waktuMulai = document.getElementById('waktu_mulai');
        const waktuSelesai = document.getElementById('waktu_selesai');
        const now = new Date().toISOString().slice(0,16);
        if (!waktuMulai.value) waktuMulai.min = now;
        if (!waktuSelesai.value) waktuSelesai.min = now;

        waktuMulai.addEventListener('change', function(){
          waktuSelesai.min = this.value;
        });
      });
    </script>
@endsection
