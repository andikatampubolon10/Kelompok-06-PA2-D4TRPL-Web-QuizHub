@extends('layouts.siswa')

@section('title', 'Practice Questions - eCourse')

@section('breadcrumb')
  <li class="text-foreground">Practice Questions</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-2">Practice Questions</h1>
    <p class="text-muted-foreground">Browse practice questions by curriculum and subject</p>
  </div>

  {{-- Level: Curriculum --}}
  <div id="practice-curriculum" class="practice-level">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="showPracticeLevel('academic-year','D4 TRPL')">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center">
            <i class="fas fa-book text-primary-foreground"></i>
          </div>
          <div>
            <h3 class="font-semibold">Kurikulum Merdeka</h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Level: Academic Year --}}
  <div id="practice-academic-year" class="practice-level hidden">
    <button onclick="showPracticeLevel('curriculum')" class="flex items-center space-x-2 text-muted-foreground hover:text-foreground mb-4">
      <i class="fas fa-arrow-left"></i><span>Back to Curriculum</span>
    </button>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="showPracticeLevel('semester','2024/2025')">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-calendar text-white"></i>
          </div>
          <div>
            <h3 class="font-semibold">2024/2025</h3>
            <p class="text-sm text-muted-foreground">Current Academic Year</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Level: Semester --}}
  <div id="practice-semester" class="practice-level hidden">
    <button onclick="showPracticeLevel('academic-year')" class="flex items-center space-x-2 text-muted-foreground hover:text-foreground mb-4">
      <i class="fas fa-arrow-left"></i><span>Back to Academic Year</span>
    </button>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="showPracticeLevel('subject','Semester 1')">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-play text-white"></i>
          </div>
          <div>
            <h3 class="font-semibold">Semester 1</h3>
            <p class="text-sm text-muted-foreground">First Semester</p>
          </div>
        </div>
      </div>
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="showPracticeLevel('subject','Semester 2')">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-forward text-white"></i>
          </div>
          <div>
            <h3 class="font-semibold">Semester 2</h3>
            <p class="text-sm text-muted-foreground">Second Semester</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Level: Subject --}}
  <div id="practice-subject" class="practice-level hidden">
    <button onclick="showPracticeLevel('semester')" class="flex items-center space-x-2 text-muted-foreground hover:text-foreground mb-4">
      <i class="fas fa-arrow-left"></i><span>Back to Semester</span>
    </button>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="showPracticeLevel('questions','Programming Fundamentals')">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-code text-white"></i>
          </div>
          <div>
            <h3 class="font-semibold">Programming Fundamentals</h3>
            <p class="text-sm text-muted-foreground">Basic programming concepts</p>
          </div>
        </div>
      </div>
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="showPracticeLevel('questions','Data Structures')">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-teal-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-sitemap text-white"></i>
          </div>
          <div>
            <h3 class="font-semibold">Data Structures</h3>
            <p class="text-sm text-muted-foreground">Arrays, lists, trees, graphs</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Level: Questions --}}
  <div id="practice-questions" class="practice-level hidden">
    <button onclick="showPracticeLevel('subject')" class="flex items-center space-x-2 text-muted-foreground hover:text-foreground mb-4">
      <i class="fas fa-arrow-left"></i><span>Back to Subjects</span>
    </button>
    <div class="space-y-4">
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
              <i class="fas fa-question text-white"></i>
            </div>
            <div>
              <h3 class="font-semibold">Basic Concepts Practice Set</h3>
              <p class="text-sm text-muted-foreground">25 questions • Beginner level</p>
            </div>
          </div>
          <div class="text-right">
            <p class="text-sm font-medium">85% completed</p>
            <p class="text-xs text-muted-foreground">21/25 questions</p>
          </div>
        </div>
      </div>
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
              <i class="fas fa-brain text-white"></i>
            </div>
            <div>
              <h3 class="font-semibold">Advanced Problem Solving</h3>
              <p class="text-sm text-muted-foreground">15 questions • Advanced level</p>
            </div>
          </div>
          <div class="text-right">
            <p class="text-sm font-medium">Not started</p>
            <p class="text-xs text-muted-foreground">0/15 questions</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  function hideAll() {
    document.querySelectorAll('.practice-level').forEach(el => el.classList.add('hidden'));
  }
  function showPracticeLevel(level, value = null) {
    hideAll();
    document.getElementById(`practice-${level}`).classList.remove('hidden');
  }
</script>
@endpush
