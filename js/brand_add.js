// аджакс запрос для добавления бренда
document.getElementById('brandForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const responseMessage = document.getElementById('brandResponseMessage');
    
    fetch('add_brand.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            responseMessage.innerHTML = `Бренд добавлен! ID: ${data.id}`;
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