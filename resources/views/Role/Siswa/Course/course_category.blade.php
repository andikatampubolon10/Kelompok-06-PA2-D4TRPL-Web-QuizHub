@extends('layouts.siswa')

@section('title', 'Course Category - eCourse')

@section('breadcrumb')
  <li class="text-foreground">Course Category</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-2">Course Category</h1>
    <p class="text-muted-foreground">Browse and enroll in available courses</p>
  </div>

  {{-- Level: Curriculum --}}
  <div id="category-curriculum" class="category-level">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="showCategoryLevel('academic-year','D4 TRPL')">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center">
            <i class="fas fa-graduation-cap text-primary-foreground"></i>
          </div>
          <div>
            <h3 class="font-semibold">D4 TRPL</h3>
            <p class="text-sm text-muted-foreground">Teknologi Rekayasa Perangkat Lunak</p>
            <p class="text-xs text-muted-foreground mt-1">24 courses available</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Level: Academic Year --}}
  <div id="category-academic-year" class="category-level hidden">
    <button onclick="showCategoryLevel('curriculum')" class="flex items-center space-x-2 text-muted-foreground hover:text-foreground mb-4">
      <i class="fas fa-arrow-left"></i><span>Back to Curriculum</span>
    </button>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="showCategoryLevel('semester','2024/2025')">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-calendar-alt text-white"></i>
          </div>
          <div>
            <h3 class="font-semibold">2024/2025</h3>
            <p class="text-sm text-muted-foreground">Current Academic Year</p>
            <p class="text-xs text-muted-foreground mt-1">12 courses available</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Level: Semester --}}
  <div id="category-semester" class="category-level hidden">
    <button onclick="showCategoryLevel('academic-year')" class="flex items-center space-x-2 text-muted-foreground hover:text-foreground mb-4">
      <i class="fas fa-arrow-left"></i><span>Back to Academic Year</span>
    </button>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="showCategoryLevel('courses','Semester 1')">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-play text-white"></i>
          </div>
          <div>
            <h3 class="font-semibold">Semester 1</h3>
            <p class="text-sm text-muted-foreground">First Semester</p>
            <p class="text-xs text-muted-foreground mt-1">6 courses available</p>
          </div>
        </div>
      </div>
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="showCategoryLevel('courses','Semester 2')">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-forward text-white"></i>
          </div>
          <div>
            <h3 class="font-semibold">Semester 2</h3>
            <p class="text-sm text-muted-foreground">Second Semester</p>
            <p class="text-xs text-muted-foreground mt-1">6 courses available</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Level: Courses --}}
  <div id="category-courses" class="category-level hidden">
    <button onclick="showCategoryLevel('semester')" class="flex items-center space-x-2 text-muted-foreground hover:text-foreground mb-4">
      <i class="fas fa-arrow-left"></i><span>Back to Semester</span>
    </button>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="showEnrollmentModal('Advanced Programming','PROG301')">
        <div class="h-32 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg mb-4"></div>
        <h3 class="font-semibold mb-2">Advanced Programming</h3>
        <p class="text-sm text-muted-foreground mb-2">PROG301 • 4 Credits</p>
        <div class="flex items-center justify-between">
          <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Available</span>
          <i class="fas fa-lock text-muted-foreground"></i>
        </div>
      </div>
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="showEnrollmentModal('Database Systems','DB201')">
        <div class="h-32 bg-gradient-to-br from-green-
