@extends('layouts.ujian')

@section('title', 'Exam Results')

@section('breadcrumb')
  <li><a href="{{ route('Siswa.Course.index') }}" class="hover:text-foreground">My Courses</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="{{ route('Siswa.Course.tipeujian', $kursus->id_kursus) }}" class="hover:text-foreground">Exams</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Results</li>
@endsection

@section('content')
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-12 px-4">
    <div class="max-w-2xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-2">{{ $ujian->nama_ujian }}</h1>
        <p class="text-slate-400">{{ $kursus->nama_kursus }}</p>
      </div>

      <!-- Main Result Card -->
      <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-12 mb-8 shadow-2xl">
        <!-- Score Circle -->
        <div class="flex justify-center mb-12">
          <div class="relative w-48 h-48">
            <!-- Background Circle -->
            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 200 200">
              <circle cx="100" cy="100" r="90" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="8"/>
              <!-- Progress Circle -->
              <circle 
                cx="100" 
                cy="100" 
                r="90" 
                fill="none" 
                stroke="url(#gradient)" 
                stroke-width="8"
                stroke-dasharray="{{ (($nilai / 100) * 565.48) }} 565.48"
                stroke-linecap="round"
                class="transition-all duration-1000"
              />
              <defs>
                <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                  <stop offset="100%" style="stop-color:#06b6d4;stop-opacity:1" />
                </linearGradient>
              </defs>
            </svg>
            <!-- Center Content -->
            <div class="absolute inset-0 flex flex-col items-center justify-center">
              <div class="text-6xl font-bold text-white">{{ number_format($nilai, 1) }}</div>
              <div class="text-sm text-slate-300 mt-2">out of 100</div>
            </div>
          </div>
        </div>

        <!-- Grade Badge -->
        <div class="flex justify-center mb-8">
          <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full px-8 py-3 inline-block">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-2xl font-bold text-white">{{ $grade['grade'] }}</span>
              </div>
              <div>
                <div class="text-white font-semibold">{{ $grade['label'] }}</div>
                <div class="text-blue-100 text-sm">Grade</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Feedback -->
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 mb-8">
          <p class="text-white text-center text-lg leading-relaxed">
            {{ $feedback }}
          </p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-3 gap-4 mb-8">
          <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <div class="text-slate-400 text-sm mb-2">Status</div>
            <div class="text-2xl font-bold text-white">
              @if($nilai >= 70)
                <span class="text-emerald-400">✓ Passed</span>
              @else
                <span class="text-red-400">✗ Failed</span>
              @endif
            </div>
          </div>
          <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <div class="text-slate-400 text-sm mb-2">Percentage</div>
            <div class="text-2xl font-bold text-white">{{ number_format($nilai, 0) }}%</div>
          </div>
          <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <div class="text-slate-400 text-sm mb-2">Completed</div>
            <div class="text-2xl font-bold text-white">{{ now()->format('M d, Y') }}</div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 justify-center">
          <a href="{{ route('Siswa.Course.tipeujian', $kursus->id_kursus) }}" 
             class="px-8 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-lg transition-all duration-300">
            Back to Exams
          </a>
          <a href="{{ route('Siswa.Course.index') }}" 
             class="px-8 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white rounded-lg transition-all duration-300 font-semibold">
            Back to Courses
          </a>
        </div>
      </div>

      <!-- Tips Section -->
      <div class="bg-white/5 border border-white/20 rounded-2xl p-8">
        <h3 class="text-white font-semibold text-lg mb-4">💡 Tips for Next Time</h3>
        <ul class="space-y-3 text-slate-300">
          <li class="flex gap-3">
            <span class="text-blue-400">•</span>
            <span>Review the material you found challenging</span>
          </li>
          <li class="flex gap-3">
            <span class="text-blue-400">•</span>
            <span>Practice with similar questions</span>
          </li>
          <li class="flex gap-3">
            <span class="text-blue-400">•</span>
            <span>Manage your time better during the exam</span>
          </li>
          <li class="flex gap-3">
            <span class="text-blue-400">•</span>
            <span>Read questions carefully before answering</span>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <style>
    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-slide-up {
      animation: slideUp 0.6s ease-out;
    }

    @keyframes pulse-ring {
      0%, 100% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
      }
      50% {
        box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
      }
    }

    .animate-pulse-ring {
      animation: pulse-ring 2s infinite;
    }
  </style>

  <script>
    // Animate score on page load
    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('.bg-white\\/5');
      cards.forEach((card, index) => {
        card.style.animation = `slideUp 0.6s ease-out ${index * 0.1}s both`;
      });
    });
  </script>
@endsection