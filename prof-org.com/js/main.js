var $dom = {
			document: $(document),
			window: $(window),
			html: $('html'),
			body: $('body'),
			htmlBody: $('html, body')
		},
		bPopupOptions = {
			speed: 100,
			followSpeed: 100,
			closeClass: 'popup-close'
		},
		validationOptions = {
			beforeSubmit: function($form){
				$form.find('input.disabled').prop('disabled', true);
				$form.find('a.submit').addClass('disabled');
			},
			afterValidation: function($form){
				$form.find('input.disabled').prop('disabled', false);
				$form.find('a.submit').removeClass('disabled');
			}
		};




$.confirm = function(o){
	var o = $.extend(true, {
		content: '',
		ok: null,
		cancel: null
	}, o);
	$('<div class="popup-src confirm-popup"><div class="wrap">'+o.content+'<div class="actions"><a class="btn red cancel">Отмена</a><a class="btn green ok">OK</a></div><a class="popup-close"></a></div></div>').bPopup($.extend(true, bPopupOptions, {
		onOpen: function(){
			var $popup = this;
			$popup.find('.btn.ok').bind('click', function(){
				$popup.bPopup().close();
				if(typeof o.ok == 'function'){
					o.ok.call(this);
				}
			});
			$popup.find('.btn.cancel').bind('click', function(){
				$popup.bPopup().close();
				if(typeof o.cancel == 'function'){
					o.cancel.call(this);
				}
			});
		}
	}));
}

$.alert = function(o){
	var o = $.extend(true, {
		content: '',
		ok: null
	}, o);
	$('<div class="popup-src alert-popup"><div class="wrap">'+o.content+'<div class="actions"><a class="btn green ok">OK</a></div><a class="popup-close"></a></div></div>').bPopup($.extend(true, bPopupOptions, {
		onOpen: function(){
			var $popup = this;
			$popup.find('.btn.ok').bind('click', function(){
				$popup.bPopup().close();
				if(typeof o.ok == 'function'){
					o.ok.call(this);
				}
			});
		},
		onClose: function(){
			if(typeof o.ok == 'function'){
				o.ok.call(this);
			}
		}
	}));
}

$.ajaxPopup = function(o){
	var o = $.extend(true, {
		link: null,
		url: '',
		data: undefined,
		load: null,
		useNanoScroll: undefined
	}, o)
	$.ajax({
		method: 'post',
		url: o.url,
		data: o.data,
		complete: function(data){
			if(data && data.responseText){
				$(data.responseText).bPopup($.extend(true, bPopupOptions, {
					useNanoScroll: o.useNanoScroll,
					callback: function(){
						if(typeof o.load == 'function'){
							o.load.call(this, o.link);
						}
					}
				}));

			}
		}
	});
}



//style scrollbars (for select box):
$.fn.doNanoScroll = function(){
		return this.each(function(){
				var $el = $(this),
						$ik_inner = $el.closest('.ik_select_list_inner');
				if($ik_inner.css('overflow') == 'auto'){
						if($el.data('hasnanoscroll') !== true){
								$el.wrap('<div class="nano"><div class="nanocontent">');
								$el.data('hasnanoscroll', true);
						}
						$el.closest('.nano').nanoScroller();
				} else {
						if($el.data('hasnanoscroll') == true){
								$el.closest('.nano').nanoScroller({stop: true});
						}
				}
		});
};

$.fn.ikSelectNano = function(o){
		return this.each(function(){
				var $select = $(this);
				$select.ikSelect(o ? o : {
						syntax: "<div class=\"ik_select_link_wrap\"><div class=\"ik_select_link\"><span class=\"ik_select_link_text\"></span></div></div><div class=\"ik_select_block"+($select.closest('.big-input').length ? ' big-input' : '')+($select.hasClass('units-select') ? ' units-select' : '')+($select.hasClass('big-link') ? ' big-link' : '')+"\"><div class=\"ik_select_list\"></div></div>",
						autoWidth: false,
						ddFullWidth: false,
						ddMaxHeight: 240,
						skipFirst: true,
						keepInsideWindow: false
				}).bind('ikshow', function(){
					$select.data('plugin_ikSelect').block.find('.ik_select_list_inner > ul').doNanoScroll();
				});
		});
};


