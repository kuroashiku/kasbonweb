<div id="byr-grid"></div>
<script type="text/javascript">
    $('#byr-grid').datagrid({
        border:false,
        singleSelect:true,
        fit:true,
        columns:[[{
            field:'byr_id',
            title:'ID',
            resizable:false,
            width:100,
            hidden:true
        },{
            field:'byr_nama',
            title:'Nama',
            resizable:false,
            width:130
        },{
            field:'byr_mitra',
            title:'Mitra',
            resizable:false,
            width:130
        }]],
        url:getRestAPI('master/pembayaran') // tidak membaca database tapi dari json di model
    });
</script>