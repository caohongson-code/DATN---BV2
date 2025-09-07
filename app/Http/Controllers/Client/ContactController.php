<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\Contact;

class ContactController extends Controller
{
    public function showContactForm()
    {
        return view('client.contact');
    }

    public function submitContactForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Lưu vào DB
            $contact = Contact::create($validated);

            // Gửi email thông báo
            Mail::raw(
                "Khách hàng: {$validated['name']}\nSĐT: {$validated['phone']}\nNội dung: {$validated['message']}",
                function ($msg) {
                    $msg->to('webdemo@gmail.com')->subject('Liên hệ tư vấn mua hàng');
                }
            );

            Session::flash('success', 'Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm nhất.');
        } catch (\Exception $e) {
            Session::flash('error', 'Có lỗi xảy ra, vui lòng thử lại sau.');
        }
        return redirect()->route('client.contact');
    }
}