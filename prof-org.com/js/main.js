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

	if($form.hasClass('serverside')){
		$form.serverValidate(validationOptions);
	} else {
		$form.validate(validationOptions);
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

$(function(){

	doForms();

	$('.login-form').loginForm();

	$('.withdraw-form').each(function(){
		var $root = $(this),
				$slider = $root.find('.slider'),
				$sumInput = $root.find('.sum-area input');
		$slider.slider({
			min: 1,
			step: 1,
			max: 1000,
			value: 100,
			create: function(event, ui){
				$slider.append('<div class="ui-slider-range" />');
				var $handle = $slider.find('.ui-slider-handle');
				$slider.find('.ui-slider-range').width($handle.offset().left);
			},
			slide: function(event,ui){
				$slider.find('.ui-slider-range').width($(ui.handle).offset().left);
				calc(ui.value);
			},
			stop: function(event, ui){
				$slider.find('.ui-slider-range').width($(ui.handle).offset().left);
				calc(ui.value);
			}			
		});

		function calc(value){
			$sumInput.val(value);
		}
	});

});
