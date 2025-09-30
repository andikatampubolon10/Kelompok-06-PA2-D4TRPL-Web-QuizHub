@extends('layouts.siswa')

@section('title')

@section('breadcrumb')
  <li><a href="" class="hover:text-foreground">My Courses</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Exams</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-2"></h1>
    <p class="text-muted-foreground">Available assessments for this course</p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- Card: Quiz --}}
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
        @foreach(['quiz'] as $qz)
          <a href=""
             class="flex items-center justify-between bg-secondary/40 hover:bg-secondary rounded-lg px-3 py-2">
            <div>
              <p class="text-sm font-medium"></p>
              <p class="text-xs text-muted-foreground">Completed: </p>
            </div>
            <i class="fas fa-arrow-right text-muted-foreground"></i>
          </a>
        @endforeach
      </div>
    </div>

    {{-- Card: UTS --}}
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
        @foreach(['uts'] as $e)
          <a href=""
             class="flex items-center justify-between bg-secondary/40 hover:bg-secondary rounded-lg px-3 py-2">
            <div>
              <p class="text-sm font-medium"></p>
              <p class="text-xs text-muted-foreground"></p>
            </div>
            <i class="fas fa-arrow-right text-muted-foreground"></i>
          </a>
        @endforeach
      </div>
    </div>

    {{-- Card: UAS --}}
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
        @foreach(['uas'] as $e)
          <a href=""
             class="flex items-center justify-between bg-secondary/40 hover:bg-secondary rounded-lg px-3 py-2">
            <div>
              <p class="text-sm font-medium"></p>
              <p class="text-xs text-muted-foreground"></p>
            </div>
            <i class="fas fa-arrow-right text-muted-foreground"></i>
          </a>
        @endforeach
      </div>
    </div>
  </div>
@endsection
