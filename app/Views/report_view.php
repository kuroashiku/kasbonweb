<table width="100%" height="100%" border="1"
    style="font-size:14px;font-family:sans-serif;padding:10px">
    <tr height="100%">
        <td width="100%" id="bigten-rep">
        </td>
    </tr>
</table>

<script>
$(function() {
    $.ajax({
        type:'POST',
        data:data,
        url:'report/bigten',
        success:function(retval) {
            var obj = JSON.parse(retval);
            if (obj.status == 'success') {
                if (manEditedId == 0) {
                    $('#man-grid').datagrid('insertRow', {
                        index:0,
                        row:obj.row
                    });
                    $('#man-grid').datagrid('selectRow', 0);
                }
                else {
                    var selectedRow = $('#man-grid').datagrid('getSelected');
                    var index = $('#man-grid').datagrid('getRowIndex', selectedRow);
                    $('#man-grid').datagrid('updateRow', {
                        index:index,
                        row:obj.row
                    });
                    $('#man-grid').datagrid('selectRow', index);
                }
            }
            else
                alert(obj.errmsg);
            manEditedId = -1;
            manSetEnableDisable();
        }
    });
});
</script>