<?php

namespace App\Http\Controllers;

use App\Models\Eknexa;
use Illuminate\Http\Request;
use Exception;
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

        if ($request->hasFile('img_upload')) {
            // Generate a unique file name with its original extension
            $fileName = time() . '-' . uniqid() . '.' . $request->file('img_upload')->getClientOriginalExtension();

            // Upload the image to the B2 bucket
            $imagePath = $request->file('img_upload')->storeAs('images', $fileName, 'b2');

            // Ensure the file is set to public
            Storage::disk('b2')->setVisibility($imagePath, 'public');

            // Construct the public URL for the uploaded image
            $imageUrl = "https://f000.backblazeb2.com/file/" . env('B2_BUCKET_NAME') . "/" . $imagePath;

            // Return success response with the image URL
            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully!',
                'url' => $imageUrl,
            ]);
        }

        Eknexa::create([
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $imagePath,
        ]);

        return redirect('/')->with('success', 'Post created successfully!');
    }
}
