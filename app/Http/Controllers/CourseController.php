<?php

namespace App\Http\Controllers;

use App\Models\kursus;
use App\Models\guru;
use App\Models\mata_pelajaran;
use App\Models\Operator;
use App\Models\kelas;
use App\Models\MataPelajaran;
use App\Models\Guru_Mata_Pelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class CourseController extends Controller
{
    public function index()
    {
        // Get the currently authenticated user
        $user = auth()->user();

        // Get the Guru instance related to the authenticated user
        $guru = $user->guru; // This will automatically load the guru relationship

        // Make sure to get the courses for the authenticated Guru
        $courses = kursus::where('id_guru', $guru->id_guru)->get(); // Use $guru->id_guru to filter courses

        // Pass the user and courses to the view
        return view('Role.Guru.index', compact('courses', 'user','guru'));
    }


    public function beranda()
    {
        $user = auth()->user();

        Log::info('User Role: ' . implode(', ', $user->getRoleNames()->toArray()));

        if (!$user->hasRole('Operator')) {
            abort(403, 'Unauthorized');
        }

        $courses = Kursus::with('guru', 'mataPelajaran')->get();

        return view('Role.Operator.Course.index', compact('courses', 'user'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $operator = Operator::where('id_user', $user->id)->first();

        $kelas = Kelas::where('id_operator', $operator->id_operator)->get();

        $mataPelajarans = mata_pelajaran::where('id_operator', $operator->id_operator)
            ->with('guru')  // Memastikan relasi guru dimuat
            ->get();

        $gurus = Guru::where('id_operator', $operator->id_operator)->get();

        if ($request->has('mata_pelajaran') && $request->mata_pelajaran != '') {
            $mataPelajaran = mata_pelajaran::find($request->mata_pelajaran);

            $gurus = $mataPelajaran ? $mataPelajaran->guru : [];
        }

        $tahunAjaranAktif = TahunAjaran::where('Status', 'Aktif')->first();

        if (!$tahunAjaranAktif) {
            $tahunAjaranAktif = (object) ['tahun_ajaran' => 'Tahun ajaran aktif tidak ditemukan'];
        }

        return view('Role.Operator.Course.create', compact('gurus', 'kelas', 'mataPelajarans', 'user', 'tahunAjaranAktif'));
    }

    public function store(Request $request)
    {
        try {
            // Log request data sebelum validasi
            Log::info('Request data received for creating course', $request->all());

            // Validasi inputan
            $validated = $request->validate([
                "nama_kursus" => [
                    'required',
                    'string',
                    'max:100',
                    function ($attribute, $value, $fail) use ($request) {
                        $mataPelajaran = mata_pelajaran::find($request->mata_pelajaran);

                        if ($mataPelajaran) {
                            // Memastikan nama kursus mengandung nama mata pelajaran yang dipilih
                            if (stripos($value, $mataPelajaran->nama_mata_pelajaran) === false) {
                                $fail('Nama kursus harus mengandung kata "' . $mataPelajaran->nama_mata_pelajaran . '"');
                            }
                        }
                    },
                ],
                "password" => 'required|string|min:8|confirmed',
                "image" => 'required|image|mimes:jpeg,png,jpg,gif|max:40960',
                "id_guru" => 'required|exists:guru,id_guru',
                "id_mata_pelajaran" => 'required|exists:mata_pelajaran,id_mata_pelajaran',
                "ID_Tahun_Ajaran" => 'required|exists:tahun_ajaran,ID_Tahun_Ajaran',
                "id_kelas" => 'required|exists:kelas,id_kelas',  // Validasi kelas
            ], [
                'nama_kursus.required' => 'Nama kursus harus diisi.',
                'nama_kursus.max' => 'Nama kursus tidak boleh lebih dari 100 karakter.',
                'nama_kursus.unique' => 'Nama kursus sudah terdaftar untuk mata pelajaran yang dipilih.',
                'password.required' => 'Password harus diisi.',
                'password.min' => 'Password minimal terdiri dari 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
                'image.required' => 'Gambar harus diunggah.',
                'image.image' => 'File yang diunggah harus berupa gambar.',
                'image.mimes' => 'Format gambar yang diperbolehkan adalah jpeg, png, jpg, dan gif.',
                'image.max' => 'Ukuran gambar tidak boleh lebih dari 40MB.',
                'id_guru.exists' => 'Guru yang dipilih tidak valid.',
                'id_operator.exists' => 'Operator yang dipilih tidak valid.',
                'id_mata_pelajaran.exists' => 'Mata pelajaran yang dipilih tidak valid.',
                'ID_Tahun_Ajaran.exists' => 'Tahun ajaran yang dipilih tidak valid.',
                'id_kelas.exists' => 'Kelas yang dipilih tidak valid.',
            ]);

            Log::info('Validated request data', $validated);

            $idUser = auth()->user()->id;
            $operator = Operator::where('id_user', $idUser)->first();

            if (!$request->image->isValid()) {
                Log::error('Invalid image uploaded');
                throw new \Exception('Gambar yang diupload tidak valid.');
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imageUrl = url('images/' . $imageName);

            Log::info('Saving course to database', [
                'nama_kursus' => $validated['nama_kursus'],
                'id_guru' => $validated['id_guru'],
                'id_operator' => $operator->id_operator,
                'id_mata_pelajaran' => $validated['id_mata_pelajaran'],
                'ID_Tahun_Ajaran' => $validated['ID_Tahun_Ajaran'],
                'id_kelas' => $validated['id_kelas'],
            ]);

            $course = Kursus::create([
                'nama_kursus' => $validated['nama_kursus'],
                'password' => Hash::make($validated['password']),
                'id_guru' => $validated['id_guru'],
                'id_operator' => $operator->id_operator,
                'id_mata_pelajaran' => $validated['id_mata_pelajaran'],
                'ID_Tahun_Ajaran' => $validated['ID_Tahun_Ajaran'],
                'id_kelas' => $validated['id_kelas'],
                'image' => $imageName,
                'image_url' => $imageUrl,
            ]);

            Log::info('Course created successfully', ['course_id' => $course->id_kursus]);

            return redirect()->route('Operator.Course.beranda')->with('success', 'Course created successfully.');
        } catch (\Exception $e) {
            Log::error('Error occurred while creating course', ['error' => $e->getMessage()]);

            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat membuat course. Silakan coba lagi.']);
        }
    }

    public function show(string $id, Request $request)
    {
        $course = Kursus::with('guru')->findOrFail($id);
        $user = auth()->user();
        $operator = Operator::where('id_user', $user->id)->first();

        $kelas = Kelas::where('id_operator', $operator->id_operator)->get();

        $mataPelajarans = mata_pelajaran::where('id_operator', $operator->id_operator)
            ->with('guru')  // Memastikan relasi guru dimuat
            ->get();

        $gurus = Guru::where('id_operator', $operator->id_operator)->get();

        if ($request->has('mata_pelajaran') && $request->mata_pelajaran != '') {
            $mataPelajaran = mata_pelajaran::find($request->mata_pelajaran);

            $gurus = $mataPelajaran ? $mataPelajaran->guru : [];
        }

        $tahunAjaranAktif = TahunAjaran::where('Status', 'Aktif')->first();

        if (!$tahunAjaranAktif) {
            $tahunAjaranAktif = (object) ['tahun_ajaran' => 'Tahun ajaran aktif tidak ditemukan'];
        }
        return view('Role.Guru.index', compact('course', 'gurus', 'kelas', 'mataPelajarans', 'user', 'tahunAjaranAktif'));
    }

    public function edit(string $id, Request $request)
    {
        $course = Kursus::findOrFail($id);
        $user = auth()->user();
        $operator = Operator::where('id_user', $user->id)->first();

        $kelas = Kelas::where('id_operator', $operator->id_operator)->get();

        $mataPelajarans = mata_pelajaran::where('id_operator', $operator->id_operator)
            ->with('guru')  // Memastikan relasi guru dimuat
            ->get();

        $gurus = Guru::where('id_operator', $operator->id_operator)->get();

        if ($request->has('mata_pelajaran') && $request->mata_pelajaran != '') {
            $mataPelajaran = mata_pelajaran::find($request->mata_pelajaran);

            $gurus = $mataPelajaran ? $mataPelajaran->guru : [];
        }

        $tahunAjaranAktif = TahunAjaran::where('Status', 'Aktif')->first();

        if (!$tahunAjaranAktif) {
            $tahunAjaranAktif = (object) ['tahun_ajaran' => 'Tahun ajaran aktif tidak ditemukan'];
        }

        $user = auth()->user();
        return view('Role.Operator.Course.edit', compact('user', 'course', 'gurus', 'kelas', 'mataPelajarans', 'user', 'tahunAjaranAktif'));
    }

    public function update(Request $request, string $id_kursus)
    {
        // Validate input data
        $validated = $request->validate([
            "nama_kursus" => 'required|string|max:100|unique:kursus,nama_kursus,' . $id_kursus . ',id_kursus', // Unique validation excluding current course
            "image" => 'nullable|image|mimes:jpeg,png,jpg,gif|max:40960', // Image is optional
            "id_mata_pelajaran" => 'required|exists:mata_pelajaran,id_mata_pelajaran', // Mata Pelajaran must exist
            "id_guru" => 'required|exists:guru,id_guru', // Guru must exist
            "id_kelas" => 'required|exists:kelas,id_kelas', // Kelas must exist
            "ID_Tahun_Ajaran" => 'required|exists:tahun_ajaran,ID_Tahun_Ajaran', // Tahun Ajaran must exist
        ], [
            'id_mata_pelajaran.exists' => 'Mata pelajaran yang dipilih tidak valid.',
            'id_guru.exists' => 'Guru yang dipilih tidak valid.',
            'id_kelas.exists' => 'Kelas yang dipilih tidak valid.',
            'ID_Tahun_Ajaran.exists' => 'Tahun ajaran yang dipilih tidak valid.',
        ]);

        // Find the course by ID
        $course = Kursus::findOrFail($id_kursus);

        // Handle image upload if a new image is provided
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if exists
            if ($course->image) {
                $oldImagePath = public_path('images/' . $course->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); // Delete old image
                }
            }

            // Save the new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imageUrl = url('images/' . $imageName);

            // Update the validated data with new image information
            $validated['image'] = $imageName;
            $validated['image_url'] = $imageUrl;
        }

        // If password is provided, hash it and include it in the validated data
        if ($request->filled('password')) {  // Ensure the password is not empty
            $validated['password'] = Hash::make($request->password); // Hash password if provided
        }

        // Update the course data with validated values
        $course->update($validated);

        // Redirect to the courses index with a success message
        return redirect()->route('Operator.Course.beranda')->with('success', 'Course updated successfully.');
    }

    public function destroy(string $id)
    {
        $course = Kursus::findOrFail($id);
        $course->delete();

        return redirect()->route('Operator.Course.beranda')->with('success', 'Course deleted successfully.');
    }

    public function getGurusByMataPelajaran(Request $request)
    {
        // Ambil mata pelajaran berdasarkan ID yang dipilih
        $mataPelajaran = mata_pelajaran::findOrFail($request->mata_pelajaran_id);

        // Ambil semua guru yang mengajar mata pelajaran ini melalui relasi many-to-many
        $gurus = $mataPelajaran->gurus;

        // Kembalikan data guru dalam format JSON
        return response()->json($gurus);
    }
}
