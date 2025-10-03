<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        Guru | Kursus | List Siswa
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f5f7;
            font-family: 'Arial', sans-serif;
            padding: 0;
            margin: 0;
            color: #333;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(to right, #00bfae, #00796b);
            color: white;
            padding: 20px 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .header .logo img {
            max-width: 120px;
            border-radius: 8px;
        }

        .header .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
        }

        .header .user-info img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .header .user-info img:hover {
            transform: scale(1.1);
        }

        .header .user-info span {
            font-size: 16px;
            font-weight: 600;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Dropdown Menu Styles */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 60px;
            right: 0;
            background-color: #ffffff;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
            padding: 10px;
            border-radius: 8px;
            width: 150px;
            z-index: 1500;
        }

        .dropdown-menu.show {
            display: block;
        }

        .logout-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            text-align: center;
        }

        .logout-btn:hover {
            background-color: #e04040;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(to bottom, #00796b, #00bfae, #00796b);
            width: 260px;
            padding: 25px 15px;
            position: fixed;
            top: 80px;
            left: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            gap: 20px;
            transition: all 0.3s ease;
            z-index: 900;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 18px;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 17px;
            transition: all 0.3s ease;
        }

        .sidebar a i {
            margin-right: 15px;
            font-size: 22px;
        }

        .sidebar a.active {
            background-color: #00796b;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar a:hover {
            background-color: #004d40;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Button Styles */
        .btn-add-top-right {
            position: absolute;
            top: 100px;
            right: 30px;
            background-color: #00bfae;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 16px;
            border: none;
            transition: background-color 0.3s ease;
            min-width: 150px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-add-top-right:hover {
            background-color: #00796b;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            padding: 18px 25px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #14A098;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
        }

        table tr:hover {
            background-color: #f1f1f1;
            transform: scale(1.01);
            transition: all 0.3s ease;
        }

        table td {
            vertical-align: middle;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 100px 30px 30px;
            flex: 1;
            transition: all 0.3s ease-in-out;
            overflow-y: auto;
        }

        /* Main Content Box */
        .main-content-box {
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                padding: 15px 20px;
                top: 60px;
                left: 0;
                height: auto;
                border-radius: 0;
                position: fixed;
                flex-direction: row;
                justify-content: space-around;
                gap: 0;
                z-index: 1000;
            }

            .sidebar a {
                font-size: 14px;
                padding: 10px 8px;
                border-radius: 8px;
                justify-content: center;
            }

            .sidebar a i {
                margin-right: 0;
                font-size: 18px;
            }

            .sidebar a span {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 130px 20px 20px;
            }

            .btn-add-top-right {
                position: fixed;
                top: 60px;
                right: 20px;
                width: auto;
                padding: 10px 20px;
                border-radius: 20px;
                font-size: 14px;
                min-width: auto;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                z-index: 1100;
            }

            table th,
            table td {
                padding: 12px 10px;
                font-size: 14px;
            }

            table th {
                font-size: 13px;
            }
        }

        /* Highlight animation for updated values */
        @keyframes highlight {
            0% {
                background-color: #ffffff;
            }

            50% {
                background-color: #c8e6c9;
            }

            100% {
                background-color: #ffffff;
            }
        }

        .highlight {
            animation: highlight 2s ease;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 55px;">

        <div class="relative dropdown">
            <div class="flex items-center cursor-pointer" onclick="toggleDropdown()">
                <div class="flex flex-col items-center">
                    <span class="text-white">Welcome, Guru</span>
                    <span class="text-white font-semibold">{{ $user->name }}</span>
                </div>
                <i
                    class="fas fa-user rounded-full ml-4 text-3xl text-gray-700 bg-white p-2 w-12 h-12 flex items-center justify-center"></i>
            </div>
            <div id="dropdown-menu" class="dropdown-menu">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">Logout</button>
                </form>
            </div>
        </div>
    </div>
    <div class="flex flex-col md:flex-row">
        <div class="sidebar">
            <a
                href="{{ route('Guru.Course.index') }}"class="flex items-center text-black p-2 rounded-lg shadow hover:bg-blue-500">
                <i class="fas fa-book-open text-sm"></i>
                <span>Kursus</span>
            </a>
            <a
                href="{{ route('Guru.Latihan.index') }}"class="d-flex align-items-center text-gray-700 p-2 rounded-lg hover:bg-gray-300">
                <i class="fas fa-pen text-sm"></i>
                <span>Latihan Soal</span>
            </a>

            <a
                href="{{ route('Guru.Nilai.index') }}"class="d-flex align-items-center text-gray-700 p-2 rounded-lg hover:bg-gray-300">
                <i class="fas fa-chart-line text-sm"></i>
                <span>Nilai</span>
            </a>
        </div>
        <div class="main-content">
            <div class="w-full bg-white p-6 shadow-md rounded-lg">
                <nav class="text-gray-600 text-lg mb-4" aria-label="Breadcrumb">
                    <ol class="list-reset flex">
                        <li><a href="{{ route('Guru.Course.index') }}" class="text-blue-600 hover:underline">Course</a>
                        </li>
                        <li><span class="mx-2">/</span></li>
                        <li><a href=""
                                class="text-blue-600 hover:underline">List Siswa</a></li>
                    </ol>
                </nav>
                <div class="border-b border-black pb-1 mb-4">
                    <h1 class="text-3xl leading-none font-semibold">
                        {{ $kursus->nama_kursus }}
                    </h1>
                </div>

                <form class="mb-8 space-y-6">
                    @foreach ($persentase as $percent)
                        @if ($percent->tipePersentase->nama_persentase == 'persentase_kuis')
                            <div class="flex justify-between items-center text-base font-normal mb-2">
                                <div>
                                    Persentase Kuis: {{ $percent->persentase }} %
                                </div>
                            </div>
                        @elseif ($percent->tipePersentase->nama_persentase == 'persentase_UTS')
                            <div class="flex justify-between items-center text-base font-normal mb-2">
                                <div>
                                    Persentase Ujian Tengah Semester: {{ $percent->persentase }} %
                                </div>
                            </div>
                        @elseif ($percent->tipePersentase->nama_persentase == 'persentase_UAS')
                            <div class="flex justify-between items-center text-base font-normal mb-4">
                                <div>
                                    Persentase Ujian Akhir Semester: {{ $percent->persentase }} %
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <div class="flex justify-between">
                        <button id="resetRecalculateBtn" data-id-kursus="{{ $kursus->id_kursus }}" type="button"
                            class="px-4 py-2 text-base font-normal border border-green-700 bg-green-600 text-white rounded hover:bg-green-700 transition-colors duration-300">
                            <i class="fas fa-calculator mr-1"></i> Hitung Nilai
                        </button>

                        <!-- Export Nilai Button (diletakkan di sebelah kanan) -->
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
                                    <th class="border border-black px-2 py-1 text-center">NIS</th>
                                    <th class="border border-black px-2 py-1 text-center">Nama Siswa</th>
                                    <th class="border border-black px-2 py-1 text-center">Kelas</th>
                                    <th class="border border-black px-2 py-1 text-center">Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswa as $student)
                                    <tr>
                                        <td class="border border-black px-2 py-1 text-center">{{ $student->nis }}</td>
                                        <td class="border border-black px-2 py-1 text-center">
                                            {{ $student->nama_siswa }}</td>
                                        <td class="border border-black px-2 py-1 text-center">
                                            {{ $student->kelas ? $student->kelas->nama_kelas : 'Kelas tidak tersedia' }}
                                        </td>
                                        <td class="border border-black px-2 py-1 text-center"
                                            id="nilai-{{ $student->id_siswa }}">
                                            {{ isset($nilai[$student->id_siswa]) ? number_format($nilai[$student->id_siswa]->nilai_total, 2) : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>

        <div id="loadingOverlay"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-5 rounded-lg flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500 mb-3"></div>
                <p class="text-gray-700">Menghitung nilai...</p>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const resetRecalculateBtn = document.getElementById('resetRecalculateBtn');
                const loadingOverlay = document.getElementById('loadingOverlay');

                if (resetRecalculateBtn) {
                    resetRecalculateBtn.addEventListener('click', function() {
                        const idKursus = this.getAttribute('data-id-kursus');

                        // Konfirmasi sebelum reset dan menghitung ulang
                        if (confirm('Apakah Anda yakin ingin reset dan menghitung ulang semua nilai?')) {
                            // Tampilkan loading overlay
                            loadingOverlay.classList.remove('hidden');

                            // Ubah tampilan tombol saat proses berjalan
                            this.disabled = true;
                            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menghitung...';

                            // Kirim request AJAX untuk reset dan menghitung ulang nilai
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
                                    // Sembunyikan loading overlay
                                    loadingOverlay.classList.add('hidden');

                                    // Kembalikan tampilan tombol
                                    resetRecalculateBtn.disabled = false;
                                    resetRecalculateBtn.innerHTML =
                                        ' <i class="fas fa-calculator mr-1"></i> Hitung Nilai';

                                    if (data.success) {
                                        // Tampilkan nilai untuk setiap siswa
                                        for (const siswaId in data.data.hasil) {
                                            const nilaiCell = document.getElementById(`nilai-${siswaId}`);
                                            if (nilaiCell) {
                                                // Update nilai
                                                nilaiCell.textContent = parseFloat(data.data.hasil[siswaId]
                                                    .nilai_total).toFixed(2);

                                                // Tambahkan efek highlight
                                                nilaiCell.classList.add('highlight');

                                                // Hapus class highlight setelah animasi selesai
                                                setTimeout(() => {
                                                    nilaiCell.classList.remove('highlight');
                                                }, 2000);
                                            }
                                        }

                                        // Tampilkan pesan sukses
                                        alert(
                                            `Perhitungan nilai berhasil dilakukan untuk ${data.data.jumlah_siswa} siswa!`
                                        );
                                    } else {
                                        alert('Terjadi kesalahan: ' + data.message);
                                    }
                                })
                                .catch(error => {
                                    // Sembunyikan loading overlay
                                    loadingOverlay.classList.add('hidden');

                                    // Kembalikan tampilan tombol
                                    resetRecalculateBtn.disabled = false;
                                    resetRecalculateBtn.innerHTML =
                                        ' <i class="fas fa-calculator mr-1"></i> Hitung Nilai';

                                    alert('Terjadi kesalahan: ' + error.message);
                                });
                        }
                    });
                }
            });
        </script>
</body>

</html>
