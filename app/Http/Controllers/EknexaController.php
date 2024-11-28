<?php

namespace App\Http\Controllers;

use App\Models\Eknexa;
use Illuminate\Http\Request;
//use Exception;
use Illuminate\Support\Facades\Storage;

class EknexaController extends Controller
{
    public function index()
    {
        $posts = Eknexa::all();
        return view('eknexa.index', compact('posts'));
    }

    public function create()
    {
        return view('eknexa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'img_upload' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $imagePath = null;
        $contentFilePath = null;

        // Image upload
        if ($request->hasFile('img_upload')) {
            $fileName = time() . '-' . uniqid() . '.' . $request->file('img_upload')->getClientOriginalExtension();
            $imagePath = $request->file('img_upload')->storeAs('images', $fileName, 'b2');
            $imageUrl = "https://f000.backblazeb2.com/file/" . env('B2_BUCKET_NAME') . "/" . $imagePath;
        }

        // Upload content as a txt file
        $contentFileName = time() . '-' . uniqid() . '.txt';
        $contentFilePath = $request->content;  // Store raw content as file path

        // Save the content file to B2
        Storage::disk('b2')->put('posts/' . $contentFileName, $contentFilePath);

        // Store post data in the database
        Eknexa::create([
            'title' => $request->title,
            'content' => $request->content,  // Store raw content here
            'image_path' => $imagePath,
            'content_file_path' => 'posts/' . $contentFileName,  // Store file path for later retrieval
        ]);

        return redirect('/')->with('success', 'Post created successfully!');
    }
}
