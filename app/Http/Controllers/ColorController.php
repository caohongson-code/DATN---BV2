<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        $colors = Color::orderBy('id', 'desc')->paginate(10);

    return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create', [
            'type'        => 'Màu sắc',
            'routePrefix' => 'colors',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:255|unique:colors,value',
            'code'  => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ], [
            'code.regex' => 'Mã màu phải đúng định dạng HEX (ví dụ: #FF0000).',
        ]);

        Color::create([
            'value' => $request->value,
            'code'  => $request->code,
        ]);

        return redirect()
            ->route('colors.index')
            ->with('success', 'Thêm màu sắc thành công.');
    }

    public function edit($id)
    {
        $color = Color::findOrFail($id);

        return view('admin.colors.edit', [
            'color'       => $color,
            'type'        => 'Màu sắc',
            'routePrefix' => 'colors',
        ]);
    }

    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);

        $request->validate([
            'value' => 'required|string|max:255|unique:colors,value,' . $color->id,
            'code'  => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ], [
            'code.regex' => 'Mã màu phải đúng định dạng HEX (ví dụ: #FF0000).',
        ]);

        $color->update([
            'value' => $request->value,
            'code'  => $request->code,
        ]);

        return redirect()
            ->route('colors.index')
            ->with('success', 'Cập nhật màu sắc thành công.');
    }

    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();

        return redirect()
            ->route('colors.index')
            ->with('success', 'Xóa màu sắc thành công.');
    }
}
