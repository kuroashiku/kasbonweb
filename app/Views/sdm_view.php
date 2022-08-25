<div id="sdm-grid"></div>
<div id="sdm-grid-tb" style="padding:5px">
    <div id="sdm-btn-add"></div>
    <div id="sdm-btn-save"></div>
    <div id="sdm-btn-cancel"></div>
    <div id="sdm-btn-del"></div>
    <div style="float:right">
        <div id="sdm-search"></div>
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
    var sdmEditedId = -1;
    var sdmClickedIdx = -1;

    $('#sdm-btn-add').linkbutton({
        iconCls:'fa fa-plus-circle fa-lg',
        text:'Tambah',
        height:24,
        onClick:function() {sdmAdd();}
    });
    $('#sdm-btn-save').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Simpan',
        height:24,
        disabled:true,
        onClick:function() {sdmSave();}
    });
    $('#sdm-btn-cancel').linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Batal',
        height:24,
        disabled:true,
        onClick:function() {sdmCancel();}
    });
    $('#sdm-btn-del').linkbutton({
        height:24,
        text:'Hapus',
        onClick:function() {sdmDelete();}
    });
    $('#sdm-search').searchbox({
        prompt:'Ketik nama yang dicari',
        height:24,
        width:200,
        searcher:function(value) {
            $('#sdm-grid').datagrid('reload', {
                key_val:value,
                com_id:globalConfig.com_id,
                db:getDB()
            });
        }
    });
    $('#sdm-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        editorHeight:22,
        toolbar:'#sdm-grid-tb',
        rownumbers:true,
        fit:true,
        columns:[[{
            field:'sdm_nama',
            title:'Nama',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,50]',
                    onChange:function() {sdmTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:230
        },{
            field:'sdm_id',
            title:'ID',
            resizable:false,
            width:40
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
            width:100
        },{
            field:'sdm_subjabatan',
            title:'Sub jabatan',
            editor:{
                type:'textbox',
                options:{
                    required:false,
                    validType:'length[0,45]',
                    onChange:function() {sdmTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:150
        },{
            field:'sdm_kelamin',
            title:'Gender',
            editor:{
                type:'textbox',
                options:{
                    validType:'onList["LP","Gender hanya boleh L atau P"]'
                }
            },
            resizable:false,
            width:60
        },{
            field:'sdm_status',
            title:'Aktif',
            align:'center',
            formatter:function(value, row) {
                var checkbox = '';
                if (row.sdm_status == 'A')
                    checkbox = '<span class="fa fa-check" style="color:green"></span>';
                else
                    checkbox = '<span class="fa fa-times-circle" style="color:red"></span>';
                return checkbox;
            },
            resizable:false,
            width:60
        },{
            field:'sdm_nip',
            title:'NIP',
            editor:{
                type:'textbox',
                options:{
                    required:false,
                    validType:'length[0,20]'
                }
            },
            resizeble:false,
            width:120
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
            field:'sdm_harga',
            title:'Harga',
            align:'right',
            editor:{
                type:'numberbox'
            },
            resizable:false,
            width:100
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
                if (field != 'sdm_status') sdmEdit(field);
            }
        },
        onDblClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (field == 'sdm_status' && row && sdmEditedId == -1) {
                if (isDemo()) return;
                $.ajax({
                    type:'POST',
                    data:{
                        sdm_id:row.sdm_id,
                        db:getDB()
                    },
                    url:getRestAPI('sdm/changestatus'),
                    success:function(retval) {
                        var obj = JSON.parse(retval);
                        if (obj.status == 'success') {
                            row.sdm_status = obj.new_status;
                            $('#sdm-grid').datagrid('updateRow', {
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
        var grid = $('#sdm-grid');
        var columns = grid.datagrid('options').columns;
        columns[0][0].title = 'Name';
        columns[0][1].title = 'ID';
        columns[0][2].title = 'Position';
        columns[0][3].title = 'Sub Position';
        columns[0][4].title = 'Gender';
        columns[0][5].title = 'Active';
        columns[0][6].title = 'NIP';
        columns[0][7].title = 'Address';
        columns[0][8].title = 'City';
        columns[0][9].title = 'Price';
        columns[0][10].title = 'Phone';
        grid.datagrid({columns:columns});
    }

    function sdmAdd() {
        sdmEditedId = 0; // untuk data baru sdm_id diset 0 nanti di model digenerate ID baru
        sdmClickedIdx = 0; // tambah data baru selalu di index 0, paling atas
        $('#sdm-grid').datagrid('insertRow', {
	        index:sdmClickedIdx,
            row: {
                sdm_id:sdmEditedId
            }
        });
        $('#sdm-grid').datagrid('selectRow', 0).datagrid('beginEdit', 0);
        var ed = $('#sdm-grid').datagrid('getEditor', {index:sdmClickedIdx, field:'sdm_nama'});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        sdmSetEnableDisable();
    }

    function sdmEdit(fieldName) {
        $('#sdm-grid').datagrid('beginEdit', sdmClickedIdx);
        var ed = $('#sdm-grid').datagrid('getEditor', {index:sdmClickedIdx, field:fieldName});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        var row = $('#sdm-grid').datagrid('getSelected');
        if (row) {
            sdmEditedId = row.sdm_id;
            sdmSetEnableDisable();
        }
    }

    function sdmSave() {
        if (isDemo()) return;
        if ($('#sdm-grid').datagrid('validateRow', sdmClickedIdx)) {
            $('#sdm-grid').datagrid('endEdit', sdmClickedIdx);
            var row = $('#sdm-grid').datagrid('getSelected');
            row.db = getDB();
            row.sdm_com_id = globalConfig.com_id;
            $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('sdm/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    $('#sdm-grid').datagrid('updateRow', {
                        index:sdmClickedIdx,
                        row:obj.data
                    });
                    $('#sdm-grid').datagrid('selectRow', sdmClickedIdx);
                    sdmEditedId = -1;
                    sdmSetEnableDisable();
                    if (obj.status == 'success')
                        $('#sdm-grid').datagrid('acceptChanges');
                    else
                        $.messager.alert(globalConfig.app_nama, obj.msg);
                }
            });
        }
    }

    function sdmCancel() {
        $('#sdm-grid').datagrid('rejectChanges');
        sdmEditedId = -1;
        sdmClickedIdx = -1;
        sdmSetEnableDisable();
    }

    function sdmDelete() {
        if (isDemo()) return;
        var row = $('#sdm-grid').datagrid('getSelected');
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
                        $('#sdm-grid').datagrid('deleteRow', sdmClickedIdx);
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
            $('#sdm-btn-add').linkbutton('disable');
            $('#sdm-btn-save').linkbutton('enable');
            $('#sdm-btn-cancel').linkbutton('enable');
            $('#sdm-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#sdm-btn-add').linkbutton('enable');
            $('#sdm-btn-save').linkbutton('disable');
            $('#sdm-btn-cancel').linkbutton('disable');
            $('#sdm-btn-del').linkbutton('enable');
        }
    }

    function sdmTextboxOnChange(obj) {
        var str = $(obj).textbox('getValue');
        str = setCapitalSentenceEveryWord(str);
        $(obj).textbox('setValue', str);
    }
</script>