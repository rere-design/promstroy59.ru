<?php

	define('LC_GT_SINGLE',    0);
	define('LC_GT_TABLE',     1);
	define('LC_GT_LIST',      2);

	define('LC_FT_TEXT',      0);
	define('LC_FT_PASSWORD',  1);
	define('LC_FT_CHECKBOX',  2);
	define('LC_FT_RADIO',     3);
	define('LC_FT_TEXTAREA',  4);
	define('LC_FT_IMG',       5);
	define('LC_FT_FILE',      6);
	define('LC_FT_NUMBER',    7);
	define('LC_FT_EMAIL',     8);
	define('LC_FT_RANGE',     9);
	define('LC_FT_TEL',      10);
	define('LC_FT_URL',      11);
	define('LC_FT_IMGLIST',  12);
	define('LC_FT_FILELIST', 13);
	define('LC_FT_SELECT',   14);
	define('LC_FT_COMBOBOX', 14);
	define('LC_FT_LISTBOX',  15);
	define('LC_FT_LIST',     16);
	define('LC_FT_TABLE',    17);
	define('LC_FT_WYSIWYG',  18);
	define('LC_FT_TEXTLIST', 19);
	define('LC_FT_DATE',     20);
	define('LC_FT_MONTH',    21);
	define('LC_FT_WEEK',     22);
	define('LC_FT_DATETIME', 23);
	define('LC_FT_TIME',     24);
	define('LC_FT_YMAP',     25);
	define('LC_FT_COLOR',    26);

	define('LC_USER_NA',      0);
	define('LC_USER_SA',      1);
	define('LC_USER_ADMIN',   2);

	Class LandingCms {
		public $version = '2.5';
		public $data_dir = 'data';
		public $data_file = 'cms_data.inc';
		public $upload_dir = 'data/upload';
		public $login = 'admin';
		public $password = 'admin';
		public $required = ' <sup title="Поле обязательно для заполнения">*</sup>';
		private $users = array ();
		private $data = array ();
		private $upload_path = 'uploads';
		private $fields = array ();
		private $saved = array ();
		private $current = array ();

		public function __construct() {
		}

		private function prepare() {
			$this->upload_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $this->upload_dir . DIRECTORY_SEPARATOR;
			$this->data_file = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $this->data_dir . DIRECTORY_SEPARATOR . $this->data_file;
			if (!file_exists($this->data_file)) {
				$dir = dirname($this->data_file);
				if (!file_exists($dir)) mkdir($dir, 0777, true);
				$file = fopen($this->data_file, "x");
				fwrite($file, '{}');
				fclose($file);
			}
		}

		public function init() {
			$this->prepare();
			$data = file_get_contents($this->data_file);
			if ($data) {
				$data = unserialize($data);
				$this->saved = $data;
			}
			$this->get_data();
			//vd::dump($this->data);

			if ($this->logged()) {
				$this->restructure_files();
				$this->save();
				$this->logout();
			} else {
				$this->login();
			}
		}

		public function logged() {
			return isset($_SESSION['login']) && isset($_SESSION['password']) && $_SESSION['password'] == $this->users[$_SESSION['login']]->pass;
		}

		public function the_login_form() {
			echo '<div class="login_form">';
			echo '<form action="" method="post">';
			if (!empty($_POST['cms_login'])) echo '<div class="error_mess">Неверная пара логин - пароль</div>';
			echo '<div class="item"><label>Логин: </label><input type="text" name="login" value="' . $_POST['login'] . '"></div>';
			echo '<div class="item"><label>Пароль: </label><input type="password" name="password"></div>';
			echo '<div class="button"><button type="submit" name="cms_login" value="1"><span>Войти</span></button></div>';
			echo '</form></div>';
		}

		private function login() {
			if (empty($_POST['cms_login'])) return false;
			$_SESSION['login'] = md5(strtolower($_POST['login']));
			$_SESSION['password'] = md5($_POST['password']);
			if ($this->logged()) header('Location: /cms');
		}

		private function logout() {
			if (empty($_POST['cms_logout'])) return false;
			unset($_SESSION['login']);
			unset($_SESSION['password']);
			header('Location: /cms');
		}

		public function add_user($login, $pass, $group = LC_USER_ADMIN) {
			if (empty($login)) return false;
			if (empty($pass)) return false;
			if (empty($group)) return false;
			$this->users[md5(strtolower($login))] = (object) array (
				'pass'	=>	md5($pass),
				'group'	=>	$group,
			);
		}

		public function get_user_group() {
			if (empty($_SESSION['login']) || empty($_SESSION['password'])):
				return LC_USER_NA;
			else:
				if (empty($this->users[$_SESSION['login']]) || empty($this->users[$_SESSION['login']]->group)):
					return LC_USER_NA;
				else:
					return $this->users[$_SESSION['login']]->group;
				endif;
			endif;
		}

		private function restructure_files() {
			if (empty($_FILES['data']) || gettype($_FILES['data']) !== 'array') return false;
			$output = array ();
			foreach ($_FILES['data'] as $file_key => $file_value):
				foreach ($file_value as $skey => $section):
					foreach ($section as $gkey => $group):
						foreach ($group as $id => $item):
							if (gettype($item) == 'array'):
								foreach ($item as $fkey => $field):
									if (gettype($field) == 'array'):
										foreach ($field as $fid => $fval):
											$output[$skey][$gkey][$id][$fkey][$fid][$file_key] = $fval;
										endforeach;
									else:
										$output[$skey][$gkey][$id][$fkey][$file_key] = $field;
									endif;
								endforeach;
							else:
								$output[$skey][$gkey][$id][$file_key] = $item;
							endif;
						endforeach;
					endforeach;
				endforeach;
			endforeach;
			$_FILES['data'] = $output;
		}

		public function get_data_array() {
			return $this->data;
		}

		public function get_data_object() {
			$output = json_encode($this->data);
			$output = json_decode($output);
			return $output;
		}

		public function set_current($section, $group = '') {
			$this->current = array (
				'section'	=>	$section,
				'group'		=>	$group
			);
		}

		public function add_section($name, $title = false, $visible = true) {
			if (!$title) $title = $name;
			if ($this->get_user_group() == LC_USER_SA) $visible = true;

			$this->fields[$name] = array (
				'title'		=>	$title,
				'visible'	=>	$visible,
				'groups'	=>	array (),
			);
			$this->set_current($name);
			return $this->current;
		}

		public function add_group($name, $title = false, $type = LC_GT_SINGLE, $parent = array (), $head_field = '', $options = array ()) {
			if (!$title) $title = $name;
			$section = (empty($parent)) ? $this->current['section'] : $parent['section'];
			if (!$section) return $this->current;

			$this->fields[$section]['groups'][$name] = array (
				'title'		=>	$title,
				'type'		=>	$type,
				'fields'	=>	array (),
			);
			if ($head_field) $this->fields[$section]['groups'][$name]['head'] = $head_field;
			$this->set_current($section, $name);
			switch ($type) {
				case LC_GT_LIST:
					$this->add_field('active', 'Активность', LC_FT_CHECKBOX, array (
						'default'	=>	true,
						'label'		=>	'Активен',
					));
					break;
				case LC_GT_TABLE:
					$this->add_field('active', 'Акт.', LC_FT_CHECKBOX, array (
						'default'	=>	true,
					));
					break;
				default:
					break;
			}
			return $this->current;
		}

		public function add_field($name, $title = false, $type = LC_FT_TEXT, $options = array (), $parent = array (), $separator = false) {
			if (!$title) $title = $name;
			$section = (empty($parent)) ? $this->current['section'] : $parent['section'];
			$group = (empty($parent)) ? $this->current['group'] : $parent['group'];
			if (!$section || !$group) return false;

			if (empty($options['desc'])):
				$desc = '';
			else:
				$desc = $options['desc'];
				unset($options['desc']);
			endif;
			$this->fields[$section]['groups'][$group]['fields'][$name] = array (
				'title'		=>	$title,
				'type'		=>	$type,
				'options'	=>	$options,
				'separator'	=>	$separator,
				'desc'		=>	$desc,
			);
			return true;
		}

		private function get_data() {
			$this->data = array ();
			foreach ($this->fields as $skey => $section):
				$this->data[$skey] = array ();
				foreach ($section['groups'] as $gkey => $group):
					if ($group['type'] == LC_GT_SINGLE):
						foreach ($group['fields'] as $fkey => $field):
							$this->data[$skey][$gkey][$fkey] = isset($this->saved[$skey][$gkey][$fkey]) ? $this->saved[$skey][$gkey][$fkey] : $this->fields[$skey]['groups'][$gkey]['fields'][$fkey]['options']['default'];
						endforeach;
					else:
						$this->data[$skey][$gkey] = empty($this->saved[$skey][$gkey]) ? array () : $this->saved[$skey][$gkey];
					endif;
				endforeach;
			endforeach;
		}

		private function the_section_menu() {
			echo '<nav class="sections"><ul>';
			foreach ($this->fields as $skey => $section):
				if ($section['visible']) echo '<li><a href="#nav_' . $skey . '">' . $section['title'] . '</a></li>';
			endforeach;
			echo '</ul></nav>';
		}

		private function the_group_menu($skey) {
			echo '<nav class="groups"><ul>';
			foreach ($this->fields[$skey]['groups'] as $gkey => $group):
				echo '<li><a href="#nav_' . $skey . '_' . $gkey . '">' . $group['title'] . '</a></li>';
			endforeach;
			echo '</ul></nav>';
		}

		private function the_cp_menu() {
			echo '<div class="cp_menu">';
			echo '<div class="left">';
			echo '<a href="/" target="_blank">Главная</a>';
			echo '</div>';
			echo '<div class="right">';
			echo '<form acion="" method="post"><button type="submit" name="cms_logout" value="1">Выход из панели</button></form>';
			echo '</div>';
			echo '</div>';
		}

		private function gt_single($skey, $gkey) {
			$fields = $this->fields[$skey]['groups'][$gkey]['fields'];
			foreach ($fields as $fkey => $field):
				$f_key = $skey . '_' . $gkey . '_' . $fkey;
				$required = (empty($field['options']['required'])) ? '' : $this->required;
				echo '<div class="single_field ' . $f_key . '">';
				echo '<div class="title">' . $field['title'] . $required . ':' . ($field['desc'] ? '<div class="desc">' . $field['desc'] . '</div>' : '') . '</div>';
				echo '<div class="item">' . $this->get_field($skey, $gkey, $fkey) . '</div>';
				echo '</div>';
				if ($field['separator']) echo '<hr class="separator">';
			endforeach;
		}

		private function gt_table($skey, $gkey) {
			$fields = $this->fields[$skey]['groups'][$gkey]['fields'];
			echo '<table class="params"><thead><tr><td class="move_item"></td><td class="edit"></td><td class="num">#</td>';
			foreach ($fields as $fkey => $field):
				$f_key = $skey . '_' . $gkey . '_' . $fkey;
				$required = (empty($field['options']['required'])) ? '' : $this->required;
				echo '<td class="' . $f_key . '">' . $field['title'] . $required . ($field['desc'] ? '<div class="desc">' . $field['desc'] . '</div>' : '') . '</td>';
			endforeach;
			echo '</tr></thead><tfoot><tr><td colspan="' . (count($fields) + 3) . '"><a class="add" href="#add" title="Добавить строку">Добавить</a></td></tr></tfoot><tbody>';
			foreach ($this->data[$skey][$gkey] as $id => $item):
				$active_class = empty($item['active']) ? ' disabled' : '';
				echo '<tr class="table_item' . $active_class . '"><td class="move_item"></td><td class="edit"><a class="delete" href="#delete" title="Удалить строку">Удалить</a></td><td class="num">' . ($id + 1) . '</td>';
				foreach ($fields as $fkey => $field):
					echo '<td>' . $this->get_field($skey, $gkey, $fkey, $id) . '</td>';
				endforeach;
				echo '</tr>';
			endforeach;
			echo '</tbody></table><table class="dot" style="display:none;">';
			echo '<tr class="table_item"><td class="move_item"></td><td class="edit"><a class="delete" href="#delete" title="Удалить строку">Удалить</a></td><td class="num"></td>';
				foreach ($fields as $fkey => $field):
					echo '<td>' . $this->get_field($skey, $gkey, $fkey, false, true) . '</td>';
				endforeach;
			echo '</tr></table>';
		}

		private function gt_list($skey, $gkey) {
			$fields = $this->fields[$skey]['groups'][$gkey]['fields'];
			echo '<div class="params">';
			$head = $this->fields[$skey]['groups'][$gkey]['head'];
			foreach ($this->data[$skey][$gkey] as $id => $item):
				$active_class = empty($item['active']) ? ' disabled' : '';
				if (empty($head)):
					$htitle = '';
				else:
					if (is_array($head)):
						$htitle = '';
						foreach ($head as $i => $h):
							if (is_array($h)):
								if (isset($h[$item[$i]])):
									$htitle_temp = $h[$item[$i]];
								else:
									$htitle_true = empty($h[1]) ? '' : $h[1];
									$htitle_false = empty($h[2]) ? '' : $h[2];
									$htitle_temp = empty($item[$h[0]]) ? $htitle_false : $htitle_true;
								endif;
								if ($htitle_temp):
									$htitle .= ' | ' . $htitle_temp;
								endif;
							else:
								$htitle .= ' | ' . $item[$h];
							endif;
						endforeach;
					else:
						$htitle = $item[$head];
					endif;
				endif;
				echo '<div class="list_item">';
					echo '<div class="head' . $active_class . '">';
						echo '<div class="move_item"></div>';
						echo '<div class="left" id="nav_' . $skey . '_' . $gkey . '_' . $id . '" data-id="nav_' . $skey . '_' . $gkey . '">';
							echo '<span class="num">' . ($id + 1) . '</span>';
							echo '<span class="title">' . $htitle . '</span>';
						echo '</div>';
						echo '<div class="right">';
							echo '<a class="delete" href="#delete" title="Удалить элемент">Удалить</a>';
						echo '</div>';
					echo '</div>';
					echo '<div class="cont">';
						foreach ($fields as $fkey => $field):
							$required = (empty($field['options']['required'])) ? '' : $this->required;
							echo '<div class="list_field">';
								echo '<div class="title">' . $fields[$fkey]['title'] . $required . ':' . ($field['desc'] ? '<div class="desc">' . $field['desc'] . '</div>' : '') . '</div>';
								echo '<div class="item">' . $this->get_field($skey, $gkey, $fkey, $id) . '</div>';
							echo '</div>';
							if ($field['separator']) echo '<hr class="separator">';
						endforeach;
					echo '</div>';
				echo '</div>';
			endforeach;
			echo '</div>';
			echo '<div class="dot" style="display:none;">';
				echo '<div class="list_item">';
					echo '<div class="head">';
						echo '<div class="move_item"></div>';
						echo '<div class="left" data-id="nav_' . $skey . '_' . $gkey . '">';
							echo '<span class="num">0</span>';
							echo '<span class="title"></span>';
						echo '</div>';
						echo '<div class="right">';
							echo '<a class="delete" href="#delete" title="Удалить элемент">Удалить</a>';
						echo '</div>';
					echo '</div>';
					echo '<div class="cont">';
						foreach ($fields as $fkey => $field):
							$required = (empty($field['options']['required'])) ? '' : $this->required;
							echo '<div class="list_field">';
								echo '<div class="title">' . $fields[$fkey]['title'] . $required . ':' . ($field['desc'] ? '<div class="desc">' . $field['desc'] . '</div>' : '') . '</div>';
								echo '<div class="item">' . $this->get_field($skey, $gkey, $fkey, false, true) . '</div>';
							echo '</div>';
							if ($field['separator']) echo '<hr class="separator">';
						endforeach;
					echo '</div>';
				echo '</div>';
			echo '</div>';
			echo '<div class="manage"><a class="add" href="#add" title="Добавить элемент">Добавить</a></div>';
		}

		public function the_cp() {
			$this->the_cp_menu();
			$this->the_section_menu();
			echo '<div class="content">';
			foreach ($this->fields as $skey => $section):
				echo '<div id="nav_' . $skey . '" class="section">';
				echo '<h2 id="' . $skey . '">' . $section['title'] . '</h2>';
				$this->the_group_menu($skey);
				foreach ($section['groups'] as $gkey => $group):
					echo '<div id="nav_' . $skey . '_' . $gkey . '" class="group">';
					echo '<h3>' . $group['title'] . '</h3>';
					echo '<form class="control" action="" method="post" enctype="multipart/form-data" data-param="data[' . $skey . '][' . $gkey . ']">';
					switch ($group['type']):
						case LC_GT_SINGLE:
							$this->gt_single($skey, $gkey);
							break;
						case LC_GT_TABLE:
							$this->gt_table($skey, $gkey);
							break;
						case LC_GT_LIST:
							$this->gt_list($skey, $gkey);
							break;
					endswitch;
					echo '<div class="button"><button type="submit" name="cms_save" value="1"><span>Сохранить</span></button></div>';
					echo '</form>';
					echo '</div>';
				endforeach;
				echo '</div>';
			endforeach;
			echo '</div>';
		}

		private function get_thumb($options, $value) {
			if (empty($options['thumbs'])) return $value['img'];
			$min = false;
			$output = $value['img'];
			foreach ($options['thumbs'] as $key => $thumb):
				if (!$min):
					$min = $thumb['width'];
					$output = $value[$key];
				elseif ($min > $thumb['width']):
					$min = $thumb['width'];
					$output = $value[$key];
				endif;
			endforeach;
			return $output;
		}

		private function input_options($options) {
			$output = '';
			foreach ($options as $key => $value):
				if ($key == 'required' || $key == 'disabled' || $key == 'multiple' || $key == 'readonly' || $key == 'checked' || $key == 'selected'):
					if ($value):
						$output .= ' ' . $key;
					endif;
				else:
					$output .= ' ' . $key . '="' . $value . '"';
				endif;
			endforeach;
			return $output;
		}

		private function get_field($skey, $gkey, $fkey, $id = false, $dot = false) {
			$options = $this->fields[$skey]['groups'][$gkey]['fields'][$fkey]['options'];
			$type = $this->fields[$skey]['groups'][$gkey]['fields'][$fkey]['type'];
			if ($id === false):
				$name = 'data[' . $skey . '][' . $gkey . '][' . $fkey . ']';
				$value = $this->data[$skey][$gkey][$fkey];
			else:
				$name = 'data[' . $skey . '][' . $gkey . '][' . $id . '][' . $fkey . ']';
				$value = $this->data[$skey][$gkey][$id][$fkey];
			endif;
			if (!isset($value) || $dot) $value = $options['default'];
			unset($options['default']);
			if ($dot):
				if (!empty($options['required'])):
					$options['data-require'] = '1';
					unset($options['required']);
				endif;
			else:
				$options['name'] = $name;
			endif;
			$options['value'] = $value;
			$options['data-name'] = '[' . $fkey . ']';
			unset($options['replace']);
			$output = '';
			switch ($type) {
				case LC_FT_TEXT:
					$output .= '<input type="text"' . $this->input_options($options) . '>';
					break;
				case LC_FT_PASSWORD:
					$output .= '<input type="password"' . $this->input_options($options) . '>';
					break;
				case LC_FT_CHECKBOX:
					$options['value'] = '0';
					$output .= '<input type="hidden"' . $this->input_options($options) . '>';
					$options['value'] = '1';
					if ($value) $options['checked'] = true;
					$output .= '<label><input type="checkbox"' . $this->input_options($options) . '> ' . $options['label'] . '</label>';
					break;
				case LC_FT_RADIO:
					foreach ($options['items'] as $id => $item):
						$tmp_opt = $options;
						unset($tmp_opt['items']);
						$tmp_opt['value'] = $id;
						if ($id == $value) $tmp_opt['checked'] = true;
						$output .= '<div class="item"><label><input type="radio"' . $this->input_options($tmp_opt) . '> ' . $item . '</label></div>';
					endforeach;
					break;
				case LC_FT_TEXTAREA:
					unset($options['value']);
					$output .= '<textarea' . $this->input_options($options) . '>';
					$output .= $value;
					$output .= '</textarea>';
					break;
				case LC_FT_WYSIWYG:
					unset($options['value']);
					$output .= '<textarea' . $this->input_options($options) . ' class="cledit">';
					$output .= $value;
					$output .= '</textarea>';
					break;
				case LC_FT_IMG:
					$options['accept'] = 'image/*';
					if (!$value):
						unset($options['thumbs']);
						$output .= '<input type="file"' . $this->input_options($options) . '>';
						break;
					endif;
					$thumb = $this->get_thumb($options, $value);
					unset($options['thumbs']);
					if ($dot):
						$output .= '<input type="file"' . $this->input_options($options) . '>';
					else:
						$output .= '<div class="imgfield"><div class="img"><img src="' . $thumb . '" alt=""><a href="#delete" class="img_del" title="Удалить изображение">Удалить</a>';
						$tmp_opt = $options;
						foreach ($value as $key => $img):
							$tmp_opt['name'] = $name . '[' . $key . ']';
							$tmp_opt['value'] = $img;
							$tmp_opt['data-name'] = '[' . $fkey . '][' . $key . ']';
							$output .= '<input type="hidden"' . $this->input_options($tmp_opt) . '>';
						endforeach;
						$output .= '</div>';
						unset($options['required']);
						unset($options['value']);
						$output .= '<div class="file"><input type="file"' . $this->input_options($options) . '></div></div>';
					endif;
					break;
				case LC_FT_FILE:
					if ($value):
						$fname = pathinfo($value, PATHINFO_BASENAME);
						$output .= '<div class="linkfield"><div class="link"><a href="' . $value . '" target="_blank">' . $fname . '</a></div>';
						$output .= '<input type="hidden"' . $this->input_options($options) . '>';
						unset($options['required']);
						unset($options['value']);
						$output .= '<div class="file"><input type="file"' . $this->input_options($options) . '></div></div>';
					else:
						$output .= '<input type="file"' . $this->input_options($options) . '>';
					endif;
					break;
				case LC_FT_FILELIST:
					$output .= '<div class="text_list">';
					$output .= '<div class="text_list_items">';
					if (empty($options['value']) || count($options['value']) == 0) $options['value'] = array ('');
					//vd::dump($options);
					foreach ($options['value'] as $def) {
						$tmp_opt = $options;
						$tmp_opt['value'] = $def;
						$tmp_opt['name'] = $tmp_opt['name'] . '[]';
						$tmp_opt['data-name'] = $tmp_opt['data-name'] . '[]';
						$output .= '<div class="text_list_item">';
						if ($def):
							$output .= '<div class="move"></div>';
							$fname = pathinfo($def, PATHINFO_BASENAME);
							$output .= '<input type="hidden"' . $this->input_options($tmp_opt) . '>';
							$output .= '<div class="ftitle"><a href="' . $def . '" target="_blank">' . $fname . '</a></div>';
							unset($tmp_opt['value']);
							unset($tmp_opt['required']);
						endif;
						$output .= '<input type="file"' . $this->input_options($tmp_opt) . '>';
						$output .= '<a href="#del" class="text_list_item_delete">Удалить</a>';
						$output .= '</div>';
					}
					$output .= '</div>';
					$output .= '<a href="#add" class="text_list_item_add">Добавить</a>';
					$output .= '</div>';
					break;
				case LC_FT_NUMBER:
					$output .= '<input type="number"' . $this->input_options($options) . '>';
					break;
				case LC_FT_EMAIL:
					$output .= '<input type="email"' . $this->input_options($options) . '>';
					break;
				case LC_FT_RANGE:
					$output .= '<input type="range"' . $this->input_options($options) . '>';
					break;
				case LC_FT_TEL:
					$output .= '<input type="tel"' . $this->input_options($options) . '>';
					break;
				case LC_FT_URL:
					$output .= '<input type="url"' . $this->input_options($options) . '>';
					break;
				case LC_FT_DATE:
					$output .= '<input type="date"' . $this->input_options($options) . '>';
					break;
				case LC_FT_MONTH:
					$output .= '<input type="month"' . $this->input_options($options) . '>';
					break;
				case LC_FT_WEEK:
					$output .= '<input type="week"' . $this->input_options($options) . '>';
					break;
				case LC_FT_DATETIME:
					$output .= '<input type="datetime"' . $this->input_options($options) . '>';
					break;
				case LC_FT_TIME:
					$output .= '<input type="time"' . $this->input_options($options) . '>';
					break;
				case LC_FT_IMGLIST:
					$options['accept'] = 'image/*';
					$options['multiple'] = true;
					$options['data-name'] = $options['data-name'] . '[]';
					if (!$value):
						unset($options['thumbs']);
						$options['name'] = $options['name'] . '[]';
						$output .= '<input type="file"' . $this->input_options($options) . '>';
						break;
					endif;
					if ($dot):
						//vd::dump($options);
						$output .= '<input type="file"' . $this->input_options($options) . '>';
					else:
						$output .= '<div class="imglist">';
						foreach ($value as $file_id => $file_value):
							$thumb = $this->get_thumb($options, $file_value);
							$output .= '<div class="img"><img src="' . $thumb . '" alt=""><a href="#delete" class="img_del" title="Удалить изображение">Удалить</a>';
							$tmp_opt = $options;
							unset(
								$tmp_opt['thumbs'],
								$tmp_opt['multiple'],
								$tmp_opt['accept']
							);
							foreach ($file_value as $key => $img):
								$tmp_opt['name'] = $name . '[' . $file_id . '][' . $key . ']';
								$tmp_opt['value'] = $img;
								$tmp_opt['data-name'] = '[' . $fkey . '][' . $file_id . '][' . $key . ']';
								if ($id !== false) $tmp_opt['data-item'] = $id;
								$tmp_opt['data-field'] = '[' . $fkey . ']';
								$tmp_opt['data-file'] = '[' . $key . ']';
								$output .= '<input type="hidden"' . $this->input_options($tmp_opt) . '>';
							endforeach;
							$output .= '</div>';
						endforeach;
						unset($options['thumbs']);
						unset($options['required']);
						unset($options['value']);
						$options['name'] = $options['name'] . '[]';
						$output .= '<div class="file"><input type="file"' . $this->input_options($options) . '></div></div>';
					endif;
					break;
				case LC_FT_SELECT;
				case LC_FT_COMBOBOX;
					$tmp_opt = $options;
					unset($tmp_opt['items'], $tmp_opt['value']);
					$output .= '<select' . $this->input_options($tmp_opt) . '>';
					foreach ($options['items'] as $id => $item):
						$tmp_opt = array ();
						$tmp_opt['value'] = $id;
						if ($id == $value) $tmp_opt['selected'] = true;
						$output .= '<option' . $this->input_options($tmp_opt) . '>' . $item . '</option>';
					endforeach;
					$output .= '</select>';
					break;
				case LC_FT_LISTBOX:
					$tmp_opt = $options;
					unset($tmp_opt['items'], $tmp_opt['value']);
					$tmp_opt['multiple'] = true;
					$tmp_opt['name'] = $tmp_opt['name'] . '[]';
					$tmp_opt['data-name'] = $tmp_opt['data-name'] . '[]';
					$output .= '<select' . $this->input_options($tmp_opt) . '>';
					foreach ($options['items'] as $id => $item):
						$tmp_opt = array ();
						$tmp_opt['value'] = $id;
						if (in_array($id, $value)) $tmp_opt['selected'] = true;
						$output .= '<option' . $this->input_options($tmp_opt) . '>' . $item . '</option>';
					endforeach;
					$output .= '</select>';
					break;
				case LC_FT_LIST:
					$output .= '<div class="text_list">';
					$output .= '<div class="text_list_items">';
					if (empty($options['value']) || count($options['value']) == 0) $options['value'] = array ('');
					foreach ($options['value'] as $def) {
						$tmp_opt = $options;
						$tmp_opt['value'] = $def;
						$tmp_opt['name'] = $tmp_opt['name'] . '[]';
						$tmp_opt['data-name'] = $tmp_opt['data-name'] . '[]';
						$output .= '<div class="text_list_item">';
						$output .= '<div class="move"></div>';
						$output .= '<input type="text"' . $this->input_options($tmp_opt) . '>';
						$output .= '<a href="#del" class="text_list_item_delete">Удалить</a>';
						$output .= '</div>';
					}
					$output .= '</div>';
					$output .= '<a href="#add" class="text_list_item_add">Добавить</a>';
					$output .= '</div>';
					break;
				case LC_FT_TEXTLIST:
					$output .= '<div class="text_list">';
					$output .= '<div class="text_list_items">';
					if (empty($options['value']) || count($options['value']) == 0) $options['value'] = array ('');
					foreach ($options['value'] as $def) {
						$tmp_opt = $options;
						$tmp_opt['value'] = $def;
						$tmp_opt['name'] = $tmp_opt['name'] . '[]';
						$tmp_opt['data-name'] = $tmp_opt['data-name'] . '[]';
						$output .= '<div class="text_list_item">';
						$val = $tmp_opt['value'];
						unset($tmp_opt['value']);
						$output .= '<div class="move"></div>';
						$output .= '<textarea' . $this->input_options($tmp_opt) . '>' . $val . '</textarea>';
						$output .= '<a href="#del" class="text_list_item_delete">Удалить</a>';
						$output .= '</div>';
					}
					$output .= '</div>';
					$output .= '<a href="#add" class="text_list_item_add">Добавить</a>';
					$output .= '</div>';
					break;
				case LC_FT_TABLE:
					$output .= '<div class="table_list">';
					$output .= '<table>';
					$output .= '<thead>';
					$output .= '<tr><td class="table_list_item_move"></td>';
					foreach ($options['items'] as $key => $item) {
						$output .= '<td>' . $item['title'];
						if (!empty($item['options']['required'])) $output .= ' <sup title="Поле обязательно для заполнения">*</sup>';
						$output .= '</td>';
					}
					$output .= '<td></td>';
					$output .= '</tr>';
					$output .= '</thead>';
					$output .= '<tbody>';
					//vd::dump($options);
					if (empty($options['value'])) {
						$output .= '<tr class="table_list_item"><td class="table_list_item_move"></td>';
						foreach ($options['items'] as $key => $item) {
							$tmp_opt = $item['options'];
							$tmp_opt['value'] = empty($tmp_opt['default']) ? '' : $tmp_opt['default'];
							unset($tmp_opt['default']);
							$tmp_opt['name'] = $options['name'] . '[0][' . $key . ']';
							$tmp_opt['data-subname'] = $options['name'] . '[%num%][' . $key . ']';
							$tmp_opt['data-name'] = $options['data-name'] . '[0][' . $key . ']';
							if (isset($tmp_opt['required']) && $tmp_opt['required']) $tmp_opt['data-require'] = '1';
							if ($dot) unset($tmp_opt['required']);
							$output .= '<td>';
							$output .= '<input type="text"' . $this->input_options($tmp_opt) . '>';
							$output .= '</td>';
						}
						$output .= '<td><a href="#del" class="table_list_item_delete">Удалить</a></td>';
						$output .= '</tr>';
					} else {
						foreach ($options['value'] as $id => $items) {
							$output .= '<tr class="table_list_item"><td class="table_list_item_move"></td>';
							foreach ($options['items'] as $key => $item) {
								$tmp_opt = $item['options'];
								$tmp_opt['value'] = $items[$key];
								unset($tmp_opt['default']);
								$tmp_opt['name'] = $options['name'] . '[' . $id  . '][' . $key . ']';
								$tmp_opt['data-subname'] = $options['name'] . '[%num%][' . $key . ']';
								$tmp_opt['data-name'] = $options['data-name'] . '[' . $id  . '][' . $key . ']';
								if (isset($tmp_opt['required']) && $tmp_opt['required']) $tmp_opt['data-require'] = '1';
								$output .= '<td>';
								$output .= '<input type="text"' . $this->input_options($tmp_opt) . '>';
								$output .= '</td>';
							}
							$output .= '<td><a href="#del" class="table_list_item_delete">Удалить</a></td>';
							$output .= '</tr>';
						}
					}
					$output .= '</tbody>';
					$output .= '</table>';
					$output .= '<a href="#add" class="table_list_item_add">Добавить строку</a>';
					$output .= '</div>';
					break;
				case LC_FT_YMAP:
					//vd::dump($options);
					$val = $options['value'];
					$output .= '<div class="yandex_map">';
					$output .= '<div class="coordinates">';
					$tmp_opt = $options;
					$tmp_opt['name'] .= '[lat]';
					$tmp_opt['data-name'] .= '[lat]';
					$tmp_opt['value'] = empty($val['lat']) ? '' : $val['lat'];
					$tmp_opt['readonly'] = true;
					$output .= '<div class="col lat"><span>Lat:</span><input type="text"' . $this->input_options($tmp_opt) . '></div>';
					$tmp_opt = $options;
					$tmp_opt['name'] .= '[lon]';
					$tmp_opt['data-name'] .= '[lon]';
					$tmp_opt['value'] = empty($val['lon']) ? '' : $val['lon'];
					$tmp_opt['readonly'] = true;
					$output .= '<div class="col lon"><span>Lon:</span><input type="text"' . $this->input_options($tmp_opt) . '></div>';
					$tmp_opt = $options;
					$tmp_opt['name'] .= '[zoom]';
					$tmp_opt['data-name'] .= '[zoom]';
					$tmp_opt['value'] = empty($val['zoom']) ? '' : $val['zoom'];
					$tmp_opt['readonly'] = true;
					$output .= '<div class="col zoom"><span>Масштаб:</span><input type="text"' . $this->input_options($tmp_opt) . '></div>';
					$output .= '</div>';
					$tmp_opt = $options;
					$tmp_opt['name'] .= '[color]';
					$tmp_opt['data-name'] .= '[color]';
					$tmp_opt['value'] = empty($val['color']) ? '#FF0000' : $val['color'];
					$output .= '<span>Цвет указателя:</span>';
					$output .= '<div class="colorpicker"><div class="color_area" style="background:' . $tmp_opt['value'] . ';"></div><div class="color_input"><input type="text"' . $this->input_options($tmp_opt) . '></div></div>';
					$tmp_opt = $options;
					$tmp_opt['name'] .= '[label]';
					$tmp_opt['data-name'] .= '[label]';
					$val = empty($val['label']) ? '' : $val['label'];
					unset($tmp_opt['value']);
					$output .= '<div class="map_label"><span>Текст в указателе:</span><textarea' . $this->input_options($tmp_opt) . '>' . $val . '</textarea></div>';
					$output .= '<div class="map_link"><a href="#yandex_map" class="link_ymap">Указать на карте</a></div>';
					$output .= '<div class="map_content" style="display:none;"></div>';
					$output .= '<div class="map_manage" style="display:none;"><a href="#yandex_map_save" class="link_ymap_save">Применить</a> <a href="#yandex_map_cancel" class="link_ymap_cancel">Отмена</a></div>';
					$output .= '</div>';
					break;
				case LC_FT_COLOR:
					/*if (!empty($options['required'])) {
						$options['onpaste'] = 'event.preventDefault();';
						$options['oncut'] = 'event.preventDefault();';
						$options['onkeydown'] = 'event.preventDefault();';
					}*/
					$options['readonly'] = true;
					$val = empty($options['value']) ? '' : ' style="background:' . $options['value'] . ';"';
					$output .= '<div class="colorpicker">';
					$output .= '<div class="color_area"' . $val . '></div>';
					$output .= '<div class="color_input"><input type="text"' . $this->input_options($options) . '></div>';
					$output .= '</div>';
					break;
				default:
					# code...
					break;
			}
			return $output;
		}

		private function save() {
			if (empty($_POST['cms_save'])) return false;
			if (!file_exists($this->upload_path)) mkdir($this->upload_path, 0777, true);
			$to_save = $this->data;
			foreach ($this->fields as $skey => $section):
				foreach ($section['groups'] as $gkey => $group):
					switch ($group['type']):
						case LC_GT_SINGLE:
							if (isset($_POST['data'][$skey][$gkey])):
								$to_save[$skey][$gkey] = array ();
								foreach ($_POST['data'][$skey][$gkey] as $fkey => $field):
									if (is_array($field) && empty($field['img']) && !isset($field['zoom'])):
										$field = array_values($field);
									endif;
									$field = str_replace('\"', '"', $field);
									$to_save[$skey][$gkey][$fkey] = $field;
								endforeach;
							endif;
							if (isset($_FILES['data'][$skey][$gkey])):
								foreach ($_FILES['data'][$skey][$gkey] as $fkey => $field):
									if ($field['error'] != UPLOAD_ERR_OK) continue;
									switch ($this->fields[$skey]['groups'][$gkey]['fields'][$fkey]['type']):
										case LC_FT_IMG:
											$img_arr = $this->upload_img($skey, $gkey, $fkey);
											if ($img_arr) $to_save[$skey][$gkey][$fkey] = $img_arr;
											break;
										case LC_FT_IMGLIST:
											foreach ($field as $file_id => $file_value):
												$img_arr = $this->upload_img($skey, $gkey, $fkey, false, $file_id);
												if ($img_arr) $to_save[$skey][$gkey][$fkey][] = $img_arr;
											endforeach;
											break;
										case LC_FT_FILE:
											$img_arr = $this->upload_file($skey, $gkey, $fkey);
											if ($img_arr) $to_save[$skey][$gkey][$fkey] = $img_arr;
											break;
										case LC_FT_FILELIST:
											foreach ($field as $file_id => $file_value):
												if (!empty($file_value['size'])):
													$img_arr = $this->upload_file($skey, $gkey, $fkey, false, $file_id);
													if ($img_arr) $to_save[$skey][$gkey][$fkey][] = $img_arr;
												endif;
											endforeach;
											break;
										default:
											break;
									endswitch;
								endforeach;
							endif;
							break;
						default:
							if (isset($_POST['data'][$skey][$gkey])):
								$to_save[$skey][$gkey] = array ();
								foreach ($_POST['data'][$skey][$gkey] as $id => $item):
									foreach ($item as $fkey => $field):
										if (is_array($field) && empty($field['img']) && !isset($field['zoom'])):
											$field = array_values($field);
										endif;
										$field = str_replace('\"', '"', $field);
										$to_save[$skey][$gkey][$id][$fkey] = $field;
									endforeach;
								endforeach;
							endif;
							if (isset($_FILES['data'][$skey][$gkey])):
								foreach ($_FILES['data'][$skey][$gkey] as $id => $item):
									foreach ($item as $fkey => $field):
										if ($field['error'] != UPLOAD_ERR_OK) continue;
										switch ($this->fields[$skey]['groups'][$gkey]['fields'][$fkey]['type']):
											case LC_FT_IMG:
												$img_arr = $this->upload_img($skey, $gkey, $fkey, $id);
												if ($img_arr) $to_save[$skey][$gkey][$id][$fkey] = $img_arr;
												break;
											case LC_FT_IMGLIST:
												foreach ($field as $file_id => $file_value):
													if (!empty($file_value['size'])):
														$img_arr = $this->upload_img($skey, $gkey, $fkey, $id, $file_id);
														if ($img_arr) $to_save[$skey][$gkey][$id][$fkey][] = $img_arr;
													endif;
												endforeach;
												break;
											case LC_FT_FILE:
												$img_arr = $this->upload_file($skey, $gkey, $fkey, $id);
												if ($img_arr) $to_save[$skey][$gkey][$id][$fkey] = $img_arr;
												break;
											case LC_FT_FILELIST:
												foreach ($field as $file_id => $file_value):
													if (!empty($file_value['size'])):
														$img_arr = $this->upload_file($skey, $gkey, $fkey, $id, $file_id);
														if ($img_arr) $to_save[$skey][$gkey][$id][$fkey][] = $img_arr;
													endif;
												endforeach;
												break;
											default:
												break;
										endswitch;
									endforeach;
								endforeach;
							endif;
							break;
					endswitch;
				endforeach;
			endforeach;
			//vd::dumpn('$_POST[data]', $_POST['data']);
			//vd::dumpn('$_FILES', $_FILES);
			//vd::dumpn('$this->data', $this->data);
			//vd::dumpn('$to_save', $to_save);
			file_put_contents($this->data_file, serialize($to_save));
			header('Location: /cms');
		}

		private function upload_file($skey, $gkey, $fkey, $id = false, $img_id = false) {
			if ($id === false):
				if ($img_id === false):
					$fdata = $_FILES['data'][$skey][$gkey][$fkey];
					$dirs = array ($skey, $gkey);
				else:
					$fdata = $_FILES['data'][$skey][$gkey][$fkey][$img_id];
					$dirs = array ($skey, $gkey);
				endif;
			else:
				if ($img_id === false):
					$fdata = $_FILES['data'][$skey][$gkey][$id][$fkey];
					$dirs = array ($skey, $gkey, $id);
				else:
					$fdata = $_FILES['data'][$skey][$gkey][$id][$fkey][$img_id];
					$dirs = array ($skey, $gkey, $id);
				endif;
			endif;
			$dirs = array ($skey, $gkey);
			$field_data = $this->fields[$skey]['groups'][$gkey]['fields'][$fkey];
			//vd::dump($field_data);
			$target_dir = $this->upload_path . join(DIRECTORY_SEPARATOR, $dirs) . DIRECTORY_SEPARATOR;
			$target_url = '/' . $this->upload_dir . '/' . join('/', $dirs) . '/';
			$target_name = $this->get_target_name($fdata['name'], $target_dir, $field_data['options']['replace']);
			if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
			move_uploaded_file($fdata['tmp_name'], $target_dir . $target_name);
			return $target_url . $target_name;
		}

		private function upload_img($skey, $gkey, $fkey, $id = false, $img_id = false) {
			$output = array ();
			$filter = array (
				'image/jpeg',
				'image/png',
				'image/gif',
			);
			if ($id === false):
				if ($img_id === false):
					$fdata = $_FILES['data'][$skey][$gkey][$fkey];
					$dirs = array ($skey, $gkey);
				else:
					$fdata = $_FILES['data'][$skey][$gkey][$fkey][$img_id];
					$dirs = array ($skey, $gkey);
				endif;
			else:
				if ($img_id === false):
					$fdata = $_FILES['data'][$skey][$gkey][$id][$fkey];
					$dirs = array ($skey, $gkey, $id);
				else:
					$fdata = $_FILES['data'][$skey][$gkey][$id][$fkey][$img_id];
					$dirs = array ($skey, $gkey, $id);
				endif;
			endif;
			$dirs = array ($skey, $gkey);
			if (!in_array($fdata['type'], $filter)) return false;
			$field_data = $this->fields[$skey]['groups'][$gkey]['fields'][$fkey];
			$target_dir = $this->upload_path . join(DIRECTORY_SEPARATOR, $dirs) . DIRECTORY_SEPARATOR;
			$target_url = '/' . $this->upload_dir . '/' . join('/', $dirs) . '/';
			$target_name = $this->get_target_name($fdata['name'], $target_dir, $field_data['options']['replace']);
			if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
			move_uploaded_file($fdata['tmp_name'], $target_dir . $target_name);
			$output['img'] = $target_url . $target_name;
			$thumbs = $field_data['options']['thumbs'];
			if (!empty($thumbs)):
				foreach ($thumbs as $ikey => $img):
					$target_thumb = $ikey . '_' . $target_name;
					$bg = empty($img['bg']) ? '#fff' : $img['bg'];
					$bg = $this->hex_to_rgb($bg);
					$image = new SimpleImage($target_dir . $target_name);
					$image->maxareafill($img['width'], $img['height'], $bg['r'], $bg['g'], $bg['b']);
					$image->save($target_dir . $target_thumb);
					$output[$ikey] = $target_url . $target_thumb;
				endforeach;
			endif;
			return $output;
		}

		private function get_ext($fname) {
			return substr(strrchr($fname, '.'), 1);
		}

		private function translit($text) {
			$rus = array ('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
			$lat = array ('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
			return str_replace($rus, $lat, $text);
		}

		private function hex_to_rgb($hex, $alpha = false) {
			$hex = str_replace('#', '', $hex);
			$length = strlen($hex);
			if (!preg_match('/[0-9a-fA-F]{3}|[0-9a-fA-F]{6}/', $hex)) return false;
			$rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : substr($hex, 0, 1));
			$rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : substr($hex, 1, 1));
			$rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : substr($hex, 2, 1));
			if ($alpha) $rgb['a'] = $alpha;
			return $rgb;
		}

		private function get_target_name($fname, $target_dir, $replace) {
			$fname = $this->translit($fname);
			$fname = preg_replace('/\s/', '_', $fname);
			if (empty($replace)):
				$tmp_name = $fname;
				$i = 0;
				while (file_exists($target_dir . $tmp_name)) {
					$tmp_name = $i . '_' . $fname;
					$i++;
				}
				$fname = $tmp_name;
			endif;
			return $fname;
		}

		public static function set_br($var) {
			return str_replace(PHP_EOL, '<br>', $var);
		}

		public static function set_p($var) {
			return '<p>' . str_replace(PHP_EOL, '</p><p>', $var) . '</p>';
		}

		public static function get_phone($var) {
		$var = preg_replace('/\D+/', '', $var);
		return (trim($var) == '') ? '' : 'tel:+' . preg_replace('/^8/', '7', $var);
		}
	}


?>