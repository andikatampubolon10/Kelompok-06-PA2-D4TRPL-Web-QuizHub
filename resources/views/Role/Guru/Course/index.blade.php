@extends('layouts.guru-layout')

@section('title', 'Materi | ' . $kursus->nama_kursus)



@section('content')
    <div class="w-full bg-white p-6 shadow-md">
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold text-teal-700">
                {{ $kursus->nama_kursus }}
            </h1>
            <a href="{{ route('Guru.ListSiswa', ['id_kursus' => $kursus->id_kursus]) }}" class="bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
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
            
                <a href="{{ url('/Guru/Materi?id_kursus=' . $course->id_kursus) }}">
                    <h3 class="text-lg font-semibold">Materi</h3>
                </a>
            </div>
            <div class="w-1/2 border-b-2 border-blue-600 pb-2 flex justify-center items-center">
                <a href="{{ url('/Guru/Ujian?id_kursus=' . $kursus->id_kursus) }}">
                    <h3 class="text-lg font-semibold">Ujian</h3>
                </a>
            </div>
        </div>

        <div class="flex justify-end mb-4">
            <a href="{{ route('Guru.Ujian.create', ['id_kursus' => $id_kursus]) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambahkan
            </a>
        </div>

       @foreach ($ujians->sortBy('tanggal_ujian') as $exam)
                    <h4 class="text-lg font-semibold mb-2">
                        {{ \Carbon\Carbon::parse($exam->tanggal_ujian)->format('d M Y') }}
                    </h4>
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex-grow">
                            <div class="exam-container">
                                <a href="{{ url('/Guru/Soal') }}?id_ujian={{ $exam->id_ujian }}">
                                    <h2 class="exam-title">{{ $exam->tipe_ujian->nama_tipe_ujian }} :
                                        {{ $exam->nama_ujian }}</h2>
                                </a>
                            </div>
                        </div>

                        <div class="flex space-x-5 justify-end">
                            <form action="{{ route('Guru.Ujian.destroy', $exam->id_ujian) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 flex items-center hover:text-red-700">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                </button>
                            </form>

                            <form action="{{ route('Guru.Ujian.edit', $exam->id_ujian) }}" method="GET">
                                <input type="hidden" name="id_kursus" value="{{ $id_kursus }}">
                                <button type="submit" class="text-blue-500 flex items-center hover:text-blue-700">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
    </div>
@endsection
