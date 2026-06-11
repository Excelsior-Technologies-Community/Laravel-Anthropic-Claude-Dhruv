readme_content = """# Claude API Project in Laravel

A basic Laravel project to practice interacting with Anthropic's Claude API.

## Package Used
This project utilizes the [claude-php/Claude-PHP-SDK-Laravel](https://github.com/claude-php/Claude-PHP-SDK-Laravel) package. It allows us to handle Anthropic API requests easily using a clean Laravel Facade (`Claude::messages()`).

## Setup Instructions

1. **Clone the repository:**

```bash
   git clone [https://github.com/yourusername/claude-laravel-practice.git](https://github.com/yourusername/claude-laravel-practice.git)
   cd claude-laravel-practice
```

---

2. **Install Dependencies:**

```bash
   composer install
```

---

3. **Environment setup:**

```bash
    cp .env.example .env
    php artisan key:generate
```

---

4. **Configure the Claude SDK:**
Publish the SDK configuration file:

```bash
    php artisan vendor:publish --tag=claude-config
```
Add your Anthropic API key to the .env file. (Note: If you leave this blank, the app will run in a built-in "Mock Mode" for local UI testing).
```bash
    ANTHROPIC_API_KEY=your_api_key_here
```

---

5. **Running the App:**
Start the local development server:

```bash
    php artisan serve
```
Visit ```http://127.0.0.1:8000/claude``` in your browser to test the chat interface!
