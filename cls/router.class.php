<?php

	class Router {
		public static $version = '3.12';
		private $style = array ();
		private $js = array ();
		public $debug = false;
		public $path = 'index';
		public $path_a = array ();
		public $root = '';
		private $abtest = array ();
		private $goals = array ();
		private $css = array (
			'form'	=>	array (
				'label'	=>	'position:absolute;z-index:1;left:0;top:0;background:#fff;color:#f00;padding:0 5px;border:1px solid #f00;font-size:12px;font-weight:bold;',
			),
			'goals'	=>	array (
				'block'	=>	'padding:30px 0;color:#000;background:#fff;border-top:1px solid #000;',
				'title'	=>	'font-size:18px;font-weight:bold;margin-bottom:20px;',
				'table'	=>	'width:auto;margin:0 auto;',
				'thead'	=>	'text-align:left;font-weight:bold;background:#ccc;padding:2px;',
				'tbody'	=>	'padding:2px;',
				'input'	=>	'width:400px;',
			),
		);

		public function add_style ($elements = array ()) {
			$this->style = array_merge($this->style, $elements);
		}

		public function add_js ($elements = array ()) {
			$this->js = array_merge($this->js, $elements);
		}

		public function include_style () {
			$s = "\n";
			foreach ($this->style as $item) {
				$ext = pathinfo($item);
				$ext = empty($ext['extension']) ? 'css' : $ext['extension'];
				if ($ext == 'less') {
					$new_item = preg_replace('/\.less$/i', '.css', $item);
					if ($this->debug) {
						$less = new lessc($this->root . $item);
						file_put_contents($this->root . $new_item, $less->parse());
					}
					$item = $new_item;
				}
				$s .= "\t\t" . '<link type="text/css" rel="stylesheet" href="' . $item . '">' . "\n";
			}
			return $s;
		}

		public function include_js () {
			$s = "\n";
			foreach ($this->js as $item) {
				$s .= "\t\t" . '<script type="text/javascript" src="' . $item . '"></script>' . "\n";
			}
			return $s;
		}

		public function add_ab_test ($name, $path, $rules, $redirect = true) {
			if (empty($name)) return false;
			if (empty($path)) return false;
			if (empty($rules) || !is_array($rules)) return false;
			if (is_array($path)) {
				foreach ($path as $value) {
					$this->abtest[$value] = array (
						'file'	=> $name,
						'path'	=> $rules,
						'redir'	=> $redirect,
					);
				}
			} else {
				$this->abtest[$path] = array (
					'file'	=> $name,
					'path'	=> $rules,
					'redir'	=> $redirect,
				);
			}
		}

		private function check_ab () {
			if (!empty($this->abtest[$this->path])) {
				$ab_arr = $this->abtest[$this->path];
				if (empty($ab_arr['file'])) {
					$ab_file = $this->root . '/ab_' . $this->path . '.ini';
				} else {
					$ab_file = $this->root . '/ab_' . $ab_arr['file'] . '.ini';
				}

				if (isset($_SESSION[$ab_file])) {
					$ab = $_SESSION[$ab_file];
				} else {
					if (file_exists($ab_file)) {
						$ab = file_get_contents($ab_file);
						$ab = intval($ab);
						$ab ++;
					} else {
						$ab = 0;
					}
					if ($ab >= count($ab_arr['path'])) $ab = 0;
					file_put_contents($ab_file, $ab);
					$_SESSION[$ab_file] = $ab;
				}
				if (!empty($ab_arr['path'][$ab])) {
					if ($this->path != $ab_arr['path'][$ab]) {
						$this->path = $ab_arr['path'][$ab];
						if ($ab_arr['redir']) {
							if (strpos($this->path, 'http://') === false && strpos($this->path, 'https://') === false) {
								$url = '/' . $this->path;
							} else {
								$url = $this->path;
							}
							$query = $_SERVER['REQUEST_URI'];
							if (strpos($query, '?') !== false) {
								$query = substr($query, strpos($query, '?'));
							} else {
								$query = '';
							}
							if ($query) $url .= $query;
							header('location: ' . $url);
						}
					}
				}
			}
		}

		public function init () {
			if (isset($_GET['d']) && $_GET['d'] == '1') $this->debug = true;
			$this->root = $_SERVER['DOCUMENT_ROOT'];
			$this->path = (empty($_GET['route'])) ? 'index' : trim($_GET['route']);
			unset($_GET['route']);
			$this->path_a = explode('/', $this->path);
			$this->check_ab();
			$tmpl = $this->root . '/tmpls/' . $this->path . '.tpl';
			if (file_exists($tmpl)) {
				include $tmpl;
			} else {
				header('HTTP/1.0 404 Not Found');
				include $this->root . '/tmpls/404.tpl';
				die;
			}
		}

		public function get_form($type, $action, $items, $goal, $button) {
			if (!$type) return 'Неверный тип формы';
			if (empty($items)) return 'В форме должны быть поля';
			$id = count($this->goals) + 1;
			$output = '<form action="' . $action . '" method="post" class="form_' . $type . '" style="position:relative;">';
			if ($this->debug) $output .= '<div style="' . $this->css['form']['label'] . '" id="popup_form_id_' . $id . '">' . $id . '</div>';
			$output .= '<div class="form_wrap">';
			foreach ($items as $key => $item) {
				$output .= '<div class="input_wrap"><label>';
				if (!empty($item['label'])) $output .= '<span>' . $item['label'] . '</span>';
				$tp = empty($item['type']) ? 'text' : $item['type'];
				$ph = empty($item['ph']) ? '' : $item['ph'];
				$val = empty($item['val']) ? '' : $item['val'];
				$ml = empty($item['ml']) ? 30 : $item['ml'];
				$output .= '<input type="' . $tp . '" name="' . $key . '" placeholder="' . $ph . '" value="' . $val . '" maxlength="' . $ml . '">';
				$output .= '</label></div>';
			}
			$output .= '<div class="button_wrap"><button type="submit"><span>' . $button . '</span></button></div>';
			$output .= '</div>';
			foreach ($goal as $key => $val) {
				$output .= '<input type="hidden" name="' . $key . '" value="' . $val . '">';
			}
			$output .= '</form>';
			$output .= "\n";
			krsort($goal);
			$this->goals[] = $goal;
			return $output;
		}

		public function the_form($type, $action, $items, $goal, $button) {
			echo $this->get_form($type, $action, $items, $goal, $button);
		}

		public function get_goals() {
			return $this->goals;
		}

		public function the_goals() {
			$the_click = 'this.focus();this.select();try{document.execCommand(\'copy\');}catch(err){alert(\'Не скопировалось!\');}';
			$output = '<div style="' . $this->css['goals']['block'] . '"><div style="' . $this->css['goals']['title'] . '">Цели для аналитики (щелчок мышью копирует в буфер обмена):</div><table style="' . $this->css['goals']['table'] . '"><thead class="goals_table"><tr><td style="' . $this->css['goals']['thead'] . '">#</td>';
			if (!empty($this->goals[0])) {
				krsort($this->goals[0]);
				foreach ($this->goals[0] as $key => $goal) {
					$output .= '<td style="' . $this->css['goals']['thead'] . '">' . $key . '</td>';
				}
			}
			$output .= '</tr></thead><tbody>';
			foreach ($this->goals as $id => $goal) {
				$output .= '<tr><td style="' . $this->css['goals']['tbody'] . '"><a href="#popup_form_id_' . ($id + 1) . '" class="scroll">' . ($id + 1) . '</a></td>';
				krsort($goal);
				foreach ($goal as $key => $item) {
					$output .= '<td style="' . $this->css['goals']['tbody'] . '"><input type="text" value="' . $item . '" readonly onclick="' . $the_click . '" style="' . $this->css['goals']['input'] . '"></td>';
				}
				$output .= '</tr>';
			}
			$output .= '</tbody></table></div>';
			echo $output;
		}
	}

?>