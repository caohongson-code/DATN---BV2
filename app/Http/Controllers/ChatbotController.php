<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class ChatbotController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function index()
    {
        return view('chatbot'); // hiển thị trang chủ có chatbot
    }

    public function send(Request $request)
    {
        $message = $request->input('message');
        $reply = $this->gemini->chat($message);

        return response()->json([
            'reply' => $reply
        ]);
    }
}