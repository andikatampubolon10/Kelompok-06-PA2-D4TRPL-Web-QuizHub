@extends('layouts.siswa')

@section('title', 'My Grades')

@section('breadcrumb')
  <li class="text-foreground">Grades</li>
@endsection

@section('content')
  <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-8 px-4 text-gray-100">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">My Grades</h1>
        <p class="text-gray-400">View your academic performance across all courses</p>
      </div>

      <!-- Overall Summary Card -->
      <div class="bg-gradient-to-r from-blue-700 to-cyan-700 rounded-2xl p-8 mb-8 text-white shadow-xl border border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <!-- Overall GPA -->
          <div class="flex flex-col items-center justify-center">
            <div class="text-5xl font-bold mb-2">{{ number_format($nilaiOverall, 2) }}</div>
            <div class="text-cyan-100 text-sm">Overall Average</div>
          </div>

          <!-- Grade Badge -->
          <div class="flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-2">
              <span class="text-3xl font-bold">{{ $gradeOverall['grade'] }}</span>
            </div>
            <div class="text-cyan-100 text-sm">{{ $gradeOverall['label'] }}</div>
          </div>

          <!-- Courses Taken -->
          <div class="flex flex-col items-center justify-center">
            <div class="text-5xl font-bold mb-2">{{ $totalKursus }}</div>
            <div class="text-cyan-100 text-sm">Courses Taken</div>
          </div>

          <!-- Status -->
          <div class="flex flex-col items-center justify-center">
            <div class="text-5xl font-bold mb-2">{{ $kursusNilai->count() }}</div>
            <div class="text-cyan-100 text-sm">Enrolled Courses</div>
          </div>
        </div>
      </div>

      <!-- Courses Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kursusNilai as $kursus)
          @php
          if (!function_exists('calc_grade')) {
            function calc_grade($n) {
                $n = (float) $n;
                if ($n >= 85) return ['grade' => 'A',  'label' => 'Excellent'];
                if ($n >= 75) return ['grade' => 'B',  'label' => 'Good'];
                if ($n >= 65) return ['grade' => 'C',  'label' => 'Satisfactory'];
                if ($n >= 55) return ['grade' => 'D',  'label' => 'Needs Improvement'];
                return ['grade' => 'E', 'label' => 'Poor'];
            }
          }
          $nilai = $kursus->nilai->first();
          $nilaiTotal = $nilai ? $nilai->nilai_total : 0;
          $grade = calc_grade($nilaiTotal);
          $percentage = min(100, max(0, $nilaiTotal));
          @endphp

          <a href="{{ route('Siswa.Grades.course', $kursus->id_kursus) }}" 
             class="group bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-700 hover:border-blue-500">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-700 p-6 border-b border-gray-700">
              <h3 class="text-lg font-semibold text-white group-hover:text-blue-400 transition-colors">
                {{ $kursus->nama_kursus }}
              </h3>
              <p class="text-sm text-gray-400 mt-1">
                {{ $kursus->mataPelajaran->nama_mata_pelajaran ?? 'N/A' }}
              </p>
            </div>

            <!-- Content -->
            <div class="p-6">
              <!-- Score Circle -->
              <div class="flex justify-center mb-6">
                <div class="relative w-32 h-32">
                  <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="54" fill="none" stroke="#374151" stroke-width="6"/>
                    <circle 
                      cx="60" 
                      cy="60" 
                      r="54" 
                      fill="none" 
                      stroke="url(#gradient-{{ $kursus->id_kursus }})" 
                      stroke-width="6"
                      stroke-dasharray="{{ ($percentage / 100) * 339.29 }} 339.29"
                      stroke-linecap="round"
                      class="transition-all duration-500"
                    />
                    <defs>
                      <linearGradient id="gradient-{{ $kursus->id_kursus }}" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#06b6d4;stop-opacity:1" />
                      </linearGradient>
                    </defs>
                  </svg>
                  <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <div class="text-3xl font-bold text-white">{{ number_format($nilaiTotal, 0) }}</div>
                    <div class="text-xs text-gray-400">out of 100</div>
                  </div>
                </div>
              </div>

              <!-- Grade Badge -->
              <div class="flex justify-center mb-4">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 border border-blue-500 rounded-full">
                  <span class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                    {{ $grade['grade'] }}
                  </span>
                  <span class="text-sm font-semibold text-gray-200">{{ $grade['label'] }}</span>
                </span>
              </div>

              <!-- Status -->
              <div class="text-center">
                @if($nilaiTotal >= 70)
                  <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-emerald-800 text-emerald-100">
                    ✓ Passed
                  </span>
                @else
                  <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-800 text-red-100">
                    ✗ Failed
                  </span>
                @endif
              </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-900 border-t border-gray-700 flex items-center justify-between">
              <span class="text-xs text-gray-400">View Details</span>
              <span class="text-blue-400 group-hover:translate-x-1 transition-transform">→</span>
            </div>
          </a>
        @empty
          <div class="col-span-full bg-gray-800 rounded-xl p-12 text-center border border-gray-700">
            <p class="text-gray-400">No courses enrolled yet</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection
