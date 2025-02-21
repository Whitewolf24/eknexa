<?php

namespace App\Http\Controllers;

use App\Models\Eknexa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EknexaController extends Controller
{
    public function index()
    {
        $posts = Eknexa::orderBy('created_at', 'desc')->get();
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

        $image_path = null;
        $content_url = null;

        if ($request->hasFile('img_upload')) {
            $file = time() . '-' . uniqid() . '.' . $request->file('img_upload')->getClientOriginalExtension();
            $image_path = $request->file('img_upload')->storeAs('images', $file, 'b2');
            $image_path = "https://f003.backblazeb2.com/file/" . env('B2_BUCKET_NAME') . "/images/" . $file;
        }

        $content = time() . '-' . uniqid() . '.txt';
        Storage::disk('b2')->put('posts/' . $content, $request->content);
        $content_url = "https://f003.backblazeb2.com/file/" . env('B2_BUCKET_NAME') . "/posts/" . $content;

        Eknexa::create([
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $image_path,
            'content_file_path' => $content_url,
        ]);

        return redirect('/');
    }
}
