@extends('layouts.guru-layout')

@section('content')
    <div class="flex flex-col md:flex-row">

        <!-- Main Content -->
        <div class="main-content w-full  p-4 md:p-6">
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4 text-blue-600">Informasi Kursus</h2>
                <div class="space-y-4">
                    <div class="mb-4">
                        @forelse($courses as $course)
                            <div
                                class="bg-gray-100 p-4 rounded-lg shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                                <div class="flex items-center mb-4 sm:mb-0">
                                    <img alt="Thumbnail image of the {{ $course->nama_kursus }} course"
                                        class="w-24 h-24 rounded-lg mr-4 object-cover" src="{{ $course->image_url }}" />
                                    <div>
                                        <h4 class="text-2xl font-bold text-teal-700">
                                            <a href="{{ route('Guru.Materi.index', ['id_kursus' => $course->id_kursus]) }}"
                                                class="no-underline hover:underline">
                                                {{ $course->nama_kursus }}
                                            </a>
                                        </h4>
                                        <p class="text-gray-600">{{ $course->kelas->nama_kelas }}</p>
                                        {{-- Tambahkan baris ini untuk menampilkan mata pelajaran --}}
                                        <p class="text-sm text-gray-800 italic">
                                            <strong>Mata Pelajaran:</strong>
                                            {{ $course->mataPelajaran ? $course->mataPelajaran->nama_mata_pelajaran : 'Belum Ditentukan' }}
                                        </p>
                                        {{-- Akhir tambahan --}}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p>Tidak ada kursus yang ditemukan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
