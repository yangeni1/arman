document.addEventListener('DOMContentLoaded', () => {
    const openPopupButton = document.getElementById('openPopup'); // Кнопка для открытия модального окна
    const popup = document.querySelector('.popup');
    const closePopupButton = document.getElementById('closePopup');
    const popupLogButton = document.querySelector('.popup_log');
    const popupRegButton = document.querySelector('.popup_reg');
    const form = document.querySelector('form');
    const button = form.querySelector('.login-button');
    const regButton = form.querySelector('.reg-button'); // Кнопка регистрации
    const inputFieldsReg = document.querySelector('.input_fields_reg');
    const inputFieldsLog = document.querySelector('.input_fields_login');

    // Открытие модального окна
    openPopupButton.addEventListener('click', () => {
        popup.style.display = 'flex'; // Показать модальное окно
    });

    // Закрытие модального окна
    if (closePopupButton) {
        closePopupButton.addEventListener('click', () => {
            popup.style.display = 'none';
        });
    }

    window.addEventListener('click', (e) => {
        if (e.target === popup) {
            popup.style.display = 'none';
        }
    });

    // Переключение форм
    function toggleForm(log) {
        if (log) {
            popupLogButton.style.color = "#5E9F67";
            popupRegButton.style.color = "#253D4E";
            button.textContent = 'Войти';
            inputFieldsReg.style.display = 'none';
            inputFieldsLog.style.display = 'block';
            form.action = '/login';
        } else {
            popupRegButton.style.color = "#5E9F67";
            popupLogButton.style.color = "#253D4E";
            button.textContent = 'Зарегистрироваться';
            inputFieldsLog.style.display = 'none';
            inputFieldsReg.style.display = 'block';
            form.action = '/register';
        }
    }

    if (popupLogButton) {
        popupLogButton.addEventListener('click', () => {
            toggleForm(true);
        });
    }

    if (popupRegButton) {
        popupRegButton.addEventListener('click', () => {
            toggleForm(false);
        });
    }

    // Обработчик для кнопки "Зарегистрироваться"
    if (regButton) {
        regButton.addEventListener('click', (e) => {
            // Здесь можно добавить логику для проверки формы или других действий при нажатии на кнопку
            console.log('Кнопка регистрации была нажата');
        });
    }

    // Функция для переключения пароля
    const togglePasswordLogin = document.getElementById('togglePasswordLogin');
    const passwordFieldLogin = document.getElementById('password_login');

    const togglePasswordReg = document.getElementById('togglePasswordReg');
    const passwordFieldReg = document.getElementById('password_reg');

    const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
    const passwordFieldConfirmation = document.getElementById('password_confirmation');

    function togglePassword(field, button) {
        if (field.type === 'password') {
            field.type = 'text';
            button.querySelector('img').src = './media/popup/2.png';
        } else {
            field.type = 'password';
            button.querySelector('img').src = './media/popup/1.png';
        }
    }

    if (togglePasswordLogin && passwordFieldLogin) {
        togglePasswordLogin.addEventListener('click', (e) => {
            e.preventDefault();
            togglePassword(passwordFieldLogin, togglePasswordLogin);
        });
    }

    if (togglePasswordReg && passwordFieldReg) {
        togglePasswordReg.addEventListener('click', (e) => {
            e.preventDefault();
            togglePassword(passwordFieldReg, togglePasswordReg);
        });
    }

    if (togglePasswordConfirmation && passwordFieldConfirmation) {
        togglePasswordConfirmation.addEventListener('click', (e) => {
            e.preventDefault();
            togglePassword(passwordFieldConfirmation, togglePasswordConfirmation);
        });
    }
});



document.addEventListener('DOMContentLoaded', function () {
    // Очистка поля при клике на кнопку
    document.querySelector('.clear-button').addEventListener('click', function () {
        const input = document.getElementById('search-input');
        input.value = ''; // Очищаем текстовое поле
        input.focus();    // Возвращаем фокус в поле ввода
    });

    // Изменение иконки при фокусе на поле ввода
    const searchInput = document.getElementById('search-input');
    const searchIcon = document.querySelector('.search-button .search-icon');

    searchInput.addEventListener('focus', () => {
        if (searchIcon) {
            searchIcon.src = './media/header/иконка поиска при наведении.svg'; // Путь к новой иконке
        }
    });

    searchInput.addEventListener('blur', () => {
        if (searchIcon) {
            searchIcon.src = './media/header/иконка поиска.svg'; // Путь к стандартной иконке
        }
    });
});







