<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claude API Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .chat-container { max-width: 800px; margin: 2rem auto; }
        .claude-response { background-color: #f8f9fa; border-left: 4px solid #7c3aed; }
    </style>
</head>
<body>

<div class="container chat-container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4 text-center">Claude API Project</h2>

            @if(isset($reply))
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="badge bg-secondary mb-2">You asked:</span>
                            <p class="mb-0 fs-5">{{ $prompt }}</p>
                        </div>
                        
                        <hr>
                        
                        <div class="mt-3 p-3 rounded claude-response">
                            <span class="badge bg-primary mb-2" style="background-color: #7c3aed !important;">Claude's Reply:</span>
                            <p class="mb-0">{!! nl2br(e($reply)) !!}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('claude.chat') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="prompt" class="form-label fw-bold">Enter your prompt</label>
                            <textarea 
                                name="prompt" 
                                id="prompt" 
                                rows="4" 
                                class="form-control @error('prompt') is-invalid @enderror" 
                                placeholder="E.g., Write a short welcome message for an e-commerce store..." 
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