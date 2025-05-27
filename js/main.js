/**
*  Ajax Autocomplete for jQuery, version 1.4.10
*  (c) 2017 Tomas Kirda
*
*  Ajax Autocomplete for jQuery is freely distributable under the terms of an MIT-style license.
*  For details, see the web site: https://github.com/devbridge/jQuery-Autocomplete
*/
!function(a){"use strict";"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports&&"function"==typeof require?require("jquery"):jQuery)}(function(a){"use strict";function b(c,d){var e=this;e.element=c,e.el=a(c),e.suggestions=[],e.badQueries=[],e.selectedIndex=-1,e.currentValue=e.element.value,e.timeoutId=null,e.cachedResponse={},e.onChangeTimeout=null,e.onChange=null,e.isLocal=!1,e.suggestionsContainer=null,e.noSuggestionsContainer=null,e.options=a.extend(!0,{},b.defaults,d),e.classes={selected:"autocomplete-selected",suggestion:"autocomplete-suggestion"},e.hint=null,e.hintValue="",e.selection=null,e.initialize(),e.setOptions(d)}function c(a,b,c){return a.value.toLowerCase().indexOf(c)!==-1}function d(b){return"string"==typeof b?a.parseJSON(b):b}function e(a,b){if(!b)return a.value;var c="("+g.escapeRegExChars(b)+")";return a.value.replace(new RegExp(c,"gi"),"<strong>$1</strong>").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/&lt;(\/?strong)&gt;/g,"<$1>")}function f(a,b){return'<div class="autocomplete-group">'+b+"</div>"}var g=function(){return{escapeRegExChars:function(a){return a.replace(/[|\\{}()[\]^$+*?.]/g,"\\$&")},createNode:function(a){var b=document.createElement("div");return b.className=a,b.style.position="absolute",b.style.display="none",b}}}(),h={ESC:27,TAB:9,RETURN:13,LEFT:37,UP:38,RIGHT:39,DOWN:40},i=a.noop;b.utils=g,a.Autocomplete=b,b.defaults={ajaxSettings:{},autoSelectFirst:!1,appendTo:"body",serviceUrl:null,lookup:null,onSelect:null,width:"auto",minChars:1,maxHeight:300,deferRequestBy:0,params:{},formatResult:e,formatGroup:f,delimiter:null,zIndex:9999,type:"GET",noCache:!1,onSearchStart:i,onSearchComplete:i,onSearchError:i,preserveInput:!1,containerClass:"autocomplete-suggestions",tabDisabled:!1,dataType:"text",currentRequest:null,triggerSelectOnValidInput:!0,preventBadQueries:!0,lookupFilter:c,paramName:"query",transformResult:d,showNoSuggestionNotice:!1,noSuggestionNotice:"No results",orientation:"bottom",forceFixPosition:!1},b.prototype={initialize:function(){var c,d=this,e="."+d.classes.suggestion,f=d.classes.selected,g=d.options;d.element.setAttribute("autocomplete","off"),d.noSuggestionsContainer=a('<div class="autocomplete-no-suggestion"></div>').html(this.options.noSuggestionNotice).get(0),d.suggestionsContainer=b.utils.createNode(g.containerClass),c=a(d.suggestionsContainer),c.appendTo(g.appendTo||"body"),"auto"!==g.width&&c.css("width",g.width),c.on("mouseover.autocomplete",e,function(){d.activate(a(this).data("index"))}),c.on("mouseout.autocomplete",function(){d.selectedIndex=-1,c.children("."+f).removeClass(f)}),c.on("click.autocomplete",e,function(){d.select(a(this).data("index"))}),c.on("click.autocomplete",function(){clearTimeout(d.blurTimeoutId)}),d.fixPositionCapture=function(){d.visible&&d.fixPosition()},a(window).on("resize.autocomplete",d.fixPositionCapture),d.el.on("keydown.autocomplete",function(a){d.onKeyPress(a)}),d.el.on("keyup.autocomplete",function(a){d.onKeyUp(a)}),d.el.on("blur.autocomplete",function(){d.onBlur()}),d.el.on("focus.autocomplete",function(){d.onFocus()}),d.el.on("change.autocomplete",function(a){d.onKeyUp(a)}),d.el.on("input.autocomplete",function(a){d.onKeyUp(a)})},onFocus:function(){var a=this;a.fixPosition(),a.el.val().length>=a.options.minChars&&a.onValueChange()},onBlur:function(){var b=this,c=b.options,d=b.el.val(),e=b.getQuery(d);b.blurTimeoutId=setTimeout(function(){b.hide(),b.selection&&b.currentValue!==e&&(c.onInvalidateSelection||a.noop).call(b.element)},200)},abortAjax:function(){var a=this;a.currentRequest&&(a.currentRequest.abort(),a.currentRequest=null)},setOptions:function(b){var c=this,d=a.extend({},c.options,b);c.isLocal=Array.isArray(d.lookup),c.isLocal&&(d.lookup=c.verifySuggestionsFormat(d.lookup)),d.orientation=c.validateOrientation(d.orientation,"bottom"),a(c.suggestionsContainer).css({"max-height":d.maxHeight+"px",width:d.width+"px","z-index":d.zIndex}),this.options=d},clearCache:function(){this.cachedResponse={},this.badQueries=[]},clear:function(){this.clearCache(),this.currentValue="",this.suggestions=[]},disable:function(){var a=this;a.disabled=!0,clearTimeout(a.onChangeTimeout),a.abortAjax()},enable:function(){this.disabled=!1},fixPosition:function(){var b=this,c=a(b.suggestionsContainer),d=c.parent().get(0);if(d===document.body||b.options.forceFixPosition){var e=b.options.orientation,f=c.outerHeight(),g=b.el.outerHeight(),h=b.el.offset(),i={top:h.top,left:h.left};if("auto"===e){var j=a(window).height(),k=a(window).scrollTop(),l=-k+h.top-f,m=k+j-(h.top+g+f);e=Math.max(l,m)===l?"top":"bottom"}if("top"===e?i.top+=-f:i.top+=g,d!==document.body){var n,o=c.css("opacity");b.visible||c.css("opacity",0).show(),n=c.offsetParent().offset(),i.top-=n.top,i.top+=d.scrollTop,i.left-=n.left,b.visible||c.css("opacity",o).hide()}"auto"===b.options.width&&(i.width=b.el.outerWidth()+"px"),c.css(i)}},isCursorAtEnd:function(){var a,b=this,c=b.el.val().length,d=b.element.selectionStart;return"number"==typeof d?d===c:!document.selection||(a=document.selection.createRange(),a.moveStart("character",-c),c===a.text.length)},onKeyPress:function(a){var b=this;if(!b.disabled&&!b.visible&&a.which===h.DOWN&&b.currentValue)return void b.suggest();if(!b.disabled&&b.visible){switch(a.which){case h.ESC:b.el.val(b.currentValue),b.hide();break;case h.RIGHT:if(b.hint&&b.options.onHint&&b.isCursorAtEnd()){b.selectHint();break}return;case h.TAB:if(b.hint&&b.options.onHint)return void b.selectHint();if(b.selectedIndex===-1)return void b.hide();if(b.select(b.selectedIndex),b.options.tabDisabled===!1)return;break;case h.RETURN:if(b.selectedIndex===-1)return void b.hide();b.select(b.selectedIndex);break;case h.UP:b.moveUp();break;case h.DOWN:b.moveDown();break;default:return}a.stopImmediatePropagation(),a.preventDefault()}},onKeyUp:function(a){var b=this;if(!b.disabled){switch(a.which){case h.UP:case h.DOWN:return}clearTimeout(b.onChangeTimeout),b.currentValue!==b.el.val()&&(b.findBestHint(),b.options.deferRequestBy>0?b.onChangeTimeout=setTimeout(function(){b.onValueChange()},b.options.deferRequestBy):b.onValueChange())}},onValueChange:function(){if(this.ignoreValueChange)return void(this.ignoreValueChange=!1);var b=this,c=b.options,d=b.el.val(),e=b.getQuery(d);return b.selection&&b.currentValue!==e&&(b.selection=null,(c.onInvalidateSelection||a.noop).call(b.element)),clearTimeout(b.onChangeTimeout),b.currentValue=d,b.selectedIndex=-1,c.triggerSelectOnValidInput&&b.isExactMatch(e)?void b.select(0):void(e.length<c.minChars?b.hide():b.getSuggestions(e))},isExactMatch:function(a){var b=this.suggestions;return 1===b.length&&b[0].value.toLowerCase()===a.toLowerCase()},getQuery:function(b){var c,d=this.options.delimiter;return d?(c=b.split(d),a.trim(c[c.length-1])):b},getSuggestionsLocal:function(b){var c,d=this,e=d.options,f=b.toLowerCase(),g=e.lookupFilter,h=parseInt(e.lookupLimit,10);return c={suggestions:a.grep(e.lookup,function(a){return g(a,b,f)})},h&&c.suggestions.length>h&&(c.suggestions=c.suggestions.slice(0,h)),c},getSuggestions:function(b){var c,d,e,f,g=this,h=g.options,i=h.serviceUrl;if(h.params[h.paramName]=b,h.onSearchStart.call(g.element,h.params)!==!1){if(d=h.ignoreParams?null:h.params,a.isFunction(h.lookup))return void h.lookup(b,function(a){g.suggestions=a.suggestions,g.suggest(),h.onSearchComplete.call(g.element,b,a.suggestions)});g.isLocal?c=g.getSuggestionsLocal(b):(a.isFunction(i)&&(i=i.call(g.element,b)),e=i+"?"+a.param(d||{}),c=g.cachedResponse[e]),c&&Array.isArray(c.suggestions)?(g.suggestions=c.suggestions,g.suggest(),h.onSearchComplete.call(g.element,b,c.suggestions)):g.isBadQuery(b)?h.onSearchComplete.call(g.element,b,[]):(g.abortAjax(),f={url:i,data:d,type:h.type,dataType:h.dataType},a.extend(f,h.ajaxSettings),g.currentRequest=a.ajax(f).done(function(a){var c;g.currentRequest=null,c=h.transformResult(a,b),g.processResponse(c,b,e),h.onSearchComplete.call(g.element,b,c.suggestions)}).fail(function(a,c,d){h.onSearchError.call(g.element,b,a,c,d)}))}},isBadQuery:function(a){if(!this.options.preventBadQueries)return!1;for(var b=this.badQueries,c=b.length;c--;)if(0===a.indexOf(b[c]))return!0;return!1},hide:function(){var b=this,c=a(b.suggestionsContainer);a.isFunction(b.options.onHide)&&b.visible&&b.options.onHide.call(b.element,c),b.visible=!1,b.selectedIndex=-1,clearTimeout(b.onChangeTimeout),a(b.suggestionsContainer).hide(),b.signalHint(null)},suggest:function(){if(!this.suggestions.length)return void(this.options.showNoSuggestionNotice?this.noSuggestions():this.hide());var b,c=this,d=c.options,e=d.groupBy,f=d.formatResult,g=c.getQuery(c.currentValue),h=c.classes.suggestion,i=c.classes.selected,j=a(c.suggestionsContainer),k=a(c.noSuggestionsContainer),l=d.beforeRender,m="",n=function(a,c){var f=a.data[e];return b===f?"":(b=f,d.formatGroup(a,b))};return d.triggerSelectOnValidInput&&c.isExactMatch(g)?void c.select(0):(a.each(c.suggestions,function(a,b){e&&(m+=n(b,g,a)),m+='<div class="'+h+'" data-index="'+a+'">'+f(b,g,a)+"</div>"}),this.adjustContainerWidth(),k.detach(),j.html(m),a.isFunction(l)&&l.call(c.element,j,c.suggestions),c.fixPosition(),j.show(),d.autoSelectFirst&&(c.selectedIndex=0,j.scrollTop(0),j.children("."+h).first().addClass(i)),c.visible=!0,void c.findBestHint())},noSuggestions:function(){var b=this,c=b.options.beforeRender,d=a(b.suggestionsContainer),e=a(b.noSuggestionsContainer);this.adjustContainerWidth(),e.detach(),d.empty(),d.append(e),a.isFunction(c)&&c.call(b.element,d,b.suggestions),b.fixPosition(),d.show(),b.visible=!0},adjustContainerWidth:function(){var b,c=this,d=c.options,e=a(c.suggestionsContainer);"auto"===d.width?(b=c.el.outerWidth(),e.css("width",b>0?b:300)):"flex"===d.width&&e.css("width","")},findBestHint:function(){var b=this,c=b.el.val().toLowerCase(),d=null;c&&(a.each(b.suggestions,function(a,b){var e=0===b.value.toLowerCase().indexOf(c);return e&&(d=b),!e}),b.signalHint(d))},signalHint:function(b){var c="",d=this;b&&(c=d.currentValue+b.value.substr(d.currentValue.length)),d.hintValue!==c&&(d.hintValue=c,d.hint=b,(this.options.onHint||a.noop)(c))},verifySuggestionsFormat:function(b){return b.length&&"string"==typeof b[0]?a.map(b,function(a){return{value:a,data:null}}):b},validateOrientation:function(b,c){return b=a.trim(b||"").toLowerCase(),a.inArray(b,["auto","bottom","top"])===-1&&(b=c),b},processResponse:function(a,b,c){var d=this,e=d.options;a.suggestions=d.verifySuggestionsFormat(a.suggestions),e.noCache||(d.cachedResponse[c]=a,e.preventBadQueries&&!a.suggestions.length&&d.badQueries.push(b)),b===d.getQuery(d.currentValue)&&(d.suggestions=a.suggestions,d.suggest())},activate:function(b){var c,d=this,e=d.classes.selected,f=a(d.suggestionsContainer),g=f.find("."+d.classes.suggestion);return f.find("."+e).removeClass(e),d.selectedIndex=b,d.selectedIndex!==-1&&g.length>d.selectedIndex?(c=g.get(d.selectedIndex),a(c).addClass(e),c):null},selectHint:function(){var b=this,c=a.inArray(b.hint,b.suggestions);b.select(c)},select:function(a){var b=this;b.hide(),b.onSelect(a)},moveUp:function(){var b=this;if(b.selectedIndex!==-1)return 0===b.selectedIndex?(a(b.suggestionsContainer).children("."+b.classes.suggestion).first().removeClass(b.classes.selected),b.selectedIndex=-1,b.ignoreValueChange=!1,b.el.val(b.currentValue),void b.findBestHint()):void b.adjustScroll(b.selectedIndex-1)},moveDown:function(){var a=this;a.selectedIndex!==a.suggestions.length-1&&a.adjustScroll(a.selectedIndex+1)},adjustScroll:function(b){var c=this,d=c.activate(b);if(d){var e,f,g,h=a(d).outerHeight();e=d.offsetTop,f=a(c.suggestionsContainer).scrollTop(),g=f+c.options.maxHeight-h,e<f?a(c.suggestionsContainer).scrollTop(e):e>g&&a(c.suggestionsContainer).scrollTop(e-c.options.maxHeight+h),c.options.preserveInput||(c.ignoreValueChange=!0,c.el.val(c.getValue(c.suggestions[b].value))),c.signalHint(null)}},onSelect:function(b){var c=this,d=c.options.onSelect,e=c.suggestions[b];c.currentValue=c.getValue(e.value),c.currentValue===c.el.val()||c.options.preserveInput||c.el.val(c.currentValue),c.signalHint(null),c.suggestions=[],c.selection=e,a.isFunction(d)&&d.call(c.element,e)},getValue:function(a){var b,c,d=this,e=d.options.delimiter;return e?(b=d.currentValue,c=b.split(e),1===c.length?a:b.substr(0,b.length-c[c.length-1].length)+a):a},dispose:function(){var b=this;b.el.off(".autocomplete").removeData("autocomplete"),a(window).off("resize.autocomplete",b.fixPositionCapture),a(b.suggestionsContainer).remove()}},a.fn.devbridgeAutocomplete=function(c,d){var e="autocomplete";return arguments.length?this.each(function(){var f=a(this),g=f.data(e);"string"==typeof c?g&&"function"==typeof g[c]&&g[c](d):(g&&g.dispose&&g.dispose(),g=new b(this,c),f.data(e,g))}):this.first().data(e)},a.fn.autocomplete||(a.fn.autocomplete=a.fn.devbridgeAutocomplete)});

jQuery(function ($) {

	/**
	 * Header
	 */
	var didScroll = false,
		lastScrollTop = 0;

	$(window).on('scroll', function () {
		var distanceY = $(this).scrollTop(),
			shrinkOn = 160,
			header = $('.header');

		if (distanceY > shrinkOn) {
			header.addClass('smaller');
		} else {
			if (header.hasClass('smaller')) {
				header.removeClass('smaller');
			}
		}

		didScroll = true;
	});

	setInterval(function() {
		if (didScroll) {
			hasScrolled();

			didScroll = false;
		}
	}, 250);

	/**
	 * Mobile Menu
	 */
	var siteToggle = $('.navbar-toggler'),
		siteMenu = $('.header__right');

	siteToggle.on('click', function () {
		$(this).toggleClass('collapsed');

		siteMenu.toggleClass('show');

		$('body').toggleClass('overflow-hidden');
	});

	$('.site__layer').on('click', function () {
		siteToggle.removeClass('collapsed');

		siteMenu.removeClass('show');

		$('body').removeClass('overflow-hidden');
	});

	if (!Cookies.get('_c')) {
		$('.cookie-bar').show();
	}

	$('.accept-cta').on('click', function (evt) {
		evt.preventDefault();

		Cookies.set('_c', '1', { expires: 365 });

		$('.cookie-bar').hide();
	});

	/**
	 * Styler
	 */
	$('.select-styler, input:radio, input:checkbox').styler();

	/**
	 * Focus
	 */
	if ($('.hero-search__input').length) {
		$('.hero-search__input input').on('focus', function () {
			$(this).parents('.hero-search')
				.addClass('hero-search_focus');
		}).on('blur', function () {
			$(this).parents('.hero-search')
				.removeClass('hero-search_focus');
		});
	}

	/**
	 * Typed
	 */
	if ($('.element').length) {
		$('.element').each(function () {
			$(this).typed({
				strings: [$(this).data('text1'), $(this).data('text2'), $(this).data('text3')],
				loop: true, // $(this).data('loop') ? $(this).data('loop') : false,
				backDelay: $(this).data('backdelay') ? $(this).data('backdelay') : 2000,
				typeSpeed: 10
			});
		});
	}

	/**
	 * Compare
	 */
	if ($('.roduct-box-detail__add-compare').length) {
		$(document).on('click', '.roduct-box-detail__add-compare', function (evt) {
			if (!$(this).hasClass('added')) {
				evt.preventDefault();

				var el = $(this);

				el.addClass('loading');

				$.post(el.data('action'))
					.done(function (data) {
						if (data.status == 'success') {
							$('.badge-num').text(data.count);

							$(el).addClass('added')
								.text('Compare');

							if (!Cookies.get('_cph')) {
								if ($('#modal-compare #title').length) {
									var title = el.parentsUntil('.procuct__col')
										.find('.product-box__info .product-box__title a')
										.text();

									$('#modal-compare #title').text(title);
								}

								$.fancybox.open($('#modal-compare'), {
									touch: false,
									infobar: false,
									baseClass: 'compare-modal'
								});
							}
						}
					})
					.always(function () {
						el.removeClass('loading');
					});
			}
		});

		$('.popup-box-content-elem').mCustomScrollbar({
			theme: 'minimal'
		});

		$('.compare-info__links .btn-link').on('click', function () {
			$.fancybox.close();
		});

		$('#modal-compare input').on('change', function () {
			if ($(this).is(':checked')) {
				Cookies.set('_cph', '1', { expires: 365 });
			} else {
				Cookies.remove('_cph');
			}
		});
	}

	if ($('.compare-choose__select, .control-bar__select').length) {
		$('.compare-choose__select, .control-bar__select').on('change', function () {
			if (typeof $(this)[0].form !== 'undefined') {
				$(this)[0].form.submit();
			}
		});
	}

	var slick;

	if ($('.carousel_wrapper').length) {
		$('.carousel_wrapper').each(function () {
			var el = $(this);

			if (el.find('.carousel > *').length <= el.data('columns')) {
				return true;
			}

			var slidesToShow = el.data('columns');

			var slidesToScroll = $.isNumeric(el.data('slides-to-scroll')) ? el.data('slides-to-scroll') : 1;
			var infinite = el.data('infinite') == 'on';
			var pagination = el.data('pagination') == 'on';
			var navigation = el.data('navigation') == 'on';
			var autoHeight = el.data('auto-height') == 'on';
			var draggable = el.data('draggable') == 'on';
			var autoplay = el.data('autoplay') == 'on';
			var autoplaySpeed = el.data('autoplay-speed');
			var pauseOnHover = el.data('pause-on-hover') == 'on';
			var vertical = el.data('vertical') == 'on';
			var verticalSwipe = el.data('vertical-swipe') == 'on';
			var tabletLandscape = el.data('tablet-landscape');
			var tabletPortrait = el.data('tablet-portrait');
			var mobile = el.data('mobile');
			var carousel = el.children('.carousel');
			var rtl = $('body').hasClass('rtl');

			var responsive = {
				responsive: []
			};

			var args = {
				slidesToShow: slidesToShow,
				slidesToScroll: slidesToScroll,
				infinite: infinite,
				dots: pagination,
				arrows: navigation,
				adaptiveHeight: autoHeight,
				draggable: draggable,
				swipeToSlide: true,
				swipe: true,
				touchMove: true,
				touchThreshold: 10,
				autoplay: autoplay, 
				autoplaySpeed: autoplaySpeed,
				pauseOnHover: pauseOnHover, 
				vertical: vertical,
				verticalSwiping: verticalSwipe,
				rtl: rtl,
				margin: 20
			};

			if (typeof tabletLandscape !== 'undefined') {
				responsive.responsive.push(rb_carousel_responsive_array(1200, tabletLandscape));
			}

			if (typeof tabletPortrait !== 'undefined') {
				responsive.responsive.push(rb_carousel_responsive_array(992, tabletPortrait));
			}

			if (typeof mobile !== 'undefined') {
				responsive.responsive.push(rb_carousel_responsive_array(768, mobile) );
			} else {
				if (el.parent().hasClass('layout_carousel')) {
					responsive.responsive.push(rb_carousel_responsive_array(768, 3));
					responsive.responsive.push(rb_carousel_responsive_array(480, 1));
				} else {
					responsive.responsive.push(rb_carousel_responsive_array(768, 1));
				}
			}

			args = $.extend({}, args, responsive);

			slick = carousel.slick(args);
		});
	}

	if ($('.compare-box__remove').length) {
		$(document).on('click', '.compare-box__remove', function (evt) {
			evt.preventDefault();

			var el = $(this);

			el.parentsUntil('.item')
				.addClass('loading');

			$.post(el.attr('href'))
				.done(function (data) {
					if (data.status == 'success') {
						$('.badge-num').text(data.count);

						el.parentsUntil('.item').parent()
							.remove();

						if (!$('.item').length) {
							$('.compare-body-heading, .compare-carousel').remove();

							$('.empty').show();
						} else {
							slick.slick('setOption', {}, true);
						}
					}
				})
				.always(function () {
					el.parentsUntil('.item')
						.removeClass('loading');
				});
		});
	}

	if ($('#grid').length) {
		$('#grid').on('click', function () {
			if ($(this).hasClass('active')) {
				return false;
			}

			$(this).addClass('active');

			$('#list').removeClass('active');

			Cookies.remove('_lv');

			$('.products').fadeOut(300, function () {
				$(this).addClass('products-grid')
					.removeClass('products-list')
					.fadeIn(300);
			});
		});

		$('#list').on('click', function () {
			if ($(this).hasClass('active')) {
				return false;
			}

			$(this).addClass('active');

			$('#grid').removeClass('active');

			Cookies.set('_lv', '1', { expires: 365 });

			$('.products').fadeOut(300, function () {
				$(this).removeClass('products-grid')
					.addClass('products-list')
					.fadeIn(300);
			});

			return false;
		});
	}

	/**
	 * Share
	 */
	if ($('.share-label').length) {
		$(document).on('click', '.share-label', function () {
			$(this).parent('.share')
				.toggleClass('show');
		});
	}

	/**
	 * Tabs
	 */
	if ($('.tabs').length) {
		/**
		$('.tabs-content .tabs-content-tab').each(function (index, value) {

			if ($(value).html().length == 16) {
				$('.tabs-nav li').eq(index)
					.remove();

				$(value).remove();
			}

		});
		*/

		$('.tabs .tabs-nav li a').on('click', function (evt) {
			evt.preventDefault();

			var tabs_parent = $(this).parents('.tabs'),
				tabs = tabs_parent.find('.tabs-content'),
				index = $(this).parents('li').index();

			tabs_parent.find('.tabs-nav > .current_tab')
				.removeClass('current_tab');

			$(this).parents('li').addClass('current_tab');

			tabs.find('.tabs-content-tab').not(':eq(' + index + ')')
				.removeClass('active_tab');

			tabs.find('.tabs-content-tab:eq(' + index + ')')
				.addClass('active_tab');
		});
	}

	if ($('.tabsi').length) {
		$('.tabsi .tabs-nav li a').on('click', function (evt) {
			evt.preventDefault();

			var el = $(this),
				type = el.data('type'),
				category = el.data('category');

			var tabs_parent = el.parents('.tabsi'),
				tabs = tabs_parent.find('.tabs-content'),
				index = el.parents('li').index();

			if ($('#content-' + category).is(':empty')) {
				tabs_parent.addClass('loading');

				$.get('?type=' + type + '&category=' + category, function (data) {
					var owl = $(data);

					owl.owlCarousel(owl.data());

					$('#content-' + category).html(owl);

					tabs_parent.find('.tabs-nav > .current_tab')
						.removeClass('current_tab');

					el.parents('li').addClass('current_tab');

					tabs.find('.tabs-content-tab').not(':eq(' + index + ')')
						.removeClass('active_tab');

					tabs.find('.tabs-content-tab:eq(' + index + ')')
						.addClass('active_tab');

					tabs_parent.removeClass('loading');
				});
			} else {
				tabs_parent.find('.tabs-nav > .current_tab')
					.removeClass('current_tab');

				el.parents('li').addClass('current_tab');

				tabs.find('.tabs-content-tab').not(':eq(' + index + ')')
					.removeClass('active_tab');

				tabs.find('.tabs-content-tab:eq(' + index + ')')
					.addClass('active_tab');
			}
		});
	}

	/**
	 * Carousel
	 */
	if ($('.owl-carousel').length) {
		$('.owl-carousel').each(function () {
			var owl = $(this),
				options = $(this).data();

			$(this).owlCarousel(options);
		});
	}

	/**
	 * Toggle Filters
	 */
	if ($('.side-toggle__btn').length) {
		var filterToggle = $('.side-toggle__btn'),
			layer = $('.site__layer'),
			filterPanel= $('.side-panel');

		filterToggle.on('click', function () {
			layer.toggleClass('active');

			$(this).toggleClass('collapsed');

			filterPanel.toggleClass('show');

			$('body').toggleClass('overflow-hidden');
		});

		$('.site__layer, .side-panel-close').on('click', function () {
			layer.removeClass('active');

			filterToggle.removeClass('collapsed');

			filterPanel.removeClass('show');

			$('body').removeClass('overflow-hidden');
		});
	}

	if ($('.currency-group').length) {
		$(document).on('click', '.currency-group .btn', function (evt) {
			$('.currency-group .btn').removeClass('active');

			$(this).addClass('active');

			$('.curr-symbol').text($(this).data('currency'));

			$('input[name="currency"]').val($(this).data('currency'));
		});
	}

	/**
	 * Search
	 */
	if ($('.hero-search__input').length) {
		var search_form = $('.hero form, form.hero-search');

		search_form.find('.form-control')
			.prop('autocomplete', 'off')
			.autocomplete({
				serviceUrl: search_form.attr('action') + 'suggest/',
				params: {
					type: function () {
						if (search_form.find('input[name="type"]:checked').length) {
							return search_form.find('input[name="type"]:checked').val();
						}

						return search_form.find('input[name="type"]').val();
					}
				},
				noCache: true,
				onSelect: function (suggestion) {
					$('body').addClass('loading');

					location.href = suggestion.data;
				}
			});
	}

	if ($('.search_page.hero-search__filter').length) {
		$('.search_page.hero-search__filter :radio').on('change', function () {
			$('input[type="hidden"][name="type"]').val($(this).val());

			$('.product-filter__clear').trigger('click', true);
			$('.product-filter__apply').trigger('click');
		});
	}

	if ($('.product-filter__apply').length) {
		$(document).on('click', '.product-filter__apply, .popup-filter__apply', function (evt, clear) {
			evt.preventDefault();

			var form = $(this).parent()
				.parent();

			if (!form.is('form')) {
				form = form.parent()
					.parent();
			}

			$('.products-row-small-filter').addClass('loading');

			$.ajax(form.attr('action') + '?' + form.serialize())
				.done(function (data) {
					$('.products.row').replaceWith($(data).find('.products.row'));

					$('.found-posts').replaceWith($(data).find('.found-posts:first'));

					$('.side-panel__body').replaceWith($(data).find('.side-panel__body'));

					if ($(data).find('.product-filter__clear').css('display') == 'block') {
						$('.product-filter__clear').show();
					} else {
						$('.product-filter__clear').hide();
					}

					if ($(data).find('.result-count').length) {
						$('.result-count').replaceWith($(data).find('.result-count'));
					} else {
						$('.result-count').hide();
					}

					if ($(data).find('.page-navi').length) {
						$('.page-navi').replaceWith($(data).find('.page-navi'));
					} else {
						$('.page-navi').hide();
					}

					$('.popup-box-content').replaceWith($(data).find('.popup-box-content'));

					$('.popup-box-content-elem').mCustomScrollbar({
						theme: 'minimal'
					});

					$('.select-styler, input:radio, input:checkbox').styler();
				})
				.always(function () {
					$('.products-row-small-filter').removeClass('loading');

					$.fancybox.close();
				});
		});
	}

	if ($('.product-filter__clear').length) {
		$(document).on('click', '.product-filter__clear', function (evt, dont_apply) {
			evt.preventDefault();

			var form = $(this).parent()
				.parent();

			form.find(':input').each(function () {
				if ($(this).is(':checked')) {
					$(this).prop('checked', false)
						.change();
				}
			});

			if (!dont_apply) {
				$('.product-filter__apply').trigger('click');
			}
		});
	}

	if ($('.popup-box').length) {
		$(document).on('change', '.popup-box :checkbox', function (evt) {
			var input_name = $(this).attr('name'),
				checked_inputs = $('.popup-box .product-filter-control input[name="' + input_name + '"]:checked').length;

			if (checked_inputs) {
				$('.filter-ch-elem#' + input_name.slice(0, -2)).show();

				$('.filter-ch-elem#' + input_name.slice(0, -2) + ' .count').text(checked_inputs);
			} else {
				$('.filter-ch-elem#' + input_name.slice(0, -2)).hide();
			}

			if ($('.popup-box :checkbox:checked').length) {
				$('.popup-box-content-elem__95').show();
			} else {
				$('.popup-box-content-elem__95').hide();
			}
		});

		$(document).on('click', '.popup-box .checked-el-close', function (evt) {
			evt.preventDefault();

			var el = $(this).parent()
				.parent();

			$('.popup-box .product-filter-control input[name="' + el.attr('id') + '[]"]')
				.each(function () {
					if ($(this).is(':checked')) {
						$(this).prop('checked', false)
							.change();
					}
				});

			el.hide();

			if (!$('.popup-box-content-elem__95 .filter-ch-elem:visible').length) {
				$('.popup-box-content-elem__95').hide();
			}
		});
	}

	/**
	 * Input Labels
	 */
	if ($('.input__field').length) {
		$('.input__field').on('focus', function () {
			$(this).parent()
				.parent()
				.addClass('edit');
		});

		$('.input__field').on('blur', function() {
			if ($(this).val() == '') {
				$(this).parent()
					.parent()
					.removeClass('edit');
			}
		});
	}

	/**
	 * Fix Height
	 */
	if ($('.carousel-products').length) {
		$('.carousel-products').each(function () {
			var title = $(this);

			setTimeout(function () {
				// equalHeight(title.find('.product-box__title'));
			}, 250);
		});
	}

	if ($('.compare__row_1').length) {
		setTimeout(function () {
			equalHeight('.compare__row_1');
			equalHeight('.compare__row_2');
			equalHeight('.compare__row_3');
			equalHeight('.compare__row_4');
			equalHeight('.compare__row_5');
		}, 250);
	}

	/**
	 * Functions
	 */
	function hasScrolled() {
		var st = $(this).scrollTop();

		if (Math.abs(lastScrollTop - st) <= 5) {
			return;
		}

		if (st > lastScrollTop && st > $('header').outerHeight()) {
			$('header').removeClass('nav-down')
				.addClass('nav-up');
		} else {
			if (st + $(window).height() < $(document).height()) {
				$('header').removeClass('nav-up')
					.addClass('nav-down');
			}
		}

		lastScrollTop = st;
	}

	function rb_carousel_responsive_array(res, cols) {
		var out = {
			breakpoint: res,
			settings: {
				slidesToShow: cols,
				slidesToScroll: 1
			}
		};

		if (res == 768) {
			out.settings['dots'] = true;
			out.settings['arrows'] = true;
			out.settings['adaptiveHeight'] = true;
		}

		return out;
	}

	function equalHeight(el) {
		var highestBox = 0,
			heightAuto = 'auto'
		$(el).height(heightAuto);

		$(el).each(function () {
			if ($(this).height() > highestBox) {
				highestBox = $(this).height(); 
			}
		});

		$(el).height(highestBox);
	}

});
