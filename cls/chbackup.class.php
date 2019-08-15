<?php

	class CHBackup {
		private $data = array ();
		public $url = 'http://bk.ce27707.tmweb.ru/hook.php';
		public $return = array ();
		public function __construct($data) {
			$this->data = $data;
			$this->_send();
		}
		private function _send() {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
			$this->return = json_decode(curl_exec($curl));
		}
	}

?>