<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\NewsRequest;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = News::query();

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo featured
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        // Lọc theo hot
        if ($request->filled('is_hot')) {
            $query->where('is_hot', $request->is_hot);
        }

        // Số lượng hiển thị trên mỗi trang
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [15, 25, 50, 100]) ? $perPage : 15;

        $news = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewsRequest $request)
    {
        Log::info('News store method called', [
            'has_file' => $request->hasFile('featured_image'),
            'all_data' => $request->all()
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_hot'] = $request->has('is_hot');

        // Xử lý hình ảnh
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            Log::info('Processing image', [
                'original_name' => $image->getClientOriginalName(),
                'size' => $image->getSize(),
                'mime_type' => $image->getMimeType()
            ]);
            
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            Log::info('Generated image name', ['image_name' => $imageName]);
            
            try {
                $path = $image->storeAs('public/news', $imageName);
                Log::info('Image stored successfully', ['path' => $imageName]);
                $data['featured_image'] = 'news/' . $imageName;
                Log::info('Final featured_image path', ['featured_image' => $data['featured_image']]);
            } catch (\Exception $e) {
                Log::error('Error storing image', ['error' => $e->getMessage()]);
                return back()->withErrors(['featured_image' => 'Lỗi khi lưu hình ảnh: ' . $e->getMessage()])->withInput();
            }
        } else {
            Log::info('No image file provided');
        }

        try {
            $news = News::create($data);
            Log::info('News created successfully', ['news_id' => $news->id]);
        } catch (\Exception $e) {
            Log::error('Error creating news', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Lỗi khi tạo tin tức: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('news.index')
            ->with('success', 'Tin tức đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NewsRequest $request, News $news)
    {

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_hot'] = $request->has('is_hot');

        // Xử lý hình ảnh
        if ($request->hasFile('featured_image')) {
            // Xóa hình ảnh cũ
            if ($news->featured_image) {
                Storage::delete('public/' . $news->featured_image);
            }
            
            $image = $request->file('featured_image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/news', $imageName);
            $data['featured_image'] = 'news/' . $imageName;
        }

        $news->update($data);

        return redirect()->route('news.index')
            ->with('success', 'Tin tức đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        // Xóa hình ảnh
        if ($news->featured_image) {
            Storage::delete('public/' . $news->featured_image);
        }

        $news->delete();

        return redirect()->route('news.index')
            ->with('success', 'Tin tức đã được xóa thành công!');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(News $news)
    {
        $news->toggleFeatured();
        
        return response()->json([
            'success' => true,
            'is_featured' => $news->is_featured,
            'message' => $news->is_featured ? 'Đã đánh dấu nổi bật' : 'Đã bỏ đánh dấu nổi bật'
        ]);
    }

    /**
     * Toggle hot status
     */
    public function toggleHot(News $news)
    {
        $news->toggleHot();
        
        return response()->json([
            'success' => true,
            'is_hot' => $news->is_hot,
            'message' => $news->is_hot ? 'Đã đánh dấu hot' : 'Đã bỏ đánh dấu hot'
        ]);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        if (!$ids || !$action) {
            return redirect()->back()->with('error', 'Vui lòng chọn hành động và tin tức!');
        }

        switch ($action) {
            case 'delete':
                News::whereIn('id', $ids)->delete();
                $message = 'Đã xóa ' . count($ids) . ' tin tức!';
                break;
            case 'publish':
                News::whereIn('id', $ids)->update([
                    'status' => 'published',
                    'published_at' => now()
                ]);
                $message = 'Đã xuất bản ' . count($ids) . ' tin tức!';
                break;
            case 'draft':
                News::whereIn('id', $ids)->update(['status' => 'draft']);
                $message = 'Đã chuyển ' . count($ids) . ' tin tức về bản nháp!';
                break;
            default:
                return redirect()->back()->with('error', 'Hành động không hợp lệ!');
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Test method for debugging file upload
     */
    public function testUpload(Request $request)
    {
        Log::info('Test upload method called');
        Log::info('Request has file: ' . ($request->hasFile('featured_image') ? 'Yes' : 'No'));
        Log::info('Request all data: ' . json_encode($request->all()));
        Log::info('Request files: ' . json_encode($request->allFiles()));
        
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            Log::info('File info', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'is_valid' => $file->isValid(),
                'error' => $file->getError(),
                'error_message' => $file->getErrorMessage()
            ]);
            
            if (!$file->isValid()) {
                Log::error('File is not valid: ' . $file->getErrorMessage());
                return response()->json(['success' => false, 'error' => 'File không hợp lệ: ' . $file->getErrorMessage()]);
            }
            
            try {
                $path = $file->store('public/news');
                Log::info('File stored at: ' . $path);
                return response()->json(['success' => true, 'path' => $path]);
            } catch (\Exception $e) {
                Log::error('Error storing file: ' . $e->getMessage());
                return response()->json(['success' => false, 'error' => $e->getMessage()]);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'No file provided']);
    }
} 