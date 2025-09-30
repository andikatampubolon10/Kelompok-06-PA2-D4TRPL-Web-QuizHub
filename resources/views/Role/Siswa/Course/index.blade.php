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

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Course Card 1 -->
    <a href=""
       class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer">
      <div class="h-32 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg mb-4"></div>
      <h3 class="font-semibold mb-2">D4 Sarjana Terapan Teknologi Rekayasa Perangkat Lunak</h3>
      <p class="text-sm text-muted-foreground mb-2">Automata 25/26</p>
      <div class="flex items-center justify-between">
        <span class="text-xs bg-secondary px-2 py-1 rounded">In Progress</span>
        <i class="fas fa-arrow-right text-muted-foreground"></i>
      </div>
    </a>

    <!-- Course Card 2 -->
    <a href=""
       class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer">
      <div class="h-32 bg-gradient-to-br from-teal-400 to-blue-500 rounded-lg mb-4"></div>
      <h3 class="font-semibold mb-2">D4 Sarjana Terapan Teknologi Rekayasa Perangkat Lunak</h3>
      <p class="text-sm text-muted-foreground mb-2">4143203 - Data Science</p>
      <div class="flex items-center justify-between">
        <span class="text-xs bg-secondary px-2 py-1 rounded">In Progress</span>
        <i class="fas fa-arrow-right text-muted-foreground"></i>
      </div>
    </a>

    <!-- Course Card 3 -->
    <a href=""
       class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer">
      <div class="h-32 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg mb-4"></div>
      <h3 class="font-semibold mb-2">D4 Sarjana Terapan Teknologi Rekayasa Perangkat Lunak</h3>
      <p class="text-sm text-muted-foreground mb-2">4143106 - Software Quality Assurance</p>
      <div class="flex items-center justify-between">
        <span class="text-xs bg-secondary px-2 py-1 rounded">In Progress</span>
        <i class="fas fa-arrow-right text-muted-foreground"></i>
      </div>
    </a>

    <!-- Course Card 4 -->
    <a href=""
       class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer">
      <div class="h-32 bg-gradient-to-br from-pink-400 to-red-500 rounded-lg mb-4"></div>
      <h3 class="font-semibold mb-2">D4 Sarjana Terapan Teknologi Rekayasa Perangkat Lunak</h3>
      <p class="text-sm text-muted-foreground mb-2">Keamanan Perangkat Lunak</p>
      <div class="flex items-center justify-between">
        <span class="text-xs bg-secondary px-2 py-1 rounded">In Progress</span>
        <i class="fas fa-arrow-right text-muted-foreground"></i>
      </div>
    </a>
  </div>
@endsection
