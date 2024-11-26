<?php

namespace App\Http\Controllers;

use App\Models\Eknexa;
use Illuminate\Http\Request;

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
            'img_upload' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('img_upload')) {
            $imagePath = $request->file('img_upload')->store('images', 'public');
        }

        Eknexa::create([
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $imagePath,
        ]);

        return redirect('/')->with('success', 'Post created successfully!');
    }
}
