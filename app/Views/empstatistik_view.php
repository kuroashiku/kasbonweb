<div id="sta-grid"></div>
<div id="sta-grid-tb" style="padding:5px">
    Tahun <div id="sta-search-tahun"></div>
    <div id="sta-search-ok"></div>
    <div id="sta-btn-save"></div>
    <div id="sta-btn-cancel"></div>
</div>
<script type="text/javascript">
    $.extend($.fn.validatebox.defaults.rules, {
        onYear: {
            validator: function(value, param) {
                return (value >= 2018 && value <= 2038);
            },
            message:'Hanya bisa tahun 2018 sampai 2038'
        }
    });

    // Minus satu artinya mode lihat
    // 0 berarti mode tambah
    // selain itu berarti sedang ada record yang diedit
    var staEditedId = -1;
    var staClickedIdx = -1;

    $('#sta-search-tahun').textbox({
        width:50,
        height:24,
        value:staGetCurYear(),
        validType:'onYear[]'
    });
    $('#sta-search-ok').linkbutton({
        text:'Cari',
        iconCls:'fa fa-search fa-lg',
        height:24,
        onClick:function() {
            if ($('#sta-search-tahun').textbox('isValid'))
                staLoad();
        }
    });
    $('#sta-btn-save').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Simpan',
        height:24,
        disabled:true,
        onClick:function() {staSave();}
    });
    $('#sta-btn-cancel').linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Batal',
        height:24,
        disabled:true,
        onClick:function() {staCancel();}
    });
    $('#sta-grid').datagrid({
        border:false,
        singleSelect:true,
        rownumbers:true,
        editorHeight:22,
        toolbar:'#sta-grid-tb',
        fit:true,
        rowStyler:function(index,row) {
            var str = '';
            if (row.sta_kua_kode%100 == 0)
                str = 'background:lightgrey;font-weight:bold;';
            return str;
        },
        columns:[[{
            field:'sta_tahun',
            title:'Tahun',
            resizable:false,
            width:45
        },{
            field:'sta_kua_kode',
            title:'Kode',
            resizable:false,
            width:50
        },{
            field:'kua_nama',
            title:'Kualifikasi Pendidikan',
            resizable:false,
            width:245
        },{
            field:'sta_keadaan_lakilaki',
            title:'Jumlah <span class="fa fa-male"></span>',
            editor:{
                type:'numberbox',
                options:{
                    required:false
                }
            },
            resizeble:false,
            width:63
        },{
            field:'sta_keadaan_perempuan',
            title:'Jumlah <span class="fa fa-female"></span>',
            editor:{
                type:'numberbox',
                options:{
                    required:false
                }
            },
            resizeble:false,
            width:63
        },{
            field:'sta_kebutuhan_lakilaki',
            title:'Kebutuhan <span class="fa fa-male"></span>',
            editor:{
                type:'numberbox',
                options:{
                    required:false
                }
            },
            resizeble:false,
            width:85
        },{
            field:'sta_kebutuhan_perempuan',
            title:'Kebutuhan <span class="fa fa-female"></span>',
            editor:{
                type:'numberbox',
                options:{
                    required:false
                }
            },
            resizeble:false,
            width:85
        }]],
        queryParams:{
            sta_tahun:$('#sta-search-tahun').textbox('getValue'),
            sta_com_id:globalConfig.com_id,
            db:getDB()
        },
        url:getRestAPI('empstatistik/read'),
        onClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (staClickedIdx == -1) staClickedIdx = index;
            else if (staClickedIdx != index) {
                if (staEditedId != -1) staCancel();
                staClickedIdx = index;
            }
            else if (row.sta_kua_kode%100 > 0) {
                staClickedIdx = index;
                staEdit(field);
            }
        }
    });
    if(globalConfig.login_data.lang == 1){
        var grid = $('#sta-grid');
        var columns = grid.datagrid('options').columns;
        columns[0][0].title = 'Year';
        columns[0][1].title = 'Code';
        columns[0][2].title = 'Educational Qualification';
        columns[0][3].title = 'Amount <span class="fa fa-male"></span>';
        columns[0][4].title = 'Amount <span class="fa fa-female"></span>';
        columns[0][5].title = 'Need <span class="fa fa-male"></span>';
        columns[0][6].title = 'Need <span class="fa fa-female"></span>';
        grid.datagrid({columns:columns});
    }

    function staLoad() {
        var tahun = $('#sta-search-tahun').textbox('getValue');
        $.ajax({
            type:'POST',
            data:{
                sta_tahun:tahun,
                sta_com_id:globalConfig.com_id,
                db:getDB()
            },
            url:getRestAPI('empstatistik/isexist'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'none') $.messager.confirm(globalConfig.app_nama,
                    'Data keadaan ketenagakerjaan tahun '+tahun+' belum ada. '+
                    'Apakah dibuat sekarang?', function(r) {
                    if (r && !isDemo()) $.ajax({
                        type:'POST',
                        data:{
                            sta_tahun:tahun,
                            sta_com_id:globalConfig.com_id,
                            sta_username:globalConfig.login_data.username,
                            db:getDB()
                        },
                        url:getRestAPI('empstatistik/create'),
                        success:function(retval) {
                            var obj = JSON.parse(retval);
                            if (obj.status == 'success') $('#sta-grid').datagrid('reload', {
                                sta_tahun:$('#sta-search-tahun').textbox('getValue'),
                                sta_com_id:globalConfig.com_id,
                                db:globalConfig.login_data.db
                            });
                            else $.messager.alert(globalConfig.app_nama,
                                "Gagal membuat data ketenagakerjaan tahun "+tahun);
                        }
                    });
                });
                else $('#sta-grid').datagrid('reload', {
                    sta_tahun:$('#sta-search-tahun').textbox('getValue'),
                    sta_com_id:globalConfig.com_id,
                    db:globalConfig.login_data.db
                });
            }
        });
    }

    function staEdit(fieldName) {
        $('#sta-grid').datagrid('beginEdit', staClickedIdx);
        var ed = $('#sta-grid').datagrid('getEditor', {index:staClickedIdx, field:fieldName});
        if (ed) {
            $(ed.target).textbox('textbox').focus();
            $(ed.target).textbox('textbox').select();
        }
        var row = $('#sta-grid').datagrid('getSelected');
        staEditedId = row.sta_kua_kode;
        staSetEnableDisable();
    }

    function staSave() {
        if (isDemo()) return;
        if ($('#sta-grid').datagrid('validateRow', staClickedIdx)) {
            $('#sta-grid').datagrid('endEdit', staClickedIdx);
            var row = $('#sta-grid').datagrid('getSelected');
            row.sta_tahun = $('#sta-search-tahun').textbox('getValue');
            row.sta_com_id = globalConfig.com_id;
            row.db = getDB();
            $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('empstatistik/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    $('#sta-grid').datagrid('updateRow', {
                        index:staClickedIdx,
                        row:obj.data
                    });
                    $('#sta-grid').datagrid('selectRow', staClickedIdx);
                    staEditedId = -1;
                    staSetEnableDisable();
                    if (obj.status == 'success') {
                        $('#sta-grid').datagrid('acceptChanges');
                        var data = $('#sta-grid').datagrid('getData');
                        staSumGroup(data);
                        $('#sta-grid').datagrid('loadData', data);
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.msg);
                }
            });
        }
    }

    function staCancel() {
        $('#sta-grid').datagrid('rejectChanges');
        staEditedId = -1;
        staClickedIdx = -1;
        staSetEnableDisable();
    }

    function staSumGroup(data) {
        var objBigGroup = undefined;
        var objSubGroup = undefined;
        $.each(data.rows, function(index, obj) {
            if (obj.sta_kua_kode % 10000 == 0)
                objBigGroup = obj;
            else if (obj.sta_kua_kode % 100 == 0)
                objSubGroup = obj;
            else if (obj.sta_kua_kode % 100 != 0) {
                if (objBigGroup != undefined) {
                    objBigGroup.sta_keadaan_lakilaki = 
                        parseInt(objBigGroup.sta_keadaan_lakilaki)+
                        parseInt(obj.sta_keadaan_lakilaki);
                    objBigGroup.sta_keadaan_perempuan =
                        parseInt(objBigGroup.sta_keadaan_perempuan)+
                        parseInt(obj.sta_keadaan_perempuan);
                    objBigGroup.sta_kebutuhan_lakilaki =
                        parseInt(objBigGroup.sta_kebutuhan_lakilaki)+
                        parseInt(obj.sta_kebutuhan_lakilaki);
                    objBigGroup.sta_kebutuhan_perempuan =
                        parseInt(objBigGroup.sta_kebutuhan_perempuan)+
                        parseInt(obj.sta_kebutuhan_perempuan);
                }
                if (objSubGroup != undefined) {
                    objSubGroup.sta_keadaan_lakilaki = 
                        parseInt(objSubGroup.sta_keadaan_lakilaki)+
                        parseInt(obj.sta_keadaan_lakilaki);
                    objSubGroup.sta_keadaan_perempuan =
                        parseInt(objSubGroup.sta_keadaan_perempuan)+
                        parseInt(obj.sta_keadaan_perempuan);
                    objSubGroup.sta_kebutuhan_lakilaki =
                        parseInt(objSubGroup.sta_kebutuhan_lakilaki)+
                        parseInt(obj.sta_kebutuhan_lakilaki);
                    objSubGroup.sta_kebutuhan_perempuan =
                        parseInt(objSubGroup.sta_kebutuhan_perempuan)+
                        parseInt(obj.sta_kebutuhan_perempuan);
                }
            }
        });
    }

    function staSetEnableDisable() {
        if (staEditedId >= 0) { // mode tambah atau edit
            $('#sta-btn-save').linkbutton('enable');
            $('#sta-btn-cancel').linkbutton('enable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#sta-btn-save').linkbutton('disable');
            $('#sta-btn-cancel').linkbutton('disable');
        }
    }

    function staGetCurYear() {
        var today = new Date;
        return 1900+today.getYear();
    }
</script>