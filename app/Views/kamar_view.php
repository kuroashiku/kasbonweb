<div id="kmr-grid"></div>
<div id="kmr-grid-tb" style="padding:5px">
    <div id="kmr-btn-add"></div>
    <div id="kmr-btn-save"></div>
    <div id="kmr-btn-cancel"></div>
    <div id="kmr-btn-del"></div>
</div>
<script type="text/javascript">
    // Minus satu artinya mode lihat
    // 0 berarti mode tambah
    // selain itu berarti sedang ada record yang diedit
    var kmrEditedId = -1;
    var kmrClickedIdx = -1;

    $('#kmr-btn-add').linkbutton({
        iconCls:'fa fa-plus-circle fa-lg',
        text:'Tambah',
        height:24,
        onClick:function() {kmrAdd();}
    });
    $('#kmr-btn-save').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Simpan',
        height:24,
        disabled:true,
        onClick:function() {kmrSave();}
    });
    $('#kmr-btn-cancel').linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Batal',
        height:24,
        disabled:true,
        onClick:function() {kmrCancel();}
    });
    $('#kmr-btn-del').linkbutton({
        height:24,
        text:'Hapus',
        onClick:function() {kmrDelete();}
    });
    $('#kmr-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        editorHeight:22,
        toolbar:'#kmr-grid-tb',
        fit:true,
        columns:[[{
            field:'kmr_id',
            title:'ID',
            resizable:false,
            width:100,
            hidden:true
        },{
            field:'kmr_nomorbed',
            title:'Nomor Bed',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,10]',
                    onChange:function() {kmrTextboxOnChange(this)}
                }
            },
            resizeble:false,
            width:80
        },{
            field:'kmr_ruang',
            title:'Ruang',
            editor:{
                type:'combobox',
                options:{
                    required:true,
                    valueField:'kmr_ruang',
                    textField:'kmr_ruang',
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    mode:'remote',
                    method:'post',
                    url:getRestAPI('kamar/ruang'),
                    onChange:function() {kmrTextboxOnChange(this)},
                    queryParams:{
                        db:getDB(),
                        lok_id:globalConfig.lok_id
                    }
                }
            },
            resizable:false,
            width:160
        },{
            field:'kmr_kelas_ruang',
            title:'Kelas',
            editor:{
                type:'numberbox',
                options:{
                    required:false
                }
            },
            resizable:false,
            width:50
        },{
            field:'kmr_tarif_ruang',
            title:'Tarif',
            editor:{
                type:'numberbox',
                options:{
                    required:false
                }
            },
            resizable:false,
            width:100
        },{
            field:'kmr_bangsal',
            title:'Bangsal',
            editor:{
                type:'combobox',
                options:{
                    valueField:'kmr_bangsal',
                    textField:'kmr_bangsal',
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    mode:'remote',
                    method:'post',
                    url:getRestAPI('kamar/bangsal'),
                    onChange:function() {kmrTextboxOnChange(this)},
                    queryParams:{
                        db:getDB(),
                        lok_id:globalConfig.lok_id
                    }
                }
            },
            resizable:false,
            width:160
        }]],
        queryParams:{
            lok_id:globalConfig.lok_id,
            db:getDB()
        },
        url:getRestAPI('kamar/read'),
        onClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (kmrClickedIdx == -1) kmrClickedIdx = index;
            else if (kmrClickedIdx != index) {
                if (kmrEditedId != -1) kmrCancel();
                kmrClickedIdx = index;
            }
            else {
                kmrClickedIdx = index;
                kmrEdit(field);
            }
        }
    });

    if(globalConfig.login_data.lang == 1){
        var grid = $('#kmr-grid');
        var columns = grid.datagrid('options').columns;
        columns[0][0].title = 'Nomor Bed';
        columns[0][1].title = 'Room';
        columns[0][2].title = 'Class';
        columns[0][3].title = 'Fare';
        columns[0][4].title = 'Ward';
        grid.datagrid({columns:columns});
    }

    function kmrAdd() {
        kmrEditedId = 0; // untuk data baru sdm_id diset 0 nanti di model digenerate ID baru
        kmrClickedIdx = 0; // tambah data baru selalu di index 0, paling atas
        $('#kmr-grid').datagrid('insertRow', {
	        index:kmrClickedIdx,
            row: {
                kmr_id:kmrEditedId
            }
        });
        $('#kmr-grid').datagrid('selectRow', 0).datagrid('beginEdit', 0);
        var ed = $('#kmr-grid').datagrid('getEditor', {index:kmrClickedIdx, field:'kmr_nomorbed'});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        kmrSetEnableDisable();
    }

    function kmrEdit(fieldName) {
        $('#kmr-grid').datagrid('beginEdit', kmrClickedIdx);
        var ed = $('#kmr-grid').datagrid('getEditor', {index:kmrClickedIdx, field:fieldName});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        var row = $('#kmr-grid').datagrid('getSelected');
        if (row) {
            kmrEditedId = row.kmr_id;
            kmrSetEnableDisable();
        }
    }

    function kmrSave() {
        if (isDemo()) return;
        if ($('#kmr-grid').datagrid('validateRow', kmrClickedIdx)) {
            $('#kmr-grid').datagrid('endEdit', kmrClickedIdx);
            var row = $('#kmr-grid').datagrid('getSelected');
            row.db = getDB();
            row.kmr_lok_id = globalConfig.lok_id;
            $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('kamar/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    $('#kmr-grid').datagrid('updateRow', {
                        index:kmrClickedIdx,
                        row:obj.data
                    });
                    $('#man-grid').datagrid('selectRow', kmrClickedIdx);
                    kmrEditedId = -1;
                    kmrSetEnableDisable();
                    if (obj.status == 'success')
                        $('#kmr-grid').datagrid('acceptChanges');
                    else
                        $.messager.alert(globalConfig.app_nama, obj.msg);
                }
            });
        }
    }

    function kmrCancel() {
        $('#kmr-grid').datagrid('rejectChanges');
        kmrEditedId = -1;
        kmrClickedIdx = -1;
        kmrSetEnableDisable();
    }

    function kmrDelete() {
        if (isDemo()) return;
        var row = $('#kmr-grid').datagrid('getSelected');
        if (row) $.messager.confirm(globalConfig.app_nama,
            'Apakah betul data nomor bed: '+row.kmr_nomorbed+', ruang: '+
            row.kmr_ruang+', bangsal: '+row.kmr_bangsal+' akan dihapus?', function(r) {
            if (r) $.ajax({
                type:'POST',
                data:{
                    kmr_id:row.kmr_id,
                    db:getDB()
                },
                url:getRestAPI('kamar/delete'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#kmr-grid').datagrid('deleteRow', kmrClickedIdx);
                        kmrClickedIdx = -1;
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.errmsg);
                }
            });
        });
    }

    function kmrSetEnableDisable() {
        if (kmrEditedId >= 0) { // mode tambah atau edit
            $('#kmr-btn-add').linkbutton('disable');
            $('#kmr-btn-save').linkbutton('enable');
            $('#kmr-btn-cancel').linkbutton('enable');
            $('#kmr-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#kmr-btn-add').linkbutton('enable');
            $('#kmr-btn-save').linkbutton('disable');
            $('#kmr-btn-cancel').linkbutton('disable');
            $('#kmr-btn-del').linkbutton('enable');
        }
    }

    function kmrTextboxOnChange(obj) {
        var str = $(obj).textbox('getValue');
        $(obj).textbox('setValue', str.toUpperCase());
    }
</script>