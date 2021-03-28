<?php
	global $inc, $current, $refinfo, $cms_object, $root;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <? include($root . '/tmpls/head.inc'); ?>
</head>
<body>
<? include($root . '/tmpls/header.inc'); ?>

<div class="sev sec_offer" id="offer">
    <div class="banner">
        <div class="banner__img">
            <img src="/styles/img/offer_bg100.jpg" width="" height="600" alt="">
        </div>
        <div class="art container">
            <div class="columns is-variable is-7">
                <div class="column is-5-tablet is-7-desktop">
                    <div class="block banner__box">
                        <h1>Аренда компрессоров</h1>
                        <div class="desc">Наша компания занимается сдачей в аренду КОМПРЕССОРОВ и др. техники.</div>
                    </div>
                </div>
                <div class="column">
                    <div class="info" style="">
                        <div class="sec sec_hook" id="hook">
                            <div class="wrap">
                                <div class="sev sec_quiz" id="quizs">
                                    <div class="art container">
                                        <div class="head2 title">Подберите компрессор для своих задач:</div>

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
<div class=" sec_rent section" id="rent">
    <div class="art container">
        <div class="head2">Нужен компрессор в аренду?<br>
            большой парк надежных компрессоров компании Atlas Copco: Kaeser
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
                        Аренда компрессора <br> Kaeser M50
                    </div>
                    <div class="card-product__img">
                        <img src="/styles/img/m50.jpg"
                             alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Рабочее давление, бар:</b> 7</li>
                            <li><b>Производительность, м<sup>3</sup>/мин: </b>5</li>
                            <li><b>Цена:</b> от 3000 руб./сутки</li>
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
                        Аренда компрессора <br> Kaeser M100
                    </div>
                    <div class="card-product__img">
                        <img src="/styles/img/m100.jpg"
                             alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Рабочее давление, бар:</b> 7</li>
                            <li><b>Производительность, м<sup>3</sup>/мин:</b>10</li>
                            <li><b>Цена:</b> узнать по запросу</li>
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
                        <img src="../styles/img/xahs-186.jpg"
                             alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Рабочее давление, бар:</b> 12</li>
                            <li><b>Производительность, м<sup>3</sup>/мин: </b>10,4</li>
                            <li><b>Цена:</b> узнать по запросу</li>
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
                        <img src="../styles/img/xats-156.jpg"
                             alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Рабочее давление, бар:</b> 10,3</li>
                            <li><b>Производительность, м<sup>3</sup>/мин: </b>10</li>
                            <li><b>Цена:</b> узнать по запросу</li>
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
                        Пескоструйный Аппарат <br> Contracor DBS 200
                    </div>
                    <div class="card-product__img">
                        <img src="data/upload/equipments/list/CRKT6tfQNnM.jpg"
                             alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Длина шланга: </b> 60м</li>
                            <li><b>Диаметр сопла: </b>8мм</li>
                            <li><b>Цена:</b> узнать по запросу</li>
                        </ul>
                    </div>
                </div>
            </div>

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
<div class="sev sec_quiz section" id="quiz">
    <div class="art container">
        <div class="head2 title">Подберите компрессор для своих задач:</div>

        <div id="app">
            <div class="info" v-show="completed === false">
                <div class="percentage">
                    Пройдено: {{ percent }}
                </div>
                <div class="progressbar">
                    <div class="progressbar_body"
                         v-bind:style="{width: percentStyle}"></div>
                </div>
                <div class="question_number">
                    Вопрос {{ questionIndex+1 }} из {{ quiz.questions.length+1 }}
                </div>
            </div>
            <div v-for="(question, index) in quiz.questions">
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
                 v-show="questionIndex === quiz.questions.length && completed === false">
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
<?/*
    <div class="sev sec_compressors section" id="compressors">
<div class="art container">
    <div class="head2">Вам нужен компрессор?<br><span>Тогда вы правильно обратились!</span></div>
    <div class="columns items is-multiline">
        <div class="column">
            <div class="card-product">
                <div class="card-product__hover">
                    <a href="#compressors_form"
                       class="callback scroll">Связаться
                        с нами</a>
                </div>
                <div class="card-product__title">
                    Компрессор дизельный <br> малой мощности
                </div>
                <div class="card-product__img">
                    <img src="data/upload/equipments/list/kmm.png"
                         alt="Компрессор дизельный малой мощности 7-12 Бар/2-5,5 м3.мин">
                </div>
                <div class="card-product__desc">
                    <ul>
                        <li><b>Рабочее давление, бар:</b> 7-12</li>
                        <li><b>Производительность, м<sup>3</sup>/мин:</b> 2-5,5</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="card-product">
                <div class="card-product__hover">
                    <a href="#compressors_form"
                       class="callback scroll">Связаться
                        с нами</a>
                </div>
                <div class="card-product__title">
                    Компрессор дизельный <br> средней мощности
                </div>
                <div class="card-product__img">
                    <img src="data/upload/equipments/list/kmc.png"
                         alt="Компрессор дизельный средней мощности 7-14 Бар/7,5-12 м3.мин">
                </div>
                <div class="card-product__desc">
                    <ul>
                        <li><b>Рабочее давление, бар:</b> 7-14</li>
                        <li><b>Производительность, м<sup>3</sup>/мин:</b> 5-12</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="card-product">
                <div class="card-product__hover">
                    <a href="#compressors_form"
                       class="callback scroll">Связаться
                        с нами</a>
                </div>
                <div class="card-product__title">
                    Компрессор дизельный <br> высокой мощности
                </div>
                <div class="card-product__img">
                    <img src="data/upload/equipments/list/kmv.png"
                         alt="Компрессор дизельный средней мощности 8,6-35Бар/22-63 м3.мин">
                </div>
                <div class="card-product__desc">
                    <ul>
                        <li><b>Рабочее давление, бар:</b> 8,6-35</li>
                        <li><b>Производительность, м<sup>3</sup>/мин:</b> 22-63</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="sec_brand section">
    <div class="container">
        <div class="sec_brand__wrapper-box">
            <div class="columns">
                <div class="column is-4">
                    <div class="sec_brand__wrapper">
                        <div class="sec_brand__box">
                            <img src="data/upload/equipments/list/logo-1.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="column is-4">
                    <div class="sec_brand__wrapper">
                        <div class="sec_brand__box">
                            <img src="data/upload/equipments/list/logo-2.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="column is-4">
                    <div class="sec_brand__wrapper">
                        <div class="sec_brand__box">
                            <img src="data/upload/equipments/list/logo.svg" width="212" height="50" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sev sec_svyazatsa_form" id="compressors_form">
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
*/?>

