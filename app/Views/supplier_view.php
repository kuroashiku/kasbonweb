<table class="easyui-datagrid"
    id="sup-grid" rownumbers="true" fitColumns="true">
    <thead>
		<tr>
		    <th data-options="field:'sup_id',width:50,editor:'textbox'">Id</th>
		    <th data-options="field:'sup_kode',width:50,editor:'textbox'">Kode</th>
		    <th data-options="field:'sup_nama',width:50,editor:'textbox'">Nama</th>
		    <th data-options="field:'sup_npwp',width:50,editor:'textbox'">NPWP</th>
            <th data-options="field:'sup_akunbank',width:50,editor:'textbox'">Akun Bank</th>
		    <th data-options="field:'sup_alamat',width:50,editor:'textbox'">Alamat</th>
		    <th data-options="field:'sup_telp',width:50,editor:'textbox'">Telp</th>
		    <th data-options="field:'sup_com_id',width:50,editor:'textbox'">Com Id</th>
		</tr>
	</thead>
</table>
<div id="tb" style="height:auto">
    <div id="sup-btn-add" class="easyui-linkbutton"
        data-options="iconCls:'fa fa-plus-circle fa-lg',plain:true" 
        onclick="append()">Append</div>
    <div id="sup-btn-remove" class="easyui-linkbutton"
        data-options="iconCls:'fa fa-minus-circle fa-lg',plain:true" 
        onclick="removeit()">Remove</div>
    <div id="sup-btn-save" class="easyui-linkbutton"
        data-options="iconCls:'fa fa-check-circle fa-lg',plain:true" 
        onclick="acceptit()">Save</div>
    <div id="sup-btn-cancel" class="easyui-linkbutton"
        data-options="iconCls:'fa fa-times-circle fa-lg',plain:true" 
        onclick="reject()">Cancel</div>
</div>
<script type="text/javascript">
    $('#sup-grid').datagrid({
        fit:true,
        border:false,
		iconCls: 'icon-edit',
        singleSelect: true,
        toolbar: '#tb',
        url: getRestAPI('supplier/read'),
        queryParams:{
            db:getDB()
        },
        method: 'POST',
        onClickCell: onClickCell,
        onLoadSuccess:function () {
            $('#sup-btn-remove').linkbutton('disable');
            $('#sup-btn-save').linkbutton('disable');
            $('#sup-btn-cancel').linkbutton('disable');
        }
    });
    var editIndex = undefined;
    function endEditing(){
        if (editIndex == undefined){
            return true
        }
        if ($('#sup-grid').datagrid('validateRow', editIndex)){
            $('#sup-grid').datagrid('endEdit', editIndex);
            editIndex = undefined;
            return true;
        } 
        else {
            return false;
        }
    }
    function onClickCell(index, field){
        if (editIndex != index){
            if (endEditing()){
                $('#sup-btn-add').linkbutton('disable');
                $('#sup-btn-remove').linkbutton('disable');
                $('#sup-btn-save').linkbutton('enable');
                $('#sup-btn-cancel').linkbutton('enable');
                $('#sup-grid').datagrid('selectRow', index);
                $('#sup-grid').datagrid('beginEdit', index);
                var ed = $('#sup-grid').datagrid('getEditor', {index:index,field:field});
                if (ed){
                    ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                }
                editIndex = index;
            } 
            else {
                $('#sup-grid').datagrid('selectRow', editIndex);
            }
        }
    }
    function append(){
        $('#sup-btn-remove').linkbutton('disable');
        $('#sup-btn-save').linkbutton('enable');
        $('#sup-btn-cancel').linkbutton('enable');
        $('#sup-btn-add').linkbutton('disable');
    }
    function removeit(){
        if (isDemo()) return;
        if (editIndex == undefined){return}
        $('#sup-grid').datagrid('cancelEdit', editIndex)
            .datagrid('deleteRow', editIndex);
        editIndex = undefined;
        if (acceptit){
            $('#sup-btn-add').linkbutton('enable');
            $('#sup-btn-remove').linkbutton('disable');
            $('#sup-btn-save').linkbutton('enable');
            $('#sup-btn-cancel').linkbutton('disable');
        }
    }
    function acceptit(){
        if (isDemo()) return;
        if (endEditing()){
            $('#sup-grid').datagrid('acceptChanges');
            $('#sup-btn-add').linkbutton('enable');
            $('#sup-btn-remove').linkbutton('disable');
            $('#sup-btn-save').linkbutton('disable');
            $('#sup-btn-cancel').linkbutton('disable');
            var row = $('#sup-grid').datagrid('getSelected');
            $.ajax({
                type:'POST',
                data:row,
                url:'save',
                success:function(retval) {
                    alert('Data berhasil terkirim');
                }
            });
        }
    }
    function reject(){
        $('#sup-grid').datagrid('rejectChanges');
        editIndex = undefined;
        if(reject){
            $('#sup-btn-add').linkbutton('enable');
        }
    }
</script>