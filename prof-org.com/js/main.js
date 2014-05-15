

$(function(){
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
