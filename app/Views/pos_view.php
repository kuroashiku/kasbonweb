<style>
.datagrid-header .datagrid-cell span{
    font-size:18px;
}		
@font-face {
    font-family: Rhomesfonts;
    src: url('public/css/font/CALIBRI.TTF');
}

*:not(.fa){
    font-family:Rhomesfonts;
    font-size: 18px;
}
.textbox .textbox-text{
    font-size: 20px;
}
.pos-menu {
    text-align:center;
    border-radius: 10px;
    cursor:pointer;
    width:99px;
    height:60px;
    background:#ff3a3a;
    display:inline-block;
    color:#fff;
    margin:0 3px 5px 0;
    border: none;
}
.pos-menu:hover {
    background: yellow;
    color: #000000;
    border-color: transparent;
    border: none;
}
.pos-icon {
    vertical-align:middle;
    font-size:30px;
    line-height:40px;
}   
.pos-menu-bayar {
    border-radius: 10px;
    text-align:center;
    font-weight:bold;
    cursor:pointer;
    width:345px;
    height:111px;
    font-size:40px;
    line-height:100px;
    background:#008ba0;
    display:inline-block;
    color:#fff;
}
.pos-menu-bayar-disabled {
    text-align:center;
    font-weight:bold;
    width:100%;
    height:100%;
    font-size:75px;
    line-height:150px;
    background:lightgrey;
    display:inline-block;
    color:grey;
}
.pos-menu-bayar:hover {
    background: #74b4da;
    color: #000000;
    border-color: transparent;
}
span.datebox{
    height:0px;
    border-color:transparent;
}
a.combo-arrow{
    padding-left:65px;
    background: #fff;
}
span.combo{
    width:65px !important;
}


</style>
<div class="easyui-layout" data-options="fit:true" id="post">
    <div data-options="region:'east',split:false" style="width:355px;">
        <div class="easyui-layout" data-options="fit:true" >
            <div data-options="region:'center',border:false">
                <div id="calculator"></div>
                <table width="100%" height="0%">
                    <tr>
                        <td width="0%" style="vertical-align:top">
                        </td>
                    </tr>
                    <tr>
                        <td width="0%" style="vertical-align:top">
                            <div id="totalkurang"></div>
                        </td>
                        <td width="100%" style="vertical-align:top">
                            <div id="totalkembalian"></div>
                        </td>
                    </tr>
                </table>
                <div id="pos-btn-05"></div>
                <div id="pos-btn-1"></div>
                <div id="pos-btn-2"></div>
                <div id="pos-btn-5"></div>
                <div id="pos-btn-10"></div>
                <div id="pos-btn-20"></div>
                <div id="pos-btn-50"></div>
                <div id="pos-btn-100"></div>
                <div id="pos-btn-now"></div>
                
            </div>
            <div data-options="region:'south',border:false"style="height:115px;">
                <div class="pos-menu-bayar" id="pos-bayar">
                    BAYAR
                </div>
                <div id="pos-jatuhtempo"></div>
            </div>
        </div>
    </div>
    <div data-options="region:'center'">
        <div class="easyui-layout" data-options="fit:true" id="pos-layout">
            <div data-options="region:'north',split:false" style="height:130px;background-color:#8ae0ed;">
                <table width="100%">
                <tr>
                    <td width="0%" style="padding-right:50px;padding-left:15px">
                        <div style="background: transparent;border-color:transparent;" id="pos-logopojok"></div>
                    </td>
                    <td width="0%" style="padding-right:5px">
                        <div id="pos-datenow"></div>
                    </td>
                    <td width="0%" style="padding-right:5px;white-space:nowrap">
                        <div class="easyui-panel" title="Kustomer" style="width:223;height:120;padding:50px 0px 0px 10px">
                            <input id="searchkustomer" style="width:200px;"></input>
                        </div>
                    </td>
                    <td width="100%" style="padding-right:5px;white-space:nowrap">
                        <div class="easyui-panel" title="Draft" style="width:223;height:120;padding:50px 0px 0px 10px">
                            <input id="searchdraft" style="width:200px"></input>
                        </div>
                    </td>
                </tr>
                </table>
            </div>
            <div data-options="region:'center'" id="tess">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'center'">
                        <div id="pos-grid"></div>
                        <div id="pos-grid-tb" style="padding:5px">
                            <input id="searchitem" style="width:300px"></input>
                            <div id="pos-item-gambar" title="Tambah Item"></div>
                            <div id="pos-kustomer-nama"></div>
                            
                            <div id="pos-btn-clear" title="Bersihkan modul"></div>
                            <div id="pos-btn-pin" title="Simpan ke draft"></div>
                            <div id="pos-btn-leftqty" title="Kurangkan kuantiti"></div>
                            <div id="pos-qty"></div>
                            <div id="pos-btn-rightqty" title="Tambahkan kuantiti"></div>
                            <div id="pos-btn-leftdsc" title="Kurangkan diskon"></div>
                            <div id="pos-dsc"></div>
                            <div id="pos-btn-rightdsc" title="Tambahkan diskon"></div>
                            <div id="pos-kustomer-id"></div>
                            <div id="pos-btn-satuan"></div>
                            <div id="pos-btn-sales">
                            </div>
                        </div>
                    </div>
                    <div data-options="region:'south'"style="height:70px">
                        <div class="easyui-layout" data-options="fit:true">
                            <div data-options="region:'center',border:false">
                                <button class="btn pos-menu" id="pos-btn-kustomer-add">
                                    <div class="fa fa-users pos-icon"></div>
                                </button>
                                <button class="btn pos-menu" id="pos-btn-item-add">
                                    <div class="fa fa-gift pos-icon"></div>
                                </button>
                                <button class="btn pos-menu" id="pos-btn-konversi">
                                    <div class="fa fa-boxes pos-icon"></div>
                                </button>
                                <button class="btn pos-menu" id="pos-btn-transaksi">
                                    <div class="fa fa-clipboard pos-icon"></div>
                                </button>
                                <button class="btn pos-menu" id="pos-btn-po">
                                    <div class="fa fa-luggage-cart pos-icon"></div>
                                </button>
                                <button class="btn pos-menu" id="pos-btn-receive">
                                    <div class="fa fa-handshake pos-icon"></div>
                                </button>
                            </div>
                            <div data-options="region:'east',border:false" style="width:330px;">
                                <div id="pos-total"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mainnotif" data-options="region:'east',split:true" style="width:0px">
                <div id="notif-grid"></div>
                <div id="notif-grid-tb" style="padding:5px">
                    <div id="notif-label"></div>
                </div>
            </div>
            <div id="kumpulan-dialog">
            <div id="pos-kustomer-add-dlg"></div>
            <div id="pos-item-add-dlg"></div>
            <div id="pos-item-gambar-dlg"></div>
            <div id="pos-konversi-dlg"></div>
            <div id="pos-transaksi-dlg"></div>
            <div id="pos-sales-dlg"></div>
            <div id="pos-po-dlg"></div>
            <div id="pos-rcv-dlg"></div>
            </div>
        </div>    
    </div>
