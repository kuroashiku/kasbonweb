<div id="mtd-grid"></div>
<div id="mtd-grid-tb" style="padding:5px">
    <div id="mtd-btn-add"></div>
    <div id="mtd-btn-save"></div>
    <div id="mtd-btn-cancel"></div>
    <div id="mtd-btn-del"></div>
    <div style="float:right">
        <div id="mtd-search"></div>
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
    var mtdEditedId = -1;
    var mtdClickedIdx = -1;
    var indexglobal=-1;
    var indexNow=-1;
    var rownow;
    $('#mtd-btn-add').linkbutton({
        iconCls:'fa fa-plus-circle fa-lg',
        text:'Tambah',
        height:24,
        onClick:function() {mtdAdd();}
    });
    $('#mtd-btn-save').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Simpan',
        height:24,
        disabled:true,
        onClick:function() {mtdSave();}
    });
    $('#mtd-btn-cancel').linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Batal',
        height:24,
        disabled:true,
        onClick:function() {mtdCancel();}
    });
    $('#mtd-btn-del').linkbutton({
        height:24,
        text:'Hapus',
        onClick:function() {mtdDelete();}
    });
    $('#mtd-search').searchbox({
        prompt:'Ketik nama yang dicari',
        height:24,
        width:200,
        searcher:function(value) {
            $('#mtd-grid').datagrid('reload', {
                key_val:value,
                com_id:globalConfig.com_id,
                lok_id:globalConfig.lok_id,
                db:globalConfig.login_data.db
            });
        }
    });
    $('#mtd-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        editorHeight:22,
        toolbar:'#mtd-grid-tb',
        rownumbers:true,
        idField:'dhti_id',
        fit:true,
        columns:[[{
            field:'dtin_nama',
            title:'Nama Tindakan',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,50]',
                    onChange:function() {mtdTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:250
        },{
            field:'dhti_id',
            title:'ID',
            resizable:false,
            width:40
        },{
            field:'dhti_dgti_id',
            title:'Grup Tindakan',
            resizable:false,
            width:150,
            formatter:function(value,row) {return row.dgti_nama;},
            editor:{
                type:'combobox',
                options:{
                    valueField:'dgti_id',
                    textField:'dgti_nama',
                    queryParams:{
                        db:getDB(),
                        lok_id:globalConfig.lok_id
                    },
                    url:getRestAPI('tindakan/dgti'),
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    mode:'remote',
                    method:'post',
                    required:true,
                }
            }
        },{
            field:'dhti_dghr_id',
            title:'Grup Harga',
            resizable:false,
            width:100,
            formatter:function(value,row) {
                var str = row.dghr_nama;
                return str;
            },
            editor:{
                type:'combobox',
                options:{
                    valueField:'dghr_id',
                    textField:'dghr_nama',
                    queryParams:{
                        db:getDB(),
                        lok_id:globalConfig.lok_id
                    },
                    url:getRestAPI('tindakan/dghr'),
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    mode:'remote',
                    method:'post',
                    required:true,
                }
            }
        },{
            field:'dhti_harga',
            title:'Harga',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,50]',
                    onChange:function() {mtdTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:60
        },{
            field:'dtin_status',
            title:'Status',
            align:'center',
            formatter:function(value, row) {
                var checkbox = '';
                if (row.dtin_status == 'Aktif')
                    checkbox = '<span class="fa fa-check" style="color:green"></span>';
                else
                    checkbox = '<span class="fa fa-times-circle" style="color:red"></span>';
                return checkbox;
            },
            resizable:false,
            width:60
        }]],
        queryParams:{com_id:globalConfig.com_id,db:getDB(),lok_id:globalConfig.lok_id},
        url:getRestAPI('tindakan/read_master_dks'),
        onClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (mtdClickedIdx == -1) mtdClickedIdx = index;
            else if(mtdClickedIdx == index) {
                if (field != 'dtin_status') mtdEdit(field);
            }
            else mtdCancel();
            {
                
                // // mtdEditedId = -1;
                // // mtdClickedIdx = -1;
                // // mtdSetEnableDisable(); 
                // var row=[];
                // var selectedKunRow = $('#mtd-grid').datagrid('getSelected');
                // // var rowIndex = $("#mtd-grid").datagrid("getRowIndex", selectedKunRow);
                // var rowIndex=indexNow;
                // console.log(indexNow)
                // console.log(($('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dtin_nama'}).target).textbox('getValue'))
                // row.dtin_nama = ($('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dtin_nama'}).target).textbox('getValue');
                // //console.log(($('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dtin_nama'}).target).textbox('getValue'))
                // row.dhti_dghr_id = ($('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dhti_dghr_id'}).target).combobox('getValue');
                // row.dghr_nama = ($('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dhti_dghr_id'}).target).combobox('getText');
                // row.dhti_dgti_id = ($('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dhti_dgti_id'}).target).combobox('getValue');
                // row.dgti_nama = ($('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dhti_dgti_id'}).target).combobox('getText');
                // console.log(row)
                

                // // data = [{
                // //     dhti_dghr_id:row.dhti_dghr_id,
                // //     dghr_nama:row.dghr_nama
                // // }];
                // // ($('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dtin_nama'}).target).textbox('setValue',row.dtin_nama);
                // // ($('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dhti_dghr_id'}).target).combobox('loadData',data);
                // // ($('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dhti_dghr_id'}).target).combobox('setValue',row.dhti_dghr_id);
                // // data = [{
                // //     dhti_dgti_id:row.dhti_dgti_id,
                // //     dgti_nama:row.dgti_nama
                // // }];
                // // ($('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dhti_dgti_id'}).target).combobox('loadData',data);
                // // ($('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dhti_dgti_id'}).target).combobox('setValue',row.dhti_dgti_id);
                // $('#mtd-grid').datagrid('endEdit', indexNow);
                // $('#mtd-grid').datagrid('reload');
                // mtdEditedId = -1;
                // mtdClickedIdx = -1;
                // mtdSetEnableDisable();
                
                // // console.log(indexNow)
                // //$('#mtd-grid').datagrid('beginEdit', indexNow);

                // //mtdEdit(field);
            }
        },
        onDblClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (field == 'dtin_status' && row && mtdEditedId == -1) {
                $.ajax({
                    type:'POST',
                    data:{dtin_id:row.dtin_id},
                    url:getRestAPI('tindakan/changestatusdks'),
                    success:function(retval) {
                        var obj = JSON.parse(retval);
                        if (obj.status == 'success') {
                            row.dtin_status = obj.new_status;
                            $('#mtd-grid').datagrid('updateRow', {
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
        var grid = $('#mtd-grid');
        var columns = grid.datagrid('options').columns;
        columns[0][0].title = 'Action Name';
        columns[0][1].title = 'ID';
        columns[0][2].title = 'Action Group';
        columns[0][3].title = 'Price Group';
        columns[0][4].title = 'Price';
        columns[0][5].title = 'Status';
        grid.datagrid({columns:columns});
    }

    function mtdAdd() {
        mtdEditedId = 0; // untuk data baru dhti_id diset 0 nanti di model digenerate ID baru
        mtdClickedIdx = 0; // tambah data baru selalu di index 0, paling atas
        $('#mtd-grid').datagrid('insertRow', {
            index:mtdClickedIdx,
            row: {
                dhti_id:mtdEditedId
            }
        });
        $('#mtd-grid').datagrid('selectRow', 0).datagrid('beginEdit', 0);
        var ed = $('#mtd-grid').datagrid('getEditor', {index:mtdClickedIdx, field:'dtin_nama'});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        mtdSetEnableDisable();
    }

    function mtdEdit(fieldName) {
        $('#mtd-grid').datagrid('beginEdit', mtdClickedIdx);
        tampil_combobox();
        var ed = $('#mtd-grid').datagrid('getEditor', {index:mtdClickedIdx, field:fieldName});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        var row = $('#mtd-grid').datagrid('getSelected');
        if (row) {
            mtdEditedId = row.dhti_id;
            mtdSetEnableDisable();
        }
    }

    function tampil_combobox(){
        var selectedKunRow = $('#mtd-grid').datagrid('getSelected');
        var rowIndex = $("#mtd-grid").datagrid("getRowIndex", selectedKunRow);
        ed = $('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dhti_dghr_id'});
        data = [{
            dghr_id:selectedKunRow.dghr_id,
            dghr_nama:selectedKunRow.dghr_nama
        }];
        $(ed.target).combobox('loadData', data);
        $(ed.target).combobox('setValue', selectedKunRow.dghr_id);
        ed = $('#mtd-grid').datagrid('getEditor', {index:rowIndex, field:'dhti_dgti_id'});
        data = [{
            dgti_id:selectedKunRow.dgti_id,
            dgti_nama:selectedKunRow.dgti_nama
        }];
        $(ed.target).combobox('loadData', data);
        $(ed.target).combobox('setValue', selectedKunRow.dgti_id);
    }

    function mtdSave() {
        if ($('#mtd-grid').datagrid('validateRow', mtdClickedIdx)) {
            $('#mtd-grid').datagrid('endEdit', mtdClickedIdx);
            var row = $('#mtd-grid').datagrid('getSelected');
            row.db = getDB();
            row.lok_jenis = globalConfig.lok_jenis;
            $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('tindakan/save_dks'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    console.log(obj.row)
                    $('#mtd-grid').datagrid('updateRow', {
                        index:mtdClickedIdx,
                        row:obj.row[0]
                    });
                    //$('#man-grid').datagrid('selectRow', mtdClickedIdx);
                    mtdEditedId = -1;
                    mtdSetEnableDisable();
                    if (obj.status == 'success'){
                        $('#mtd-grid').datagrid('acceptChanges');
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.msg);
                }
            });
        }
        
    }

    function mtdCancel() {
        $('#mtd-grid').datagrid('rejectChanges');
        mtdEditedId = -1;
        mtdClickedIdx = -1;
        mtdSetEnableDisable();
    }

    function mtdDelete() {
        var row = $('#mtd-grid').datagrid('getSelected');
        row.db = getDB();
        row.lok_jenis = globalConfig.lok_jenis;
        if (row) $.messager.confirm(globalConfig.app_nama,
            'Apakah betul tindakan '+row.dtin_nama+' akan dihapus?', function(r) {
            if (r) $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('tindakan/delete_dks'),
                success:function(retval) {
                    $('#mtd-grid').datagrid('reload');
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#mtd-grid').datagrid('deleteRow', mtdClickedIdx);
                        mtdClickedIdx = -1;
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.errmsg);
                }
            });
        });
    }

    function mtdSetEnableDisable() {
        if (mtdEditedId >= 0) { // mode tambah atau edit
            var selectedRow = $('#mtd-grid').datagrid('getSelected');
            var index = $('#mtd-grid').datagrid('getRowIndex', selectedRow);
            indexNow=index;
            $('#mtd-btn-add').linkbutton('disable');
            $('#mtd-btn-save').linkbutton('enable');
            $('#mtd-btn-cancel').linkbutton('enable');
            $('#mtd-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#mtd-grid').datagrid('selectRow', 1);
            $('#mtd-btn-add').linkbutton('enable');
            $('#mtd-btn-save').linkbutton('disable');
            $('#mtd-btn-cancel').linkbutton('disable');
            $('#mtd-btn-del').linkbutton('enable');
        }
    }

    function mtdTextboxOnChange(obj) {
        var str = $(obj).textbox('getValue');
        str = setCapitalSentenceEveryWord(str);
        $(obj).textbox('setValue', str);
    }
</script>