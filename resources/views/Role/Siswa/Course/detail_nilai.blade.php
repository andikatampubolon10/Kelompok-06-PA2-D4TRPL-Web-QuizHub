@extends('layouts.siswa')

@section('title', $kursus->nama_kursus . ' - Grades')

@section('breadcrumb')
  <li><a href="{{ route('Siswa.Grades.index') }}" class="hover:text-foreground">Grades</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">{{ $kursus->nama_kursus }}</li>
@endsection

@section('content')
  <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-8 px-4 text-gray-100">
    <div class="max-w-4xl mx-auto">
      <!-- Back Button -->
      <a href="{{ route('Siswa.Grades.index') }}" class="inline-flex items-center gap-2 text-blue-400 hover:text-blue-300 mb-6">
        <span>←</span> Back to Grades
      </a>

      <!-- Course Header -->
      <div class="bg-gray-800 rounded-2xl shadow-lg p-8 mb-8 border border-gray-700">
        <h1 class="text-3xl font-bold text-white mb-2">{{ $kursus->nama_kursus }}</h1>
        <p class="text-gray-400 mb-4">{{ $kursus->mataPelajaran->nama_mata_pelajaran ?? 'N/A' }}</p>
        <p class="text-sm text-gray-400">Taught by: {{ $kursus->guru->nama_guru ?? 'N/A' }}</p>
      </div>

      <!-- Overall Score Card -->
      <div class="bg-gradient-to-r from-blue-700 to-cyan-700 rounded-2xl p-8 mb-8 text-white shadow-lg border border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Score -->
          <div class="flex flex-col items-center justify-center">
            <div class="text-5xl font-bold mb-2">{{ number_format($nilaiTotal, 1) }}</div>
            <div class="text-cyan-100">Final Score</div>
          </div>

          <!-- Grade -->
          <div class="flex flex-col items-center justify-center">
            <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mb-2">
              <span class="text-4xl font-bold">{{ $gradeKursus['grade'] }}</span>
            </div>
            <div class="text-cyan-100">{{ $gradeKursus['label'] }}</div>
          </div>

          <!-- Status -->
          <div class="flex flex-col items-center justify-center">
            @if($nilaiTotal >= 70)
              <div class="w-24 h-24 bg-emerald-800/40 rounded-full flex items-center justify-center mb-2 border border-emerald-600/40">
                <span class="text-4xl">✓</span>
              </div>
              <div class="text-cyan-100">Passed</div>
            @else
              <div class="w-24 h-24 bg-red-800/40 rounded-full flex items-center justify-center mb-2 border border-red-600/40">
                <span class="text-4xl">✗</span>
              </div>
              <div class="text-cyan-100">Failed</div>
            @endif
          </div>
        </div>
      </div>

      <!-- Exam Scores -->
      <div class="bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-700">
        <div class="p-8 border-b border-gray-700">
          <h2 class="text-2xl font-bold text-white">Exam Scores</h2>
        </div>

        <div class="divide-y divide-gray-700">
          @forelse($ujianNilai as $item)
            <div class="p-6 hover:bg-gray-700/60 transition-colors">
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <h3 class="text-lg font-semibold text-white">{{ $item['ujian']->nama_ujian }}</h3>
                  <p class="text-sm text-gray-400 mt-1">
                    Type: {{ $item['ujian']->tipe_ujian->nama_tipe_ujian ?? 'N/A' }}
                  </p>
                </div>

                <div class="flex items-center gap-6">
                  <!-- Score -->
                  <div class="text-right">
                    <div class="text-3xl font-bold text-white">{{ number_format($item['nilai'], 1) }}</div>
                    <div class="text-xs text-gray-400">Score</div>
                  </div>

                  <!-- Grade Badge -->
                  <div class="w-16 h-16 bg-gray-700 rounded-lg flex items-center justify-center border border-blue-500">
                    <div class="text-center">
                      <div class="text-2xl font-bold text-blue-400">{{ $item['grade']['grade'] }}</div>
                      <div class="text-xs text-gray-300">{{ $item['grade']['label'] }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="p-8 text-center text-gray-400">
              No exam scores available yet
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection
