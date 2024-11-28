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
            try {
                // Generate a unique file name
                $fileName = time() . '-' . uniqid() . '.' . $request->img_upload->extension();

                // Upload the image to Backblaze B2
                $imagePath = $request->file('img_upload')->storeAs('images', $fileName, 'b2');

                if ($imagePath) {
                    // Make the file public
                    Storage::disk('b2')->setVisibility($imagePath, 'public');

                    // Manually construct the public URL
                    $imageUrl = "https://f000.backblazeb2.com/file/" . env('B2_BUCKET_NAME') . "/" . $imagePath;
                }
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Error uploading the image: ' . $e->getMessage());
            }
        }

        Eknexa::create([
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $imagePath,
        ]);

        return redirect('/')->with('success', 'Post created successfully!');
    }
}
