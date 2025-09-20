@extends('layouts.guru-layout')

@section('content')
<div class="flex flex-col md:flex-row">

    <!-- Main Content -->
    <div class="main-content w-full  p-4 md:p-6">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Informasi Kursus</h2>
            <div class="space-y-4">
                <div class="mb-4">
                    @foreach ($courses as $course)
                        <div class="bg-gray-100 p-4 rounded-lg shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                            <div class="flex items-center mb-4 sm:mb-0">
                                <img alt="Thumbnail image of the {{ $course->nama_kursus }} course"
                                     class="w-24 h-24 rounded-lg mr-4 object-cover"
                                     src="{{ $course->image_url }}" />
                                <div>
                                    <h4 class="text-2xl font-bold text-teal-700">
                                        <a href="{{ route('Guru.Materi.index', ['id_kursus' => $course->id_kursus]) }}"
                                           class="no-underline hover:underline">
                                            {{ $course->nama_kursus }}
                                        </a>
                                    </h4>
                                    <p class="text-gray-600">{{ $course->kelas->nama_kelas }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
