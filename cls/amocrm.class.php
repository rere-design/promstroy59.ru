<?php

/*
	class AmoCRM
	===================================================
	Author:	Zagirskiy Ruslan Aleksandrovich
	Email:	szenprogs@gmail.com
	Skype:	szenprogs
	Site:	http://szenprogs.ru
	===================================================
	USING:
	---------------------------------------------------

	include_once('amocrm.class.php');
	$data = array (
		'account'	=> 'accountname', // https://accountname.amocrm.ru/
		'login'		=> 'login@mail.ru',
		'apikey'	=> 'apikey', // https://accountname.amocrm.ru/settings/dev/
		'userid'	=> 0,
		'name'		=> 'name',
		'email'		=> 'test@test.ru',
		'phone'		=> '+79876543210',
		'lead_custom_fields'	=> array (
			'Поле1'		=> 'Значений 1',
			'Поле2'		=> 'Значение 2',
		),
		'contact_custom_fields'	=> array (
			'Поле1'		=> 'Значений 1',
			'Поле2'		=> 'Значение 2',
		),
	);
	$amo = new AmoCRM(array (
		'account'	=>	$data['account'],
		'login'		=>	$data['login'],
		'apikey'	=>	$data['apikey'],
		'cookie'	=>	$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'cookie_amocrm.ini'
	));
	$amo->init();
	$amo->addField('name', empty($data['name']) ? 'Имя не указано' : $data['name']);
	$amo->addField('company_name', $_SERVER['HTTP_HOST']);
	$amo->addField('responsible_user_id', $data['userid']);
	$amo->addLeadField('name', $data['name']);
	$amo->addLeadField('responsible_user_id', $data['userid']);
	foreach ($data['lead_custom_fields'] as $key => $val) {
		$amo->addCustomFieldLeads($key, $val);
	}
	$amo->addCustomFieldContacts('EMAIL', $data['email']);
	$amo->addCustomFieldContacts('PHONE', $data['phone']);
	foreach ($data['contact_custom_fields'] as $key => $val) {
		$amo->addCustomFieldContacts($key, $val);
	}
	$amo->sendLead('Первичный контакт');
	$amo->sendContact();
	if ($amo->warning) var_dump($amo->warning);
*/

	Class AmoCRM {
		public $version = '5.0';
		private $_account = '';
		private $_login = '';
		private $_apikey = '';
		private $_logged_in = false;
		private $_url_auth = '';
		private $_url_api = '';
		public $_custom_fields_accounts = array ();
		public $_custom_fields_leads = array ();
		public $_fields_accounts = array ();
		public $_fields_leads = array ();
		public $_account_info = array ();

		public $response = array ();
		public $cookie = 'cookie.ini';
		public $protocol = 'https://';
		public $urlAuth = 'amocrm.ru/private/api/auth.php?type=json';
		public $urlApi = 'amocrm.ru/private/api/v2/json/';
		public $clientVersion = 'amoCRM-API-client/1.0';
		public $contact_id = 0;
		public $lead_id = 0;
		public $warning = '';

		public function setWarning($warn) {
			$this->warning = __FILE__ . ' > ' . $warn;
			return false;
		}

		public function __construct() {
			if (func_num_args() === 3) {
				$arguments = func_get_args();
				$this->_account = $arguments[0];
				$this->_login = $arguments[1];
				$this->_apikey = $arguments[2];
			} elseif (func_num_args() === 1) {
				$arguments = func_get_args();
				$arguments = $arguments[0];
				if (is_array($arguments)) {
					if (!empty($arguments['account'])) $this->_account = $arguments['account'];
					if (!empty($arguments['login'])) $this->_login = $arguments['login'];
					if (!empty($arguments['apikey'])) $this->_apikey = $arguments['apikey'];
					if (!empty($arguments['cookie'])) $this->cookie = $arguments['cookie'];
				}
			}
		}

		public function addField($name, $val) {
			$name = strtolower($name);
			$this->_fields_contacts[$name] = $val;
			return $this;
		}

		public function addLeadField($name, $val) {
			$name = strtolower($name);
			$this->_fields_leads[$name] = $val;
			return $this;
		}

		public function addCustomFieldContacts($name, $val) {
			if (!isset($this->_fields_contacts['custom_fields'])) {
				$this->_fields_contacts['custom_fields'] = array ();
			}
			$valid = false;
			foreach ($this->_custom_fields_contacts as $field) {
				if ($field['code'] == $name || $field['name'] == $name || $field['id'] == $name) {
					$field['values'] = array ();
					$field['values'][] = array (
						'value' => $val,
						'enum' => 'OTHER'
					);
					$this->_fields_contacts['custom_fields'][] = $field;
					$valid = true;
				}
			}
			return $this;
		}

		public function addCustomFieldLeads($name, $val) {
			$valid = false;
			foreach ($this->_custom_fields_leads as $field) {
				if ($field['code'] == $name || $field['name'] == $name || $field['id'] == $name) {
					$field['values'] = array ();
					$field['values'][] = array (
						'value' => $val
					);
					$this->_fields_leads['custom_fields'][] = $field;
					$valid = true;
				}
			}
			return $this;
		}

		public function sendContact() {
			if ($this->lead_id != 0) {
				$this->_fields_contacts['linked_leads_id'] = array ($this->lead_id);
			}
			$set['request']['contacts']['add'][] = $this->_fields_contacts;
			$this->_curl_connect($this->_url_api . 'contacts/set', $set);
			$this->contact_id = $this->response['contacts']['add'][0]['id'];
			return $this;
		}

		public function sendLead($status) {
			$stat = 0;
			foreach ($this->_account_info['account']['leads_statuses'] as $field) {
				if ($field['name'] == $status) {
					$stat = $field['id'];
					break;
				}
			}
			if ($stat === 0) $this->setWarning(__FUNCTION__ . ': Не найден указанный статус сделки');
			$lead['request']['leads']['add'] = array (
				array (
					'status_id'	=>	$stat,
					'price'		=>	0,
				)
			);
			$lead['request']['leads']['add'][0] = $this->_fields_leads;
			$this->_curl_connect($this->_url_api . 'leads/set', $lead);
			$this->lead_id = $this->response['leads']['add'][0]['id'];
			return $this;
		}

		public function getContacts($parametrs = false) {
			$data = '';
			if ($parametrs) $data = '?' . http_build_query($parametrs);
			$this->_curl_connect($this->_url_api . 'contacts/list' . $data);
			return $this->response;
		}

		public function getLeads($parametrs = false) {
			$data = '';
			if ($parametrs) $data = '?' . http_build_query($parametrs);
			$this->_curl_connect($this->_url_api . 'leads/list' . $data);
			return $this->response;
		}

		public function getInfo($uri) {
			$this->_curl_connect($this->_url_api . $uri);
		}

		private function _get_custom_fields_contacts() {
			foreach ($this->_account_info['account']['custom_fields']['contacts'] as $field) {
				$this->_custom_fields_contacts[] = array (
					'id'		=>	$field['id'],
					'name'		=>	$field['name'],
					'code'		=>	$field['code'],
				);
			}
		}

		private function _get_custom_fields_leads() {
			foreach ($this->_account_info['account']['custom_fields']['leads'] as $field) {
				$this->_custom_fields_leads[] = array (
					'id'		=>	$field['id'],
					'name'		=>	$field['name'],
					'code'		=>	$field['code'],
				);
			}
		}

		public function setAccount($val) {
			$this->_account = $val;
			return $this;
		}

		public function setLogin($val) {
			$this->_login = $val;
			return $this;
		}

		public function setAPIKey($key) {
			$this->_apikey = $key;
			return $this;
		}

		private function _check_response($code) {
			$code = (int) $code;
			$errors = array (
				301 => 'Moved permanently',
				400 => 'Bad request',
				401 => 'Unauthorized',
				403 => 'Forbidden',
				404 => 'Not found',
				500 => 'Internal server error',
				502 => 'Bad gateway',
				503 => 'Service unavailable'
			);
			try {
				if ($code != 200 && $code != 204) {
					throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error', $code);
				}
			} catch (Exception $E) {
				//die (__FUNCTION__ . ': Ошибка: ' . $E->getMessage() . PHP_EOL . 'Код ошибки: ' . $E->getCode());
				$this->setWarning(__FUNCTION__ . ': Ошибка: ' . $E->getMessage() . ' | Код ошибки: ' . $E->getCode());
			}
		}

		private function _curl_connect($link, $post = false) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_USERAGENT, $this->clientVersion);
			curl_setopt($curl, CURLOPT_URL, $link);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie);
			curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			if (!empty($post)) {
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post));
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			}
			$out = curl_exec($curl);
			$code = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
			$this->_check_response($code);
			if (empty($out)) {
				$this->response = array ();
				return true;
			} else {
				$this->response = json_decode($out, true);
				$this->response = $this->response['response'];
				return false;
			}
		}

		private function _autorization() {
			$user = array (
				'USER_LOGIN'	=>	$this->_login,
				'USER_HASH'		=>	$this->_apikey
			);
			$this->_curl_connect($this->_url_auth, $user);
			if (!$this->response['auth']) $this->setWarning('Авторизация не удалась');
			$this->_logged_in = true;
		}

		public function _get_account_info() {
			$this->_curl_connect($this->_url_api . 'accounts/current');
			$this->_account_info = $this->response;
		}

		public function findContact($field = '') {
			$this->_curl_connect($this->_url_api . 'contacts/list?query=' . $field);
		}

		public function init() {
			if (empty($this->_account)) return $this->setWarning('Account name is missing');
			if (empty($this->_login)) return $this->setWarning('Login is missing');
			if (empty($this->_apikey)) return $this->setWarning('API key is missing');
			$this->_url_auth = $this->protocol . $this->_account . '.' . $this->urlAuth;
			$this->_url_api = $this->protocol . $this->_account . '.' . $this->urlApi;
			$this->_autorization();
			$this->_get_account_info();
			$this->_get_custom_fields_contacts();
			$this->_get_custom_fields_leads();
		}
	}

?>