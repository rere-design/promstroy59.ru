<?php

	Class Bitrix24 {
		public $version = '1.0';
		private $host = '';
		private $port = '443';
		private $path = '/crm/configs/import/lead.php';
		private $_post_data = array ();

		function __construct($data) {
			$this->host = $data['host'];
			$this->_post_data = array ();
			$this->_post_data['SOURCE_ID'] = 'WEB';
			$this->_post_data['OPPORTUNITY'] = '12000';
			$this->_post_data['COMMENTS'] = '';
			$this->_post_data['LAST_NAME'] = '';
			$this->_post_data['SOURCE_DESCRIPTION'] = '';
			$this->_post_data['OPPORTUNITY'] = '';
			$this->_post_data['ASSIGNED_BY_ID'] = '';
			if (!empty($data['login'])) $this->_post_data['LOGIN'] = $data['login'];
			if (!empty($data['password'])) $this->_post_data['PASSWORD'] = $data['password'];
			if (!empty($data['auth'])) $this->_post_data['AUTH'] = $data['auth'];
		}

		public function addField($key, $value) {
			$this->_post_data[$key] = $value;
		}

		public function send() {
			$socket = fsockopen("ssl://" . $this->host, $this->port, $errno, $errstr, 30);
			if ($socket) {
				$post_data = '';

				foreach ($this->_post_data as $key => $value) {
					$post_data .= ($post_data == '' ? '' : '&') . $key . '=' . urlencode($value);
				}
				$header = "POST " . $this->path . " HTTP/1.0\r\n";
				$header .= "Host: " . $this->host . "\r\n";
				$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
				$header .= "Content-Length: " . strlen($post_data) . "\r\n";
				$header .= "Connection: close\r\n\r\n";

				$post_data = $header . $post_data;

				fwrite($socket, $post_data);

				$result = '';
				while (!feof($socket)) {
					$result .= fgets($socket, 128);
				}
				fclose($socket);
				$response = explode("\r\n\r\n", $result);
				$response_str = $response[1];
				$response_json = str_replace('\'', '"', $response_str);
				$response_obj = json_decode($response_json);
				return $response_obj;
			} else {
				return (object) array (
					'error'			=>	'301',
					'error_message'	=>	'Ошибка соединения!',
				);
			}
		}
	}

?>