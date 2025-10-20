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
            <div class="w-1/2 pb-2 flex justify-center items-center">

                <a href="{{ url('/Guru/Materi?id_kursus=' . $course->id_kursus) }}" class="block text-xl font-semibold  hover:text-blue-800 hover:underline cursor-pointer transition-colors">
                    <h3 class="text-lg font-semibold">Materi</h3>
                </a>
            </div>
            <div class="w-1/2 border-b-2 border-blue-600 pb-2 flex justify-center items-center">
                <a href="{{ url('/Guru/Ujian?id_kursus=' . $kursus->id_kursus) }}" class="block text-xl font-semibold  hover:text-blue-800 hover:underline cursor-pointer transition-colors">
                    <h3 class="text-lg font-semibold">Ujian</h3>
                </a>
            </div>
        </div>

        <div class="flex justify-end mb-4">
            <a href="{{ route('Guru.Ujian.create', ['id_kursus' => $id_kursus]) }}"
                class="bg-green-500 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambahkan
            </a>
        </div>

<<<<<<< Updated upstream
      @foreach ($ujians->sortBy('tanggal_ujian') as $exam)
    <div class="border-b border-gray-200 pb-4 mb-4">
        <div class="flex justify-between items-start">
            <div class="flex-grow">
                <!-- Judul Ujian -->
                <a href="{{ url('/Guru/Soal') }}?id_ujian={{ $exam->id_ujian }}" 
                   class="block text-xl font-semibold text-gray-800 hover:text-blue-800 hover:underline transition-colors">
                    {{ $exam->tipe_ujian->nama_tipe_ujian }} : {{ $exam->nama_ujian }}
                </a>
=======
        @foreach ($ujians->sortBy('tanggal_ujian') as $exam)
            <h4 class="text-lg font-semibold mb-2">
                {{ \Carbon\Carbon::parse($exam->tanggal_ujian)->format('d M Y') }}
            </h4>
            <div class="flex justify-between items-center mb-4">
                <div class="flex-grow">
                    <div class="exam-container">
                        <a href="{{ url('/Guru/Soal') }}?id_ujian={{ $exam->id_ujian }}" class="exam-link">
                            <h2 class="exam-title">{{ $exam->tipe_ujian->nama_tipe_ujian }} : {{ $exam->nama_ujian }}</h2>
                        </a>

                        <style>
                            .exam-link {
                                text-decoration: none;
                                /* Menghilangkan garis bawah default */
                                color: #007bff;
                                /* Menentukan warna link (biru standar) */
                                transition: color 0.3s ease, text-decoration 0.3s ease;
                                /* Efek transisi saat hover */
                            }

                            .exam-link:hover {
                                color: #0056b3;
                                /* Warna saat hover */
                                text-decoration: underline;
                                /* Menambahkan garis bawah saat hover */
                            }

                            .exam-title {
                                font-size: 1rem;
                                /* Ukuran font */
                                font-weight: 600;
                                /* Membuat teks lebih tebal */
                            }
                        </style>
                    </div>
                </div>
>>>>>>> Stashed changes

                <!-- Tanggal di bawah judul -->
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
@endsection
