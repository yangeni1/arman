document.addEventListener('DOMContentLoaded', function() {
    const checkoutBtn = document.getElementById('checkoutBtn');
    const basketItems = document.querySelector('.basket_items');
    const orderProcessing = document.querySelector('.order-processing');
    const processingIcon = document.querySelector('.processing-icon');
    const processingText = document.querySelector('.processing-text');
    const paymentInfo = document.querySelector('.payment-info');

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            // Скрываем корзину
            basketItems.classList.add('hidden');
            
            // Показываем анимацию обработки заказа
            orderProcessing.classList.add('active');
            processingText.classList.add('visible');
            paymentInfo.classList.add('visible');

            // Отправляем запрос на сервер для оформления заказа
            fetch('process_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'create_order'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Через 4 секунды показываем галочку
                    setTimeout(() => {
                        processingIcon.classList.add('success');
                        processingText.textContent = 'Заказ успешно оформлен!';
                        
                        // Через 2 секунды показываем сообщение о пустой корзине
                        setTimeout(() => {
                            orderProcessing.style.display = 'none';
                            const emptyBasketMessage = document.createElement('div');
                            emptyBasketMessage.className = 'basket_main_txt';
                            emptyBasketMessage.innerHTML = '<p>Ваша корзина пуста</p>';
                            document.querySelector('.basket_main').appendChild(emptyBasketMessage);

                            // Очищаем корзину в базе данных
                            fetch('clear_cart.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (!data.success) {
                                    console.error('Ошибка при очистке корзины:', data.error);
                                }
                            })
                            .catch(error => {
                                console.error('Ошибка при очистке корзины:', error);
                            });
                        }, 2000);
                    }, 4000);
                }
            })
            .catch(error => {
                console.error('Ошибка при оформлении заказа:', error);
            });
        });
    }
}); 