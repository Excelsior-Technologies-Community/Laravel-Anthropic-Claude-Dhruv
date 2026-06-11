<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ClaudeController extends Controller
{
    //
    public function index()
    {
        $history = Session::get('chat_history', []);
        return view('claude.index', compact('history'));
    }

    public function chat(Request $request)
    {
        $request->validate(['prompt' => 'required|string']);
        $userPrompt = $request->input('prompt');
        $apiKey = Config::get('claude.api_key');

        $history = Session::get('chat_history', []);

        $history[] = [
            'role' => 'user',
            'parts' => [['text' => $userPrompt]]
        ];

        if (empty($apiKey) || $apiKey === 'dummy_key_for_now') {
            $replyText = "API Key not detected. Simulated reply to: " . $userPrompt;
        } else {
            try {
                $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => $history 
                ]);

                if ($response->successful()) {
                    $replyText = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'No response text found.';
                } else {
                    $replyText = "Gemini API Error: " . $response->body();
                }
            } catch (\Exception $e) {
                $replyText = "Error: " . $e->getMessage();
            }
        }

        $history[] = [
            'role' => 'model', 
            'parts' => [['text' => $replyText]]
        ];

        Session::put('chat_history', $history);

        return redirect()->route('claude.index');
    }

    public function clear()
    {
        Session::forget('chat_history');
        return redirect()->route('claude.index');
    }
}


