<div id="lay-grid"></div>
<div id="lay-grid-tb" style="padding:5px">
    <div id="lay-btn-add"></div>
    <div id="lay-btn-save"></div>
    <div id="lay-btn-cancel"></div>
    <div id="lay-btn-del"></div>
</div>
<script type="text/javascript">
    $.extend($.fn.validatebox.defaults.rules, {
        onList: {
            validator: function(value, param) {
                return (param[0].indexOf(value) != -1);
            },
            message:'Gender hanya boleh L atau P'
        }
    });

    // Minus satu artinya mode lihat
    // 0 berarti mode tambah
    // selain itu berarti sedang ada record yang diedit
    var sdmEditedId = -1;
    var sdmClickedIdx = -1;

    $('#lay-btn-add').linkbutton({
        iconCls:'fa fa-plus-circle fa-lg',
        text:'Tambah',
        height:24,
        onClick:function() {sdmAdd();}
    });
    $('#lay-btn-save').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Simpan',
        height:24,
        disabled:true,
        onClick:function() {sdmSave();}
    });
    $('#lay-btn-cancel').linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Batal',
        height:24,
        disabled:true,
        onClick:function() {sdmCancel();}
    });
    $('#lay-btn-del').linkbutton({
        height:24,
        text:'Hapus',
        onClick:function() {sdmDelete();}
    });
    $('#lay-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        editorHeight:22,
        toolbar:'#lay-grid-tb',
        fit:true,
        columns:[[{
            field:'sdm_id',
            title:'ID',
            resizable:false,
            width:100,
            hidden:true
        },{
            field:'sdm_nip',
            title:'NIP',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,20]'
                }
            },
            resizeble:false,
            width:120
        },{
            field:'sdm_nama',
            title:'Nama',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,30]',
                    onChange:function() {sdmTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:170
        },{
            field:'sdm_kelamin',
            title:'Gender',
            editor:{
                type:'textbox',
                options:{
                    validType:'onList["LP"]'
                }
            },
            resizable:false,
            width:60
        },{
            field:'sdm_alamat',
            title:'Alamat',
            editor:{
                type:'textbox',
                options:{
                    validType:'length[0,60]',
                    onChange:function() {sdmTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:200
        },{
            field:'sdm_are_id',
            formatter:function(value,row) {return row.are_nama},
            title:'Kota',
            editor:{
                type:'combobox',
                options:{
                    prompt:'Ketik minimal 3 huruf untuk mencari',
                    valueField:'are_id',
                    textField:'are_nama',
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    mode:'remote',
                    method:'POST',
                    url:getRestAPI('area/read'),
                    queryParams:{
                        db:getDB()
                    }
                }
            },
            resizable:false,
            width:120
        },{
            field:'sdm_telpon',
            title:'Telpon',
            editor:{
                type:'textbox',
                options:{
                    validType:'length[0,45]'
                }
            },
            resizable:false,
            width:120
        },{
            field:'sdm_jab_id',
            formatter:function(value,row) {return row.jab_nama},
            title:'Jabatan',
            editor: {
                type:'combobox',
                options:{
                    valueField:'jab_id',
                    textField:'jab_nama',
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    mode:'remote',
                    method:'post',
                    url:getRestAPI('jabatan/read'),
                    queryParams:{
                        com_id:globalConfig.com_id,
                        db:getDB()
                    }
                }
            },
            resizable:false,
            width:150
        }]],
        queryParams:{
            com_id:globalConfig.com_id,
            db:getDB()
        },
        url:getRestAPI('sdm/read'),
        onClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (sdmClickedIdx == -1) sdmClickedIdx = index;
            else if (sdmClickedIdx != index) {
                if (sdmEditedId != -1) sdmCancel();
                sdmClickedIdx = index;
            }
            else {
                sdmClickedIdx = index;
                sdmEdit(field);
            }
        }
    });

    function sdmAdd() {
        sdmEditedId = 0; // untuk data baru sdm_id diset 0 nanti di model digenerate ID baru
        sdmClickedIdx = 0; // tambah data baru selalu di index 0, paling atas
        $('#lay-grid').datagrid('insertRow', {
	        index:sdmClickedIdx,
            row: {
                sdm_id:sdmEditedId
            }
        });
        $('#lay-grid').datagrid('selectRow', 0).datagrid('beginEdit', 0);
        var ed = $('#lay-grid').datagrid('getEditor', {index:sdmClickedIdx, field:'sdm_nip'});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        sdmSetEnableDisable();
    }

    function sdmEdit(fieldName) {
        $('#lay-grid').datagrid('beginEdit', sdmClickedIdx);
        var ed = $('#lay-grid').datagrid('getEditor', {index:sdmClickedIdx, field:fieldName});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        var row = $('#lay-grid').datagrid('getSelected');
        sdmEditedId = row.sdm_id;
        sdmSetEnableDisable();
    }

    function sdmSave() {
        if ($('#lay-grid').datagrid('validateRow', sdmClickedIdx)) {
            $('#lay-grid').datagrid('endEdit', sdmClickedIdx);
            var row = $('#lay-grid').datagrid('getSelected');
            row.db = getDB();
            row.sdm_com_id = globalConfig.com_id;
            $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('sdm/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    $('#lay-grid').datagrid('updateRow', {
                        index:sdmClickedIdx,
                        row:obj.data
                    });
                    $('#man-grid').datagrid('selectRow', sdmClickedIdx);
                    sdmEditedId = -1;
                    sdmSetEnableDisable();
                    if (obj.status == 'success')
                        $('#lay-grid').datagrid('acceptChanges');
                    else
                        $.messager.alert(globalConfig.app_nama, obj.msg);
                }
            });
        }
    }

    function sdmCancel() {
        $('#lay-grid').datagrid('rejectChanges');
        sdmEditedId = -1;
        sdmClickedIdx = -1;
        sdmSetEnableDisable();
    }

    function sdmDelete() {
        var row = $('#lay-grid').datagrid('getSelected');
        if (row) $.messager.confirm(globalConfig.app_nama,
            'Apakah betul data pegawai bernama '+row.sdm_nama+' akan dihapus?', function(r) {
            if (r) $.ajax({
                type:'POST',
                data:{
                    sdm_id:row.sdm_id,
                    db:getDB()
                },
                url:getRestAPI('sdm/delete'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#lay-grid').datagrid('deleteRow', sdmClickedIdx);
                        sdmClickedIdx = -1;
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.errmsg);
                }
            });
        });
    }

    function sdmSetEnableDisable() {
        if (sdmEditedId >= 0) { // mode tambah atau edit
            $('#lay-btn-add').linkbutton('disable');
            $('#lay-btn-save').linkbutton('enable');
            $('#lay-btn-cancel').linkbutton('enable');
            $('#lay-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#lay-btn-add').linkbutton('enable');
            $('#lay-btn-save').linkbutton('disable');
            $('#lay-btn-cancel').linkbutton('disable');
            $('#lay-btn-del').linkbutton('enable');
        }
    }

    function sdmTextboxOnChange(obj) {
        var str = $(obj).textbox('getValue');
        str = setCapitalSentenceEveryWord(str);
        $(obj).textbox('setValue', str);
    }
</script>