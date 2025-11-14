@extends('layouts.guru-layout')

@section('content')

<div class="flex min-h-screen bg-white">  <!-- FULL HEIGHT + WHITE BG -->

    <!-- Sidebar -->
    <aside class="w-full md:w-1/4 p-4 border-r border-gray-300 bg-white flex-shrink-0">
        <h3 class="text-lg font-bold text-gray-600 mb-3">Daftar Mata Pelajaran</h3>

        <ul class="space-y-2">

            <!-- Semua Mapel -->
            <li>
                <a href="{{ route('Guru.Course.index') }}"
                    class="block px-2 py-1 rounded-md 
                        {{ $selectedMataPelajaran ? 'text-gray-700 hover:text-blue-600' : 'text-blue-600 font-semibold' }}">
                    Semua Mata Pelajaran
                </a>
            </li>

            <!-- List Mapel -->
            @foreach($mataPelajaranOptions as $mapel)
                <li>
                    <a href="{{ route('Guru.Course.index', ['id_mata_pelajaran' => $mapel->id_mata_pelajaran]) }}"
                        class="block px-2 py-1 rounded-md
                            {{ $selectedMataPelajaran == $mapel->id_mata_pelajaran 
                                ? 'text-blue-600 font-semibold' 
                                : 'text-gray-700 hover:text-blue-600' }}">
                        {{ $mapel->nama_mata_pelajaran }}
                    </a>
                </li>
            @endforeach

        </ul>
    </aside>

    <!-- Content -->
    <div class="w-full md:w-3/4 p-4 md:p-6 bg-white">
        <h2 class="text-xl font-bold text-blue-600 mb-4">
            Informasi Kursus
            @if($selectedMataPelajaran)
                <span class="text-sm text-gray-500">
                    (Filter: {{ $mataPelajaranOptions->find($selectedMataPelajaran)->nama_mata_pelajaran }})
                </span>
            @endif
        </h2>

        <div class="space-y-6">

            @forelse($courses as $course)
                <div class="pb-4 border-b border-gray-300 flex items-start space-x-4">

                    <img alt="{{ $course->nama_kursus }}"
                        src="{{ $course->image_url }}"
                        class="w-20 h-20 rounded-md object-cover">

                    <div>
                        <h4 class="text-2xl font-bold text-teal-700">
                            <a href="{{ route('Guru.Materi.index', ['id_kursus' => $course->id_kursus]) }}"
                               class="hover:underline">
                                {{ $course->nama_kursus }}
                            </a>
                        </h4>

                        <p class="text-gray-600">{{ $course->kelas->nama_kelas }}</p>

                        <p class="text-sm text-gray-800 italic">
                            <strong>Mata Pelajaran:</strong>
                            {{ $course->mataPelajaran ? $course->mataPelajaran->nama_mata_pelajaran : 'Belum Ditentukan' }}
                        </p>
                    </div>

                </div>

            @empty
                <p class="text-gray-600">Tidak ada kursus untuk mata pelajaran ini.</p>
            @endforelse

        </div>
    </div>

</div>

@endsection
