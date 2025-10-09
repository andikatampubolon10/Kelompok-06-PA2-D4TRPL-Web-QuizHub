@extends('layouts.siswa')

@section('title', 'Topik Latihan - ' . ($kelas->nama_kelas ?? 'Kelas'))

@section('breadcrumb')
  <li><a href="{{ route('Siswa.latihan.kurikulum') }}" class="hover:text-foreground">Kurikulum</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="{{ route('Siswa.latihan.tahunajaran', $kurikulum->id_kurikulum) }}" class="hover:text-foreground">Tahun Ajaran</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="{{ route('Siswa.latihan.semester', [$kurikulum->id_kurikulum, $tahunAjaran->ID_Tahun_Ajaran]) }}" class="hover:text-foreground">Semester</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Mata Pelajaran</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-1">Topik Latihan: {{ $latihan->Topik }}</h1>
    <p class="text-muted-foreground">
      Kurikulum: {{ $kurikulum->nama_kurikulum }} • Tahun Ajaran: {{ $tahunAjaran->Nama_Tahun_Ajaran }}
    </p>
  </div>

  <form method="POST" action="{{ route('Siswa.latihan.submit', [$kurikulum->id_kurikulum, $tahunAjaran->ID_Tahun_Ajaran, $semester->id_semester, $mapel->id_mata_pelajaran, $kelas->id_kelas, $latihan->id_latihan]) }}">
    @csrf

    <div class="space-y-6">
      <!-- Display one question at a time -->
      <div id="question-container">
        @foreach ($soals as $index => $soal)
          <div id="question-{{ $soal->id_soal }}" class="question" style="display: none;">
            <div class="bg-card rounded-lg p-6 border border-border">
              <p class="text-lg font-semibold">{{ $soal->soal }}</p>

              <!-- Display the options if available -->
              @if ($soal->id_tipe_soal == 1)  <!-- Pilihan Ganda -->
                <div class="mt-4">
                  @foreach ($soal->jawaban_soal as $jawaban)
                    <label class="block">
                      <input type="radio" name="soal_{{ $soal->id_soal }}" value="{{ $jawaban->id_jawaban_soal }}" class="form-radio">
                      {{ $jawaban->jawaban }}
                    </label>
                  @endforeach
                </div>
              @elseif ($soal->id_tipe_soal == 2) <!-- Benar/Salah -->
                <div class="mt-4">
                  <label class="block">
                    <input type="radio" name="soal_{{ $soal->id_soal }}" value="1" class="form-radio"> Benar
                  </label>
                  <label class="block">
                    <input type="radio" name="soal_{{ $soal->id_soal }}" value="0" class="form-radio"> Salah
                  </label>
                </div>
              @elseif ($soal->id_tipe_soal == 3) <!-- Isian -->
                <div class="mt-4">
                  <textarea name="soal_{{ $soal->id_soal }}" class="w-full p-2 border border-gray-300 rounded-md" rows="4"></textarea>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between mt-6">
      <button type="button" id="prevBtn" class="px-6 py-3 bg-secondary text-white rounded-lg" style="visibility: hidden;">Previous</button>
      <button type="button" id="nextBtn" class="px-6 py-3 bg-primary text-white rounded-lg">Next</button>
    </div>

    <div class="mt-6 flex justify-end">
      <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg" id="submitBtn" style="visibility: hidden;">Submit</button>
    </div>
  </form>

  <!-- Question Navigator -->
  <div class="mt-4 flex justify-center space-x-2">
    @foreach ($soals as $soal)
      <button type="button" class="px-4 py-2 bg-gray-200 rounded-md question-nav" data-id="{{ $soal->id_soal }}">{{ $loop->iteration }}</button>
    @endforeach
  </div>
@endsection

@push('scripts')
<script>
  let currentQuestionIndex = 0;
  const totalQuestions = {{ count($soals) }};
  const questionContainer = document.getElementById('question-container');
  const questions = @json($soals);

  // Show the first question by default
  showQuestion(currentQuestionIndex);

  // Show the question based on index
  function showQuestion(index) {
    const questionElements = document.querySelectorAll('.question');
    questionElements.forEach((el, i) => {
      el.style.display = i === index ? 'block' : 'none';
    });

    // Update button visibility
    document.getElementById('prevBtn').style.visibility = index > 0 ? 'visible' : 'hidden';
    document.getElementById('nextBtn').style.visibility = index < totalQuestions - 1 ? 'visible' : 'hidden';
    document.getElementById('submitBtn').style.visibility = index === totalQuestions - 1 ? 'visible' : 'hidden';
  }

  // Next button functionality
  document.getElementById('nextBtn').addEventListener('click', function() {
    if (currentQuestionIndex < totalQuestions - 1) {
      currentQuestionIndex++;
      showQuestion(currentQuestionIndex);
    }
  });

  // Previous button functionality
  document.getElementById('prevBtn').addEventListener('click', function() {
    if (currentQuestionIndex > 0) {
      currentQuestionIndex--;
      showQuestion(currentQuestionIndex);
    }
  });

  // Question navigation buttons
  const questionNavButtons = document.querySelectorAll('.question-nav');
  questionNavButtons.forEach(button => {
    button.addEventListener('click', function() {
      const questionId = this.dataset.id;
      currentQuestionIndex = questions.findIndex(q => q.id_soal == questionId);
      showQuestion(currentQuestionIndex);
    });
  });
</script>
@endpush
