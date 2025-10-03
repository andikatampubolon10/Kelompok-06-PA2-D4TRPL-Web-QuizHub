@extends('layouts.siswa')

@section('title', 'Tahun Ajaran - eCourse')

@section('breadcrumb')
  <li class="text-foreground"><a href="">Kurikulum</a></li>
  <li class="text-foreground">Tahun Ajaran</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-2">Tahun Ajaran</h1>
    <p class="text-muted-foreground">Pilih Tahun Ajaran</p>
  </div>

  <div id="practice-academic-year" class="practice-level">
    <button onclick="" class="flex items-center space-x-2 text-muted-foreground hover:text-foreground mb-4">
      <i class="fas fa-arrow-left"></i><span>Kembali ke Kurikulum</span>
    </button>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="'">
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
@endsection
