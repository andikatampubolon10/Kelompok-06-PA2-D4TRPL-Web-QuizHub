@extends('layouts.siswa')

@section('title', 'My Courses - eCourse')

@section('breadcrumb')
    <li class="text-foreground">My Courses</li>
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2">My Courses</h1>
        <p class="text-muted-foreground">Manage your enrolled courses and access exams</p>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded bg-red-100 px-4 py-3 text-red-800">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @forelse ($courses as $course)
            <a href="{{ route('Siswa.Course.tipeujian', ['id_kursus' => $course->id_kursus]) }}"
                class="block bg-card rounded-lg p-4 border border-border hover:shadow-md transition-shadow cursor-pointer">

                {{-- gambar lebih pendek --}}
                <div class="h-24 rounded-md mb-3 overflow-hidden">
                    <img src="{{ $course->foto_url ?? 'https://placehold.co/600x400?text=Kursus' }}"
                        alt="{{ $course->nama_kursus }}" class="w-full h-full object-cover">
                </div>

                {{-- judul & meta: font lebih kecil, margin dipadatkan --}}
                <h3 class="font-semibold text-sm mb-1 line-clamp-1">{{ $course->nama_kursus }}</h3>

                <div class="text-xs text-muted-foreground space-y-0.5 mb-2.5">
                    <p>
                        <span class="font-medium">Mapel:</span>
                        {{ optional($course->mataPelajaran)->nama_mata_pelajaran ?? '-' }}
                    </p>
                    <p>
                        <span class="font-medium">Kelas:</span>
                        {{ optional($course->kelas)->nama_kelas ?? '-' }}
                    </p>
                    <p>
                        <span class="font-medium">Guru:</span>
                        {{ optional($course->guru)->nama ?? '-' }}
                    </p>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-[10px] bg-secondary px-1.5 py-0.5 rounded">Enrolled</span>
                    <i class="fas fa-arrow-right text-muted-foreground text-xs"></i>
                </div>
            </a>
        @empty
            <div
                class="col-span-1 sm:col-span-2 md:col-span-3 rounded-lg border border-dashed p-6 text-center text-muted-foreground">
                Belum ada kursus yang kamu ikuti.
            </div>
        @endforelse
    </div>
@endsection