document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchContainer = document.querySelector('.search');

    // Обработчик для фокуса на поле ввода
    searchInput.addEventListener('focus', function() {
        searchContainer.classList.add('search-focused');
    });

    // Обработчик для потери фокуса у поля ввода
    searchInput.addEventListener('blur', function() {
        searchContainer.classList.remove('search-focused');
    });
});




// Получаем элемент, который нужно сделать фиксированным
const header = document.querySelector('.main_head_fix');

// Флаг для отслеживания, был ли уже добавлен новый элемент
let elementCreated = false;
let citySelectorParent = null; // Родительский элемент для city_selector
let citySelectorSibling = null; // Соседний элемент для вставки

// Функция, которая добавляет или удаляет класс в зависимости от прокрутки и создает новый элемент при скролле вниз
function onScroll() {
    if (window.scrollY > 0) { // Если страница прокручена вниз хотя бы на 1 пиксель
        header.classList.add('fixed');

        // Создаем новый элемент <a> только при первом скролле вниз
        if (!elementCreated) {
            // Создаем новый элемент <a> и добавляем его в DOM
            const newLink = document.createElement('a');
            newLink.href = "./catalog.php"; // Устанавливаем ссылку
            newLink.classList.add('btn_catalog'); // Добавляем класс

            // Создаем вложенные элементы <img> и <p> для нового <a>
            const img = document.createElement('img');
            img.src = "./media/header/иконка каталога.svg";
            img.alt = ""; // Атрибут alt для изображения

            const p = document.createElement('p');
            p.textContent = "Каталог"; // Текст для элемента <p>

            // Добавляем <img> и <p> в <a>
            newLink.appendChild(img);
            newLink.appendChild(p);

            // Находим элементы, между которыми нужно вставить новый элемент
            const logoElement = document.querySelector('.img_logo');
            const searchElement = document.querySelector('.search');

            // Вставляем новый элемент перед блоком поиска
            if (logoElement && searchElement) {
                logoElement.parentNode.insertBefore(newLink, searchElement);
            }

            // Запоминаем родительский элемент и соседний элемент для city_selector, а затем удаляем его
            const citySelector = document.querySelector('.city_selector');
            if (citySelector) {
                citySelectorParent = citySelector.parentNode; // Сохраняем родительский элемент
                citySelectorSibling = citySelector.nextSibling; // Сохраняем соседний элемент
                citySelector.remove(); // Удаляем элемент из DOM
            }

            // Устанавливаем флаг, чтобы не создавать элемент повторно
            elementCreated = true;
        }
    } else {
        header.classList.remove('fixed');

        // Если страница прокручена вверх до самого верха, возвращаем элементы на место
        if (elementCreated) {
            // Удаляем созданный элемент <a>
            const newLink = document.querySelector('.btn_catalog');
            if (newLink) {
                newLink.remove();
            }

            // Восстанавливаем элемент <div> с классом 'city_selector'
            const citySelector = document.createElement('div');
            citySelector.classList.add('city_selector');

            const img = document.createElement('img');
            img.src = "./media/header/иконка местоположения.svg";
            img.alt = ""; // Атрибут alt для изображения

            const select = document.createElement('select');
            select.name = "";
            select.id = "";

            const option1 = document.createElement('option');
            option1.value = "kurgan";
            option1.textContent = "Курган";

            const option2 = document.createElement('option');
            option2.value = "tymen";
            option2.textContent = "Тюмень";

            select.appendChild(option1);
            select.appendChild(option2);

            citySelector.appendChild(img);
            citySelector.appendChild(select);

            // Вставляем обратно элемент в родительский элемент перед соседним элементом
            if (citySelectorParent) {
                citySelectorParent.insertBefore(citySelector, citySelectorSibling);
            }

            // Сбрасываем флаг
            elementCreated = false;
        }
    }
}

// Добавляем обработчик события при прокрутке
window.addEventListener('scroll', onScroll);

