document.addEventListener('DOMContentLoaded', function() {
    // Добавляем HTML модального окна в body
    document.body.insertAdjacentHTML('beforeend', `
        <div class="product-modal" id="productModal">
            <div class="modal_tovar">
                <div class="product-modal-close">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18" stroke="#151515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 6L18 18" stroke="#151515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="product-modal-category"></p>
                <div class="main_modal_tovar">
                    <img class="product-modal-image" src="" alt="">
                    <div class="txt_modal_tovar">
                        <h2 class="product-modal-title"></h2>
                        <p class="product-modal-rating">
                        </p>
                        <span class="product-modal-description"></span>
                        <div class="price_modal_tovar">
                            <p class="product-modal-price"></p>
                        </div>
                        <div class="btn_modal_dd">
                            <button class="btn_tovar_modal product-modal-add-to-cart">
                                <img src="./media/modal/Vector (1).png" alt="">
                                <p>В корзину</p>
                            </button>
                            <button class="btn_favorites product-modal-favorite add-to-favorites-btn">
                                <img src="./media/modal/Vector (2).png" alt="">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="green_poloska"></div>
            </div>
        </div>
    `);

    const modal = document.getElementById('productModal');
    const closeBtn = modal.querySelector('.product-modal-close');

    // Функция для форматирования цены
    function formatPrice(price) {
        return price.toLocaleString('ru-RU', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

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

    // Функция для добавления товара в корзину
    async function addToCart(productId) {
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

            // Добавляем товар в корзину
            const formData = new FormData();
            formData.append('product_id', productId);

            const response = await fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error('Ошибка добавления в корзину');
            
            const data = await response.json();
            if (data.success) {
                modal.style.display = 'none';
                showNotification(data.message || 'Товар успешно добавлен в корзину');
            } else {
                throw new Error(data.message || 'Ошибка при добавлении товара в корзину');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(error.message, false);
        }
    }

    // Функция для открытия модального окна
    function openProductModal(productData) {
        const modalImage = modal.querySelector('.product-modal-image');
        const modalTitle = modal.querySelector('.product-modal-title');
        const modalCategory = modal.querySelector('.product-modal-category');
        const modalPrice = modal.querySelector('.product-modal-price');
        const modalDescription = modal.querySelector('.product-modal-description');
        const addToCartBtn = modal.querySelector('.product-modal-add-to-cart');
        const favoriteBtn = modal.querySelector('.product-modal-favorite');
        // Заполняем данные товара
        modalImage.src = productData.image;
        modalImage.alt = productData.name;
        modalTitle.textContent = productData.name;
        modalCategory.textContent = productData.category;
        modalDescription.textContent = productData.description || 'Описание отсутствует';
        modalPrice.textContent = `₽${formatPrice(parseFloat(productData.price))}`;

        // Устанавливаем ID товара для кнопки
        addToCartBtn.setAttribute('data-product-id', productData.id);
        favoriteBtn.setAttribute('data-product-id', productData.id);
        // Добавляем обработчик для кнопки "В корзину"
        addToCartBtn.onclick = async function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Анимация нажатия
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);

            const productId = this.getAttribute('data-product-id');
            if (!productId) {
                showNotification('Ошибка: ID товара не найден', false);
                return;
            }

            await addToCart(productId);
        };

        // Показываем модальное окно
        modal.style.display = 'flex';
    }

    // Закрытие модального окна
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Закрытие по клику вне модального окна
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Добавляем обработчики для карточек товаров
    function addProductCardListeners() {
        const productCards = document.querySelectorAll('.product-card, .product-card-catalog, .product-card-catalog-1');
        
        productCards.forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('.add-to-cart-btn')) {
                    e.preventDefault();
                    
                    const priceElement = this.querySelector('.price-values p');
                    const oldPriceElement = this.querySelector('.price-values span');
                    
                    const productData = {
                        id: this.querySelector('.add-to-cart-btn').getAttribute('data-product-id'),
                        image: this.querySelector('.img_product-card').src,
                        name: this.querySelector('p').textContent,
                        category: this.querySelector('span').textContent,
                        price: oldPriceElement ? oldPriceElement.textContent.replace('₽', '') : priceElement.textContent.replace('₽', ''),
                        description: this.getAttribute('data-description') || ''
                    };

                    openProductModal(productData);
                }
            });
        });
    }

    // Инициализация обработчиков
    addProductCardListeners();

    // Наблюдатель за динамически добавленными карточками
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                addProductCardListeners();
            }
        });
    });

    // Наблюдаем за изменениями в контейнерах с карточками
    const containers = document.querySelectorAll('.card_product_catalog_products, .blina, .swiper-wrapper');
    containers.forEach(container => {
        observer.observe(container, { childList: true, subtree: true });
    });
});
