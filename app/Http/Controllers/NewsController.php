<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\NewsRequest;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        if ($request->filled('is_hot')) {
            $query->where('is_hot', $request->is_hot);
        }

        $perPage = in_array($request->get('per_page'), [15, 25, 50, 100]) ? $request->get('per_page') : 15;
        $news = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return view('admin.news.index', compact('news'));
    }

    public function store(NewsRequest $request)
{
    $data = $request->except('featured_image');
    $data['slug'] = Str::slug($request->title);
    $data['is_featured'] = $request->boolean('is_featured');
    $data['is_hot'] = $request->boolean('is_hot');

    if ($request->hasFile('featured_image')) {
        $file = $request->file('featured_image');
        if ($file->isValid()) {
            $imageName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            // Lưu file trong disk public, thư mục news
            $path = $file->storeAs('news', $imageName, 'public');

            if ($path) {
                $data['featured_image'] = $path;
            } else {
                return back()->withErrors(['featured_image' => 'Không thể lưu ảnh'])->withInput();
            }
        } else {
            return back()->withErrors(['featured_image' => 'Ảnh tải lên không hợp lệ'])->withInput();
        }
    }

    News::create($data);

    return redirect()->route('news.index')->with('success', 'Tin tức đã được tạo thành công!');
}
public function edit(News $news)
{
    return view('admin.news.edit', compact('news'));
}

public function update(NewsRequest $request, News $news)
{
    $data = $request->except('featured_image');
    $data['slug'] = Str::slug($request->title);
    $data['is_featured'] = $request->boolean('is_featured');
    $data['is_hot'] = $request->boolean('is_hot');

    if ($request->hasFile('featured_image')) {
        $file = $request->file('featured_image');

        if (!$file->isValid()) {
            return back()->withErrors(['featured_image' => 'File ảnh không hợp lệ hoặc quá lớn'])->withInput();
        }

        // Xóa ảnh cũ nếu có
        if ($news->featured_image && Storage::disk('public')->exists($news->featured_image)) {
            Storage::disk('public')->delete($news->featured_image);
        }

        $imageName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('news', $imageName, 'public');

        if ($path) {
            $data['featured_image'] = $path;
        } else {
            return back()->withErrors(['featured_image' => 'Không thể lưu ảnh'])->withInput();
        }
    }

    $news->update($data);

    return redirect()->route('news.index')->with('success', 'Tin tức đã được cập nhật thành công!');
}

    public function destroy(News $news)
    {
        if ($news->featured_image && Storage::disk('public')->exists($news->featured_image)) {
            Storage::disk('public')->delete($news->featured_image);
        }

        $news->delete();

        return redirect()->route('news.index')->with('success', 'Tin tức đã được xóa thành công!');
    }

    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }
}
