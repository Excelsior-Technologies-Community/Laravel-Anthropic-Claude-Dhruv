<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claude MCP Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .chat-container { max-width: 800px; margin: 2rem auto; }
        .bot-response { background-color: #f1f3f4; border-left: 4px solid #1a73e8; }
        .user-response { background-color: #e8f0fe; border-right: 4px solid #6c757d; }
    </style>
</head>
<body>

<div class="container chat-container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4 text-center">Claude MCP Project</h2>

            @if(!empty($history))
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                        <h5 class="mb-0 fw-bold text-secondary">Conversation</h5>
                        <form action="{{ route('mcp.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                Clear Chat
                            </button>
                        </form>
                    </div>
                    
                    <div class="card-body bg-white" style="max-height: 500px; overflow-y: auto;">
                        @foreach($history as $message)
                            @php
                                $isToolCall = isset($message['parts'][0]['functionCall']);
                                $isToolResponse = isset($message['parts'][0]['functionResponse']);
                                $text = $message['parts'][0]['text'] ?? null;
                            @endphp

                            @if($message['role'] === 'user' && !$isToolResponse)
                                <div class="mb-3 text-end">
                                    <span class="badge bg-secondary mb-1">You</span>
                                    <div class="d-flex justify-content-end">
                                        <div class="p-3 border rounded text-start user-response" style="max-width: 80%;">
                                            {{ $text }}
                                        </div>
                                    </div>
                                </div>
                            @elseif($isToolCall)
                                <div class="mb-3 text-center">
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        ⚙️ Executed Local Tool: <code>{{ $message['parts'][0]['functionCall']['name'] }}</code>
                                    </span>
                                </div>
                            @elseif($message['role'] === 'model' && !$isToolCall && $text)
                                <div class="mb-4 text-start">
                                    <span class="badge mb-1" style="background-color: #1a73e8;">Gemini</span>
                                    <div class="d-flex justify-content-start">
                                        <div class="p-3 border rounded text-start bot-response" style="max-width: 80%;">
                                            {!! nl2br(e($text)) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('mcp.chat') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="prompt" class="form-label fw-bold text-dark">Your Message</label>
                            <textarea 
                                name="prompt" 
                                id="prompt" 
                                rows="3" 
                                class="form-control @error('prompt') is-invalid @enderror" 
                                placeholder="Ask a question or ask for the current time..." 
                                required>{{ old('prompt') }}</textarea>
                            
                            @error('prompt')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-dark px-4 py-2">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>