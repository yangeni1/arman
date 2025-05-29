document.addEventListener('DOMContentLoaded', function () {
    let productIdToDelete = null;

    // Функция загрузки товаров через AJAX
    function loadProducts() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'api/product_list_load_ajax.php', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        renderProducts(data.products);
                    } else {
                        document.querySelector('.products-grid').innerHTML = `<p>${data.message}</p>`;
                    }
                } catch (e) {
                    console.error('Ошибка парсинга ответа:', xhr.responseText);
                    document.querySelector('.products-grid').innerHTML = '<p>Ошибка загрузки данных</p>';
                }
            } else {
                console.error('Ошибка сервера:', xhr.status);
                document.querySelector('.products-grid').innerHTML = '<p>Ошибка загрузки товаров</p>';
            }
        };

        xhr.onerror = function () {
            console.error('AJAX ошибка');
            document.querySelector('.products-grid').innerHTML = '<p>Не удалось загрузить товары</p>';
        };

        xhr.send();
    }

    // Функция отрисовки товаров
    function renderProducts(products) {
        if (!products.length) {
            document.querySelector('.products-grid').innerHTML = '<p>Товары не найдены</p>';
            return;
        }

        let html = '';
        products.forEach(product => {
            html += `
                <div class="product-card" data-id="${product.id}">
                    <div class="product-image-container">
                        <img src="${product.image}" 
                             class="product-image"
                             alt="${product.name}"
                             onerror="this.src='/uploads/products/default-product.jpg'; this.onerror=null;">
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">${product.name}</h3>
                        <div class="product-meta">
                            <span class="product-category">${product.category}</span>
                            <span class="product-brand">${product.brand}</span>
                        </div>
                        <div class="product-price">${parseFloat(product.price).toFixed(2)} ₽</div>
                    </div>
                    <div class="actions">
                        <a href="/edit_product.php?id=${product.id}" class="btn btn-edit">Редактировать</a>
                        <button class="btn btn-delete delete-product" data-id="${product.id}">Удалить</button>
                    </div>
                </div>
            `;
        });

        document.querySelector('.products-grid').innerHTML = html;
    }

    // Обработчик удаления товара
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('delete-product')) {
            productIdToDelete = event.target.dataset.id;
            document.getElementById('confirmModal').style.display = 'block';
        }
    });

    // Отмена удаления
    document.getElementById('cancelDelete').addEventListener('click', function () {
        document.getElementById('confirmModal').style.display = 'none';
        productIdToDelete = null;
    });

    // Подтверждение удаления
    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (!productIdToDelete) return;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'api/delete_product_list_ajax.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        loadProducts(); // Обновляем список
                        document.getElementById('confirmModal').style.display = 'none';
                    } else {
                        alert("Ошибка удаления: " + data.message);
                    }
                } catch (e) {
                    console.error('Ошибка парсинга ответа:', xhr.responseText);
                    alert("Произошла ошибка");
                }
            } else {
                console.error('Ошибка сервера:', xhr.status);
                alert("Не удалось удалить товар");
            }
        };

        xhr.onerror = function () {
            console.error('AJAX ошибка');
            alert("Не удалось удалить товар");
        };

        xhr.send(`id=${productIdToDelete}`);
    });

    // Загружаем товары при первом открытии
    loadProducts();

    // Для корректной работы при возврате через кнопку "Назад" или кэшировании страницы
    window.onpageshow = function (event) {
        if (event.persisted) {
            loadProducts();
        }
    };
});