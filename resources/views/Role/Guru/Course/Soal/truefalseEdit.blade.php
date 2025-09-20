@extends('layouts.guru-layout')

@section('title', 'Tambah Akun')

@section('content')
    <!-- Additional Info Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-600 text-sm"></i>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-blue-800 mb-1">Tips Penambahan Soal</h4>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Masukkan Soal</li>
                    <li>• Pilih Jawaban</li>
                    <li>• Pilih Latihan Soal</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md h-full w-full">
        <h2 class="text-2xl font-bold mb-6">Edit Soal</h2>

        <form action="{{ route('Guru.Soal.update', $soal->id_soal) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id_tipe_soal" value="{{ $soal->id_tipe_soal }}">

            <input type="hidden" name="id_ujian" value="{{ request()->query('id_ujian') }}">

            <!-- Soal -->
                    <label class="block text-gray-700 text-sm font-bold mb-2">Soal</label>
                    <div class="border p-2">
                        <div class="flex space-x-2 mb-2">
                            <button type="button" class="border p-1" id="list-button-soal"><i
                                    class="fas fa-list"></i></button>
                            <button type="button" class="border p-1" id="bold-button-soal"><i
                                    class="fas fa-bold"></i></button>
                            <button type="button" class="border p-1" id="image-button-soal"><i
                                    class="fas fa-image"></i></button>
                        </div>
                        <textarea id="soal-textarea" name="soal" class="w-full border p-2" rows="4">{{ $soal->soal }}</textarea>
                        <div id="image-preview-soal" class="mt-2">
                            @if ($soal->image)
                                <div class="relative mt-2 inline-block">
                                    <img src="{{ $soal->image_url }}" alt="Soal Image"
                                        class="max-w-full h-auto max-h-40 border rounded">
                                    <span
                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer"
                                        onclick="removeImage('soal')">×</span>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="image-input-soal" name="image" class="hidden" accept="image/*" />
                        @error('soal')
                            <span class="alert-danger">{{ $message }}</span>
                        @enderror
                    </div>

           <!-- Get jawaban_soal data -->
                    @php
                        $jawaban = $soal->jawaban_soal->sortBy('id_jawaban_soal')->values();
                    @endphp

            <!-- Jawaban 1 -->
            <div class="border p-2 mb-4 mt-4">
                <div class="flex space-x-2 mb-2">
                    <button type="button" class="border p-1" id="list-button-1"><i class="fas fa-list"></i></button>
                    <button type="button" class="border p-1" id="bold-button-1"><i class="fas fa-bold"></i></button>
                </div>
                <textarea id="jawaban-1-textarea" name="jawaban_1" placeholder="True" class="w-full border p-2" rows="2">{{ $jawaban[0]->jawaban ?? 'True' }}</textarea>
                @error('jawaban_1')
                    <span class="alert-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Jawaban 2 -->
            <div class="border p-2 mb-4">
                <div class="flex space-x-2 mb-2">
                    <button type="button" class="border p-1" id="list-button-2"><i class="fas fa-list"></i></button>
                    <button type="button" class="border p-1" id="bold-button-2"><i class="fas fa-bold"></i></button>
                </div>
                 <textarea id="jawaban-2-textarea" name="jawaban_2" placeholder="False" class="w-full border p-2" rows="2">{{ $jawaban[1]->jawaban ?? 'False' }}</textarea>
                @error('jawaban_2')
                    <span class="alert-danger">{{ $message }}</span>
                @enderror
            </div>

    

            <!-- Correct Answer Selection -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="correct_answer">Jawaban
                            Benar</label>
                        <select id="correct_answer" name="correct_answer"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @php
                                $correctIndex = 0;
                                foreach ($jawaban as $index => $jwb) {
                                    if ($jwb->benar) {
                                        $correctIndex = $index;
                                        break;
                                    }
                                }
                            @endphp
                    <option value="jawaban_1" {{ $correctIndex == 0 ? 'selected' : '' }}>True</option>
                            <option value="jawaban_2" {{ $correctIndex == 1 ? 'selected' : '' }}>False</option>
                        </select>

                @error('correct_answer')
                    <span class="alert-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Correct Answer Selection -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="id_latihan">Latihan</label>
                        <select name="id_latihan" id="id_latihan"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Pilih Latihan (Opsional)</option>
                            @foreach ($latihan as $latihans)
                                <option value="{{ $latihans->id_latihan }}"
                                    {{ $soal->id_latihan == $latihans->id_latihan ? 'selected' : '' }}>
                                    {{ $latihans->Topik }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    
                        <!-- Form Actions -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 pt-6 border-t border-gray-100 mt-8">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-info-circle"></i>
                        <span>Semua field yang bertanda (*) wajib diisi</span>
                    </div>
       
                        
                          <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('Guru.Soal.index', ['id_ujian' => $soal->id_ujian]) }}"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>

               
                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-save mr-2"></i>
                            Simpan 
                        </button>
                    </div>
                </div>
            
            
        </form>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div id="successAlert"
            class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3">
            <i class="fas fa-check-circle text-xl"></i>
            <div>
                <p class="font-semibold">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
            <button onclick="closeAlert('successAlert')" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div id="errorAlert"
            class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3">
            <i class="fas fa-exclamation-circle text-xl"></i>
            <div>
                <p class="font-semibold">Error!</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
            <button onclick="closeAlert('errorAlert')" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <script>
        function closeAlert(alertId) {
            document.getElementById(alertId).style.display = 'none';
        }

        // Auto close alerts after 5 seconds
        setTimeout(() => {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            if (successAlert) successAlert.style.display = 'none';
            if (errorAlert) errorAlert.style.display = 'none';
        }, 5000);

        // Form validation enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const nameInput = document.getElementById('nama_kurikulum');

            nameInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-green-500');
                }
            });

            form.addEventListener('submit', function(e) {
                if (nameInput.value.trim() === '') {
                    e.preventDefault();
                    nameInput.classList.add('border-red-500');
                    nameInput.focus();
                }
            });
        });
    </script>
@endsection