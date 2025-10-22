@extends('layouts.siswa')

@section('title', 'Hasil Latihan')

@section('breadcrumb')
  <li><a href="{{ route('Siswa.latihan.kurikulum') }}" class="hover:text-foreground">Kurikulum</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Hasil Latihan</li>
@endsection

@section('content')
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-12 px-4">
    <div class="max-w-2xl mx-auto text-center">
      <h1 class="text-4xl font-bold text-white mb-2">{{ $latihan->Topik }}</h1>
      <p class="text-slate-400">Kelas: {{ $kelas->nama_kelas }}</p>
      
      <div class="mt-8 text-white">
        <h2 class="text-3xl font-semibold">Nilai Akhir: {{ number_format($score, 2) }}%</h2>
        <p class="mt-2">Jawaban Benar: {{ $correctAnswers }}/{{ $totalQuestions }}</p>
        <p class="mt-2">Bobot Soal Benar: {{ $totalWeight }}</p>
      </div>

      <div class="mt-8">
        <a href="{{ route('Siswa.latihan.kurikulum') }}" class="px-8 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">Kembali ke Latihan</a>
      </div>
    </div>
  </div>
@endsection
