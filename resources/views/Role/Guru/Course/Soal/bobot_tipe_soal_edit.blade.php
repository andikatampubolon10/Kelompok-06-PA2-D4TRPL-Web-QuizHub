{{-- resources/views/Role.Guru.Course.Soal.bobot_tipe_soal_edit.blade.php --}}
@extends('layouts.guru-layout')

@section('title', 'Edit Bobot Tipe Soal')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Edit Bobot Tipe Soal</h3>
        </div>

        <form action="{{ route('Guru.BobotTipeSoal.update', ['id_bobot_tipe_soal' => $bobot->id_bobot_tipe_soal]) }}"
            method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <!-- Pastikan id_ujian tersedia untuk update jika diperlukan -->
            <input type="hidden" name="id_ujian" value="{{ $bobot->id_ujian ?? '' }}">

            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="id_tipe_soal" class="form-label">Tipe Soal</label>
                    <select name="id_tipe_soal" id="id_tipe_soal"
                        class="form-control @error('id_tipe_soal') is-invalid @enderror" required>
                        @foreach ($tipeSoal as $ts)
                            <option value="{{ $ts->id_tipe_soal }}"
                                {{ old('id_tipe_soal', $bobot->id_tipe_soal) == $ts->id_tipe_soal ? 'selected' : '' }}>
                                {{ $ts->nama_tipe_soal }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_tipe_soal')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="bobot" class="form-label">Bobot</label>
                    <input type="number" step="0.01" name="bobot" id="bobot"
                        class="form-control @error('bobot') is-invalid @enderror" value="{{ old('bobot', $bobot->bobot) }}"
                        required min="0" max="100" aria-describedby="bobotHelp">
                    @error('bobot')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                    <small id="bobotHelp" class="form-text text-muted">Total bobot untuk ujian ini tidak boleh melebihi
                        100.</small>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('Guru.BobotTipeSoal.index', ['id_ujian' => $bobot->id_ujian]) }}"
                    class="btn btn-default">Kembali</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
@endsection

@push('css')
    <!-- CSS khusus jika diperlukan -->
@endpush

@push('js')
    <script>
        // Optional: front-end validation
    </script>
@endpush
