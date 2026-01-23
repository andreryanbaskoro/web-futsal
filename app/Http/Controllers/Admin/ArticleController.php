<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->get();

        return view('admin.articles.index', [
            'title'    => 'Daftar Artikel',
            'articles' => $articles,
        ]);
    }

    public function create()
    {
        return view('admin.articles.create', [
            'title' => 'Tambah Artikel',
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'kategori'     => 'nullable|string|max:50',
            'author'       => 'nullable|string|max:100',
            'tanggal_post' => 'required|date',
            'waktu_baca'   => 'nullable|string|max:50',
            'konten'       => 'required|string',
            'tags'         => 'nullable|array',
            'tags.*'       => 'string|max:50',
            'image_type'   => 'required|in:upload,url',
            'featured_image_file' => 'nullable|image|max:2048', // 2MB
            'featured_image_url'  => 'nullable|url|max:255',
        ]);

        // Handle gambar
        if ($request->image_type === 'upload' && $request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('articles', 'public');
            $validated['featured_image'] = $path;
        } elseif ($request->image_type === 'url' && $request->featured_image_url) {
            $validated['featured_image'] = $request->featured_image_url;
        } else {
            $validated['featured_image'] = null;
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil ditambahkan');
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);

        return view('admin.articles.edit', [
            'title'   => 'Edit Artikel',
            'article' => $article,
        ]);
    }


    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'kategori'     => 'nullable|string|max:50',
            'author'       => 'nullable|string|max:100',
            'tanggal_post' => 'required|date',
            'waktu_baca'   => 'nullable|string|max:50',
            'konten'       => 'required|string',
            'tags'         => 'nullable|array',
            'tags.*'       => 'string|max:50',
            'image_type'   => 'required|in:upload,url',
            'featured_image_file' => 'nullable|image|max:2048',
            'featured_image_url'  => 'nullable|url|max:255',
        ]);

        // Handle gambar
        if ($request->image_type === 'upload' && $request->hasFile('featured_image_file')) {
            // hapus file lama jika ada
            if ($article->featured_image && !str_starts_with($article->featured_image, 'http')) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $path = $request->file('featured_image_file')->store('articles', 'public');
            $validated['featured_image'] = $path;
        } elseif ($request->image_type === 'url' && $request->featured_image_url) {
            $validated['featured_image'] = $request->featured_image_url;
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        try {
            // Hapus file gambar jika ada dan bukan URL
            if ($article->featured_image && !str_starts_with($article->featured_image, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($article->featured_image);
            }

            // Hapus data dari database
            $article->delete();

            return redirect()->route('admin.articles.index')
                ->with('success', 'Artikel berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus artikel: ' . $e->getMessage());
        }
    }
}
