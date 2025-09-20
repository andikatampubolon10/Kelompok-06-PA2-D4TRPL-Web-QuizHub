@extends('layouts.guru-layout')

@section('content')
<div class="flex flex-col md:flex-row">

    <!-- Main Content -->
    <div class="main-content w-full  p-4 md:p-6">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Informasi Nilai</h2>
            <div class="space-y-4">
                <div class="mb-4">
                @foreach ($courses as $course)
                    <div class="p-5 border border-gray-200 rounded-lg shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between hover:shadow-lg transition-shadow duration-300 bg-white">
                        <div class="flex items-center mb-4 sm:mb-0">
                            <img alt="Thumbnail image of the {{ $course->nama_kursus }} course" class="w-24 h-24 rounded-lg mr-4 object-cover" height="100" src="{{ $course->image_url }}" width="100" />
                            <div>
                                <h4 class="text-2xl font-bold text-teal-700">
                                    <a href="{{ route('Guru.Persentase.create', ['id_kursus' => $course->id_kursus]) }}" class="no-underline hover:underline">
                                        {{ $course->nama_kursus }}
                                    </a>
                                </h4>
                                <p class="text-gray-600"> {{ $course->kelas->nama_kelas }}</p>
                            </div>
                        </div>
                        <div class="flex justify-end sm:flex-none">
                            <form action="{{ route('Guru.Persentase.edit', $course->id_kursus) }}" method="GET">
                                <button type="submit" class="text-blue-600 flex items-center font-semibold hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">
                                    <i class="fas fa-edit mr-2"></i> Edit
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>
@endsection
