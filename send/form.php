<?php

	header('Content-Type: application/json');
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/options.inc';

	$mail = new Mailer();
	$mail->SetSubject($cms_object->options->order->subject);
	$mail->SetFrom($cms_object->options->order->mailfrom, $cms_object->options->order->mailfromtitle);
	$mail->AddTo($cms_object->options->order->mailto);
	$mail->AddCopy($cms_object->options->order->copy);
	$mail->AddHidden($cms_object->options->order->hidden);
	$mail->AddPost('name', 'Имя', Mailer::REQUIRE_IFEXISTS, '', 0, 0, array (
		'empty'		=>	'Введите ваше имя!',
	));
	$mail->AddPost('phone', 'Телефон', Mailer::REQUIRE_IFEXISTS, 'filter:phone', 7, 14, array (
		'empty'		=>	'Введите ваш телефон!',
		'filter'	=>	'Неверный номер телефона',
	));
	$mail->AddPost('email', 'Почта', Mailer::REQUIRE_NEVER, 'filter:email', 0, 0, array (
		'empty'		=>	'Введите ваш email!',
		'filter'	=>	'Email нужно вводить в формате name@site.ru',
	));
	$mail->AddPost('good', 'Оборудование', Mailer::REQUIRE_NEVER, '', 0, 0, array ());
	$mail->AddPost('text', 'Вопрос', Mailer::REQUIRE_IFEXISTS, '', 0, 0, array (
		'empty'		=>	'Введите ваш вопрос!',
	));
	$mail->AddPost('politic', '', Mailer::REQUIRE_ALWAYS, '', 0, 0, array (
		'empty'		=>	'Нужно согласиться с политикой',
	));
	$mail->AddPost('goalname', 'Заявка с кнопки', Mailer::REQUIRE_ALWAYS, '', 0, 0, array (
		'empty'		=>	'Отсутствует информация о кнопке! <br> Обратитесь к администратору сайта!',
	));
	$mail->AddPost('goal', '', Mailer::REQUIRE_ALWAYS, '', 0, 0, array (
		'empty'		=>	'Отсутствует информация о цели! <br> Обратитесь к администратору сайта!',
	));
	if (!empty($_POST['choice'])) {
		$_POST['choice'] = str_replace(['[', ']', '"'], ['','', ''], $_POST['choice']);
		$mail->AddPost('choice', 'Выбор оборудования', Mailer::REQUIRE_NEVER, '', 0, 0, array ());	
	}
	if (!empty($_POST['answers'])) {
		$_POST['answers'] = str_replace(['[', ']', '"', '?,', ','], ['','', '', '?:', ' | '], $_POST['answers']);
		$mail->AddPost('answers', 'Ответы на квиз', Mailer::REQUIRE_NEVER, '', 0, 0, array ());	
	}	
	$mail->AddSeparator();
	if (is_object($refinfo)):
		$mail->AddVariable('Дата первого контакта', $refinfo->data->first_date);
		$mail->AddVariable('Первый контакт', $refinfo->data->first_ref);
		$mail->AddVariable('Реферал', $refinfo->data->referer);
		$mail->AddVariable('Параметры', $refinfo->data->get);
		$mail->AddVariable('IP-адрес', $refinfo->data->ip);
		$mail->AddVariable('User agent', $refinfo->data->ua);
		$sxgeo = new SxGeo($inc->sxgeodb, SXGEO_BATCH);
		$city = $sxgeo->getCityFull($refinfo->data->ip);
		if ($city):
			$mail->AddSeparator();
			$mail->AddVariable('Страна', $city['country']['name_ru']);
			$mail->AddVariable('Регион', $city['region']['name_ru']);
			$mail->AddVariable('Город', $city['city']['name_ru']);
			$mail->AddVariable('На карте', '<a href="https://www.google.ru/maps/@' . $city['city']['lat'] . ',' . $city['city']['lon'] . ',12z?hl=ru">Google карта</a>');
		endif;
	endif;
	$out = $mail->Send();
	echo $out;
	/*vd::dump($out);
	vd::setOptions(true, true, true);
	vd::dump($mail);*/

?>