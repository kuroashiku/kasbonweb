<html>
    <body>
    <style>
        div.datagrid-header{
            display:none;
        }
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
    <script type="text/javascript">
        $.parser.onComplete = function(){
            $('body').css('visibility','visible');
        };
    </script>
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',border:false">
            <div id="trn-grid"></div>
            <div id="trn-grid-tb" style="padding:5px">
                <div style="padding: 10px;">
                    <input id="tanggalgrafiktransaksi" name="tanggalgrafiktransaksi" class="easyui-datebox ui-disabled" value="<?php echo date('Y-m'); ?>" maxlength="2"/>
                    <div style="margin-right:280px" id="tombolgrafiktransaksi"></div>
                    Total:&nbsp<div id="trn-notatotal"></div>
                </div>
                
            </div>
            <div id="trn-menu" class="easyui-menu" style="width:210px;">
                <table width="100%" height="0%">
                    <tr>
                        <td width="0%" style="vertical-align:top">
                            <table width="70%" height="0%" style="font-size:14px">
                                <tr height="0%">
                                    <input id="trn-form-cil-id">
                                </tr>
                                <tr height="0%">
                                    <input id="trn-form-cil-not-id">
                                </tr>
                                <tr height="0%">
                                    <input id="trn-form-cil-kekurangan">
                                </tr>
                                <tr height="0%">
                                    <input id="trn-form-cil-carabayar">
                                </tr>
                                <tr height="0%">
                                    <input id="trn-form-cil-pay">
                                </tr>
                                <tr height="0%" id="trtagihan">
                                    <td style="white-space:nowrap;vertical-align:middle; padding-left:25px"
                                        id="trn-tagihan-label" width="5%">Tagihan</td>
                                    <td style="white-space:nowrap" width="70%"><input id="trn-tagihan"></td>
                                </tr>
                                <tr height="0%" id="trsisa">
                                    <td style="white-space:nowrap;vertical-align:middle; padding-left:25px"
                                        id="trn-sisa-label" width="5%">Sisa</td>
                                    <td style="white-space:nowrap" width="70%"><input id="trn-sisa"></td>
                                </tr>
                                <tr height="0%" id="trpay">
                                    <td style="white-space:nowrap;vertical-align:middle; padding-left:25px"
                                        id="trn-pay-label" width="5%">Cicilan</td>
                                    <td style="white-space:nowrap" width="70%"><input id="trn-pay"></td>
                                </tr>
                                <tr height="0%" id="trbunga">
                                    <td style="white-space:nowrap;vertical-align:middle; padding-left:25px"
                                        id="trn-bunga-label" width="5%">Bunga</td>
                                    <td style="white-space:nowrap" width="70%"><div id="trn-bunga-left"></div><input id="trn-bunga"><div id="trn-bunga-right" style="padding-left:2px"></div></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <div data-options="id:'trn-mnu-pay',iconCls:'icon-add'">Bayar</div>

                <div data-options="id:'trn-mnu-print',iconCls:'icon-edit'">Print</div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
        var currdate = new Date();
        var currmonthdate = (currdate.getMonth()+1)
        var curryeardate = currdate.getFullYear();
        var globaltotal=0;
        $('#tombolgrafiktransaksi').linkbutton({
            iconCls: 'fa fa-play-circle',
            height:24,
            onClick:function() {
                var tanggal = $('#tanggalgrafiktransaksi').combobox('getValue');
                var d = new Date(tanggal);
                var datestring = d.getFullYear()  + "-" + (d.getMonth()+1)
                var currmonthdate = d.getMonth()+1
                var curryeardate = d.getFullYear()
                console.log(datestring)
                $.post(getRestAPI("nota/read"), {
                    lok_id:globalConfig.login_data.data.kas_lok_id,
                    loaditems:'yes',
                    bln:currmonthdate,
                    thn:curryeardate
                },
                function(data) {
                    var obj = JSON.parse(data);
                    $('#trn-grid').datagrid('loadData',obj.data);
                    globaltotal=0;
                    $.each(obj.data,function(i,v){
                        globaltotal=globaltotal+parseInt(v.total);
                    });
                    $('#trn-notatotal').numberbox('setValue',globaltotal);
                })
            }
        });
        $('#tanggalgrafiktransaksi').datebox({
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
                            $('#tanggalgrafiktransaksi').datebox('hidePanel').datebox('setValue', year +'-' + month);
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
        var p = $('#tanggalgrafiktransaksi').datebox('panel'),
            tds = false,
            span = p.find('span.calendar-text');
        function myformatter(date) {
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            return y +'-' + m;
        }
        var flagtrn=0;
        var indextrn=-1;
        var globalbunga=0;
        $('#trn-notatotal').textbox({
            width:120,
            readonly:true,
        });
        $('#trn-form-cil-id').textbox({
            width:120,
            readonly:true,
        });
        $('#trn-form-cil-not-id').textbox({
            width:120,
            readonly:true,
        });
        $('#trn-form-cil-kekurangan').numberbox({
            width:120,
            readonly:true,
        });
        $('#trn-form-cil-carabayar').textbox({
            width:120,
            readonly:true,
        });
        $('#trn-form-cil-pay').numberbox({
            width:120,
            readonly:true,
        });
        $('#trn-form-cil-pay').textbox('textbox').parent().hide();
        $('#trn-form-cil-kekurangan').textbox('textbox').parent().hide();
        $('#trn-form-cil-carabayar').textbox('textbox').parent().hide();
        $('#trn-form-cil-not-id').textbox('textbox').parent().hide();
        $('#trn-form-cil-id').textbox('textbox').parent().hide();
        $('#trn-tagihan').numberbox({
            width:120,
            readonly:true,
        });
        $('#trn-sisa').numberbox({
            width:120,
            readonly:true,
        });
        $('#trn-bunga').numberbox({
            width:75,
            readonly:true,
            // onChange:function(value) {$('#trn-tagihan').numberbox('setValue',$('#trn-tagihan').numberbox('getValue')*(1+(value/100)))}
        });
        $('#trn-pay').numberbox({
            width:120,
            onChange:function(value) {
                $('#trn-sisa').numberbox('setValue',($('#trn-sisa').numberbox('getValue')-value)<0?0:($('#trn-sisa').numberbox('getValue')-value))
            }
        });
        $('#trn-bunga-left').linkbutton({
            iconCls:'fa fa-caret-left fa-lg',
            width:20,
            onClick:function() {
                var lbg=$('#trn-bunga').numberbox('getValue');
                lbg=parseInt(lbg)-1;
                if(lbg==0)lbg=0;
                $('#trn-tagihan').numberbox('setValue',$('#trn-form-cil-kekurangan').numberbox('getValue')*(1+(lbg/100)))
                $('#trn-bunga').numberbox('setValue',lbg);
                $('#trn-sisa').numberbox('setValue',$('#trn-tagihan').numberbox('getValue')-$('#trn-form-cil-pay').numberbox('getValue'));
            }
        });
        $('#trn-bunga-right').linkbutton({
            iconCls:'fa fa-caret-right fa-lg',
            width:20,
            onClick:function() {
                var rbg=$('#trn-bunga').numberbox('getValue');
                rbg=parseInt(rbg)+1;
                $('#trn-tagihan').numberbox('setValue',$('#trn-form-cil-kekurangan').numberbox('getValue')*(1+(rbg/100)))
                $('#trn-bunga').numberbox('setValue',rbg);
                $('#trn-sisa').numberbox('setValue',$('#trn-tagihan').numberbox('getValue')-$('#trn-form-cil-pay').numberbox('getValue'));
            }
        });
        $('#trn-btn-konversisave').linkbutton({
            text: 'Simpan',
            iconCls:'fa fa-user-plus fa-lg',
            onClick:function() {trnSaveKonversi();}
        });
        $.post(getRestAPI("nota/read"), {
            lok_id:globalConfig.login_data.data.kas_lok_id,
            loaditems:'yes',
            bln:currmonthdate,
            thn:curryeardate
        },
        function(data) {
            var obj = JSON.parse(data);
            console.log(obj)
            globaltotal=0;
            $.each(obj.data,function(i,v){
                globaltotal=globaltotal+parseInt(v.total);
            });
            $('#trn-notatotal').textbox('setValue',currencyFormat(globaltotal));
            $('#trn-grid').datagrid({
                border:false,
                singleSelect:true,
                editorHeight:22,
                toolbar:'#trn-grid-tb',
                fit:true,
                columns:[[{
                    field:'not_id',
                    title:'ID',
                    resizable:false,
                    width:40,
                    hidden:true
                },{
                    field:'cus_nama',
                    title:'Nama',
                    resizeble:false,
                    width:770,
                    formatter: function(value, row) { 
                        var options = { year: 'numeric', month: 'long', day: 'numeric' };
                        var now = new Date();
                        var jatuhtempo = new Date(row.jatuhtempo);
                        var tgljatuhtempo  = jatuhtempo.toLocaleDateString("in-ID",options);
                        var tgllunas  = new Date(row.tanggal).toLocaleDateString("in-ID",options);
                        var items='';
                        
                        var nama=row.cus_nama?row.cus_nama:'NoNama';
                        
                        // var d2 = new Date("2011/02/01");
                        var diff = Math.round(row.jatuhtempo?(now-jatuhtempo)/(1000 * 60 * 60 * 24):0);
                        
                        $.each(row.notaitems,function(i,v){
                            items=items+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-9">'+
                                    v.itm_nama+
                                '</div>'+
                                '<div class="col-md-3">'+
                                    currencyFormat(v.total)+
                                '</div>'+
                            '</div>'+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12" style="font-size:16px">'+
                                    (v.satuan0?v.satuan0:v.satuan1)+
                                '</div>'+
                            '</div>'
                        })
                        return '<div class="container" style="padding-bottom:3px;">'+
                            (diff>0&&row.piutlunas==0?'<div class="row" style="padding-bottom:3px; color: red;">':'<div class="row" style="padding-bottom:3px; color: blue;">')+
                                (row.dicicil==1?'<div class="col-md-6 text-truncate">':'<div class="col-md-9 text-truncate">')+
                                    '<span>'+row.nomor+' ('+nama+')</span>'+
                                '</div>'+
                                (row.dicicil==1&&row.piutlunas==0?('<div class="col-md-3 text-truncate">'+
                                '<span style="color:red !important;">Piutang</span>'+(diff>0?' <span style="color:red !important;">telat '+Math.abs(diff)+' hari</span>':'')+'</div>'):
                                ((row.dicicil==1&&row.piutlunas==1)?('<div class="col-md-3 text-truncate">'+
                                '<span style="color:blue !important;">Piutang Lunas</span></div>'):''))+
                                '<div class="col-md-2">'+
                                    currencyFormat(row.total)+
                                '</div>'+
                            '</div>'+
                            items+    
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12">'+
                                    'Bayar pakai '+row.carabayar+
                                '</div>'+
                            '</div>'+                        
                        '</div>'
                    }
                }]],
                data:obj.data,
                onSelect:function(index, row) {
                    $('#trn-pay').numberbox('setValue','');
                    $('#trn-bunga').numberbox('setValue','');
                    $.ajax({
                        type:'POST',
                        data:{
                            not_id:row.not_id
                        },
                        url:getRestAPI('piutang/read'),
                        success:function(retval) {
                            var obj = JSON.parse(retval);
                            if(obj.data[0])
                            {
                                globalbunga=obj.data[0].cil_bunga;
                                $("#trbunga").show();
                                $("#trpay").show();
                                $("#trtagihan").show();
                                $("#trsisa").show();
                                $("#trn-mnu-pay").show();
                                $('#trn-form-cil-carabayar').textbox('setValue',obj.data[0].cil_carabayar);
                                $('#trn-form-cil-kekurangan').numberbox('setValue',obj.data[0].cil_kekurangan);
                                $('#trn-form-cil-not-id').textbox('setValue',obj.nota.id);
                                $('#trn-form-cil-id').textbox('setValue',obj.data[0].cil_id);
                                $('#trn-tagihan').numberbox('setValue',obj.data[0].cil_tagihan);
                                $('#trn-sisa').numberbox('setValue',obj.data[0].cil_sisa);
                                $('#trn-bunga').numberbox('setValue',obj.data[0].cil_bunga);
                                $('#trn-form-cil-pay').numberbox('setValue',obj.data[0].cil_cicilan);
                            }
                            else{
                                $("#trbunga").hide();
                                $("#trpay").hide();
                                $("#trtagihan").hide();
                                $("#trsisa").hide();
                                $("#trn-mnu-pay").hide();
                            }
                        }
                    })
                    // console.log(row)
                    var options = { year: 'numeric', month: 'long', day: 'numeric' };
                    var now = new Date();
                    var jatuhtempo = new Date(row.jatuhtempo);
                    var tgljatuhtempo  = jatuhtempo.toLocaleDateString("in-ID",options);
                    var tgllunas  = new Date(row.tanggal).toLocaleDateString("in-ID",options);
                    var diff = Math.round(row.jatuhtempo?(now-jatuhtempo)/(1000 * 60 * 60 * 24):0);
                    indextrn=index;
                },
                onRowContextMenu:function(e,index,row) {
                    if (row) {
                        $('#trn-grid').datagrid('selectRow',index);
                        e.preventDefault();
                        $('#trn-menu').menu('show',{
                            left:e.pageX,
                            top:e.pageY,
                            onClick:function(item) {
                                switch(item.id) {
                                case 'trn-mnu-pay':
                                    trnPay();
                                    break;
                                case 'trn-mnu-print':
                                    trnPrint();
                                    break;
                                }
                            }
                        });
                    }
                }
            });
        });
        
        function trnSaveKonversi(){
            
        }
        function trnPay(){
            $.ajax({
                type:'POST',
                data:{
                    cil_id:$('#trn-form-cil-id').textbox('getValue')?$('#trn-form-cil-id').textbox('getValue'):0,
                    cil_not_id:$('#trn-form-cil-not-id').textbox('getValue'),
                    cil_kekurangan:$('#trn-form-cil-kekurangan').textbox('getValue'),
                    cil_bunga:$('#trn-bunga').numberbox('getValue'),
                    cil_tagihan:$('#trn-tagihan').numberbox('getValue'),
                    cil_cicilan:parseInt($('#trn-pay').numberbox('getValue'))+parseInt($('#trn-form-cil-pay').numberbox('getValue')),
                    cil_sisa:$('#trn-sisa').numberbox('getValue'),
                    cil_carabayar:$('#trn-form-cil-carabayar').textbox('getValue')
                },
                url:getRestAPI('piutang/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    $('#trn-form-cil-carabayar').textbox('setValue',obj.data[0].cil_carabayar);
                    $('#trn-form-cil-kekurangan').textbox('setValue',obj.data[0].cil_kekurangan);
                    $('#trn-form-cil-not-id').textbox('setValue',obj.data[0].cil_not_id);
                    $('#trn-form-cil-id').textbox('setValue',obj.data[0].cil_id);
                    $('#trn-tagihan').numberbox('setValue',obj.data[0].cil_tagihan);
                    $('#trn-sisa').numberbox('setValue',obj.data[0].cil_sisa);
                    $('#trn-bunga').numberbox('setValue',obj.data[0].cil_bunga);
                    $('#trn-pay').numberbox('setValue','');
                    
                    console.log(obj)
                }
            })
        }
        function numberWithCommas(x) {
            var parts = x.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }
        ///////fungsi penunjang
        function changeFieldBorderColor(field, color){
            var t = $(field);
            var el = t.data('textbox') ? t.next() : $(t);
            el.css('border-color', color);
        }
    })
    
    </script>
    </body>
</html>