$.fn.autoNano = function(o){
		return this.each(function(){
				var $root = $(this),
						rootData = $root.data('autonano');
				o = rootData ? rootData : $.extend({
							scrollX: true,
							scrollY: true,
							adjustWrapSize: true,
							adjustPaneXPosition: true
						}, o);

				$.fn.adjustWrapSize = function(){
					return this.each(function(){
						var $root = $(this),
								$children = $root.children(),
								w = 0;
						$children.each(function(){
							var thisWidth = $(this).outerWidth();
							if(thisWidth > w){
								w = thisWidth;
							}
						});
						$root.width(w);
					});
				}

				function nanoRefresh(){
					if(!$root.is(':visible')) return;

					var rootHeight = $root.height(),
							rootWidth = $root.width();

					if(!$root.data('autonano')){
							$root.wrapInner('<div class="nano"><div class="nanocontent"><div class="nanowrap">');
							$root.data('autonano', o);
					}

					var $nanowrap = $root.find('.nanowrap'),
							$nano = $root.find('.nano');

					if(o.adjustWrapSize == true) $nanowrap.adjustWrapSize();

					if(o.scrollX !== true){
						rootWidth = $nanowrap.width();
						$root.width(rootWidth);
					}

					if(o.scrollY !== true){
						rootHeight = $nanowrap.height();
						$root.height(rootHeight);
					}

					if(rootWidth < $nanowrap.width() || rootHeight < $nanowrap.height()){
						$nano.width(rootWidth).height(rootHeight).nanoScroller();
					} else {
							if($root.data('autonano')){
								$nano.width(rootWidth).height(rootHeight);
								$nano.nanoScroller({stop: true});
							}
					}

					if(o.scrollX && o.adjustPaneXPosition){
						adjustPaneXPosition();
					}

				}

				nanoRefresh();

				function adjustPaneXPosition(){
					var windowBottomOffset = 15,
							$paneX = $('.autonano:visible .pane-x'),
							$nano = $paneX.closest('.nano');
					if(!$paneX.length || !$nano.length) return;
					var nanoHeight = $nano.height(),
							oldBottom = parseInt($paneX.css('bottom')),
							oldOffset = $paneX.offset().top,
							scrollTop = $dom.window.scrollTop(),
							windowHeight = $dom.window.height(),
							delta = scrollTop + windowHeight - windowBottomOffset - oldOffset,
							newBottom = delta > oldBottom ? 0 : (oldBottom - delta < nanoHeight - windowBottomOffset ? oldBottom - delta : nanoHeight - windowBottomOffset);

					$paneX.css({
						bottom: newBottom+'px'
					});

				}

				if(!rootData){
					$dom.window.debounce('resize.nano', nanoRefresh, 60);
					$dom.window.debounce('scroll.nano', adjustPaneXPosition, 25);
					if($root.closest('.ui-tabs').length){
						$root.closest('.ui-tabs').bind('tabsactivate', nanoRefresh);
					}
				}

		});
};


$.fn.serverValidate = function(o){
	var specialInputs = {
			},
			specialErrors = {
			};

	o = $.extend({
		afterValidation: null,
		submitAction: null
	}, o);

	$.fn.setError = function(){
		return this.each(function(){
			var $el = $(this);
			if($el.parent('.ik_select').length){
				$el.parent('.ik_select').find('.ik_select_link').addClass('error');
			}
			$el.addClass('error').one('focus click change', function(){
				$(this).removeError();
			});
		});
	}

	$.fn.removeError = function(){
		return this.each(function(){
			var $el = $(this);
			if($el.parent('.ik_select').length){
				$el.parent('.ik_select').find('.ik_select_link').removeClass('error');
			}
			$el.removeClass('error');
		});
	}

	return this.each(function(){
		var $form = $(this);

		$form.bind('submit', function(){
			$form.ajaxSubmit({
				data: {
					ajax: ajaxFormName = $form.attr('name') || ''
				},
				beforeSubmit: o.beforeSubmit($form) || null,
				complete: function(response){
					var validSuccess = true;
					try{
						response = $.parseJSON(response.responseText);
					} catch(e) {
						return;
					}

					var len = 0;
					for(var key in response){
						len += Number(response.hasOwnProperty(key));
					}
					if(len > 0){
						validSuccess = false;
					}

					$form.find('input, select, textarea, .item').each(function(){
						var $el = $(this);
						if(response[$el.attr('id')]){
							$el.setError();
							validSuccess = false;
						} else {
							$el.removeError();
						}
					});
					$.each(response, function(index, value){
						if(specialErrors[index]){
							$.alert({
								content: '<div class="header">Ошибка</div><p>'+value+'</p>'
							});
							return;
						}
						if(!$('#'+index).length){
							if($(specialInputs[index]).length){
								$(specialInputs[index]).setError();
							}
						}
					});

					if(typeof o.afterValidation == 'function'){
						o.afterValidation($form);
					}

					if(validSuccess !== false){
						if(typeof o.submitAction == 'function'){
							o.submitAction.call($form);
							return false;
						} else {
							$form[0].submit();
						}
					} else {
						//errors
						if($('.error').length){
							$('html, body').scrollTop($('.error').eq(0).offset().top);
						}
						return false;
					}
				}
			});
			return false;
		});
	});
}

