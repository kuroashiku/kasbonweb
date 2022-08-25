<div id="kit-grid"></div>
<div id="kit-grid-tb" style="padding:5px">
    <div id="kit-btn-add"></div>
    <div id="kit-btn-save"></div>
    <div id="kit-btn-cancel"></div>
    <div id="kit-btn-del"></div>
    <div style="float:right">
        <div id="kit-search"></div>
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

    // Minus satu artinya mode lihat
    // 0 berarti mode tambah
    // selain itu berarti sedang ada record yang diedit
    var kitEditedId = -1;
    var kitClickedIdx = -1;

    $('#kit-btn-add').linkbutton({
        iconCls:'fa fa-plus-circle fa-lg',
        text:'Tambah',
        height:24,
        onClick:function() {kitAdd();}
    });
    $('#kit-btn-save').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Simpan',
        height:24,
        disabled:true,
        onClick:function() {kitSave();}
    });
    $('#kit-btn-cancel').linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Batal',
        height:24,
        disabled:true,
        onClick:function() {kitCancel();}
    });
    $('#kit-btn-del').linkbutton({
        height:24,
        text:'Hapus',
        onClick:function() {kitDelete();}
    });
    $('#kit-search').searchbox({
        prompt:'Ketik nama yang dicari',
        height:24,
        width:200,
        searcher:function(value) {
            $('#kit-grid').datagrid('reload', {
                key_val:value,
                com_id:globalConfig.com_id,
                db:getDB()
            });
        }
    });
    $('#kit-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        editorHeight:22,
        toolbar:'#kit-grid-tb',
        rownumbers:true,
        fit:true,
        columns:[[{
            field:'kit_nama',
            title:'Nama',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,50]',
                    onChange:function() {kitTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:250
        },{
            field:'ict_id',
            title:'ID',
            resizable:false,
            width:40
        },{
            field:'kit_id',
            title:'ID',
            resizable:false,
            width:35,
            hidden:true
        },{
            field:'kit_kode',
            title:'Kode Penyakit',
            editor:{
                type:'textbox',
                options:{
                    required:false,
                    validType:'length[0,45]',
                    onChange:function() {kitTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:90
        },{
            field:'ict_nama',
            title:'Nama Grup',
            editor:{
                type:'textbox',
                options:{
                    required:false,
                    validType:'length[0,45]',
                    onChange:function() {kitTextboxOnChange(this)},
                    valueField:'ict_id',
                    textField:'ict_nama',
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    mode:'remote',
                    method:'post',
                    url:getRestAPI('penyakit/read'),
                    queryParams:{
                        com_id:globalConfig.com_id,
                        db:getDB()
                    }
                }
            },
            resizable:false,
            width:90
        },{
            field:'ict_status',
            title:'Aktif',
            align:'center',
            formatter:function(value, row) {
                var checkbox = '';
                if (row.ict_status == 'AKTIF')
                    checkbox = '<span class="fa fa-check" style="color:green"></span>';
                else
                    checkbox = '<span class="fa fa-times-circle" style="color:red"></span>';
                return checkbox;
            },
            resizable:false,
            width:60
        }]],
        queryParams:{
            com_id:globalConfig.com_id,
            db:getDB()
        },
        url:getRestAPI('penyakit/read'),
        onClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (kitClickedIdx == -1) kitClickedIdx = index;
            else if (kitClickedIdx != index) {
                if (kitEditedId != -1) kitCancel();
                kitClickedIdx = index;
            }
            else {
                kitClickedIdx = index;
                if (field != 'ict_status') kitEdit(field);
            }
        },
        onDblClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (field == 'ict_status' && row && kitEditedId == -1) {
                if (isDemo()) return;
                $.ajax({
                    type:'POST',
                    data:{
                        ict_id:row.ict_id,
                        db:getDB()
                    },
                    url:getRestAPI('penyakit/changestatus'),
                    success:function(retval) {
                        var obj = JSON.parse(retval);
                        if (obj.status == 'success') {
                            row.ict_status = obj.new_status;
                            $('#kit-grid').datagrid('updateRow', {
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
    
    function kitAdd() {
        kitEditedId = 0; // untuk data baru ict_id diset 0 nanti di model digenerate ID baru
        kitClickedIdx = 0; // tambah data baru selalu di index 0, paling atas
        $('#kit-grid').datagrid('insertRow', {
	        index:kitClickedIdx,
            row: {
                ict_id:kitEditedId
            }
        });
        $('#kit-grid').datagrid('selectRow', 0).datagrid('beginEdit', 0);
        var ed = $('#kit-grid').datagrid('getEditor', {index:kitClickedIdx, field:'kit_nama'});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        kitSetEnableDisable();
    }

    function kitEdit(fieldName) {
        $('#kit-grid').datagrid('beginEdit', kitClickedIdx);
        var ed = $('#kit-grid').datagrid('getEditor', {index:kitClickedIdx, field:fieldName});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        var row = $('#kit-grid').datagrid('getSelected');
        if (row) {
            kitEditedId = row.ict_id;
            kitSetEnableDisable();
        }
    }

    function kitSave() {
        if (isDemo()) return;
        if ($('#kit-grid').datagrid('validateRow', kitClickedIdx)) {
            $('#kit-grid').datagrid('endEdit', kitClickedIdx);
            var row = $('#kit-grid').datagrid('getSelected');
            row.db = getDB();
            row.ict_com_id = globalConfig.com_id;
            $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('penyakit/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    console.log(obj)
                    $('#kit-grid').datagrid('updateRow', {
                        index:kitClickedIdx,
                        row:obj.row
                    });
                    //$('#man-grid').datagrid('selectRow', kitClickedIdx);
                    kitEditedId = -1;
                    kitSetEnableDisable();
                    if (obj.status == 'success')
                        $('#kit-grid').datagrid('acceptChanges');
                    else
                        $.messager.alert(globalConfig.app_nama, obj.msg);
                }
            });
        }
    }

    function kitCancel() {
        $('#kit-grid').datagrid('rejectChanges');
        kitEditedId = -1;
        kitClickedIdx = -1;
        kitSetEnableDisable();
    }

    function kitDelete() {
        var row = $('#kit-grid').datagrid('getSelected');
        if (row) $.messager.confirm(globalConfig.app_nama,
            'Apakah betul data pegawai bernama '+row.kit_nama+' akan dihapus?', function(r) {
            if (r) $.ajax({
                type:'POST',
                data:{
                    kit_id:row.kit_id,
                    ict_id:row.ict_id,
                    db:getDB()
                },
                url:getRestAPI('penyakit/delete'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#kit-grid').datagrid('deleteRow', kitClickedIdx);
                        kitClickedIdx = -1;
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.errmsg);
                }
            });
        });
    }

    function kitSetEnableDisable() {
        if (kitEditedId >= 0) { // mode tambah atau edit
            $('#kit-btn-add').linkbutton('disable');
            $('#kit-btn-save').linkbutton('enable');
            $('#kit-btn-cancel').linkbutton('enable');
            $('#kit-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#kit-btn-add').linkbutton('enable');
            $('#kit-btn-save').linkbutton('disable');
            $('#kit-btn-cancel').linkbutton('disable');
            $('#kit-btn-del').linkbutton('enable');
        }
    }

    function kitTextboxOnChange(obj) {
        var str = $(obj).textbox('getValue');
        str = setCapitalSentenceEveryWord(str);
        $(obj).textbox('setValue', str);
    }
</script>