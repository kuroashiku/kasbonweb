<div class="easyui-layout"
    data-options="fit:true,border:true,animate:false"
    title="Laporan 1" style="padding:0px">
    <div id="dks_query" data-options="region:'north',
        split:true,
        title:'Query',
        hideCollapsedContent:false,
        collapsed:true,
        border:false"
        style="height:200px;background-color:#8ae0ed;padding:5px">
        <input id="dks-form-sql">
    </div>
    <div id="dks-panel" data-options="region:'center',
        border:false,
        onOpen:function(){$('#dks-form-laporan').combobox('setValue', 'rl2-keadaan.sql');}">
        <div id="dks-grid"></div>
        <div id="dks-grid-tb" style="padding:5px">
            <input id="dks-form-laporan">&nbsp;
            <div id="dks-btn-execute"></div>
            <div id="dks-btn-exportxls"></div>
            <div id="dks-btn-exportcsv"></div>
            <div id="dks-btn-print"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#dks-form-sql').textbox({
        fit:true,
        multiline:true,
        inputEvents:$.extend({}, $.fn.textbox.defaults.inputEvents, {
            keypress:function(e) {
                if (e.ctrlKey && e.which == 13)
                    dksExecute();
            }
        })
    });
    $('#dks_query').find('textarea').css({
        'font-family':'monospace',
        'font-size':'12'
    });
    $('#dks-form-laporan').combobox({
        width:300,
        height:24,
        valueField:'lap_id',
        textField:'lap_label',
        editable:false,
        panelHeight:'auto',
        data:[
            {lap_id:'rl2-keadaan.sql', lap_label:'RL 2 Ketenagaan'},
            {lap_id:'rl3.3-gigi-mulut.sql', lap_label:'RL 3.3 Gigi Mulut'},
            {lap_id:'rl3.14-rujukan.sql', lap_label:'RL 3.14 Rujukan'},
            {lap_id:'rl4.B-penyakit-rajal.sql', lap_label:'RL 4.B Penyakit Rawat Jalan'},
            {lap_id:'rl5.1-pengunjung.sql', lap_label:'RL 5.1 Pengunjung'},
            {lap_id:'rl5.2.1-kunjungan-ranap.sql', lap_label:'RL 5.2.1 Kunjungan Rawat Inap'},
            {lap_id:'rl5.2-kunjungan-rajal.sql', lap_label:'RL 5.2 Kunjungan Rawat Jalan'},
            {lap_id:'rl5.4-10-besar-penyakit-rajal.sql', lap_label:'RL 4 Sepuluh Besar Penyakit Rawat Jalan'}
        ],
        onSelect:function(record) {
            dksChange(record);
        }
    });
    $('#dks-btn-execute').linkbutton({
        text:'Eksekusi',
        iconCls:'fa fa-play-circle',
        height:24,
        onClick:function() {dksExecute();}
    });
    $('#dks-btn-exportxls').linkbutton({
        text:'Ekspor XLS',
        height:24,
        iconCls: 'fa fa-file-excel',
        onClick:function() {dksExport('toExcel');}
    });
    $('#dks-btn-exportcsv').linkbutton({
        text:'Ekspor CSV',
        height:24,
        iconCls: 'fa fa-file-csv',
        onClick:function() {dksExport('toCsv');}
    });
    $('#dks-btn-print').linkbutton({
        text:'Print',
        height:24,
        iconCls: 'fa fa-print',
        onClick:function() {dksPrint();}
    });
    $('#dks-grid').datagrid({
        border:false,
        toolbar:'#dks-grid-tb',
        singleSelect:true,
        fit:true
    });

    function dksChange(record) {
        $('#dks-panel').panel('showMask');
        $.ajax({
            type:'POST',
            data:{
                file:record.lap_id,
                db:getDB()
            },
            url:getRestAPI('laporan/dinkesgetscript'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                $('#dks-form-sql').textbox('setValue', obj.script);
                $('#dks-panel').panel('hideMask');
            }
        });
    }

    function dksExecute() {
        var sql = $('#dks-form-sql').textbox('getValue');
        $('#dks-panel').panel('showMask');
        $.ajax({
            type:'POST',
            data:{
                sql:sql,
                db:getDB()
            },
            url:getRestAPI('laporan/dinkes'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    $('#dks-grid').datagrid({
                        columns:obj.cols,
                        data:obj.rows
                    });
                    var hdr = $('#dks-grid').datagrid('getPanel').
                        find('tr.datagrid-header-row');
                    hdr.css('background-color','#32cd32');
                    $.each(obj.cols[0], function(index, col) {
                        $('#dks-grid').datagrid('autoSizeColumn', col.field);
                    });
                    $('#dks-panel').panel('hideMask');
                }
                else {
                    $('#dks-grid').datagrid({
                        columns:[[]],
                        data:[]
                    });
                    $('#dks-panel').panel('hideMask');
                    $.messager.alert(globalConfig.app_nama, obj.errmsg);
                }
            }
        });
    }

    function dksExport(type) {
        var namaFile = $('#dks-form-laporan').combobox('getText')+
            (type=='toExcel'?'.xls':'.csv');
        $('#dks-grid').datagrid(type, namaFile);
    }

    function dksPrint() {
        $('#dks-grid').datagrid('print','DataGrid');
    }
</script>