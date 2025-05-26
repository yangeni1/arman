document.addEventListener('DOMContentLoaded', function() {
    // Функция для обновления количества товара
    async function updateQuantity(cartId, newQuantity) {
        try {
            const formData = new FormData();
            formData.append('cart_id', cartId);
            formData.append('quantity', newQuantity);

            const response = await fetch('update_cart.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error('Ошибка при обновлении корзины');
            
            const data = await response.json();
            if (data.success) {
                // Обновляем отображение цены
                const item = document.querySelector(`[data-cart-id="${cartId}"]`);
                if (item) {
                    const priceElement = item.querySelector('.price');
                    const totalElement = item.querySelector('.basket_item_total');
                    const price = parseFloat(priceElement.textContent.replace('₽', '').replace(',', ''));
                    const total = price * newQuantity;
                    totalElement.textContent = `₽${total.toFixed(2)}`;
                }
                updateTotal();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Функция для удаления товара из корзины
    async function removeFromCart(cartId) {
        try {
            const formData = new FormData();
            formData.append('cart_id', cartId);

            const response = await fetch('remove_from_cart.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error('Ошибка при удалении из корзины');
            
            const data = await response.json();
            if (data.success) {
                const item = document.querySelector(`[data-cart-id="${cartId}"]`);
                if (item) {
                    item.remove();
                    updateTotal();
                    
                    // Если корзина пуста, показываем сообщение
                    const remainingItems = document.querySelectorAll('.basket_item');
                    if (remainingItems.length === 0) {
                        const container = document.querySelector('.basket_items');
                        container.innerHTML = '<div class="basket_main_txt"><p>Ваша корзина пуста</p></div>';
                    }
                }
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Функция для обновления общей суммы
    function updateTotal() {
        const items = document.querySelectorAll('.basket_item');
        let total = 0;
        
        items.forEach(item => {
            const priceText = item.querySelector('.price').textContent;
            const quantity = parseInt(item.querySelector('input[type="number"]').value);
            const price = parseFloat(priceText.replace('₽', '').replace(',', ''));
            total += price * quantity;
        });

        const totalElement = document.querySelector('.basket_total p span');
        if (totalElement) {
            totalElement.textContent = `₽${total.toFixed(2)}`;
        }
    }

    // Обработчики для кнопок изменения количества
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const currentValue = parseInt(input.value);
            const cartId = this.closest('.basket_item').dataset.cartId;

            if (this.classList.contains('plus')) {
                input.value = currentValue + 1;
            } else if (this.classList.contains('minus') && currentValue > 1) {
                input.value = currentValue - 1;
            }

            updateQuantity(cartId, parseInt(input.value));
        });
    });

    // Обработчики для кнопок удаления
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const cartId = this.closest('.basket_item').dataset.cartId;
            removeFromCart(cartId);
        });
    });

    // Функция для добавления товара в корзину
    async function addToCart(productId) {
        try {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', 1);

            const response = await fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error('Ошибка при добавлении в корзину');
            
            const data = await response.json();
            if (data.success) {
                // Удаляем товар из избранного
                const favoriteResponse = await fetch('add_to_favorites.php', {
                    method: 'POST',
                    body: formData
                });

                if (favoriteResponse.ok) {
                    const favoriteData = await favoriteResponse.json();
                    if (favoriteData.success) {
                        // Если мы на странице избранного, удаляем карточку товара
                        const productCard = document.querySelector(`.product-card-catalog[data-product-id="${productId}"]`);
                        if (productCard) {
                            productCard.remove();
                            
                            // Если больше нет товаров в избранном, показываем сообщение
                            const remainingProducts = document.querySelectorAll('.product-card-catalog');
                            if (remainingProducts.length === 0) {
                                const container = document.querySelector('.favorites_products');
                                if (container) {
                                    container.innerHTML = '<p class="empty-favorites">У вас пока нет избранных товаров</p>';
                                }
                            }
                        }
                    }
                }

                // Показываем уведомление об успехе
                showNotification('Товар добавлен в корзину', 'success');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Ошибка при добавлении в корзину', 'error');
        }
    }

    // Обработчики для кнопок добавления в корзину
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            if (productId) {
                addToCart(productId);
            }
        });
    });

    // Функция для показа уведомлений
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Анимация появления
        setTimeout(() => {
            notification.style.opacity = '1';
        }, 10);
        
        // Автоматическое скрытие через 3 секунды
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
}); 