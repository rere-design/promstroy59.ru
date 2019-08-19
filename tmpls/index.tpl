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
				<div class="banner__img" >
					<img src="/styles/img/offer_bg5.jpg" width="" height="600" alt="">
				</div>
				<div class="art container">
					<div class="columns is-variable is-7">
						<div class="column is-5-tablet is-7-desktop">
							<div class="block banner__box">
								<h1><?= $cms_object->offer->data->header; ?></h1>
								<div class="desc"><?= $cms_object->offer->data->desc; ?></div>
							</div>
						</div>
						<div class="column">
							<div class="info" style="">
								<p>
									Наша компания занимается ПРОДАЖЕЙ новых и б/у КОМПРЕССОРОВ.
								</p>
								<div class="sec sec_hook" id="hook">
									<div class="wrap" >
										<form action="<?= $inc->send; ?>" method="post">
											<input type="hidden" name="goalname" value="Информация">
											<input type="hidden" name="goal" value="goal_info">
											<input type="hidden" name="good" value="">
											<div class="items"  style="display: flex; flex-direction: column">
												<input type="hidden" name="answers" value="">
												<div class="item" style="width: 100%; max-width:none;"><input type="email" name="email" placeholder="Элетронная почта"></div>
												<div class="item" style="width: 100%; max-width:none;"><input type="text" name="phone" placeholder="+7 (____) - ___ - ___ - _"></div>
												<div class="item" style="width: 100%; max-width:none;"><button type="submit"><span>Отправить письмо</span></button></div>
											</div>
											<label><input type="checkbox" name="politic" value="1" data-focus="icon" checked><span class="icon"></span><span class="label">Я даю согласие на обработку персональных данных</span></label>
										</form>
										<div class="rem" style="margin-top: 15px;">Перезвоним вам в течении 20 минут</div>
									</div>
								</div>
								<div class="button_wrap" style="display: none;">
									<a href="#hook" class="button scroll gotoform" data-goal="goal_call" data-goalname="Связаться с нами" data-class-head2="Безмасляные компрессоры для любой сферы применения" data-name-good="">
										<span>Связаться с нами</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="sev sec_compressors section" id="compressors">
			<div class="art container">
				<div class="head2" style="line-height: 30px;">Вам нужен компрессор?<br>
					<span style="font-size: 18px">Тогда вы по адресу, большой парк надежных компрессоров компании Atlas Copco:</span>
				</div>
				<div class="text" style="text-align:left;">
					(аналоги Айрман,Кайзер, ЧКЗ КВ, ЗИФ Ремеза и т.д.) малой и средней мощности (7-12 бар, 5-10м3/мин).
					<br>
					Мы надежный поставщик арендных компрессоров на рынке Пермского края, у нас своя сервисная служба, которая ликвидирует остановку компрессора в кротчайшие сроки или разъяснит Вашим сотрудникам необходимые вопросы.
					<br> Если Вам нужен хороший компрессор ? Звоните !!!
				</div>
                <div class="columns items is-multiline" style="justify-content: flex-start;">
                    <div class="column">
                        <div class="item">
                            <div class="desc">
                                <span>Компрессор дизельный малой мощности 7-12 Бар/2-5,5 м3.мин</span>
                            </div>
                            <img src="data/upload/equipments/list/thumb_01.jpg" alt="Компрессор дизельный малой мощности 7-12 Бар/2-5,5 м3.мин">
                        </div>
                    </div>
                    <div class="column">
                        <div class="item">
                            <div class="desc">
                                <span>Компрессор дизельный средней мощности 7-14 Бар/7,5-12 м3.мин</span>
                            </div>
                            <img src="data/upload/equipments/list/thumb_02.jpg" alt="Компрессор дизельный малой мощности 7-12 Бар/2-5,5 м3.мин">
                        </div>
                    </div>
                    <div class="column">
                        <div class="item">
                            <div class="desc">
                                <span>Компрессор дизельной высокой мощности 8,6-35Бар/22-63 м3.мин</span>
                            </div>
                            <img src="data/upload/equipments/list/thumb_03.jpg" alt="Компрессор дизельной высокой мощности 8,6-35Бар/22-63 м3.мин">
                        </div>
                    </div>
					<div class="column">
						<div class="item">
							<div class="desc">
								<span>Аренда компрессора Atlas Copco XAS 97 5,3м3/мин 7бар</span>
							</div>
							<img src="data/upload/equipments/list/cq5dam.web.1600.1600.jpeg" alt="Аренда компрессора Atlas Copco XAS97 5,3м3/мин 7бар">
						</div>
					</div>
					<div class="column">
						<div class="item">
							<div class="desc">
								<span>Аренда компрессора Atlas Copco XATS 156</span>
							</div>
							<img src="data/upload/equipments/list/cq5dam.web.1600.jpeg" alt="Аренда компрессора Atlas Copco XATS 156">
						</div>
					</div>
					<div class="column">
						<div class="item">
							<div class="desc">
								<span>Аренда компрессора Atlas Copco XAHS 186 10,4м3/мин 12 бар</span>
							</div>
							<img src="data/upload/equipments/list/cq5dam.web.16.jpeg" alt="Аренда компрессора Atlas Copco XAHS 186 10,4м3/мин 12 бар">
						</div>
					</div>
					<div class="column">
						<div class="item">
							<div class="desc">
								<span>Пескоструйный Аппарат Contracor DBS 200 (шланги 60м + сопло 8мм)</span>
							</div>
							<img src="data/upload/equipments/list/CRKT6tfQNnM.jpg" alt="Пескоструйный Аппарат Contracor DBS 200 (шланги 60м + сопло 8мм)">
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
							<div class="item"><button type="submit"><span>Отправить письмо</span></button></div>
						</div>
						<label><input type="checkbox" name="politic" value="1" data-focus="icon" checked><span class="icon"></span><span class="label">Я даю согласие на обработку персональных данных</span></label>
					</form>
				</div>
			</div>
			</div>
		</div>

		<div class="sev sec_quiz section" id="quiz">
			<div class="art container">
				<div class="head2 title">Подберите компрессор для своего бизнеса у нас,<br> со <span>скидкой в 20%!</span></div>

				<div id="app">
				  <div class="info" v-show="completed === false">
					  <div class="percentage" >
						  Пройдено: {{ percent }}
					  </div>
					  <div class="progressbar">
					  	<div class="progressbar_body" v-bind:style="{width: percentStyle}"></div>
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
				  <div class="result_controls" v-show="questionIndex === quiz.questions.length && completed === false">
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
								<div class="item"><input type="email" name="email" placeholder="Элетронная почта"></div>
								<div class="item"><input type="text" name="phone" placeholder="+7 (____) - ___ - ___ - _"></div>
								<div class="item"><button type="submit"><span>Отправить письмо</span></button></div>
							</div>
							<label><input type="checkbox" name="politic" value="1" data-focus="icon" checked><span class="icon"></span><span class="label">Я даю согласие на обработку персональных данных</span></label>
						</form>
					</div>

				</div>

			</div>
		</div>

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
				percent: function() {
					return ((this.questionIndex / this.quiz.questions.length) * 100) + '%'
				},
				percentStyle: function() {
					return ((this.questionIndex / this.quiz.questions.length) * 100 + 5) + '%'
				}
			  },
			  methods: {
			    next: function(e) {
			      var question = quiz.questions[this.questionIndex].text;
			      var answer = e.target.labels[0].innerText;
			      this.answers.push([question, answer]);
			      this.questionIndex++;
			    },
			    submit: function() {
			    	this.completed = true;
			    	$('[name=answers]').val(JSON.stringify(this.answers));
			    },
			    restart: function() {
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
							<div class="img"><img src="<?= $equipment->img->thumb; ?>" alt="<?= strip_tags($equipment->title); ?>"></div>
							<div class="manufacturer">
								<ul>
									<? foreach ($equipment->manufact as $item): ?>
										<li><b>-</b> <?= $item; ?></li>
									<? endforeach; ?>
								</ul>
							</div>
							<div class="button_wrap"><a href="#hook" class="button scroll gotoform" data-goal="goal_equipment" data-goalname="Подобрать модель" data-class-head2="Подобрать модель" data-name-good="<?= strip_tags($equipment->title); ?>"><span>Подобрать модель</span></a></div>
						</div>
					<? endforeach; ?>
				</div>
			</div>
		</div>
*/?>

		<div class="sec sec_hook section" id="hook">
			<div class="art container">
				<div class="wrap">
					<div class="head2">Остались вопросы, <br>мы вам перезвоним!</div>
					<form action="<?= $inc->send; ?>" method="post">
						<input type="hidden" name="goalname" value="Остались вопросы">
						<input type="hidden" name="goal" value="goal_questions">
						<input type="hidden" name="good" value="">
						<div class="items columns">
							<div class="item column"><input type="text" name="name" placeholder="Ваше имя"></div>
							<div class="item column"><input type="text" name="phone" placeholder="+7 (____) - ___ - ___ - _"></div>
						</div>
						<div class="columns items">
							<div class="item column"><input type="text" name="email" placeholder="E-mail"></div>
							<div class="item column"><button type="submit"><span>Отправить письмо</span></button></div>
						</div>
						<label>
                            <input type="checkbox" name="politic" value="1" data-focus="icon" checked>
                            <span class="icon"></span><span class="label">Я даю согласие на обработку персональных данных</span>
                        </label>
					</form>
				</div>
			</div>
		</div>

		<?
			$buyouts = array (
				(object) array (
					'title'	=>	'Выкуп Б/У: Компрессоров и Комплектующих',
					'desc'	=>	'Купим Ваш компрессор <b>ДОРОГО</b> <br>и в любом состоянии',
					'img'	=>	'/styles/img/buyout_1_bg.jpg',
					'items'	=>	array (
						(object) array (
							'title'	=>	'Работаем 24/7',
							'icon'	=>	'/styles/img/buyout/buyout_1_img_01.png',
						),
						(object) array (
							'title'	=>	'Выезд в любую <br>точку страны',
							'icon'	=>	'/styles/img/buyout/buyout_1_img_02.png',
						),
						(object) array (
							'title'	=>	'Деньги в день <br>сделки',
							'icon'	=>	'/styles/img/buyout/buyout_1_img_03.png',
						),
						(object) array (
							'title'	=>	'Консультация по любым <br>вопросам сделки',
							'icon'	=>	'/styles/img/buyout/buyout_1_img_04.png',
						),
					),
				),
				(object) array (
					'title'	=>	'Процесс выкупа <br> оборудование',
					'desc'	=>	'',
					'img'	=>	'/styles/img/buyout_2_bg.jpg',
					'items'	=>	array (
						(object) array (
							'title'	=>	'Звонок или заявка',
							'icon'	=>	'/styles/img/buyout/buyout_2_img_01.png',
						),
						(object) array (
							'title'	=>	'Оцениваем ваше <br>оборудование',
							'icon'	=>	'/styles/img/buyout/buyout_2_img_02.png',
						),
						(object) array (
							'title'	=>	'Вызежаем к вам',
							'icon'	=>	'/styles/img/buyout/buyout_2_img_03.png',
						),
						(object) array (
							'title'	=>	'Вы получаете деньги',
							'icon'	=>	'/styles/img/buyout/buyout_2_img_04.png',
						),
					),
				),
			);
		?>
		<div class="sec sec_buyout section" id="buyout">
			<div class="art container">
				<div class="items columns">
					<? foreach ($buyouts as $buyout): ?>
						<div class="item column">
							<div class="header">
								<div class="head2"><?= $buyout->title; ?></div>
								<div class="desc"><?= $buyout->desc; ?></div>
							</div>
							<div class="wrapper">
								<div class="img"><img src="<?= $buyout->img; ?>" alt="<?= $buyout->title; ?>"></div>
								<div class="icons">
									<ul>
										<? foreach ($buyout->items as $item): ?>
										<li>
											<div class="icon"><img src="<?= $item->icon; ?>" alt="<?= $item->title; ?>"></div>
											<div class="title"><?= $item->title; ?></div>
										</li>
										<? endforeach; ?>
									</ul>
								</div>
							</div>
						</div>
					<? endforeach; ?>
				</div>
			</div>
		</div>

		<div class="sec sec_buyout_long" id="buyout_long">
			<div class="art container">
				<div class="items">
					<div class="item">
						<div class="header">
							<div class="head2">Как мы продаем компрессора:</div>
						</div>
						<div class="icons">
							<ul>
								<li>
									<div class="icon"><img src="/styles/img/buyout/buyout_2_img_01.png" alt="Звонок или заявка"></div>
									<div class="title">Звонок или заявка</div>
								</li>
								<li>
									<div class="icon"><img src="/styles/img/buyout/buyout_1_img_04.png" alt="Оцениваем ваше оборудование"></div>
									<div class="title">Уточняем параметры компрессор</div>
								</li>
								<li>
									<div class="icon"><img src="/styles/img/buyout/buyout_2_img_02.png" alt="Подбираем подходящую модель"></div>
									<div class="title">Подбираем подходящую модель</div>
								</li>
								<li>
									<div class="icon"><img src="/styles/img/buyout/buyout_long_img_doc.png" alt="Оформляем документы"></div>
									<div class="title">Оформляем документы</div>
								</li>
								<li>
									<div class="icon"><img src="/styles/img/buyout/buyout_1_img_03.png" alt="Вы оплачиваете"></div>
									<div class="title">Вы оплачиваете</div>
								</li>
								<li>
									<div class="icon"><img src="/styles/img/buyout/buyout_2_img_03.png" alt="Компрессор уезжает к ВАМ!!!"></div>
									<div class="title">Компрессор уезжает к <strong>вам!</strong></div>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="notice">
					Возможна продажа по программе по <span>Trade In</span>, в зачет возьмем Ваш старый компрессор!
				</div>
			</div>
		</div>

		<? if (!empty($cms_object->comments->list)): ?>
			<div class="sec sec_comments section" id="comments">
				<div class="art container">
					<div class="head2">Отзывы наших клиентов</div>
					<div class="desc">Сотрудничайте и убедитесь сами</div>
					<div class="slider">
						<ul data-center="1">
							<? foreach ($cms_object->comments->list as $comment): ?>
								<? if (empty($comment->active)) continue; ?>
								<li>
									<div class="header">
										<div class="photo"><img src="<?= Landing::IfThen(empty($comment->photo), '/styles/img/comments_def_photo.jpg', $comment->photo->thumb); ?>" alt="<?= $comment->name; ?>"></div>
										<div class="title">
											<div class="name"><?= $comment->name; ?></div>
											<div class="company"><?= $comment->desc; ?></div>
										</div>
									</div>
									<div class="text">
										<div class="content"><?= Landing::GetTextareaContent($comment->text, Landing::LE_PARAGRAPH); ?></div>
										<? if (!empty($comment->letter)): ?><div class="letter"><span><a href="<?= $comment->letter; ?>" target="_blank">Скачать благодарственное письмо</a></span></div><? endif; ?>
										<div class="date"><?= $comment->date; ?></div>
									</div>
								</li>
							<? endforeach; ?>
						</ul>
						<div class="navigation">
							<a href="#prev" class="nav prev">prev</a>
							<span class="dots">
								<? foreach ($cms_object->comments->list as $id => $comment): ?>
									<? if (empty($comment->active)) continue; ?>
									<span class="dot"><?= ($id + 1); ?></span>
								<? endforeach; ?>
							</span>
							<a href="#next" class="nav next">next</a>
						</div>
					</div>
				</div>
			</div>
		<? endif; ?>

		<div class="sec sec_contacts section" id="contacts">
			<div class="art art1 container">
				<div class="head2">Контактная информация</div>
				<div class="desc">Как вы можете связаться с нами</div>
			</div>
			<div class="bg">
				<div class="art art2 container">
					<div class="columns">
						<div class="column is-5">
							<div class="contacts">
								<div class="address"><?= Landing::GetTextareaContent($cms_object->options->contacts->address, Landing::LE_BR); ?></div>
								<div class="phones">
									<? foreach ($cms_object->options->contacts->phones as $id => $phone): ?>
									<div class="item"><a href="<?= Landing::GetPhone($phone); ?>" class="clickgoal" data-goal="goal_phone"><?= $phone; ?></a></div>
									<? endforeach; ?>
								</div>
								<div class="email"><a href="<?= Landing::GetEmail($cms_object->options->contacts->email); ?>" target="_blank" class="clickgoal" data-goal="goal_email"><?= $cms_object->options->contacts->email; ?></a></div>
								<div class="socials">
									<? if ($cms_object->options->contacts->whatsapp): ?><div class="item"><img src="/styles/img/socials/icon_whatsapp.png" alt="WhatsApp"><a href="<?= Landing::GetWhatsApp($cms_object->options->contacts->whatsapp); ?>" class="clickgoal" data-goal="goal_whatsapp">WhatsApp</a></div><? endif; ?>
									<? if ($cms_object->options->contacts->viber): ?><div class="item"><img src="/styles/img/socials/icon_viber.png" alt="Viber"><a href="<?= Landing::GetViber($cms_object->options->contacts->viber); ?>" class="clickgoal" data-goal="goal_viber">Viber</a></div><? endif; ?>
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

		<?= $this->include_js(); ?>

		<? include($root . '/tmpls/counters_foo.inc'); ?>
	</body>
</html>