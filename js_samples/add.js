$(function(){
	var $lists = $('#lists'),
		$icon = $('.b-form-input__icon'),
		$ajax = false;
	
	$('[name=apikey]').keyup(function(e){
		var str = $(this).val();
		
		$lists.hide();
		$ajax && $ajax.abort();
		
		if ( ! e.metaKey && str.search('^[0-9a-z]{32}-[a-z]{2}[0-9]{1}$') != -1)
		{
			$icon.removeClass('cross').addClass('loading_16').removeAttr('title').show();
			
			$ajax = $.post(
				'get_lists',
				{ apikey:str },
				function(json){
					if ( ! json)
					{
						$icon.removeClass('loading_16').addClass('cross').attr('title','Неверный API-ключ');
						return;
					}
					
					var lists = '';
					
					for (var i in json['data'])
					{
						lists += '<option value="' + json['data'][i]['id'] + '">' + json['data'][i]['name'] + '</option>';
					}
					
					$lists.show();
					$lists.find('select').empty().append(lists);
					$icon.hide();
				},
				'json' 
			).error(function(){
				$icon.removeClass('loading_16').addClass('cross').attr('title','Проблемы с соединением');
			});
		}
	});
});
