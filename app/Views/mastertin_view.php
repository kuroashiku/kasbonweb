<div id="mtn-grid"></div>
<div id="mtn-grid-tb" style="padding:5px">
    <div id="mtn-btn-add"></div>
    <div id="mtn-btn-save"></div>
    <div id="mtn-btn-cancel"></div>
    <div id="mtn-btn-del"></div>
    <div style="float:right">
        <div id="mtn-search"></div>
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
    var mtnEditedId = -1;
    var mtnClickedIdx = -1;

    $('#mtn-btn-add').linkbutton({
        iconCls:'fa fa-plus-circle fa-lg',
        text:'Tambah',
        height:24,
        onClick:function() {mtnAdd();}
    });
    $('#mtn-btn-save').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Simpan',
        height:24,
        disabled:true,
        onClick:function() {mtnSave();}
    });
    $('#mtn-btn-cancel').linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Batal',
        height:24,
        disabled:true,
        onClick:function() {mtnCancel();}
    });
    $('#mtn-btn-del').linkbutton({
        height:24,
        text:'Hapus',
        onClick:function() {mtnDelete();}
    });
    $('#mtn-search').searchbox({
        prompt:'Ketik nama yang dicari',
        height:24,
        width:200,
        searcher:function(value) {
            $('#mtn-grid').datagrid('reload', {
                key_val:value,
                com_id:globalConfig.com_id,
                lok_id:globalConfig.lok_id,
                db:globalConfig.login_data.db
            });
        }
    });
    $('#mtn-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        editorHeight:22,
        toolbar:'#mtn-grid-tb',
        rownumbers:true,
        fit:true,
        columns:[[{
            field:'tin_nama',
            title:'Nama',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,50]',
                    onChange:function() {mtnTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:300
        },{
            field:'tin_id',
            title:'ID',
            resizable:false,
            width:40
        },{
            field:'tin_kode',
            title:'Kode',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,50]',
                    onChange:function() {mtnTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:60
        },{
            field:'tin_yan_id',
            title:'Layanan',
            resizable:false,
            width:100,
            formatter:function(value,row) {
                var str = row.yan_nama;
                return str;
            },
            editor:{
                type:'combobox',
                options:{
                    valueField:'yan_id',
                    textField:'yan_nama',
                    queryParams:{
                        db:getDB(),
                        lok_id:globalConfig.lok_id,
                        lok_jenis:globalConfig.lok_jenis
                    },
                    url:getRestAPI('layanan/read'),
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    mode:'remote',
                    method:'post',
                    required:true,
                    // keyHandler:$.extend({}, $.fn.combobox.defaults.keyHandler, {
                    //     down:function(e) {
                    //         $(this).combobox('showPanel');
                    //         $.fn.combobox.defaults.keyHandler.down.call(this,e);
                    //     }
                    // })
                }
            }
        },{
            field:'tin_harga',
            title:'Harga',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,50]',
                    onChange:function() {mtnTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:60
        },{
            field:'tin_status',
            title:'Status',
            align:'center',
            formatter:function(value, row) {
                var checkbox = '';
                if (row.tin_status == 'Aktif')
                    checkbox = '<span class="fa fa-check" style="color:green"></span>';
                else
                    checkbox = '<span class="fa fa-times-circle" style="color:red"></span>';
                return checkbox;
            },
            resizable:false,
            width:60
        }]],
        queryParams:{com_id:globalConfig.com_id,db:getDB(),lok_id:globalConfig.lok_id},
        url:getRestAPI('tindakan/read_master'),
        onClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (mtnClickedIdx == -1) mtnClickedIdx = index;
            else if (mtnClickedIdx != index) {
                if (mtnEditedId != -1) mtnCancel();
                mtnClickedIdx = index;
            }
            else {
                mtnClickedIdx = index;
                if (field != 'tin_status') mtnEdit(field);
            }
        },
        onDblClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (field == 'tin_status' && row && mtnEditedId == -1) {
                $.ajax({
                    type:'POST',
                    data:{tin_id:row.tin_id},
                    url:getRestAPI('tindakan/changestatus'),
                    success:function(retval) {
                        var obj = JSON.parse(retval);
                        if (obj.status == 'success') {
                            row.tin_status = obj.new_status;
                            $('#mtn-grid').datagrid('updateRow', {
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
    
    function mtnAdd() {
        mtnEditedId = 0; // untuk data baru dtin_id diset 0 nanti di model digenerate ID baru
        mtnClickedIdx = 0; // tambah data baru selalu di index 0, paling atas
        $('#mtn-grid').datagrid('insertRow', {
            index:mtnClickedIdx,
            row: {
                tin_id:mtnEditedId
            }
        });
        $('#mtn-grid').datagrid('selectRow', 0).datagrid('beginEdit', 0);
        var ed = $('#mtn-grid').datagrid('getEditor', {index:mtnClickedIdx, field:'tin_nama'});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        mtnSetEnableDisable();
    }

    function mtnEdit(fieldName) {
        $('#mtn-grid').datagrid('beginEdit', mtnClickedIdx);
        var ed = $('#mtn-grid').datagrid('getEditor', {index:mtnClickedIdx, field:fieldName});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        var row = $('#mtn-grid').datagrid('getSelected');
        if (row) {
            mtnEditedId = row.tin_id;
            mtnSetEnableDisable();
        }
        
    }

    function mtnSave() {
        if ($('#mtn-grid').datagrid('validateRow', mtnClickedIdx)) {
            $('#mtn-grid').datagrid('endEdit', mtnClickedIdx);
            var row = $('#mtn-grid').datagrid('getSelected');
            row.db = getDB();
            row.com_id = globalConfig.com_id;
            $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('tindakan/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    console.log(obj.row[0]);
                    $('#mtn-grid').datagrid('updateRow', {
                        index:mtnClickedIdx,
                        row:obj.row[0]
                    });
                    //$('#man-grid').datagrid('selectRow', mtnClickedIdx);
                    mtnEditedId = -1;
                    mtnSetEnableDisable();
                    if (obj.status == 'success'){
                        $('#mtn-grid').datagrid('acceptChanges');
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.msg);
                }
            });
        }
    }

    function mtnCancel() {
        $('#mtn-grid').datagrid('rejectChanges');
        mtnEditedId = -1;
        mtnClickedIdx = -1;
        mtnSetEnableDisable();
    }

    function mtnDelete() {
        var row = $('#mtn-grid').datagrid('getSelected');
        row.db = getDB();
        row.lok_jenis = globalConfig.lok_jenis;
        if (row) $.messager.confirm(globalConfig.app_nama,
            'Apakah betul tindakan '+row.tin_nama+' akan dihapus?', function(r) {
            if (r) $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('tindakan/delete'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#mtn-grid').datagrid('deleteRow', mtnClickedIdx);
                        mtnClickedIdx = -1;
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.errmsg);
                }
            });
        });
    }

    function mtnSetEnableDisable() {
        if (mtnEditedId >= 0) { // mode tambah atau edit
            $('#mtn-btn-add').linkbutton('disable');
            $('#mtn-btn-save').linkbutton('enable');
            $('#mtn-btn-cancel').linkbutton('enable');
            $('#mtn-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#mtn-btn-add').linkbutton('enable');
            $('#mtn-btn-save').linkbutton('disable');
            $('#mtn-btn-cancel').linkbutton('disable');
            $('#mtn-btn-del').linkbutton('enable');
        }
    }

    function mtnTextboxOnChange(obj) {
        var str = $(obj).textbox('getValue');
        str = setCapitalSentenceEveryWord(str);
        $(obj).textbox('setValue', str);
    }
</script>