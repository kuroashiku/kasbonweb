$(function() {
    var arrField;

    $('#labrefresh').linkbutton({
        iconCls:'fa fa-sync-alt fa-lg',
        height:24,
        onClick:function() {
            $('#klb-fields-panel').empty();
            var datakbi = $('#kbi-grid').datagrid('getData');
            var labval = new Array();
            arrField = [];
            $.each(datakbi.rows,function(key, labkbi){
                if(labkbi.kbi_jns_id == 'L'){
                    $.ajax({
                        url:getRestAPI("laborat/getlabfields"), // tidak membaca database tapi dari json di model
                        data:{
                            id:labkbi.kbi_bea_id,
                        },
                        type:'POST',
                        async:false,
                        success:function(retlab) {
                            var objlab = JSON.parse(retlab);
                            $.each(objlab, function(key,fields){
                                $('#klb-fields-panel')
                                    .append('<tr><td width="0%" nowrap>'+fields.nama+'</td>'+
                                    '<td width="0%"><input type="text" id="klb-field-'+
                                    fields.id+'"></td>'+'<td width="100%" nowrap>'+
                                    (fields.satuan?fields.satuan:'')+'</td></tr>');
                                $('#klb-field-'+fields.id).textbox({
                                    height:24
                                });
                                arrField.push(fields.id);
                            });
                        }
                    });
                }
            });
            $.ajax({
                url:getRestAPI("kunlab/read"),
                data:{
                    klb_kun_id:datakbi.rows[0].kbi_kun_id,
                    db:getDB()
                },
                type:'POST',
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    $.each(obj.rows, function(key, labitem) {
                        $('#klb-field-'+labitem.klb_lab_id).val(labitem.klb_lab_value);
                    }); 
                }
            });
        }
    });

    $('#labsimpan').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        height:24,
        onClick:function() {
            if (isDemo()) return;
            var data = [];
            var datakbi = $('#kbi-grid').datagrid('getData');
            $.each(arrField, function(key, idfield) {
                var value = $('#klb-field-'+idfield).val();
                data.push({
                    klb_lab_id:idfield,
                    klb_lab_value:value
                });
            });
            $.ajax({
                data:{
                    klb_kun_id:datakbi.rows[0].kbi_kun_id,
                    row:data,
                    db:getDB()
                },
                type:'POST',
                url:getRestAPI('kunlab/save'),
                success:function(retval) {
                    $.messager.alert(globalConfig.app_nama, "Data berhasil tersimpan");
                }
            });
        }
    });
});