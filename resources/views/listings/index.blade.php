<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Объявления о продаже одежды</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0; 
            padding: 0;
            background: #f8f8f8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }
        header, footer {
            background: #333;
            color: white;
            padding: 15px;
            width: 100%;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        header a {
            color: white;
            text-decoration: none;
            font-weight: 600;
        }
        main {
            padding: 15px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            flex-grow: 1;
        }
        .listings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            width: 100%;
        }
        .listing {
            background: white;
            border-radius: 6px;
            box-shadow: 0 0 5px #ccc;
            padding: 15px;
            display: flex;
            flex-direction: column;
            max-width: 100%;
        }
        .listing img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .details {
            flex-grow: 1;
        }
        .details h3 {
            margin: 0 0 10px 0;
            font-weight: 700;
            font-size: 1.25rem;
            word-break: break-word;
        }
        .details p {
            margin: 4px 0;
            font-size: 0.95rem;
            color: #333;
            word-break: break-word;
        }
        
        .btn-primary {
            background: #0d6efd;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            font-size: 0.875rem;
        }
        .btn-primary:hover {
            background: #084cdf;
        }
        .btn-danger {
            background: #dc3545;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            font-size: 0.875rem;
        }
        .btn-danger:hover {
            background: #bb2d3b;
        }
        .header-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a, .pagination span {
            margin: 0 4px;
            padding: 6px 10px;
            background: #ddd;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .pagination span.current {
            background: #333;
            color: white;
        }
        footer {
            text-align: center;
            margin-top: auto;
        }

        .logout-form {
            display: inline;
            margin: 0;
            padding: 0;
        }
        #google_translate_element {
            display: inline-block;
            margin-right: 15px;
        }
        .goog-te-gadget {
            font-family: Arial, sans-serif !important;
        }
        .goog-te-gadget-simple {
            background: #f8f8f8 !important;
            border: 1px solid #ccc !important;
            padding: 5px !important;
            border-radius: 4px !important;
        }
    </style>
</head>
<body>
<header>
    <div class="header-group">
        @if(auth()->check())
            <form class="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-danger">Выйти</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-primary">Войти</a>
        @endif
    </div>
    <div id="google_translate_element"></div>
    <script>
    function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'ru',
        includedLanguages: 'en,ru',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
    }
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <div class="header-group">
        <a href="{{ route('cart.index') }}" class="btn-primary">Корзина</a>
        @if(auth()->check() && auth()->user()->role === 'Администратор')
            <a href="{{ route('listings.admin') }}" class="btn-primary">Панель администратора</a>
        @endif
        <form action="{{ route('listings.index') }}" method="GET" style="margin-left: 15px;">
            <input
                type="text"
                name="search"
                placeholder="Поиск по названию..."
                value="{{ request('search') }}"
                style="padding: 5px 8px; border-radius: 4px; border: 1px solid #ccc;"
            />
            <button type="submit" class="btn-primary" style="margin-left: 5px;">Найти</button>
        </form>
    </div>
</header>

<main>
    <div class="listings-grid">
        @foreach($listings as $listing)
        <div class="listing">
            @if($listing->photo)
                <img src="{{ asset('storage/' . $listing->photo) }}" alt="{{ $listing->title }}">
            @else
                <img src="https://via.placeholder.com/300x200?text=Фото" alt="Без фото">
            @endif
            <div class="details">
                <h3>{{ $listing->title }}</h3>
                <p><strong>Бренд:</strong> {{ $listing->brand }}</p>
                <p><strong>Размер:</strong> {{ $listing->size }}</p>
                <p><strong>Остаток:</strong> {{ $listing->quantity }}</p>
                <p><strong>Цена:</strong> {{ number_format($listing->price, 2, ',', ' ') }} ₽</p>
                <p><strong>Сезон:</strong> {{ $listing->season }}</p>

                <form action="{{ route('cart.add', $listing) }}" method="POST" class="add-to-cart-form" data-listing-id="{{ $listing->id }}">
                    @csrf
                    <button type="submit" class="btn-primary">В корзину</button>
                </form>

            </div>
        </div>
        @endforeach
    </div>

    <nav class="pagination">
        @if ($listings->onFirstPage())
            <span>« Назад</span>
        @else
            <a href="{{ $listings->previousPageUrl() }}">« Назад</a>
        @endif

        <span class="current">Страница {{ $listings->currentPage() }} из {{ $listings->lastPage() }}</span>

        @if ($listings->hasMorePages())
            <a href="{{ $listings->nextPageUrl() }}">Вперёд »</a>
        @else
            <span>Вперёд »</span>
        @endif
    </nav>
</main>

<footer>
    &copy; {{ date('Y') }}
</footer>
</body>
</html>