@extends('layouts.guru-layout')

@section('title', 'Siswa Selesai Ujian')

@section('content')
    <div class="bg-white p-6 shadow rounded-lg">
        <h2 class="text-2xl font-bold text-teal-700 mb-4">
            Daftar Siswa yang Telah Menyelesaikan {{ $ujian->nama_ujian }}
        </h2>

        <table class="min-w-full border border-gray-300 text-sm">
            <thead class="bg-teal-600 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">Nama Siswa</th>
                    <th class="px-4 py-2 text-left">Total Jawaban</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaSelesai as $index => $siswa)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('Guru.Ujian.jawabanSiswa', ['id_ujian' => $ujian->id_ujian, 'id_siswa' => $siswa->id_siswa]) }}"
                                class="text-blue-600 hover:underline">
                                {{ $siswa->nama_siswa }}
                            </a>
                        </td>
                        <td class="px-4 py-2">{{ $siswa->total_jawaban }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-500">Belum ada siswa yang menyelesaikan ujian ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection