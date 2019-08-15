/*
	Landing JS-Core
	version: 7.10
	Автор:	Руслан Александрович
	Контакты
		Skype:	SzenProgs
		Email:	szenprogs@gmail.com
		Site:	http://szenprogs.ru

	Содержит:
		$.loadListener();
		$.isMobile();
		$.SmoothScroll();
		$(selector).popup();
		$(selector).submitForm();
		$(selector).appendScroll();
		$(selector).fixedBlock();
		$(selector).slider();
		$(selector).backgroundVideo();
		$(selector).onLeave();
		setCookie();
		getCookie();

	Зависимости:
		jquery
		jquery.colorbox
		owl.carousel
*/

(function($) {

	var loaded_scripts = [];

	/*
		Отслеживает хеш в адресае при загрузке страницы и выполняет действие, которое к этому хешу привязано.
		Допустим, есть блок с id=block и есть ссылка, которая выводит этот блок в модальное окно.
		При открытии сатраницы с хешем #block в адресе - откроется модальное окно.

			$.loadListener({
				scroll:			bool,						// true		Прокрутка до элемента
				scrollSpeed:	int,						// 1000		Скорость прокрутки (ms)
				click:			bool,						// true		Выполнить привязанное действие
				clickTimeout:	int,						// 1200		Задержка до выполняемого действия
				timeout:		int,						// 2000		Задержка на выполнение функции
				before:			function($targets, hash),	// false	Callback до выполнения функции
								$targets:	jQueryObject,	// Объекты, к которым привязаны обработчики, связанные с хешем
								hash:		str,			// Хеш
				after:			function($targets, hash),	// false	Callback после выполнения функции
								$targets:	jQueryObject,	// Объекты, к которым привязаны обработчики, связанные с хешем
								hash:		str,			// Хеш
			});

		return null;
	*/
	$.loadListener = function(options) {
		var _f_after = function(opt, $target, hash) {
			if (opt.click) {
				window.setTimeout(function() {
					$target.click();
					if (opt.after && typeof(opt.after) === 'function') opt.after($targets, hash);
				}, opt.clickTimeout);
			} else {
				if (opt.after && typeof(opt.after) === 'function') opt.after($targets, hash);
			}
		};
		var o = $.extend({
			scroll: true,
			scrollSpeed: 1000,
			click: true,
			clickTimeout: 1200,
			timeout: 2000,
			before: false,
			after: false,
		}, options);
		window.setTimeout(function() {
			var hash = window.location.hash;
			if (hash === '') return false;
			$targets = $('a[href*=' + hash + ']');
			if ($targets.length === 0) return false;
			$target = $targets.eq(0);
			if (o.before && typeof(o.before) === 'function') o.before($targets, hash);
			if (o.scroll) {
				var $el = $(hash);
				if ($(hash).is(':visible')) {
					_f_after(o, $target, hash);
				} else {
					var wh = $( window ).height() / 2 | 0;
					$('html,body').animate({
						scrollTop: $target.offset().top - wh
					}, o.scrollSpeed, function() {
						_f_after(o, $target, hash);
					});
				}
			} else {
				_f_after(o, $target, hash);
			}
		}, o.timeout);
	};

	$.loadScript = function(src, afterload) {
		if (loaded_scripts.indexOf(src) == -1) {
			loaded_scripts.push(src);
			var $script = $('<script>');
			$script.attr({
				src: src,
				async: true,
			}).ready(function() {
				console.log(src);
				afterload();
			});
			$('head').append($script);
		} else {
			afterload();
		}
	};



	/*
		Возвращает элемент со смещением относительно определенного элемента коллекции.

			$(selector).select(
				current:	jQueryObject,	// *	Элемент коллекции
				offset:		int,			// 1	Смещение
			);

		return jQueryObject;
	*/
	$.fn.select = function(current, offset) {
		var
			$this = $(this),
			len = $this.length;
		if (typeof(current) === 'object') current = $this.index(current);
		current = current || 0;
		current = (current < 0) ? 0 : current;
		offset = offset || 1;
		current = current + offset;
		if (offset >= 0) {
			while (current >= len) current = current - len;
		} else {
			while (current < 0) current = len + current;
		}
		return $this.eq(current);
	};



	/*
		Удаляет все теги из строки и возвращает результат.

			$.stripTags(
				str:	str,	// *		Обрабатываемая строка
				wrap:	bool,	// false	Преобразовывать <br> в переносы
			);

		return str;
	*/
	$.stripTags = function(str, wrap) {
		wrap = wrap || false;
		if (wrap) str = str.replace(/<br[\s\/]*>\s*/gi, '\n');
		return str.replace(/<[^>]+>/gi, '');
	};



	/*
		Возвращает истину, если элемент существует и ложь, если элемента нет

			$(selector).exists();

		return bool;
	*/
	$.fn.exists = function() {
		return $(this).length !== 0;
	};


	/*
		Создает эффект вкладок со скрытыми блоками и ссылками для доступа к ним.

			$(selector).tabs({
				speed:			int,							// 400						Скорость перехода между вкладками
				target:			str,							// '.item'					Селектор блоков
				selectClass:	str,							// 'selected'				Класс выделенного элемента
				animate:		str,							// 'fade'|'hide'|'slide'	Тип анимации
				init:			function($this, $target, o),	// false					Callback инициализации функции
								$this:		jQueryObject,		// Объект вкладки
								$target:	jQueryObject,		// Блок, к которому ведет ссылка вкладки
								o:			Object,				// Опции вызова функции
				before:			function($this, $target, o),	// false					Callback до перехода на вкладку
								$this:		jQueryObject,		// Объект вкладки
								$target:	jQueryObject,		// Блок, к которому ведет ссылка вкладки
								o:			Object,				// Опции вызова функции
				after:			function($this, $target, o),	// false					Callback после перехода на вкладку
								$this:		jQueryObject,		// Объект вкладки
								$target:	jQueryObject,		// Блок, к которому ведет ссылка вкладки
								o:			Object,				// Опции вызова функции
			});

		return jQueryObject;
	*/
	$.fn.tabs = function( options ) {
		var $tabs = this;
		return this.each(function() {
			var $this = $(this);
			var o = $.extend({
				speed: 400,
				target: '.item',
				selectClass: 'selected',
				animate: 'fade',
				init: false,
				before: false,
				after: false,
			}, options, $this.data());

			var id = $this.attr('href');
			var $target = $(id);
			if (!$target.exists()) return true;
			if ($target.is(':visible')) $this.addClass(o.selectClass);
			if (o.init && typeof(o.init) === 'function') o.init($this, $target, o);

			$this.on('click', function(e) {
				var id = $this.attr('href');
				var $target = $(id);
				if (o.before && typeof(o.before) === 'function') {
					var res = o.before($this, $target, o);
					if (typeof(res) != 'undefined') return res;
				}
				e.preventDefault();
				$tabs.removeClass(o.selectClass);
				$(this).addClass(o.selectClass);

				var $visible = $(o.target).not(id).filter(':visible');

				if (o.animate == 'hide') {
					$visible.hide(o.speed, function() {
						$target.show(o.speed, function() {
							if (o.after && typeof(o.after) === 'function') o.after($this, $target, o);
						});
					});
				} else if (o.animate == 'fade') {
					$visible.fadeOut(o.speed, function() {
						$target.fadeIn(o.speed, function() {
							if (o.after && typeof(o.after) === 'function') o.after($this, $target, o);
						});
					});
				} else {
					$visible.slideUp(o.speed, function() {
						$target.slideDown(o.speed, function() {
							if (o.after && typeof(o.after) === 'function') o.after($this, $target, o);
						});
					});
				}
			});
		});
	};

	$.fn.toggleBlock = function( options ) {
		_scroll = function($t, opt) {
			if (!opt.scroll) return false;
			$('html,body').animate({
				scrollTop: $t.offset().top - opt.scrollOffset
			}, opt.speed);
		};
		return this.each(function() {
			var $this = $(this);
			var o = $.extend({
				speed: 'normal',
				animate: 'slide',
				once: false,
				openedText: $this.html(),
				closedText: $this.html(),
				scroll: true,
				scrollOffset: 0,
				activeClass: 'active',
			}, options, $this.data());
			var $target = $($this.attr('href'));
			if (!$target.exists) return true;

			var rel = $this.attr('rel');
			var $group = $('a[rel=' + rel + ']');
			if ($group.exists()) {
				var filter = ':eq(0)';
				if ($group.hasClass(o.activeClass)) filter = '.' + o.activeClass;
				$group.not(filter).each(function() {
					$(this).removeClass(o.activeClass);
					var $toHide = $($(this).attr('href'));
					if ($toHide.is(':visible')) $toHide.hide();
				});
				$group.filter(filter).addClass(o.activeClass);
			} else {
				if (!$this.hasClass(o.activeClass)) $target.hide();
			}

			$this.click(function() {
				if ($target.is(':visible')) {
					if (o.animate == 'hide') {
						$target.hide(o.speed);
					} else if (o.animate == 'fade') {
						$target.fadeOut(o.speed);
					} else {
						$target.slideUp(o.speed);
					}
					$this.removeClass(o.activeClass);
					$this.html(o.closedText);
				} else {
					if ($group.exists()) {
						$group.not($this).removeClass(o.activeClass).each(function() {
							var id = $(this).attr('href');
							if ($(id).is(':visible')) {
								if (o.animate == 'hide') {
									$(id).hide(o.speed);
								} else if (o.animate == 'fade') {
									$(id).fadeOut(o.speed);
								} else {
									$(id).slideUp(o.speed);
								}
							}
						});
					}
					if (o.animate == 'hide') {
						$target.show(o.speed, function() {_scroll($target, o);});
					} else if (o.animate == 'fade') {
						$target.fadeIn(o.speed, function() {_scroll($target, o);});
					} else {
						$target.slideDown(o.speed, function() {_scroll($target, o);});
					}
					if (o.once) $this.remove();
					$this.addClass(o.activeClass);
					$this.html(o.openedText);
				}
				return false;
			});
		});
	};

	$.fn.popup = function( options, cbopt ) {
		return this.each(function() {
			var $this = $(this);
			var o = $.extend({
				close: '.close',
				before: false,
				after: false,
			}, options);
			var cbo = $.extend({
				inline: true,
				fixed: true,
				transition: 'fade',
				closeButton: true,
				maxHeight: '95%',
				className: 'cbx_popupwin',
				onOpen: function() {
					if (o.before && typeof(o.before) === 'function') o.before($this);
				},
				onClosed: function() {
					if (o.after && typeof(o.after) === 'function') o.after($this);
				},
			}, options);
			$this.click(function(event) {
				event.preventDefault();
				var id = $(this).attr('href');
				if (!$(id).exists()) {
					window.alert('Форма с идентификатором ' + id + ' на странице отсуствует. Сообщите администратору!');
					return false;
				}
				var $close = $(id).find('.close');
				cbo.closeButton = $close.length === 0;
				cbo.href = id;
				$.colorbox(cbo);
				if ($close.length === 0) return true;
				$close.click(function() {
					$.colorbox.close();
					return false;
				});
			});
		});
	};

	$.fn.submitForm = function( options, cbopt ) {
		openForm = function( id, colorboxOptions ) {
			if ($(id).length === 0) return false;
			colorboxOptions = $.extend({
				href: id,
				className: 'cbx_popupwin',
				closeButton: !$(id).find('.close').exists(),
			}, colorboxOptions);
			$.colorbox(colorboxOptions);
		};
		return this.each(function() {
			var $form = $(this);

			var o = $.extend({
				dataType: 'json',
				okForm: '#popup_ok',
				errorClass: '.errorMessage',
				errorForm: '#popup_error',
				returnSelector: '.result',
				clearFields: true,
				errorSelectClass: 'error',
				errorDelay: 3000,
				success: false,
				error: false,
				speed: 'normal',
				loadText: 'Идет отправка данных...',
			}, options, $form.data());

			var cbo = $.extend({
				inline: true,
				fixed: true,
				transition: 'fade'
			}, cbopt);

			$form.bind('submit', function( event ) {
				event.preventDefault();
				var $submit = $form.find('[type=submit]');
				var submit_text = $submit.html();
				$submit.prop('disabled', true);
				$submit.html(o.loadText);
				var data = new FormData();
				$form.find('input[type=file]').each(function(){
					data.append($(this).attr('name'), this.files[0]);
				});
				$.each($form.serializeArray(), function(){
					data.append(this.name, this.value);
				});
				data.append('FSID', getCookie('FSID'));
				$err = $form.find(o.errorClass);
				$errMess = $err;
				if ($err.exists()) {
					while ($errMess.children().length > 0) {
						$errMess = $errMess.children().filter(':first');
					}
				}
				$err.hide();
				$errMess.empty();
				$.ajax({
					type: $form.attr('method') || 'post',
					url: $form.attr('action') || '',
					data: data,
					processData: false,
					contentType: false,
					dataType: o.dataType,
					success: function (d) {
						console.log(d);
						if (d.status == 'success') {
							if (o.success && typeof(o.success) === 'function') o.success($form, d);
							if ($(o.okForm).exists()) {
								$(o.okForm).find(o.returnSelector).html(d.message);
								openForm(o.okForm, cbo);
							} else {
								alert($.stripTags(d.message, true));
							}
							if (o.clearFields) $form.trigger('reset');
							$submit.html(submit_text);
							$submit.prop('disabled', false);
						} else {
							if (o.error && typeof(o.error) === 'function') o.error($form, d);
							if ($form.find(d.input).not('[type=hidden]').exists()) {
								$('html,body').animate({
									scrollTop: $form.find(d.input).offset().top - 200
								}, o.speed, function() {
									var $focus_field = $form.find(d.input).not('[type=hidden]');
									var focus_data = $focus_field.data('focus');
									if (focus_data) $focus_field = $form.find('.' + focus_data);
									$focus_field.addClass(o.errorSelectClass).focus();
									if ($err.exists()) {
										$err.show();
										$errMess.html(d.message);
									}
									window.setTimeout(function() {
										$focus_field.removeClass(o.errorSelectClass);
										if ($err.exists()) {
											$err.hide();
											$errMess.empty();
										}
									}, o.errorDelay);
								});
							} else if ($err.exists()) {
								if ($err.exists()) {
									$err.show();
									$errMess.html(d.message);
								}
								window.setTimeout(function() {
									$form.find(d.input).removeClass(o.errorSelectClass);
									if ($err.exists()) {
										$err.hide();
										$errMess.empty();
									}
								}, o.errorDelay);
							} else if($(o.errorForm).exists()) {
								$(o.errorForm).find(o.returnSelector).html(d.message);
								openForm(o.errorForm, cbo);
							} else {
								alert($.stripTags(d.message, true));
							}
							$.colorbox.resize();
							$submit.html(submit_text);
							$submit.prop('disabled', false);
						}
						if (d.warning) {
							if (typeof(d.warning) === 'object') {
								$.each(d.warning, function(key, val) {
									console.warn(val);
								});
							} else {
								console.warn(d.warning);
							}
						}
					},
					error: function (d) {
						if (o.error && typeof(o.error) === 'function') o.error($form, d);
						console.log(d);
						var error_text = '';
						if (d.responseText) {
							error_text = d.responseText;
						} else {
							error_text = d.statusText;
						}
						if ($err.exists()) {
							$err.show();
							$errMess.html(error_text);
							window.setTimeout(function() {
								$err.hide();
								$errMess.empty();
							}, o.errorDelay);
						} else if($(o.errorForm).exists()) {
							$(o.errorForm).find(o.returnSelector).html(error_text);
							openForm(o.errorForm, cbo);
						} else {
							alert(error_text);
						}
						$.colorbox.resize();
						$submit.html(submit_text);
						$submit.prop('disabled', false);
					}
				});
				return false;
			});
		});
	};

	$.fn.appendScroll = function( options ) {
		return this.each(function() {
			var o = $.extend({
				speed: 1000,
				debug: true,
				deltaY: 0,
			}, options);
			$(this).bind('click', function() {
				if (location.pathname.replace(/^\//,'') != this.pathname.replace(/^\//, '') || location.hostname != this.hostname) {
					if (o.debug) console.warn('$.appendScroll: Link follow to other domen');
					return true;
				}
				var $target = $(this.hash);
				$target = $target.length ? $target : $('[name=' + this.hash.slice(1) + ']');
				if (!$target.length) {
					if (o.debug) console.warn('$.appendScroll: Target ' + this.hash + ' or [name=' + this.hash.slice(1) + '] not found');
					return true;
				}
				$('html,body').animate({
					scrollTop: $target.offset().top + o.deltaY
				}, o.speed);
				return false;
			});
		});
	};

	$.fn.fixedBlock = function( options ) {
		init = function($e, ey, opt) {
			var sy = $(document).scrollTop();
			if (sy > ey) $e.addClass(opt.className); else $e.removeClass(opt.className);
		};
		return this.each(function() {
			var o = $.extend({
				deltaY: 0,
				className: 'fixed',
			}, options);
			var $this = $(this);
			var ey = $this.offset().top + o.deltaY;
			init($this, ey, o);
			$(document).bind('scroll', function() {
				init($this, ey, o);
				return true;
			});
		});
	};

	$.fn.slider = function( options ){
		return this.each(function() {
			var $this = $(this);
			var $parent = $this.parent().parent();
			var o = $.extend({
				prev: '.prev',
				next: '.next',
				dots: '.dots',
				dot: '.dot',
				speed: 400,
			}, options);
			var opt = $.extend({
				items: 3,
				startPosition: 0,
				dotsContainer: $parent.find(o.dots),
			}, $this.data());
			var cnt = $this.children().length;
			if (typeof(opt.loop) === 'undefined') {
				if (cnt > 1) opt.loop = true; else opt.loop = false;
			}
			$this.owlCarousel(opt);
			$parent.find(o.prev).click(function() {
				$this.trigger('prev.owl.carousel', [o.speed]);
				return false;
			});
			$parent.find(o.next).click(function() {
				$this.trigger('next.owl.carousel', [o.speed]);
				return false;
			});
			$parent.find(o.dot).click(function() {
				$this.trigger('to.owl.carousel', [$(this).index(), o.speed]);
				return false;
			});
		});
	};

	$.fn.vslider = function( options ) {
		return this.each(function() {
			var $this = $(this);
			var o = $.extend({
				items: 3,
				step: 1,
				prev: '.prev',
				next: '.next',
				full: '.full',
				list: 'ul'
			}, options, $this.data());
			var $nav = $this.find(o.prev).add($this.find(o.next));
			var $full = $this.find(o.full);
			var $list = $this.find(o.list);
			$this.data('index', 0);
			$nav.click(function() {
				var d = 0;
				if ($(this).is(o.prev)) d = 1;
				if ($(this).is(o.next)) d = -1;
				if (d === 0) return false;
				var $ch = $list.children();
				var h = $ch.eq(0).height();
				var l = $ch.length;
				var gh = h * l;
				$list.height(gh);
				var pos = $this.data('index');
				pos = pos + d * h;
				if (pos > 0) pos = o.items * h - gh;
				if (pos < (o.items - l) * h) pos = 0;
				$list.animate({
					top: pos
				}, 'normal');
				$this.data('index', pos);
				return false;
			});
			$list.find('a').click(function() {
				var src = $(this).attr('href');
				var full = $(this).data('full');
				$(o.full).attr('src', full);
				$(o.full).parent('a').attr('href', src);
				return false;
			});
		});
	};

	$.isMobile = function() {
		mobile = {
			Android: /Android/i.test(navigator.userAgent),
			BlackBerry: /BlackBerry/i.test(navigator.userAgent),
			iOS: /iPhone|iPad|iPod/i.test(navigator.userAgent),
			Opera: /Opera Mini/i.test(navigator.userAgent),
			Windows: /IEMobile/i.test(navigator.userAgent),
		};
		var b = false;
		$.each(mobile, function(key, value) {
			if (value) {
				b = true;
				return false;
			}
		});
		return b;
	};

	$.fn.backgroundVideo = function( options ){
		if (!$.isMobile()) {
			return this.each(function(){
				var o = $.extend({
					mp4: 'video/bg.mp4',
					webm: 'video/bg.webm',
					width: 1920,
					ratio: '16:9',
					background: '#000',
					opacity: 0.6,
					offset: 0,
					crop: true,
				}, options);

				$(this).css({
					position: 'relative'
				}).children().css({
					position: 'relative',
					zIndex: 1
				});
				var size = o.ratio.split(':');
				var v_height = Math.round(o.width / size[0] * size[1]);
				var p_height = $(this).height();
				var $wrap = $('<div>').css({
					position: 'absolute',
					zIndex: 0,
					top: o.offset,
					left: 0,
					width: '100%',
					overflow: 'hidden'
				});
				if (o.crop) $wrap.css({height: p_height});
				var $v = $('<video>').attr({
					autoplay: 'autoplay',
					loop: 'loop',
					width: o.width,
					height: v_height
				}).css({
					position: 'absolute',
					zIndex: 0,
					top: 0,
					left: '50%',
					marginLeft: -Math.round(o.width / 2),
				});
				var $s_mp4 = $('<source>').attr({
					src: o.mp4,
					type: 'video/mp4',
					media: 'screen'
				});
				var $s_webm = $('<source>').attr({
					src: o.webm,
					type: 'video/webm',
					media: 'screen'
				});
				var $mask = $('<div>').css({
					background: o.background,
					opacity: o.opacity,
					width: o.width,
					height: v_height,
					position: 'absolute',
					zIndex: 1,
					top: 0,
					left: 0
				});
				$v.append($s_mp4).append($s_webm);
				$wrap.append($v).append($mask);
				$(this).append($wrap);
			});
		}
	};

	$.fn.backgroundSlider = function( options ) {
		if (!$.isMobile()) {
			return this.each(function(){
				var o = $.extend({
					selector: '',
					speed: 2000,
					delay: 5000,
					index: 0,
				}, options);

				var $img = $(this).children(o.selector);
				$img.not(':eq(0)').fadeTo(1, 0);
				var l = $img.length;
				if (o.index > l - 1 || o.index < 0) o.index = 0;
				setInterval(function() {
					$img.not(':eq(' + o.index + ')').fadeTo(o.speed, 0.0);
					$img.eq(o.index).fadeTo(o.speed, 1.0);
					o.index++;
					if (o.index >= l) o.index = 0;
				}, o.delay);
			});
		}
	};

	setCookie = function( name, value, options ) {
		options = options || {};
		var expires = options.expires;
		if (typeof expires == 'number' && expires) {
			var d = new Date();
			d.setTime(d.getTime() + expires * 1000);
			expires = options.expires = d;
		}
		if (expires && expires.toUTCString) {
			options.expires = expires.toUTCString();
		}
		value = encodeURIComponent(value);
		var updatedCookie = name + '=' + value;
		for(var propName in options) {
			updatedCookie += '; ' + propName;
			var propValue = options[propName];
			if (propValue !== true) updatedCookie += '=' + propValue;
		}
		document.cookie = updatedCookie;
	};

	getCookie = function( name ) {
		var matches = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	};

	$.fn.onLeave = function( options ){
		var o = $.extend({
			href: false,
			expires: 86400
		}, options);
		var olflag = false;
		$(document).mouseleave(function() {
			if (!getCookie('olflag') && !olflag) {
				setCookie('olflag', '1', {expires: o.expires});
				olflag = true;
				$.colorbox({
					href: o.href,
					inline: true,
					fixed: true,
					transition: 'fade',
					closeButton: false
				});
			}
		});
	};

	$.fn.videoLoad = function( options ) {
		var matches = {
			'www.youtube.com': {
				type:	'youtube',
				re:		/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/,
				url:	'https://www.youtube.com/embed/%id%?rel=0&showinfo=0&autoplay=1',
				scr:	'https://img.youtube.com/vi/%id%/hqdefault.jpg',
				thumb:	'https://img.youtube.com/vi/%id%/mqdefault.jpg',
			},
			'youtube.com': {
				type:	'youtube',
				re:		/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/,
				url:	'https://www.youtube.com/embed/%id%?rel=0&showinfo=0&autoplay=1',
				scr:	'https://img.youtube.com/vi/%id%/hqdefault.jpg',
				thumb:	'https://img.youtube.com/vi/%id%/mqdefault.jpg',
			},
			'youtu.be': {
				type:	'youtube',
				re:		/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/,
				url:	'https://www.youtube.com/embed/%id%?rel=0&showinfo=0&autoplay=1',
				scr:	'https://img.youtube.com/vi/%id%/hqdefault.jpg',
				thumb:	'https://img.youtube.com/vi/%id%/mqdefault.jpg',
			},
			'www.youtu.be': {
				type:	'youtube',
				re:		/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/,
				url:	'https://www.youtube.com/embed/%id%?rel=0&showinfo=0&autoplay=1',
				scr:	'https://img.youtube.com/vi/%id%/hqdefault.jpg',
				thumb:	'https://img.youtube.com/vi/%id%/mqdefault.jpg',
			},
		};
		return this.each(function() {
			var o = $.extend({
				realThumb: true,
			}, options);
			var match = matches[$(this)[0].host];
			if (typeof match == 'undefined') return true;
			var href = $(this).attr('href');
			var vid = href.match(match.re)[1];
			var url = match.url.replace('%id%', vid);
			var scr = match.scr.replace('%id%', vid);
			var $img = $(this).find('img');
			if (!$img.exists()) {
				$img = $('<img>').attr({
					alt: '',
				});
			}
			if (o.realThumb) {
				$img.attr('src', scr);
				var $play = $('<span>').css({
					display: 'block',
					background: 'url(/js/images/owl.video.play.png) no-repeat center center',
					position: 'absolute',
					zIndex: 0,
					width: '80px',
					height: '80px',
					top: '50%',
					left: '50%',
					marginTop: '-40px',
					marginLeft: '-40px',
				});
				$(this).css({
					display: 'block',
					position: 'relative',
				}).append($play);
			}
			$(this).on('click', function(e) {
				e.preventDefault();
				var $iframe = $('<iframe>').attr({
					src: url,
					allowfullscreen: true,
				});
				$(this).html($iframe);
			});
		});
	};

	$.fn.playlist = function( options ) {
		function get_screen(href, scr, opt) {
			return $('<a>').addClass(opt.itemLinkClass).attr({
				href: href,
				target: '_blank',
			}).append(
				$('<img>').attr({
					src: scr,
					alt: '',
				}).addClass(opt.screenClass)
			).append(
				$('<img>').attr({
					src: opt.playImg,
					alt: 'play',
				}).addClass(opt.playClass)
			).click(function(e) {
				e.preventDefault();
				var $item = $(this).closest('.' + opt.itemClass);
				var $iframe = get_video($item.data('url'), opt);
				$item.addClass(opt.playingClass).html('');
				$item.append($iframe);
			});
		}

		function get_video(url, opt) {
			return $('<iframe>').attr({
				src: url,
				allowfullscreen: true,
			});
		}

		var matches = {
			'www.youtube.com': {
				type:	'youtube',
				re:		/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/,
				url:	'https://www.youtube.com/embed/%id%?rel=0&showinfo=0&autoplay=1',
				scr:	'https://img.youtube.com/vi/%id%/hqdefault.jpg',
				thumb:	'https://img.youtube.com/vi/%id%/mqdefault.jpg',
			},
			'youtube.com': {
				type:	'youtube',
				re:		/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/,
				url:	'https://www.youtube.com/embed/%id%?rel=0&showinfo=0&autoplay=1',
				scr:	'https://img.youtube.com/vi/%id%/hqdefault.jpg',
				thumb:	'https://img.youtube.com/vi/%id%/mqdefault.jpg',
			},
			'youtu.be': {
				type:	'youtube',
				re:		/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/,
				url:	'https://www.youtube.com/embed/%id%?rel=0&showinfo=0&autoplay=1',
				scr:	'https://img.youtube.com/vi/%id%/hqdefault.jpg',
				thumb:	'https://img.youtube.com/vi/%id%/mqdefault.jpg',
			},
			'www.youtu.be': {
				type:	'youtube',
				re:		/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/,
				url:	'https://www.youtube.com/embed/%id%?rel=0&showinfo=0&autoplay=1',
				scr:	'https://img.youtube.com/vi/%id%/hqdefault.jpg',
				thumb:	'https://img.youtube.com/vi/%id%/mqdefault.jpg',
			},
		};

		var query = this;
		return this.each(function() {
			var $parent = $(this);
			var o = $.extend({
				itemSelector:	'li',
				itemIgnore:		'.button',
				speed:			300,
				click:			false,
				beforeClick:	false,
				afterClick:		false,
				play:			false,
				beforePlay:		false,
				afterPlay:		false,
				showThumb:		false,
				showMenuTitle:	true,
				playlistClass:	'pl_container',
				menuClass:		'pl_menu',
				menuTitleClass:	'pl_menu_title',
				menuItemsClass:	'pl_menu_items',
				menuItemClass:	'pl_menu_item',
				menuLinkClass:	'pl_menu_link',
				menuItemTitleClass:	'pl_menu_item_title',
				menuItemThumbClass:	'pl_menu_item_thumb',
				itemsClass:		'pl_items',
				itemsWrapClass:	'pl_items_wrap',
				itemClass:		'pl_item',
				itemLinkClass:	'pl_item_link',
				screenClass:	'pl_screen',
				playClass:		'pl_play',
				currentClass:	'pl_current',
				playingClass:	'pl_playing',
				playImg:		'/images/play.png',
				menuTitleText:	'Выбор видео:',
			}, options, $parent.data());

			var playlistNum = $(query.selector).index($(this));
			var playlistId = query.selector;
			playlistId = playlistId.replace(/^\.|\#/gi, '').replace(/\s*\.|\s*\#|\s+|\>|\*/gi, '_') + '_' + playlistNum + '_';

			var $pl_menu_items = $('<ul>').addClass(o.menuItemsClass);
			var $pl_items = $('<div>').addClass(o.itemsWrapClass);
			var id = 0;
			$parent.find(o.itemSelector).each(function() {
				var $video_link = $(this).find('a').not(o.itemIgnore);
				var $ignored_item = $(this).find(o.itemIgnore);
				if ($ignored_item.length === 0) $ignored_item = '';
				var match = matches[$video_link[0].host];
				if (typeof match == 'undefined') return true;
				var href = $video_link.attr('href');
				var title = $video_link.html();
				var vid = href.match(match.re)[1];
				var url = match.url.replace('%id%', vid);
				var scr = match.scr.replace('%id%', vid);
				var thumb = match.thumb.replace('%id%', vid);
				var current = '';
				if (id === 0) current = o.currentClass;
				$pl_menu_items.append(
					$('<li>').addClass(o.menuItemClass).append(
						$('<a>').addClass(o.menuLinkClass).addClass(current).attr({
							href: '#' + playlistId + id,
						}).append(
							$('<span>').addClass(o.menuItemTitleClass).append(title)
						).append(
							function() {
								if (o.showThumb) {
									return $('<span>').addClass(o.menuItemThumbClass).append(
										$('<img>').attr({
											src: thumb,
											alt: '',
										})
									);
								} else {
									return '';
								}
							}
						).click(function(e) {
							e.preventDefault();
							var $target = $($(this).attr('href'));
							var $wrap = $(this).closest('.' + o.playlistClass).find('.' + o.itemsWrapClass);
							var $items = $wrap.find('.' + o.itemClass);
							var height = $items.height();
							var num = $items.index($target);
							var pos = -num * height;
							var $playing_item = $wrap.find('.' + o.playingClass);
							$playing_item.removeClass(o.playingClass).empty().append(
								get_screen($playing_item.data('href'), $playing_item.data('scr'), o)
							);
							var $current = $(this).closest('.' + o.menuClass).find('.' + o.currentClass);
							$current.removeClass(o.currentClass);
							$(this).addClass(o.currentClass);
							$wrap.animate({
								top: pos,
							}, o.speed);
						})
					)
				);
				$pl_items.append(
					$('<div>').addClass(o.itemClass).data({
						href:	href,
						url:	url,
						scr:	scr,
					}).attr({
						id:		playlistId + id,
					}).append(
						get_screen(href, scr, o)
					).append(
						$ignored_item
					)
				);
				id ++;
			});

			$parent.empty();
			$parent.append(
				$('<div>').addClass(o.playlistClass).append(
					$('<div>').addClass(o.menuClass).append(
						(o.showMenuTitle) ? $('<div>').addClass(o.menuTitleClass).html(o.menuTitleText) : ''
					).append(
						$pl_menu_items
					)
				).append(
					$('<div>').addClass(o.itemsClass).append(
						$pl_items
					)
				)
			);

			playlistId ++;
		});
	};

	$.fn.formFields = function(values) {
		var output = [];
		this.each(function() {
			var $form = $(this);
			var form = {
				action: $form.attr('action') || '',
				method: $form.attr('method') || 'get',
				fields: {},
			};
			$form.find('input,textarea,select').each(function () {
				if (form.fields[$(this).attr('name')] !== undefined) return true;
				var field = {
					tag: $(this)[0].tagName.toLowerCase(),
					type: $(this).attr('type').toLowerCase() || '',
					name: $(this).attr('name'),
					value: '',
					options: [],
				};
				if (field.tag == 'select') {
					$(this).find('option').each(function() {
						field.options.push({
							text: $(this).text(),
							value: $(this).attr('value'),
						});
					});
				}
				var $field = $(field.tag + '[name=' + field.name + ']');
				$field.each(function() {
					if (field.type == 'checkbox' && $(this).is(':checked')) {
						field.value = $(this).val();
					} else {
						field.value = $(this).val();
					}
				});
				form.fields[field.name] = field;
			});
			output.push(form);
		});
		return output;
	};

	$.showgoals = function (options) {
		function get_goals(opt) {
			var output = [];
			var checkers = {};
			// forms
			$('form').each(function() {
				var $form = $(this);
				var $parent = $form.closest('[id]');
				var goal = {
					type: 'form',
					name: $form.find('input[name=' + opt.name + ']').val() || '',
					text: $form.find('input[name=' + opt.text + ']').val() || '',
					id: $parent.exists() ? ('#' + $parent.attr('id')) : '',
					el: $parent.exists() ? $parent : $form,
					fields: $form.formFields()[0].fields,
				};
				if (opt.noduplicate) {
					if (typeof(checkers[goal.name]) == 'undefined') {
						checkers[goal.name] = true;
						output.push(goal);
					}
				} else {
					output.push(goal);
				}
			});
			// openform links
			$('a[data-' + opt.name + ']').each(function() {
				var $link = $(this);
				var link = $link.attr('href');
				var goal = {};
				if (link.substr(0, 1) == '#') {
					var $form = $($link.attr('href')).find('form');
					goal = {
						type: 'link',
						name: $link.data(opt.name) || '',
						text: $link.data(opt.text) || $link.text(),
						id: $link.attr('href') || '',
						el: $link,
						fields: $form.exists() ? $form.formFields()[0].fields : [],
					};
				} else {
					goal = {
						type: 'link',
						name: $link.data(opt.name) || '',
						text: $link.data(opt.text) || $link.text(),
						id: '',
						el: $link,
						fields: [],
					};
				}
				if (opt.noduplicate) {
					if (typeof(checkers[goal.name]) == 'undefined') {
						checkers[goal.name] = true;
						if (typeof(opt.constants[goal.name]) != 'undefined') goal.text = opt.constants[goal.name];
						output.push(goal);
					}
				} else {
					output.push(goal);
				}
			});
			return output;
		}

		var o = $.extend({
			active: false,
			name: 'goal',
			text: 'goalname',
			target: '',
			console: true,
			noduplicate: false,
			constants: {
				goal_phone: 'Клик по телефону',
				goal_email: 'Клик по email',
			}
		}, options);

		var goals = get_goals(o);

		if (o.console) console.info(goals);

		if (o.target && $(o.target).exists()) {
			var $dot = {
				table:	$('<table border="1">').css({
					width: '600px',
					maxWidth: '100%',
					margin: '10px auto',
					textAlign: 'left',
					background: '#fff',
					color: '#000',
					border: '1px solid #000',
					fontFamily: 'sans serif',
					fontSize: '12px',
				}),
				row:	$('<tr>'),
				num:	$('<td>').addClass('num').css({
					width: '30px',
				}),
				goal:	$('<td>').addClass('goal').css({
					width: '120px',
				}),
				name:	$('<td>').addClass('name'),
				input:	$('<input type="text">').css({
					width: '100%',
					border: 'none',
					background: '#fff',
					color: '#000',
					fontFamily: 'sans serif',
					fontSize: '12px',
				}).click(function() {
					$(this)[0].focus();
					$(this)[0].select();
				}),
			};
			var $table_dot = $dot.table;
			var $row_dot = $dot.row;
			$row_dot.append($dot.num);
			$row_dot.append($dot.goal.append($dot.input.clone(true)));
			$row_dot.append($dot.name.append($dot.input.clone(true)));
			$(o.target).each(function() {
				var $table = $table_dot.clone(true);
				$.each(goals, function(key, val) {
					var $row = $row_dot.clone(true);
					$row.find('.num').html(key + 1);
					$row.find('.goal input').val(val.name);
					$row.find('.name input').val(val.text);
					$table.append($row);
				});
				$(this).append($table);
			});
		}
	};

	$.fn.toTop = function( options ) {
		function listenPosition($e, opt) {
			var sy = $(document).scrollTop();
			if (sy >= opt.deltaY) {
				$e.addClass(opt.className);
			} else {
				$e.removeClass(opt.className);
			}
		}
		return this.each(function() {
			var o = $.extend({
				speed: 1000,
				className: 'fixed',
				deltaY: 200,
			}, options);
			var $this = $(this);
			$this.on('click', function(e) {
				e.preventDefault();
				$('html,body').animate({
					scrollTop: 0,
				}, o.speed);
			});
			listenPosition($this, o);
			$(document).bind('scroll', function() {
				listenPosition($this, o);
				return true;
			});
		});
	};

	$.fn.gotoForm = function(options) {
		return this.each(function() {
			var o = $.extend({
			}, options);
			$(this).on('click', function() {
				var $this = $(this);
				if (!$this.attr('href')) return true;
				var $target = $($this.attr('href'));
				if (!$target.exists()) return true;
				var data = $this.data();
				$.each(data, function(key, val) {
					if (key.substr(0, 5) == 'class') {
						key = key.replace('class', '').toLowerCase();
						$element = $target.find('.' + key);
						if ($element.exists()) $element.html(val);
					} else if (key.substr(0, 2) == 'id') {
						key = key.replace('id', '').toLowerCase();
						$element = $target.find('#' + key);
						if ($element.exists()) $element.html(val);
					} else if (key.substr(0, 4) == 'name') {
						key = key.replace('name', '').toLowerCase();
						$element = $target.find('[name="' + key + '"]');
						if ($element.exists()) {
							$element.val(val);
						} else {
							$target.find('form').each(function() {
								var $element = $('<input type="hidden">');
								$element.attr({
									'name': key,
								}).val(val);
								$(this).append($element);
							});
						}
					} else if (key.substr(0, 3) == 'tag') {
						key = key.replace('tag', '').toLowerCase();
						$element = $target.find(key);
						if ($element.exists()) $element.html(val);
					} else {
						key = key.replace('class', '').toLowerCase();
						$element = $target.find('.' + key);
						if ($element.exists()) $element.html(val);
					}
				});
				if (data.goal) {
					var $input_goal = $target.find('input').filter('[name=goal]');
					if ($input_goal.exists()) $input_goal.val(data.goal);
				}
				if (data.goalname) {
					var $input_goalname = $target.find('input').filter('[name=goalname]');
					if ($input_goalname.exists()) $input_goalname.val(data.goalname);
				}
				return false;
			});
		});
	};

	$.fn.ymap = function(options) {
		return this.each(function() {
			var o = $.extend({
			}, options);
			var $this = $(this);
			ymaps.ready(function() {
				var map_data = {
					center: [$this.data('coordLat'), $this.data('coordLon')],
					zoom: $this.data('zoom'),
				};
				var map_properties = {
					hintContent: $this.find('.address').text(),
					balloonContent: $this.find('.balloon').html(),
				};
				var map_options = {
					preset: $this.data('dotType'),
					iconColor: $this.data('dotColor'),
				};
				$this.html('');

				var map = new ymaps.Map($this[0], map_data);
				var mark = new ymaps.Placemark(map_data.center, map_properties, map_options);
				map.geoObjects.add(mark);
				map.behaviors.disable('scrollZoom');
				if ($.isMobile()) {
					map.behaviors.disable('drag');
				}
			});
		});
	};

	$.sendGoals = function(goal) {
		if (!goal) return false;
		if (typeof(yaCounters) != 'undefined') {
			$.each(yaCounters, function(key, val) {
				ym(val, 'reachGoal', goal);
			});
			console.info('send Yandex action: ' + goal);
		}
		if (typeof(GoogleAnalyticsObject) != 'undefined' && typeof(gaCounters) != 'undefined') {
			$.each(gaCounters, function(key, val) {
				ga(val + '.send', 'event', 'form', goal);
			});
			console.info('send Google Analytics action: ' + goal);
		}
	};

})(jQuery);