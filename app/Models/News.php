<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'featured_image',
        'author',
        'status',
        'published_at',
        'view_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_featured',
        'is_hot',
        'sort_order'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_hot' => 'boolean',
        'view_count' => 'integer',
        'sort_order' => 'integer'
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', Carbon::now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeHot($query)
    {
        return $query->where('is_hot', true);
    }

    public function scopeOrderBySort($query)
    {
        return $query->orderBy('sort_order', 'asc')
                    ->orderBy('published_at', 'desc');
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        $statuses = [
            'draft' => 'Bản nháp',
            'published' => 'Đã xuất bản',
            'archived' => 'Đã lưu trữ'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'badge-secondary',
            'published' => 'badge-success',
            'archived' => 'badge-warning'
        ];
        
        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getIsPublishedAttribute()
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at <= Carbon::now();
    }

    // Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function setPublishedAtAttribute($value)
    {
        if ($value) {
            $this->attributes['published_at'] = Carbon::parse($value);
        }
    }

    // Methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function toggleFeatured()
    {
        $this->update(['is_featured' => !$this->is_featured]);
    }

    public function toggleHot()
    {
        $this->update(['is_hot' => !$this->is_hot]);
    }
} 