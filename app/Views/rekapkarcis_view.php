
<style type="text/css">
    .rkptable {
        border-collapse: collapse;
    }
    .rkpsolid {
        border: 1px solid lightgrey;
        white-space: nowrap;
        padding: 2px 4px
    }
    .rkptitle {
        font-size: 20px;
        font-weight: bold;
        padding: 5px;
        text-align: left;
    }
</style>
<div class="easyui-layout"
    data-options="fit:true,border:true,animate:false"
    style="padding:0px">
    <div data-options="region:'north',border:false"
        style="height:37px;background-color:#8ae0ed;padding:5px">
        <input id="rkp-form-tanggal" name="rkp-form-tanggal" class="easyui-datebox" value="<?php echo date('Y-m'); ?>"/>
        <div id="rkp-btn-go"></div>
    </div>
    <div data-options="region:'center',border:false" style="padding:5px">
        <div id="rkp-div"></div>
    </div>
</div>

<script type="text/javascript">
    $('#rkp-btn-go').linkbutton({
        iconCls: 'fa fa-play-circle',
        height:24,
        onClick:function() {
            var rkp_form_tanggal = $('#rkp-form-tanggal').combobox('getValue');
            var rkp_form_bulan_tahun=rkp_form_tanggal.split("-");
            var rkp_form_bulan=rkp_form_bulan_tahun[1];
            var rkp_form_tahun=rkp_form_bulan_tahun[0];
            $('#rkp-div').panel('showMask');
            $.ajax({
                type:'POST',
                data:{
                    bulan:rkp_form_bulan,
                    tahun:rkp_form_tahun,
                    lok_id:globalConfig.lok_id,
                    db:getDB()
                },
                url:getRestAPI('laporan/rekapkarcis'),
                success:function(retval) {
                    var data = JSON.parse(retval);
                    if ($('#rkp-repdiv').length)
                        $('#rkp-repdiv').remove();
                    $('#rkp-div').append('<div id="rkp-repdiv" '+
                        'class="easyui-panel" data-options="fit:true" '+
                        'style="text-align:center"></div>')
                    $.each(data, function(namarep, datarep) {
                        rkpCetak(namarep, datarep);
                    });
                    $('#rkp-div').panel('hideMask');
                }
            });
        }
    });
    $('#rkp-div').panel({
        fit:true,
        border:false
    });
    $('#rkp-form-tanggal').datebox({
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
						$('#rkp-form-tanggal').datebox('hidePanel').datebox('setValue', year +'-' + month);
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
	var p = $('#rkp-form-tanggal').datebox('panel'),
		tds = false,
		span = p.find('span.calendar-text');
	function myformatter(date) {
		var y = date.getFullYear();
		var m = date.getMonth() + 1;
		return y +'-' + m;
	}
    function rkpCetak(nama, data) {
        $('#rkp-repdiv').append('<div class="rkptitle">'+data.rkpnama+'</div>');
        $('#rkp-repdiv').append('<table class="rkptable" id="rkp-'+nama+'" width="0%" '+
            'height="0%" border="1"></table>');
        $('#rkp-'+nama).append('<tr class="rkpsolid" id="rkp-'+nama+'-header"></tr>');
        $('#rkp-'+nama+'-header').append('<td class="rkpsolid"><b>Kunjungan</b></td>')
        $.each(data.rkpcols, function(colidx, col) {
            $('#rkp-'+nama+'-header').append('<td class="rkpsolid" '+
                'style="width:20px;text-align:right"><b>'+col+'</b></td>');
        });
        $('#rkp-'+nama+'-header').append('<td class="rkpsolid" '+
            'style="text-align:right"><b>Total</b></td>')
        var i=0;
        var formatter = new Intl.NumberFormat('en-US', {});
        $.each(data.rkprows, function(rowidx, row) {
            $('#rkp-'+nama).append('<tr class="rkpsolid" id="rkp-'+nama+'-'+i+'">'+
                '<td class="rkpsolid">'+row+'</td></tr>');
            var total = 0;
            $.each(data.rkpcols, function(colidx, col) {
                var val = '';
                if (data.rkpdata[row] && data.rkpdata[row][col]) {
                    val = formatter.format(data.rkpdata[row][col]);
                    total += parseInt(data.rkpdata[row][col]);
                }
                $('#rkp-'+nama+'-'+i).append('<td class="rkpsolid" style="text-align:right">'+val+'</td>');
            });
            $('#rkp-'+nama+'-'+i).append('<td class="rkpsolid" style="text-align:right">'+
                formatter.format(total)+'</td>');
            i++;
        });
    }
</script>