<?php

	class Landing {
		static public $version = '1.0';

		const LE_NO = 0;
		const LE_PARAGRAPH = 1;
		const LE_BR = 2;

		const VIBER_CHAT = 1;
		const VIBER_ADD = 2;

		const SKYPE_CHAT = 1;
		const SKYPE_CALL = 2;

		static public function SetSID() {
			session_start();
			if (empty($_SESSION['FSID'])) {
				$fsid = date('YmdHis');
				setcookie('FSID', $fsid, 0, '/');
				$_SESSION['FSID'] = md5($fsid);
			}
		}

		/**
		 * Возвращает значение, в зависимости от условия.
		 * @param  boolean $eq     Условие.
		 * @param  string  $then   Возвращается, если условие = true.
		 * @param  string  $else   Возвращается, если условие = false.
		 */
		static public function IfThen($eq, $then, $else = '') {
			if ($eq):
				return $then;
			else:
				return $else;
			endif;
		}

		/**
		 * Возвращает форматированный текст.
		 * @param  string  $text   Форматируемый текст ('форматированный %text%').
		 * @param  array   $vars   Массив ключей и подмен ('text' => 'текст').
		 * @return string
		 */
		static public function GetFormatted($text, $vars = array ()) {
			foreach ($vars as $key => $var):
				$text = str_replace('%' . $key . '%', $var, $text);
			endforeach;
			return $text;
		}

		/**
		 * Выводит на экран форматированный текст.
		 * @param  string  $text   Форматируемый текст ('форматированный %text%').
		 * @param  array   $vars   Массив ключей и подмен ('text' => 'текст').
		 */
		static public function ShowFormatted($text, $vars = array ()) {
			echo self::GetFormatted($text, $vars);
		}

		/**
		 * Возвращает форматированный текст из TEXTAREA с заданным типом переноса строк.
		 * @param  string  $text    Исходный текст.
		 * @param  integer $le_type Тип форматирования новых строк.
		 * @return string
		 */
		static public function GetTextareaContent($text, $le_type = self::LE_NO) {
			switch ($le_type) {
				case self::LE_BR:
					$text = preg_replace('/([\n\r]|[\n]|\r)+/i', '<br>', $text);
					return $text;
					break;
				case self::LE_PARAGRAPH:
					$text = preg_replace('/([\n\r]|[\n]|\r)+/i', "</p><p>", $text);
					$text = '<p>' . $text . '</p>';
					return $text;
					break;
				default:
					return $text;
					break;
			}
		}

		/**
		 * Возвращает ссылку на телефон.
		 * @param  string  $data   Номер телефона в любом формате (возможны теги).
		 * @return string
		 */
		static public function GetPhone($data) {
			$data = preg_replace('/\D+/', '', $data);
			return (trim($data) == '') ? '' : 'tel:+' . preg_replace('/^8/', '7', $data);
		}

		/**
		 * Выводит на экран форматированный в соответствии с шаблоном телефон.
		 * @param  string  $data   Номер телефона в любом формате (возможны теги).
		 * @param  string  $dot    Шаблон (%link% - ссылка телефона; %data% - номер на телефон, соответствует $data).
		 */
		static public function ShowPhone($data, $dot = '<a href="%link%" class="phone clickgoal" data-goal="goal_phone">%data%</a>') {
			$link = self::GetPhone($data);
			echo self::GetFormatted($dot, array (
				'link'	=>	$link,
				'data'	=>	$data,
			));
		}

		/**
		 * Возвращает ссылку на email.
		 * @param  string  $data   Email адрес.
		 * @return string
		 */
		static public function GetEmail($data) {
			return (trim($data) == '') ? '' : 'mailto:' . $data;
		}

		/**
		 * Выводит на экран форматированный в соответствии с шаблоном email.
		 * @param  string  $data   Email адрес.
		 * @param  string  $dot    Шаблон (%link% - ссылка на email; %data% - адрес email, соответствует $data).
		 */
		static public function ShowEmail($data, $dot = '<a href="%link%" class="email clickgoal" data-goal="goal_email">%data%</a>') {
			$link = self::GetEmail($data);
			echo self::GetFormatted($dot, array (
				'link'	=>	$link,
				'data'	=>	$data,
			));
		}

		/**
		 * Возвращает ссылку на Skype.
		 * @param  string  $data      Логин Skype.
		 * @param  integer $call_type Тип ссылки. Константа типа SKYPE_*.
		 * @return string
		 */
		static public function GetSkype($data, $call_type = self::SKYPE_CALL) {
			switch ($call_type) {
				case self::SKYPE_CALL:
					$data = 'skype:' . $data . '?call';
					break;
				case self::SKYPE_CHAT:
					$data = 'skype:' . $data . '?chat';
					break;
				default:
					break;
			}
			return (trim($data) == '') ? '' : $data;
		}

		/**
		 * Выводит на экран форматированный в соответствии с шаблоном Skype.
		 * @param  string  $data   Логин Skype.
		 * @param  string  $dot    Шаблон (%link% - ссылка на Skype; %data% - логин Skype, соответствует $data).
		 */
		static public function ShowSkype($data, $dot = '<a href="%link%" class="skype clickgoal" data-goal="goal_skype">%data%</a>') {
			$link = self::GetSkype($data);
			echo self::GetFormatted($dot, array (
				'link'	=>	$link,
				'data'	=>	$data,
			));
		}

		/**
		 * Возвращает ссылку на Viber.
		 * @param  string  $data      Телефон Viber.
		 * @param  integer $call_type Тип ссылки. Константа типа VIBER_*.
		 * @return string
		 */
		static public function GetViber($data, $call_type = self::VIBER_ADD) {
			$data = preg_replace('/\D+/', '', $data);
			$data = preg_replace('/^8/', '7', $data);
			switch ($call_type) {
				case self::VIBER_ADD:
					$data = 'viber://add?number=' . $data;
					break;
				case self::VIBER_CHAT:
					$data = 'viber://chat?number=' . $data;
					break;
				default:
					break;
			}
			return (trim($data) == '') ? '' : $data;
		}

		/**
		 * Выводит на экран форматированный в соответствии с шаблоном Viber.
		 * @param  string  $data   Телефон Viber.
		 * @param  string  $dot    Шаблон (%link% - ссылка на Viber; %data% - телефон Viber, соответствует $data).
		 */
		static public function ShowViber($data, $dot = '<a href="%link%" class="viber clickgoal" data-goal="goal_viber">%data%</a>') {
			$link = self::GetViber($data);
			echo self::GetFormatted($dot, array (
				'link'	=>	$link,
				'data'	=>	$data,
			));
		}

		/**
		 * Возвращает ссылку на WhatsApp.
		 * @param  string  $data   Телефон WhatsApp.
		 * @return string
		 */
		static public function GetWhatsApp($data) {
			$data = preg_replace('/\D+/', '', $data);
			$data = preg_replace('/^8/', '7', $data);
			return (trim($data) == '') ? '' : 'whatsapp://send?phone=+' . $data;
		}

		/**
		 * Выводит на экран форматированный в соответствии с шаблоном WhatsApp.
		 * @param  string  $data   Телефон WhatsApp.
		 * @param  string  $dot    Шаблон (%link% - ссылка на WhatsApp; %data% - телефон WhatsApp, соответствует $data).
		 */
		static public function ShowWhatsApp($data, $dot = '<a href="%link%" class="whatsapp clickgoal" data-goal="goal_whatsapp">%data%</a>') {
			$link = self::GetWhatsApp($data);
			echo self::GetFormatted($dot, array (
				'link'	=>	$link,
				'data'	=>	$data,
			));
		}

		/**
		 * Возвращает ссылку на Telegram.
		 * @param  string  $data   Логин Telegram.
		 * @return string
		 */
		static public function GetTelegram($data) {
			return (trim($data) == '') ? '' : 'tg://resolve?domain=' . $data;
		}

		/**
		 * Выводит на экран форматированный в соответствии с шаблоном Telegram.
		 * @param  string  $data   Логин Telegram.
		 * @param  string  $dot    Шаблон (%link% - ссылка на Telegram; %data% - логин Telegram, соответствует $data).
		 */
		static public function ShowTelegram($data, $dot = '<a href="%link%" class="telegram clickgoal" data-goal="goal_telegram">%data%</a>') {
			$link = self::GetTelegram($data);
			echo self::GetFormatted($dot, array (
				'link'	=>	$link,
				'data'	=>	$data,
			));
		}

		/**
		 * Выводит на экран код зарегистрированных метрик Яндекса.
		 * @param  array   $items  Список ID метрик, состоит из элементов:
		 *                         (object) array (
		 *                             'active' => boolean  активность,
		 *                             'id'     => string   ID счетчика,
		 *                         )
		 * @return boolean         Возвращает true, если счетчики выведены на экран, false, если счетчиков нету.
		 */
		static public function ShowMetrika($items = array ()) {
			if (empty($items)) return false;
			$id_list = array ();
			$img_list = '';
			$code = '';
			foreach ($items as $item):
				if (empty($item->active)) continue;
				$id_list[] = '"' . $item->id . '"';
				$img_list .= '<img src="https://mc.yandex.ru/watch/' . $item->id . '" style="position:absolute;left:-9999px;" alt="">';
				$code .= '
						ym("' . $item->id . '", "init", {
							id: "' . $item->id . '",
							clickmap: true,
							trackLinks: true,
							accurateTrackBounce: true,
							webvisor: true,
						});
				';
			endforeach;
			if (empty($id_list)) return false;
			$id = implode(',', $id_list);
			$output = '
				<!-- Yandex.Metrika counter -->
					<script type="text/javascript" >
						(function(m, e, t, r, i, k, a){
							m[i] = m[i] || function(){
								(m[i].a = m[i].a || []).push(arguments);
							};
							m[i].l = 1*new Date();
							k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k,a);
						})(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
						var yaCounters = [' . $id . '];
						' . $code . '
					</script>
					<noscript><div>' . $img_list . '</div></noscript>
				<!-- /Yandex.Metrika counter -->
			';
			echo $output;
			return true;
		}

		/**
		 * Выводит на экран код зарегистрированных счетчиков Google.
		 * @param  array   $items  Список ID счетчиков Google, состоит из элементов:
		 *                         (object) array (
		 *                             'active' => boolean  активность,
		 *                             'id'     => string   ID счетчика,
		 *                         )
		 * @return boolean         Возвращает true, если счетчики выведены на экран, false, если счетчиков нету.
		 */
		static public function ShowAnalytics($items = array ()) {
			if (empty($items)) return false;
			$id_list = array ();
			$code = '';
			foreach ($items as $index => $item):
				if (empty($item->active)) continue;
				$id_list[] = '"tracker' . $index . '"';
				$code .= '
						ga("create", "' . $item->id . '", "auto", "tracker' . $index . '");
						ga("tracker' . $index . '.require", "displayfeatures");
						ga("tracker' . $index . '.send", "pageview");
				';
			endforeach;
			if (empty($id_list)) return false;
			$id = implode(',', $id_list);
			$output = '
				<!-- GoogleAnalytics counter -->
					<script type="text/javascript">
						(function(i,s,o,g,r,a,m) {
							i.GoogleAnalyticsObject = r;
							i[r] = i[r] || function() {
								(i[r].q = i[r].q || []).push(arguments);
							};
							i[r].l = 1 * new Date();
							a = s.createElement(o);
							m = s.getElementsByTagName(o)[0];
							a.async = 1;
							a.src = g;
							m.parentNode.insertBefore(a,m);
						})(window, document, "script", "//www.google-analytics.com/analytics.js", "ga");
						var gaCounters = [' . $id . '];
						' . $code . '
					</script>
				<!-- /GoogleAnalytics counter -->
			';
			echo $output;
			return true;
		}

		/**
		 * Выводит на экран код скриптов.
		 * @param  array   $data   Массив скриптов, состоит из элементов:
		 *                         (object) array (
		 *                             'active' => boolean  активность,
		 *                             'title'  => string   название скрипта,
		 *                             'code'   => string   код скрипта,
		 *                         )
		 * @return boolean         Возвращает true, если код выведен на экран, false, если кода нету.
		 */
		static public function ShowCode($data = array ()) {
			if (empty($data)) return false;
			$output = '';
			foreach ($data as $item):
				if (empty($item->active)) continue;
				$output .= '
					<!-- Start [' . $item->title . '] -->
					' . $item->code . '
					<!-- End [' . $item->title . '] -->
				';
			endforeach;
			if (empty($output)) return false;
			echo $output;
			return true;
		}

		/**
		 * Выводит на экран кнопку покупки товара через сервис КупиВкредит (Тинькофф).
		 * @param  array   $goods   Массив товаров, каждый состоит из элементов:
		 *                          array (
		 *                              'category' => string  категория товара,
		 *                              'title'    => string  название товара,
		 *                              'count'    => float   количество товара (разделитель - точка),
		 *                              'cost'     => float   цена еденицы товара (разделитель - точка),
		 *                          )
		 * @param  array   $options Массив опций (если не заданы, то запускается тестовый магазин), состав:
		 *                          array (
		 *                              'shopId'     => string  ID магазина,
		 *                              'showcaseId' => string  ID витрины,
		 *                              'url'        => string  url адрес обработчика заявок,
		 *                          )
		 * @param  string  $text    Текст кнопки.
		 * @param  string  $class   Класс кнопки.
		 */
		static public function ShowKupiVkredit($goods = array (), $options = false, $text = 'Купивкредит', $class = '') {
			$_options = (object) array (
				'shopId'		=>	empty($options->shopId) ? 'test_shop' : $options->shopId,
				'showcaseId'	=>	empty($options->showcaseId) ? 'test_shop' : $options->showcaseId,
				'url'			=>	empty($options->url) ? 'https://loans-qa.tcsbank.ru/api/partners/v1/lightweight/create' : $options->url,
			);
			$sum = 0;
			$goods_output = '';
			foreach ($goods as $id => $good):
				if (empty($good['cost'])) continue;
				$cost = preg_replace('/<s>.*<\/s>/', '', $good['cost']);
				$cost = preg_replace('/\D+/', '', $cost);
				if (empty($cost)) continue;
				$count = empty($good['count']) ? 1 : $good['count'];
				$sum += $cost * $count;
				if (!empty($good['category'])) $goods_output .= '<input name="itemCategory_' . $id . '" value="' . $good['category'] . '" type="hidden">';
				$goods_output .= '<input name="itemName_' . $id . '" value="' . $good['title'] . '" type="hidden">';
				$goods_output .= '<input name="itemQuantity_' . $id . '" value="' . $count . '" type="hidden">';
				$goods_output .= '<input name="itemPrice_' . $id . '" value="' . $cost . '" type="hidden"> ';
			endforeach;
			if (empty($goods_output)):
				echo '';
			else:
				$output = '<form action="' . $_options->url . '" method="post" class="no-ajax" target="_blank">';
				$output .= '<input name="shopId" value="' . $_options->shopId . '" type="hidden">';
				$output .= '<input name="showcaseId" value="' . $_options->showcaseId . '" type="hidden">';
				$output .= $goods_output;
				$output .= '<input name="sum" value="' . $sum . '" type="hidden">';
				$output .= '<button type="submit" class="' . $class . '"><span>' . $text . '</span></button>';
				$output .= '</form>';
				echo $output;
			endif;
		}

		/**
		 * Возвращает Яндекс карту.
		 * @param  stdClass $data  Данные карты (брать из админки поле карта).
		 * @param  string   $class Класс блока с картой.
		 * @param  string   $load  Подпись, которая будет видна до загрузки карты.
		 * @return string
		 */
		static public function GetMap($data, $class = 'ymap', $load = 'Загрузка карты...') {
			return self::GetFormatted('<div class="%class%" data-coord-lat="%lat%" data-coord-lon="%lon%" data-zoom="%zoom%" data-dot-type="islands#dotIcon" data-dot-color="%color%"><span>%load%</span><div style="display:none;" class="balloon">%address%</div></div>', array (
				'class'		=>	$class,
				'load'		=>	$load,
				'lat'		=>	$data->lat,
				'lon'		=>	$data->lon,
				'zoom'		=>	$data->zoom,
				'color'		=>	$data->color,
				'address'	=>	self::GetTextareaContent($data->label, self::LE_BR),
			));
		}

		/**
		 * Выводит на экран Яндекс карту.
		 * @param  stdClass $data  Данные карты (брать из админки поле карта).
		 * @param  string   $class Класс блока с картой.
		 * @param  string   $load  Подпись, которая будет видна до загрузки карты.
		 */
		static public function ShowMap($data, $class = 'ymap', $load = 'Загрузка карты...') {
			echo self::GetMap($data, $class, $load);
		}

		/**
		 * Возвращает данные текущей страницы из списка страниц.
		 * @param  stdClass $pages Список страниц. Брать из админки.
		 * @return stdClass
		 */
		static public function GetCurrentPage($pages) {
			$uri = $_SERVER['REQUEST_URI'];
			if (strpos($uri, '?') !== false) $uri =substr($uri, 0, strpos($uri, '?'));
			foreach ($pages as $item) {
				if ($uri == $item->url) return $item;
				if (isset($item->child)) {
					foreach ($item->child as $sub) {
						if ($uri == $sub->url) return $sub;
					}
				}
			}
			return false;
		}

	}

?>