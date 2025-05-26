function initMap() {
    // Координаты магазинов
    const kurgan = { lat: 55.4687, lng: 65.3412 }; // Курган, ул. Пушкина, д. 167
    const tyumen = { lat: 57.1522, lng: 65.5278 }; // Тюмень, ул. Барабинская, д. 3, корп. 17

    // Создаем карту
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 6,
        center: { lat: 56.3105, lng: 65.4345 }, // Центр между Курганом и Тюменью
    });

    // Добавляем метки
    const kurganMarker = new google.maps.Marker({
        position: kurgan,
        map: map,
        title: "Магазин в Кургане",
        label: "Курган"
    });

    const tyumenMarker = new google.maps.Marker({
        position: tyumen,
        map: map,
        title: "Магазин в Тюмени",
        label: "Тюмень"
    });

    // Добавляем информационные окна
    const kurganInfo = new google.maps.InfoWindow({
        content: "г. Курган, ул. Пушкина, д. 167"
    });

    const tyumenInfo = new google.maps.InfoWindow({
        content: "г. Тюмень, ул. Барабинская, д. 3, корп. 17"
    });

    // Добавляем обработчики кликов по меткам
    kurganMarker.addListener("click", () => {
        kurganInfo.open(map, kurganMarker);
    });

    tyumenMarker.addListener("click", () => {
        tyumenInfo.open(map, tyumenMarker);
    });
} 