<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\kursus;
use App\Models\guru;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
public function index(Request $request)
{
    $user = auth()->user();

    // Mendapatkan kursus berdasarkan id_kursus dari query parameter
    $id_kursus = $request->query('id_kursus');
    $kursus = Kursus::where('id_kursus', $id_kursus)->first();

    // Jika kursus tidak ditemukan, kembalikan error
    if (!$kursus) {
        return redirect()->back()->withErrors(['error' => 'Kursus tidak ditemukan.']);
    }

    // Mendapatkan data guru berdasarkan user yang login
    $guru = Guru::where('id_user', $user->id)->first();
    if (!$guru) {
        return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
    }

    // Mendapatkan materi pertama berdasarkan kursus yang dipilih
    $materi_pertama = Materi::where('id_kursus', $kursus->id_kursus)
        ->orderBy('tanggal_materi', 'DESC')
        ->first(); // Mengambil satu materi pertama

    $id_materi = $materi_pertama ? $materi_pertama->id_materi : null;

    // Mengambil semua materi yang diurutkan berdasarkan tanggal materi
    $materi = Materi::where('id_kursus', $kursus->id_kursus)
        ->orderBy('tanggal_materi', 'DESC')
        ->get(); // Mengambil semua materi yang diurutkan

    // Memastikan $course dipassing ke view
    $course = $kursus;

    return view('Role.Guru.Course.Materi.index', compact('materi', 'materi_pertama', 'user', 'kursus', 'id_kursus', 'id_materi', 'course'));
}

public function create(Request $request)
{
    $guru = Guru::where('id_user', auth()->user()->id)->first();

    if (!$guru) {
        return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
    }

    $id_kursus = $request->query('id_kursus');

    $kursus = Kursus::where('id_guru', $guru->id_guru)->get();

    if ($kursus->isEmpty()) {
        return redirect()->back()->with('error', 'Kursus tidak ditemukan.');
    }

    $courses = Kursus::with('guru')->get();

    // Ambil kursus yang sesuai dengan id_kursus
    $course = $courses->where('id_kursus', $id_kursus)->first();

    $user = auth()->user();

    return view('Role.Guru.Course.Materi.create', compact('kursus', 'courses', 'course', 'user', 'id_kursus'));
}


    public function store(Request $request)
    {
        Log::info('Mulai validasi input.');
        $request->validate([
            'judul_materi' => 'required|string|max:30',
            'deskripsi' => 'nullable|string',
            'file' => 'required|mimes:pdf,docx,doc,ppt,pptx|max:10240', // Validasi file
            'id_kursus' => 'required|exists:kursus,id_kursus', // Validasi kursus
        ]);
        Log::info('Validasi input berhasil.');

        Log::info('Mulai menyimpan file.');

        // Move the file directly to public/files
        $fileName = time() . '.' . $request->file->extension();
        $filePath = $request->file('file')->move(public_path('files'), $fileName); // Store file directly in public/files

        // Generate a URL that can be accessed publicly
        $fileUrl = url('files/' . $fileName); // URL that can be accessed for the file

        Log::info('File URL yang disimpan: ' . $fileUrl);

        try {
            Log::info('Mencari data guru berdasarkan id_user: ' . auth()->user()->id);
            $guru = Guru::where('id_user', auth()->user()->id)->first();

            if (!$guru) {
                Log::error('Guru tidak ditemukan dengan id_user: ' . auth()->user()->id);
                return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
            }
            Log::info('Guru ditemukan dengan id_guru: ' . $guru->id_guru);

            // Create a new Materi record
            Log::info('Membuat materi baru dengan judul: ' . $request->judul_materi);
            Materi::create([
                'judul_materi' => $request->judul_materi,
                'deskripsi' => $request->deskripsi,
                'file' => $fileName,
                'file_url' => $fileUrl, // Store the file URL
                'tanggal_materi' => now(),
                'id_kursus' => $request->id_kursus,
                'id_guru' => $guru->id_guru, // Get id_guru from the found guru data
            ]);
            Log::info('Materi berhasil ditambahkan dengan judul: ' . $request->judul_materi);

            return redirect()->route('Guru.Materi.index', ['id_kursus' => $request->id_kursus])->with('success', 'Materi berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error storing materi: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan materi.']);
        }
    }

    public function show($id_materi)
    {
        $materi = Materi::where('id_materi', $id_materi)->firstOrFail();

        return view('Role.Guru.Course.Materi.edit', compact('materi'));
    }

    public function edit(Request $request, $id_materi)
    {
        $materi = Materi::where('id_materi', $id_materi)->firstOrFail();

        $id_kursus = $request->query('id_kursus');

        $user = auth()->user();

        $id_materi = $request->query('id_materi');

        $guru = Guru::where('id_user', auth()->user()->id)->first();

        if (!$guru) {
            return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
        }

        $courses = Kursus::where('id_guru', $guru->id_guru)->get();

        $courses = Kursus::with('guru')->get();

        $course = $courses->where('id_kursus', $id_kursus)->first();

        if ($courses->isEmpty()) {
            return redirect()->back()->with('error', 'Kursus tidak ditemukan.');
        }

        return view('Role.Guru.Course.Materi.edit', compact('materi','course', 'guru', 'id_kursus', 'courses', 'user', 'id_materi'));
    }

    public function update(Request $request, $id_materi)
    {
        Log::info('Mulai validasi input.');
        $request->validate([
            'judul_materi' => 'required|string|max:30',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|mimes:pdf,docx,doc,ppt,pptx|max:10240', // Validasi file
        ]);
        Log::info('Validasi input berhasil.');

        try {
            Log::info('Mencari materi dengan id_materi: ' . $id_materi);
            $materi = Materi::where('id_materi', $id_materi)->firstOrFail();

            Log::info('Memperbarui materi dengan id_materi: ' . $materi->id_materi);
            $materi->judul_materi = $request->judul_materi;
            $materi->deskripsi = $request->deskripsi;

            if ($request->hasFile('file')) {
                Log::info('File baru ditemukan. Menghapus file lama dan menyimpan file baru.');

                // Menghapus file lama jika ada
                if ($materi->file) {
                    Storage::delete($materi->file);
                    Log::info('File lama berhasil dihapus.');
                }

                $filePath = $request->file('file')->store('materi_files', 'public');

                $fileUrl = url('storage/' . $filePath);
                $materi->file = $filePath;
                $materi->file_url = $fileUrl; // Menyimpan URL file baru

                Log::info('File baru berhasil disimpan di: ' . $filePath);
                Log::info('File URL baru yang disimpan: ' . $fileUrl);
            }

            $materi->save();
            Log::info('Materi berhasil diperbarui dengan id_materi: ' . $materi->id_materi);

            $id_kursus = $request->id_kursus ?? $materi->id_kursus;
            if (!$id_kursus) {
                Log::error('id_kursus tidak ditemukan di request atau materi.');
                return redirect()->back()->withErrors(['error' => 'ID Kursus tidak ditemukan.']);
            }

            return redirect()->route('Guru.Materi.index', ['id_kursus' => $id_kursus])->with('success', 'Materi berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log error jika ada kesalahan
            Log::error('Error updating materi: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui materi.']);
        }
    }

    public function destroy($id_materi)
    {
        $materi = Materi::findOrFail($id_materi);

        if ($materi->file) {
            Storage::delete($materi->file);
        }

        $id_kursus = $materi->id_kursus;

        $materi->delete();

        return redirect()->route('Guru.Materi.index', ['id_kursus' => $id_kursus])->with('success', 'Materi berhasil dihapus.');
    }
}
