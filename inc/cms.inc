<?php

	$cms = new LandingCms();
	$cms->add_user('sa', 'njhthj', LC_USER_SA);
	$cms->add_user('admin', 'prom44Stroy');

	// SECTION
	$cms->add_section('options', 'Опции', true);
	$cms->add_group('pages', 'Страницы', LC_GT_LIST, array (), array ('name', array ('desktop_menu', 'Десктопное меню'), array ('mobile_menu', 'Мобильное меню')));
	$cms->add_field('desktop_menu', 'В десктопном меню', LC_FT_CHECKBOX, array (
		'default'	=>	true,
		'required'	=>	false,
		'label'		=>	'Показывать',
	));
	$cms->add_field('mobile_menu', 'В мобильном меню', LC_FT_CHECKBOX, array (
		'default'	=>	true,
		'required'	=>	false,
		'label'		=>	'Показывать',
	));
	$cms->add_field('name', 'Название', LC_FT_TEXT, array (
		'default'	=>	'',
		'required'	=>	true,
		'desc'		=>	'Отображается в меню',
	));
	$cms->add_field('url', 'Ссылка', LC_FT_TEXT, array (
		'default'	=>	'/',
		'required'	=>	true,
	));
	$cms->add_field('title', 'TITLE', LC_FT_TEXT, array (
		'default'	=>	'',
		'required'	=>	true,
	));
	$cms->add_field('desc', 'DESCRIPTION', LC_FT_TEXTAREA, array (
		'default'	=>	'',
		'required'	=>	false,
		'rows'		=>	4,
	));
	$cms->add_field('key', 'KEYWORDS', LC_FT_TEXTAREA, array (
		'default'	=>	'',
		'required'	=>	false,
		'rows'		=>	4,
	));

	$cms->add_group('menu', 'Меню', LC_GT_TABLE, array ());
	$cms->add_field('title', 'Текст', LC_FT_TEXT, array (
		'default'	=>	'',
		'required'	=>	false,
	));
	$cms->add_field('url', 'Ссылка', LC_FT_TEXT, array (
		'default'	=>	'',
		'required'	=>	false,
	));

	$cms->add_group('contacts', 'Контакты', LC_GT_SINGLE, array ());
	$cms->add_field('phones', 'Телефоны', LC_FT_LIST, array (
		'default'	=>	'',
		'required'	=>	true,
	));
	$cms->add_field('email', 'Email', LC_FT_TEXT, array (
		'default'	=>	'',
		'required'	=>	true,
	), array (), true);
	$cms->add_field('whatsapp', 'WhatsApp', LC_FT_TEXT, array (
		'default'	=>	'',
		'required'	=>	false,
	));
	$cms->add_field('viber', 'Viber', LC_FT_TEXT, array (
		'default'	=>	'',
		'required'	=>	false,
	), array (), true);
	$cms->add_field('address', 'Адрес', LC_FT_TEXTAREA, array (
		'default'	=>	'',
		'required'	=>	true,
		'rows'		=>	4,
	));
	$cms->add_field('map', 'Карта', LC_FT_YMAP, array (
		'default'	=>	array (
			'lat'		=>	'',
			'lon'		=>	'',
			'zoom'		=>	'17',
			'color'		=>	'#0000FF',
			'label'		=>	'<img src="/styles/img/logo.png" alt="">',
		),
		'required'	=>	true,
	));

	/*$cms->add_group('contacts', 'Контакты', LC_GT_LIST, array (), 'title');
	$cms->add_field('title', 'Название', LC_FT_TEXT, array (
		'default'	=>	'',
		'required'	=>	true,
	));
	$cms->add_field('desc', 'Описание', LC_FT_TEXTAREA, array (
		'default'	=>	'',
		'required'	=>	false,
		'rows'		=>	4,
	));
	$cms->add_field('phone', 'Телефон', LC_FT_TEXT, array (
		'default'	=>	'',
		'required'	=>	true,
	));
	$cms->add_field('email', 'Email', LC_FT_TEXT, array (
		'default'	=>	'',
		'required'	=>	false,
	));
	$cms->add_field('address', 'Адрес', LC_FT_TEXT, array (
		'default'	=>	'',
		'required'	=>	false,
	));
	$cms->add_field('map', 'Карта', LC_FT_YMAP, array (
		'default'	=>	'',
		'required'	=>	false,
	));*/

	$cms->add_group('order', 'Заявки', LC_GT_SINGLE, array ());
	$cms->add_field('mailfromtitle', 'Почта от кого (имя)', LC_FT_TEXT, array (
		'default'	=>	'',
		'required'	=>	false,
		'desc'		=>	'<b>подстановки:</b><ul><li>%host% - текущий хост</li></ul>',
	));
	$cms->add_field('mailfrom', 'Почта от кого (адрес)', LC_FT_TEXT, array (
		'default'	=>	'no-reply@%host%',
		'required'	=>	true,
		'desc'		=>	'<b>подстановки:</b><ul><li>%host% - текущий хост</li></ul>',
	));
	$cms->add_field('subject', 'Тема заявки', LC_FT_TEXT, array (
		'default'	=>	'Заявка с сайта %host%',
		'required'	=>	true,
		'desc'		=>	'<b>подстановки:</b><ul><li>%host% - текущий хост</li></ul>',
	));
	$cms->add_field('mailto', 'Заявочная почта', LC_FT_LIST, array (
		'default'	=>	'',
		'required'	=>	true,
		'desc'		=>	'Возможно несколько',
	));
	$cms->add_field('copy', 'Копия', LC_FT_LIST, array (
		'default'	=>	'',
		'required'	=>	false,
		'desc'		=>	'Возможно несколько',
	));
	$cms->add_field('hidden', 'Скрытые получатели', LC_FT_LIST, array (
		'default'	=>	'',
		'required'	=>	false,
		'desc'		=>	'Возможно несколько',
	));



	// SECTION
	$cms->add_section('codes', 'Счетчики', false);
	$cms->add_group('metrikas', 'Метрика', LC_GT_TABLE, array ());
	$cms->add_field('id', 'ID', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));

	$cms->add_group('analytics', 'Аналитика', LC_GT_TABLE, array ());
	$cms->add_field('id', 'ID', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));

	$cms->add_group('head', 'Код в &lt;HEAD&gt;', LC_GT_TABLE, array ());
	$cms->add_field('title', 'Название', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));
	$cms->add_field('code', 'Код', LC_FT_TEXTAREA, array (
		'required'	=>	false,
		'default'	=>	'',
		'rows'		=>	12,
	));

	$cms->add_group('body', 'Код после &lt;BODY&gt;', LC_GT_TABLE, array ());
	$cms->add_field('title', 'Название', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));
	$cms->add_field('code', 'Код', LC_FT_TEXTAREA, array (
		'required'	=>	false,
		'default'	=>	'',
		'rows'		=>	12,
	));

	$cms->add_group('footer', 'Код перед &lt;/BODY&gt;', LC_GT_TABLE, array ());
	$cms->add_field('title', 'Название', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));
	$cms->add_field('code', 'Код', LC_FT_TEXTAREA, array (
		'required'	=>	false,
		'default'	=>	'',
		'rows'		=>	12,
	));



	// SECTION
	$cms->add_section('integrations', 'Интеграции', false);
	$cms->add_group('amocrm', 'AMOCrm', LC_GT_SINGLE, array ());
	$cms->add_field('active', 'Активность', LC_FT_CHECKBOX, array (
		'required'	=>	false,
		'default'	=>	false,
		'label'		=>	'Активно',
	));
	$cms->add_field('account', 'Аккаунт', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));
	$cms->add_field('login', 'Логин', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));
	$cms->add_field('apikey', 'API ключ', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));
	$cms->add_field('userid', 'ID пользователя', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));

	$cms->add_group('bitrix24', 'Bitrix24', LC_GT_SINGLE, array ());
	$cms->add_field('active', 'Активность', LC_FT_CHECKBOX, array (
		'required'	=>	false,
		'default'	=>	false,
		'label'		=>	'Активно',
	));
	$cms->add_field('host', 'Хост', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));
	$cms->add_field('login', 'Логин', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));
	$cms->add_field('password', 'Пароль', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));

	$cms->add_group('mailchimp', 'MailChimp', LC_GT_SINGLE, array ());
	$cms->add_field('active', 'Активность', LC_FT_CHECKBOX, array (
		'required'	=>	false,
		'default'	=>	false,
		'label'		=>	'Активно',
	));
	$cms->add_field('apikey', 'API ключ', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));
	$cms->add_field('listid', 'ID списка', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));

	$cms->add_group('unisender', 'UniSender', LC_GT_SINGLE, array ());
	$cms->add_field('active', 'Активность', LC_FT_CHECKBOX, array (
		'required'	=>	false,
		'default'	=>	false,
		'label'		=>	'Активно',
	));
	$cms->add_field('apikey', 'API ключ', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));
	$cms->add_field('listid', 'ID списка', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));



	// SECTION
	$cms->add_section('offer', 'Оффер', true);
	$cms->add_group('data', 'Основные данные', LC_GT_SINGLE, array ());
	$cms->add_field('header', 'Заголовок (H1)', LC_FT_TEXTAREA, array (
		'required'	=>	true,
		'default'	=>	'',
		'rows'		=>	2,
	));
	$cms->add_field('desc', 'Подпись под заголовком', LC_FT_TEXTAREA, array (
		'required'	=>	false,
		'default'	=>	'',
		'rows'		=>	4,
	), array (), true);
	$cms->add_field('right', 'Текст справа', LC_FT_TEXTAREA, array (
		'required'	=>	true,
		'default'	=>	'',
		'rows'		=>	10,
	));



	// SECTION
	$cms->add_section('equipments', 'Оборудование', true);
	$cms->add_group('list', 'Список', LC_GT_LIST, array (), array ('title'));
	$cms->add_field('title', 'Название', LC_FT_TEXTAREA, array (
		'required'	=>	true,
		'default'	=>	'',
		'rows'		=>	2,
	));
	$cms->add_field('img', 'Фото', LC_FT_IMG, array (
		'required'	=>	true,
		'default'	=>	'',
		'thumbs'	=>	array (
			'thumb'		=>	array (
				'width'		=>	350,
				'height'	=>	290,
				'bg'		=>	'#ffffff',
			),
		),
	));
	$cms->add_field('manufact', 'Производители', LC_FT_LIST, array (
		'required'	=>	true,
		'default'	=>	'',
	));



	// SECTION
	$cms->add_section('comments', 'Отзывы', true);
	$cms->add_group('list', 'Список', LC_GT_LIST, array (), array ('name'));
	$cms->add_field('name', 'Имя', LC_FT_TEXT, array (
		'required'	=>	true,
		'default'	=>	'',
	));
	$cms->add_field('desc', 'Подпись', LC_FT_TEXT, array (
		'required'	=>	false,
		'default'	=>	'',
	));
	$cms->add_field('photo', 'Фото', LC_FT_IMG, array (
		'required'	=>	false,
		'default'	=>	'',
		'thumbs'	=>	array (
			'thumb'		=>	array (
				'width'		=>	48,
				'height'	=>	48,
				'bg'		=>	'#ffffff',
			),
		),
	));
	$cms->add_field('text', 'Текст', LC_FT_TEXTAREA, array (
		'required'	=>	true,
		'default'	=>	'',
		'rows'		=>	20,
	));
	$cms->add_field('letter', 'Благодарственное письмо', LC_FT_FILE, array (
		'required'	=>	false,
		'default'	=>	'',
	));
	$cms->add_field('date', 'Дата', LC_FT_DATE, array (
		'required'	=>	true,
		'default'	=>	'',
	));



	$cms->init();

?>