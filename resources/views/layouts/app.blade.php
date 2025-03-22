<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'نظام إدارة المخازن' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto">
        <header class="bg-blue-600 text-white p-4 text-center">
            <h1>{{ $title ?? 'نظام إدارة المخازن' }}</h1>
        </header>

        <main class="py-8">
            {{ $slot }}
        </main>

        <footer class="bg-gray-800 text-white text-center p-4">
            © 2025 جميع الحقوق محفوظة.
        </footer>
    </div>
</body>
</html>
