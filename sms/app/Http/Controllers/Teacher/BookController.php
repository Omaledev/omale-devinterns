<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\ClassLevel;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        // Show books uploaded by this teacher
        $books = Book::where('teacher_id', auth()->id())
            ->with(['classLevel', 'subject'])
            ->latest()
            ->paginate(10);

        return view('teacher.books.index', compact('books'));
    }

    public function create()
    {
        $schoolId = session('active_school');
        $classes = ClassLevel::where('school_id', $schoolId)->get();
        $subjects = Subject::where('school_id', $schoolId)->get();

        return view('teacher.books.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'class_level_id' => 'required|exists:class_levels,id',
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240', // Max 10MB
        ]);

        $file = $request->file('file');
        
        // Store file in 'public/books' folder
        $path = $file->store('books', 'public');

        Book::create([
            'school_id' => session('active_school'),
            'teacher_id' => auth()->id(),
            'class_level_id' => $request->class_level_id,
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => round($file->getSize() / 1024), // KB
        ]);

        return redirect()->route('teacher.books.index')
            ->with('success', 'Book uploaded successfully!');
    }

    public function destroy(Book $book)
    {
        // Security: Ensure teacher owns the book
        if($book->teacher_id !== auth()->id()) abort(403);

        // Delete file from storage
        if(Storage::disk('public')->exists($book->file_path)){
            Storage::disk('public')->delete($book->file_path);
        }

        $book->delete();

        return redirect()->route('teacher.books.index')
            ->with('success', 'Book deleted successfully.');
    }
}