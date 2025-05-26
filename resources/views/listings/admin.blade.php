<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Админ-панель объявлений</title>
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
    header {
        background: #333;
        color: white;
        padding: 15px;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    header button,
    header form button,
    header a.btn-primary {
        background: #0d6efd;
        border: none;
        color: white;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        display: inline-block;
        margin-left: 8px;
        transition: background-color 0.2s ease;
    }
    header button:hover,
    header form button:hover,
    header a.btn-primary:hover {
        background: #084cdf;
    }
    header form button.btn-danger {
        background: #dc3545;
        margin-left: 8px;
    }
    header form button.btn-danger:hover {
        background: #bb2d3b;
    }
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border-radius: 6px;
        box-shadow: 0 0 5px #ccc;
        margin-bottom: 1rem;
        overflow: hidden;
        font-size: 0.95rem;
    }
    thead tr {
        background: #f4f4f4;
    }
    th, td {
        padding: 12px 15px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        vertical-align: middle;
        word-break: break-word;
    }
    tbody tr:last-child td {
        border-bottom: none;
    }
    img {
        max-width: 60px;
        max-height: 60px;
        border-radius: 4px;
        border: 1px solid #ddd;
        object-fit: cover;
    }
    .btn-sm {
        padding: 4px 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        border: none;
        transition: background-color 0.2s ease;
    }
    .btn-warning {
        background: #ffc107;
        color: black;
        margin-right: 6px;
    }
    .btn-warning:hover {
        background: #e0a800;
    }
    .btn-danger {
        background: #dc3545;
        color: white;
    }
    .btn-danger:hover {
        background: #bb2d3b;
    }
    /* Модальное окно */
    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 6px;
        width: 100%;
        max-width: 400px;
        position: relative;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        font-size: 0.9rem;
    }
    .modal-content label {
        display: block;
        margin-top: 12px;
        font-weight: 700;
        color: #333;
    }
    .modal-content input[type="text"],
    .modal-content input[type="number"],
    .modal-content input[type="file"] {
        width: 100%;
        padding: 8px;
        margin-top: 6px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 0.9rem;
        box-sizing: border-box;
    }
    .modal-content button {
        margin-top: 20px;
        padding: 8px 16px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .btn-primary {
        background: #0d6efd;
        color: white;
    }
    .btn-primary:hover {
        background: #084cdf;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
        margin-left: 10px;
    }
    .btn-secondary:hover {
        background: #5a6268;
    }
    .pagination {
        text-align: center;
        margin-top: 20px;
    }
    .pagination a, .pagination span {
        margin: 0 5px;
        padding: 6px 12px;
        background: #ddd;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-block;
    }
    .pagination span.current {
        background: #333;
        color: white;
    }
    /* Адаптив */
    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }
        thead tr {
            display: none;
        }
        tbody tr {
            margin-bottom: 1rem;
            background: white;
            border-radius: 6px;
            box-shadow: 0 0 5px #ccc;
            padding: 15px;
        }
        tbody tr td {
            border: none;
            padding: 10px 10px 10px 40%;
            text-align: left;
            position: relative;
        }
        tbody tr td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            top: 10px;
            font-weight: 700;
            color: #555;
            width: 35%;
            white-space: nowrap;
        }
        tbody tr td img {
            position: static;
            margin: 0 auto 10px auto;
            display: block;
            max-width: 100px;
            max-height: 100px;
        }
    }
</style>
</head>
<body>
<header>
    <div>
        <button id="btn-back">← Назад</button>
    </div>
    <div>
        <button id="btn-change-roles" class="btn-primary">Изменить роли</button>
        <button id="btn-add" class="btn-primary">Добавить объявление</button>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn-danger">Выйти</button>
        </form>
    </div>
</header>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Фото</th>
            <th>Название</th>
            <th>Бренд</th>
            <th>Размер</th>
            <th>Количество</th>
            <th>Цена</th>
            <th>Сезон</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody id="listings-table-body">
        @foreach($listings as $listing)
            <tr data-id="{{ $listing->id }}">
                <td data-label="ID">{{ $listing->id }}</td>
                <td data-label="Фото">
                    @if($listing->photo)
                        <img src="{{ asset('storage/' . $listing->photo) }}" alt="Фото">
                    @else
                        Нет фото
                    @endif
                </td>
                <td data-label="Название">{{ $listing->title }}</td>
                <td data-label="Бренд">{{ $listing->brand }}</td>
                <td data-label="Размер">{{ $listing->size }}</td>
                <td data-label="Количество">{{ $listing->quantity }}</td>
                <td data-label="Цена">{{ number_format($listing->price, 2, ',', ' ') }} ₽</td>
                <td data-label="Сезон">{{ $listing->season }}</td>
                <td data-label="Действия">
                    <button class="btn-edit btn-sm btn-warning">Редактировать</button>
                    <button class="btn-delete btn-sm btn-danger">Удалить</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination">
    {{-- Пагинация --}}
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
</div>

