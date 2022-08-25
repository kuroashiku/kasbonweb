var editingId;
var clickedId;

$(function(){
    $('#coa-btn-newclass').linkbutton({
        iconCls:'fa fa-plus-circle',
        onClick:function() {addClass();}
    });
    $('#coa-btn-reload').linkbutton({
        iconCls:'fa fa-sync-alt',
        onClick:function() {$('#coa-grid').treegrid('reload');}
    });
    $('#coa-grid').treegrid({
        fit:true,
        border:false,
        toolbar:'#coa-grid-tb',
        animate:true,
        collapsible:true,
        queryParams:{
            db:getDB()
        },
        url:getRestAPI('coa/read'),
        method:'POST',
        idField:'coa_id',
        treeField:'coa_nama',
        columns: [[{
            field:'coa_id',
            title:'ID',
            width:35,
            resizable:false,
            hidden:false
        },{
            field:'coa_nama',
            title:'Nama akun',
            width:250,
            editor:'text',
            styler:function(value, row) {
                var newstyle = '';
                if(row.coa_cty_id) newstyle = 'font-weight:bold;color:#0000aa';
                return newstyle;
            }
        },{
            field:'coa_kode',
            title:'Kode akun',
            width:75,
            editor:'text'
        },{
            field:'coa_cty_id',
            title:'Tipe kelas akun',
            width:120,
            formatter:function(value,row) {
                return row.cty_nama;
            },
            editor: {
                type:'combobox',
                options: {
                    valueField:'cty_id',
                    textField:'cty_nama',
                    editable:false,
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    queryParams:{
                        db:getDB()
                    },
                    url:getRestAPI('classtype/read'),
                    height:26,
                    keyHandler:$.extend({}, $.fn.combobox.defaults.keyHandler, {
                        enter:function(e) {saveCoa()}
                    })
                }
            }
        }]],
        onExpand:function(row) {
            $.ajax({
                data:{db:getDB()},
                type:'POST',
                data:row,
                url:getRestAPI('coa/save')
            });
        },
        onCollapse:function(row) {
            $.ajax({
                data:{db:getDB()},
                type:'POST',
                data:row,
                url:getRestAPI('coa/save')
            });
        },
        onBeforeDrag:function(row) {
            if (row.coa_id == editingId)
                return false;
        },
        onLoadSuccess:function(row) {
            $('#coa-grid').treegrid('enableDnd', row?row.coa_id:null);
        },
        onDrop:function(targetRow, sourceRow, point) {
            return moveCoa(targetRow, sourceRow, point);
        },
        onClickCell:function(field, row) {
            if (clickedId == undefined || clickedId != row.coa_id)
                clickedId = row.coa_id;
            else if ('coa_kode,coa_nama,coa_cty_id'.
                indexOf(field) >= 0 &&
                globalConfig.login_data.coa == 'edit')
                editCoa(field);
        },
        onBeginEdit:function(row) {
            var dg = $(this);
            var ed = dg.treegrid('getEditors', row.coa_id);
            if (!ed)
                return;
            var t;
            for(i=0;i<ed.length;i++) {
                t = $(ed[i].target);
                if (ed[i].field == 'coa_kode') {
                    $(ed[i].target).attr('maxlength', '30');
                    $(ed[i].target).css('height', '26');
                }
                else if (ed[i].field == 'coa_nama') {
                    $(ed[i].target).attr('maxlength', '45');
                    $(ed[i].target).css('height', '26');
                }
                else if(ed[i].field == 'coa_cty_id')
                    $(ed[i].target).combobox('readonly', row.coa_parent_id);
                t.bind('keydown', function(e) {
                    if (e.keyCode == 13) saveCoa();
                    else if (e.keyCode == 27) cancelCoa();
                });
            }
        },
        onContextMenu:function(e,row) {
            if (row) {
                e.preventDefault();
                $(this).treegrid('select', row.coa_id);
                $('#coa-menu').menu('show',{
                    left: e.pageX,
                    top: e.pageY,
                    onClick:function(item) {
                        switch(item.id) {
                        case 'coa-mnu-addsub':addCoa();break;
                        case 'coa-mnu-delsub':delCoa();
                        }
                    }
                });				
            }
        }
    });
});

function addClass() {
    if (!globalConfig.login_data.coa || globalConfig.login_data.coa == 'view')
        $.messager.alert(globalConfig.app_nama,
            'Maaf anda tidak berhak melakukan aktivitas ini. Terima kasih');
    else if (globalConfig.login_data.coa == 'edit') 
    $.ajax({
        type:'POST',
        data:{db:getDB()},
        url:getRestAPI('coa/addclass'),
        success: function(retval) {
            var obj = JSON.parse(retval);
            if(obj.status == 'failed')
                alert('Proses penambahan kelas akun gagal');
            else $('#coa-grid').treegrid('append',{
                data:[{
                    coa_id:obj.row.coa_id,
                    coa_nama:obj.row.coa_nama
                }]
            });
        }
    });
}

