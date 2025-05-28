// document.addEventListener('DOMContentLoaded', function () {
//     const orderForm = document.querySelector('.order-form');
//     console.log(orderForm);
//     if (!orderForm) return;

//     const phoneInput = orderForm.querySelector('input[name="phone"]');
//     const submitButton = orderForm.querySelector('button[type="submit"]');

//     function validatePhone(phone) {
//         const phoneRegex = /^\+7\(\d{3}\) \d{3} \d{2}-\d{2}$/;
//         return phoneRegex.test(phone);
//     }

//     orderForm.addEventListener('submit', function (e) {
//         const phoneValue = phoneInput.value.trim();
//         if (!validatePhone(phoneValue)) {
//             e.preventDefault();
//             alert('Неверный формат номера телефона. Используйте +7(xxx) xxx xx-xx.');
//             phoneInput.focus();
//         }
//     });
// });
document.querySelector('#phone').onkeydown = function(e){
    inputphone(e,document.querySelector('#phone'))
    }
    function inputphone(e, phone){
    function stop(evt) {
        evt.preventDefault();
    }
    let key = e.key, v = phone.value; not = key.replace(/([0-9])/, 1)
    
    if(not == 1 || 'Backspace' === not){
    if('Backspace' != not){ 
        if(v.length < 3 || v ===''){phone.value= '+7('}
        if(v.length === 6){phone.value= v +') '}
        if(v.length === 11){phone.value= v +' '}
         if(v.length === 14){phone.value= v +'-'}
        }
    }else{stop(e)} 
}