document.addEventListener('DOMContentLoaded', function() {
    // Загрузка выпадающих списков
    loadOptions('/api/get_statuses.php', 'status_id');
    loadOptions('/api/get_categories.php', 'category_id');
    loadOptions('/api/get_brands.php', 'brand_id');
    
    // Инициализация загрузки изображений
    initImageUploads();
    
    // Инициализация валидации формы
    initFormValidation();
});

// Функции для работы с выпадающими списками
async function loadOptions(url, selectId) {
    try {
        const select = document.getElementById(selectId);
        if (!select) return;

        select.disabled = true;
        select.innerHTML = '<option value="">Загрузка...</option>';

        const response = await fetch(url);
        if (!response.ok) throw new Error(`HTTP error: ${response.status}`);

        const data = await response.json();
        select.innerHTML = '<option value="">Выберите...</option>';
        data.forEach(item => {
            select.add(new Option(item.name, item.name));
        });
    } catch (error) {
        console.error(`Error loading ${url}:`, error);
        const select = document.getElementById(selectId);
        if (select) select.innerHTML = '<option value="">Ошибка загрузки</option>';
    } finally {
        const select = document.getElementById(selectId);
        if (select) select.disabled = false;
    }
}

// Функции для работы с изображениями
function initImageUploads() {
    const container = document.getElementById('imageUploads');
    if (!container) return;

    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-input')) {
            handleImageUpload(e.target);
        }
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-image-btn')) {
            const item = e.target.closest('.image-upload-item');
            const wasMain = item.querySelector('input[type="radio"]:checked');
            item.remove();
            
            if (wasMain) {
                const firstItem = container.querySelector('.image-upload-item');
                if (firstItem) {
                    firstItem.querySelector('input[type="radio"]').checked = true;
                    updateMainImageLabels();
                }
            } else {
                updateImageIndexes();
            }
        }
    });

    document.getElementById('addMoreImages')?.addEventListener('click', addImageUploadField);
}

function addImageUploadField() {
    const container = document.getElementById('imageUploads');
    const items = container.querySelectorAll('.image-upload-item');
    const newIndex = items.length;

    const newItem = document.createElement('div');
    newItem.className = 'image-upload-item';
    newItem.innerHTML = `
        <input type="file" name="images[]" accept="image/*" class="image-input">
        <img src="#" alt="Предпросмотр" class="image-preview" style="display: none;">
        <button type="button" class="remove-image-btn" style="display: none;">×</button>
        <div class="image-meta">
            <label>
                <input type="radio" name="main_image" value="${newIndex}"> Сделать главным
            </label>
        </div>
    `;
    container.appendChild(newItem);
}

function handleImageUpload(input) {
    const file = input.files[0];
    if (!file) return;

    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        showMessage('danger', 'Допустимы только JPG, PNG или GIF');
        input.value = '';
        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        showMessage('danger', 'Размер изображения не должен превышать 2MB');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const item = input.closest('.image-upload-item');
        item.querySelector('.image-preview').src = e.target.result;
        item.querySelector('.image-preview').style.display = 'block';
        item.querySelector('.remove-image-btn').style.display = 'inline-block';
    };
    reader.readAsDataURL(file);

    const allInputs = document.querySelectorAll('.image-input');
    if (input === allInputs[allInputs.length - 1]) {
        addImageUploadField();
    }
}

function updateImageIndexes() {
    const items = document.querySelectorAll('.image-upload-item');
    items.forEach((item, index) => {
        const radio = item.querySelector('input[type="radio"]');
        radio.value = index;
    });
}

function updateMainImageLabels() {
    const items = document.querySelectorAll('.image-upload-item');
    items.forEach((item, index) => {
        const label = item.querySelector('.image-meta label');
        if (index === 0) {
            label.innerHTML = '<input type="radio" name="main_image" value="0" checked> Главное изображение';
        } else {
            label.innerHTML = `<input type="radio" name="main_image" value="${index}"> Сделать главным`;
        }
    });
}

// Функции для работы с формой
function initFormValidation() {
    const form = document.getElementById('productForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const requiredFields = ['status', 'category', 'brand', 'name', 'price', 'quantity'];
        let isValid = true;

        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !field.value) {
                isValid = false;
                field.classList.add('invalid');
            } else if (field) {
                field.classList.remove('invalid');
            }
        });

        const hasImages = Array.from(document.querySelectorAll('.image-input'))
            .some(input => input.files.length > 0);
        
        if (!hasImages) {
            showMessage('danger', 'Необходимо загрузить хотя бы одно изображение');
            isValid = false;
        }

        if (isValid) {
            submitProductForm(form);
        } else {
            showMessage('danger', 'Пожалуйста, заполните все обязательные поля');
        }
    });
}

async function submitProductForm(form) {
    const responseElement = document.getElementById('responseMessage');
    responseElement.innerHTML = '<div class="alert alert-info">Отправка данных...</div>';

    try {
        // Убедимся, что выбрано главное изображение
        const mainImageSelected = document.querySelector('input[name="main_image"]:checked') !== null;
        if (!mainImageSelected) {
            const firstItem = document.querySelector('.image-upload-item');
            if (firstItem) {
                firstItem.querySelector('input[type="radio"]').checked = true;
            }
        }

        const formData = new FormData(form);
        
        console.log(formData)

        const response = await fetch('/add_product.php', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) throw new Error(`HTTP error! Status: ${response.data}`);

        const data = await response.json();
        if (data.success) {
            showMessage('success', `Товар успешно добавлен<br>ID товара: ${data.product_id}`, 8000);
            resetForm();
        } else {
            throw new Error(data.message || 'Unknown error');
        }
    } catch (error) {
        console.error('Ошибка отправки:', error);
        showMessage('danger', error.message || 'Ошибка при отправке формы');
    }
}

function resetForm() {
    const form = document.getElementById('productForm');
    form.reset();
    
    const container = document.getElementById('imageUploads');
    container.innerHTML = `
        <div class="image-upload-item">
            <input type="file" name="images[]" accept="image/*" class="image-input" required>
            <img src="#" alt="Предпросмотр" class="image-preview" style="display: none;">
            <button type="button" class="remove-image-btn" style="display: none;">×</button>
            <div class="image-meta">
                <label>
                    <input type="radio" name="main_image" value="0" checked> Главное изображение
                </label>
            </div>
        </div>
    `;
}

function showMessage(type, message, duration = 5000) {
    const responseElement = document.getElementById('responseMessage');
    if (!responseElement) return;
    
    responseElement.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    if (duration > 0) {
        setTimeout(() => responseElement.innerHTML = '', duration);
    }
}