function addCoa() {
    var row = $('#coa-grid').treegrid('getSelected');
    if (row) {
        if (globalConfig.login_data.coa == 'view')
            $.messager.alert(globalConfig.app_nama,
                'Maaf anda tidak berhak melakukan aktivitas ini. Terima kasih');
        else $.ajax({
            type:'POST',
            data:{
                target_id:row.coa_id,
                target_parent_id:row._parentId,
                source_id:null,
                source_parent_id:null,
                point:'append',
                db:getDB()
            },
            url:getRestAPI('coa/addcoa'),
            success: function(retval) {
                var obj = JSON.parse(retval);
                if(obj.status == 'failed')
                    $.messager.alert(globalConfig.app_nama,'Proses penambahan akun gagal','info');
                else {
                    $('#coa-grid').treegrid('append',{
                        parent:obj.row.coa_parent_id,
                        data:[{
                            coa_id:obj.row.coa_id,
                            coa_nama:obj.row.coa_nama
                        }]
                    });
                    $('#coa-grid').treegrid('select', obj.row.coa_id);
                    editCoa('coa_nama');
                }
            }
        });
    }
    else $.messager.alert(globalConfig.app_nama,
        'Silahkan dipilih dulu di kelas atau akun mana yang akan ditambahkan sub akun',
        'info');
}

function delCoa() {
    if (globalConfig.login_data.coa == 'view')
        $.messager.alert(globalConfig.app_nama,
            'Maaf anda tidak berhak melakukan aktivitas ini. Terima kasih');
    else {
        var row = $('#coa-grid').treegrid('getSelected');
        if (!row)
            $.messager.alert(globalConfig.app_nama,
                'Silahkan dipilih dulu akun yang akan dihapus','info');
        else {
            var children = $('#coa-grid').treegrid('getChildren', row.coa_id);
            if (children.length > 0) $.messager.alert('SiReDisH',
                'Kelas atau akun yang memiliki sub akun tidak bisa dihapus','info');
            else {
                var deletedCoaId = row.coa_id;
                $.ajax({
                    type:'POST',
                    data:{
                        coa_id:deletedCoaId,
                        db:getDB()
                    },
                    url:getRestAPI('coa/delete'),
                    success: function(retval) {
                        var obj = JSON.parse(retval);
                        if(obj.status == 'failed') $.messager.alert(globalConfig.app_nama,
                            'Proses penghapusan akun gagal','info');
                        else
                            $('#coa-grid').treegrid('remove', deletedCoaId);
                    }
                });
            }
        }
    }
}

function moveCoa(targetRow, sourceRow, point) {
    var retval = true;
    $.ajax({
        type:'POST',
        data:{
            target_id:targetRow.coa_id,
            target_parent_id:targetRow._parentId,
            source_id:sourceRow.coa_id,
            source_parent_id:sourceRow._parentId,
            point:point,
            db:getDB()
        },
        url:getRestAPI('coa/move'),
        success: function(retval) {
            var obj = JSON.parse(retval);
            if(obj.status == 'failed') {
                $.messager.alert(globalConfig.app_nama,'Proses pemindahan akun gagal','info');
                retval = false;
            }
        }
    });
    return retval;
}

function editCoa(fieldName) {
    if (editingId != undefined){
        $('#coa-grid').treegrid('select', editingId);
        return;
    }
    var row = $('#coa-grid').treegrid('getSelected');
    if (row) {
        editingId = row.coa_id
        $('#coa-grid').treegrid('beginEdit', editingId);
        var ed = $('#coa-grid').treegrid('getEditor', {index:row.coa_id, field:fieldName});
        $(ed.target).focus();
        $(ed.target).select();
    }
}

function saveCoa() {
    if (editingId != undefined){
        $('#coa-grid').treegrid('endEdit', editingId);
        editingId = undefined;
        var row = $('#coa-grid').treegrid('getSelected');
        row.db = getDB();
        $.ajax({
            type:'POST',
            data:row,
            url:getRestAPI('coa/save'),
            success: function(retval) {
                var obj = JSON.parse(retval);
                if(obj.status == 'success') $('#coa-grid').treegrid('update',{
                    id: obj.row.coa_id,
                    row: obj.row
                });
                else $.messager.alert(globalConfig.app_nama,
                    'Proses save akun gagal','info');
            }
        });
    }
}

function cancelCoa() {
    if (editingId != undefined){
        $('#coa-grid').treegrid('cancelEdit', editingId);
        editingId = undefined;
    }
}