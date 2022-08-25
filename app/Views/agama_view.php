<div id="agm-grid"></div>
<script type="text/javascript">
    $('#agm-grid').datagrid({
        border:false,
        singleSelect:true,
        fit:true,
        columns:[[{
            field:'agm_id',
            title:'ID',
            resizable:false,
            width:100,
            hidden:true
        },{
            field:'agm_nama',
            title:'Nama',
            resizable:false,
            width:130
        }]],
        url:getRestAPI('master/agama') // tidak membaca database tapi dari json di model
    });

    if(globalConfig.login_data.lang == 1){
        var grid = $('#agm-grid');
        var columns = grid.datagrid('options').columns;
        columns[0][0].title = 'ID';
        columns[0][1].title = 'Name';
        grid.datagrid({columns:columns});
    }
</script>