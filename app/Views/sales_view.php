<style>
span.datebox{
    height:0px;
    border-color:blue;
}
</style>
<div style="padding: 10px;">
	<input id="tanggalgrafik" name="tanggalgrafik" class="easyui-datebox ui-disabled" value="<?php echo date('Y-m'); ?>" maxlength="2"/>
	<div id="tombolgrafik"></div>
</div>
<!-- <div id="bigten-chart" style=" float: left; width: 1500px; height overflow:scroll; ">
	<canvas id="total-bulanan" width="2500"></canvas>
</div> -->
<table border="0"
    style="font-size:14px;font-family:sans-serif;padding:10px">
    <tr>
        <td id="bigten-chart" colspan="2" style="width: 1500px">
			<canvas id="total-bulanan" width="2500" height="200"></canvas>
        </td>
    </tr>
	<tr>
        <td id="bigten-chart2" colspan="2" style="width: 700px;">
			<canvas id="total-bulanan2" width="700" height="200"></canvas>
        </td>
    </tr>
</table>
<script>
    var ctx5 = document.getElementById("total-bulanan").getContext('2d');
	var ctx6 = document.getElementById("total-bulanan2").getContext('2d');
    var d = new Date();
    var datestring = d.getFullYear()  + "-" + (d.getMonth()+1)
    if(globalConfig.login_data){
    kunjungan(datestring);tahunan(datestring);
	}
    $('#tombolgrafik').linkbutton({
        iconCls: 'fa fa-play-circle',
        height:24,
        onClick:function() {
		$('#total-bulanan').remove();
		
		$('#bigten-chart').append('<canvas id="total-bulanan" width="2500" height="200" ></canvas>');
		$('#total-bulanan2').remove();
		
		$('#bigten-chart2').append('<canvas id="total-bulanan2" width="2500" height="200" ></canvas>');
		
		ctx5 = document.getElementById("total-bulanan").getContext('2d');
		ctx6 = document.getElementById("total-bulanan2").getContext('2d');
		var tanggal = $('#tanggalgrafik').combobox('getValue');
		var curr_time = new Date();
        console.log(tanggal)
		kunjungan(tanggal);tahunan(tanggal);

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
		function fillArray(value, len) {
			var arr = [];
			for (var i = 0; i < len; i++) {
				arr.push(value);
			}
			return arr;
		}
		$.ajax({
			url:getRestAPI("nota/grafikbayarnota"),
			data:{
				lok_id:globalConfig.login_data.data.kas_lok_id,
				bln:bulan,
				thn:tahun,
				dayly:'yes'
			},
			type:'POST',
			success:function(retval) {
				var monthNames = ["0","Januari", "Februari", "Maret", "April", "Mei", "Juni",
					"Juli", "Augustus", "September", "Oktober", "November", "Desember"
				];
				console.log(bulan)
				var obj = JSON.parse(retval);
				var title=monthNames[parseInt(bulan)]+" "+tahun
				var label = ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"];
				var value = ["0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0"];
				for (var i in obj.data) {
					value[parseInt(obj.data[i].perhari)-1]=obj.data[i].total;
				}
				console.log(value)
					var bg=fillArray('rgba(255, 99, 132, 0.2)', 30)
					var bd=fillArray('rgba(255,99,132,1)', 30)
				
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
							text: title,
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
	function tahunan(tanggal){
		if(tanggal=='')
		{
			var tahun='';var bulan='';
		}
		else{
		var bulantahun=tanggal.split("-");
		var bulan=bulantahun[1];
		var tahun=bulantahun[0];}
		function fillArray(value, len) {
			var arr = [];
			for (var i = 0; i < len; i++) {
				arr.push(value);
			}
			return arr;
		}
		$.ajax({
			url:getRestAPI("nota/grafikbayarnota"),
			data:{
				lok_id:globalConfig.login_data.data.kas_lok_id,
				bln:bulan,
				thn:tahun,
				monthly:'yes'
			},
			type:'POST',
			success:function(retval) {
				var monthNames = ["0","Januari", "Februari", "Maret", "April", "Mei", "Juni",
					"Juli", "Augustus", "September", "Oktober", "November", "Desember"
				];
				console.log(bulan)
				var obj = JSON.parse(retval);
				var title=tahun
				var label=[]
				var value=[]
				var label = ["1","2","3","4","5","6","7","8","9","10","11","12"];
				var value = ["0","0","0","0","0","0","0","0","0","0","0","0"];
				for (var i in obj.data) {
					// label=obj.data[i].perbulan;
					// value=obj.data[i].total;
					value[parseInt(obj.data[i].perbulan)-1]=obj.data[i].total;
				}
				console.log(value)
					var bg=fillArray('rgba(255, 99, 132, 0.2)', 30)
					var bd=fillArray('rgba(255,99,132,1)', 30)
				
				var myChart2 = new Chart(ctx6, {
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
							text: title,
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