</div>
<script>
    $('#pos-btn-kustomer-add').prop('disabled', true);
    $('#pos-btn-item-add').prop('disabled', true);
    $('#pos-btn-konversi').prop('disabled', true);
    $('#pos-btn-transaksi').prop('disabled', true);
    $('#pos-btn-po').prop('disabled', true);
    $('#pos-btn-receive').prop('disabled', true);
    if(globalConfig.login_data)
    {
        $('#pos-btn-kustomer-add').prop('disabled', false);
        $('#pos-btn-item-add').prop('disabled', false);
        $('#pos-btn-konversi').prop('disabled', false);
        $('#pos-btn-transaksi').prop('disabled', false);
        $('#pos-btn-po').prop('disabled', false);
        $('#pos-btn-receive').prop('disabled', false);  
    }
    var globalsatuan=[];
    globalharga=0;
    var globalnotif=[];
    globaluang=0;
    var globalkurang=0;
    var globalkembalian=0;
    var globalqty=0;
    var globaldsc=0;
    var globalid=0;
    var bayarflag=0;
    var jatuhtempoflag=0;
    var refreshIntervalId;
    $('#pos-jatuhtempo').datebox({
        width:110,
        editable:false,
        onSelect:function(date){
            jatuhtempoflag=1;
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            $.ajax({
                type:'POST',
                data:{rows:globalrow,
                    total:globalharga,
                    kas_id:globalConfig.login_data.data.kas_id,
                    kas_nama:globalConfig.login_data.data.kas_nama,
                    dicicil:1,
                    dibayar:globaluang,
                    kembalian:parseInt(globaluang)-parseInt(globalharga),
                    cus_id:parseInt($('#pos-kustomer-id').textbox('getValue'))?parseInt($('#pos-kustomer-id').textbox('getValue')):0,
                    lok_id:globalConfig.login_data.data.kas_lok_id,
                    catatan:'',
                    diskon:0,
                    disnom:0,
                    carabayar:'KAS',
                    sft_id:0,
                    jatuhtempo:y+'-'+m+'-'+d
                },
                url:getRestAPI('nota/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    $.ajax({
                        type:'POST',
                        data:{
                            cil_id:-1,
                            cil_not_id:obj.not_id,
                            cil_kekurangan:Math.abs(parseInt(globaluang)-parseInt(globalharga)),
                            cil_bunga:0,
                            cil_tagihan:Math.abs(parseInt(globaluang)-parseInt(globalharga)),
                            cil_cicilan:0,
                            cil_sisa:Math.abs(parseInt(globaluang)-parseInt(globalharga)),
                            cil_carabayar:'KAS'
                        },
                        url:getRestAPI('piutang/save')
                    })
                    resetall();
                    jatuhtempoflag=0;
                }
            })
        }
    });
    $('#pos-item-gambar').linkbutton({
        iconCls:'fa fa-cart-plus fa-lg',
        onClick:function() {posAddItemGambar();}
    });
    $('#pos-logopojok').panel({
        width:230,
        height:120,
        content:'<img src="<?= base_url('images/kasbon-logo-v2.png') ?>">'
    });
    var today = new Date();
    $('#pos-datenow').panel({
        width:223,
        height:120,
        title:'Tanggal',
        content:today.toLocaleDateString()
    }).css("padding","40px 0px 0px 10px").css("font-size","30px");
    $('#pos-btn-kustomer-add').click(function() {
        posAddKustomer();
    });
    $('#pos-btn-item-add').click(function() {
        posAddItem();
    });
    $('#pos-bayar').click(function() {
        posPay();
    });
    $('#pos-btn-konversi').click(function() {
        posKonversi();
    });
    $('#pos-btn-transaksi').click(function() {
        posTransaksi();
    });
    $('#pos-btn-po').click(function() {
        posPO();
    });
    $('#pos-btn-receive').click(function() {
        posRcv();
    });
    $('#pos-btn-sales').linkbutton({
        iconCls:'fa fa-chart-bar fa-lg',
        onClick:function() {posSales();}
    });
    $('#pos-btn-satuan').combobox({
        width:96,
        valueField:'sat_id',
        textField:'sat_nama',
        panelHeight:'auto',
        iconCls:'',
        data:[{"sat_id":0,"sat_nama":"Satuan"}],
        onClick:function(row) {
            var selectedRow = $('#pos-grid').datagrid('getSelected');
            var indexRow = $('#pos-grid').datagrid('getRowIndex', selectedRow);
            globalharga=0;
            $.each(globalrow,function(i,v){
                if(v.itm_id==selectedRow.itm_id)
                {
                    v.satuan0=row.sat_nama
                    v.total=row.sat_hrg;
                    v.satuan0hrg=row.sat_hrg;
                    v.satuan0hpp=row.sat_hpp;
                    v.satuan0of1=row.sat_of?row.sat_of:1;
                    v.qty=1;
                    v.diskon=0;
                    v.disnom=0;
                    v.konvidx=0;
                    $('#pos-qty').numberbox('setValue',parseInt(v.qty))
                    $('#pos-dsc').numberbox('setValue',parseInt(v.diskon));
                }
                globalharga=globalharga+parseInt(v.total)
            })
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            $('#pos-total').textbox('setValue',globalharga);
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-grid').datagrid('selectRow',indexRow);
        },
    });
    $('#pos-btn-clear').linkbutton({
        iconCls:'fa fa-brush fa-lg',
        onClick:function() {posClear();}
    });
    $('#pos-btn-pin').linkbutton({
        iconCls:'fa fa-thumbtack fa-lg',
        onClick:function() {posPin();}
    });
    $('#pos-btn-leftqty').linkbutton({
        iconCls:'fa fa-caret-left fa-lg',
        width:20,
        onClick:function() {
            var lqty=$('#pos-qty').numberbox('getValue');
            var selectedRow = $('#pos-grid').datagrid('getSelected');
            var indexRow = $('#pos-grid').datagrid('getRowIndex', selectedRow);
            globalharga=0;
            lqty=parseInt(lqty)-1
            if(lqty==0)lqty=0;
            $.each(globalrow,function(i,v){
                if(v.itm_id==globalid)
                {
                    v.qty=lqty;
                    v.total=parseInt(v.satuan0hrg)*v.qty*(1-(parseInt($('#pos-dsc').numberbox('getValue'))/100));
                }
                globalharga=globalharga+parseInt(v.total)
            })
            $('#pos-total').textbox('setValue',globalharga);
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            $('#pos-qty').numberbox('setValue',lqty);
            $('#pos-grid').datagrid('selectRow',indexRow);
        }
    });
    $('#pos-btn-rightqty').linkbutton({
        iconCls:'fa fa-caret-right fa-lg',
        width:20,
        onClick:function() {
            var rqty=$('#pos-qty').numberbox('getValue');
            var selectedRow = $('#pos-grid').datagrid('getSelected');
            var indexRow = $('#pos-grid').datagrid('getRowIndex', selectedRow);
            var globalharga=0;
            rqty=parseInt(rqty)+1
            $.each(globalrow,function(i,v){
                if(parseInt(v.itm_id)==parseInt(globalid))
                {
                    if(parseInt(v.itm_stok)<rqty){
                        $.messager.alert("Error kuantiti", "Maaf kuantiti item tidak boleh lebih dari stok");
                        rqty=parseInt(v.itm_stok)
                    }
                    v.qty=rqty;
                    v.total=parseInt(v.satuan0hrg)*parseInt(v.qty)*(1-(parseInt($('#pos-dsc').numberbox('getValue'))/100));
                }
                globalharga=parseInt(globalharga)+parseInt(v.total)
            })
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#pos-total').textbox('setValue',globalharga);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            $('#pos-qty').numberbox('setValue',rqty);
            $('#pos-grid').datagrid('selectRow',indexRow);
        }
    });
    $('#pos-btn-leftdsc').linkbutton({
        iconCls:'fa fa-caret-left fa-lg',
        width:20,  
        onClick:function() {
            var ldsc=$('#pos-dsc').numberbox('getValue');
            var selectedRow = $('#pos-grid').datagrid('getSelected');
            var indexRow = $('#pos-grid').datagrid('getRowIndex', selectedRow);
            globalharga=0;
            ldsc=parseInt(ldsc)-1
            if(ldsc==0)ldsc=0;
            $.each(globalrow,function(i,v){
                if(v.itm_id==globalid)
                {
                    v.diskon=ldsc;
                    v.total=parseInt(v.satuan0hrg)*(parseInt($('#pos-qty').numberbox('getValue')))*(1-(v.diskon/100));
                }
                globalharga=globalharga+parseInt(v.total)
            })
            $('#pos-total').textbox('setValue',globalharga);
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            $('#pos-dsc').numberbox('setValue',ldsc);
            $('#pos-grid').datagrid('selectRow',indexRow);
        }
    });
    $('#pos-btn-rightdsc').linkbutton({
        iconCls:'fa fa-caret-right fa-lg',
        width:20,
        onClick:function() {
            var rdsc=$('#pos-dsc').numberbox('getValue');
            var selectedRow = $('#pos-grid').datagrid('getSelected');
            var indexRow = $('#pos-grid').datagrid('getRowIndex', selectedRow);
            globalharga=0;
            rdsc=parseInt(rdsc)+1
            $.each(globalrow,function(i,v){
                if(v.itm_id==globalid)
                {
                    v.diskon=rdsc;
                    v.total=parseInt(v.satuan0hrg)*(parseInt($('#pos-qty').numberbox('getValue')))*(1-(v.diskon/100));
                }
                globalharga=globalharga+parseInt(v.total)
            })
            $('#pos-total').textbox('setValue',globalharga);
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            $('#pos-dsc').numberbox('setValue',rdsc);
            $('#pos-grid').datagrid('selectRow',indexRow);
        }
    });
    $('#notif-label').textbox({
        prompt:'Ditemukan multiitem',
        width:300, 
        readonly:true   
    });
    $('#pos-qty').numberbox({
        prompt:'Qty',
        width:60,  
    });
    $('#pos-qty').textbox('textbox').bind('keydown', function(e){
	    if (e.keyCode == 13){   // when press ENTER key, accept the inputed value.
            var selectedRow = $('#pos-grid').datagrid('getSelected');
            var indexRow = $('#pos-grid').datagrid('getRowIndex', selectedRow);
            globalharga=0;
            $.each(globalrow,function(i,v){
                if(v.itm_id==globalid)
                {
                    if(parseInt(v.itm_stok)<(parseInt($('#pos-qty').numberbox('getValue')))){
                        $.messager.alert("Error kuantiti", "Maaf kuantiti item tidak boleh lebih dari stok");
                        $('#pos-qty').numberbox('setValue',v.itm_stok);
                    }
                    v.qty=$('#pos-qty').numberbox('getValue');
                    v.total=parseInt(v.satuan0hrg)*(parseInt($('#pos-qty').numberbox('getValue')))*(1-(v.diskon/100));
                }
                globalharga+=parseInt(v.total)
            })
            $('#pos-total').textbox('setValue',globalharga);
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            $('#pos-grid').datagrid('selectRow',indexRow);
	   }
	});
    $('#pos-dsc').numberbox({
        prompt:'Diskon',
        width:80,  
    });
    $('#pos-dsc').textbox('textbox').bind('keydown', function(e){
        if (e.keyCode == 13){   // when press ENTER key, accept the inputed value.
            var selectedRow = $('#pos-grid').datagrid('getSelected');
            var indexRow = $('#pos-grid').datagrid('getRowIndex', selectedRow);
            globalharga=0;
            $.each(globalrow,function(i,v){
                if(v.itm_id==globalid)
                {
                    v.dsc=$('#pos-dsc').numberbox('getValue');
                    v.total=parseInt(v.satuan0hrg)*(parseInt($('#pos-qty').numberbox('getValue')))*(1-(v.diskon/100));
                }
                globalharga+=parseInt(v.total)
            })
            $('#pos-total').textbox('setValue',globalharga);
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            $('#pos-grid').datagrid('selectRow',indexRow);
    }
    });
    $('#pos-kustomer-nama').textbox({
        prompt:'Nama Kustomer',
        width:250, 
        readonly:true   
    });
    $('#pos-kustomer-id').textbox({
        width:0,
        readonly:true   
    });
    $('#pos-kustomer-nama').next().hide();
    $('#pos-kustomer-id').next().hide();
    $('#notif-label').textbox('textbox').css('background', 'rgb(245, 245, 245)');
    changeFieldBorderColor('#notif-label', 'rgb(245, 245, 245)');
    $('#calculator').textbox({
        width:'100%',
        height:60,
        align:'right',
        multiline:true,
        readonly:true   
    });
    $('#pos-total').textbox({
        width:'100%',
        height:63,
        prompt:'Total',
        multiline:true,
        readonly:true   
    });
    $('#totalkurang').textbox({
        width:'171',
        height:24,
        readonly:true   
    });
    $('#totalkembalian').textbox({
        width:'171',
        height:24,
        readonly:true   
    });
    $('#calculator').textbox('setValue','Dibayar=Rp.'+currencyFormat(globaluang)+',00');
    $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
    $('#totalkembalian').textbox('setValue',"kembalian="+currencyFormat(globalkembalian)+',00');
    $('#calculator').textbox('textbox').css('text-align', 'right').css('font-size', '28');
    $('#pos-total').textbox('textbox').css('text-align', 'right').css('font-size', '28');
    $('#searchitem').searchbox({
        searcher:function(value,name){
            if(value!='')
            {
                $.post(getRestAPI("item/read"), {
                    lok_id:globalConfig.login_data.data.kas_lok_id,
                    key_val:value
                },
                function(data) {
                    var obj=JSON.parse(data)
                    
                    if(obj.data.length>1)
                    {
                        $('#searchitem').textbox('disable');
                        $('#searchkustomer').textbox('disable');
                        $('#searchdraft').textbox('disable');
                        var strnotif="Ditemukan "+obj.data.length+" item yaitu: "
                        $.each(obj.data,function(i,v){
                            strnotif+=i+1+"."+v.itm_nama+", ";
                            v.qty=1;
                            v.diskon=0;
                            v.disnom=0;
                            v.konvidx=0;
                            v.total=v.itm_satuan1hrg;
                            v.satuan0hrg=v.itm_satuan1hrg;
                            v.satuan0=v.itm_satuan1;
                            v.satuan0hpp=v.itm_satuan1hpp;
                            v.satuan1hrg=v.itm_satuan1hrg;
                            v.satuan1=v.itm_satuan1;
                            v.satuan1hpp=v.itm_satuan1hpp;
                            v.satuan2hrg=v.itm_satuan2hrg;
                            v.satuan2=v.itm_satuan2;
                            v.satuan2hpp=v.itm_satuan2hpp;
                            v.satuan3hrg=v.itm_satuan3hrg;
                            v.satuan3=v.itm_satuan3;
                            v.satuan3hpp=v.itm_satuan3hpp;
                            v.satuan0of1=1;
                            v.id=v.itm_id;
                            v.nama=v.itm_nama;
                            v.tipe=1;
                            globalnotif.push(v);
                        })
                        $('#pos-layout').layout('panel','east').panel('resize',{
                            width: window.innerWidth/4
                        });
                        $('#pos-layout').layout('resize');
                        //$('#pos-layout').layout('expand','east');
                        $('#globalkurang').textbox('setValue',globalharga)
                        strnotif+="<br> Pastikan memasukkan data yang lengkap, atau yang mudah dikenali misalkan 'degan' "
                        //$.messager.alert("Error multi item", strnotif);
                        $('#notif-grid').datagrid('loadData',globalnotif);
                    }
                    else if(obj.data.length<1)
                    {
                        $.messager.alert("Error data kosong", "Maaf item tidak terdeteksi");
                    }
                    else{
                        obj.data[0].qty=1;
                        obj.data[0].diskon=0;
                        obj.data[0].disnom=0;
                        obj.data[0].konvidx=0;
                        obj.data[0].total=obj.data[0].itm_satuan1hrg;
                        obj.data[0].satuan0hrg=obj.data[0].itm_satuan1hrg;
                        obj.data[0].satuan0=obj.data[0].itm_satuan1;
                        obj.data[0].satuan0hpp=obj.data[0].itm_satuan1hpp;
                        obj.data[0].satuan1hrg=obj.data[0].itm_satuan1hrg;
                        obj.data[0].satuan1=obj.data[0].itm_satuan1;
                        obj.data[0].satuan1hpp=obj.data[0].itm_satuan1hpp;
                        obj.data[0].satuan2hrg=obj.data[0].itm_satuan2hrg;
                        obj.data[0].satuan2=obj.data[0].itm_satuan2;
                        obj.data[0].satuan2hpp=obj.data[0].itm_satuan2hpp;
                        obj.data[0].satuan3hrg=obj.data[0].itm_satuan3hrg;
                        obj.data[0].satuan3=obj.data[0].itm_satuan3;
                        obj.data[0].satuan3hpp=obj.data[0].itm_satuan3hpp;
                        obj.data[0].satuan0of1=1;
                        obj.data[0].id=obj.data[0].itm_id;
                        obj.data[0].nama=obj.data[0].itm_nama;
                        obj.data[0].tipe=1;
                        $.each(globalrow,function(i,v){
                            if(v.itm_id==obj.data[0].itm_id)
                            {
                                itemflag=1;
                            }
                        })
                        if(itemflag==0){
                            if(parseInt(obj.data[0].itm_stok)>0)
                            {
                                globalharga+=parseInt(obj.data[0].satuan1hrg)
                                globalrow.push(obj.data[0]);
                                $('#pos-total').textbox('setValue',globalharga);
                                $('#pos-grid').datagrid('loadData',globalrow);
                                var lastIndex = $('#pos-grid').datagrid('getRows').length - 1;
                                $('#pos-grid').datagrid('selectRow',lastIndex);
                                $('#globalkurang').textbox('setValue',globalharga)
                                $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
                                $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
                            }
                            else {
                                $('#searchitem').searchbox('setValue','')
                                $.messager.alert("Error item kosong", "Item habis");  
                            }
                        }
                        else{
                            $.messager.alert("Error item kembar", "Item sudah terdaftar");  
                            itemflag=0;
                        }
                    }
                })
            }
        },
        height: 'auto',
        prompt:'Masukkan Item'
    });
    $('#searchkustomer').searchbox({
        searcher:function(value,name){
            if(value!='')
            {
                $.post(getRestAPI("customer/search"), {
                    com_id:globalConfig.login_data.data.kas_com_id,
                    q:value
                },
                function(data) {
                    var obj=JSON.parse(data)
                    if(obj.data.length>1)
                    {
                        $('#searchitem').textbox('disable');
                        $('#searchkustomer').textbox('disable');
                        $('#searchdraft').textbox('disable');
                        var strnotif="Ditemukan "+obj.data.length+" nama yaitu: "
                        $.each(obj.data,function(i,v){
                            strnotif+=i+1+"."+v.cus_nama+", ";
                            v.id=v.cus_id;
                            v.nama=v.cus_nama;
                            v.tipe=2;
                            globalnotif.push(v);
                        })
                        $('#pos-layout').layout('panel','east').panel('resize',{
                            width: window.innerWidth/4
                        });
                        $('#pos-layout').layout('resize');
                        // $('#pos-layout').layout('expand','east');
                        strnotif+="<br> Pastikan memasukkan nama yang lengkap"
                        //$.messager.alert("Error multi nama kustomer", strnotif);
                        $('#notif-grid').datagrid('loadData',globalnotif);
                    }
                    else if(obj.data.length<1)
                    {
                        $.messager.alert("Error data kosong", "Maaf item tidak terdeteksi");
                    }
                    else{
                        $('#searchkustomer').textbox('setValue',obj.data[0].cus_nama)
                        $('#pos-kustomer-id').textbox('setValue',obj.data[0].cus_id)
                    }
                })
            }
        },
        prompt:'Masukkan Nama'
    });
    $('#searchdraft').searchbox({
        searcher:function(value,name){
            if(value!='')
            {
                $.post(getRestAPI("draftnota/read"), {
                    kas_id:globalConfig.login_data.data.kas_id,
                    q:value
                },
                function(data) {
                    var obj=JSON.parse(data)
                    if(obj.data.length>1)
                    {
                        $('#searchitem').textbox('disable');
                        $('#searchkustomer').textbox('disable');
                        $('#searchdraft').textbox('disable');
                        var strnotif="Ditemukan "+obj.data.length+" draft yaitu: "
                        var notifnama="";
                        
                        $.each(obj.data,function(i,v){
                            
                            if(v.cus_nama==null)
                            v.cus_nama='NoNama'
                            strnotif+=i+1+"."+v.cus_nama+", ";
                            notifnama+=v.cus_nama+" Beritem ";
                            v.id=v.dot_id;
                            v.tipe=3;
                            globalnotif.push(v);
                            $.each(obj.data[i].notaitems,function(ii,vi){
                                vi.dot_id=v.dot_id
                                notifnama+=vi.itm_nama+", "
                            });
                            v.nama=notifnama;
                            if(obj.data.dot_id)
                            globalnotif.push(obj.data);
                            notifnama='';
                        })
                        $('#pos-layout').layout('panel','east').panel('resize',{
                            width: window.innerWidth/4
                        });
                        $('#pos-layout').layout('resize');
                        // $('#pos-layout').layout('expand','east');
                        strnotif+="<br> Pastikan memilih draft didalam draft yang hanya 1 memesannya atau cari catatan"
                        //$.messager.alert("Error multi draft", strnotif);
                        $('#notif-grid').datagrid('loadData',globalnotif);
                    }
                    else if(obj.data.length<1)
                    {
                        $.messager.alert("Error data kosong", "Maaf item tidak terdeteksi");
                    }
                    else{
                        globalrow=[];
                        $('#pos-grid').datagrid('loadData',globalrow);
                        $.each(obj.data[0].notaitems,function(i,v){
                            v.disnom=0;
                            v.konvidx=0;
                            v.total=parseInt(v.satuan1hrg)*(parseInt(v.qty))*(1-(v.diskon/100));
                            v.satuan0hrg=v.satuan1hrg;
                            v.satuan0=v.satuan1;
                            v.satuan0hpp=v.satuan1hpp;
                            v.id=v.itm_id;
                            v.nama=v.itm_nama;
                            v.dot_id=obj.data[0].dot_id
                            v.tipe=3;
                            globalrow.push(v);
                            globalharga+=parseInt(v.total)
                            
                        })
                        $('#searchkustomer').textbox('setValue',obj.data[0].cus_nama)
                        $('#pos-kustomer-id').textbox('setValue',obj.data[0].cus_id)
                        $('#pos-total').textbox('setValue',globalharga);
                        
                        $('#pos-grid').datagrid('loadData',globalrow);
                        // $.each(globalrow,function(i,v){
                        //     $.ajax({
                        //         type:'POST',
                        //         data:{lok_id:globalConfig.login_data.data.kas_lok_id,
                        //         key_val:v.itm_nama
                        //         },
                        //         url:getRestAPI('item/read'),
                        //         success:function(retval) {
                        //             var obj = JSON.parse(retval);
                        //             v.stok2=obj.data[0].itm_stok
                        //             if(i==2)
                        //             globalrow.pop();
                        //         }
                        //     });
                        // })
                        // $('#pos-grid').datagrid('loadData',globalrow);
                        // console.log(globalrow[2])
                        $('#calculator').textbox('setValue','Dibayar=Rp.'+currencyFormat(globaluang)+',00')
                        $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00')
                        $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
                    }
                })
            }
        },
        prompt:'Masukkan Draft'
    });
    $('#searchitem').textbox('textbox').css('font-size', '20px');
    $('#searchkustomer').textbox('textbox').css('font-size', '20px');
    $('#searchdraft').textbox('textbox').css('font-size', '20px');
    $('#pos-qty').textbox('textbox').css('font-size', '20px');
    $('#pos-dsc').textbox('textbox').css('font-size', '20px');
    $('#notif-grid').datagrid({
        border:false,
        toolbar:'#notif-grid-tb',
        fit:true,
        singleSelect:true,
        columns:[[{
            field:'id',
            title:'ID',
            resizable:false,
            width:35,
            hidden:true
        },
        {
            field:'nama',
            title:'Nama',
            resizable:false,
            width:350,
        },
        {
            field:'tipe',
            title:'Tipe',
            resizable:false,
            width:35,
            hidden:true
        }]],
        data:globalnotif,
        onSelect:function(index,row){
            $('#searchitem').textbox('enable');
            $('#searchkustomer').textbox('enable');
            $('#searchdraft').textbox('enable');
            if(row.tipe==1)
            {
                var itemflagnotif=0;
                $.each(globalrow,function(i,v){
                    if(v.itm_id==row.itm_id)
                    {
                        itemflagnotif=1;
                    }
                })
                if(itemflagnotif==0){
                    if(parseInt(row.itm_stok)>0)
                    {
                        globalrow.push(row)
                        globalharga=globalharga+parseInt(row.satuan1hrg);
                        $('#pos-grid').datagrid('loadData',globalrow);
                        globalnotif=[];
                        $('#notif-grid').datagrid('loadData',globalnotif);
                        $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00')
                        $('#searchitem').searchbox('setValue','');
                        $('#pos-total').textbox('setValue',globalharga);
                        $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
                    }
                    else {
                        $('#searchitem').searchbox('setValue','');
                        globalnotif=[];
                        $('#notif-grid').datagrid('loadData',globalnotif);
                        $.messager.alert("Error item kosong", "Item habis");  
                    }
                }
                else{
                    globalnotif=[];
                    $('#notif-grid').datagrid('loadData',globalnotif);
                    $.messager.alert("Error item kembar", "Item sudah terdaftar");  
                    itemflag=0;
                }
                
            }
            else if(row.tipe==2){
                $('#searchkustomer').textbox('setValue',row.cus_nama)
                $('#pos-kustomer-id').textbox('setValue',row.cus_id)
                $('#searchitem').searchbox('setValue','')
                globalnotif=[];
                $('#notif-grid').datagrid('loadData',globalnotif);
            }
            else if(row.tipe==3)
            {
                globalrow=[];
                $.each(row.notaitems,function(i,v){
                    globalharga+=parseInt(v.total);
                    globalrow.push(v);
                });
                $('#searchkustomer').textbox('setValue',row.cus_nama)
                $('#pos-kustomer-id').textbox('setValue',row.cus_id)
                $('#pos-grid').datagrid('loadData',globalrow);
                globalnotif=[];
                $('#notif-grid').datagrid('loadData',globalnotif);
                $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00')
                $('#pos-total').textbox('setValue',globalharga);
                $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
                $('#searchitem').searchbox('setValue','')
            }
            // $('#pos-layout').layout('collapse','east');
            $('#pos-layout').layout('panel','east').panel('resize',{
                width: 1
            });
            $('#pos-layout').layout('resize');
        } 
    })
    $('#pos-grid').datagrid({
        border:false,
        toolbar:'#pos-grid-tb',
        fit:true,
        singleSelect:true,
        columns:[[{
            field:'itm_id',
            title:'ID',
            resizable:false,
            width:35,
            hidden:true
        },{
            field:'itm_nama',
            title:'Nama',
            resizable:false,
            width:200
        },{
            field:'qty',
            title:'Qty',
            resizable:false,
            width:50
        },{
            field:'diskon',
            title:'Diskon',
            resizable:false,
            width:70,
            formatter: function(value, row) {return row.diskon+(parseInt(row.diskon)>=0?'%':'')}
        },{
            field:'satuan1hrg',
            title:'Harga',
            resizable:false,
            width:150,
            align:"right",
            formatter: function(value, row) {return value=='Total:'?'Total:':'Rp.'+currencyFormat(row.satuan1hrg)+',00'}
        },{
            field:'total',
            title:'Total',
            resizable:false,
            width:150,
            align:"right",
            formatter: function(value, row) {return 'Rp.'+currencyFormat(row.total)+',00'}
        }]],
        data:globalrow,
        // onLoadSuccess:function(data) {
        //     console.log(data)
        //     $.ajax({
        //         type:'POST',
        //         data:{lok_id:globalConfig.login_data.data.kas_lok_id,
        //         key_val:data.itm_nama
        //         },
        //         url:getRestAPI('item/read'),
        //         success:function(retval) {
        //             var obj = JSON.parse(retval);
                    
        //             if(parseInt(obj.data[0].itm_stok)<=parseInt(data.qty))
        //             console.log();
        //             $('#pos-grid').datagrid('loadData',globalrow);
        //         }
        //     });
        // },
        onSelect:function(index,row){
            console.log(row)
            $('#pos-qty').numberbox('setValue',parseInt(row.qty))
            $('#pos-dsc').numberbox('setValue',parseInt(row.diskon));
            globalsatuan=[];
            globalid=row.itm_id;
            globalsatuan.push({"sat_id":1,"sat_nama":row.satuan1,"sat_hpp":row.satuan1hpp,"sat_hrg":row.satuan1hrg});
            if(row.satuan2)
            globalsatuan.push({"sat_id":2,"sat_nama":row.satuan2,"sat_hpp":row.satuan2hpp,"sat_hrg":row.satuan2hrg,"sat_of":row.satuan2of1});
            if(row.satuan3)
            globalsatuan.push({"sat_id":3,"sat_nama":row.satuan3,"sat_hpp":row.satuan3hpp,"sat_hrg":row.satuan3hrg,"sat_of":row.satuan3of1});
            
            $('#pos-btn-satuan').combobox('loadData',globalsatuan);
            if(row.satuan0==row.satuan1)
            $('#pos-btn-satuan').combobox('setText',row.satuan1);
            else if(row.satuan0==row.satuan2)
            $('#pos-btn-satuan').combobox('setText',row.satuan2);
            else if(row.satuan0==row.satuan3)
            $('#pos-btn-satuan').combobox('setText',row.satuan3);
        }
    });
    $('#pos-btn-05').linkbutton({
        text:'C',
        height:111,
        width:111,
        onClick:function() {
            globaluang=0;
            $('#calculator').textbox('setValue',"Dibayar="+'Rp.'+currencyFormat(globaluang)+',00');
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#totalkembalian').textbox('setValue',"kembalian="+currencyFormat(globalkembalian)+',00');
        }
    }).css('text-align', 'center').css('background-color','#ed0000');
    $('#pos-btn-1').linkbutton({
        text:'1.000',
        height:111,
        width:111,
        onClick:function() {
            bayaruang(1000)
        }
    }).css('text-align', 'center').css('background-color','#eada85');
    $('#pos-btn-2').linkbutton({
        text:'2.000',
        height:111,
        width:111,
        onClick:function() {
            bayaruang(2000)
        }
    }).css('text-align', 'center').css('background-color','#c9bfbe');
    $('#pos-btn-5').linkbutton({
        text:'5.000',
        height:111,
        width:111,
        onClick:function() {
            bayaruang(5000)
        }
    }).css('text-align', 'center').css('background-color','#eac398');
    $('#pos-btn-10').linkbutton({
        text:'10.000',
        height:111,
        width:111,
        onClick:function() {
            bayaruang(10000)
        }
    }).css('text-align', 'center').css('background-color','#9c4b85');
    $('#pos-btn-20').linkbutton({
        text:'20.000',
        height:111,
        width:111,
        onClick:function() {
            bayaruang(20000)
        }
    }).css('text-align', 'center').css('background-color','#92d1c9');
    $('#pos-btn-50').linkbutton({
        text:'50.000',
        height:111,
        width:111,
        onClick:function() {
            bayaruang(50000)
        }
    }).css('text-align', 'center').css('background-color','#4264a1');
    $('#pos-btn-100').linkbutton({
        text:'100.000',
        height:111,
        width:111,
        onClick:function() {
            bayaruang(100000)
        }
    }).css('text-align', 'center').css('background-color','#da6c6f');
    $('#pos-btn-now').linkbutton({
        text:'0',
        height:111,
        width:111,
        onClick:function() {
            bayaruang(globalharga)
        }
    }).css('text-align', 'center').css('background-color','#0db700');
    function posClear(){
       resetall();
    }
    function posPin(){
        if(globalrow[0].dot_id){
            $.ajax({
                type:'POST',
                data:{dot_id:globalrow[0].dot_id
                },
                url:getRestAPI('draftnota/delete'),
            })
        }
        $.ajax({
            type:'POST',
            data:{rows:globalrow,
                total:globalharga,
                kas_id:globalConfig.login_data.data.kas_id,
                kas_nama:globalConfig.login_data.data.kas_nama,
                cus_id:$('#pos-kustomer-id').textbox('getValue')==''?0: $('#pos-kustomer-id').textbox('getValue'),
                lok_id:globalConfig.login_data.data.kas_lok_id,
                catatan:'',
                diskon:0,
                disnom:0
            },
            url:getRestAPI('draftnota/save'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                resetall();
            }
        })
    }
    function bayaruang(uang){
        globaluang=globaluang+uang;
        var counter=0;
        if((parseInt(globalharga)>parseInt(globaluang))&&parseInt($('#pos-kustomer-id').textbox('getValue'))){
            setTimeout(function() {
                bayarflag=1;
                $('#pos-bayar').text("BAYAR DP");
            }, 5000);
        }
        $('#calculator').textbox('setValue','Dibayar=Rp.'+currencyFormat(globaluang)+',00')
        var kurang=globalharga-globaluang;
        var kembali=0;
        if(kurang<0){
            kurang=0;
            kembali=Math.abs(globalharga-globaluang);
        }
        else kembali=0;
        globalkembalian=kembali
        $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(kurang)+',00')
        $('#totalkembalian').textbox('setValue',"kembalian="+'Rp.'+currencyFormat(globalkembalian)+',00')
    }
    function posPay(){
        if(globaluang<=0)
            $.messager.alert("Error tidak bayar", "Mulailah membayar");
        else if((parseInt(globalharga)>parseInt(globaluang))&&!parseInt($('#pos-kustomer-id').textbox('getValue'))){
            $.messager.alert("Error kurang bayar", "Pembayaran kurang");
        }
        else if((parseInt(globalharga)>parseInt(globaluang))&&parseInt($('#pos-kustomer-id').textbox('getValue'))){
            if(parseInt(globaluang)>=20000){
                if(bayarflag==1)
                {
                    $('#pos-jatuhtempo').datebox('showPanel');
                    bayarflag=0;
                    if(globalrow[0].dot_id){
                        $.ajax({
                            type:'POST',
                            data:{dot_id:globalrow[0].dot_id
                            },
                            url:getRestAPI('draftnota/delete'),
                        })
                    } 
                    refreshIntervalId=setInterval(() => {
                        if(jatuhtempoflag==0)
                        $('#pos-jatuhtempo').datebox('showPanel');
                    }, 1000);
                }
                else
                $.messager.alert("Error Waktu DP", "Sabar, tunggu 5 detik");
            }
            else if(parseInt(globaluang)<20000){
                $.messager.alert("Error Ingin DP", "Pembayaran harus 20000 supaya bisa menDP");
            }
        }
        else if(parseInt(globalharga)<=parseInt(globaluang)){
            if(globalrow[0].dot_id){
                $.ajax({
                    type:'POST',
                    data:{dot_id:globalrow[0].dot_id
                    },
                    url:getRestAPI('draftnota/delete'),
                })
            }
            $.ajax({
                type:'POST',
                data:{rows:globalrow,
                    total:globalharga,
                    kas_id:globalConfig.login_data.data.kas_id,
                    kas_nama:globalConfig.login_data.data.kas_nama,
                    dibayar:globaluang,
                    kembalian:globalkembalian,
                    cus_id:parseInt($('#pos-kustomer-id').textbox('getValue'))?parseInt($('#pos-kustomer-id').textbox('getValue')):0,
                    lok_id:globalConfig.login_data.data.kas_lok_id,
                    catatan:'',
                    diskon:0,
                    disnom:0,
                    carabayar:'KAS',
                    sft_id:0,
                    dicicil:0
                },
                url:getRestAPI('nota/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    resetall();
                }
            })
        }
    }
    function resetall(){
        globalrow=[];
        globalharga=0;
        globalkembalian=0;
        globaluang=0;
        bayarflag=0;
        $('#pos-bayar').text("BAYAR");
        $('#calculator').textbox('setValue',"Dibayar="+'Rp.'+currencyFormat(globaluang)+',00');
        $('#pos-grid').datagrid('loadData',globalrow);
        $('#pos-total').textbox('setValue',globalharga);
        globalnotif=[];
        $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
        $('#totalkembalian').textbox('setValue',"kembalian="+currencyFormat(globalkembalian)+',00');
        $('#notif-grid').datagrid('loadData',globalnotif);
        $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
        $('#pos-qty').numberbox('setValue','');
        $('#pos-dsc').numberbox('setValue','');
        $('#searchkustomer').searchbox('setValue','');
        $('#searchitem').searchbox('setValue','');
        $('#searchdraft').searchbox('setValue','');
        $('#pos-btn-satuan').combobox('loadData',[{"sat_id":0,"sat_nama":"Satuan"}])
        $('#pos-btn-satuan').combobox('setValue',0);
        clearInterval(refreshIntervalId);
    }
    function posAddKustomer(){
        $('#pos-kustomer-add-dlg').dialog({
            title:'Master Kustomer',
            width:455,
            height:400,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'pos/customer'
        });
    }
    function posAddItem(){
        $('#pos-item-add-dlg').dialog({
            title:'Master Item',
            width:925,
            height:600,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'pos/item'
        });
    }
    function posAddItemGambar(){
        $('#pos-item-gambar-dlg').dialog({
            title:'Tambah Item',
            width:455,
            height:400,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'pos/posgambar'
        });
    }
    function posKonversi(){
        $('#pos-konversi-dlg').dialog({
            title:'Master Konversi',
            width:925,
            height:600,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'pos/konversi'
        });
    }
    function posTransaksi(){
        $('#pos-transaksi-dlg').dialog({
            title:'Master Transaksi',
            width:800,
            height:400,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'pos/transaksi'
        });
    }
    function posSales(){
        $('#pos-sales-dlg').dialog({
            title:'Master Sales',
            width:800,
            height:400,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'pos/sales'
        });
    }
    function posPO(){
        $('#pos-po-dlg').dialog({
            title:'Master PO',
            width:800,
            height:400,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'pos/po'
        });
    }
    function posRcv(){
        $('#pos-rcv-dlg').dialog({
            title:'Master Receive',
            width:800,
            height:400,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'pos/receive'
        });
    }
    ///////fungsi penunjang
    function changeFieldBorderColor(field, color){
        var t = $(field);
        var el = t.data('textbox') ? t.next() : $(t);
        el.css('border-color', color);
    }
</script>