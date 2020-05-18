<?php
global $inc, $current, $refinfo, $cms_object, $root;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <!-- HEAD SECTION START -->
    <title>Продажа компрессоров</title>
    <meta name="description"
          content="Наш сервис по продаже компрессоров представлен широким ассортиментом винтовых, зубчатых, центробежных, поршневых, спиральных компрессоров и компрессоров с впрыском воды.">
    <meta name="keywords" content="">
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="x-rim-auto-match" content="none">

    <meta property="og:type" content="article">
    <meta property="og:site_name" content="Продажа компрессоров">
    <meta property="og:title" content="Продажа компрессоров">
    <meta property="og:description"
          content="Наш сервис по продаже компрессоров представлен широким ассортиментом винтовых, зубчатых, центробежных, поршневых, спиральных компрессоров и компрессоров с впрыском воды.">
    <meta property="og:url" content="/">

    <script type="text/javascript" src="/js/tools/fixes/console-fix.js"></script>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/js/tools/fixes/html5.js"></script>
    <script type="text/javascript" src="/js/tools/fixes/ie9.js"></script>
    <script type="text/javascript" src="/js/tools/fixes/css3-mediaqueries.js"></script>
    <![endif]-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
    <link href="/favicon.ico" rel="icon" type="image/x-icon">
    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">

    <link type="text/css" rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700|Open+Sans:300,400,600,700,800&display=swap">
    <link type="text/css" rel="stylesheet" href="/js/tools/colorbox/style/colorbox.css">
    <link type="text/css" rel="stylesheet" href="/js/tools/owl-carousel/owl.carousel.css">
    <link type="text/css" rel="stylesheet" href="/styles/style.css">
    <link type="text/css" rel="stylesheet" href="/local/templates/r-promstroy/assets/app.css">
    <script src="//code-ya.jivosite.com/widget/PuP6HpYMWx" async></script>
    <!-- /HEAD SECTION END -->
    <style>
        .sec_offer .banner__img:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, .4);
        }
    </style>