<script>
    var quiz = {
        questions: [
            {
                text: "Какой привод компрессора?",
                responses: [
                    {text: 'Электрический'},
                    {text: 'Дизельный'},
                    {text: 'Не знаю'},
                ]
            }, {
                text: "Какой тип компрессора требуется?",
                responses: [
                    {text: 'Передвижной'},
                    {text: 'Станционарный'},
                    {text: 'Не знаю'},
                ]
            }, {
                text: "Необходимое рабочее давление?",
                responses: [
                    {text: 'От 1-7 Бар'},
                    {text: '7-14 Бар'},
                    {text: '14-35 Бар'},
                    {text: 'Не знаю'},
                ]
            }, {
                text: "Требуемая производительность компрессора?",
                responses: [
                    {text: 'До 5,5 м3 в минуту'},
                    {text: 'от 5,5 – 13 м3 в минуту'},
                    {text: '13- 65 м3 в минуту'},
                    {text: 'Не знаю'},
                ]
            }
        ]
    };

    new Vue({
        el: '#app',
        data: {
            quiz: quiz,
            questionIndex: 0,
            userResponses: Array(quiz.questions.length).fill(false),
            answers: [],
            completed: false
        },
        computed: {
            percent: function () {
                return ((this.questionIndex / this.quiz.questions.length) * 100) + '%'
            },
            percentStyle: function () {
                return ((this.questionIndex / this.quiz.questions.length) * 100 + 5) + '%'
            }
        },
        methods: {
            next: function (e) {
                var question = quiz.questions[this.questionIndex].text;
                var answer = e.target.labels[0].innerText;
                this.answers.push([question, answer]);
                this.questionIndex++;
            },
            submit: function () {
                this.completed = true;
                $('[name=answers]').val(JSON.stringify(this.answers));
            },
            restart: function () {
                this.answers = [];
                this.questionIndex = 0;
            }
        }
    });
</script>

<script>
    var quizs = {
        questions: [
            {
                text: "Какой привод компрессора?",
                responses: [
                    {text: 'Электрический'},
                    {text: 'Дизельный'},
                    {text: 'Не знаю'},
                ]
            }, {
                text: "Какой тип компрессора требуется?",
                responses: [
                    {text: 'Передвижной'},
                    {text: 'Станционарный'},
                    {text: 'Не знаю'},
                ]
            }, {
                text: "Необходимое рабочее давление?",
                responses: [
                    {text: 'От 1-7 Бар'},
                    {text: '7-14 Бар'},
                    {text: '14-35 Бар'},
                    {text: 'Не знаю'},
                ]
            }, {
                text: "Требуемая производительность компрессора?",
                responses: [
                    {text: 'До 5,5 м3 в минуту'},
                    {text: 'от 5,5 – 13 м3 в минуту'},
                    {text: '13- 65 м3 в минуту'},
                    {text: 'Не знаю'},
                ]
            }
        ]
    };

    new Vue({
        el: '#apps',
        data: {
            quizs: quizs,
            questionIndex: 0,
            userResponses: Array(quizs.questions.length).fill(false),
            answers: [],
            completed: false
        },
        computed: {
            percent: function () {
                return ((this.questionIndex / this.quizs.questions.length) * 100) + '%'
            },
            percentStyle: function () {
                return ((this.questionIndex / this.quizs.questions.length) * 100 + 5) + '%'
            }
        },
        methods: {
            next: function (e) {
                var question = quizs.questions[this.questionIndex].text;
                var answer = e.target.labels[0].innerText;
                this.answers.push([question, answer]);
                this.questionIndex++;
            },
            submit: function () {
                this.completed = true;
                $('[name=answers]').val(JSON.stringify(this.answers));
            },
            restart: function () {
                this.answers = [];
                this.questionIndex = 0;
            }
        }
    });
