<?php

	//namespace app\inc;

/*
	Structured Highlited VarDump
	===========================
	Autor: Zazhirskiy Ruslan
	Email: szenprogs@gmail.com
	Site: http://szenprogs.ru
	===========================
	Usage:

	Dump variable or variables
	vd::dump($var1: Variant [, $var2: Variant] [, $var3: Variant] [, ...]);

	Dump variable with name
	vd::dumpn(VariableName: String, $var: Variant);
*/

	class vd {
		public static $version = '4.10';
		public static $logfile = 'vd_dump.log';
		private static $options = array (
			'private'	=>	false,
			'protected'	=>	false,
			'public'	=>	true,
		);
		private static $options_once = false;
		private static $count = 1;
		private static $line_count = 1;
		private static $now = 0;
		private static $timers = array ();
		private static $style = array (
			'title'		=>	'text-align:left;font-weight:bold;font-size:16px;color:#000;padding:0;margin:0;background:#fff;',
			'desc'		=>	'text-align:left;font-size:12px;color:#000;padding:0;margin:0;background:#fff;',
			'code'		=>	'text-align:left;border:1px solid #999;padding:5px;margin-bottom:10px;font-size:11px;color:#000;background:#fff;',
			'sec'		=>	'color:#800;',
			'key'		=>	'color:#080;',
			'val'		=>	'color:#800;word-wrap:break-word;',
			'type'		=>	'color:#00f;',
			'size'		=>	'color:#880;',
			'func'		=>	'color:#000;',
			'file'		=>	'font-style:italic;',
			'tm_block'	=>	'background:#8f8;border:1px solid #080;padding:5px;font-size:12px;text-align:left;margin:0;color:#000;font-weight:normal;margin:5px 0;',
			'tm_title'	=>	'font-weight:bold;font-size:14px;',
			'tm_value'	=>	'',
			'tm_label'	=>	'display:inline-block;width:60px;',
			'ts_table'	=>	'border:1px solid #080;',
			'ts_thead'	=>	'font-weight:bold;font-size:14px;border-bottom:1px solid #080;',
			'ts_tbody'	=>	'font-size:12px;',
			'ts_td'		=>	'text-align:left;padding:0 10px;',
			'ts_label'	=>	'font-weight:bold;',
		);
		private static $wrap = PHP_EOL;
		private static $rownum_dot = "<span style='color:#909;'>%04d</span>|\t";
		private static $rownum_line_dot = "%04d|\t";
		private static $block_dot_s = '<span class="vardump_wrap"> <span class="vardump_link" style="cursor:pointer;background:#eee;"> - </span> <span class="vardump_block" style="display:block;">';
		private static $block_dot_e = '</span></span>';
		private static $rownum = 1;
		private static function script() {
			if (self::$count !== 1) return false;
			echo ("<script type='text/javascript'>
				if (!window.jQuery) {
					var jqscript = document.createElement('script');
					jqscript.type = 'text/javascript';
					jqscript.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js';
					document.getElementsByTagName('head')[0].appendChild(jqscript);
					window.setTimeout(function() {
						$(function() {
							$('.vardump_link').click(function() {
								var btn = $(this);
								var block = $(btn).closest('.vardump_wrap').find('.vardump_block').eq(0);
								var stat = block.is(':visible');
								if (stat) {
									block.hide(100, function() {
										btn.html(' ... ');
									});
								} else {
									block.show(100, function() {
										btn.html(' - ');
									});
								}
							});
						});
					}, 1000);
				}
			</script>");
		}

		private static function getResourceData($var) {
			switch (get_resource_type($var)) {
				case 'curl':
					$data = array (
						'curl_errno()'						=>	curl_errno($var),
						'curl_error()'						=>	curl_error($var),
						'curl_getinfo()'					=>	curl_getinfo($var),
						'curl_getinfo($ch, CURLINFO_HEADER_OUT)'				=>	curl_getinfo($var, CURLINFO_HEADER_OUT),
						'curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD)'	=>	curl_getinfo($var, CURLINFO_CONTENT_LENGTH_DOWNLOAD),
						'curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_UPLOAD)'		=>	curl_getinfo($var, CURLINFO_CONTENT_LENGTH_UPLOAD),
						'curl_getinfo($ch, CURLINFO_PRIVATE)'					=>	curl_getinfo($var, CURLINFO_PRIVATE),
						'curl_getinfo($ch, CURLINFO_RESPONSE_CODE)'				=>	curl_getinfo($var, CURLINFO_RESPONSE_CODE),
						'curl_getinfo($ch, CURLINFO_HTTP_CONNECTCODE)'			=>	curl_getinfo($var, CURLINFO_HTTP_CONNECTCODE),
						'curl_getinfo($ch, CURLINFO_HTTPAUTH_AVAIL)'			=>	curl_getinfo($var, CURLINFO_HTTPAUTH_AVAIL),
						'curl_getinfo($ch, CURLINFO_PROXYAUTH_AVAIL)'			=>	curl_getinfo($var, CURLINFO_PROXYAUTH_AVAIL),
						'curl_getinfo($ch, CURLINFO_OS_ERRNO)'					=>	curl_getinfo($var, CURLINFO_OS_ERRNO),
						'curl_getinfo($ch, CURLINFO_NUM_CONNECTS)'				=>	curl_getinfo($var, CURLINFO_NUM_CONNECTS),
						'curl_getinfo($ch, CURLINFO_SSL_ENGINES)'				=>	curl_getinfo($var, CURLINFO_SSL_ENGINES),
						'curl_getinfo($ch, CURLINFO_COOKIELIST)'				=>	curl_getinfo($var, CURLINFO_COOKIELIST),
						'curl_getinfo($ch, CURLINFO_FTP_ENTRY_PATH)'			=>	curl_getinfo($var, CURLINFO_FTP_ENTRY_PATH),
						'curl_getinfo($ch, CURLINFO_APPCONNECT_TIME)'			=>	curl_getinfo($var, CURLINFO_APPCONNECT_TIME),
						'curl_getinfo($ch, CURLINFO_CONDITION_UNMET)'			=>	curl_getinfo($var, CURLINFO_CONDITION_UNMET),
						'curl_getinfo($ch, CURLINFO_RTSP_CLIENT_CSEQ)'			=>	curl_getinfo($var, CURLINFO_RTSP_CLIENT_CSEQ),
						'curl_getinfo($ch, CURLINFO_RTSP_CSEQ_RECV)'			=>	curl_getinfo($var, CURLINFO_RTSP_CSEQ_RECV),
						'curl_getinfo($ch, CURLINFO_RTSP_SERVER_CSEQ)'			=>	curl_getinfo($var, CURLINFO_RTSP_SERVER_CSEQ),
						'curl_getinfo($ch, CURLINFO_RTSP_SESSION_ID)'			=>	curl_getinfo($var, CURLINFO_RTSP_SESSION_ID),
					);
					break;
				case 'stream':
					$data = array (
						'stream_is_local($stream)'				=>	stream_is_local($var),
						'stream_supports_lock($stream)'			=>	stream_supports_lock($var),
						'stream_get_meta_data($stream)'			=>	stream_get_meta_data($var),
						'stream_get_transports()'				=>	stream_get_transports(),
						'stream_get_wrappers()'					=>	stream_get_wrappers(),
						'stream_get_filters()'					=>	stream_get_filters(),
						//'stream_get_contents($stream)'			=>	stream_get_contents($var, 200),
						'stream_context_get_options($context)'	=>	stream_context_get_options(stream_context_get_default()),
						'stream_context_get_params($context)'	=>	stream_context_get_params(stream_context_get_default()),
					);
					break;
				default:
					$data = array ();
					break;
			}
			return $data;
		}

		private static function tabs($_count) {
			$s = '';
			while ($_count > 0) {
				$s .= "\t";
				$_count--;
			}
			return $s;
		}

		private static function printd($_str, $_dot) {
			foreach ($_dot as $key => $val) {
				if (gettype($val) == 'array') {
					$val = sprintf($val[0], $val[1]);
				}
				$_str = str_replace('%' . $key . '%', $val, $_str);
			}
			echo $_str;
		}

		private static function sprintd($_str, $_dot) {
			foreach ($_dot as $key => $val) {
				if (gettype($val) == 'array') {
					$val = sprintf($val[0], $val[1]);
				}
				$_str = str_replace('%' . $key . '%', $val, $_str);
			}
			return $_str;
		}

		private static function printnum() {
			printf (self::$rownum_dot, self::$rownum++);
		}

		private static function sprintnum() {
			return sprintf (self::$rownum_line_dot, self::$rownum++);
		}

		/**
		 * Устанавливает опцию отображения свойств и методов в объектах
		 * @param bool $private		Отображать private свойства и методы
		 * @param bool $protected	Отображать protected свойства и методы
		 * @param bool $public		Отображать public свойства и методы
		 * @return void
		 */
		public static function setOptions($private = false, $protected = false, $public = true) {
			self::$options = array (
				'private'	=>	$private,
				'protected'	=>	$protected,
				'public'	=>	$public,
			);
		}

		/**
		 * Устанавливает опцию отображения свойств и методов в объектах только для следующего вызова dump, dumpn, save, saven
		 * @param bool $private		Отображать private свойства и методы
		 * @param bool $protected	Отображать protected свойства и методы
		 * @param bool $public		Отображать public свойства и методы
		 * @return void
		 */
		public static function setOptionsOnce($private = false, $protected = false, $public = true) {
			self::$options_once = array (
				'private'	=>	$private,
				'protected'	=>	$protected,
				'public'	=>	$public,
			);
		}

		private static function printv($_var, $_ts = 1) {
			$type = gettype($_var);
			if (self::$rownum == 1) self::printnum();
			if ($type == 'array') {
				self::printd('<span style="%style_type%">array</span> (<span style="%style_size%">%size%</span>) [', array (
					'style_type'	=>	self::$style['type'],
					'style_size'	=>	self::$style['size'],
					'size'			=>	array ('%d', count($_var))
				));
				echo (self::$block_dot_s);
				foreach ($_var as $key => $val) {
					self::printnum();
					if (gettype($key) == 'string') {
						self::printd('%tabs%<span style="%style_key%">\'%key%\'</span> => ', array (
							'tabs'		=>	self::tabs($_ts),
							'style_key'	=>	self::$style['key'],
							'key'		=>	$key
						));
					} else {
						self::printd('%tabs%<span style="%style_key%">%key%</span> => ', array (
							'tabs'		=>	self::tabs($_ts),
							'style_key'	=>	self::$style['key'],
							'key'		=>	$key
						));
					}
					self::printv($val, $_ts + 1);
					echo (self::$wrap);
				}
				echo (self::$block_dot_e);
				self::printnum();
				self::printd('%tabs%],', array (
					'tabs'	=>	self::tabs($_ts - 1)
				));
			} elseif ($type == 'object') {
				if (get_class($_var) == 'stdClass') {
					$cnt = 0;
					foreach ($_var as $item) {
						$cnt++;
					}
					self::printd('<span style="%style_type%">object</span> (%class_name%) (<span style="%style_size%">%size%</span>) {', array (
						'style_type'	=>	self::$style['type'],
						'style_size'	=>	self::$style['size'],
						'class_name'	=>	get_class($_var),
						'size'			=>	array ('%d', $cnt),
					));
					echo (self::$block_dot_s);
					foreach ($_var as $key => $val) {
						self::printnum();
						self::printd('%tabs%<span style="%style_key%">\'%key%\'</span> => ', array (
							'tabs'		=>	self::tabs($_ts),
							'style_key'	=>	self::$style['key'],
							'key'		=>	$key
						));
						self::printv($val, $_ts + 1);
						echo (self::$wrap);
					}
					echo (self::$block_dot_e);
					self::printnum();
					self::printd('%tabs%},', array (
						'tabs'	=>	self::tabs($_ts - 1)
					));
				} else {
					$reflection = new ReflectionClass($_var);

					$options = (self::$options_once) ? self::$options_once : self::$options;

					// Collect Constants
					$constants = array ();
					$vars = $reflection->getConstants();
					foreach ($vars as $key => $val) {
						$constants[$key] = (object) array (
							'value'		=>	$val,
						);
					}

					// Collect Properties
					$properties = array ();
					$vars = $reflection->getProperties();
					foreach ($vars as $val) {
						$val->setAccessible(true);
						$temp = (object) array (
							'visible'	=>	'',
							'static'	=>	'',
							'value'		=>	$val->getValue($_var),
						);
						if ($options['private'] and $val->isPrivate()) {
							$temp->visible = 'private ';
						} elseif ($options['protected'] and $val->isProtected()) {
							$temp->visible = 'protected ';
						} elseif ($options['public'] and $val->isPublic()) {
							$temp->visible = 'public ';
						}
						if ($val->isStatic()) $temp->static = 'static ';
						if ($temp->visible) $properties[$val->name] = $temp;
					}

					// Collect Methods
					$methods = array ();
					$vars = $reflection->getMethods();
					foreach ($vars as $val) {
						$temp = (object) array (
							'keyword'	=>	'',
							'visible'	=>	'',
							'static'	=>	'',
						);
						if ($val->isAbstract()) {
							$temp->keyword = 'abstract ';
						} elseif ($val->isFinal()) {
							$temp->keyword = 'final ';
						}
						if ($options['private'] and $val->isPrivate()) {
							$temp->visible = 'private ';
						} elseif ($options['protected'] and $val->isProtected()) {
							$temp->visible = 'protected ';
						} elseif ($options['public'] and $val->isPublic()) {
							$temp->visible = 'public ';
						}
						if ($val->isStatic()) $temp->static = 'static ';
						if ($temp->visible) $methods[$val->name] = $temp;
					}

					$cnt = count($constants) + count($properties) + count($methods);
					self::printd('<span style="%style_type%">object</span> (%class_name%) [<span style="%style_file%">%file_name%</span>] (<span style="%style_size%">%size%</span>) {', array (
						'style_type'	=>	self::$style['type'],
						'style_size'	=>	self::$style['size'],
						'style_file'	=>	self::$style['file'],
						'class_name'	=>	$reflection->getName(),
						'size'			=>	array ('%d', $cnt),
						'file_name'		=>	$reflection->getFileName(),
					));
					echo (self::$block_dot_s);

					// Constants
					foreach ($constants as $key => $val) {
						self::printnum();
						self::printd('%tabs%<span style="%style_sec%">const</span> <span style="%style_key%">%key%</span> = ', array (
							'tabs'		=>	self::tabs($_ts),
							'style_sec'	=>	self::$style['sec'],
							'style_key'	=>	self::$style['key'],
							'key'		=>	$key,
						));
						self::printv($val->value, $_ts + 1);
						echo (self::$wrap);
					}

					// Properties
					foreach ($properties as $key => $val) {
						self::printnum();
						self::printd('%tabs%<span style="%style_sec%">%visible%%static%</span><span style="%style_key%">$%key%</span> = ', array (
							'tabs'		=>	self::tabs($_ts),
							'style_sec'	=>	self::$style['sec'],
							'style_key'	=>	self::$style['key'],
							'visible'	=>	$val->visible,
							'static'	=>	$val->static,
							'key'		=>	$key,
						));
						self::printv($val->value, $_ts + 1);
						echo (self::$wrap);
					}

					// Methods
					foreach ($methods as $key => $val) {
						self::printnum();
						self::printd('%tabs%<span style="%style_sec%">%keyword%%visible%%static%</span><span style="%style_key%">function</span> <span style="%style_val%">%val%()</span>,', array (
							'tabs'		=>	self::tabs($_ts),
							'style_sec'	=>	self::$style['sec'],
							'style_key'	=>	self::$style['key'],
							'style_val'	=>	self::$style['func'],
							'val'		=>	$key,
							'keyword'	=>	$val->keyword,
							'visible'	=>	$val->visible,
							'static'	=>	$val->static,
						));
						echo (self::$wrap);
					}

					echo (self::$block_dot_e);
					self::printnum();
					self::printd('%tabs%},', array (
						'tabs'	=>	self::tabs($_ts - 1)
					));

					unset($reflection);
				}
			} elseif ($type == 'boolean') {
				self::printd('<span style="%style_type%">boolean</span> (<span style="%style_val%">%val%</span>),', array (
					'style_type'	=>	self::$style['type'],
					'style_val'		=>	self::$style['val'],
					'val'			=>	$_var ? 'true' : 'false'
				));
			} elseif ($type == 'integer') {
				self::printd('<span style="%style_type%">int</span> (<span style="%style_val%">%val%</span>),', array (
					'style_type'	=>	self::$style['type'],
					'style_val'		=>	self::$style['val'],
					'val'			=>	array ('%d', $_var)
				));
			} elseif ($type == 'double') {
				self::printd('<span style="%style_type%">float</span> (<span style="%style_val%">%val%</span>),', array (
					'style_type'	=>	self::$style['type'],
					'style_val'		=>	self::$style['val'],
					'val'			=>	array ('%f', $_var)
				));
			} elseif ($type == 'string') {
				$_var2 = str_replace('<', '&lt;', $_var);
				$_var2 = str_replace('>', '&gt;', $_var2);
				self::printd('<span style="%style_type%">string</span> (<span style="%style_size%">%size%</span>) <span style="%style_val%">\'%val%\'</span>,', array (
					'style_type'	=>	self::$style['type'],
					'style_size'	=>	self::$style['size'],
					'style_val'		=>	self::$style['val'],
					'size'			=>	array ('%d', strlen($_var)),
					'val'			=>	$_var2,
				));
			} elseif ($type == 'resource') {
				self::printd('<span style="%style_type%">resource</span> (%val%)', array (
					'style_type'	=>	self::$style['type'],
					'val'			=>	get_resource_type($_var)
				));
				$data = self::getResourceData($_var);
				if (count($data) > 0) {
					echo (':');
					echo (self::$block_dot_s);
					foreach ($data as $key => $val) {
						self::printnum();
						self::printd('%tabs%<span style="%style_key%">function</span> <span style="%style_val%">%val%</span> = ', array (
							'tabs'		=>	self::tabs($_ts),
							'style_key'	=>	self::$style['key'],
							'style_val'	=>	self::$style['func'],
							'val'		=>	$key,
						));
						self::printv($val, $_ts + 1);
						echo (self::$wrap);
					}
					echo (self::$block_dot_e);
					self::printnum();
					self::printd('%tabs%;', array (
						'tabs'	=>	self::tabs($_ts - 1)
					));
				} else {
					echo (',');
				}
			} elseif ($type == 'NULL') {
				self::printd('<span style="%style_val%">%val%</span>,', array (
					'style_val'		=>	self::$style['val'],
					'val'			=>	'NULL'
				));
			} else {
				self::printd('<span style="%style_val%">%val%</span>,', array (
					'style_val'		=>	self::$style['val'],
					'val'			=>	'UNKNOWN'
				));
			}
		}

		private static function sprintv($_var, $_ts = 1) {
			$output = '';
			$type = gettype($_var);
			if (self::$rownum == 1) $output .= self::sprintnum();
			if ($type == 'array') {
				$output .= self::sprintd('array (%size%) [', array (
					'size'			=>	array ('%d', count($_var))
				));
				$output .= self::$wrap;
				foreach ($_var as $key => $val) {
					$output .= self::sprintnum();
					if (gettype($key) == 'string') {
						$output .= self::sprintd('%tabs%\'%key%\' => ', array (
							'tabs'		=>	self::tabs($_ts),
							'key'		=>	$key
						));
					} else {
						$output .= self::sprintd('%tabs%%key% => ', array (
							'tabs'		=>	self::tabs($_ts),
							'key'		=>	$key
						));
					}
					$output .= self::sprintv($val, $_ts + 1);
					$output .= self::$wrap;
				}
				$output .= self::sprintnum();
				$output .= self::sprintd('%tabs%],', array (
					'tabs'	=>	self::tabs($_ts - 1)
				));
			} elseif ($type == 'object') {
				if (get_class($_var) == 'stdClass') {
					$cnt = 0;
					foreach ($_var as $item) {
						$cnt++;
					}
					$output .= self::sprintd('object (%class_name%) (%size%) {', array (
						'class_name'	=>	get_class($_var),
						'size'			=>	array ('%d', $cnt),
					));
					$output .= self::$wrap;
					foreach ($_var as $key => $val) {
						$output .= self::sprintnum();
						$output .= self::sprintd('%tabs%\'%key%\' => ', array (
							'tabs'		=>	self::tabs($_ts),
							'key'		=>	$key
						));
						$output .= self::sprintv($val, $_ts + 1);
						$output .= self::$wrap;
					}
					$output .= self::sprintnum();
					$output .= self::sprintd('%tabs%},', array (
						'tabs'	=>	self::tabs($_ts - 1)
					));
				} else {
					$reflection = new ReflectionClass($_var);

					$options = (self::$options_once) ? self::$options_once : self::$options;

					// Collect Constants
					$constants = array ();
					$vars = $reflection->getConstants();
					foreach ($vars as $key => $val) {
						$constants[$key] = (object) array (
							'value'		=>	$val,
						);
					}

					// Collect Properties
					$properties = array ();
					$vars = $reflection->getProperties();
					foreach ($vars as $val) {
						$val->setAccessible(true);
						$temp = (object) array (
							'visible'	=>	'',
							'static'	=>	'',
							'value'		=>	$val->getValue($_var),
						);
						if ($options['private'] and $val->isPrivate()) {
							$temp->visible = 'private ';
						} elseif ($options['protected'] and $val->isProtected()) {
							$temp->visible = 'protected ';
						} elseif ($options['public'] and $val->isPublic()) {
							$temp->visible = 'public ';
						}
						if ($val->isStatic()) $temp->static = 'static ';
						if ($temp->visible) $properties[$val->name] = $temp;
					}

					// Collect Methods
					$methods = array ();
					$vars = $reflection->getMethods();
					foreach ($vars as $val) {
						$temp = (object) array (
							'keyword'	=>	'',
							'visible'	=>	'',
							'static'	=>	'',
						);
						if ($val->isAbstract()) {
							$temp->keyword = 'abstract ';
						} elseif ($val->isFinal()) {
							$temp->keyword = 'final ';
						}
						if ($options['private'] and $val->isPrivate()) {
							$temp->visible = 'private ';
						} elseif ($options['protected'] and $val->isProtected()) {
							$temp->visible = 'protected ';
						} elseif ($options['public'] and $val->isPublic()) {
							$temp->visible = 'public ';
						}
						if ($val->isStatic()) $temp->static = 'static ';
						if ($temp->visible) $methods[$val->name] = $temp;
					}

					$cnt = count($constants) + count($properties) + count($methods);

					$output .= self::sprintd('object (%class_name%) [%file_name%] (%size%) {', array (
						'class_name'	=>	$reflection->getName(),
						'size'			=>	array ('%d', $cnt),
						'file_name'		=>	$reflection->getFileName(),
					));
					$output .= self::$wrap;
					foreach ($_var as $key => $val) {
						$output .= self::sprintnum();
						$output .= self::sprintd('%tabs%\'%key%\' => ', array (
							'tabs'		=>	self::tabs($_ts),
							'key'		=>	$key
						));
						$output .= self::sprintv($val, $_ts + 1);
						$output .= self::$wrap;
					}

					// Constants
					foreach ($constants as $key => $val) {
						$output .= self::sprintnum();
						$output .= self::sprintd('%tabs%const %key% = ', array (
							'tabs'		=>	self::tabs($_ts),
							'key'		=>	$key
						));
						$output .= self::sprintv($val->value, $_ts + 1);
						$output .= self::$wrap;
					}

					// Properties
					foreach ($properties as $key => $val) {
						$output .= self::sprintnum();
						$output .= self::sprintd('%tabs%%visible%%static%$%key% = ', array (
							'tabs'		=>	self::tabs($_ts),
							'visible'	=>	$val->visible,
							'static'	=>	$val->static,
							'key'		=>	$key,
						));
						$output .= self::sprintv($val->value, $_ts + 1);
						$output .= self::$wrap;
					}

					// Methods
					foreach ($methods as $key => $val) {
						$output .= self::sprintnum();
						$output .= self::sprintd('%tabs%%keyword%%visible%%static%function %val%(),', array (
							'tabs'		=>	self::tabs($_ts),
							'val'		=>	$key,
							'keyword'	=>	$val->keyword,
							'visible'	=>	$val->visible,
							'static'	=>	$val->static,
						));
						$output .= self::$wrap;
					}

					$output .= self::sprintnum();
					$output .= self::sprintd('%tabs%},', array (
						'tabs'	=>	self::tabs($_ts - 1)
					));

					unset($reflection);
				}
			} elseif ($type == 'boolean') {
				$output .= self::sprintd('boolean (%val%),', array (
					'val'			=>	$_var ? 'true' : 'false'
				));
			} elseif ($type == 'integer') {
				$output .= self::sprintd('int (%val%),', array (
					'val'			=>	array ('%d', $_var)
				));
			} elseif ($type == 'double') {
				$output .= self::sprintd('float (%val%),', array (
					'val'			=>	array ('%f', $_var)
				));
			} elseif ($type == 'string') {
				$output .= self::sprintd('string (%size%) \'%val%\',', array (
					'size'			=>	array ('%d', strlen($_var)),
					'val'			=>	$_var,
				));
			} elseif ($type == 'resource') {
				$output .= self::sprintd('resource (%val%)', array (
					'val'			=>	get_resource_type($_var)
				));
				$data = self::getResourceData($_var);
				if (count($data) > 0) {
					$output .= ':';
					$output .= self::$wrap;
					foreach ($data as $key => $val) {
						$output .= self::sprintnum();
						$output .= self::sprintd('%tabs%function %val% = ', array (
							'tabs'		=>	self::tabs($_ts),
							'val'		=>	$key,
						));
						$output .= self::sprintv($val, $_ts + 1);
						$output .= self::$wrap;
					}
					$output .= self::sprintnum();
					$output .= self::sprintd('%tabs%;', array (
						'tabs'	=>	self::tabs($_ts - 1)
					));
				} else {
					$output .= ',';
				}
			} elseif ($type == 'NULL') {
				$output .= self::sprintd('%val%,', array (
					'val'			=>	'NULL'
				));
			} else {
				$output .= self::sprintd('%val%,', array (
					'val'			=>	'UNKNOWN'
				));
			}
			return $output;
		}

		/**
		 * Выводит на экран дамп переменной или переменных (вводить через запятые в качестве аргумента). Принимает любой тип переменных.
		 * @param mixed $var [, mixed $var [, ...]]		Переменные
		 * @return void
		 */
		public static function dump() {
			self::script();
			$trace = debug_backtrace();
			$vars = func_get_args();
			foreach ($vars as $index => $var) {
				self::printd('<div style="%style_title%">Call #%call_num%, Variable #%var_num%:</div>%wrap%', array (
					'style_title'	=>	self::$style['title'],
					'call_num'		=>	array ('%d', self::$count),
					'var_num'		=>	array ('%d', ($index + 1)),
					'wrap'			=>	self::$wrap,
				));
				self::printd('<div style="%style_desc%">%file%:%line%</div>%wrap%', array (
					'style_desc'	=>	self::$style['desc'],
					'file'			=>	$trace[0]['file'],
					'line'			=>	$trace[0]['line'],
					'wrap'			=>	self::$wrap,
				));
				self::printd('<pre style="%style_code%">', array (
					'style_code'	=>	self::$style['code']
				));
				self::$rownum = 1;
				self::printv($var);
				echo ('</pre>');
			}
			self::$count++;
			self::$options_once = false;
		}

		/**
		 * Выводит на экран дамп переменной с именем.
		 * @param string $name		Имя переменной
		 * @param mixed $var		Переменная
		 * @return void
		 */
		public static function dumpn($name, $var) {
			self::script();
			$trace = debug_backtrace();
			self::printd('<div style="%style_title%">Call #%call_num%, Name: %var_name%:</div>%wrap%', array (
				'style_title'	=>	self::$style['title'],
				'call_num'		=>	array ('%d', self::$count),
				'var_name'		=>	$name,
				'wrap'			=>	self::$wrap
			));
			self::printd('<div style="%style_desc%">%file%:%line%</div>%wrap%', array (
				'style_desc'	=>	self::$style['desc'],
				'file'			=>	$trace[0]['file'],
				'line'			=>	$trace[0]['line'],
				'wrap'			=>	self::$wrap,
			));
			self::printd('<pre style="%style_code%">', array (
				'style_code'	=>	self::$style['code']
			));
			self::$rownum = 1;
			self::printv($var);
			echo ('</pre>');
			self::$count++;
			self::$options_once = false;
		}

		/**
		 * Сохраняет в файл дамп переменной или переменных. Файл сохраняется в корне сайта под именем, соответствующим свойству $logfile этого класса.
		 * @param mixed $var [, mixed $var [, ...]]		Переменные
		 * @return void
		 */
		public static function save() {
			$trace = debug_backtrace();
			$vars = func_get_args();
			$file = $_SERVER['DOCUMENT_ROOT'] . '/' . self::$logfile;
			$output = PHP_EOL;
			foreach ($vars as $index => $var) {
				//ob_start();
				//var_dump($var);
				$output .= self::sprintd('Call: #%call_num%, Variable: #%var_num% (%date% %time% GMT%gmt%)%eol%', array (
					'date'		=>	Date('Y-m-d'),
					'time'		=>	Date('H:i:s'),
					'gmt'		=>	Date('P'),
					'call_num'	=>	array ('%d', self::$line_count),
					'var_num'	=>	array ('%d', ($index + 1)),
					'eol'		=>	PHP_EOL,
					//'var'		=>	ob_get_contents(),
				));
				$output .= self::sprintd('File: %file%:%line%%eol%', array (
					'file'		=>	$trace[0]['file'],
					'line'		=>	$trace[0]['line'],
					'eol'		=>	PHP_EOL,
				));
				$output .= '------------------------------------------------------------------------' . PHP_EOL;
				self::$rownum = 1;
				$output .= self::sprintv($var) . PHP_EOL;
				$output .= '========================================================================' . PHP_EOL;
				//ob_end_clean();
			}
			$fpc_param = (self::$line_count === 1) ? 0 : (FILE_APPEND);
			self::$line_count++;
			file_put_contents($file, $output, $fpc_param);
			self::$options_once = false;
		}

		/**
		 * Сохраняет в файл дамп переменной с именем. Файл сохраняется в корне сайта под именем, соответствующим свойству $logfile этого класса.
		 * @param string $name		Имя переменной
		 * @param mixed $var		Переменная
		 * @return void
		 */
		public static function saven($name, $var) {
			$trace = debug_backtrace();
			$file = $_SERVER['DOCUMENT_ROOT'] . '/' . self::$logfile;
			$output = PHP_EOL;
			//ob_start();
			//var_dump($var);
			$output .= self::sprintd('Call: #%call_num%, Name: %var_name% (%date% %time% GMT%gmt%)%eol%', array (
				'date'		=>	Date('Y-m-d'),
				'time'		=>	Date('H:i:s'),
				'gmt'		=>	Date('P'),
				'call_num'	=>	array ('%d', self::$line_count),
				'var_name'	=>	$name,
				'eol'		=>	PHP_EOL,
				//'var'		=>	ob_get_contents(),
			));
			$output .= self::sprintd('File: %file%:%line%%eol%', array (
				'file'		=>	$trace[0]['file'],
				'line'		=>	$trace[0]['line'],
				'eol'		=>	PHP_EOL,
			));
			$output .= '------------------------------------------------------------------------' . PHP_EOL;
			self::$rownum = 1;
			$output .= self::sprintv($var) . PHP_EOL;
			$output .= '========================================================================' . PHP_EOL;
			//ob_end_clean();
			self::$line_count++;
			file_put_contents($file, $output, FILE_APPEND);
			self::$options_once = false;
		}

		private static function ms_to_str($ms) {
			$rms = floor($ms);
			$format = array ();
			$format['ms']		=	sprintf('%04d', ($ms - $rms) * 1000);
			$format['second']	=	sprintf('%02d', $rms % 60);
			$format['minute']	=	sprintf('%02d', $rms / 60 % 60);
			$format['hour']		=	sprintf('%02d', $rms / 3600 % 24);
			return self::sprintd('%hour%:%minute%:%second%:%ms%', $format);
		}

		/**
		 * Запускает таймер для теста скорости скриптов.
		 * @return void
		 */
		public static function timer_start() {
			self::$now = microtime(true);
		}

		/**
		 * Ставит метку времени, начиная от вызова timer_start() с выводом тайминга на экран
		 * @param string $label			Подпись к метке
		 * @param string $namespace		Пространство имен для таймера
		 * @return void
		 */
		public static function timer($label, $namespace = 'global') {
			$count = count(self::$timers[$namespace]);
			$label = trim($label);
			if ($count == 0) {
				$last = (object) array (
					'label'	=> 'Start',
					'time'	=> self::$now,
				);
			} else {
				$last = self::$timers[$namespace][$count - 1];
			}
			$current = (object) array (
				'label'	=> (empty($label) ? 'Timer #' . ($count + 1) : $label),
				'time'	=> microtime(true),
			);
			self::$timers[$namespace][] = $current;
			self::printd('<div style="%style_block%"><div style="%style_title%">%label%</div><div style="%style_value%"><span style="%style_label%">Global:</span> %global_time% <br><span style="%style_label%">Previous:</span> %last_time%</div></div>', array (
				'label'			=> $current->label,
				'global_time'	=> self::ms_to_str($current->time - self::$now),
				'last_time'		=> self::ms_to_str($current->time - $last->time),
				'style_block'	=> self::$style['tm_block'],
				'style_title'	=> self::$style['tm_title'],
				'style_value'	=> self::$style['tm_value'],
				'style_label'	=> self::$style['tm_label'],
			));
		}

		/**
		 * Ставит метку времени, начиная от вызова timer_start()
		 * @param string $label			Подпись к метке
		 * @param string $namespace		Пространство имен для таймера
		 * @return void
		 */
		public static function timerh($label, $namespace = 'global') {
			$count = count(self::$timers[$namespace]);
			$label = trim($label);
			$current = (object) array (
				'label'	=> (empty($label) ? 'Timer #' . ($count + 1) : $label),
				'time'	=> microtime(true),
			);
			self::$timers[$namespace][] = $current;
		}

		/**
		 * Выводит на экран все метки времени
		 * @return void
		 */
		public static function timer_show() {
			foreach (self::$timers as $namespace => $item) {
				self::printd('<table style="%style_table%"><thead style="%style_thead%"><tr><td colspan="3" style="%style_td%">%namespace%</td></tr></thead><tbody style="%style_tbody%">', array (
					'namespace'		=> $namespace,
					'style_table'	=> self::$style['ts_table'],
					'style_thead'	=> self::$style['ts_thead'],
					'style_tbody'	=> self::$style['ts_tbody'],
					'style_td'		=> self::$style['ts_td'],
				));
				$last = (object) array (
					'label'	=> 'Start',
					'time'	=> self::$now,
				);
				foreach ($item as $id => $val) {
					self::printd('<tr><td style="%style_td%%style_label%">%id%</td><td style="%style_td%">%global_time%</td><td style="%style_td%">%last_time%</td></tr>', array (
						'id'			=> $val->label,
						'global_time'	=> self::ms_to_str($val->time - self::$now),
						'last_time'		=> self::ms_to_str($val->time - $last->time),
						'style_td'		=> self::$style['ts_td'],
						'style_label'	=> self::$style['ts_label'],
					));
					$last = $val;
				}
				echo '</tbody></table>';
			}
		}

		/**
		 * Производит трассировку вызова функций и выводит результат на экран.
		 * @return void
		 */
		public static function trace($sort_from_root = true) {
			$trace = debug_backtrace();
			if ($sort_from_root) krsort($trace);
			self::printd('<div style="%style_title%">Trace dump:</div>%wrap%', array (
				'style_title'	=>	self::$style['title'],
				'wrap'			=>	self::$wrap
			));
			self::printd('<pre style="%style_code%">', array (
				'style_code'	=>	self::$style['code']
			));
			self::$rownum = 1;
			foreach ($trace as $item) {
				$ts = 0;
				$func = '';
				if (isset($item['class'])) $func .= $item['class'];
				if (isset($item['type'])) $func .= $item['type'];
				if (isset($item['function'])) $func .= $item['function'];
				self::printnum();
				self::printd('%tabs%[<span style="%style_file%">%file%:%line%</span>]%wrap%', array (
					'tabs'			=>	self::tabs($ts),
					'style_file'	=>	self::$style['file'],
					'file'			=>	$item['file'],
					'line'			=>	$item['line'],
					'wrap'			=>	self::$wrap,
				));
				self::printnum();
				self::printd('%tabs%<span style="%style_key%">%func%</span> (', array (
					'tabs'			=>	self::tabs($ts),
					'style_key'		=>	self::$style['key'],
					'func'			=>	$func,
				));
				if (count($item['args'])) echo (self::$wrap);
				$ts++;
				foreach ($item['args'] as $key => $arg) {
					self::printnum();
					echo (self::tabs($ts));
					$ts++;
					self::printv($arg, $ts);
					$ts--;
					echo (self::$wrap);
				}
				if (count($item['args'])) {
					$ts--;
					self::printnum();
					self::printd('%tabs%);%wrap%', array (
						'tabs'			=>	self::tabs($ts),
						'wrap'			=>	self::$wrap,
					));
				} else {
					echo (');' . self::$wrap);
				}
				self::printnum();
				echo (self::$wrap);
			}
			echo ('</pre>');
		}

		/**
		 * Производит трассировку вызова функций и сохраняет результат в файл.
		 * @param bool $sort_from_root		Сортировать от корня вызовов
		 * @return void
		 */
		public static function strace($sort_from_root = true) {
			$trace = debug_backtrace();
			if ($sort_from_root) krsort($trace);
			$file = $_SERVER['DOCUMENT_ROOT'] . '/' . self::$logfile;
			$output = self::sprintd('Trace dump:%eol%', array (
				'eol'	=>	PHP_EOL,
			));
			$output .= '------------------------------------------------------------------------' . PHP_EOL;
			self::$rownum = 1;
			foreach ($trace as $item) {
				$ts = 0;
				$func = '';
				if (isset($item['class'])) $func .= $item['class'];
				if (isset($item['type'])) $func .= $item['type'];
				if (isset($item['function'])) $func .= $item['function'];
				$output .= self::sprintnum();
				$output .= self::sprintd('%tabs%[%file%:%line%]%eol%', array (
					'tabs'	=>	self::tabs($ts),
					'file'	=>	$item['file'],
					'line'	=>	$item['line'],
					'eol'	=>	PHP_EOL,
				));
				$output .= self::sprintnum();
				$output .= self::sprintd('%tabs%%func% (', array (
					'tabs'	=>	self::tabs($ts),
					'func'	=>	$func,
				));
				if (count($item['args'])) $output .= PHP_EOL;
				$max_args = count($item['args']) - 1;
				$ts++;
				foreach ($item['args'] as $key => $arg) {
					$output .= self::sprintnum();
					$output .= self::tabs($ts);
					$ts++;
					$output .= self::sprintv($arg, $ts);
					$output .= PHP_EOL;
					$ts--;
				}
				if (count($item['args'])) {
					$ts--;
					$output .= self::sprintnum();
					$output .= self::sprintd('%tabs%);%eol%', array (
						'tabs'		=>	self::tabs($ts),
						'eol'		=>	PHP_EOL,
					));
				} else {
					$output .= ');' . PHP_EOL;
				}
				$output .= self::sprintnum();
				$output .= PHP_EOL;
			}
			$output .= '========================================================================' . PHP_EOL;
			$fpc_param = (self::$line_count === 1) ? 0 : (FILE_APPEND);
			self::$line_count++;
			file_put_contents($file, $output, $fpc_param);
		}

		public static function init() {
			self::timer_start();
		}
	}

	vd::init();

?>