</head>
<body>
<header class="header">
    <div class="art container">
        <nav class="navbar is-spaced">
            <div class="navbar-brand">
                <a class="navbar-item header__logo" href="/">
                    <img src="/styles/img/logo.jpg" width="188" height="43">
                </a>
                <a role="button" class="navbar-burger burger" data-toggle="menu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="menu" class="navbar-menu">
                <ul class="navbar-start" style="max-width: 996px;">
                    <li class="navbar-item" style="float: none; margin:0;">
                        <a href="#rent" class="scroll">Аренда</a>
                    </li>
                    <li class="navbar-item" style="float: none; margin:0;">
                        <a href="#comments" class="scroll">Отзывы</a>
                    </li>
                    <li class="navbar-item" style="float: none; margin:0;">
                        <a href="#contacts" class="scroll">Контакты</a>
                    </li>
                    <li class="more-item navbar-item has-dropdown is-hoverable is-hidden"><a
                                href="javascript:void(false)">Еще</a>
                        <ul class="navbar-dropdown is-radiusless header-menu__third-level"></ul>
                    </li>
                </ul>
                <ul class="navbar-end contacts">
                    <li class="phone navbar-item" style="float: none; margin:0;">
                        <a href="tel:+79824508490" class="clickgoal" data-goal="goal_phone">
                            8 (982) 450 84 90
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<div class="sev sec_offer" id="offer">
    <div class="banner">
        <div class="banner__img">
            <img src="/styles/img/rent-bg.jpg" width="" height="600" alt="">
        </div>
        <div class="art container">
            <div class="columns is-variable is-7">
                <div class="column is-5-tablet is-7-desktop">
                    <div class="block banner__box">
                        <h1><?= $cms_object->offer->data->header; ?></h1>
                        <div class="desc">Наша компания занимается АРЕНДА дизельных КОМПРЕССОРОВ.</div>
                    </div>
                </div>
                <div class="column">
                    <div class="info" style="">
                        <div class="sec sec_hook" id="hook">
                            <div class="wrap">
                                <div class="sev sec_quiz sec_quiz_rent" id="quizs">
                                    <div class="art container">
                                        <div class="head2 title">Подберите компрессор для своего бизнеса у нас,<br> со
                                            <span>скидкой в 20%!</span></div>

                                        <div id="apps">
                                            <div class="info" v-show="completed === false">
                                                <div class="percentage">
                                                    Пройдено: {{ percent }}
                                                </div>
                                                <div class="progressbar">
                                                    <div class="progressbar_body"
                                                         v-bind:style="{width: percentStyle}"></div>
                                                </div>
                                                <div class="question_number">
                                                    Вопрос {{ questionIndex+1 }} из {{ quizs.questions.length+1 }}
                                                </div>
                                            </div>
                                            <div v-for="(question, index) in quizs.questions">
                                                <!-- Hide all questions, show only the one with index === to current question index -->
                                                <div v-show="index === questionIndex">
                                                    <h2>{{ question.text }}</h2>
                                                    <ol>
                                                        <li v-for="response in question.responses">
                                                            <label class="option">
                                                                <!-- The radio button has three new directives -->
                                                                <!-- v-bind:value sets "value" to "true" if the response is correct -->
                                                                <!-- v-bind:name sets "name" to question index to group answers by question -->
                                                                <!-- v-model creates binding with userResponses -->
                                                                <input type="radio"
                                                                       v-bind:name="index"
                                                                       v-model="userResponses[index]" v-on:click="next"
                                                                > {{response.text}}
                                                            </label>
                                                        </li>
                                                    </ol>
                                                </div>
                                            </div>
                                            <div class="result_controls"
                                                 v-show="questionIndex === quizs.questions.length && completed === false">
                                                <button v-on:click="submit">
                                                    Подтвердить
                                                </button>
                                                <button v-on:click="restart">
                                                    Ответить заново
                                                </button>
                                            </div>

                                            <div class="form-quiz" v-show="completed === true">
                                                <div class="head3">Спасибо за ответы, куда выслать информацию?</div>
                                                <form action="<?= $inc->send; ?>" method="post">
                                                    <input type="hidden" name="goalname" value="Информация">
                                                    <input type="hidden" name="goal" value="goal_info">
                                                    <input type="hidden" name="good" value="">
                                                    <div class="items">
                                                        <input type="hidden" name="answers" value="">
                                                        <div class="item"><input type="email" name="email"
                                                                                 placeholder="Электронная почта"></div>
                                                        <div class="item"><input type="text" name="phone"
                                                                                 placeholder="+7 (____) - ___ - ___ - _">
                                                        </div>
                                                        <div class="item">
                                                            <button type="submit"><span>Отправить письмо</span></button>
                                                        </div>
                                                    </div>
                                                    <label><input type="checkbox" name="politic" value="1"
                                                                  data-focus="icon" checked><span
                                                                class="icon"></span><span
                                                                class="label">Я даю согласие на обработку персональных данных</span></label>
                                                </form>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <div class="form-desktop">
                                    <p style="color: black; text-align: center;">Пишите! Ответим на все Ваши
                                        вопросы!</p>
                                    <form action="<?= $inc->send; ?>" method="post">
                                        <input type="hidden" name="goalname" value="Информация">
                                        <input type="hidden" name="goal" value="goal_info">
                                        <input type="hidden" name="good" value="">
                                        <div class="items" style="display: flex; flex-direction: column">
                                            <input type="hidden" name="answers" value="">
                                            <div class="item" style="width: 100%; max-width:none;"><input type="email"
                                                                                                          name="email"
                                                                                                          placeholder="Электронная почта">
                                            </div>
                                            <div class="item" style="width: 100%; max-width:none;"><input type="text"
                                                                                                          name="phone"
                                                                                                          placeholder="+7 (____) - ___ - ___ - _">
                                            </div>
                                            <div class="item" style="width: 100%; max-width:none;">
                                                <button type="submit"><span>Отправить письмо</span></button>
                                            </div>
                                        </div>
                                        <label><input type="checkbox" name="politic" value="1" data-focus="icon"
                                                      checked><span class="icon"></span><span class="label">Я даю согласие на обработку персональных данных</span></label>
                                    </form>
                                    <div class="rem" style="margin-top: 15px; text-align: center;">Перезвоним вам в
                                        течение
                                        20 минут
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button_wrap" style="display: none;">
                            <a href="#hook" class="button scroll gotoform" data-goal="goal_call"
                               data-goalname="Связаться с нами"
                               data-class-head2="Безмасляные компрессоры для любой сферы применения" data-name-good="">
                                <span>Связаться с нами</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<? /*
		<div class="sec sec_equipment" id="equipment">
<div class="art container">
    <div class="head2">Оборудование <span>PromStroy</span></div>
    <div class="items">
        <? foreach ($cms_object->equipments->list as $equipment): ?>
        <? if (empty($equipment->active)) continue; ?>
        <div class="item">
            <div class="title"><?= $equipment->title; ?></div>
            <div class="img"><img src="<?= $equipment->img->thumb; ?>" alt="<?= strip_tags($equipment->title); ?>">
            </div>
            <div class="manufacturer">
                <ul>
                    <? foreach ($equipment->manufact as $item): ?>
                    <li><b>-</b> <?= $item; ?></li>
                    <? endforeach; ?>
                </ul>
            </div>
            <div class="button_wrap"><a href="#hook" class="button scroll gotoform" data-goal="goal_equipment"
                                        data-goalname="Подобрать модель" data-class-head2="Подобрать модель"
                                        data-name-good="<?= strip_tags($equipment->title); ?>"><span>Подобрать модель</span></a>
            </div>
        </div>
        <? endforeach; ?>
    </div>
</div>
</div>
*/ ?>





