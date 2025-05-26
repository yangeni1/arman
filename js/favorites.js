document.addEventListener('DOMContentLoaded', function() {
    // Функция для отображения уведомления
    function showNotification(message, isSuccess = true) {
        const notification = document.createElement('div');
        notification.className = isSuccess ? 'success-message' : 'error-message';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }, 3000);
    }

    // Функция для добавления/удаления из избранного
    async function toggleFavorite(productId, button) {
        try {
            // Проверяем авторизацию
            const authResponse = await fetch('check_auth.php');
            if (!authResponse.ok) throw new Error('Ошибка проверки авторизации');
            
            const authData = await authResponse.json();
            if (!authData.authenticated) {
                // Открываем попап авторизации
                const popup = document.querySelector('.popup');
                if (popup) {
                    popup.style.display = 'flex';
                    const popupLogButton = document.querySelector('.popup_log');
                    const popupRegButton = document.querySelector('.popup_reg');
                    const inputFieldsReg = document.querySelector('.input_fields_reg');
                    const inputFieldsLog = document.querySelector('.input_fields_login');
                    
                    popupLogButton.style.color = "#5E9F67";
                    popupRegButton.style.color = "#253D4E";
                    inputFieldsReg.style.display = 'none';
                    inputFieldsLog.style.display = 'block';
                }
                return;
            }

            // Добавляем/удаляем из избранного
            const formData = new FormData();
            formData.append('product_id', productId);

            const response = await fetch('add_to_favorites.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error('Ошибка работы с избранным');
            
            const data = await response.json();
            if (data.success) {
                // Анимация кнопки
                button.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    button.style.transform = 'scale(1)';
                }, 100);

                showNotification(data.message);
                
                // Если мы на странице избранного и товар был удален, обновляем страницу
                if (data.action === 'removed' && window.location.pathname.includes('favorites.php')) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } else {
                throw new Error(data.message || 'Ошибка при работе с избранным');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(error.message, false);
        }
    }

    // Добавляем обработчики для кнопок избранного
    function addFavoriteButtonsListeners() {
        const favoriteButtons = document.querySelectorAll('.add-to-favorites-btn');
        favoriteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = this.getAttribute('data-product-id');
                if (!productId) {
                    showNotification('Ошибка: ID товара не найден', false);
                    return;
                }

                toggleFavorite(productId, this);
            });
        });
    }

    // Инициализация обработчиков
    addFavoriteButtonsListeners();

    // Наблюдатель за динамически добавленными кнопками
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                addFavoriteButtonsListeners();
            }
        });
    });

    // Наблюдаем за изменениями в контейнерах с карточками
    const containers = document.querySelectorAll('.card_product_catalog_products, .blina, .swiper-wrapper, .favorites_products');
    containers.forEach(container => {
        observer.observe(container, { childList: true, subtree: true });
    });
}); 