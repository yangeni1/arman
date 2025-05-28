console.log('cart');
async function togglecart(productId, button) {
    fetch('check_auth.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.authenticated) {
                        // Если пользователь не авторизован, открываем попап авторизации
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

                    // Если пользователь авторизован, добавляем товар в корзину
                    const formData = new FormData();
                    formData.append('product_id', productId);

                    fetch('add_to_cart.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Показываем уведомление об успешном добавлении
                                const notification = document.createElement('div');
                                notification.className = 'success-message';
                                notification.textContent = data.message || 'Товар успешно добавлен в корзину';
                                document.body.appendChild(notification);

                                // Удаляем уведомление через 3 секунды
                                setTimeout(() => {
                                    notification.style.opacity = '0';
                                    setTimeout(() => notification.remove(), 500);
                                }, 3000);
                            } else {
                                // Показываем сообщение об ошибке
                                const errorNotification = document.createElement('div');
                                errorNotification.className = 'error-message';
                                errorNotification.textContent = data.message || 'Произошла ошибка при добавлении товара в корзину';
                                document.body.appendChild(errorNotification);

                                // Удаляем уведомление через 3 секунды
                                setTimeout(() => {
                                    errorNotification.style.opacity = '0';
                                    setTimeout(() => errorNotification.remove(), 500);
                                }, 3000);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            const errorNotification = document.createElement('div');
                            errorNotification.className = 'error-message';
                            errorNotification.textContent = 'Произошла ошибка при добавлении товара в корзину';
                            document.body.appendChild(errorNotification);

                            // Удаляем уведомление через 3 секунды
                            setTimeout(() => {
                                errorNotification.style.opacity = '0';
                                setTimeout(() => errorNotification.remove(), 500);
                            }, 3000);
                        });
                })
                .catch(error => {
                    console.error('Error:', error);
                    const errorNotification = document.createElement('div');
                    errorNotification.className = 'error-message';
                    errorNotification.textContent = 'Произошла ошибка при проверке авторизации';
                    document.body.appendChild(errorNotification);

                    // Удаляем уведомление через 3 секунды
                    setTimeout(() => {
                        errorNotification.style.opacity = '0';
                        setTimeout(() => errorNotification.remove(), 500);
                    }, 3000);
                });
};

function addToCart() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    console.log(addToCartButtons);
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = this.getAttribute('data-product-id');
            togglecart(productId, this);
        });
    });
};
addToCart();
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.addedNodes.length) {
            addToCart();
        }
    });
});

// Наблюдаем за изменениями в контейнерах с карточками
const containers = document.querySelectorAll('.card_product_catalog_products, .blina, .swiper-wrapper, .favorites_products');
containers.forEach(container => {
    observer.observe(container, { childList: true, subtree: true });
});