<?
$buyouts = array(
    (object)array(
        'title' => 'Ремонт и Сервисное обслуживание <br> дизельных компрессоров',
        'id' => 'service',
        'desc' => '<b>Выполняем ремонтные и сервисные работы</b>, проводим плановое техобслуживание винтовых, поршневых
компрессоров. ',
        'img' => '/styles/img/L3NA84NEKP4.jpg',
        'items' => array(
            (object)array(
                'title' => 'Среднее время <br> ремонта 2 дня.',
                'icon' => '/styles/img/buyout/icons8.png',
            ),
            (object)array(
                'title' => 'Подменный компрессор <br> на время ремонта',
                'icon' => '/styles/img/buyout/icons71.png',
            ),
            (object)array(
                'title' => 'Выезд инженера 24/7',
                'icon' => '/styles/img/buyout/buyout_2_img_03.png',
            ),
        ),
    ),
);
?>


<div class=" sec_rent section" id="rent">
    <div class="art container">
        <div class="head2">Вам нужен компрессор в аренду?<br>
            <span>Тогда вы по адресу,</span>
            большой парк надежных компрессоров компании Atlas Copco:
        </div>
        <div style="text-align: left; margin-bottom: 25px;">
            (аналоги Айрман,Кайзер, ЧКЗ КВ, ЗИФ Ремеза и т.д.) малой и средней мощности (7-12 бар, 5-10м3/мин). <br>
            Мы надежный поставщик арендных компрессоров на рынке Пермского края, у нас своя сервисная служба, которая
            ликвидирует остановку компрессора в кротчайшие сроки или разъяснит Вашим сотрудникам необходимые вопросы.
        </div>
        <div class="columns is-multiline">
            <div class="column is-4">
                <div class="card-product">
                    <div class="card-product__hover">
                        <a href="#rent_form"
                           class="callback scroll">Узнать
                            цену</a>
                    </div>
                    <div class="card-product__title">
                        Аренда компрессора <br> Atlas Copco XAS 97
                    </div>
                    <div class="card-product__img">
                        <img src="data/upload/equipments/list/cq5dam.web.1600.jpg"
                             alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Рабочее давление, бар:</b> 7</li>
                            <li><b>Производительность, м<sup>3</sup>/мин: </b>5,3</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <div class="card-product">
                    <div class="card-product__hover">
                        <a href="#rent_form"
                           class="callback scroll">Узнать
                            цену</a>
                    </div>
                    <div class="card-product__title">
                        Аренда компрессора <br> Atlas Copco XAS 186
                    </div>
                    <div class="card-product__img">
                        <img src="data/upload/equipments/list/cq5dam.web.jpg"
                             alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Рабочее давление, бар:</b> 7</li>
                            <li><b>Производительность, м<sup>3</sup>/мин:</b> 5,3</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <div class="card-product">
                    <div class="card-product__hover">
                        <a href="#rent_form"
                           class="callback scroll">Узнать
                            цену</a>
                    </div>
                    <div class="card-product__title">
                        Аренда компрессора <br> Atlas Copco XAHS 186
                    </div>
                    <div class="card-product__img">
                        <img src="data/upload/equipments/list/cq5dam.web.jpg"
                             alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Рабочее давление, бар:</b> 12</li>
                            <li><b>Производительность, м<sup>3</sup>/мин: </b>10,4</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <div class="card-product">
                    <div class="card-product__hover">
                        <a href="#rent_form"
                           class="callback scroll">Узнать
                            цену</a>
                    </div>
                    <div class="card-product__title">
                        Аренда компрессора <br> Atlas Copco XATS 156
                    </div>
                    <div class="card-product__img">
                        <img src="data/upload/equipments/list/cq5dam.web.jpg"
                             alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Рабочее давление, бар:</b> 10,3</li>
                            <li><b>Производительность, м<sup>3</sup>/мин: </b>10</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <div class="card-product">
                    <div class="card-product__hover">
                        <a href="#rent_form"
                           class="callback scroll">Узнать
                            цену</a>
                    </div>
                    <div class="card-product__title">
                        Пескоструйный Аппарат <br> Contracor DBS-200
                    </div>
                    <div class="card-product__img">
                        <img src="data/upload/equipments/list/CRKT6tfQNnM.jpg"
                             alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Длина шланга: </b> 60м</li>
                            <li><b>Диаметр сопла: </b>8мм</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <div class="card-product">
                    <div class="card-product__hover">
                        <a href="#rent_form"
                           class="callback scroll">Узнать
                            цену</a>
                    </div>
                    <div class="card-product__title">Аренда покрасочного <br> аппарата Graco Xtreme X70
                    </div>
                    <div class="card-product__img">
                        <img src="data/upload/equipments/list/135.jpg"
                             alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Длина шланга: </b> 60м</li>
                            <li><b>Диаметр сопла: </b>8мм</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sec sec_hook section" id="hook">
    <div class="art sec_hook-rent container">
        <div class="wrap">
            <div class="head2">Есть вопросы, <br>мы вам перезвоним!</div>
            <form action="<?= $inc->send; ?>" method="post">
                <input type="hidden" name="goalname" value="Есть вопросы">
                <input type="hidden" name="goal" value="goal_questions">
                <input type="hidden" name="good" value="">
                <div class="items columns">
                    <div class="item column"><input type="text" name="name" placeholder="Ваше имя"></div>
                    <div class="item column"><input type="text" name="phone" placeholder="+7 (____) - ___ - ___ - _">
                    </div>
                </div>
                <div class="columns items">
                    <div class="item column"><input type="text" name="email" placeholder="E-mail"></div>
                    <div class="item column">
                        <button type="submit"><span>Отправить письмо</span></button>
                    </div>
                </div>
                <label>
                    <input type="checkbox" name="politic" value="1" data-focus="icon" checked>
                    <span class="icon"></span><span class="label">Я даю согласие на обработку персональных данных</span>
                </label>
            </form>
        </div>
    </div>
