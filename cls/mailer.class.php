<?php

	/**
	 * Класс отправки письма на базе PHP Mail
	 *
	 * Удобный класс для отправки электронных писем.
	 *
	 * @package Backend
	 * @category Mail
	 * @author Руслан Александрович <szenprogs@gmail.com>
	 * @version 6.0
	 * @copyright Copyright (c) 2019, SzenProgs.ru
	 */
	class Mailer {
		static public $version = '6.0';

		const REQUIRE_ALWAYS = 1;
		const REQUIRE_IFEXISTS = 2;
		const REQUIRE_NEVER = 3;

		const FILTER_OFF = 0;

		const STATUS_SUCCESS = 'success';
		const STATUS_ERROR = 'error';

		const TYPE_POST = 'post';
		const TYPE_GET = 'get';
		const TYPE_VAR = 'var';
		const TYPE_SEPARATOR = 'sep';

		private $subject_raw = '';
		private $subject = '';
		private $from = '';
		private $to = array ();
		private $copy = array ();
		private $hidden = array ();

		private $filters = array ();
		private $fields = array ();
		private $files = array ();
		private $errors = array ();
		private $warnings = array ();

		private $other_get = false;
		private $other_post = false;
		private $other_files = false;

		private $check_referer = true;
		private $check_sid = true;

		/**
		 * Список коллбек функций, которые будут выполнены в случае успешной отправки.
		 * @var array
		 */
		private $callbacks_before_success = array ();
		private $callbacks_after_success = array ();
		private $callbacks_after_error = array ();
		private $callbacks_before_send = array ();
		private $callbacks_after_send = array ();

		private $mail_separator = 'mailerclassphpbound';
		private $templates_path = array ();
		private $template_file = '';
		private $template_text = '';
		private $header = '';
		private $body = '';

		private $result = array ();

		private $messages = array (
			'require'		=>	'Отсутствует обязательное поле <b>%field_name%</b>',
			'empty'			=>	'Пустое поле <b>%field_name%</b>',
			'filter'		=>	'Поле <b>%field_name%</b> не соответствует заданному формату',
			'min'			=>	'Количество символов в поле <b>%field_name%</b> должно быть не меньше <b>%field_min%</b>',
			'max'			=>	'Количество символов в поле <b>%field_name%</b> должно быть не больше <b>%field_max%</b>',
			'minsize'		=>	'Размер файла в поле <b>%field_name%</b> должен быть не меньше <b>%minsize%</b>',
			'maxsize'		=>	'Размер файла в поле <b>%field_name%</b> должен быть не больше <b>%maxsize%</b>',
			'filetype'		=>	'Тип файла в поле <b>%field_name%</b> не соответствует значению <b>%filetypes%</b>',
			'ini_maxsize'	=>	'Размер файла в поле <b>%field_name%</b> превысил допустимый размер в <b>%ini_maxsize%</b> установленный в php.ini',
			'form_maxsize'	=>	'Размер файла в поле <b>%field_name%</b> превысил допустимый размер в <b>%form_maxsize%</b> установленный в форме (MAX_FILE_SIZE)',
			'partial'		=>	'Файл в поле <b>%field_name%</b> загружен не полностью.',
			'no_tmp_dir'	=>	'Ошибка загрузки файла <b>%field_name%</b>. Отсутствует временная директория на сервере',
			'cant_write'	=>	'Ошибка загрузки файла <b>%field_name%</b>. Не удалось записать файл на сервере',
			'php_ext'		=>	'Ошибка загрузки файла <b>%field_name%</b>. Неизвестная ошибка расширения',
			'subject'		=>	'Нужно задать тему сообщения',
			'email_from'	=>	'Нужно задать почту "от кого"',
			'email_to'		=>	'Нужно задать почту "кому"',
			'spam'			=>	'Попытка внешнего доступа! Спам не приветствуется',
			'success'		=>	'Заявка успешно отправлена',
			'unknown'		=>	'Неизвестная ошибка',
		);

		function __construct() {
			$this->AddFilters();
			$this->AddTemplatesPath('\send/');
			$this->AddTemplatesPath('/send/templates/');
		}

/* START - Универсальные статические функции */

		/**
		 * Объединяет или дополняет массив.
		 * @param  array        $items Исходный массив.
		 * @param  array|string $item  Дополняющий массив или строка.
		 * @return array               Объединенный массив.
		 */
		static private function Merge($items, $item) {
			if (empty($item)) return $items;
			switch (gettype($item)):
				case 'array':
					$item = array_diff($item, array (''));
					$items = array_merge($items, $item);
					break;
				case 'string':
					$items[] = $item;
					break;
				default:
					break;
			endswitch;
			return $items;
		}

		/**
		 * Удаляет элементы массива по значению.
		 * @param array        &$array Исходный массив.
		 * @param string|array $values Удаляемое значение или массив значений.
		 */
		static private function DelFromArray(&$array, $values) {
			if (is_array($values)):
				foreach ($values as $value):
					if ($id = array_search($value, $array)) unset($array[$id]);
				endforeach;
			else:
				if ($id = array_search($values, $array)) unset($array[$id]);
			endif;
		}

		/**
		 * Возвращает размер файла в человеко-понятном виде.
		 * @param  integer $bytes    Размер файла в байтах.
		 * @param  integer $decimals Количество десятичных знаков.
		 * @return string
		 */
		static private function HumanFileSize($bytes, $decimals = 2) {
			$size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
			$factor = floor((strlen($bytes) - 1) / 3);
			return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
		}

		/**
		 * Преобразует массив в строку, подставляя соответствующие теги.
		 * @param  array  $array Массив значений.
		 * @return string        Значения в виде строки с тегами.
		 */
		static private function ArrayValueToString($array) {
			if (empty($array)) return '';
			$output = "\r\n" . '<ul>' . "\r\n";
			foreach ($array as $key => $val):
				if (is_array($val)):
					if (!empty($val)):
						$output .= '<li>';
						if (is_string($key)) $output .= '<b>' . $key . '</b>: ';
						$output .= self::ArrayValueToString($val);
						$output .= '</li>' . "\r\n";
					endif;
				else:
					$output .= '<li>';
					if (is_string($key)) $output .= '<b>' . htmlspecialchars($key) . '</b>: ';
					$output .= $val;
					$output .= '</li>' . "\r\n";
				endif;
			endforeach;
			$output .= '</ul>' . "\r\n";
			return $output;
		}

/* END - Универсальные статические функции */

		/**
		 * Предварительная очистка базовых параметров.
		 */
		private function Reset() {
			$this->EraseErrors();
			$this->EraseFilters();
			$this->EraseTo();
			$this->EraseCopy();
			$this->EraseHidden();
		}

		/**
		 * Дополняет строку значениями согласно шаблона %key%. Также происходит замена по фиксированным константам.
		 * @param  string $text Исходная строка.
		 * @param  array  $data Массив параметров замен ('key' => 'value').
		 * @return string       Дополненная строка.
		 */
		private function AppendConstants($text, $data = array ()) {
			$constants = array (
				'host'	=>	$_SERVER['HTTP_HOST'],
			);
			$constants = array_merge($constants, $data);
			foreach ($constants as $key => $val):
				$text = str_replace('%' . $key . '%', $val, $text);
			endforeach;
			return $text;
		}

		/**
		 * Дополняет или заменяет элементы массива стандартных сообщений новыми элементами.
		 * @param  array $messages Дополняющий массив.
		 * @return array           Дополненный массив сообщений.
		 */
		private function MergeMessages($messages = array ()) {
			return array_merge($this->messages, $messages);
		}

		/**
		 * Устанавливает настройки приема нерегистрируемых данных из формы.
		 * @param boolean $other_get   Принимать GET параметры.
		 * @param boolean $other_post  Принимать POST параметры.
		 * @param boolean $other_files Принимать файлы.
		 */
		public function SetFieldOptions($other_get = false, $other_post = false, $other_files = false) {
			$this->other_get = $other_get;
			$this->other_post = $other_post;
			$this->other_files = $other_files;
		}

		/**
		 * Устанавливает настройки проверки на спам.
		 * @param boolean $check_referer Проверять на реферала.
		 * @param boolean $check_sid     Проверять на куки, сессию и скрипты.
		 */
		public function SetSpamOptions($check_referer = true, $check_sid = true) {
			$this->check_referer = $check_referer;
			$this->check_sid = $check_sid;
		}

		/**
		 * Добавляет путь поиска шаблона.
		 * @param  string  $path Путь.
		 * @return boolean       true, если путь существует, false, если директории нету.
		 */
		public function AddTemplatesPath($path) {
			$path = preg_replace('/[\/\\\]+/i', DIRECTORY_SEPARATOR, $path);
			if (is_dir($path)):
				$this->templates_path[] = $path;
				return true;
			endif;
			$new_path = $_SERVER['DOCUMENT_ROOT'] . $path;
			$new_path = preg_replace('/[\/\\\]+/i', DIRECTORY_SEPARATOR, $new_path);
			if (is_dir($new_path)):
				$this->templates_path[] = $new_path;
				return true;
			endif;
			return false;
		}

		/**
		 * Удаляет пути поиска шаблона.
		 */
		public function EraseTemplatesPath() {
			$this->templates_path = array ();
		}

		/**
		 * Устанавливает файл шаблона.
		 * @param  string  $template_name Имя шаблона или путь к файлу.
		 * @return boolean                true, если шаблрн установлен, false, если файл шаблона не существует.
		 */
		public function SetTemplateFile($template_name) {
			$template_name = preg_replace('/[\/\\\]+/i', DIRECTORY_SEPARATOR, $template_name);
			if (is_file($template_name)):
				$this->template_file = $template_name;
				return true;
			endif;
			foreach ($this->templates_path as $path):
				if (is_file($path . $template_name)):
					$this->template_file = $path . $template_name;
					return true;
				endif;
				if (is_file($path . $template_name . '.tpl')):
					$this->template_file = $path . $template_name . '.tpl';
					return true;
				endif;
			endforeach;
			return false;
		}

		/**
		 * Устанавливает шаблон в текстовом виде.
		 * @param string $template Текст шаблона.
		 */
		public function SetTemplateText($template) {
			$this->template_text = $template;
		}

/* START - Работа с фильтрами */

		/**
		 * Добавляет базовые фильтры полей.
		 */
		private function AddFilters() {
			$this->AddBaseFilter('email', '/^([a-zа-я0-9_\.-]+)@([a-zа-я0-9_\.-]+)\.([a-zа-я\.]{2,6})$/ui');
			$this->AddBaseFilter('phone', function (&$value) {
				$value = preg_replace('/\D+/', '', $value);
				return $value != '';
			});
		}

		/**
		 * Добавляет базовый фильтр поля для формата 'filter:name'.
		 * Если фильтр с заданным именем уже существует, то он будет заменен.
		 * @param string          $name   Имя фильтра.
		 * @param string|callable $filter Фильтр. Регулярное выражение или callback функция.
		 *                                Callback функция принимает 1 значение - значение поля.
		 *                                Если исходное значение поля нужно изменить, то можно использовать указатель.
		 *                                Возвращает true, если значение соответствует фильтру и false в противном случае.
		 */
		private function AddBaseFilter($name, $filter) {
			if (empty($name)) return false;
			$this->filters[$name] = (object) array (
				'base'		=>	true,
				'filter'	=>	$filter,
			);
		}

		/**
		 * Добавляет пользовательский фильтр поля для формата 'filter:name'.
		 * Если фильтр с заданным именем уже существует, то он будет заменен.
		 * @param string          $name   Имя фильтра.
		 * @param string|callable $filter Фильтр. Регулярное выражение или callback функция.
		 *                                Callback функция принимает 1 значение - значение поля.
		 *                                Если исходное значение поля нужно изменить, то можно использовать указатель.
		 *                                Возвращает true, если значение соответствует фильтру и false в противном случае.
		 */
		public function AddFilter($name, $filter) {
			if (empty($name)) return false;
			$this->filters[$name] = (object) array (
				'base'		=>	false,
				'filter'	=>	$filter,
			);
		}

		/**
		 * Удаляет фильтр поля для формата 'filter:name'.
		 * @param string $name Имя фильтра.
		 */
		public function DelFilter($name) {
			if (isset($this->filters[$name])) unset($this->filters[$name]);
		}

		/**
		 * Удаляет все заданные фильтры полей для формата 'filter:name'.
		 * @param boolean $del_base_filters Очищать также базовые фильтры.
		 */
		public function EraseFilters($del_base_filters = false) {
			if ($del_base_filters):
				$this->filters = array ();
			else:
				$filters = array ();
				foreach ($this->filters as $name => $filter):
					if ($filter->base) $filters[$name] = $filter;
				endforeach;
				$this->filters = $filters;
			endif;
		}

		/**
		 * Возвращает зарегистрированные фильтры полей для формата 'filter:name'.
		 * @param  string                  $filter_id ID фильтра. Если не задан, возвращает все фильтры.
		 * @return string|callable|boolean            регулярное выражение или callback функция.
		 *                                            В случае отсутствия фильтра с заданным $filter_id возвращает false.
		 */
		public function GetFilters($filter_id = '') {
			if (empty($filter_id)):
				return $this->filters;
			else:
				if (empty($this->filters[$filter_id])):
					return false;
				else:
					return $this->filters[$filter_id];
				endif;
			endif;
		}

/* END - Работа с фильтрами */

/* START - Работа с ошибками */

		/**
		 * Добавляет ошибку в стек.
		 * @param string  $message_id ID сообщения об ошибке.
		 * @param string  $field      Поле.
		 * @param string  $field_name Имя поля.
		 * @param string  $data       Пользовательские данные для подмены.
		 */
		private function AddError($message_id, $field = false, $field_name = '', $data = array ()) {
			if (empty($message_id)) return false;
			$error = array ();
			$message = null;
			if (!empty($field)) $message = $field->messages[$message_id];
			if (empty($message)) $message = $this->messages[$message_id];
			$message = $this->AppendConstants($message, $data);
			$error['message'] = $message;
			if ($field_name):
				$error['input'] = '[name=' . $field_name . ']';
				$error['field'] = $field_name;
			endif;
			$this->errors[] = $error;
		}

		/**
		 * Очищает стек ошибок.
		 */
		public function EraseErrors() {
			$this->errors = array ();
		}

		/**
		 * Возвращает список ошибок в стеке.
		 * @return array Список зарегистрированных ошибок. В случае отсутствия ошибок вернет пустой массив.
		 */
		private function GetErrors() {
			return $this->errors;
		}

/* END - Работа с ошибками */

/* START - Работа с заголовками почтового сообщения */

		/**
		 * Устанавливает разделитель блоков письма (используется только для писем со вложениями).
		 * @param string $separator Разделитель.
		 */
		public function SetMailSeparator($separator) {
			if (empty($separator)) return false;
			$this->mail_separator = $separator;
		}

		/**
		 * Устанавливает заголовок сообщения.
		 * @param string $subject Заголовок сообщения.
		 */
		public function SetSubject($subject) {
			$this->subject_raw = $subject;
		}

		/**
		 * Устанавливает почтовый адрес "от кого".
		 * @param string $email Почтовый адрес.
		 * @param string $title Подпись к почтовому адресу.
		 */
		public function SetFrom($email, $title = '') {
			if (empty($title)):
				$this->from = $email;
			else:
				$this->from = $title . ' <' . $email . '>';
			endif;
		}

		/**
		 * Добавляет почтовый адрес "кому".
		 * @param string|array $email Почтовый адрес или массив адресов.
		 */
		public function AddTo($email) {
			$this->to = self::Merge($this->to, $email);
		}

		/**
		 * Удаляет почтовый адрес из "кому".
		 * @param string|array $email Почтовый адрес или массив адресов.
		 */
		public function DelTo($email) {
			self::DelFromArray($this->to, $email);
		}

		/**
		 * Удаляет все почтовые адреса из "кому".
		 */
		public function EraseTo() {
			$this->to = array ();
		}

		/**
		 * Добавляет почтовый адрес "копия".
		 * @param string|array $email Почтовый адрес или массив адресов.
		 */
		public function AddCopy($email) {
			$this->copy = self::Merge($this->copy, $email);
		}

		/**
		 * Удаляет почтовый адрес из "копия".
		 * @param string|array $email Почтовый адрес или массив адресов.
		 */
		public function DelCopy($email) {
			self::DelFromArray($this->copy, $email);
		}

		/**
		 * Удаляет все почтовые адреса из "копия".
		 */
		public function EraseCopy() {
			$this->copy = array ();
		}

		/**
		 * Добавляет почтовый адрес "скрытая копия".
		 * @param string|array $email Почтовый адрес или массив адресов.
		 */
		public function AddHidden($email) {
			$this->hidden = self::Merge($this->hidden, $email);
		}

		/**
		 * Удаляет почтовый адрес из "скрытая копия".
		 * @param string|array $email Почтовый адрес или массив адресов.
		 */
		public function DelHidden($email) {
			self::DelFromArray($this->hidden, $email);
		}

		/**
		 * Удаляет все почтовые адреса из "скрытая копия".
		 */
		public function EraseHidden() {
			$this->hidden = array ();
		}

/* END - Работа с заголовками почтового сообщения */

/* START - Работа с контентом почтового сообщения */

		/**
		 * Добавляет поле формы, данные которого принимаются по POST.
		 * @param string          $name     Имя параметра
		 * @param string          $title    Заголовок параметра, если не задано, то не будет отправлено в письме
		 * @param integer         $require  Флаг обязательности поля, используются константы класса Mailer::REQUIRE_*
		 * @param string|callable $filter   Фильтр поля по значению.
		 *                                  Задается регулярное выражение, имя базового фильтра в формате filter:name или callback функция.
		 *                                  Callback функция принимает 1 значение - значение поля.
		 *                                  Если исходное значение поля нужно изменить, то можно использовать указатель.
		 *                                  Возвращает true, если значение соответствует фильтру и false в противном случае.
		 *                                  Если не задан, валидация не проводится.
		 * @param integer         $min      Минимальное количество символов в поле.
		 *                                  Если равняется нулю, то проверка не проводится
		 * @param integer         $max      Максимальное количество символов в поле.
		 *                                  Если равняется нулю, то проверка не проводится
		 * @param array           $messages Массив сообщений, привязанных к полю (ошибки и тп).
		 */
		public function AddPost($name, $title = '', $require = self::REQUIRE_IFEXISTS, $filter = self::FILTER_OFF, $min = 0, $max = 0, $messages = array ()) {
			if (empty($name)) return false;
			$this->fields[$name] = (object) array (
				'type'		=>	self::TYPE_POST,
				'title'		=>	$title,
				'require'	=>	$require,
				'filter'	=>	$filter,
				'min'		=>	$min,
				'max'		=>	$max,
				'messages'	=>	$messages,
				'value'		=>	null,
			);
		}

		/**
		 * Удаляет поле формы, данные которого принимаются по POST.
		 * @param  string  $name Имя поля.
		 * @return boolean       Возврашает true, если поле удалено, false, если такогого поля не существует.
		 */
		public function DelPost($name) {
			if (empty($this->fields[$name])) return false;
			if ($this->fields[$name]->type == self::TYPE_POST) return false;
			unset($this->fields[$name]);
			return true;
		}

		/**
		 * Удаляет все поля формы, данные которого принимаются по POST.
		 */
		public function ErasePost() {
			$output = array ();
			foreach ($this->fields as $key => $field):
				if ($field->type != self::TYPE_POST) $output[$key] = $field;
			endforeach;
			$this->fields = $output;
		}

		/**
		 * Возвращает поля формы, данные которого принимаются по POST.
		 * @param  string        $name Имя поля.
		 * @return array|boolean       Если не задан $name, возващает все поля.
		 *                             Если $name задан, то в случае успеха вернет соответствующее поле, иначе - false.
		 */
		public function GetPost($name = '') {
			if ($name == ''):
				$output = array ();
				foreach ($this->fields as $key => $field):
					if ($field->type == self::TYPE_POST) $output[$key] = $field;
				endforeach;
				return $output;
			endif;
			if (empty($this->fields[$name])):
				return false;
			else:
				if ($this->fields[$name]->type == self::TYPE_POST):
					return $this->fields[$name];
				else:
					return false;
				endif;
			endif;
		}

		/**
		 * Добавляет поле формы, данные которого принимаются по GET.
		 * @param string          $name     Имя параметра
		 * @param string          $title    Заголовок параметра, если не задано, то не будет отправлено в письме
		 * @param integer         $require  Флаг обязательности поля, используются константы класса Mailer::REQUIRE_*
		 * @param string|callable $filter   Фильтр поля по значению.
		 *                                  Задается регулярное выражение, имя базового фильтра в формате filter:name или callback функция.
		 *                                  Callback функция принимает 1 значение - значение поля.
		 *                                  Если исходное значение поля нужно изменить, то можно использовать указатель.
		 *                                  Возвращает true, если значение соответствует фильтру и false в противном случае.
		 *                                  Если не задан, валидация не проводится.
		 * @param integer         $min      Минимальное количество символов в поле.
		 *                                  Если равняется нулю, то проверка не проводится
		 * @param integer         $max      Максимальное количество символов в поле.
		 *                                  Если равняется нулю, то проверка не проводится
		 * @param array           $messages Массив сообщений, привязанных к полю (ошибки и тп).
		 */
		public function AddGet($name, $title = '', $require = self::REQUIRE_IFEXISTS, $filter = self::FILTER_OFF, $min = 0, $max = 0, $messages = array ()) {
			if (empty($name)) return false;
			$this->fields[$name] = (object) array (
				'type'		=>	self::TYPE_GET,
				'title'		=>	$title,
				'require'	=>	$require,
				'filter'	=>	$filter,
				'min'		=>	$min,
				'max'		=>	$max,
				'messages'	=>	$messages,
				'value'		=>	null,
			);
		}

		/**
		 * Удаляет поле формы, данные которого принимаются по GET.
		 * @param  string  $name Имя поля.
		 * @return boolean       Возврашает true, если поле удалено, false, если такогого поля не существует.
		 */
		public function DelGet($name) {
			if (empty($this->fields[$name])) return false;
			if ($this->fields[$name]->type == self::TYPE_GET) return false;
			unset($this->fields[$name]);
			return true;
		}

		/**
		 * Удаляет все поля формы, данные которого принимаются по GET.
		 */
		public function EraseGet() {
			$output = array ();
			foreach ($this->fields as $key => $field):
				if ($field->type != self::TYPE_GET) $output[$key] = $field;
			endforeach;
			$this->fields = $output;
		}

		/**
		 * Возвращает поля формы, данные которого принимаются по GET.
		 * @param  string        $name Имя поля.
		 * @return array|boolean       Если не задан $name, возващает все поля.
		 *                             Если $name задан, то в случае успеха вернет соответствующее поле, иначе - false.
		 */
		public function GetGet($name = '') {
			if ($name == ''):
				$output = array ();
				foreach ($this->fields as $key => $field):
					if ($field->type == self::TYPE_GET) $output[$key] = $field;
				endforeach;
				return $output;
			endif;
			if (empty($this->fields[$name])):
				return false;
			else:
				if ($this->fields[$name]->type == self::TYPE_GET):
					return $this->fields[$name];
				else:
					return false;
				endif;
			endif;
		}

		/**
		 * Добавляет пользовательские данные.
		 * @param string $title Название поля.
		 * @param string $value Значение поля.
		 */
		public function AddVariable($title, $value) {
			$this->fields[] = (object) array (
				'type'		=>	self::TYPE_VAR,
				'title'		=>	$title,
				'require'	=>	self::REQUIRE_NEVER,
				'filter'	=>	self::FILTER_OFF,
				'min'		=>	0,
				'max'		=>	0,
				'messages'	=>	array (),
				'value'		=>	$value,
			);
		}

		/**
		 * Удаляет пользовательльские данные письма.
		 * @param  string  $name ID данных.
		 * @return boolean       Возврашает true, если поле удалено, false, если такогого поля не существует.
		 */
		public function DelVariable($name) {
			if (empty($this->fields[$name])) return false;
			if ($this->fields[$name]->type == self::TYPE_VAR) return false;
			unset($this->fields[$name]);
			return true;
		}

		/**
		 * Удаляет все пользовательльские данные письма.
		 */
		public function EraseVariable() {
			$output = array ();
			foreach ($this->fields as $key => $field):
				if ($field->type != self::TYPE_VAR) $output[$key] = $field;
			endforeach;
			$this->fields = $output;
		}

		/**
		 * Возвращает пользовательльские данные письма.
		 * @param  integer       $name ID данных.
		 * @return array|boolean       Если не задан $name, возващает все поля.
		 *                             Если $name задан, то в случае успеха вернет соответствующее поле, иначе - false.
		 */
		public function GetVariable($name = '') {
			if ($name == ''):
				$output = array ();
				foreach ($this->fields as $key => $field):
					if ($field->type == self::TYPE_VAR) $output[$key] = $field;
				endforeach;
				return $output;
			endif;
			if (empty($this->fields[$name])):
				return false;
			else:
				if ($this->fields[$name]->type == self::TYPE_VAR):
					return $this->fields[$name];
				else:
					return false;
				endif;
			endif;
		}

		/**
		 * Добавляет разделитель в тело сообщения.
		 * @param string $separator Разделитель. Тег и/или текст.
		 */
		public function AddSeparator($separator = '<hr>') {
			$this->fields[] = (object) array (
				'type'		=>	self::TYPE_SEPARATOR,
				'title'		=>	$separator,
				'require'	=>	self::REQUIRE_NEVER,
				'filter'	=>	self::FILTER_OFF,
				'min'		=>	0,
				'max'		=>	0,
				'messages'	=>	array (),
				'value'		=>	'',
			);
		}

		/**
		 * Добавляет файловое поле формы.
		 * @param string  $name       Имя поля.
		 * @param integer $require    Флаг обязательности поля, используются константы класса Mailer::REQUIRE_*
		 * @param integer $minsize    Минимальный размер файла в байтах.
		 * @param integer $maxsize    Максимальны размер файла в байтах.
		 * @param array   $file_types Массив допустимых MIME-типов файлов.
		 * @param array   $messages   Массив сообщений, привязанных к полю (ошибки и тп).
		 */
		public function AddFile($name, $require = self::REQUIRE_IFEXISTS, $minsize = 0, $maxsize = 0, $file_types = array (), $messages = array ()) {
			if (empty($name)) return false;
			$this->files[$name] = (object) array (
				'require'	=>	$require,
				'minsize'	=>	$minsize,
				'maxsize'	=>	$maxsize,
				'types'		=>	$file_types,
				'messages'	=>	$messages,
				'value'		=>	null,
			);
		}

		/**
		 * Удаляет файловое поле формы.
		 * @param  string  $name Имя поля.
		 * @return boolean       Возврашает true, если файловое поле удалено, false, если такогого поля не существует.
		 */
		public function DelFile($name) {
			if (empty($this->files[$name])) return false;
			unset($this->files[$name]);
			return true;
		}

		/**
		 * Удаляет все файловые поля формы.
		 */
		public function EraseFiles() {
			$this->files = array ();
		}

		/**
		 * Возвращает файловые поля формы.
		 * @param  string         $name Имя поля.
		 * @return array|boolean        Если не задан $name, возващает все поля.
		 *                              Если $name задан, то в случае успеха вернет соответствующее поле, иначе - false.
		 */
		public function GetFile($name = '') {
			if (empty($name)) return $this->files;
			return empty($this->files[$name]) ? false : $this->files[$name];
		}

/* END - Работа с контентом почтового сообщения */

/* START - Проверка полей */

		/**
		 * Получает незарегистрированные поля из GET.
		 * @return array Возвращает массив полей.
		 */
		private function GetOtherGet()  {
			$output = array ();
			if (empty($_GET)) return $output;
			foreach ($_GET as $key => $val):
				if (isset($this->fields[$key]) && $this->fields[$key]->type == self::TYPE_GET) continue;
				if (!isset($val)) continue;
				$output[$key] = $val;
			endforeach;
			return $output;
		}

		/**
		 * Получает незарегистрированные поля из POST.
		 * @return array Возвращает массив полей.
		 */
		private function GetOtherPost()  {
			$output = array ();
			if (empty($_POST)) return $output;
			foreach ($_POST as $key => $val):
				if (isset($this->fields[$key]) && $this->fields[$key]->type == self::TYPE_POST) continue;
				if (!isset($val)) continue;
				$output[$key] = $val;
			endforeach;
			return $output;
		}

		/**
		 * Получает незарегистрированные файлы.
		 * @return array Возвращает массив полей.
		 */
		private function GetOtherFiles()  {
			$output = array ();
			if (empty($_FILES)) return $output;
			foreach ($_FILES as $key => $val):
				if (isset($this->files[$key])) continue;
				if (empty($val)) continue;
				if ($val['error'] == UPLOAD_ERR_NO_FILE) continue;
				$output[$key] = $val;
			endforeach;
			return $output;
		}

		/**
		 * Проверяет поле на количество символов. Если значение не соответствует, ошибка будет записана в стек класса.
		 * @param  string   $key    Имя поля.
		 * @param  stdClass &$field Поле
		 * @param  array    $data   Пользовательские данные для замены в сообщениях (ошибки и тп).
		 * @return boolean          Возвращает true, если ошибок нету, false, если ошибки существуют.
		 */
		private function CheckFieldCount($key, &$field, $data = array ()) {
			if (!empty($field->min)):
				if (strlen($field->value) < $field->min):
					$this->AddError('min', $field, $key, $data);
					return false;
				endif;
			endif;
			if (!empty($field->max)):
				if (strlen($field->value) > $field->max):
					$this->AddError('max', $field, $key, $data);
					return false;
				endif;
			endif;
			return true;
		}

		/**
		 * Проверяет поле на зарегистрированные фильтры. Если значение не соответствует, ошибка будет записана в стек класса.
		 * @param  string   $key    Имя поля.
		 * @param  stdClass &$field Поле
		 * @param  array    $data   Пользовательские данные для замены в сообщениях (ошибки и тп).
		 * @return boolean          Возвращает true, если ошибок нету, false, если ошибки существуют.
		 */
		private function CheckFieldFilter($key, &$field, $data = array ()) {
			if (is_callable($field->filter)):
				if ($field->filter($field->value)):
					return $this->CheckFieldCount($key, $field, $data);
				else:
					$this->AddError('filter', $field, $key, $data);
					return false;
				endif;
			elseif (is_string($field->filter)):
				if (preg_match('/^filter:([a-z0-9_-]+)/ui', $field->filter, $filter_name)):
					$filter_name = $filter_name[1];
					if (empty($this->filters[$filter_name])):
						return $this->CheckFieldCount($key, $field, $data);
					else:
						$filter = $this->filters[$filter_name]->filter;
						if (is_callable($filter)):
							if ($filter($field->value)):
								return $this->CheckFieldCount($key, $field, $data);
							else:
								$this->AddError('filter', $field, $key, $data);
								return false;
							endif;
						else:
							if (preg_match($filter, $field->value)):
								return $this->CheckFieldCount($key, $field, $data);
							else:
								$this->AddError('filter', $field, $key, $data);
								return false;
							endif;
						endif;
					endif;
				else:
					if (preg_match($field->filter, $field->value)):
						return $this->CheckFieldCount($key, $field, $data);
					else:
						$this->AddError('filter', $field, $key, $data);
						return false;
					endif;
				endif;
			endif;
			return true;
		}

		/**
		 * Проверяет поле на наличие данных. Если значение не соответствует, ошибка будет записана в стек класса.
		 * @param  string   $key    Имя поля.
		 * @param  stdClass &$field Поле
		 * @param  array    $data   Пользовательские данные для замены в сообщениях (ошибки и тп).
		 * @return boolean          Возвращает true, если ошибок нету, false, если ошибки существуют.
		 */
		private function CheckFieldEmpty($key, &$field, $data = array ()) {
			if (is_string($field->value)):
				$value = trim($field->value);
				if ($value == ''):
					$this->AddError('empty', $field, $key, $data);
					return false;
				else:
					if (empty($field->filter)):
						return $this->CheckFieldCount($key, $field, $data);
					else:
						return $this->CheckFieldFilter($key, $field, $data);
					endif;
				endif;
			else:
				$value = array_diff($field->value, array (''));
				if (empty($value)):
					$this->AddError('empty', $field, $key, $data);
					return false;
				else:
					return true;
				endif;
			endif;
			return true;
		}

		/**
		 * Проверяет поле на обязательность данных. Если значение не соответствует, ошибка будет записана в стек класса.
		 * @param  string   $key    Имя поля.
		 * @param  stdClass &$field Поле
		 * @param  array    $data   Пользовательские данные для замены в сообщениях (ошибки и тп).
		 * @return boolean          Возвращает true, если ошибок нету, false, если ошибки существуют.
		 */
		private function CheckFieldRequire($key, &$field, $data = array ()) {
			switch ($field->require):
				case Mailer::REQUIRE_ALWAYS:
					if (isset($field->value)):
						return $this->CheckFieldEmpty($key, $field, $data);
					else:
						$this->AddError('require', $field, $key, $data);
						return false;
					endif;
					break;
				case Mailer::REQUIRE_IFEXISTS:
					if (isset($field->value)):
						return $this->CheckFieldEmpty($key, $field, $data);
					endif;
						return true;
					break;
				default:
					return true;
					break;
			endswitch;
			return true;
		}

		/**
		 * Запускает процесс проверки полей формы.
		 */
		private function CheckFields() {
			foreach ($this->fields as $key => &$field):
				if ($field->type != self::TYPE_POST && $field->type != self::TYPE_GET) continue;
				if ($field->type == self::TYPE_POST):
					$field->value = $_POST[$key];
				elseif ($field->type == self::TYPE_GET):
					$field->value = $_GET[$key];
				endif;
				$fields_data = array (
					'field_name'	=>	$key,
					'field_title'	=>	$field->title,
					'field_min'		=>	$field->min,
					'field_max'		=>	$field->max,
				);
				$this->CheckFieldRequire($key, $field, $fields_data);
			endforeach;
		}

		/**
		 * Проверяет на наличие темы письма.
		 * Если тема письма не задана, то ошибка будет записана в стек.
		 */
		private function CheckSubject() {
			if (empty($this->subject_raw)) $this->AddError('subject');
		}

		/**
		 * Проверяет на наличие почтовых адресов "от кого" и "кому".
		 *
		 */
		private function CheckEmails() {
			if (empty($this->from)) $this->AddError('email_from');
			if (empty($this->to)) $this->AddError('email_to');
		}

/* END - Проверка полей */

/* START - Проверка файлов-вложений */

		/**
		 * Проверяет файловое поле на размер. Если значение не соответствует, ошибка будет записана в стек класса.
		 * @param  string   $key    Имя поля.
		 * @param  stdClass &$field Поле
		 * @param  array    $data   Пользовательские данные для замены в сообщениях (ошибки и тп).
		 * @return boolean          Возвращает true, если ошибок нету, false, если ошибки существуют.
		 */
		private function CheckFilesSize($key, &$field, $data = array ()) {
			if (!empty($field->minsize)):
				if ($field->value['size'] < $field->minsize):
					$this->AddError('minsize', $field, $key, $data);
					return false;
				endif;
			endif;
			if (!empty($field->maxsize)):
				if ($field->value['size'] > $field->maxsize):
					$this->AddError('maxsize', $field, $key, $data);
					return false;
				endif;
			endif;
			return true;
		}

		/**
		 * Проверяет файловое поле на тип. Если значение не соответствует, ошибка будет записана в стек класса.
		 * @param  string   $key    Имя поля.
		 * @param  stdClass &$field Поле
		 * @param  array    $data   Пользовательские данные для замены в сообщениях (ошибки и тп).
		 * @return boolean          Возвращает true, если ошибок нету, false, если ошибки существуют.
		 */
		private function CheckFilesType($key, &$field, $data = array ()) {
			if (empty($field->types)):
				return $this->CheckFilesSize($key, $field, $data);
			else:
				if (in_array($field->value['type'], $field->types)):
					return $this->CheckFilesSize($key, $field, $data);
				else:
					$this->AddError('filetype', $field, $key, $data);
					return false;
				endif;
			endif;
		}

		/**
		 * Проверяет файловое поле на ошибки загрузки. Если значение не соответствует, ошибка будет записана в стек класса.
		 * @param  string   $key    Имя поля.
		 * @param  stdClass &$field Поле
		 * @param  array    $data   Пользовательские данные для замены в сообщениях (ошибки и тп).
		 * @return boolean          Возвращает true, если ошибок нету, false, если ошибки существуют.
		 */
		private function CheckFilesError($key, &$field, $data = array ()) {
			switch ($field->value['error']) {
				case UPLOAD_ERR_OK:
					return $this->CheckFilesType($key, $field, $data);
					break;
				case UPLOAD_ERR_INI_SIZE:
					$this->AddError('ini_maxsize', $field, $key, $data);
					return false;
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$this->AddError('form_maxsize', $field, $key, $data);
					return false;
					break;
				case UPLOAD_ERR_PARTIAL:
					$this->AddError('partial', $field, $key, $data);
					return false;
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$this->AddError('no_tmp_dir', $field, $key, $data);
					return false;
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$this->AddError('cant_write', $field, $key, $data);
					return false;
					break;
				case UPLOAD_ERR_EXTENSION:
					$this->AddError('php_ext', $field, $key, $data);
					return false;
					break;
				default:
					$this->AddError('unknown', $field, $key, $data);
					return false;
					break;
			}
		}

		/**
		 * Проверяет файловое поле на наличие данных. Если значение не соответствует, ошибка будет записана в стек класса.
		 * @param  string   $key    Имя поля.
		 * @param  stdClass &$field Поле
		 * @param  array    $data   Пользовательские данные для замены в сообщениях (ошибки и тп).
		 * @return boolean          Возвращает true, если ошибок нету, false, если ошибки существуют.
		 */
		private function CheckFilesEmpty($key, &$field, $data = array ()) {
			if ($field->value['error'] == UPLOAD_ERR_NO_FILE):
				$this->AddError('empty', $field, $key, $data);
				return false;
			else:
				return $this->CheckFilesError($key, $field, $data);
			endif;
		}

		/**
		 * Проверяет файловое поле на существование. Если значение не соответствует, ошибка будет записана в стек класса.
		 * @param  string   $key    Имя поля.
		 * @param  stdClass &$field Поле
		 * @param  array    $data   Пользовательские данные для замены в сообщениях (ошибки и тп).
		 * @return boolean          Возвращает true, если ошибок нету, false, если ошибки существуют.
		 */
		private function CheckFilesRequire($key, &$field, $data = array ()) {
			switch ($field->require):
				case Mailer::REQUIRE_ALWAYS:
					if (isset($field->value)):
						return $this->CheckFilesEmpty($key, $field, $data);
					else:
						$this->AddError('require', $field, $key, $data);
						return false;
					endif;
					break;
				case Mailer::REQUIRE_IFEXISTS:
					if (isset($field->value)):
						return $this->CheckFilesEmpty($key, $field, $data);
					endif;
						return true;
					break;
				default:
					return true;
					break;
			endswitch;
			return true;
		}

		/**
		 * Запускает процесс проверки файловых полей.
		 */
		private function CheckFiles() {
			foreach ($this->files as $key => &$field):
				$field->value = $_FILES[$key];
				$fields_data = array (
					'field_name'	=>	$key,
					'minsize'		=>	self::HumanFileSize($field->minsize),
					'maxsize'		=>	self::HumanFileSize($field->maxsize),
					'ini_maxsize'	=>	ini_get('upload_max_filesize'),
					'form_maxsize'	=>	self::HumanFileSize($_POST['MAX_FILE_SIZE']),
					'filetypes'		=>	implode(', ', $field->types),
				);
				$this->CheckFilesRequire($key, $field, $fields_data);
			endforeach;
		}

/* END - Проверка файлов-вложений */

		public function AddCallbackBeforeSend($callback, $data = array ()) {
			if (!is_callable($callback)) return false;
			$this->callbacks_before_send[] = (object) array (
				'callback'	=>	$callback,
				'data'		=>	$data,
			);
			return true;
		}

		public function AddCallbackAfterSend($callback, $data = array ()) {
			if (!is_callable($callback)) return false;
			$this->callbacks_after_send[] = (object) array (
				'callback'	=>	$callback,
				'data'		=>	$data,
			);
			return true;
		}

		public function AddCallbackBeforeSuccess($callback, $data = array ()) {
			if (!is_callable($callback)) return false;
			$this->callbacks_before_success[] = (object) array (
				'callback'	=>	$callback,
				'data'		=>	$data,
			);
			return true;
		}

		public function AddCallbackAfterSuccess($callback, $data = array ()) {
			if (!is_callable($callback)) return false;
			$this->callbacks_after_success[] = (object) array (
				'callback'	=>	$callback,
				'data'		=>	$data,
			);
			return true;
		}

		public function AddCallbackAfterError($callback, $data = array ()) {
			if (!is_callable($callback)) return false;
			$this->callbacks_after_error[] = (object) array (
				'callback'	=>	$callback,
				'data'		=>	$data,
			);
			return true;
		}

		/**
		 * Подготавливает тему сообщения.
		 */
		private function PrepareSubject() {
			if (empty($this->subject_raw)) $this->subject_raw = 'Заявка с сайта ' . $_SERVER['HTTP_HOST'];
			$subject = $this->AppendConstants($this->subject_raw);
			$this->subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
		}

		/**
		 * Подготавливает заголовок сообщения.
		 */
		private function PrepareHeader() {
			$this->header = "MIME-Version: 1.0\r\n";
			if (empty($this->files)):
				$this->header .= "Content-type: text/html; charset=utf-8\r\n";
			else:
				$this->header .= "Content-Type: multipart/mixed; boundary=\"" . $this->mail_separator . "\"\r\n";
			endif;
			$this->header .= "From: " . $this->AppendConstants($this->from) . "\r\n";
			if (!empty($this->copy)) $this->header .= "Cc: " . implode(',', $this->copy) . "\r\n";
			if (!empty($this->hidden)) $this->header .= "Bcc: " . implode(',', $this->hidden) . "\r\n";
		}

		/**
		 * Подменяет шаблонные ключи в тексте на значения полей.
		 * @param  string $text Исходный текст.
		 * @return string       Текст с заменами.
		 */
		private function ReplaceBodyVariables($text) {
			$output = $this->AppendConstants($text);
			foreach ($this->fields as $key => $field):
				$val = is_string($field->value) ? htmlspecialchars($val) : self::ArrayValueToString($field->value);
				$output = str_replace('%' . $key . '%', $val, $output);
			endforeach;
			if ($this->other_get && !empty($_GET)):
				foreach ($_GET as $key => $val):
					if (isset($this->fields[$key]) && $this->fields[$key]->type == self::TYPE_GET) continue;
					if (!isset($val)) continue;
					$val = is_string($val) ? htmlspecialchars($val) : self::ArrayValueToString($val);
					$output = str_replace('%' . $key . '%', $val, $output);
				endforeach;
			endif;
			if ($this->other_post && !empty($_POST)):
				foreach ($_POST as $key => $val):
					if (isset($this->fields[$key]) && $this->fields[$key]->type == self::TYPE_POST) continue;
					if (!isset($val)) continue;
					$val = is_string($val) ? htmlspecialchars($val) : self::ArrayValueToString($val);
					$output = str_replace('%' . $key . '%', $val, $output);
				endforeach;
			endif;
			return $output;
		}

		/**
		 * Преобразует значения полей в тело сообщения.
		 */
		private function GetBody() {
			if (!empty($this->template_text)):
				$output = $this->template_text . "\r\n";
				$output = $this->ReplaceBodyVariables($output);
			elseif (!empty($this->template_file)):
				$output = file_get_contents($this->template_file) . "\r\n";
				$output = $this->ReplaceBodyVariables($output);
			else:
				$output = '<h1>' . $this->AppendConstants($this->subject_raw) . '</h1><hr>' . "\r\n";
				foreach ($this->fields as $key => $field):
					if (empty($field->title)) continue;
					if (!isset($field->value)) continue;
					if ($field->type == self::TYPE_SEPARATOR):
						$output .= $field->title . "\r\n";
					else:
						if (is_string($field->value)):
							$output .= '<div><b>' . $field->title . '</b>: ' . htmlspecialchars($field->value) . '</div>' . "\r\n";
						else:
							$output .= '<div><b>' . $field->title . '</b>: ' . self::ArrayValueToString($field->value) . '</div>' . "\r\n";
						endif;
					endif;
				endforeach;
				if ($this->other_get):
					$fields = $this->GetOtherGet();
					if (!empty($fields)) $output .= '<hr>' . "\r\n";
					foreach ($fields as $key => $val):
						if (is_string($val)):
							$output .= '<div><b>' . $key . '</b>: ' . htmlspecialchars($val) . '</div>' . "\r\n";
						else:
							$output .= '<div><b>' . $key . '</b>: ' . self::ArrayValueToString($val) . '</div>' . "\r\n";
						endif;
					endforeach;
				endif;
				if ($this->other_post):
					$fields = $this->GetOtherPost();
					if (!empty($fields)) $output .= '<hr>' . "\r\n";
					foreach ($fields as $key => $val):
						if (is_string($val)):
							$output .= '<div><b>' . $key . '</b>: ' . htmlspecialchars($val) . '</div>' . "\r\n";
						else:
							$output .= '<div><b>' . $key . '</b>: ' . self::ArrayValueToString($val) . '</div>' . "\r\n";
						endif;
					endforeach;
				endif;
			endif;
			return $output;
		}

		/**
		 * Подготавливает тело сообщения.
		 */
		private function PrepareBody() {
			if (empty($this->files)):
				$this->body = $this->GetBody();
			else:
				$this->body = "--" . $this->mail_separator . "\r\n";
				$this->body .= "Content-type: text/html; charset=\"utf-8\"\r\n";
				$this->body .= "Content-Transfer-Encoding: Quot-Printed\r\n\r\n";
				$this->body .= $this->GetBody() . "\r\n";
				foreach ($this->files as $field):
					if (empty($field->value['size'])) continue;
					$file = fopen($field->value['tmp_name'], "rb");
					$cont = fread($file, $field->value['size']);
					fclose($file);
					$this->body .= "--" . $this->mail_separator . "\r\n";
					$this->body .= "Content-Type: application/octet-stream;name==?UTF-8?B?" . base64_encode($field->value['name']) . "?=\r\n";
					$this->body .= "Content-Transfer-Encoding:base64\r\n";
					$this->body .= "Content-Disposition:attachment\r\n\r\n";
					$this->body .= chunk_split(base64_encode($cont)) . "\r\n\r\n";
				endforeach;
				if ($this->other_files):
					$files = $this->GetOtherFiles();
					foreach ($files as $file_data):
						if (empty($file_data['size'])) continue;
						$file = fopen($file_data['tmp_name'], "rb");
						$cont = fread($file, $file_data['size']);
						fclose($file);
						$this->body .= "--" . $this->mail_separator . "\r\n";
						$this->body .= "Content-Type: application/octet-stream;name==?UTF-8?B?" . base64_encode($file_data['name']) . "?=\r\n";
						$this->body .= "Content-Transfer-Encoding:base64\r\n";
						$this->body .= "Content-Disposition:attachment\r\n\r\n";
						$this->body .= chunk_split(base64_encode($cont)) . "\r\n\r\n";
					endforeach;
				endif;
				$this->body .= $this->mail_separator . "--\r\n";
			endif;
		}

		/**
		 * Проверка на реферала. Отсекает заявки с сайтов, домен которых отличается от текущего.
		 * @return boolean    Возвращает true, если ошибки нету или проверка отключена, и false, если произошла ошибка.
		 */
		private function CheckReferer() {
			if (!$this->check_referer) return true;
			$ref = $_SERVER['HTTP_REFERER'];
			$ref_host = parse_url($ref, PHP_URL_HOST);
			if ($ref_host != $_SERVER['HTTP_HOST']) {
				$this->AddError('spam');
				return false;
			}
			return true;
		}

		/**
		 * Проверка на спам. Проверка на куки, сессии и скрипты.
		 * @return boolean    Возвращает true, если ошибки нету или проверка отключена, и false, если произошла ошибка.
		 */
		private function CheckSID () {
			if (!$this->check_sid) return true;
			$sid_hash = empty($_SESSION['FSID']) ? '' : $_SESSION['FSID'];
			$sid = $_POST['FSID'];
			if (empty($sid_hash) || empty($sid) || $sid_hash != md5($sid)) {
				$this->AddError('spam');
				return false;
			}
			return true;
		}

		/**
		 * Отправляет сообщение.
		 */
		public function Send() {
			$this->EraseErrors();

			foreach ($this->callbacks_before_send as $item):
				$callback = $item->callback;
				$return = $callback($item->data);
				return $return;
			endforeach;

			$this->CheckFields();
			$this->CheckFiles();
			$this->CheckSubject();
			$this->CheckEmails();
			$this->CheckReferer();
			$this->CheckSID();

			if (empty($this->errors)):
				$this->PrepareSubject();
				$this->PrepareHeader();
				$this->PrepareBody();
				$sended = mail(implode(',', $this->to), $this->subject, $this->body, $this->header);
				if ($sended):
					foreach ($this->callbacks_before_success as $item):
						$callback = $item->callback;
						$return = $callback($item->data);
					endforeach;

					$this->result = array (
						'status'	=> self::STATUS_SUCCESS,
						'message'	=> $this->messages['success'],
						'warning'	=> $this->warnings,
						'from'		=> $this->AppendConstants($this->from),
						'to'		=> implode(',', $this->to),
						'errors'	=> array (),
					);

					foreach ($this->callbacks_after_success as $item):
						$callback = $item->callback;
						$return = $callback($item->data);
					endforeach;
				else:
					$error = error_get_last();
					$this->result = array (
						'status'	=> self::STATUS_ERROR,
						'message'	=> $error['message'],
						'warning'	=> $this->warnings,
						'from'		=> $this->AppendConstants($this->from),
						'to'		=> implode(',', $this->to),
						'errors'	=> array (),
					);

					foreach ($this->callbacks_after_error as $item):
						$callback = $item->callback;
						$return = $callback($item->data);
					endforeach;
				endif;
			else:
				$errors = $this->GetErrors();
				$error = $errors[0];
				$this->result = array (
					'status'	=> self::STATUS_ERROR,
					'message'	=> $error['message'],
					'warning'	=> $this->warnings,
					'from'		=> $this->AppendConstants($this->from),
					'to'		=> implode(',', $this->to),
				);
				if (isset($error['field'])) $this->result['field'] = $error['field'];
				if (isset($error['input'])) $this->result['input'] = $error['input'];
				if (!empty($errors)) $this->result['errors'] = $errors;

				foreach ($this->callbacks_after_error as $item):
					$callback = $item->callback;
					$return = $callback($item->data);
				endforeach;
			endif;

			foreach ($this->callbacks_after_send as $item):
				$callback = $item->callback;
				$this->result = $callback($this->result, $item->data);
			endforeach;
			return json_encode($this->result);
		}
	}

?>