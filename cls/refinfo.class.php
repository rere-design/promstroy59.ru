<?php

/*
	require $_SERVER['DOCUMENT_ROOT'] . '/include/refinfo.class.php';
	$refinfo = new RefInfo();
	$refinfo->init();
*/

	class RefInfo {
		static public $version = '5.4';
		public $data = null;
		public $html = '';
		public $direct_url = 'LOCAL';
		public $cookie_key = '_ri_';
		public $input_dot = '<input type="hidden" name="refinfo_%key%" value="%val%">';
		public $expire = '+10 years';
		public $utm_key = array (
			'utm_campaign',
			'utm_content',
			'utm_source'
		);
		public $utm_domen_key = 'utm_placement';
		public $call_code_replace = array ('_', '.', ',', '-', '+');

		public function __construct() {
			$this->_prepare_data();
			$this->expire = strtotime($this->expire);
		}

		private function _prepare_data () {
			$this->data = (object) array (
				'first_date'	=>	'',
				'first_ref'		=>	'',
				'referer'		=>	'',
				'get'			=>	'',
				'utm'			=>	'',
				'call_code'		=>	'',
				'ip'			=>	'',
				'ua'			=>	'',
			);
		}

		public function init() {
			$this->data->ip = $this->_get_ip();
			$this->_set_storage_data('ip', $this->data->ip);

			$this->data->first_date = $this->_get_first_date();
			$this->_set_storage_data('first_date', $this->data->first_date, true);

			$this->data->first_ref = $this->_get_first_ref();
			$this->_set_storage_data('first_ref', $this->data->first_ref, true);

			$this->data->referer = $this->_get_referer();
			$this->_set_storage_data('referer', $this->data->referer);

			$this->data->get = $this->_get_string();
			$this->_set_storage_data('get', $this->data->get);

			$this->data->utm = $this->_get_utm();
			$this->_set_storage_data('utm', $this->data->utm);

			$this->data->call_code = $this->_get_call_code();
			$this->_set_storage_data('call_code', $this->data->call_code);

			$this->data->ua = $this->_get_useragent();
			$this->_set_storage_data('ua', $this->data->ua);

			$this->html = $this->_get_html();
		}

		private function _get_storage_data ($key) {
			$key = $this->cookie_key . $key;
			return empty($_COOKIE[$key]) ? '' : $_COOKIE[$key];
		}

		private function _set_storage_data ($key, $val = '', $expire = false, $replace = false) {
			if (empty($this->_cookie[$key]) || $replace) {
				setcookie($this->cookie_key . $key, $val, ($expire ? $this->expire : 0) , '/');
			}
		}

		private function _get_first_date () {
			$cookie = $this->_get_storage_data('first_date');
			switch (true) {
				case ( !empty($cookie) ):
					return $cookie;
					break;
				default:
					return date('d.m.Y H:i:s') . ' (UTC ' . date('P') . ')';
			}
		}

		private function _get_first_ref () {
			$cookie = $this->_get_storage_data('first_ref');
			switch (true) {
				case ( !empty($cookie) ):
					return $cookie;
					break;
				case ( !empty($_SERVER['QUERY_STRING']) && strpos($_SERVER['QUERY_STRING'], 'utm_') !== false ):
					return urldecode(trim($_SERVER['QUERY_STRING']));
					break;
				case ( !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_HOST'] !== parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) ):
					return $_SERVER['HTTP_REFERER'];
					break;
				default:
					return $this->direct_url;
			}
		}

		private function _get_ip () {
			$cookie = $this->_get_storage_data('ip');
			switch (true) {
				case ( !empty($cookie) ):
					return $cookie;
					break;
				case ( !empty($_SERVER['HTTP_X_REAL_IP']) ):
					return $_SERVER['HTTP_X_REAL_IP'];
					break;
				case ( !empty($_SERVER['HTTP_CLIENT_IP']) ):
					return $_SERVER['HTTP_CLIENT_IP'];
					break;
				case ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ):
					return $_SERVER['HTTP_X_FORWARDED_FOR'];
					break;
				default:
					return $_SERVER['REMOTE_ADDR'];
			}
		}

		private function _get_referer () {
			$cookie = $this->_get_storage_data('referer');
			switch (true) {
				case ( !empty($cookie) ):
					return $cookie;
					break;
				case ( !empty($_GET[$this->utm_domen_key]) ):
					return $_GET[$this->utm_domen_key];
					break;
				case ( !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_HOST'] !== parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) ):
					return $_SERVER['HTTP_REFERER'];
					break;
				default:
					return '';
			}
		}

		private function _get_string () {
			$cookie = $this->_get_storage_data('get');
			switch (true) {
				case ( !empty($cookie) ):
					return $cookie;
					break;
				case ( !empty($_SERVER['QUERY_STRING']) ):
					return urldecode($_SERVER['QUERY_STRING']);
					break;
				default:
					return '';
			}
		}

		private function _get_utm () {
			$cookie = $this->_get_storage_data('utm');
			switch (true) {
				case ( !empty($cookie) ):
					return $cookie;
					break;
				case ( !empty($_GET) ):
					$output = '';
					foreach ($_GET as $key => $val) {
						$key = strtolower($key);
						if (strpos($key, 'utm_') !== false) {
							$output .= $key . '=' . $val . '; ';
						}
					}
					return $output;
					break;
				default:
					return '';
			}
		}

		private function _get_call_code () {
			$cookie = $this->_get_storage_data('call_code');
			switch (true) {
				case ( !empty($cookie) ):
					$result = $cookie;
					break;
				case ( !empty($this->utm_key) ):
					if (gettype($this->utm_key) === 'array') {
						foreach ($this->utm_key as $item) {
							if (!empty($_GET[$item]) && !strpos($_GET[$item], '{') && !strpos($_GET[$item], '}')) {
								$result = trim($_GET[$item]);
								break;
							}
						}
					} else {
						if (!empty($_GET[$this->utm_key]) && !strpos($_GET[$this->utm_key], '{') && !strpos($_GET[$this->utm_key], '}')) {
							$result = $_GET[$this->utm_key];
							break;
						}
					}
				case ( !empty($_SERVER['HTTP_REFERER']) ):
					$result = parse_url(trim($_SERVER['HTTP_REFERER']), PHP_URL_HOST);
					break;
				default:
					$result = $this->direct_url;
			}
			if ($result == $_SERVER['HTTP_HOST']) $result = $this->direct_url;
			$result = str_replace($this->call_code_replace, '', $result);
			$result = mb_strtoupper($result);
			return urldecode($result);
		}

		private function _get_useragent () {
			$cookie = $this->_get_storage_data('ua');
			switch (true) {
				case ( !empty($cookie) ):
					return $cookie;
					break;
				default:
					return $_SERVER['HTTP_USER_AGENT'];
			}
		}

		private function _get_html () {
			$html = '';
			foreach ($this->data as $key => $val) {
				if (!empty($val)) {
					$val = strip_tags($val);
					$val = htmlspecialchars($val);
					$input = str_replace('%key%', $key, $this->input_dot);
					$input = str_replace('%val%', $val, $input);
					$html .= $input;
				}
			}
			return $html . "\n\r";
		}
	}

?>