$.fn.myForm = function(){
	return this.each(function(){
		var $form = $(this);

	$form.find('input[type=checkbox]').prettyCheckboxes();
	$form.find('select').ikSelectNano();

	if($form.hasClass('serverside')){
		$form.serverValidate(validationOptions);
	} else {
		$form.validate(validationOptions);
	}

	if($form.find('a.submit').length && !$form.find('input[type=submit]').length){
		$form.append('<input type="submit" class="visuallyhidden">');
	}

	if(!$dom.html.hasClass('form-events')){
		$dom.html.on('click focus', 'input.disabled', function(e){
			$(this).blur();
			return false;
		});
		$dom.html.on('click', 'form a.submit:not(.disabled)', function(e){
			e.preventDefault();
			var $a = $(this),
					$form = $a.closest('form');
			$form.submit();
		});
		$dom.html.on('click', '.btn.disabled', function(e){
			e.preventDefault();
			return false;
		});	
		$dom.html.addClass('form-events');
	}


	});
}

function doForms(){
	$('form').myForm();
}

$.fn.loginForm = function(){
	return this.each(function(){
		var $root = $(this);
			$root.find('a.forgot').bind('click', function(e){
				e.preventDefault();
				$root.find('.login-screen').hide();
				$root.find('.forgot-screen').show();
			});
			$root.find('a.backtologin').bind('click', function(e){
				e.preventDefault();
				$root.find('.forgot-screen').hide();
				$root.find('.emailsent-screen').hide();
				$root.find('.login-screen').show();
			});

	});
}

function moneyFormat(val){
	return val.toFixed(2);
}

$.fn.dataTable = function(){
	return this.each(function(){
		var $root = $(this);
		$root.find('.btn').bind('click', function(e){
			e.preventDefault();
			var $el = $(this);
			if($el.hasClass('disabled')) return false;
			if(!$el.data('id')) return false;
			var id = $el.data('id'),
					url = $el.data('url'),
					action = $el.data('action');
			$.confirm({
				content: '<div class="content big">Вы уверены?</div>',
				ok: function(){
					var data = {};
					data[action] = id;
					$.ajax({
						method: 'post',
						url: url,
						data: data,
						complete: function(data){
							if(data && data.responseText){
								data = $.parseJSON(data.responseText);
								if(data.status){
									$.alert({
										content: '<div class="content big">'+data.status+'</div>'
									});
									if(action == 'confirm'){
										$el.addClass('disabled');
									} else {
										$el.closest('tr').remove();									
									}
								}
							}
						}
					});
				}
			});
		});
	});
}

