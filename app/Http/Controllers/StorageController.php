<?php

namespace App\Http\Controllers;

use App\Models\Storage;
use App\Models\StorageOption;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function index(Request $request)
{
    $query = StorageOption::query();

    if ($request->has('keyword') && !empty($request->keyword)) {
        $query->where('value', 'like', '%' . $request->keyword . '%');
    }
    $storages = $query->orderByDesc('id')->paginate(10)->withQueryString();
    return view('admin.storages.index', [
        'storages' => $storages,
        'type' => 'Storage',
        'routePrefix' => 'storages',
    ]);
}


    public function create()
    {
        return view('admin.storages.create', [
            'type' => 'Storage',
            'routePrefix' => 'storages',
        ]);
    }

    public function store(Request $request)
    {
       $request->validate([
            'value' => 'required|string|max:255|unique:storages,value',
        ], [
            'value.required' => 'Vui lòng nhập dung lượng dung lượng',
            'value.string'   => 'Dung lượng dung lượng phải là chuỗi ký tự',
            'value.max'      => 'Dung lượng dung lượng không được vượt quá 255 ký tự',
            'value.unique'   => 'Dung lượng dung lượng này đã tồn tại',
        ]);
        StorageOption::create(['value' => $request->value]);

        return redirect()->route('storages.index')->with('success', 'Thêm dung lượng thành công.');
    }

    public function edit($id)
    {
        $storages = StorageOption::findOrFail($id);

        return view('admin.storages.edit', [
            'storages' => $storages,
            'type' => 'Storage',
            'routePrefix' => 'storages',
        ]);
    }

    public function update(Request $request, $id)
    {
        $storages = StorageOption::findOrFail($id);

        $request->validate([
            'value' => 'required|string|max:255|unique:storages,value,' . $storages->id,
        ]);

        $storages->update(['value' => $request->value]);

        return redirect()->route('storages.index')->with('success', 'Cập nhật dung lượng thành công.');
    }

public function destroy($id)
{
    $storage = StorageOption::findOrFail($id);

    // Kiểm tra có sản phẩm nào đang dùng Storage này không
    $hasVariants = \App\Models\ProductVariant::where('storage_id', $id)->exists();

    if ($hasVariants) {
        return redirect()
            ->route('storages.index')
            ->with('error', 'Dung lượng này đang được sử dụng bởi sản phẩm, không thể xóa.');
    }

    $storage->delete();

    return redirect()
        ->route('storages.index')
        ->with('success', 'Xóa dung lượng thành công.');
}
}
