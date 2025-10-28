@extends('layouts.guru-layout')

@section('title', 'Guru | Kursus | List Siswa')

@section('content')
<div class="w-full bg-white p-6 shadow-md rounded-lg">
    {{-- Flash --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded bg-green-50 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-3 rounded bg-red-50 text-red-700 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    {{-- Breadcrumb --}}
    <nav class="text-gray-600 text-lg mb-4" aria-label="Breadcrumb">
        <ol class="list-reset flex">
            <li><a href="{{ route('Guru.Course.index') }}" class="text-blue-600 hover:underline">Course</a></li>
            <li><span class="mx-2">/</span></li>
            <li><span class="text-blue-600">List Siswa</span></li>
        </ol>
    </nav>

    {{-- Judul Kursus --}}
    <div class="border-b border-gray-300 pb-1 mb-4">
        <h1 class="text-3xl leading-none font-semibold text-teal-700">
            {{ $kursus->nama_kursus }}
        </h1>
    </div>

    {{-- Persentase + Aksi --}}
    <div class="mb-8 space-y-4">
        @foreach ($persentase as $percent)
            @php $nm = $percent->tipePersentase->nama_persentase ?? ''; @endphp
            @if ($nm === 'persentase_kuis')
                <div class="text-base">Persentase Kuis: {{ $percent->persentase }}%</div>
            @elseif ($nm === 'persentase_UTS')
                <div class="text-base">Persentase UTS: {{ $percent->persentase }}%</div>
            @elseif ($nm === 'persentase_UAS')
                <div class="text-base">Persentase UAS: {{ $percent->persentase }}%</div>
            @endif
        @endforeach

        <div class="flex gap-3">
            <form action="{{ route('Guru.reset-recalculate-nilai', ['id_kursus' => $kursus->id_kursus]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin reset dan menghitung ulang semua nilai?')">
                @csrf
                <button type="submit" class="px-4 py-2 text-base border border-green-700 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                    <i class="fas fa-calculator mr-1"></i> Hitung Nilai
                </button>
            </form>

            <a href="{{ route('Guru.nilai.export', ['id_kursus' => $kursus->id_kursus]) }}"
               class="px-4 py-2 text-base border border-blue-700 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors ml-auto">
               Export Nilai
            </a>
        </div>
    </div>

    {{-- List Siswa --}}
    <section class="border-t border-gray-300 pt-4 mb-4">
        <h2 class="text-2xl mb-4 font-semibold">List Siswa</h2>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse bg-white rounded-lg shadow">
                <thead>
                    <tr class="bg-teal-600 text-white">
                        <th class="px-3 py-2 text-center">NIS</th>
                        <th class="px-3 py-2 text-center">Nama Siswa</th>
                        <th class="px-3 py-2 text-center">Kelas</th>
                        <th class="px-3 py-2 text-center">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $student)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-3 py-2 text-center">{{ $student->nis }}</td>
                            <td class="px-3 py-2 text-center">{{ $student->nama_siswa }}</td>
                            <td class="px-3 py-2 text-center">{{ $student->kelas->nama_kelas ?? 'Kelas tidak tersedia' }}</td>
                            <td class="px-3 py-2 text-center">
                                @php
                                    $n = optional($nilai->get($student->id_siswa))->nilai_total;
                                @endphp
                                {{ is_numeric($n) ? number_format($n, 2) : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
