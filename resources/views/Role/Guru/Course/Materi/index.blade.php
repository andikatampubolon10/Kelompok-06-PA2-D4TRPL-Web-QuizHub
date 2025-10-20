@extends('layouts.guru-layout')

@section('title', 'Materi | ' . $kursus->nama_kursus)


@section('content')
    <div class="w-full bg-white p-6 shadow-md">
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold text-teal-700">
                {{ $kursus->nama_kursus }}
            </h1>
            <a href="{{ route('Guru.ListSiswa', ['id_kursus' => $kursus->id_kursus]) }}"
                class="bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-user mr-2"></i> Informasi Siswa
            </a>
        </div>

        @if (session('error'))
            <div class="alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="border-b border-gray-300 mb-4"></div>
        <div class="flex mb-4">
            <div class="w-1/2 border-b-2 border-blue-600 pb-2 flex justify-center items-center">
                <a href="">
                    <h3 class="text-lg font-semibold">Materi</h3>
                </a>
            </div>
            <div class="w-1/2 pb-2 flex justify-center items-center">
                <a href="{{ url('/Guru/Ujian?id_kursus=' . $kursus->id_kursus) }}">
                    <h3 class="text-lg font-semibold">Ujian</h3>
                </a>
            </div>
        </div>

        <div class="flex justify-end mb-4">
            <a href="{{ route('Guru.Materi.create', ['id_kursus' => $kursus->id_kursus]) }}"
                class="bg-green-500 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambahkan
            </a>
        </div>
        {{-- @dd($materi) --}}

        @foreach ($materi->sortByDesc('tanggal_materi') as $exam)
            <div class="mb-4 pb-3 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <!-- Judul -->
                        <h4 class="text-base font-semibold text-gray-800">
                            {{ $exam->judul_materi }}
                        </h4>

                        <!-- Tanggal di bawah judul -->
                        <p class="text-xs text-gray-500 mb-1">
                            {{ \Carbon\Carbon::parse($exam->tanggal_materi)->format('d M Y') }}
                        </p>

                        <!-- Deskripsi -->
                        <p class="text-sm text-gray-600">{{ $exam->deskripsi }}</p>

                        <!-- Link File -->
                        <a href="{{ $exam->file_url }}" target="_blank"
                            class="inline-flex items-center mt-1 text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-file-download mr-1"></i>Lihat / Unduh File
                        </a>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row sm:space-x-3 text-sm mt-1 sm:mt-0">
                        <form action="{{ route('Guru.Materi.edit', $exam->id_materi) }}" method="GET">
                            <input type="hidden" name="id_kursus" value="{{ $kursus->id_kursus }}">
                            <button type="submit" class="text-blue-500 hover:text-blue-700 flex items-center mb-1 sm:mb-0">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                        </form>

                        <form action="{{ route('Guru.Materi.destroy', $exam->id_materi) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 flex items-center">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach


    </div>
@endsection
