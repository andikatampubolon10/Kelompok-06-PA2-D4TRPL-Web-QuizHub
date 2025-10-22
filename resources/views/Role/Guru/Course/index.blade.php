@extends('layouts.guru-layout')

@section('title', 'Kursus | ' . $kursus->nama_kursus)

@section('content')
    <div class="w-full bg-white p-6 shadow-md">

        {{-- Header --}}
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold text-teal-700">
                {{ $kursus->nama_kursus }}
            </h1>
            <a href="{{ route('Guru.ListSiswa', ['id_kursus' => $kursus->id_kursus]) }}"
                class="bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-user mr-2"></i> Informasi Siswa
            </a>
        </div>

        {{-- Alert --}}
        @if (session('error'))
            <div class="alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="border-b border-gray-300 mb-4"></div>

        {{-- TAB NAV --}}
        <div class="flex mb-4 border-b border-gray-300">
            <button id="tabMateriBtn" class="w-1/2 text-center py-2 font-semibold border-b-2 border-blue-600 text-blue-600">
                Materi
            </button>
            <button id="tabUjianBtn" class="w-1/2 text-center py-2 font-semibold text-gray-600 hover:text-blue-600">
                Ujian
            </button>
        </div>

        {{-- === TAB MATERI === --}}
        <div id="tabMateri">
            <div class="flex justify-end mb-4">
                <a href="{{ route('Guru.Materi.create', ['id_kursus' => $kursus->id_kursus]) }}"
                    class="bg-green-500 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambahkan
                </a>
            </div>
{{-- @dd($kursus) --}}
            @foreach ($materi->sortByDesc('tanggal_materi') as $exam)
            {{-- @dd($exam) --}}
                <div class="mb-4 pb-3 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-base font-semibold text-gray-800">{{ $exam->judul_materi }}</h4>
                            <p class="text-xs text-gray-500 mb-1">
                                {{ \Carbon\Carbon::parse($exam->tanggal_materi)->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-600">{{ $exam->deskripsi }}</p>
                            <a href="{{ $exam->file_url }}" target="_blank"
                                class="inline-flex items-center mt-1 text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-file-download mr-1"></i> Lihat / Unduh File
                            </a>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:space-x-3 text-sm mt-1 sm:mt-0">
                            <form action="{{ route('Guru.Materi.edit', $exam->id_materi) }}" method="GET">
                                <input type="hidden" name="id_kursus" value="{{ $kursus->id_kursus }}">
                                <button type="submit"
                                    class="text-blue-500 hover:text-blue-700 flex items-center mb-1 sm:mb-0">
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

        {{-- === TAB UJIAN === --}}
        <div id="tabUjian" class="hidden">
            <div class="flex justify-end mb-4">
                <a href="{{ route('Guru.Ujian.create', ['id_kursus' => $kursus->id_kursus]) }}"
                    class="bg-green-500 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambahkan Ujian
                </a>
            </div>

           @foreach ($ujian->sortBy('tanggal_ujian') as $exam)
            <div class="border-b border-gray-200 pb-4 mb-4">
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <!-- Judul Ujian -->
                        <a href="{{ url('/Guru/Soal') }}?id_ujian={{ $exam->id_ujian }}"
                            class="block text-xl font-semibold text-gray-800 hover:text-blue-800 hover:underline transition-colors">
                            {{ $exam->tipe_ujian->nama_tipe_ujian }} : {{ $exam->nama_ujian }}
                        </a>

                        <!-- Tanggal Ujian -->
                        <p class="text-sm text-gray-500 mt-1">
                            {{ \Carbon\Carbon::parse($exam->tanggal_ujian)->format('d M Y') }}
                        </p>
                    </div>

                    <!-- Aksi -->
                    <div class="flex space-x-5 justify-end items-center mt-1">
                        <!-- Tombol Bobot -->
                        <a href="{{ route('Guru.BobotTipeSoal.index', ['id_ujian' => $exam->id_ujian]) }}"
                            class="inline-flex items-center text-yellow-500 hover:text-yellow-600 transition-colors">
                            <i class="fas fa-balance-scale mr-1"></i>
                            <span>Bobot</span>
                        </a>
                        <!-- Tombol Selesai -->
                        <a href="{{ route('Guru.Ujian.selesai', ['id_ujian' => $exam->id_ujian]) }}"
                            class="inline-flex items-center text-green-600 hover:text-green-700 transition-colors">
                            <i class="fas fa-check-circle mr-1"></i>
                            <span>Selesai</span>
                        </a>


                        <!-- Tombol Edit -->
                        <form action="{{ route('Guru.Ujian.edit', $exam->id_ujian) }}" method="GET" class="inline-block">
                            <input type="hidden" name="id_kursus" value="{{ $id_kursus }}">
                            <button type="submit" class="text-blue-500 flex items-center hover:text-blue-700 transition-colors">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                        </form>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('Guru.Ujian.destroy', $exam->id_ujian) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus ujian ini?');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 flex items-center hover:text-red-700 transition-colors">
                                <i class="fas fa-trash-alt mr-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        </div>

    </div>

    {{-- SCRIPT UNTUK GANTI TAB --}}
    <script>
        const tabMateriBtn = document.getElementById('tabMateriBtn');
        const tabUjianBtn = document.getElementById('tabUjianBtn');
        const tabMateri = document.getElementById('tabMateri');
        const tabUjian = document.getElementById('tabUjian');

        tabMateriBtn.addEventListener('click', () => {
            tabMateri.classList.remove('hidden');
            tabUjian.classList.add('hidden');
            tabMateriBtn.classList.add('border-blue-600', 'text-blue-600');
            tabUjianBtn.classList.remove('border-blue-600', 'text-blue-600');
            tabUjianBtn.classList.add('text-gray-600');
        });

        tabUjianBtn.addEventListener('click', () => {
            tabMateri.classList.add('hidden');
            tabUjian.classList.remove('hidden');
            tabUjianBtn.classList.add('border-blue-600', 'text-blue-600');
            tabMateriBtn.classList.remove('border-blue-600', 'text-blue-600');
            tabMateriBtn.classList.add('text-gray-600');
        });
    </script>
@endsection
