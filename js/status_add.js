// аджакс запрос для добавления статуса
document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const responseMessage = document.getElementById('statusResponseMessage');
    
    fetch('add_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            responseMessage.innerHTML = `Статус добавлен! ID: ${data.id}`;
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