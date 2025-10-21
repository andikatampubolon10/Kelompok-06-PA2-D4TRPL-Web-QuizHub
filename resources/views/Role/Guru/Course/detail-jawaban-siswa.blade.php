@extends('layouts.guru-layout')

@section('title', 'Jawaban ' . $siswa->nama_siswa . ' | ' . $ujian->nama_ujian)

@section('content')
<div class="bg-white p-6 shadow rounded-lg">
  <div class="mb-4">
    <h2 class="text-2xl font-bold text-teal-700">Jawaban Siswa</h2>
    <p class="text-gray-600">
      Ujian: <span class="font-semibold">{{ $ujian->nama_ujian }}</span> —
      Siswa: <span class="font-semibold">{{ $siswa->nama_siswa }}</span>
    </p>
  </div>

  <table class="min-w-full border border-gray-300 text-sm">
    <thead class="bg-teal-600 text-white">
      <tr>
        <th class="px-4 py-2 text-left">No</th>
        <th class="px-4 py-2 text-left">ID Soal</th>
        <th class="px-4 py-2 text-left">Teks Soal</th>
        <th class="px-4 py-2 text-left">Jawaban Siswa</th>
        <th class="px-4 py-2 text-left">Opsi Dipilih</th>
      </tr>
    </thead>
    <tbody>
      @forelse($jawaban as $i => $j)
        <tr class="border-b hover:bg-gray-50">
          <td class="px-4 py-2">{{ $i+1 }}</td>
          <td class="px-4 py-2">{{ $j->id_soal }}</td>

          {{-- Teks soal: sesuaikan nama kolom yang ada di tabel soal-mu.
               Coba beberapa kemungkinan aman: --}}
          <td class="px-4 py-2">
            {{ $j->soal->pertanyaan
               ?? $j->soal->isi_soal
               ?? $j->soal->teks_soal
               ?? '-' }}
          </td>

          {{-- isian bebas (essay) atau huruf pilihan siswa --}}
          <td class="px-4 py-2">
            {{ $j->jawaban_siswa ?? '-' }}
          </td>

          {{-- Jika menggunakan tabel jawaban_soal (opsi multiple choice),
               sesuaikan nama kolom opsi, contoh: teks_jawaban / jawaban / pilihan --}}
          <td class="px-4 py-2">
            {{ optional($j->jawaban_soal)->teks_jawaban
               ?? optional($j->jawaban_soal)->jawaban
               ?? optional($j->jawaban_soal)->pilihan
               ?? '-' }}
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center py-4 text-gray-500">
            Belum ada jawaban untuk siswa ini pada ujian ini.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-4">
    <a href="{{ route('Guru.Ujian.selesai', $ujian->id_ujian) }}"
       class="inline-flex items-center px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800">
       ← Kembali
    </a>
  </div>
</div>
@endsection
