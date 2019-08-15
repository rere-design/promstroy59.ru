$(function() {

	function collapseList() {
		var hash = $(this).attr('id');
		var $item = $(this).closest('.list_item').find('.cont');
		var $parent = $(this).closest('.params');
		hideYMap($parent.find('.map_content:visible'));
		var $items = $parent.find('.list_item').find('.cont');
		if ($item.is(':visible')) {
			hash = $(this).data('id');
			$items.hide(delay_collapse);
		} else {
			var $sitems;
			if ($items.length > 1) {
				$sitems = $items.not($item);
			} else {
				$sitems = $items;
			}
			$sitems.hide(delay_collapse, function() {
				$item.show(delay_collapse);
			});
		}
		window.location.hash = hash;
		return false;
	}

	function renumerateItems($items, start) {
		var i = start || 0;
		$items.each(function() {
			$(this).find('input, textarea, select').each(function() {
				var param = $(this).closest('form[data-param]').data('param');
				var name = param + '[' + i + ']' + $(this).data('name');
				$(this).attr('name', name);
				if ($(this).data('item') !== undefined) $(this).data('item', i);
			});
			$(this).find('.num').html(i + 1);
			var $title = $(this).find('.head .left');
			$title.attr('id', $title.data('id') + '_' + i);
			i++;
		});
	}

	function deleteItem(curent, body, item) {
		if (!confirm('Удалить строку?')) return false;
		var $parent = $(curent).closest('.control');
		var $body = $parent.find(body);
		$(curent).closest(item).remove();
		renumerateItems($body.find(item));
	}

	function renumerateImages($items, start) {
		var i = start || 0;
		var param = $items.closest('[data-param]').data('param');
		if ($items.find('.img').length === 0) {
			$items.find('input[type=file]').prop('required', true);
		} else {
			$items.find('.img').each(function() {
				$(this).find('input, textarea, select').each(function() {
					var item = ($(this).data('item') !== undefined) ? ('[' + $(this).data('item') + ']') : '';
					var name = param + item + $(this).data('field') + '[' + i + ']' + $(this).data('file');
					$(this).attr('name', name);
				});
				i++;
			});
		}
	}

	function deleteTextItem(current) {
		var $parent = $(current).closest('.text_list');
		var $items = $parent.find('.text_list_item');
		if ($items.length > 1) {
			$(current).closest('.text_list_item').remove();
		} else {
			var $item = $(current).closest('.text_list_item');
			$item.find('input').val('');
			$item.find('textarea').val('');
			if ($item.find('input[type=hidden]').prop('required')) $item.find('input[type=file]').prop('required', true);
			$item.find('input[type=hidden]').remove();
			$item.find('.ftitle').remove();
		}
	}

	function addTextItem(current) {
		var $parent = $(current).closest('.text_list');
		var $items = $parent.find('.text_list_item');
		var $item = $items.eq(0).clone(true);
		$item.find('input').val('');
		$item.find('textarea').val('');
		if ($item.find('input[type=hidden]').prop('required')) $item.find('input[type=file]').prop('required', true);
		$item.find('input[type=hidden]').remove();
		if ($item.find('input[type=file]').length) $item.find('.move').remove();
		$item.find('.ftitle').remove();
		$parent.find('.text_list_items').append($item);
	}

	function deleteTableItem(current) {
		var $parent = $(current).closest('.table_list');
		var $items = $parent.find('tbody tr');
		if ($items.length > 1) {
			$(current).closest('tr').remove();
		} else {
			$(current).closest('tr').find('input').val('');
		}
		$items = $parent.find('tbody tr');
		i = 0;
		$items.each(function() {
			$(this).find('input').each(function() {
				var dot = $(this).data('subname');
				$(this).attr('name', dot.replace('%num%', i));
			});
			i++;
		});
	}

	function addTableItem(current) {
		var $parent = $(current).closest('.table_list');
		var $items = $parent.find('tbody tr');
		var $item = $items.eq(0).clone(true);
		var $input = $item.find('input');
		$input.val('');
		$input.each(function() {
			var dot = $(this).data('subname');
			$(this).attr('name', dot.replace('%num%', $items.length));
		});
		$parent.find('tbody').append($item);
	}



	$('.imglist .img_del').on('click', function(e) {
		e.preventDefault();
		//if (!confirm('Удалить изображение?')) return false;
		var $items = $(this).closest('.imglist');
		$(this).closest('.img').remove();
		renumerateImages($items);
	});



	$('.imgfield .img_del').on('click', function(e) {
		e.preventDefault();
		//if (!confirm('Удалить изображение?')) return false;
		var $items = $(this).closest('.imgfield');
		$(this).closest('.img').remove();

	});



	// table type group
	$('table tfoot .add').on('click', function(e) {
		e.preventDefault();
		var $parent = $(this).closest('.control');
		var $body = $parent.find('table.params tbody');
		var param = $parent.data('param');
		var count = $body.find('tr').length;
		var $dot = $parent.find('table.dot tr').clone();

		$dot.find('.delete').on('click', function(e) {
			e.preventDefault();
			deleteItem(this, 'table.params tbody', 'tr');
		});

		$dot.find('.text_list_item_delete').click(function(e) {
			e.preventDefault();
			deleteTextItem(this);
		});

		$dot.find('.text_list_item_add').click(function(e) {
			e.preventDefault();
			addTextItem(this);
		});

		$dot.find('.link_ymap').click(function(e) {
			e.preventDefault();
			openYMap(this);
		});

		$dot.find('.link_ymap_save').click(function(e) {
			e.preventDefault();
			saveYMap(this);
		});

		$dot.find('.link_ymap_cancel').click(function(e) {
			e.preventDefault();
			hideYMap(this);
		});

		openColorpicker($dot.find('.colorpicker'));

		$dot.find('input, textarea, select').each(function() {
			var name = param + '[' + count + ']' + $(this).data('name');
			$(this).attr('name', name);
			if ($(this).data('require')) $(this).prop('required', true);
		});
		$dot.find('.num').html(count + 1);
		$body.append($dot);
		appendCLEdit($body.find('.cledit'));
	});

	$('table tbody .delete').on('click', function(e) {
		e.preventDefault();
		deleteItem(this, 'table.params tbody', 'tr');
	});



	// list type group
	$('.manage .add').on('click', function(e) {
		e.preventDefault();
		var $parent = $(this).closest('.control');
		var $body = $parent.find('div.params');
		var param = $parent.data('param');
		var count = $body.find('.list_item').length;
		var $dot = $parent.find('div.dot .list_item').clone();
		var $title = $dot.find('.head .left');

		$dot.find('.delete').on('click', function(e) {
			e.preventDefault();
			deleteItem(this, 'div.params', '.list_item');
		});

		$dot.find('.text_list_item_delete').click(function(e) {
			e.preventDefault();
			deleteTextItem(this);
		});

		$dot.find('.text_list_item_add').click(function(e) {
			e.preventDefault();
			addTextItem(this);
		});

		$dot.find('.link_ymap').click(function(e) {
			e.preventDefault();
			openYMap(this);
		});

		$dot.find('.link_ymap_save').click(function(e) {
			e.preventDefault();
			saveYMap(this);
		});

		$dot.find('.link_ymap_cancel').click(function(e) {
			e.preventDefault();
			hideYMap(this);
		});

		openColorpicker($dot.find('.colorpicker'));

		$dot.find('input, textarea, select').each(function() {
			var name = param + '[' + count + ']' + $(this).data('name');
			if ($(this).is('select')) console.log(param);
			$(this).attr('name', name);
			if ($(this).data('require')) $(this).prop('required', true);
		});
		$dot.find('.num').html(count + 1);
		$title.attr('id', $title.data('id') + '_' + count);
		$title.on('click', collapseList);
		$body.append($dot);
		$parent.find('.params .list_item').last().find('.left').click();
	});

	$('.params .list_item .head .delete').on('click', function(e) {
		e.preventDefault();
		deleteItem(this, 'div.params', '.list_item');
	});



	// list type group collapse
	var delay_collapse = 400;
	$('.list_item .cont').not(':eq(0)').hide();
	$('.list_item .head .left').on('click', collapseList);



	// nav menu
	function open_block(delay) {
		$('.current').each(function() {
			var $this = $(this);
			var id = $this.attr('href');
			var $target = $(id);
			if ($target.hasClass('section')) {
				$('.section').not($target).hide(delay, function() {
					$target.show(delay);
				});
			} else if ($target.hasClass('group')) {
				$('.group').not($target).hide(delay, function() {
					$target.show(delay);
				});
			}
		});
	}
	function get_hash() {
		var hash = location.hash;
		while (hash.indexOf('_') > -1) {
			var $target = $(hash);
			if ($target.length > 0) {
				if ($target.hasClass('section')) {
					$('nav.sections ul li a[href=' + hash + ']').addClass('current');
				} else if ($target.hasClass('group')) {
					$('nav.groups ul li a[href=' + hash + ']').addClass('current');
				} else if ($target.hasClass('left')) {
					$target.click();
				}
			}
			hash = hash.substr(0, hash.lastIndexOf('_'));
		}
		var cnt = $('.current').length;
		if (cnt === 1) {
			var id = $('.current').attr('href');
			$(id).find('nav.groups ul li a').eq(0).addClass('current');
		} else if (cnt == 0) {
			$('nav.sections ul li a').eq(0).addClass('current');
			$('nav.groups ul li a').eq(0).addClass('current');
		}
	}
	$('nav.groups ul li a').click(function(e) {
		e.preventDefault();
		var $this = $(this);
		var $list = $('nav.groups ul li a');
		var id = $this.attr('href');
		$list.not($this).removeClass('current');
		$this.addClass('current');
		window.location.hash = id;
		open_block(400);
	});
	$('nav.sections ul li a').click(function(e) {
		e.preventDefault();
		var $this = $(this);
		var $list = $('nav.sections ul li a');
		$list.not($this).removeClass('current');
		$this.addClass('current');
		var id = $this.attr('href');
		$(id).find('nav.groups ul li a').eq(0).click();
	});
	get_hash();
	open_block(0);

	$('.text_list_item_delete').click(function(e) {
		e.preventDefault();
		deleteTextItem(this);
	});

	$('.text_list_item_add').click(function(e) {
		e.preventDefault();
		addTextItem(this);
	});

	$('.table_list_item_delete').click(function(e) {
		e.preventDefault();
		deleteTableItem(this);
	});

	$('.table_list_item_add').click(function(e) {
		e.preventDefault();
		addTableItem(this);
	});



	$('.imglist').sortable({
		items: '.img',
		placeholder: 'empty',
		cursor: 'move',
		opacity: 0.5,
		revert: true,
	});

	$('.text_list_items').sortable({
		items: '.text_list_item',
		placeholder: 'empty',
		handle: '.move',
		axis: 'y',
		cursor: 'move',
		opacity: 0.5,
		revert: true,
	});

	$('.table_list tbody').sortable({
		items: '.table_list_item',
		placeholder: 'empty',
		handle: '.table_list_item_move',
		axis: 'y',
		cursor: 'move',
		opacity: 0.5,
		revert: true,
	});

	$('div.params').sortable({
		items: '.list_item',
		placeholder: 'empty_item',
		handle: '.move_item',
		axis: 'y',
		cursor: 'move',
		opacity: 0.5,
		revert: true,
		stop: function(event, ui) {
			renumerateItems($(this).find('.list_item'));
		},
	});

	$('table.params tbody').sortable({
		items: '.table_item',
		placeholder: 'empty_item',
		handle: '.move_item',
		axis: 'y',
		cursor: 'move',
		opacity: 0.5,
		revert: true,
		stop: function(event, ui) {
			renumerateItems($(this).find('tr'));
		},
	});

	function appendCLEdit($el) {
		if ($el.length == 0) return false;
		var data = $el.data();
		var options = {};
		if (typeof(data.width) !== 'undefined') options.width = data.width;
		if (typeof(data.height) !== 'undefined') options.height = data.height;
		if (typeof(data.controls) !== 'undefined') options.controls = data.controls;
		if (typeof(data.colors) !== 'undefined') options.colors = data.colors;
		if (typeof(data.fonts) !== 'undefined') options.fonts = data.fonts;
		if (typeof(data.sizes) !== 'undefined') options.sizes = data.sizes;
		$el.cleditor(options);
	}
	$('.params .cledit, .single_field .cledit').each(function() {
		appendCLEdit($(this));
	});



	var map = false;
	var mark = false;

	function getYMapData() {
		var coords = mark.geometry.getCoordinates();
		mark.properties.set('iconCaption', 'поиск...');
		ymaps.geocode(coords).then(function(res) {
			var firstGeoObject = res.geoObjects.get(0);
			mark.properties.set({
				iconCaption: [
					firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
					firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
				].filter(Boolean).join(', '),
				balloonContent: firstGeoObject.getAddressLine()
			});
		});
	}

	function showYMap($parent, coords, zoom) {
		var $content = $parent.find('.map_content');
		$content.show(400, function() {
			map = new ymaps.Map($content[0], {
				center: coords,
				zoom: zoom,
				controls: ['zoomControl', 'geolocationControl', 'searchControl', 'fullscreenControl'],
			});
			mark =  new ymaps.Placemark(coords, {}, {
				preset: 'islands#blueDotIconWithCaption',
				draggable: true,
			});
			map.events.add('click', function(e) {
				var coords = e.get('coords');
				mark.geometry.setCoordinates(coords);
				getYMapData();
			});
			map.geoObjects.add(mark);
			mark.events.add('dragend', function() {
				getYMapData();
			});
			getYMapData();
		});
		$parent.find('.map_manage').show(400);
		$form = $parent.closest('form');
		$save = $form.find('button[name=cms_save]');
		$save.prop('disabled', true);
		$('.map_link').hide(400);
	}

	function hideYMap(el) {
		var $parent = $(el).closest('.yandex_map');
		$('.map_link').show(400);
		$parent.find('.map_content').html('');
		$parent.find('.map_content').hide(400);
		$parent.find('.map_manage').hide(400);
		$form = $parent.closest('form');
		$save = $form.find('button[name=cms_save]');
		$save.prop('disabled', false);
	}

	function openYMap(el) {
		var $parent = $(el).closest('.yandex_map');
		var lat = $parent.find('.lat input').val();
		var lon = $parent.find('.lon input').val();
		var zoom = $parent.find('.zoom input').val() || 16;
		var coords = [lat, lon];
		if (coords[0] && coords[1]) {
			showYMap($parent, coords, zoom);
		} else {
			ymaps.geolocation.get(0).then(function(res) {
				showYMap($parent, res.geoObjects.position, zoom);
			});
		}
	}

	function saveYMap(el) {
		if (!map || !mark) return false;
		var zoom = map.getZoom();
		var coords = mark.geometry.getCoordinates();
		var $parent = $(el).closest('.yandex_map');
		$parent.find('.lat input').val(coords[0]);
		$parent.find('.lon input').val(coords[1]);
		$parent.find('.zoom input').val(zoom);
		hideYMap(el);
	}

	$('.link_ymap').click(function(e) {
		e.preventDefault();
		openYMap(this);
	});

	$('.link_ymap_save').click(function(e) {
		e.preventDefault();
		saveYMap(this);
	});

	$('.link_ymap_cancel').click(function(e) {
		e.preventDefault();
		hideYMap(this);
	});



	function openColorpicker(el) {
		$(el).each(function() {
			var $this = $(this);
			$this.find('.color_input input').colpick({
				submitText: 'Выбрать',
				onSubmit: function(hsb, hex, rgb, el, bySetColor) {
					$parent = $(el).closest('.colorpicker');
					$parent.find('.color_input input').val('#' + hex);
					$parent.find('.color_area').css('background', '#' + hex);
					$(el).colpickHide();
				},
			});
			$(this).find('.color_area').click(function() {
				$this.find('.color_input input').colpickShow();
			});
		});
	}
	openColorpicker($('.colorpicker'));


	$('.content_hided').show();

});