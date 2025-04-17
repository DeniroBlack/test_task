<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестовое задание</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        .header {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            padding: 1.5rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .main-title {
            color: white;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .content-wrapper {
            padding: 2rem 0;
            min-height: calc(100vh - 86px);
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="main-title mb-0">Тестовое задание</h1>
                <nav>
                    <a href="{{ url('/') }}" class="nav-link">Главная</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="content-wrapper">
        <div class="container">
            @section('content')
                <!-- Содержимое по умолчанию -->
                <div class="text-center mt-5">
                    <h2 class="mb-4">Добро пожаловать!</h2>
                    <p class="lead text-muted">
                        Для проверки тестового задания и загрузки Excel файла откройте
                        <a href="{{ route('forms.upload_excel_data') }}" class="text-decoration-none">эту ссылку</a>.
                    </p>
                </div>
            @show
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
