<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class McpController extends Controller
{
    public function index()
    {
        $history = Session::get('mcp_chat_history', []);
        return view('mcp.index', compact('history'));
    }

    public function chat(Request $request)
    {
        $request->validate(['prompt' => 'required|string']);
        $userPrompt = $request->input('prompt');
        $apiKey = Config::get('claude.api_key'); 

        $history = Session::get('mcp_chat_history', []);

        $history[] = [
            'role' => 'user',
            'parts' => [['text' => $userPrompt]]
        ];

        if (empty($apiKey) || $apiKey === 'dummy_key_for_now') {
            $history[] = ['role' => 'model', 'parts' => [['text' => "Simulated mode active. You asked: $userPrompt"]]];
            Session::put('mcp_chat_history', $history);
            return redirect()->route('mcp.index');
        }

        $tools = [
            [
                'functionDeclarations' => [
                    [
                        'name' => 'get_current_time',
                        'description' => 'Get the current server time and date. Use this whenever the user asks for the time, date, or day.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => (object)[] 
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => $history,
                'tools' => $tools
            ]);

            if (!$response->successful()) {
                throw new \Exception("Step 1 Error: " . $response->body());
            }

            $responseData = $response->json();
            $modelMessage = $responseData['candidates'][0]['content'] ?? null;

            if ($modelMessage) {
                
                if (isset($modelMessage['parts'][0]['functionCall']['args'])) {
                    if (empty($modelMessage['parts'][0]['functionCall']['args'])) {
                        $modelMessage['parts'][0]['functionCall']['args'] = (object)[];
                    }
                }

                $history[] = $modelMessage;
                
                if (isset($modelMessage['parts'][0]['functionCall'])) {
                    $functionCall = $modelMessage['parts'][0]['functionCall'];
                    
                    if ($functionCall['name'] === 'get_current_time') {
                        $toolResult = now()->format('l, F j, Y g:i A'); 
                    } else {
                        $toolResult = "Unknown function requested.";
                    }

                    $history[] = [
                        'role' => 'function', 
                        'parts' => [
                            [
                                'functionResponse' => [
                                    'name' => $functionCall['name'],
                                    'response' => [
                                        'name' => $functionCall['name'],
                                        'content' => $toolResult
                                    ]
                                ]
                            ]
                        ]
                    ];

                    $secondResponse = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                        'contents' => $history,
                        'tools' => $tools
                    ]);

                    if (!$secondResponse->successful()) {
                         throw new \Exception("Step 2 Error: " . $secondResponse->body());
                    }

                    $finalMessage = $secondResponse->json()['candidates'][0]['content'] ?? null;
                    if ($finalMessage) {
                        $history[] = $finalMessage;
                    }
                }
            }

        } catch (\Exception $e) {
            $history[] = ['role' => 'model', 'parts' => [['text' => $e->getMessage()]]];
        }

        Session::put('mcp_chat_history', $history);
        return redirect()->route('mcp.index');
    }

    public function clear()
    {
        Session::forget('mcp_chat_history');
        return redirect()->route('mcp.index');
    }
}