</div>

<div class="sev sec_svyazatsa_form" id="rent_form">
    <div class="art container">
        <div class="wrap">
            <div class="head2">Связаться с нами</div>
            <form action="<?= $inc->send; ?>" method="post">
                <input type="hidden" name="goalname" value="Связаться с нами">
                <input type="hidden" name="goal" value="goal_contact">
                <input type="hidden" name="choice" value="">
                <div class="items">
                    <div class="item"><input type="text" name="name" placeholder="Ваше имя"></div>
                    <div class="item"><input type="text" name="phone" placeholder="+7 (____) - ___ - ___ - _"></div>
                    <div class="item">
                        <button type="submit"><span>Отправить письмо</span></button>
                    </div>
                </div>
                <label><input type="checkbox" name="politic" value="1" data-focus="icon" checked><span
                            class="icon"></span><span
                            class="label">Я даю согласие на обработку персональных данных</span></label>
            </form>
        </div>
    </div>
</div>


<div class="sec sec_comments section" id="comments">
    <div class="art container">
        <div class="head2">Отзывы наших клиентов</div>
        <div class="desc">Сотрудничайте и убедитесь сами</div>
        <div class="slider">
            <ul data-center="1">
                <li>
                    <div class="header">
                        <div class="photo"><img
                                    src="/styles/img/comments_def_photo.jpg"
                                    alt=""></div>
                        <div class="title">
                            <div class="name">Вепрев Ю.А.</div>
                            <div class="company">Директор ООО «Мастер»</div>
                        </div>
                    </div>
                    <div class="text">
                        <div class="content">
                            <a href="/styles/img/OOO-Master.jpg" target="_blank">
                                <img src="/styles/img/OOO-Master.jpg" alt="">
                            </a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="header">
                        <div class="photo">
                            <img src="/styles/img/comments_def_photo.jpg" alt="">
                        </div>
                        <div class="title">
                            <div class="name">Красильников Н.А.</div>
                            <div class="company">Директор ООО «ПЗМИ»</div>
                        </div>
                    </div>
                    <div class="text">
                        <div class="content">
                            <a href="/styles/img/PZMI.jpg" target="_blank">
                                <img src="/styles/img/PZMI.jpg" alt="">
                            </a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="header">
                        <div class="photo"><img
                                    src="/styles/img/comments_def_photo.jpg"
                                    alt=""></div>
                        <div class="title">
                            <div class="name">Буторин А.Б.</div>
                            <div class="company">Директор ООО «ГАВ Пермь»</div>
                        </div>
                    </div>
                    <div class="text">
                        <div class="content">
                            <a href="/styles/img/GAV-Perm.jpg" target="_blank">
                                <img src="/styles/img/GAV-Perm.jpg" alt="">
                            </a>
                        </div>
                </li>
                <li>
                    <div class="header">
                        <div class="photo"><img
                                    src="/styles/img/comments_def_photo.jpg"
                                    alt=""></div>
                        <div class="title">
                            <div class="name">Фистин К.А.</div>
                            <div class="company">Директор ООО «ВИКОС»</div>
                        </div>
                    </div>
                    <div class="text">
                        <div class="content">
                            <a href="/styles/img/Vikos.jpg" target="_blank">
                                <img src="/styles/img/Vikos.jpg" alt="">
                            </a>
                        </div>
                </li>
            </ul>
            <div class="navigation">
                <a href="#prev" class="nav prev">prev</a>
                <span class="dots">
                    <span class="dot">1</span>
                    <span class="dot">2</span>
                    <span class="dot">3</span>
                    <span class="dot">4</span>
				</span>
                <a href="#next" class="nav next">next</a>
            </div>
        </div>
    </div>
