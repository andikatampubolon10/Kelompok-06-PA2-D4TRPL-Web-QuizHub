@extends('layouts.siswa')

@section('title', $course['title'].' - Exams')

@section('breadcrumb')
  <li><a href="{{ route('dashboard.courses') }}" class="hover:text-foreground">My Courses</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Exams</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-2">{{ $course['title'] }}</h1>
    <p class="text-muted-foreground">Course Code: {{ $course['code'] }}</p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- QUIZ CARD --}}
    <div class="bg-card rounded-lg p-6 border border-border">
      <div class="flex items-center space-x-4 mb-4">
        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
          <i class="fas fa-question-circle text-white"></i>
        </div>
        <div>
          <h3 class="font-semibold">Quiz</h3>
          <p class="text-sm text-muted-foreground">Quick assessments</p>
        </div>
      </div>

      <div class="space-y-3">
        @forelse($course['assessments']['quiz'] as $qz)
          <a href="{{ route('dashboard.assessments.take', [$slug, 'quiz', $qz['id']]) }}"
             class="block rounded-lg border border-border hover:bg-secondary transition p-3">
            <div class="flex items-center justify-between">
              <div>
                <p class="font-medium">{{ $qz['title'] }}</p>
                <p class="text-xs text-muted-foreground">{{ $qz['questions'] }} questions • {{ $qz['duration'] }} min</p>
              </div>
              @if($qz['status']==='completed')
                <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-800">Completed</span>
              @else
                <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-800">Available</span>
              @endif
            </div>
          </a>
        @empty
          <p class="text-sm text-muted-foreground">No quiz available</p>
        @endforelse
      </div>
    </div>

    {{-- UTS CARD --}}
    <div class="bg-card rounded-lg p-6 border border-border">
      <div class="flex items-center space-x-4 mb-4">
        <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
          <i class="fas fa-clipboard-check text-white"></i>
        </div>
        <div>
          <h3 class="font-semibold">UTS</h3>
          <p class="text-sm text-muted-foreground">Mid-term exam</p>
        </div>
      </div>

      <div class="space-y-3">
        @forelse($course['assessments']['uts'] as $u)
          <a href="{{ route('dashboard.assessments.take', [$slug, 'uts', $u['id']]) }}"
             class="block rounded-lg border border-border hover:bg-secondary transition p-3">
            <div class="flex items-center justify-between">
              <div>
                <p class="font-medium">{{ $u['title'] }}</p>
                <p class="text-xs text-muted-foreground">
                  {{ $u['questions'] }} questions • {{ $u['duration'] }} min
                  @if($u['date']) • {{ $u['date'] }} @endif
                </p>
              </div>
              @if($u['status']==='scheduled')
                <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-800">Scheduled</span>
              @else
                <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-800">Available</span>
              @endif
            </div>
          </a>
        @empty
          <p class="text-sm text-muted-foreground">No UTS available</p>
        @endforelse
      </div>
    </div>

    {{-- UAS CARD --}}
    <div class="bg-card rounded-lg p-6 border border-border">
      <div class="flex items-center space-x-4 mb-4">
        <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
          <i class="fas fa-graduation-cap text-white"></i>
        </div>
        <div>
          <h3 class="font-semibold">UAS</h3>
          <p class="text-sm text-muted-foreground">Final exam</p>
        </div>
      </div>

      <div class="space-y-3">
        @forelse($course['assessments']['uas'] as $a)
          <a href="{{ route('dashboard.assessments.take', [$slug, 'uas', $a['id']]) }}"
             class="block rounded-lg border border-border hover:bg-secondary transition p-3">
            <div class="flex items-center justify-between">
              <div>
                <p class="font-medium">{{ $a['title'] }}</p>
                <p class="text-xs text-muted-foreground">
                  {{ $a['questions'] }} questions • {{ $a['duration'] }} min
                  @if($a['date']) • {{ $a['date'] }} @else • TBA @endif
                </p>
              </div>
              <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-800">
                {{ $a['date'] ? 'Scheduled' : 'Not Available' }}
              </span>
            </div>
          </a>
        @empty
          <p class="text-sm text-muted-foreground">No UAS info</p>
        @endforelse
      </div>
    </div>
  </div>
@endsection
