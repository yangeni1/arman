document.addEventListener('DOMContentLoaded', function() {
    // Обработчик для кнопок добавления в корзину на карточках товаров
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            addToCart(productId);
        });
    });

    // Обработчик для кнопки добавления в корзину в модальном окне
    const modalAddToCartBtn = document.querySelector('.modal-add-to-cart-btn');
    if (modalAddToCartBtn) {
        modalAddToCartBtn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            addToCart(productId);
        });
    }
});

function addToCart(productId) {
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
        } else {
            if (data.message === 'Необходимо авторизоваться') {
                window.location.href = 'login.php';
            } else {
                alert(data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при добавлении товара в корзину');
    });
} 