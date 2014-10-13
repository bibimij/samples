<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title>Курс валют</title>
	<script type="text/javascript" src="http://yandex.st/jquery/2.1.1/jquery.min.js"></script>
	<script type="text/javascript" src="http://www.amcharts.com/lib/3/amcharts.js"></script>
	<script type="text/javascript" src="http://www.amcharts.com/lib/3/serial.js"></script>
	<script type="text/javascript" src="http://www.amcharts.com/lib/3/themes/none.js"></script>
	<script>
		$(function(){
			var $form = $('form'),
				$inputs = $form.find('input,select').removeAttr('disabled'),
				chart_data = <?php echo json_encode($data['raw']);?>,
				url = location.href.split('?')[0];

			if (chart_data)
			{
				AmCharts.makeChart('chart', {
					"type": "serial",
					"theme": "none",
					"pathToImages": "http://www.amcharts.com/lib/3/images/",
					"dataDateFormat": "YYYY-MM-DD",
					"graphs": [{
						"bullet": "round",
						"bulletBorderAlpha": 1,
						"bulletColor": "#FFFFFF",
						"bulletSize": 5,
						"hideBulletsCount": 50,
						"lineThickness": 2,
						"title": "red line",
						"useLineColorForBulletBorder": true,
						"valueField": "value"
					}],
					"chartCursor": {},
					"categoryField": "date",
					"categoryAxis": {
						"parseDates": true,
						"dashLength": 1,
						"minorGridEnabled": true,
						"position": "top"
					},
					"dataProvider": chart_data
				});
			}

			var submit = function(e){
				e.preventDefault();

				$form.find('.b-loading').remove();

				$.ajax({
					url:url,
					type:$form.attr('method'),
					data:$form.serialize(),
					dataType:'json',
					global:false,
					beforeSend:function(){
						$inputs.attr('disabled', true);
						$form.append('<span class="b-loading">Идёт загрузка…</span>');
					},
					success:function(r){
						$('table').html(r.html);

						AmCharts.makeChart('chart', {
							"type": "serial",
							"theme": "none",
							"pathToImages": "http://www.amcharts.com/lib/3/images/",
							"dataDateFormat": "YYYY-MM-DD",
							"graphs": [{
								"bullet": "round",
								"bulletBorderAlpha": 1,
								"bulletColor": "#FFFFFF",
								"bulletSize": 5,
								"hideBulletsCount": 50,
								"lineThickness": 2,
								"title": "red line",
								"useLineColorForBulletBorder": true,
								"valueField": "value"
							}],
							"chartCursor": {},
							"categoryField": "date",
							"categoryAxis": {
								"parseDates": true,
								"dashLength": 1,
								"minorGridEnabled": true,
								"position": "top"
							},
							"dataProvider": r.raw
						});
					},
					error:function(){},
					complete:function(){
						$inputs.removeAttr('disabled');
						$form.find('.b-loading').remove();
					}
				});
			};
			
			$inputs.on('change', submit);
		});
	</script>
	<style>
		body {font-family:Helvetica,Arial,sans-serif; font-size:13px;}
		input[type=text] {width:30px;}
		table {margin:50px 0;}
		table th {padding:2px 3px; border-bottom:1px solid #ddd; text-align:center;}
		.b-curs {padding:7px;}
		.b-error {background:#f9f2f4; color:#c7254e; border:1px solid #c7254e; padding:10px; text-align:center; width:450px;}
		.b-chart {width:100%; height:300px;}
	</style>
</head>
<body>
	<form action="" method="get">
		<?php echo HTML::select('M', $months, $_GET['M']);?>
		<?php echo HTML::text('Y', $_GET['Y'], date('Y'));?>
		<?php echo HTML::select('currency', $currencies, $_GET['currency']);?>
		<input type="submit" value="Загрузить">
	</form>
	
	<table cellpadding="0" cellspacing="0">
	<?php echo $data['html'];?>
	</table>

	<div id="chart" class="b-chart"></div>
</body>
</html>