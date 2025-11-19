<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $query = Student::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%')
                  ->orWhere('class', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->unit) {
            $query->where('unit', $request->unit);
        }

        if ($request->class) {
            $query->where('class', $request->class);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $students = $query->orderBy('unit')
            ->orderBy('class')
            ->orderBy('name')
            ->paginate(20);

        $units = Student::distinct()->pluck('unit')->filter();
        $classes = Student::distinct()->pluck('class')->filter();

        return view('admin.students.index', compact('students', 'units', 'classes'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.students.create');
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'nis' => 'required|string|max:255|unique:students,nis',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        Student::create([
            'nis' => $validated['nis'],
            'name' => $validated['name'],
            'unit' => $validated['unit'],
            'class' => $validated['class'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    /**
     * Show the form for importing students from CSV.
     */
    public function showImport()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.students.import');
    }

    /**
     * Import students from CSV file.
     */
    public function import(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        $data = array_map('str_getcsv', file($path));
        
        // Remove header row
        $header = array_shift($data);
        
        // Validate header
        $expectedHeaders = ['NIS', 'Unit', 'Class', 'Name'];
        if (count($header) !== 4 || array_map('trim', $header) !== $expectedHeaders) {
            return redirect()->back()->with('error', 'Format CSV tidak valid. Header harus: NIS, Unit, Class, Name');
        }

        $imported = 0;
        $updated = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($data as $index => $row) {
                if (count($row) < 4) {
                    $errors[] = "Baris " . ($index + 2) . ": Data tidak lengkap";
                    continue;
                }

                $nis = trim($row[0]);
                $unit = trim($row[1]);
                $class = trim($row[2]); // Remove trailing spaces
                $name = trim($row[3]);

                if (empty($nis) || empty($name)) {
                    $errors[] = "Baris " . ($index + 2) . ": NIS atau Nama kosong";
                    continue;
                }

                $student = Student::where('nis', $nis)->first();

                if ($student) {
                    // Update existing student
                    $student->update([
                        'name' => $name,
                        'unit' => $unit,
                        'class' => $class,
                        'is_active' => true,
                    ]);
                    $updated++;
                } else {
                    // Create new student
                    Student::create([
                        'nis' => $nis,
                        'name' => $name,
                        'unit' => $unit,
                        'class' => $class,
                        'is_active' => true,
                    ]);
                    $imported++;
                }
            }

            DB::commit();

            $message = "Import berhasil! {$imported} siswa baru ditambahkan, {$updated} siswa diperbarui.";
            if (!empty($errors)) {
                $message .= " Terjadi " . count($errors) . " error.";
            }

            return redirect()->route('admin.students.index')
                ->with('success', $message)
                ->with('errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student import error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'nis' => 'required|string|max:255|unique:students,nis,' . $id,
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $student->update($validated);

        return response()->json(['success' => true, 'message' => 'Siswa berhasil diperbarui!']);
    }

    /**
     * Remove the specified student.
     */
    public function destroy($id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json(['success' => true, 'message' => 'Siswa berhasil dihapus!']);
    }
}
