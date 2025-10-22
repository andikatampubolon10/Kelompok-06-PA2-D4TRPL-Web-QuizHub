@extends('layouts.siswa')

@section('title', 'Detail Jawaban Latihan')

@section('breadcrumb')
  <li><a href="{{ route('Siswa.latihan.kurikulum') }}" class="hover:text-foreground">Kurikulum</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Detail Jawaban Latihan</li>
@endsection

@section('content')
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-12 px-4">
    <div class="max-w-2xl mx-auto text-center">
      <h1 class="text-4xl font-bold text-white mb-2">{{ $latihan->Topik }}</h1>
      <p class="text-slate-400">Kelas: {{ $latihan->kelas->nama_kelas }}</p>
      
      <div class="mt-8 text-white">
        <h2 class="text-3xl font-semibold mb-4">Detail Jawaban</h2>

        @foreach ($questionDetails as $detail)
          <div class="mb-6">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-6">
              <div class="text-lg font-semibold text-white mb-4">{{ $detail['soal'] }}</div>
              
              <div class="space-y-2">
                @foreach ($detail['choices'] as $choice)
                  <div class="flex items-center gap-3 p-2">
                    <span class="w-6 h-6 rounded-md flex items-center justify-center border border-white/20">
                      @if ($detail['jawaban_siswa'] == $choice->id_jawaban_soal)
                        <i class="text-white fas fa-check"></i>
                      @else
                        <i class="text-white fas fa-times"></i>
                      @endif
                    </span>
                    <span class="text-white">
                      {{ $choice->jawaban }}
                    </span>
                  </div>
                @endforeach
              </div>

              <div class="mt-4">
                <strong>Jawaban Benar:</strong> 
                <span class="text-green-500">{{ $detail['jawaban_benar'] }}</span>
              </div>
              <div class="mt-2">
                <strong>Jawaban Siswa:</strong> 
                <span class="{{ $detail['is_correct'] ? 'text-green-500' : 'text-red-500' }}">
                  {{ $detail['jawaban_siswa'] }}
                </span>
              </div>
            </div>
          </div>
        @endforeach

      </div>

      <div class="mt-8">
        <a href="{{ route('Siswa.latihan.kurikulum') }}" class="px-8 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">Kembali ke Latihan</a>
      </div>
    </div>
  </div>
@endsection
