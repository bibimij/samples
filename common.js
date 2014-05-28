function hideResult(){
	this.fadeOut('medium',function(){$(this).remove();});
}

$(function () {
	var thumb = {
		0:{'width':300,'height':21},
		1:{'width':300,'height':30},
		2:{'width':300,'height':42},
		3:{'width':300,'height':36},
		4:{'width':100,'height':166},
		5:{'width':100,'height':100},
		6:{'width':100,'height':100},
		7:{'width':16,'height':16}
	};
	
	$.ajaxSetup({
		url:'http://domain.com/?module=ad'
	});
	
	var img = new AjaxUpload($('[name=ad_img]'), {
		action:'http://domain.com/?module=ad&action=img-upload',
		name:'ad_img',
		responseType:'json',
		onChange:function(){
			img.disable();
			img.setData({'format': $('[name=format]:checked').val()});
		},
		onComplete:function(f,r){
			switch(r.status){
				case 'success':
					var v = $('[name=format]:checked').val();
					$('.ad_image img').css(thumb[v]).attr('src',r.url);
					$('#delete-image').data({'fname':r.name,'chk':r.chk});
					$('.ad_image').width(thumb[v]['width']).show();
					$('[name=ad_img]').hide().after('<input type="hidden" name="ad_img_file" value="'+r.name+'" />');
				break;
				
				case 'error':
					img.enable();
				break;
				
				case 'badsize':
					alert('ОШИБКА: изображение должно быть размером ' + $('#size').text() + ' px.');
					img.enable();
				break;
			}
		}
	});
	$('#delete-image').live('click', function(){
		$.ajax({
			dataType:'jsonp',
			data:{action:'img-delete','a':$(this).data('fname'),'b':$(this).data('chk')},
			success:function(r){
				if(r.status == 'success'){
					$('.ad_image').hide();
					img.enable();
					$('[name=ad_img]').show();
					$('[name=ad_img_file]').remove();
				}
			}
		});
	});
	
	if(action == "add"){
		$('.ad_image').hide();
	}
	else{
		$('[name=ad_img]').hide();
		var v = $('[name=format]:checked').val();
		$('.ad_image,.ad_image img').width(thumb[v]['width']);
		$('.ad_image img').css(thumb[v]);
	}
	
    $('.teaser,.timerange').hide();
    $('#size').text(formats[0]);
    $('#countries').tokeninput({ajax:{data:{action:'countries'}},labels:{describe:'Введите страну'},tokens:{init:countries}});
	$('#interests').tokeninput({ajax:{data:{action:'interests'}},labels:{describe:'Выберите интересы'},tokens:{init:interests}});
	/*
	$('.submitted').click(function(){
		$('.ad-addedit').submit();
	});*/
	
	$('.submitted').click(function(e){
		e.preventDefault();
		$.ajax({
			url: 'http://domain.com/?module=ad&action=ad-'+action,
			type: 'post',
			data: $('.ad-addedit').serialize(),
			error: function(r) {
				MFDialog.alert(r.responseText);
			},
			success: function(r) {
				MFDialog.build(r, {modal:true, resizable:false, buttons:{"OK":function(){$(this).dialog('close');location.hash = '#ad';}}});
			}
		});
	});
	
	var formats_val = null;
	$('[name=format]').click(function(){
		var val = $(this).val();
		if(formats_val == val) return;
		formats_val = val;
		$('#size').text(formats[val]);
		$('.teaser,.site-show').hide();
		$('.site').show();
		switch(val){
			case '6':
				$('.teaser').show();
			break;
			
			case '7':
				$('.site').hide();
				$('.site-show').show();
			break;
		}
	});
	$('[name=format]:checked').click();
	
	var schedule_val = null;
    $('[name=schedule]').click(function(){
		var val = $(this).val();
		if(schedule_val == val) return;
		schedule_val = val;
		$('.timerange').hide();
		switch(val){
			case '1':
				$('.timerange').show();
			break;
		}
	});
	$('[name=schedule]:checked').click();
		
	$('#addcampaign').click(function(){
		var self = $(this);
		var camp = $.trim(prompt('Введите название кампании',''));
		if(!camp) return;
		self.attr('disabled','disabled');
		$.ajax({
			dataType:'jsonp',
			data:{action:'campaign-add','a':camp},
			success:function(result){
				self.removeAttr('disabled');
				if(result.status == 'success')
					$('<option/>',{val:result.id,text:camp,selected:'selected'}).appendTo('#campaign');
				var $div = $('<span/>',{'class':'notify load'+result.status,text:result.reason}).insertAfter(self).fadeIn();
				setTimeout(function(){hideResult.call($div);}, 3000);
			}
		});
	});
	$('form').submit(function(){		
		$('[name=ruday]').val($('[name=ruday]').fval());
		$('[name=cost]').val($('[name=cost]').fval());
	});
});