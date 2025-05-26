document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('product-form');
    const productId = form.dataset.id;

    // === РАСЧЁТ ЦЕНЫ СО СКИДКОЙ В РЕАЛЬНОМ ВРЕМЕНИ ===
    const priceInput = document.querySelector('[name="price"]');
    const saleInput = document.querySelector('[name="sale"]');
    const discountedPriceOutput = document.getElementById('discounted-price');

    function updateDiscountedPrice() {
        const price = parseFloat(priceInput.value) || 0;
        const sale = parseFloat(saleInput.value) || 0;
        const finalPrice = price * (1 - sale / 100);
        discountedPriceOutput.textContent = finalPrice.toFixed(2) + ' ₽';
    }

    // Обновляем при вводе
    if (priceInput) priceInput.addEventListener('input', updateDiscountedPrice);
    if (saleInput) saleInput.addEventListener('input', updateDiscountedPrice);

    // Инициализация при загрузке
    updateDiscountedPrice();
    // === КОНЕЦ РАСЧЁТА ЦЕНЫ ===

    // Превью изображений
    document.querySelectorAll('.image-file').forEach(input => {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewContainer = document.createElement('div');
                    previewContainer.classList.add('image-preview');
                    previewContainer.innerHTML = `
                        <img src="${e.target.result}" alt="Предпросмотр">
                        <button class="remove-preview">Удалить</button>
                    `;
                    document.getElementById('image-preview-container').appendChild(previewContainer);

                    // Обработчик удаления превью
                    previewContainer.querySelector('.remove-preview').addEventListener('click', function () {
                        this.parentElement.remove();
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Применить одно поле
    document.querySelectorAll('.apply-field').forEach(btn => {
        btn.addEventListener('click', () => {
            const field = btn.dataset.field;
            const value = form.querySelector(`[name="${field}"]`).value;

            fetch('api/update_field_edit_product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ productId, field, value })
            }).then(res => res.json())
              .then(data => {
                  if (data.success) {
                      alert('Успешно!');

                      // Если это было поле "price" или "sale", обновляем цену со скидкой
                      if (field === 'price' || field === 'sale') {
                          updateDiscountedPrice();
                      }
                  } else {
                      alert('Ошибка при обновлении');
                  }
              });
        });
    });

    // Сохранить всё
    document.querySelector('.save-all').addEventListener('click', () => {
        const formData = new FormData(form);
        formData.append('productId', productId);

        fetch('api/save_all_edit_product.php', {
            method: 'POST',
            body: formData
        }).then(res => res.json())
          .then(data => alert(data.success ? 'Все данные сохранены' : 'Ошибка'));
    });

    // Добавление новых изображений
    document.getElementById('add-image').addEventListener('click', () => {
        const input = document.getElementById('new-image-file');
        const files = input.files;

        if (!files.length) return;

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        formData.append('productId', productId);

        fetch('api/upload_images_edit_product.php', {
            method: 'POST',
            body: formData
        }).then(res => res.json())
          .then(data => {
              alert(data.success ? 'Изображения загружены' : 'Ошибка');
              location.reload();
          });
    });

    // Удаление изображений
    document.querySelectorAll('.delete-image').forEach(btn => {
        btn.addEventListener('click', function () {
            const imageContainer = this.closest('[data-image-id]');
            const imageId = imageContainer.dataset.imageId;

            if (!confirm('Удалить изображение?')) return;

            fetch('api/delete_image_edit_product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ productId, imageId })
            }).then(res => res.json())
              .then(data => {
                  alert(data.success ? 'Изображение удалено' : 'Ошибка');
                  location.reload();
              });
        });
    });

    // Обновление существующего изображения
    document.querySelectorAll('.update-image').forEach(btn => {
        btn.addEventListener('click', function () {
            const imageContainer = this.closest('[data-image-id]');
            const imageId = imageContainer.dataset.imageId;
            const fileInput = imageContainer.querySelector('.image-file');
            const file = fileInput.files[0];

            if (!file) {
                alert('Выберите файл для обновления.');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);
            formData.append('imageId', imageId);

            fetch('api/update_image_edit_product.php', {
                method: 'POST',
                body: formData
            }).then(res => res.json())
              .then(data => {
                  alert(data.success ? 'Изображение обновлено' : 'Ошибка');
                  location.reload();
              });
        });
    });

    // Установка главного изображения
    document.querySelectorAll('.set-main-image').forEach(btn => {
        btn.addEventListener('click', function () {
            const imageContainer = this.closest('[data-image-id]');
            const imageId = imageContainer.dataset.imageId;

            fetch('api/set_main_image_edit_product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ productId, imageId })
            }).then(res => res.json())
              .then(data => {
                  alert(data.success ? 'Главное изображение установлено' : 'Ошибка');
                  location.reload();
              });
        });
    });
});