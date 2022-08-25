<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'north',split:false,border:false"
        style="height:95px;background-color:#8ae0ed;padding:5px">
        <div id="obc-title"></div>
        <div style="margin-top:5px">
            <div id="obc-btn-accept"></div>
            <div id="obc-btn-batal"></div>
        </div>
        <div style="margin-top:5px">
            <div id="obc-form-obatchange"></div>
            <div id="obc-btn-change"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
var ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_namaobat'});
var nama = $(ed.target).textbox('getValue');
ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_bea_id'});
var obat_id = $(ed.target).textbox('getValue');
$('#obc-title').html('Obat '+nama+" Kehabisan Stok");
$('#obc-btn-change').linkbutton({
    text:'Change',
    height:24,
    iconCls:'fa fa-times-circle',
    disabled:false,
    onClick:function() {obcChange();}
});
$('#obc-form-obatchange').combobox({
    width:'auto',
    valueField:'obt_id',
    textField:'obt_kombo',
    editable:false,
    panelHeight:'auto',
    queryParams:{
        db:getDB(),
        obat_id:obat_id,
        com_id:globalConfig.com_id
    },
    url:getRestAPI('obat/altobat'),
});
function obcChange(){
    var obatid= $('#obc-form-obatchange').combobox('getValue');
    var obattext= $('#obc-form-obatchange').combobox('getText');
    var kombo=obattext.split("*");
    var selectedKunRow = $('#kbi-grid').datagrid('getSelected');
    var rowIndex = $("#kbi-grid").datagrid("getRowIndex", selectedKunRow);
    ed = $('#kbi-grid').datagrid('getEditor', {index:rowIndex, field:'kbi_bea_id'});
    data = [{
        bea_id:obatid,
        bea_nama:kombo[0]
    }];
    $(ed.target).combobox('loadData', data);
    $(ed.target).combobox('setValue', obatid);
    ed = $('#kbi-grid').datagrid('getEditor', {index:rowIndex, field:'kbi_obtstok'});
    $(ed.target).textbox('setValue', parseInt(kombo[1]));
    $harga_alt=parseInt(kombo[2].substring(2))
    ed = $('#kbi-grid').datagrid('getEditor', {index:rowIndex, field:'kbi_harga'});
    $(ed.target).textbox('setValue', $harga_alt);
    $('#kbi-obt-dlg').dialog().dialog('close');
}
</script>