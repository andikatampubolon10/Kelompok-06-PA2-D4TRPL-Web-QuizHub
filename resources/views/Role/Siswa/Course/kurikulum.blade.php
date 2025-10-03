@extends('layouts.siswa')

@section('title', 'Kurikulum - eCourse')

@section('breadcrumb')
  <li class="text-foreground">Kurikulum</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-2">Kurikulum</h1>
    <p class="text-muted-foreground">Pilih kurikulum yang tersedia</p>
  </div>

  <div id="practice-curriculum" class="practice-level">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow cursor-pointer"
           onclick="'">
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
@endsection
