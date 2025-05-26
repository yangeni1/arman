<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О нас - Арман</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .about_main {
            margin-top: 98px;
            padding: 40px 0;
            width: 100%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .about_content {
            background: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .about_content h2 {
            color: #253D4E;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        .about_content h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #5E9F67;
            border-radius: 2px;
        }

        .about_content p {
            color: #253D4E;
            font-family: "Quicksand", sans-serif;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .about_section {
            margin-bottom: 40px;
            padding: 20px;
            background: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .about_section:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .about_section:last-child {
            margin-bottom: 0;
        }

        .about_section h3 {
            color: #5E9F67;
            font-family: "Quicksand", sans-serif;
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 15px;
            position: relative;
            padding-left: 15px;
        }

        .about_section h3:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: #5E9F67;
            border-radius: 2px;
        }

        .values_list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .value_item {
            background: #F7F8FA;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #E8E8E8;
            position: relative;
            overflow: hidden;
        }

        .value_item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(94, 159, 103, 0.1);
            border-color: #5E9F67;
        }

        .value_item:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #5E9F67;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .value_item:hover:before {
            transform: scaleX(1);
        }

        .value_item h4 {
            color: #5E9F67;
            font-family: "Quicksand", sans-serif;
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .value_item p {
            color: #253D4E;
            font-size: 15px;
            margin: 0;
            line-height: 1.6;
        }

        .team_list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .team_member {
            text-align: center;
            background: #F7F8FA;
            padding: 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .team_member:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(94, 159, 103, 0.1);
        }

        .team_member img {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            margin-bottom: 20px;
            object-fit: cover;
            border: 4px solid #FFFFFF;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .team_member:hover img {
            transform: scale(1.05);
        }

        .team_member h4 {
            color: #253D4E;
            font-family: "Quicksand", sans-serif;
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 8px;
        }

        .team_member p {
            color: #5E9F67;
            font-size: 16px;
            margin: 0;
            font-weight: 500;
        }

        .about_section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .about_section ul li {
            position: relative;
            padding-left: 30px;
            margin-bottom: 15px;
            color: #253D4E;
            font-size: 16px;
            line-height: 1.6;
        }

        .about_section ul li:before {
            content: '✓';
            position: absolute;
            left: 0;
            top: 0;
            color: #5E9F67;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .about_main {
                padding: 20px;
                margin-top: 50px;
            }

            .about_content {
                padding: 20px;
            }

            .values_list,
            .team_list {
                grid-template-columns: 1fr;
            }

            .team_member img {
                width: 150px;
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <?php
        include 'db.php';
        include 'header.php';
    ?>
    <div class="container">
        <div class="container_main">
            <div class="about_main">
                <div class="about_content">
                    <h2>О компании Арман</h2>
                    
                    <div class="about_section">
                        <h3>Наша история</h3>
                        <p>Компания "Арман" была основана в 2010 году с целью предоставления качественных продуктов питания жителям Кургана и Тюмени. За годы работы мы выросли из небольшого магазина в сеть супермаркетов, сохраняя при этом индивидуальный подход к каждому клиенту.</p>
                    </div>

                    <div class="about_section">
                        <h3>Наши ценности</h3>
                        <div class="values_list">
                            <div class="value_item">
                                <h4>Качество</h4>
                                <p>Мы тщательно отбираем поставщиков и контролируем качество каждого продукта на наших полках.</p>
                            </div>
                            <div class="value_item">
                                <h4>Доступность</h4>
                                <p>Предлагаем широкий ассортимент товаров по доступным ценам для всех категорий покупателей.</p>
                            </div>
                            <div class="value_item">
                                <h4>Сервис</h4>
                                <p>Обеспечиваем высокий уровень обслуживания и комфортные условия для покупок.</p>
                            </div>
                        </div>
                    </div>

                    <div class="about_section">
                        <h3>Наша команда</h3>
                        <p>В компании "Арман" работают профессионалы своего дела, которые ежедневно стремятся сделать покупки наших клиентов приятными и удобными.</p>
                        <div class="team_list">
                            <div class="team_member">
                                <img src="images/team/ceo.jpg" alt="Генеральный директор">
                                <h4>Александр Петров</h4>
                                <p>Генеральный директор</p>
                            </div>
                            <div class="team_member">
                                <img src="images/team/manager.jpg" alt="Менеджер по закупкам">
                                <h4>Елена Смирнова</h4>
                                <p>Менеджер по закупкам</p>
                            </div>
                            <div class="team_member">
                                <img src="images/team/supervisor.jpg" alt="Супервайзер">
                                <h4>Дмитрий Иванов</h4>
                                <p>Супервайзер</p>
                            </div>
                        </div>
                    </div>

                    <div class="about_section">
                        <h3>Наши достижения</h3>
                        <ul>
                            <li>Более 10 лет успешной работы на рынке</li>
                            <li>Сеть из 2 современных супермаркетов</li>
                            <li>Более 1000 постоянных клиентов</li>
                            <li>Широкий ассортимент товаров</li>
                            <li>Собственная служба доставки</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html> 