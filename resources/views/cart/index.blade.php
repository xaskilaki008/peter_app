<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
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
        .total-price {
            text-align: right;
            margin: 20px 0;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .empty-cart {
            text-align: center;
            padding: 40px;
            font-size: 1.1rem;
            color: #666;
        }
    </style>
</head>
<body>
<header>
    <div class="header-group">
        <form class="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-danger">Выйти</button>
        </form>
    </div>
    <div class="header-group">
        <a href="{{ route('listings.index') }}" class="btn-primary">На главную</a>
        <div style="color: white; font-weight: 600;">
            Общая стоимость: {{ number_format($totalPrice, 2, ',', ' ') }} ₽
        </div>
    </div>
</header>

<main>
    <h1>Ваша корзина</h1>

    @if($items->isEmpty())
        <div class="empty-cart">
            <p>В корзине пока нет товаров.</p>
        </div>
    @else
        <div class="listings-grid">
            @foreach($items as $item)
            <div class="listing">
                @if($item->listing->photo)
                    <img src="{{ asset('storage/' . $item->listing->photo) }}" alt="{{ $item->listing->title }}">
                @else
                    <img src="https://via.placeholder.com/300x200?text=Фото" alt="Без фото">
                @endif
                <div class="details">
                    <h3>{{ $item->listing->title }}</h3>
                    <p><strong>Бренд:</strong> {{ $item->listing->brand }}</p>
                    <p><strong>Размер:</strong> {{ $item->listing->size }}</p>
                    <p><strong>Цена:</strong> {{ number_format($item->listing->price, 2, ',', ' ') }} ₽</p>
                    <p><strong>Сезон:</strong> {{ $item->listing->season }}</p>
                    
                    <form action="{{ route('cart.remove', $item->listing) }}" method="POST" 
                          onsubmit="return confirm('Удалить товар из корзины?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Удалить</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</main>

<footer>
    &copy; {{ date('Y') }}
</footer>
</body>
</html>