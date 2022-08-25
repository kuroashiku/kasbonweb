<div id="mob-grid"></div>
<div id="mob-grid-tb" style="padding:5px">
    <div id="mob-btn-add"></div>
    <div id="mob-btn-save"></div>
    <div id="mob-btn-cancel"></div>
    <div style="float:right">
        <div id="mob-search"></div>
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
    var mobEditedId = -1;
    var mobClickedIdx = -1;

    $('#mob-btn-add').linkbutton({
        iconCls:'fa fa-plus-circle fa-lg',
        text:'Tambah',
        height:24,
        onClick:function() {mobAdd();}
    });
    $('#mob-btn-save').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Simpan',
        height:24,
        disabled:true,
        onClick:function() {mobSave();}
    });
    $('#mob-btn-cancel').linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Batal',
        height:24,
        disabled:true,
        onClick:function() {mobCancel();}
    });
    $('#mob-search').searchbox({
        prompt:'Ketik nama yang dicari',
        height:24,
        width:200,
        searcher:function(value) {
            $('#mob-grid').datagrid('reload', {
                key_val:value,
                com_id:globalConfig.com_id,
                db:getDB()
            });
        }
    });
    $('#mob-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        editorHeight:22,
        toolbar:'#mob-grid-tb',
        rownumbers:true,
        fit:true,
        columns:[[{
            field:'obt_nama',
            title:'Nama Obat',
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,50]',
                    onChange:function() {mobTextboxOnChange(this)}
                }
            },
            resizable:false,
            width:250
        },{
            field:'obt_id',
            title:'ID',
            resizable:false,
            width:35
        }]],
        queryParams:{
            com_id:globalConfig.com_id,
            db:getDB()
        },
        url:getRestAPI('obat/read_master'),
        onClickCell:function(index, field, value) {
            var row = $(this).datagrid('getSelected');
            if (mobClickedIdx == -1) mobClickedIdx = index;
            else if (mobClickedIdx != index) {
                if (mobEditedId != -1) mobCancel();
                mobClickedIdx = index;
            }
            else {
                mobClickedIdx = index;
                if (field != 'obt_id') mobEdit(field);
            }
        },
    });
    
    function mobAdd() {
        mobEditedId = 0; // untuk data baru ict_id diset 0 nanti di model digenerate ID baru
        mobClickedIdx = 0; // tambah data baru selalu di index 0, paling atas
        $('#mob-grid').datagrid('insertRow', {
	        index:mobClickedIdx,
            row: {
                obt_id:mobEditedId
            }
        });
        $('#mob-grid').datagrid('selectRow', 0).datagrid('beginEdit', 0);
        var ed = $('#mob-grid').datagrid('getEditor', {index:mobClickedIdx, field:'obt_nama'});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        mobSetEnableDisable();
    }

    function mobEdit(fieldName) {
        $('#mob-grid').datagrid('beginEdit', mobClickedIdx);
        var ed = $('#mob-grid').datagrid('getEditor', {index:mobClickedIdx, field:fieldName});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
        var row = $('#mob-grid').datagrid('getSelected');
        if (row) {
            mobEditedId = row.obt_id;
            mobSetEnableDisable();
        }
    }

    function mobSave() {
        if (isDemo()) return;
        if ($('#mob-grid').datagrid('validateRow', mobClickedIdx)) {
            $('#mob-grid').datagrid('endEdit', mobClickedIdx);
            var row = $('#mob-grid').datagrid('getSelected');
            row.db = getDB();
            $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('obat/save_master'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    $('#mob-grid').datagrid('updateRow', {
                        index:mobClickedIdx,
                        row:obj.data
                    });
                    //$('#man-grid').datagrid('selectRow', mobClickedIdx);
                    mobEditedId = -1;
                    mobSetEnableDisable();
                    if (obj.status == 'success')
                        $('#mob-grid').datagrid('acceptChanges');
                    else
                        $.messager.alert(globalConfig.app_nama, obj.msg);
                }
            });
        }
    }

    function mobCancel() {
        $('#mob-grid').datagrid('rejectChanges');
        mobEditedId = -1;
        mobClickedIdx = -1;
        mobSetEnableDisable();
    }

    function mobSetEnableDisable() {
        if (mobEditedId >= 0) { // mode tambah atau edit
            $('#mob-btn-add').linkbutton('disable');
            $('#mob-btn-save').linkbutton('enable');
            $('#mob-btn-cancel').linkbutton('enable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#mob-btn-add').linkbutton('enable');
            $('#mob-btn-save').linkbutton('disable');
            $('#mob-btn-cancel').linkbutton('disable');
        }
    }

    function mobTextboxOnChange(obj) {
        var str = $(obj).textbox('getValue');
        str = setCapitalSentenceEveryWord(str);
        $(obj).textbox('setValue', str);
    }
</script>