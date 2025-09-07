<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsClientController extends Controller
{
    public function index(Request $request)
    {
        // Query chính chỉ lấy tin đã xuất bản
        $query = News::published()->orderBySort();

        // Tìm kiếm theo từ khóa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục (nếu có)
        if ($request->filled('category')) {
            // TODO: Thêm logic lọc theo category nếu cần
        }

        // Lấy tin tức nổi bật (chỉ tin đã xuất bản)
        $featuredNews = News::published()
            ->featured()
            ->orderBySort()
            ->limit(3)
            ->get();

        // Lấy tin tức hot (chỉ tin đã xuất bản)
        $hotNews = News::published()
            ->hot()
            ->orderBySort()
            ->limit(5)
            ->get();

            $latestNews = News::published()
            ->orderBy('published_at', 'desc')
            ->limit(2)
            ->get();
            $news = $query->paginate(10)->appends($request->query());
        return view('client.news.index', compact('news', 'featuredNews', 'hotNews','latestNews'));
    }


    public function show($slug)
    {
        // Chỉ tìm tin đã xuất bản
        $news = News::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Tăng lượt xem
        $news->incrementViewCount();

        // Tin tức liên quan (cũng chỉ lấy tin đã xuất bản)
        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->where(function ($query) use ($news) {
                $query->where('author', $news->author)
                      ->orWhere('is_featured', true);
            })
            ->orderBySort()
            ->limit(4)
            ->get();

        // Tin tức mới nhất (chỉ tin đã xuất bản)
        $latestNews = News::published()
            ->where('id', '!=', $news->id)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return view('client.news.show', compact('news', 'relatedNews', 'latestNews'));
    }
}
