<?php

namespace App\Http\Controllers;

use App\Models\Ram;
use Illuminate\Http\Request;

class RamController extends Controller
{
    public function index(Request $request)
    {
        $query = Ram::query();

    if ($request->filled('keyword')) {
        $query->where('value', 'like', '%' . $request->keyword . '%');
    }
    $rams = $query->orderByDesc('id')->paginate(5)->withQueryString();

    return view('admin.rams.index', [
        'rams' => $rams,
        'type' => 'RAM',
        'routePrefix' => 'rams',
    ]);
    }

    public function create()
    {
        return view('admin.rams.create', [
            'type' => 'RAM',
            'routePrefix' => 'rams',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:255|unique:rams,value',
        ]);

        Ram::create(['value' => $request->value]);

        return redirect()->route('rams.index')->with('success', 'Thêm RAM thành công.');
    }

    public function edit($id)
    {
        $rams = Ram::findOrFail($id);

        return view('admin.rams.edit', [
            'rams' => $rams,
            'type' => 'RAM',
            'routePrefix' => 'rams',
        ]);
    }

    public function update(Request $request, $id)
    {
        $rams = Ram::findOrFail($id);

        $request->validate([
            'value' => 'required|string|max:255|unique:rams,value,' . $rams->id,
        ]);

        $rams->update(['value' => $request->value]);

        return redirect()->route('rams.index')->with('success', 'Cập nhật RAM thành công.');
    }

    public function destroy($id)
    {
        $rams = Ram::findOrFail($id);
        $rams->delete();

        return redirect()->route('rams.index')->with('success', 'Xóa RAM thành công.');
    }
}