</script>

<?/*
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
*/?>

<?/*
<div class="sec sec_buyout_long section" id="buyout_long">
<div class="art container">
    <div class="items">
        <div class="item">
            <div class="header">
                <div class="head2">Как мы продаем компрессора:</div>
            </div>
            <div class="icons">
                <ul>
                    <li>
                        <div class="icon"><img src="/styles/img/buyout/buyout_2_img_01.png" alt="Звонок или заявка">
                        </div>
                        <div class="title">Звонок или заявка</div>
                    </li>
                    <li>
                        <div class="icon"><img src="/styles/img/buyout/buyout_1_img_04.png"
                                               alt="Оцениваем ваше оборудование"></div>
                        <div class="title">Уточняем параметры компрессора</div>
                    </li>
                    <li>
                        <div class="icon"><img src="/styles/img/buyout/buyout_2_img_02.png"
                                               alt="Подбираем подходящую модель"></div>
                        <div class="title">Подбираем подходящую модель</div>
                    </li>
                    <li>
                        <div class="icon"><img src="/styles/img/buyout/buyout_long_img_doc.png"
                                               alt="Оформляем документы"></div>
                        <div class="title">Оформляем документы</div>
                    </li>
                    <li>
                        <div class="icon"><img src="/styles/img/buyout/buyout_1_img_03.png" alt="Вы оплачиваете">
                        </div>
                        <div class="title">Вы оплачиваете</div>
                    </li>
                    <li>
                        <div class="icon"><img src="/styles/img/buyout/buyout_2_img_03.png"
                                               alt="Компрессор уезжает к ВАМ!!!"></div>
                        <div class="title">Компрессор уезжает к <strong style="color: white">вам!</strong></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
*/?>


<div class=" sec_rent section" style="display: none;" id="">
    <div class="art container">
        <div class="head2"><span>Ремонт и Сервисное обслуживание</span> дизельных компрессоров<br>
        </div>
        <div style="text-align: left; margin-bottom: 25px;">
            Выполняем ремонтные и сервисные работы, проводим плановое техобслуживание винтовых, поршневых компрессоров.
            <br>
            Наши преимущества:
            <ul style="    margin: 10px 0 20px 30px;list-style-type: disc;">
                <li>Среднее время ремонта 2 дня.</li>
                <li>Подменный компрессор на время ремонта</li>
                <li>Выезд инженера 24/7</li>
            </ul>
        </div>
        <div class="">
            <img src="./styles/img/L3NA84NEKP4.jpg" alt="">
        </div>
    </div>
</div>


<div style="display: none" class="sec sec_buyout section" id="buyout">
    <div class="art container">
        <div class="items columns is-multiline">
            <? foreach ($buyouts as $buyout): ?>
            <div class="item column is-full" id="<?= $buyout->id; ?>">
                <div class="buyout-item">
                    <div class="header">
                        <div class="head2"><?= $buyout->title; ?></div>
                        <div class="desc"><?= $buyout->desc; ?></div>
                    </div>
                    <div class="wrapper">
                        <div class="img"><img src="<?= $buyout->img; ?>" alt="<?= $buyout->title; ?>"></div>
                        <div class="icons">
                            <ul class="columns is-centered is-multiline is-mobile">
                                <? foreach ($buyout->items as $item): ?>
                                <li class="column is-3 is-6-mobile">
                                    <div class="buyout-wrapper">
                                        <div class="icon"><img src="<?= $item->icon; ?>" alt="<?= $item->title; ?>">
                                        </div>
                                        <div class="title"><?= $item->title; ?></div>
                                    </div>
                                </li>
                                <? endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <? endforeach; ?>
        </div>
        <div class="notice" style="display: none">
            Возможна продажа по программе по <span>Trade In</span>, в зачет возьмем Ваш старый компрессор!
        </div>
    </div>
