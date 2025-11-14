@extends('layouts.guru-layout')

@section('title', 'Bobot Tiap Tipe Soal')

@section('content')
    <div class="space-y-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">
                    Bobot Tiap Tipe Soal untuk Ujian :
                    {{ $ujian->nama_ujian ?? 'ID ' . ($ujian->id_ujian ?? '') }}
                </h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tipe Soal</th>
                                <th>Bobot</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tipes as $tipe)
                                @php
                                    $bobot = $bobotTipeSoals->firstWhere('id_tipe_soal', $tipe->id_tipe_soal);
                                @endphp
                                <tr>
                                    <td>{{ $tipe->nama_tipe_soal }}</td>
                                    <td>{{ $bobot->bobot ?? 0 }}</td>
                                    <td>
                                        <a href="{{ route('Guru.BobotTipeSoal.edit', ['id_bobot_tipe_soal' => $bobot->id_bobot_tipe_soal ?? 0]) }}"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            $('#dataTable1').DataTable();
        }
    });
</script>
@endpush
