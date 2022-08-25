var tagFirstLoad = true;

$(function() {
    $('#tag-grid').propertygrid({
        border:false,
        fitColumns:true,
        showGroup:true,
        showHeader:true,
        fit:true,
        queryParams:{
            db:getDB()
        },
        url:getRestAPI('kunbiaya/billRead'),
        columns:[[{
            field:'dt',
            title:'Tanggal',
            fixed:true,
            width:90
        },{
            field:'name',
            title:'Nama biaya',
            width:250
        },{
            field:'value',
            title:'Jumlah',
            align:'right',
            fixed:true,
            formatter:function(value,row) {
                return parseInt(row.value).toLocaleString();
            },
            width:100
        },{
            field:'info',
            title:'Keterangan',
            align:'right',
            fixed:true,
            width:100
        }]],
        onLoadSuccess:function(data) {
            if (tagFirstLoad) {
                tagFirstLoad = false;
                $('#kas-grid').datagrid('selectRow', 0);
            }
        },
        onHeaderContextMenu:function(e,field) {
            e.preventDefault();
            if (field=='kbi_tanggal') {
                var row = $(this).datagrid('getSelected');
                if (row) showID(row.kbi_id);
            }
        }
    });
});