</div>

<div class="sec sec_contacts section" id="contacts">
    <div class="bg">
        <div class="art art1 container">
            <div class="head2">Контактная информация</div>
            <div class="desc">Как вы можете связаться с нами</div>
        </div>
        <div class="art art2 container">
            <div class="columns">
                <div class="column is-5">
                    <div class="contacts">
                        <div class="address">
                            <span>г. Пермь, ул. 1-я Красноармейская 5, оф № 7</span>
                        </div>
                        <div class="phones">
                            <div class="item">
                                <a href="tel:+79824508490" class="clickgoal" data-goal="goal_phone">
                                    8 (982) 450 84 90
                                </a>
                            </div>
                        </div>
                        <div class="phones">
                            <div class="item">
                                <a href="tel:+73422021715" class="clickgoal" data-goal="goal_phone">+7 (342)
                                    202-17-15</a>
                            </div>
                        </div>
                        <div class="email"><a href="mailto:promstroy59@bk.ru"
                                              target="_blank" class="clickgoal"
                                              data-goal="goal_email">promstroy59@bk.ru</a></div>
                        <div class="socials">
                            <div class="item"><img src="/styles/img/socials/icon_whatsapp.png" alt="WhatsApp"><a
                                        href="whatsapp://send?phone=+79082500119"
                                        class="clickgoal" data-goal="goal_whatsapp">WhatsApp</a></div>
                            <div class="item"><img src="/styles/img/socials/icon_viber.png" alt="Viber"><a
                                        href="viber://add?number=79082500119"
                                        class="clickgoal" data-goal="goal_viber">Viber</a></div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="ymap" data-coord-lat="58.003113836779114" data-coord-lon="56.265097340791336"
                         data-zoom="17" data-dot-type="islands#dotIcon" data-dot-color="#0077e7">
                        <span>Загрузка карты...</span>
                        <div style="display:none;" class="balloon"><img src="/styles/img/logo.png" alt=""><br>Пермский
                            край, г. Пермь, <br>ул. 1-я Красноармейская 5, <br>оф № 7
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Marquiz script start -->
<script src="//script.marquiz.ru/v1.js" type="application/javascript"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    Marquiz.init({
      id: '5d8dea7a9ffdf500442a7903',
      autoOpen: 10,
      autoOpenFreq: 'once',
      openOnExit: false
    });
  });
</script>
<!-- Marquiz script end -->
<div class="marquiz__container">
    <a class="marquiz__button marquiz__button_blicked marquiz__button_fixed marquiz__button_fixed-right"
       href="#popup:marquiz_5d8dea7a9ffdf500442a7903" data-fixed-side="right"
       data-alpha-color="rgba(51, 154, 251, 0.5)" data-color="#339afb" data-text-color="#ffffff">Расчет стоимости</a>
</div>
<script type="text/javascript" src="/js/tools/jquery.min.js"></script>
<script type="text/javascript" src="/js/tools/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="/js/tools/owl-carousel/owl.carousel.min.js"></script>
<script type="text/javascript" src="//api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
<script type="text/javascript" src="/js/tools/core/core.js"></script>
<script type="text/javascript" src="/js/scripts.js"></script>
<script type="text/javascript" src="/local/templates/r-promstroy/assets/app.js"></script>
<script>
  var rentLink = document.querySelectorAll('a[href="#rent_form"]');
  var rentForm = document.querySelector('#rent_form');
  for (var item of rentLink) {
    item.addEventListener('click', () => {
//      console.log(rentForm);
      rentForm.classList.add('is-active');
    });
  }
</script>
</body>
</html>