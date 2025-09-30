@extends('layouts.siswa')

@section('title', 'My Grades - eCourse')

@section('breadcrumb')
  <li class="text-foreground">Grades</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-2">My Grades</h1>
    <p class="text-muted-foreground">View your academic performance across all courses</p>
  </div>

  <!-- Grade Summary -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-card rounded-lg p-6 border border-border">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
          <i class="fas fa-chart-line text-white"></i>
        </div>
        <div>
          <p class="text-sm text-muted-foreground">Overall GPA</p>
          <p class="text-2xl font-bold">3.75</p>
        </div>
      </div>
    </div>
    <div class="bg-card rounded-lg p-6 border border-border">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
          <i class="fas fa-book text-white"></i>
        </div>
        <div>
          <p class="text-sm text-muted-foreground">Courses Taken</p>
          <p class="text-2xl font-bold">12</p>
        </div>
      </div>
    </div>
    <div class="bg-card rounded-lg p-6 border border-border">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
          <i class="fas fa-trophy text-white"></i>
        </div>
        <div>
          <p class="text-sm text-muted-foreground">Credits Earned</p>
          <p class="text-2xl font-bold">48</p>
        </div>
      </div>
    </div>
    <div class="bg-card rounded-lg p-6 border border-border">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
          <i class="fas fa-star text-white"></i>
        </div>
        <div>
          <p class="text-sm text-muted-foreground">Semester GPA</p>
          <p class="text-2xl font-bold">3.85</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Grades Table -->
  <div class="bg-card rounded-lg border border-border overflow-hidden">
    <div class="p-6 border-b border-border">
      <h2 class="text-lg font-semibold">Course Grades</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-muted">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Course</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Credits</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Grade</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Points</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border">
          <tr>
            <td class="px-6 py-4">
              <div>
                <div class="text-sm font-medium">Programming Fundamentals</div>
                <div class="text-sm text-muted-foreground">CS101</div>
              </div>
            </td>
            <td class="px-6 py-4 text-sm">4</td>
            <td class="px-6 py-4 text-sm font-medium">A</td>
            <td class="px-6 py-4 text-sm">4.0</td>
            <td class="px-6 py-4">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
            </td>
          </tr>
          <tr>
            <td class="px-6 py-4">
              <div>
                <div class="text-sm font-medium">Data Structures</div>
                <div class="text-sm text-muted-foreground">CS102</div>
              </div>
            </td>
            <td class="px-6 py-4 text-sm">4</td>
            <td class="px-6 py-4 text-sm font-medium">B+</td>
            <td class="px-6 py-4 text-sm">3.5</td>
            <td class="px-6 py-4">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
            </td>
          </tr>
          <tr>
            <td class="px-6 py-4">
              <div>
                <div class="text-sm font-medium">Software Quality Assurance</div>
                <div class="text-sm text-muted-foreground">SE201</div>
              </div>
            </td>
            <td class="px-6 py-4 text-sm">3</td>
            <td class="px-6 py-4 text-sm font-medium">A-</td>
            <td class="px-6 py-4 text-sm">3.7</td>
            <td class="px-6 py-4">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">In Progress</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endsection
