<div id="myn-grid"></div>
<div id="myn-grid-tb" style="padding:5px">
    <div id="myn-btn-add"></div>
    <div id="myn-btn-save"></div>
    <div id="myn-btn-cancel"></div>
    <div id="myn-btn-del"></div>
</div>
<script type="text/javascript">
    // Minus satu artinya mode lihat
    // 0 berarti mode tambah
    // selain itu berarti sedang ada record yang diedit
    var mynEditedId = -1;
    var mynClickedIdx = -1;

    $('#myn-btn-add').linkbutton({
        iconCls:'fa fa-plus-circle fa-lg',
        text:'Tambah',
        height:24,
        onClick:function() {mynAdd();}
    });
    $('#myn-btn-save').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Simpan',
        height:24,
        disabled:true,
        onClick:function() {mynSave();}
    });
    $('#myn-btn-cancel').linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Batal',
        height:24,
        disabled:true,
        onClick:function() {mynCancel();}
    });
    $('#myn-btn-del').linkbutton({
        height:24,
        text:'Hapus',
        onClick:function() {mynDelete();}
    });
    $('#myn-grid').datagrid({
        border:false,
        singleSelect:true,
        editorHeight:22,
        toolbar:'#myn-grid-tb',
        fit:true,
        columns:[[{
            field:'yan_id',
            title:'ID',
            resizable:false,
            width:40
        },{
            field:'yan_nama',
            title:'Layanan',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,45]'
                }
            },
            resizeble:false,
            width:200
        },{
            field:'yan_klinik',
            title:'Klinik',
            editor:{
                type:'combobox',
                options:{
                    valueField:'yan_klinik',
                    textField:'yan_klinik',
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    mode:'remote',
                    method:'post',
                    url:getRestAPI('layanan/klinik'),
                    onChange:function() {mynTextboxOnChange(this)},
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
            lok_jenis:globalConfig.lok_jenis,
            lok_id:globalConfig.lok_id,
            db:getDB()
        },
        url:getRestAPI('layanan/read'),
        onClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (mynClickedIdx == -1) mynClickedIdx = index;
            else if (mynClickedIdx != index) {
                if (mynEditedId != -1) mynCancel();
                mynClickedIdx = index;
            }
            else {
                mynClickedIdx = index;
                mynEdit(field);
            }
        }
    });

    function mynAdd() {
        mynEditedId = 0; // untuk data baru yan_id diset 0 nanti di model digenerate ID baru
        mynClickedIdx = 0; // tambah data baru selalu di index 0, paling atas
        $('#myn-grid').datagrid('insertRow', {
	        index:mynClickedIdx,
            row: {
                yan_id:mynEditedId
            }
        });
        $('#myn-grid').datagrid('selectRow', 0).datagrid('beginEdit', 0);
        var ed = $('#myn-grid').datagrid('getEditor', {index:mynClickedIdx, field:'yan_nama'});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        mynSetEnableDisable();
    }

    function mynEdit(fieldName) {
        $('#myn-grid').datagrid('beginEdit', mynClickedIdx);
        var ed = $('#myn-grid').datagrid('getEditor', {index:mynClickedIdx, field:fieldName});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        var row = $('#myn-grid').datagrid('getSelected');
        mynEditedId = row.yan_id;
        mynSetEnableDisable();
    }

    function mynSave() {
        if (isDemo()) return;
        if ($('#myn-grid').datagrid('validateRow', mynClickedIdx)) {
            $('#myn-grid').datagrid('endEdit', mynClickedIdx);
            var row = $('#myn-grid').datagrid('getSelected');
            row.db = getDB();
            row.lok_id = globalConfig.lok_id;
            row.lok_jenis = globalConfig.lok_jenis;
            $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('layanan/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    $('#myn-grid').datagrid('updateRow', {
                        index:mynClickedIdx,
                        row:obj.data
                    });
                    $('#man-grid').datagrid('selectRow', mynClickedIdx);
                    mynEditedId = -1;
                    mynSetEnableDisable();
                    if (obj.status == 'success')
                        $('#myn-grid').datagrid('acceptChanges');
                    else
                        $.messager.alert(globalConfig.app_nama, obj.msg);
                }
            });
        }
    }

    function mynCancel() {
        $('#myn-grid').datagrid('rejectChanges');
        mynEditedId = -1;
        mynClickedIdx = -1;
        mynSetEnableDisable();
    }

    function mynDelete() {
        if (isDemo()) return;
        var row = $('#myn-grid').datagrid('getSelected');
        if (row) $.messager.confirm(globalConfig.app_nama,
            'Apakah betul data layanan '+row.yan_nama+' akan dihapus?', function(r) {
            if (r) $.ajax({
                type:'POST',
                data:{
                    yan_id:row.yan_id,
                    db:getDB()
                },
                url:getRestAPI('layanan/delete'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#myn-grid').datagrid('deleteRow', mynClickedIdx);
                        mynClickedIdx = -1;
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.errmsg);
                }
            });
        });
    }

    function mynSetEnableDisable() {
        if (mynEditedId >= 0) { // mode tambah atau edit
            $('#myn-btn-add').linkbutton('disable');
            $('#myn-btn-save').linkbutton('enable');
            $('#myn-btn-cancel').linkbutton('enable');
            $('#myn-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#myn-btn-add').linkbutton('enable');
            $('#myn-btn-save').linkbutton('disable');
            $('#myn-btn-cancel').linkbutton('disable');
            $('#myn-btn-del').linkbutton('enable');
        }
    }

    function mynTextboxOnChange(obj) {
        var str = $(obj).textbox('getValue');
        str = setCapitalSentenceEveryWord(str);
        $(obj).textbox('setValue', str);
    }
</script>