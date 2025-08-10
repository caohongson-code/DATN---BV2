<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsClientController extends Controller
{
    public function index(Request $request)
    {
        $query = News::published()->orderBySort();
        
        // Tìm kiếm theo từ khóa
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        // Lọc theo danh mục (nếu có)
        if ($request->has('category') && $request->category) {
            // Có thể thêm logic lọc theo category nếu cần
        }
        
        // Lấy tin tức nổi bật
        $featuredNews = News::published()->featured()->orderBySort()->limit(3)->get();
        
        // Lấy tin tức hot
        $hotNews = News::published()->hot()->orderBySort()->limit(5)->get();
        
        // Phân trang tin tức chính
        $news = $query->paginate(12);
        
        return view('client.news.index', compact('news', 'featuredNews', 'hotNews'));
    }
    
    public function show($slug)
    {
        $news = News::published()->where('slug', $slug)->firstOrFail();
        
        // Tăng lượt xem
        $news->incrementViewCount();
        
        // Tin tức liên quan (cùng tác giả hoặc cùng chủ đề)
        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->where(function($query) use ($news) {
                $query->where('author', $news->author)
                      ->orWhere('is_featured', true);
            })
            ->orderBySort()
            ->limit(4)
            ->get();
        
        // Tin tức mới nhất
        $latestNews = News::published()
            ->where('id', '!=', $news->id)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('client.news.show', compact('news', 'relatedNews', 'latestNews'));
    }
} 