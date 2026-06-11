<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ClaudePhp\Laravel\Facades\Claude;
use Illuminate\Support\Facades\Config;

class ClaudeController extends Controller
{
    //
    public function index()
    {
        return view('claude.index');
    }

    public function chat(Request $request)
    {
        $request->validate(['prompt' => 'required|string']);
        $userPrompt = $request->prompt;

        $apiKey = Config::get('claude.api_key');

        if (empty($apiKey)) {
            $reply = "API Key not detected.";
        } else {
            try {
                $response = Claude::messages()->create([
                    'model' => 'MODEL_CLAUDE_OPUS_4_6',
                    'system' => 'You are a Maths Tutor. While giving answer to a question, you does not directly give answer but give small hints and guide through the solution',
                    'max_tokens' => 1024,
                    'messages' => [['role' => 'user', 'content' => $userPrompt]]
                ]);
                $reply = $response['content'][0]['text'];
            } catch (\Exception $e) {
                $reply = "Error: " . $e->getMessage();
            }
        }

        return view('claude.index', ['reply' => $reply, 'prompt' => $userPrompt]);
    }
}