</div>
<div class="sec sec_hook section" id="hook">
    <div class="art container">
        <div class="wrap">
            <div class="head2">Есть вопросы, <br>перезвоним!</div>
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

<div class="sec section special-equipment">
    <div class="art container">
        <div class="head2">Аренда кран борта, манипулятора</div>
        <div class="columns is-multiline">
            <div class="column is-4">
                <div class="card-product">
                    <div class="card-product__hover">
                        <a href="#special_form" class="callback scroll">Узнать
                            цену</a>
                    </div>
                    <div class="card-product__title">Кран борт <br> Daewoo Novus</div>
                    <div class="card-product__img">
                        <img src="/styles/img/444.jpg" alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Г/подъёмность : </b> 7 т</li>
                            <li><b>Кузов: </b>6,3м 8т</li>
                            <li><b>Цена:</b> узнать по запросу</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <div class="card-product">
                    <div class="card-product__hover">
                        <a href="#special_form" class="callback scroll">Узнать
                            цену</a>
                    </div>
                    <div class="card-product__title">Кран борт <br> Mazda</div>
                    <div class="card-product__img">
                        <img src="/styles/img/2647443_0.jpg" alt="">
                    </div>
                    <div class="card-product__desc">
                        <ul>
                            <li><b>Г/подъёмность : </b> 2 т</li>
                            <li><b>Кузов: </b>4,3м 3т</li>
                            <li><b>Цена:</b> узнать по запросу</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sev sec_svyazatsa_form" id="special_form">
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
                        <div class="email"><a href="<?= Landing::GetEmail($cms_object->options->contacts->email); ?>"
                                              target="_blank" class="clickgoal" data-goal="goal_email"><?= $cms_object->
                                options->contacts->email; ?></a></div>
                        <div class="socials">
                            <? if ($cms_object->options->contacts->whatsapp): ?>
                            <div class="item"><img src="/styles/img/socials/icon_whatsapp.png" alt="WhatsApp"><a
                                        href="<?= Landing::GetWhatsApp($cms_object->options->contacts->whatsapp); ?>"
                                        class="clickgoal" data-goal="goal_whatsapp">WhatsApp</a></div>
                            <? endif; ?>
                            <? if ($cms_object->options->contacts->viber): ?>
                            <div class="item"><img src="/styles/img/socials/icon_viber.png" alt="Viber"><a
                                        href="<?= Landing::GetViber($cms_object->options->contacts->viber); ?>"
                                        class="clickgoal" data-goal="goal_viber">Viber</a></div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <? Landing::ShowMap($cms_object->options->contacts->map); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<? include($root . '/tmpls/footer.inc'); ?>

<? include($root . '/tmpls/popups.inc'); ?>

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
<!-- Pixel -->
<script type="text/javascript">
    (function (d, w) {
        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () {
                n.parentNode.insertBefore(s, n);
            };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://qoopler.ru/index.php?ref=" + d.referrer + "&cookie=" + encodeURIComponent(document.cookie);

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else {
            f();
        }
    })(document, window);
</script>
<!-- /Pixel -->
<div class="marquiz__container">
    <a class="marquiz__button marquiz__button_blicked marquiz__button_fixed marquiz__button_fixed-right"
       href="#popup:marquiz_5d8dea7a9ffdf500442a7903" data-fixed-side="right"
       data-alpha-color="rgba(51, 154, 251, 0.5)" data-color="#339afb" data-text-color="#ffffff">Расчет стоимости</a>
</div>
<div style="max-height: 0; overflow: hidden;">
    <div style="min-height: 16px;margin-left:0px;white-space:pre-wrap;word-break:break-word;">
        <!-- Facebook Pixel Code --></div>
    <div style="min-height: 16px;margin-left:0px;white-space:pre-wrap;word-break:break-word;">
        <script>
        </script>
    </div>
    <div style="min-height: 16px;margin-left:0px;white-space:pre-wrap;word-break:break-word;">
        <noscript>
    </div>
    <div style="min-height: 16px;margin-left:0px;white-space:pre-wrap;word-break:break-word;"><img height="1" width="1"
    </div>
    <div style="min-height: 16px;margin-left:0px;white-space:pre-wrap;word-break:break-word;">
        src="https://www.facebook.com/tr?id=649910868887814&ev=PageView
    </div>
    <div style="min-height: 16px;margin-left:0px;white-space:pre-wrap;word-break:break-word;">&noscript=1"/></div>
    <div style="min-height: 16px;margin-left:0px;white-space:pre-wrap;word-break:break-word;"></noscript></div>
    <div style="min-height: 16px;margin-left:0px;white-space:pre-wrap;word-break:break-word;">
        <!-- End Facebook Pixel Code --></div>
</div>


<?= $this->include_js(); ?>

<? include($root . '/tmpls/counters_foo.inc'); ?>
</body>
</html>