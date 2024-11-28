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

        // image upload
        if ($request->hasFile('img_upload')) {
            $fileName = time() . '-' . uniqid() . '.' . $request->file('img_upload')->getClientOriginalExtension();
            $imagePath = $request->file('img_upload')->storeAs('images', $fileName, 'b2');
            $imageUrl = "https://f000.backblazeb2.com/file/" . env('B2_BUCKET_NAME') . "/" . $imagePath;
        }

        // upload
        $contentFileName = time() . '-' . uniqid() . '.txt';
        $contentFilePath = $request->content;

        // Save the content as txt
        Storage::disk('b2')->put('posts/' . $contentFileName, $contentFilePath);

        // Store in database
        Eknexa::create([
            'title' => $request->title,
            'content' => $contentFilePath,
            'image_path' => $imagePath,
            'content_file_path' => 'posts/' . $contentFileName, // Save the file path
        ]);

        return redirect('/')->with('success', 'Post created successfully!');
    }
}
