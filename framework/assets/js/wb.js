jQuery(function ($) {

	if (typeof wb_saveable !== 'undefined' && wb_saveable) {
		$('.wb-save').show();
	}

	if (typeof wb_resettable !== 'undefined' && wb_resettable) {
		$('.wb-reset-section, .wb-reset-all').show();
	}

	/**
	 *
	 */
	var wb_collapsed = $.cookie('wb-collapsed');

	if (wb_collapsed) {
		$('.wb-menu-bar').hide();

		$('.wb-content').css('margin-left', 0);
	}

	$('.wb-collapse').on('click', function (evt) {
		evt.preventDefault();

		if ($('.wb-menu-bar').is(':visible')) {
			$('.wb-menu-bar').hide();

			$('.wb-content').css('margin-left', 0);

			$.cookie('wb-collapsed', 1, { expires : 7 });
		} else {
			$('.wb-menu-bar').show();

			$('.wb-content').css('margin-left', 230);

			$.removeCookie('wb-collapsed');
		}
	});

	/**
	 *
	 */
	$(document.body).on('click', '[wb-action="add"]', function (evt) {
		var el = $(this),
			target = el.attr('wb-target'),
			template = null;

		if (target) {
			__key = window['__key_' + target.replace(/[#.]/g, '')];
			__template = window['__template_' + target.replace(/[#.]/g, '')];
		}

		template = __template.replace(/##key##/g, __key);

		if (target) {
			if ($(target).find('.wb-empty')) {
				$('.wb-empty', target).remove();
			}

			$(template).prependTo(target).first().css('background-color', '#fcf8e3').animate({
				backgroundColor : '#ffffff'
			}, 1000);
		} else {
			if ($('[wb-list]').find('.wb-empty')) {
				$('.wb-empty').remove();
			}

			$('[wb-list]').prepend(template).css('background-color', '#fcf8e3').animate({
				backgroundColor : 'transparent'
			}, 1000);
		}

		if (target) {
			window['__key_' + target.replace(/[#.]/g, '')] = window['__key_' + target.replace(/[#.]/g, '')] + 1;
		} else {
			__key = __key + 1;
		}

		wb_autocomplete();

		evt.preventDefault();
	});

	/**
	 *
	 */
	$(document.body).on('click', '[wb-action="remove"]', function (evt) {
		var el = $(this),
			target = el.attr('wb-target');

		if (!window.confirm(wbL10n['delete_confirmation'])) {
			return false;
		}

		if (target) {
			target.hide('slow', function () {
				$(this).remove();
			});
		} else {
			el.parents('.wb-section').hide('slow', function () {
				if ($(this).parent().find('.wb-section').length == 1) {
					$(this).parent().append(' \
						<p class="wb-empty">' + wbL10n['no_items'] + '</p> \
					');
				}

				$(this).remove();
			});
		}

		evt.preventDefault();
	});

	/**
	 *
	 */
	$(document.body).on('click', '[wb-action="upload"]', function (evt) {
		var el = $(this),
			target = el.attr('wb-target'),
			image = el.attr('wb-image');

		if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
			wp.media.editor.open(Math.random());

			wp.media.editor.send.attachment = function (props, attachment) {
				if (target) {
					$(target).val(attachment.url);
				}

				if (image) {
					$(image).get(0).src = attachment.url;
				}
			}
		} else {
			tb_show('', 'media-upload.php?type=image&TB_iframe=true');

			window.send_to_editor = function (html) {
				if (target) {
					$(target).val($(html).attr('href'));
				}

				if (image) {
					$(image).get(0).src = $(html).attr('href');
				}

				tb_remove();
			}
		}

		evt.preventDefault();
	});

	/**
	 * 
	 */
	function wb_autocomplete() {
		$.widget('custom.catcomplete', $.ui.autocomplete, {
			_create: function () {
				this._super();

				this.widget().menu('option', 'items', '> :not(.ui-autocomplete-category)');
			},
			_renderMenu: function (ul, items) {
				var that = this,
					currentCategory = '';

				$.each(items, function (index, item) {
					var li;

					if (item.category != currentCategory) {
						ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');

						currentCategory = item.category;
					}

					li = that._renderItemData(ul, item);

					if (item.category) {
						li.attr('aria-label', item.category + ' : ' + item.label);
					}
				});
			}
		});

		$('[wb-action="autocomplete"]').catcomplete({
			source: 'admin-ajax.php?action=get-permalinks'
		});
	}

	wb_autocomplete();

	/**
	 *
	 */
	$('[wb-list]').sortable({
		axis : 'y',
		opacity : 0.6,
		cancel : '.wb-empty, :input',
		update : function (event, ui) {
			$(this).children().each(function (index) {
				$(this).find(':input').each(function () {
					if ($(this).attr('name')) {
						$(this).attr('name', $(this).attr('name').replace(/\[([0-9]){1,4}\]/i, '[' + index + ']'));
					}
				});
			});

			wb_autocomplete();
		}
	});

});
