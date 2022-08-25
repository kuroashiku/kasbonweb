<div id="mjb-grid"></div>
<div id="mjb-grid-tb" style="padding:5px">
    <div id="mjb-btn-add"></div>
    <div id="mjb-btn-save"></div>
    <div id="mjb-btn-cancel"></div>
    <div id="mjb-btn-del"></div>
    <div style="float:right">
        <div id="mjb-search"></div>
    </div>
</div>
<script type="text/javascript">
    $.extend($.fn.validatebox.defaults.rules, {
        onList: {
            validator: function(value, param) {
                return (param[0].indexOf(value) != -1);
            },
            message:'{1}'
        }
    });

    // Minus satu ardtinya mode lihat
    // 0 berarti mode tambah
    // selain itu berarti sedang ada record yang diedit
    var mjbEditedId = -1;
    var mjbClickedIdx = -1;

    $('#mjb-btn-add').linkbutton({
        iconCls:'fa fa-plus-circle fa-lg',
        text:'Tambah',
        height:24,
        onClick:function() {mjbAdd();}
    });
    $('#mjb-btn-save').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Simpan',
        height:24,
        disabled:true,
        onClick:function() {mjbSave();}
    });
    $('#mjb-btn-cancel').linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Batal',
        height:24,
        disabled:true,
        onClick:function() {mjbCancel();}
    });
    $('#mjb-btn-del').linkbutton({
        height:24,
        text:'Hapus',
        onClick:function() {mjbDelete();}
    });
    $('#mjb-search').searchbox({
        prompt:'Ketik nama yang dicari',
        height:24,
        width:200,
        searcher:function(value) {
            $('#mjb-grid').datagrid('reload', {
                key_val:value,
                com_id:globalConfig.com_id,
                lok_id:globalConfig.lok_id,
                db:globalConfig.login_data.db
            });
        }
    });
    $('#mjb-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        editorHeight:22,
        toolbar:'#mjb-grid-tb',
        rownumbers:true,
        fit:true,
        columns:[[{
            field:'jab_nama',
            title:'Nama',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,50]',
                    onChange:function() {mjbTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:300
        },{
            field:'jab_id',
            title:'ID',
            resizable:false,
            width:40
        },{
            field:'jab_tipe',
            title:'Tipe',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,50]',
                    onChange:function() {mjbTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:60
        },{
            field:'jab_harga',
            title:'Harga',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,50]',
                    onChange:function() {mjbTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:60
        },{
            field:'jab_status',
            title:'Status',
            align:'center',
            formatter:function(value, row) {
                var checkbox = '';
                if (row.jab_status == 'Aktif')
                    checkbox = '<span class="fa fa-check" style="color:green"></span>';
                else
                    checkbox = '<span class="fa fa-times-circle" style="color:red"></span>';
                return checkbox;
            },
            resizable:false,
            width:60
        }]],
        queryParams:{com_id:globalConfig.com_id,db:getDB(),lok_id:globalConfig.lok_id},
        url:getRestAPI('jabatan/read'),
        onClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (mjbClickedIdx == -1) mjbClickedIdx = index;
            else if (mjbClickedIdx != index) {
                if (mjbEditedId != -1) mjbCancel();
                mjbClickedIdx = index;
            }
            else {
                mjbClickedIdx = index;
                if (field != 'jab_status') mjbEdit(field);
            }
        },
        onDblClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (field == 'jab_status' && row && mjbEditedId == -1) {
                $.ajax({
                    type:'POST',
                    data:{jab_id:row.jab_id},
                    url:getRestAPI('jabatan/changestatus'),
                    success:function(retval) {
                        var obj = JSON.parse(retval);
                        if (obj.status == 'success') {
                            row.jab_status = obj.new_status;
                            $('#mjb-grid').datagrid('updateRow', {
                                index:index,
                                row:row
                            }).datagrid('refreshRow', index);
                        }
                        else
                            $.messager.alert(globalConfig.app_nama, obj.msg);
                    }
                });
            }
            
        }
    });

    if(globalConfig.login_data.lang == 1){
        var grid = $('#mjb-grid');
        var columns = grid.datagrid('options').columns;
        columns[0][0].title = 'Name';
        columns[0][1].title = 'ID';
        columns[0][2].title = 'Type';
        columns[0][3].title = 'Price';
        columns[0][4].title = 'Status';
        grid.datagrid({columns:columns});
    }
    
    function mjbAdd() {
        mjbEditedId = 0; // untuk data baru djab_id diset 0 nanti di model digenerate ID baru
        mjbClickedIdx = 0; // tambah data baru selalu di index 0, paling atas
        
        $('#mjb-grid').datagrid('insertRow', {
            index:mjbClickedIdx,
            row: {
                jab_id:mjbEditedId
            }
        });
        $('#mjb-grid').datagrid('selectRow', 0).datagrid('beginEdit', 0);
        var ed = $('#mjb-grid').datagrid('getEditor', {index:mjbClickedIdx, field:'jab_nama'});
        
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        mjbSetEnableDisable();
    }

    function mjbEdit(fieldName) {
        $('#mjb-grid').datagrid('beginEdit', mjbClickedIdx);
        var ed = $('#mjb-grid').datagrid('getEditor', {index:mjbClickedIdx, field:fieldName});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        
            var row = $('#mjb-grid').datagrid('getSelected');
            if (row) {
                mjbEditedId = row.jab_id;
                mjbSetEnableDisable();
            }
        
    }

    function mjbSave() {
        if ($('#mjb-grid').datagrid('validateRow', mjbClickedIdx)) {
            $('#mjb-grid').datagrid('endEdit', mjbClickedIdx);
            var row = $('#mjb-grid').datagrid('getSelected');
            row.db = getDB();
            row.com_id = globalConfig.com_id;
            $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('jabatan/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    $('#mjb-grid').datagrid('updateRow', {
                        index:mjbClickedIdx,
                        row:obj.data
                    });
                    //$('#man-grid').datagrid('selectRow', mjbClickedIdx);
                    mjbEditedId = -1;
                    mjbSetEnableDisable();
                    if (obj.status == 'success'){
                        $('#mjb-grid').datagrid('acceptChanges');
                        $('#mtd-grid').datagrid('reload');
                        mtdEditedId = -1;
                        mtdClickedIdx = -1;
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.msg);
                }
            });
        }
    }

    function mjbCancel() {
        $('#mjb-grid').datagrid('rejectChanges');
        $('#mtd-grid').datagrid('reload');
        mjbEditedId = -1;
        mjbClickedIdx = -1;
        mjbSetEnableDisable();
    }

    function mjbDelete() {
        var row = $('#mjb-grid').datagrid('getSelected');
        row.db = getDB();
        row.lok_jenis = globalConfig.lok_jenis;
        if (row) $.messager.confirm(globalConfig.app_nama,
            'Apakah betul jabatan '+row.jab_nama+' akan dihapus?', function(r) {
            if (r) $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('jabatan/delete_jab'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#mjb-grid').datagrid('deleteRow', mjbClickedIdx);
                        $('#mtd-grid').datagrid('reload');
                        mjbClickedIdx = -1;
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.errmsg);
                }
            });
        });
    }

    function mjbSetEnableDisable() {
        if (mjbEditedId >= 0) { // mode tambah atau edit
            $('#mjb-btn-add').linkbutton('disable');
            $('#mjb-btn-save').linkbutton('enable');
            $('#mjb-btn-cancel').linkbutton('enable');
            $('#mjb-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#mjb-btn-add').linkbutton('enable');
            $('#mjb-btn-save').linkbutton('disable');
            $('#mjb-btn-cancel').linkbutton('disable');
            $('#mjb-btn-del').linkbutton('enable');
        }
    }

    function mjbTextboxOnChange(obj) {
        var str = $(obj).textbox('getValue');
        str = setCapitalSentenceEveryWord(str);
        $(obj).textbox('setValue', str);
    }
</script>