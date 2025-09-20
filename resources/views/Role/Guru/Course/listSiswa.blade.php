@extends('layouts.guru-layout')

@section('title', 'Guru | Kursus | List Siswa')

@section('content')
    <div class="w-full bg-white p-6 shadow-md rounded-lg">
        <div class="border-b border-black pb-1 mb-4">
            <h1 class="text-3xl leading-none font-semibold">{{ $kursus->nama_kursus }}</h1>
        </div>

        <form class="mb-8 space-y-6">
            @foreach ($persentase as $percent)
                @if ($percent->tipePersentase->nama_persentase == 'persentase_kuis')
                    <div class="flex justify-between items-center text-base font-normal mb-2">
                        <div>Persentase Kuis: {{ $percent->persentase }} %</div>
                    </div>
                @elseif ($percent->tipePersentase->nama_persentase == 'persentase_UTS')
                    <div class="flex justify-between items-center text-base font-normal mb-2">
                        <div>Persentase Ujian Tengah Semester: {{ $percent->persentase }} %</div>
                    </div>
                @elseif ($percent->tipePersentase->nama_persentase == 'persentase_UAS')
                    <div class="flex justify-between items-center text-base font-normal mb-4">
                        <div>Persentase Ujian Akhir Semester: {{ $percent->persentase }} %</div>
                    </div>
                @endif
            @endforeach

            <div class="flex justify-between">
                <button id="resetRecalculateBtn" data-id-kursus="{{ $kursus->id_kursus }}" type="button"
                        class="px-4 py-2 text-base font-normal border border-green-700 bg-green-600 text-white rounded hover:bg-green-700 transition-colors duration-300">
                    <i class="fas fa-calculator mr-1"></i> Hitung Nilai
                </button>

                <!-- Export Nilai Button -->
                <a href="{{ route('Guru.nilai.export', ['id_kursus' => $kursus->id_kursus]) }}"
                   class="px-4 py-2 text-base font-normal border border-blue-700 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-300 ml-auto">
                    Export Nilai
                </a>
            </div>
        </form>

        <!-- List Siswa -->
        <section class="border-t border-black pt-4 mb-4">
            <h2 class="text-2xl mb-4 font-semibold">List Siswa</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-black rounded-lg">
                    <thead>
                    <tr>
                        <th class="border border-black px-2 py-1 text-center">NISN</th>
                        <th class="border border-black px-2 py-1 text-center">Nama Siswa</th>
                        <th class="border border-black px-2 py-1 text-center">Kelas</th>
                        <th class="border border-black px-2 py-1 text-center">Nilai Kuis</th>
                        <th class="border border-black px-2 py-1 text-center">Nilai UTS</th>
                        <th class="border border-black px-2 py-1 text-center">Nilai UAS</th>
                        <th class="border border-black px-2 py-1 text-center">Nilai Akhir</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($siswa as $student)
                        <tr>
                            <td class="border border-black px-2 py-1 text-center">{{ $student->nis }}</td>
                            <td class="border border-black px-2 py-1 text-center">{{ $student->nama_siswa }}</td>
                            <td class="border border-black px-2 py-1 text-center">
                                {{ $student->kelas ? $student->kelas->nama_kelas : 'Kelas tidak tersedia' }}
                            </td>
                            <td class="border border-black px-2 py-1 text-center">
                                {{ isset($nilai[$student->id_siswa]) && isset($nilai[$student->id_siswa]->nilai_kuis) 
                                    ? number_format($nilai[$student->id_siswa]->nilai_kuis, 2) : '-' }}
                            </td>
                            <td class="border border-black px-2 py-1 text-center">
                                {{ isset($nilai[$student->id_siswa]) && isset($nilai[$student->id_siswa]->nilai_uts) 
                                    ? number_format($nilai[$student->id_siswa]->nilai_uts, 2) : '-' }}
                            </td>
                            <td class="border border-black px-2 py-1 text-center">
                                {{ isset($nilai[$student->id_siswa]) && isset($nilai[$student->id_siswa]->nilai_uas) 
                                    ? number_format($nilai[$student->id_siswa]->nilai_uas, 2) : '-' }}
                            </td>
                            <td class="border border-black px-2 py-1 text-center">
                                {{ isset($nilai[$student->id_siswa]) && isset($nilai[$student->id_siswa]->nilai_total)
                                    ? number_format($nilai[$student->id_siswa]->nilai_total, 2) : '-' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    
                </table>
                 <!-- Form Actions -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 pt-6 border-t border-gray-100 mt-8">
                   

                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('Guru.Course.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>

                   
                    </div>
                </div>
            </form>
        </div>
            </div>
        </section>
    </div>
    
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    @if (session('success'))
        var myModal = new bootstrap.Modal(document.getElementById('successModal'));
        myModal.show();

        // Close the modal after 3 seconds
        setTimeout(function() {
            myModal.hide();
        }, 3000); // 3000ms = 3 seconds
    @endif

    document.addEventListener('DOMContentLoaded', function() {
        const resetRecalculateBtn = document.getElementById('resetRecalculateBtn');
        const loadingOverlay = document.getElementById('loadingOverlay');

        if (resetRecalculateBtn) {
            resetRecalculateBtn.addEventListener('click', function() {
                const idKursus = this.getAttribute('data-id-kursus');

                if (confirm('Apakah Anda yakin ingin reset dan menghitung ulang semua nilai?')) {
                    loadingOverlay.classList.remove('hidden');
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menghitung...';

                    fetch(`/Guru/reset-recalculate-nilai/${idKursus}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        loadingOverlay.classList.add('hidden');
                        resetRecalculateBtn.disabled = false;
                        resetRecalculateBtn.innerHTML = '<i class="fas fa-calculator mr-1"></i> Hitung Nilai';

                        if (data.success) {
                            // Update nilai for each student
                            for (const siswaId in data.data.hasil) {
                                const nilaiCell = document.getElementById(`nilai-${siswaId}`);
                                if (nilaiCell) {
                                    nilaiCell.textContent = parseFloat(data.data.hasil[siswaId].nilai_total).toFixed(2);
                                    nilaiCell.classList.add('highlight');
                                    setTimeout(() => {
                                        nilaiCell.classList.remove('highlight');
                                    }, 2000);
                                }
                            }

                            alert(`Perhitungan nilai berhasil dilakukan untuk ${data.data.jumlah_siswa} siswa!`);
                        } else {
                            alert('Terjadi kesalahan: ' + data.message);
                        }
                    })
                    .catch(error => {
                        loadingOverlay.classList.add('hidden');
                        resetRecalculateBtn.disabled = false;
                        resetRecalculateBtn.innerHTML = '<i class="fas fa-calculator mr-1"></i> Hitung Nilai';
                        alert('Terjadi kesalahan: ' + error.message);
                    });
                }
            });
        }
    });

    // Dropdown toggle function
    function toggleDropdown() {
        const menu = document.getElementById('dropdown-menu');
        menu.classList.toggle('show');
    }

    // Close dropdown if clicked outside
    window.addEventListener('click', function(e) {
        const dropdown = document.getElementById('userDropdown');
        const menu = document.getElementById('dropdown-menu');
        if (!dropdown.contains(e.target)) {
            menu.classList.remove('show');
        }
    });
</script>
