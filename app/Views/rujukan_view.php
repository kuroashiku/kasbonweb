<div id="ru-grid"></div>
<script type="text/javascript">
    $('#ru-grid').datagrid({
        border:false,
        singleSelect:true,
        fit:true,
        columns:[[{
            field:'ru_id',
            title:'ID',
            resizable:false,
            width:100,
            hidden:true
        },{
            field:'ru_nama',
            title:'Nama',
            resizable:false,
            width:130
        }]],
        url:getRestAPI('master/rujukan') // tidak membaca database tapi dari json di model
    });

    if(globalConfig.login_data.lang == 1){
        var grid = $('#ru-grid');
        var columns = grid.datagrid('options').columns;
        columns[0][1].title = 'Name';
        grid.datagrid({columns:columns});
    }
</script>