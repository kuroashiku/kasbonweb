<style>
	a.combo-arrow{
		padding-left:0px;
		width:20px !important;
		background: #fff;
	}
	span.datebox{
		height:0px;
		border-color:black;
	}
	span.combo{
		width:200px !important;
	}
</style>
<div style="padding: 10px;">
	<input id="tanggalgrafik" name="tanggalgrafik" class="easyui-datebox ui-disabled" value="<?php echo date('Y-m'); ?>" maxlength="2"/>
	<div id="tombolgrafik"></div>
</div>
<table width="100%" height="50%" border="0"
    style="font-size:14px;font-family:sans-serif;padding:10px">
    <tr>
        <td width="100%" id="bigten-chart" colspan="2">
			<canvas id="total-kunjungan-chart" width="240px" height="70px"></canvas>
        </td>
    </tr>
</table>
<script>
    var ctx5 = document.getElementById("total-kunjungan-chart").getContext('2d');
    var d = new Date();
    var datestring = d.getFullYear()  + "-" + (d.getMonth()+1)
    if(globalConfig.login_data){
    kunjungan(datestring);
	}
    $('#tombolgrafik').linkbutton({
        iconCls: 'fa fa-play-circle',
        height:24,
        onClick:function() {
		$('#total-kunjungan-chart').remove();
		
		$('#bigten-chart').append('<canvas id="total-kunjungan-chart" ></canvas>');
		
		
		ctx5 = document.getElementById("total-kunjungan-chart").getContext('2d');
	
		var tanggal = $('#tanggalgrafik').combobox('getValue');
		var curr_time = new Date();
        console.log(tanggal)
		kunjungan(tanggal);

		}
	});
    $('#tanggalgrafik').datebox({
		editable: false,
		height:24,
		onShowPanel: function () {
			span.trigger('click');
			if (!tds)
			setTimeout(function() {
					tds = p.find('div.calendar-menu-month-inner td');
					tds.click(function(e) {
						e.stopPropagation();
						var year =/\d{4}/.exec(span.html())[0],
						month = parseInt($(this).attr('abbr'), 10);
						$('#tanggalgrafik').datebox('hidePanel').datebox('setValue', year +'-' + month);
					});
				}, 0);
		},
		parser: function (s) {
			if (!s) return new Date();
			var arr = s.split('-');
			return new Date(parseInt(arr[0], 10), parseInt(arr[1], 10)-1, 1);
		},
		formatter: function (d) {
			var currentMonth = (d.getMonth()+1);
			var currentMonthStr = currentMonth <10? ('0' + currentMonth): (currentMonth +'');
			return d.getFullYear() +'-' + currentMonthStr;
		}
	});
	var p = $('#tanggalgrafik').datebox('panel'),
		tds = false,
		span = p.find('span.calendar-text');
	function myformatter(date) {
		var y = date.getFullYear();
		var m = date.getMonth() + 1;
		return y +'-' + m;
	}
    function kunjungan(tanggal){
		if(tanggal=='')
		{
			var tahun='';var bulan='';
		}
		else{
		var bulantahun=tanggal.split("-");
		var bulan=bulantahun[1];
		var tahun=bulantahun[0];}
		$.ajax({
			url:getRestAPI("nota/read"),
			data:{
				lok_id:globalConfig.login_data.data.kas_lok_id,
				bln:bulan,
				thn:tahun,
			},
			type:'POST',
			success:function(retval) {
				var obj = JSON.parse(retval);
				var label = [];
				var value = [];
				for (var i in obj.data) {
					label.push(obj.data[i].not_id);
					value.push(obj.data[i].total);
				}
				
					var bg=[
						'rgba(255, 99, 132, 0.2)',
						'rgba(54, 162, 235, 0.2)',
						'rgba(63, 195, 128, 0.2)',
						'rgba(247, 202, 24, 0.2)',
						'rgba(102, 51, 153, 0.2)',
						'rgba(255, 99, 132, 0.2)',
						'rgba(54, 162, 235, 0.2)',
						'rgba(63, 195, 128, 0.2)',
						'rgba(247, 202, 24, 0.2)',
						'rgba(102, 51, 153, 0.2)'
					];
					var bd=[
						'rgba(255,99,132,1)',
						'rgba(54, 162, 235, 1)',
						'rgba(63, 195, 128, 1)',
						'rgba(247, 202, 24, 1)',
						'rgba(102, 51, 153, 1)',
						'rgba(255,99,132,1)',
						'rgba(54, 162, 235, 1)',
						'rgba(63, 195, 128, 1)',
						'rgba(247, 202, 24, 1)',
						'rgba(102, 51, 153, 1)'
					];
				
				var myChart1 = new Chart(ctx5, {
					type: 'bar',
					plugins: [{
						afterDraw: chart => {
							var ctx = chart.chart.ctx;
							if(value === undefined || value.length == 0)
							{
								ctx.save();
								ctx.textAlign = 'center';
								ctx.font = "18px serif";
								ctx.fillStyle = "gray";
								
								ctx.fillText('TIDAK ADA DATA', chart.chart.width / 2,
									chart.chart.height / 2);
								ctx.restore();
							}
						}
					}],
					tooltipEvents: ["click"],
					data: {
						labels: label,
						datakeys: ["thefirstone","thesecondone","thethirdone","thefourthone",
							"thefifthone","thesixthone"],
						datasets: [{
							label: 'Total Pasien',
							data: value,
							backgroundColor: bg,
							borderColor: bd,
							borderWidth: 1
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						"hover": {
							"animationDuration": 0
						},
						"animation": {
							"duration": 1,
							"onComplete": function() {
								var chartInstance = this.chart,
								ctx = chartInstance.ctx;
								ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize,
									Chart.defaults.global.defaultFontStyle,
									Chart.defaults.global.defaultFontFamily);
								ctx.textAlign = 'center';
								ctx.textBaseline = 'bottom';
								this.data.datasets.forEach(function(dataset, i) {
									var meta = chartInstance.controller.getDatasetMeta(i);
									meta.data.forEach(function(bar, index) {
										var data = dataset.data[index];
										ctx.fillText(data >= 1000 ? data.toString().
											replace(/\B(?=(\d{3})+(?!\d))/g, ","):data,
											bar._model.x, bar._model.y - 5);
									});
								});
							}
						},
						tooltips: {
							enabled: false,
							mode: 'label',
							label: 'mylabel',
							callbacks: {
								label: function(tooltipItem, data) {
									return tooltipItem.yLabel.toString().
										replace(/\B(?=(\d{3})+(?!\d))/g, ",");
								},
							},
						},
						// onClick: function (e) {
                        // var activePointLabel = this.getElementsAtEvent(e)[0]._model.label;
                        // alert( this.getElementAtEvent(e)[0]._model.label);
                    	// },
						title: {
							display: true,
							text: globalConfig.login_data?(globalConfig.login_data?(globalConfig.login_data.lang==1&&globalConfig.login_data.lang?'Total Number Of Patient In Specific Clinic':'Total Pasien Berada di Layanan Tertentu'):'Total Pasien Berada di Layanan Tertentu'):'Total Pasien Berada di Layanan Tertentu',
							fontSize: 20,
						},
						legend: {
							display: false
						},
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero: true,
									callback: function(value, index, values) {
										if (parseInt(value) >= 1000) {
											return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
										} else {
											return value;
										}
									}
								},
								gridLines: {
									display: false
								},
								precision: 0
							}],
							xAxes: [{
								ticks: {
									beginAtZero: true,
									fontSize: 10
								},
								gridLines: {
									display: false
								},
								scaleLabel: {
									display: true,
									fontSize: 10,
								}
							}]
						}
					}
					
				});
				
			}
		});

	}
</script>