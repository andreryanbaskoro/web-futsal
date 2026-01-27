<?php


namespace App\Http\Controllers\Pelanggan;


use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Support\Str;



class BlogController extends Controller
{
    /**
     * Tampilkan daftar lapangan untuk halaman pelanggan.
     * Mendukung filter `status` dan `sort` via query string.
     */
    public function blog()
    {
        $articles = Article::orderBy('tanggal_post', 'desc')
            ->paginate(6);

        // artikel populer (contoh: terbaru)
        $popularArticles = Article::orderBy('tanggal_post', 'desc')
            ->limit(5)
            ->get();

        // kategori + jumlah
        $categories = Article::select('kategori')
            ->selectRaw('count(*) as total')
            ->groupBy('kategori')
            ->get();

        return view('pelanggan.blog', compact(
            'articles',
            'popularArticles',
            'categories'
        ));
    }


    public function blogDetail($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        // Artikel terkait (kategori sama, kecuali artikel ini)
        $relatedArticles = Article::where('kategori', $article->kategori)
            ->where('id', '!=', $article->id)
            ->orderBy('tanggal_post', 'desc')
            ->limit(3)
            ->get();

        return view('pelanggan.blog-detail', compact(
            'article',
            'relatedArticles'
        ));
    }
}
