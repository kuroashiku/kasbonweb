$(function() {
    $('#bpjs-loading').show();
    $('#bpjs-response').hide();
    var row = $('#kas-grid').datagrid('getSelected');
            
    $.ajax({
        type:'POST',
        data:{
            kun_id: row.kun_id,
            db:getDB()
        },
        url:getRestAPI('kunbayar/potongan_bpjs'),
        success:function(retval) {
            var obj = JSON.parse(retval);
            $('#bpjs-loading').hide();
            if(obj.bpjs_cover){
                $('#bpjs-response').show();
                $('#bpjs-value').text(`Rp ${currencyFormat(obj.bpjs_cover)}`);
                $('#bpjs-btn-copy').linkbutton({
                    iconCls: 'fa fa-copy',
                    height:24,
                    text:'Copy',
                    onClick:function() {
                        navigator.clipboard.writeText(obj.bpjs_cover);
                        window.showSnackbar("Copied");
                        $('#kas-bpjs-dlg').dialog('close');
                    }
                });
            }
        }
    });
});