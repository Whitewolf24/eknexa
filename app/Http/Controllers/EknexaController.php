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
        $imageUrl = null;

        // Image upload (store in 'images' folder on B2)
        if ($request->hasFile('img_upload')) {
            $fileName = time() . '-' . uniqid() . '.' . $request->file('img_upload')->getClientOriginalExtension();
            // Store the image in the 'images' folder
            $imagePath = $request->file('img_upload')->storeAs('images', $fileName, 'b2');

            // Construct the friendly URL for the image (accessible from Backblaze)
            $imageUrl = "https://f003.backblazeb2.com/file/" . env('B2_BUCKET_NAME') . "/images/" . $fileName;
        }

        // Upload content as a txt file (store in 'posts' folder on B2)
        $contentFileName = time() . '-' . uniqid() . '.txt';
        $contentFilePath = $request->content;  // Store raw content as file path

        // Save the content file to B2 under 'posts' folder
        Storage::disk('b2')->put('posts/' . $contentFileName, $contentFilePath);

        // Construct the friendly URL for the content file (accessible from Backblaze)
        $contentFileUrl = "https://f003.backblazeb2.com/file/" . env('B2_BUCKET_NAME') . "/posts/" . $contentFileName;

        // Store post data in the database
        Eknexa::create([
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $imageUrl,  // Use the friendly URL for the image
            'content_file_path' => $contentFileUrl, // Use the friendly URL for the content file
        ]);

        return redirect('/');
    }
}
