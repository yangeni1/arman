// аджакс запрос для добавления категорий
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const responseMessage = document.getElementById('categoryResponseMessage');
    
    fetch('add_category.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            responseMessage.innerHTML = `Категория добавлена! ID: ${data.id}`;
            responseMessage.style.color = 'green';
            this.reset();
        } else {
            throw new Error(data.message || 'Ошибка сервера');
        }
    })
    .catch(error => {
        responseMessage.innerHTML = `Ошибка: ${error.message}`;
        responseMessage.style.color = 'red';
    });
});