$(function(){

	doForms();

	$('.login-form').loginForm();

	$('.data-table').dataTable();

	$('.withdraw-form').each(function(){
		var $root = $(this),
				$slider = $root.find('.slider'),
				$sumInput = $root.find('.sum-area input'),
				$feeInput = $root.find('.fee-input'),
				$feeSpan = $root.find('.fee .value'),
				$restInput = $root.find('.rest-input'),
				$restSpan = $('.bignum.available-display'),
				$usersumInput = $root.find('.usersum-input'),
				$usersumSpan = $root.find('.usersum .value'),
				available = $root.find('.available-input').val(),
				balance = parseFloat($root.find('.balance-input').val()) || 0,
				salary = parseFloat($root.find('.salary-input').val()),
				percentfee = parseFloat($root.find('.percentfee-input').val()),
				percentcredit = parseFloat($root.find('.percentcredit-input').val()),
				days = parseFloat($root.find('.days-input').val()),
				initValue = Math.floor($sumInput.val()),
				maxValue,
				maxNoCredit,
				sumValue;

		maxValue = available;
		maxNoCredit = balance > 0 ? balance : 0;
		balance = maxNoCredit;

		if(window.location.href.indexOf('success=1') !== -1){
			$.alert({
				content: '<div class="content big">Операция выполнена успешно.</div>'
			});
		}

		$slider.slider({
			min: 1,
			step: 1,
			max: Math.floor(maxValue),
			value: initValue,
			create: function(event, ui){
				$slider.append('<div class="ui-slider-range" />');
				var $handle = $slider.find('.ui-slider-handle');
				$slider.find('.ui-slider-range').css({width: (100 * initValue/maxValue) + '%'});
				calc(initValue);
			},
			slide: function(event,ui){
				$slider.find('.ui-slider-range').css({width: (100 * ui.value/maxValue) + '%'});
				calc(ui.value);
			},
			change: function(event, ui){
				$slider.find('.ui-slider-range').css({width: (100 * ui.value/maxValue) + '%'});
			},
			stop: function(event, ui){
				$slider.find('.ui-slider-range').css({width: (100 * ui.value/maxValue) + '%'});
				calc(ui.value);
			}			
		});

		$sumInput.bind('change keyup', function(){
			var val = $sumInput.val() * 1;
			if(val > maxValue){
				$sumInput.val(sumValue);
				return false;
			}
			sumValue = val;
			$slider.slider('value', val);
			calc(val);
		});

		if(Math.floor(available) < 1){
			calc(-1);
			$sumInput.prop('disabled', true);
			$slider.slider('disable');
		}

		function calc(value){
			sumValue = value;
			var fee = value <= maxNoCredit ?
									value * percentfee / (100) : 
									value * percentfee / (100) + (value - balance) * (days * percentcredit) / (100),
					rest = available - value,
					usersum = value - fee;
			if(value < 0){
				fee = 0;
				rest = 0;
				usersum = 0;
			}
			if(value <= 0){
				$root.find('.btn.submit').addClass('disabled');
			} else {
				$root.find('.btn.submit').removeClass('disabled');
			}
			$sumInput.val(value);
			$feeInput.val(moneyFormat(fee));
			$feeSpan.text(moneyFormat(fee))
			$restInput.val(moneyFormat(rest));
			$restSpan.text(moneyFormat(rest));
			$usersumInput.val(moneyFormat(usersum));
			$usersumSpan.text(moneyFormat(usersum));
		}
	});

	$('.cards-form').each(function(){
		var $root = $(this),
				$form = $root.find('form').eq(0);

		$root.find('input[type=radio]').prettyCheckboxes().bind('change', function(){
			var $input = $(this);
			if($input.is(':checked')){
				var ccid = $input.closest('.cc').find('.cc-id').val();
				$.ajax({
					method: 'post',
					url: $form.attr('action'),
					data: {
						major: ccid
					}
				});
			}
		});

		$root.find('.btn.cross').bind('click', function(){
			if($(this).hasClass('disabled')) return false;
			$root.find('.btn.cross').addClass('disabled');
			$(this).addClass('red');
			$root.find('.actions.default').hide();
			$root.find('.actions.delete').show();
			$root.find('.bottom-actions').hide();
		});

		$root.find('.actions.delete .btn').bind('click', function(){
			if($(this).hasClass('ok')){
				var $deletecc = $root.find('.btn.cross.red').closest('.cc'),
						ccid = $deletecc.find('.cc-id').val();
				$.ajax({
					method: 'post',
					url: $form.attr('action'),
					data: {
						delete: ccid
					},
					complete: function(data){
						if(data && data.responseText){
							data = $.parseJSON(data.responseText);
							if(data.status){
								$deletecc.remove();
								$.alert({
									content: '<div class="content">'+data.status+'</div>'
								});
							}
						}
					}
				});
			}
			$root.find('.btn.cross').removeClass('disabled red');
			$root.find('.actions.delete').hide();
			$root.find('.actions.default').show();
			$root.find('.bottom-actions').show();
		});

	});

});
