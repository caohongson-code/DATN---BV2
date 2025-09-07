<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderByDesc('created_at')->paginate(10);
        return view('admin.contact.index', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin.contact.show', compact('contact'));
    }

    public function updateStatus($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->status = $contact->status === 'pending' ? 'processed' : 'pending';
        $contact->save();
        Session::flash('success', 'Cập nhật trạng thái thành công!');
        return redirect()->route('admin.contact.index');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        Session::flash('success', 'Đã xóa liên hệ thành công!');
        return redirect()->route('admin.contact.index');
    }
}