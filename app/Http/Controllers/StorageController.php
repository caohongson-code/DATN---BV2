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
        $storages = StorageOption::findOrFail($id);
        $storages->delete();

        return redirect()->route('storages.index')->with('success', 'Xóa dung lượng thành công.');
    }
}