<div id="modal" class="modal">
    <div class="modal-content">
        <h3 id="modal-title">Добавить объявление</h3>
        <form id="modal-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">

            <label>Название:</label>
            <input type="text" name="title" required maxlength="255">

            <label>Бренд:</label>
            <input type="text" name="brand" required maxlength="255">

            <label>Размер:</label>
            <input type="text" name="size" required maxlength="50">

            <label>Количество:</label>
            <input type="number" name="quantity" required min="0">

            <label>Цена:</label>
            <input type="number" name="price" required min="0" step="0.01">

            <label>Сезон:</label>
            <input type="text" name="season" required maxlength="50">

            <label>Фото (jpg/png):</label>
            <input type="file" name="photo" accept="image/*">

            <div style="text-align: right;">
                <button type="submit" class="btn-primary">Сохранить</button>
                <button type="button" id="modal-close" class="btn-secondary">Отмена</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-roles" class="modal">
    <div class="modal-content" style="max-width: 500px; max-height: 70vh; overflow-y: auto;">
        <h3>Изменить роли пользователей</h3>
        <form id="roles-form">
            <div id="users-list" style="max-height: 400px; overflow-y: auto; margin-bottom: 20px;">
            </div>
            <div style="text-align: right;">
                <button type="submit" class="btn-primary">Сохранить</button>
                <button type="button" id="roles-modal-close" class="btn-secondary">Отмена</button>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btn-change-roles').addEventListener('click', () => {
        fetch('/admin/users')
            .then(res => res.json())
            .then(users => {
                const usersList = document.getElementById('users-list');
                usersList.innerHTML = '';

                const currentUserId = {{ auth()->id() }};

                users.forEach(user => {
                    if (user.id === currentUserId) return;

                    const userRow = document.createElement('div');
                    userRow.style.marginBottom = '10px';
                    userRow.innerHTML = `
                        <label>${user.email}</label>
                        <select name="roles[${user.id}]" style="width: 100%; padding: 6px; margin-top: 4px;">
                            <option value="Пользователь" ${user.role === 'Пользователь' ? 'selected' : ''}>Пользователь</option>
                            <option value="Администратор" ${user.role === 'Администратор' ? 'selected' : ''}>Администратор</option>
                        </select>
                    `;
                    usersList.appendChild(userRow);
                });

                document.getElementById('modal-roles').style.display = 'flex';
            });
    });

    document.getElementById('roles-modal-close').addEventListener('click', () => {
        document.getElementById('modal-roles').style.display = 'none';
    });

    document.getElementById('modal-roles').addEventListener('click', e => {
        if (e.target === document.getElementById('modal-roles')) {
            document.getElementById('modal-roles').style.display = 'none';
        }
    });

    document.getElementById('roles-form').addEventListener('submit', e => {
        e.preventDefault();

        const formData = new FormData(e.target);

        fetch('/admin/users/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('modal-roles').style.display = 'none';
        })
        .catch(() => {
            alert('Ошибка при обновлении ролей.');
        });
    });


    const modal = document.getElementById('modal');
    const modalForm = document.getElementById('modal-form');
    const modalTitle = document.getElementById('modal-title');
    const methodInput = document.getElementById('method');
    let editingId = null;

    document.getElementById('btn-add').addEventListener('click', () => {
        editingId = null;
        modalTitle.textContent = 'Добавить объявление';
        methodInput.value = 'POST';
        modalForm.reset();
        modal.style.display = 'flex';
    });

    document.getElementById('modal-close').addEventListener('click', () => {
        modal.style.display = 'none';
    });

    modal.addEventListener('click', e => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', e => {
            const tr = e.target.closest('tr');
            editingId = tr.getAttribute('data-id');
            modalTitle.textContent = 'Редактировать объявление #' + editingId;
            methodInput.value = 'PUT';

            modalForm.title.value = tr.querySelector('td[data-label="Название"]').textContent.trim();
            modalForm.brand.value = tr.querySelector('td[data-label="Бренд"]').textContent.trim();
            modalForm.size.value = tr.querySelector('td[data-label="Размер"]').textContent.trim();
            modalForm.quantity.value = tr.querySelector('td[data-label="Количество"]').textContent.trim();
            modalForm.price.value = tr.querySelector('td[data-label="Цена"]').textContent.replace(/[^\d,.-]/g, '').replace(',', '.').trim();
            modalForm.season.value = tr.querySelector('td[data-label="Сезон"]').textContent.trim();

            modal.style.display = 'flex';
        });
    });

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', e => {
            const tr = e.target.closest('tr');
            const id = tr.getAttribute('data-id');
            if (confirm(`Удалить объявление #${id}?`)) {
                fetch(`/admin/listings/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                }).then(response => {
                    if (response.ok) {
                        tr.remove();
                    } else {
                        alert('Ошибка при удалении.');
                    }
                });
            }
        });
    });

    modalForm.addEventListener('submit', e => {
        e.preventDefault();
        const formData = new FormData(modalForm);

        let url = '/admin/listings';
        let method = 'POST';

        if (editingId) {
            url += '/' + editingId;
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                response.json().then(data => {
                    alert('Ошибка при сохранении объявления: ' + (data.message || 'Неизвестная ошибка'));
                }).catch(() => {
                    alert('Ошибка при сохранении объявления.');
                });
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при отправке запроса.');
        });
    });

    document.getElementById('btn-back').addEventListener('click', () => {
        window.location.href = "{{ route('listings.index') }}";
    });
});
</script>
</body>
</html>
