<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'summary' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'author' => 'nullable|string|max:100',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_hot' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ];

        // Nếu là update, không bắt buộc featured_image
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['featured_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề tin tức không được để trống',
            'title.max' => 'Tiêu đề tin tức không được vượt quá 255 ký tự',
            'summary.required' => 'Tóm tắt tin tức không được để trống',
            'summary.max' => 'Tóm tắt tin tức không được vượt quá 500 ký tự',
            'content.required' => 'Nội dung tin tức không được để trống',
            'featured_image.image' => 'File phải là hình ảnh',
            'featured_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp',
            'featured_image.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
            'author.max' => 'Tên tác giả không được vượt quá 100 ký tự',
            'status.required' => 'Trạng thái không được để trống',
            'status.in' => 'Trạng thái không hợp lệ',
            'published_at.date' => 'Thời gian xuất bản không hợp lệ',
            'meta_title.max' => 'Meta title không được vượt quá 255 ký tự',
            'meta_description.max' => 'Meta description không được vượt quá 500 ký tự',
            'meta_keywords.max' => 'Meta keywords không được vượt quá 255 ký tự',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên',
            'sort_order.min' => 'Thứ tự sắp xếp không được nhỏ hơn 0'
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'title' => 'tiêu đề',
            'summary' => 'tóm tắt',
            'content' => 'nội dung',
            'featured_image' => 'hình ảnh đại diện',
            'author' => 'tác giả',
            'status' => 'trạng thái',
            'published_at' => 'thời gian xuất bản',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
            'meta_keywords' => 'meta keywords',
            'is_featured' => 'đánh dấu nổi bật',
            'is_hot' => 'đánh dấu hot',
            'sort_order' => 'thứ tự sắp xếp'
        ];
    }
} 