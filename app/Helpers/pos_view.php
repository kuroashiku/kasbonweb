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
    font-size: 18px;
}
.pos-menu {
    text-align:center;
    cursor:pointer;
    width:100px;
    height:60px;
    background:#ff3a3a;
    display:inline-block;
    color:#fff;
    margin:0 3px 5px 0;
}
.pos-menu:hover {
    background: yellow;
    color: #000000;
    border-color: transparent;
}
.pos-icon {
    vertical-align:middle;
    font-size:30px;
    line-height:60px;
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
</style>
<div class="easyui-layout" data-options="fit:true" id="post">
    <div data-options="region:'east',split:false" style="width:350px;">
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
        <div class="pos-menu-bayar" id="pos-bayar">
            BAYAR
        </div>
    </div>
    <div data-options="region:'center'">
        <div class="easyui-layout" data-options="fit:true" id="pos-layout">
            <div data-options="region:'north',split:false" style="height:32px">
                <input id="searchitem" style="width:300px"></input>
                <input id="searchkustomer" style="width:300px"></input>
                <input id="searchdraft" style="width:300px"></input>
            </div>
            <div data-options="region:'center'">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'center'">
                        <div id="pos-grid"></div>
                        <div id="pos-grid-tb" style="padding:5px">
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
                        </div>
                    </div>
                    <div data-options="region:'south'"style="height:70px">
                        <div class="easyui-layout" data-options="fit:true">
                            <div data-options="region:'center',border:false">
                                <div class="pos-menu" id="pos-btn-kustomer-add">
                                    <div class="fa fa-user-plus pos-icon"></div>
                                </div>
                            </div>
                            <div data-options="region:'east',border:false" style="width:300px;">
                                <div id="pos-total"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div data-options="region:'east',split:true, collapsed: true" style="width:350px">
                <div id="notif-grid"></div>
                <div id="notif-grid-tb" style="padding:5px">
                    <div id="notif-label"></div>
                </div>
            </div>
            <div id="pos-kustomer-add-dlg"></div>
            <div id="pos-item-gambar-dlg"></div>
        </div>    
    </div>
</div>
<script>
    
    var globalnotif=[];
    var globaluang=0;
    var globalkurang=0;
    var globalkembalian=0;
    var globalqty=0;
    var globaldsc=0;
    var globalid=0;
    globalharga=0;
    $('#pos-item-gambar').linkbutton({
        iconCls:'fa fa-cart-plus fa-lg',
        onClick:function() {posAddItemGambar();}
    });
    $('#pos-btn-kustomer-add').click(function() {
        posAddKustomer();
    });
    $('#pos-bayar').click(function() {
        posPay();
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
            globalharga=0;
            lqty=parseInt(lqty)-1
            if(lqty==0)lqty=0;
            $.each(globalrow,function(i,v){
                if(v.itm_id==globalid)
                {
                    v.qty=lqty;
                    v.total=parseInt(v.satuan1hrg)*v.qty*(1-(parseInt($('#pos-dsc').numberbox('getValue'))/100));
                }
                globalharga=globalharga+parseInt(v.total)
            })
            $('#pos-total').textbox('setValue',globalharga);
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            $('#pos-qty').numberbox('setValue',lqty);
        }
    });
    $('#pos-btn-rightqty').linkbutton({
        iconCls:'fa fa-caret-right fa-lg',
        width:20,
        onClick:function() {
            var rqty=$('#pos-qty').numberbox('getValue');
            globalharga=0;
            rqty=parseInt(rqty)+1
            $.each(globalrow,function(i,v){
                if(v.itm_id==globalid)
                {
                    v.qty=rqty;
                    v.total=parseInt(v.satuan1hrg)*v.qty*(1-(parseInt($('#pos-dsc').numberbox('getValue'))/100));
                }
                globalharga=globalharga+parseInt(v.total)
            })
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#pos-total').textbox('setValue',globalharga);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            $('#pos-qty').numberbox('setValue',rqty);
        }
    });
    $('#pos-btn-leftdsc').linkbutton({
        iconCls:'fa fa-caret-left fa-lg',
        width:20,  
        onClick:function() {
            var ldsc=$('#pos-dsc').numberbox('getValue');
            globalharga=0;
            ldsc=parseInt(ldsc)-1
            if(ldsc==0)ldsc=0;
            $.each(globalrow,function(i,v){
                if(v.itm_id==globalid)
                {
                    v.diskon=ldsc;
                    v.total=parseInt(v.satuan1hrg)*(parseInt($('#pos-qty').numberbox('getValue')))*(1-(v.diskon/100));
                }
                globalharga=globalharga+parseInt(v.total)
            })
            $('#pos-total').textbox('setValue',globalharga);
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            $('#pos-dsc').numberbox('setValue',ldsc);
        }
    });
    $('#pos-btn-rightdsc').linkbutton({
        iconCls:'fa fa-caret-right fa-lg',
        width:20,
        onClick:function() {
            var rdsc=$('#pos-dsc').numberbox('getValue');
            
            globalharga=0;
            rdsc=parseInt(rdsc)+1
            $.each(globalrow,function(i,v){
                if(v.itm_id==globalid)
                {
                    v.diskon=rdsc;
                    v.total=parseInt(v.satuan1hrg)*(parseInt($('#pos-qty').numberbox('getValue')))*(1-(v.diskon/100));
                }
                globalharga=globalharga+parseInt(v.total)
            })
            $('#pos-total').textbox('setValue',globalharga);
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            $('#pos-dsc').numberbox('setValue',rdsc);
        }
    });
    $('#notif-label').textbox({
        prompt:'Ditemukan beberapa item dari pencarian',
        width:300, 
        readonly:true   
    });
    $('#pos-qty').numberbox({
        prompt:'Qty',
        width:60,  
    });
    $('#pos-qty').textbox('textbox').bind('keydown', function(e){
	    if (e.keyCode == 13){   // when press ENTER key, accept the inputed value.
            
            globalharga=0;
            $.each(globalrow,function(i,v){
                if(v.itm_id==globalid)
                {
                    v.qty=$('#pos-qty').numberbox('getValue');
                    v.total=parseInt(v.satuan1hrg)*(parseInt($('#pos-qty').numberbox('getValue')))*(1-(v.diskon/100));
                }
                globalharga+=parseInt(v.total)
            })
            $('#pos-total').textbox('setValue',globalharga);
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
	   }
	});
    $('#pos-dsc').numberbox({
        prompt:'Diskon',
        width:60,  
    });
    $('#pos-dsc').textbox('textbox').bind('keydown', function(e){
        if (e.keyCode == 13){   // when press ENTER key, accept the inputed value.
            
            globalharga=0;
            $.each(globalrow,function(i,v){
                if(v.itm_id==globalid)
                {
                    v.dsc=$('#pos-dsc').numberbox('getValue');
                    v.total=parseInt(v.satuan1hrg)*(parseInt($('#pos-qty').numberbox('getValue')))*(1-(v.diskon/100));
                }
                globalharga+=parseInt(v.total)
            })
            $('#pos-total').textbox('setValue',globalharga);
            $('#pos-grid').datagrid('loadData',globalrow);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
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
                        var strnotif="Ditemukan "+obj.data.length+" item yaitu: "
                        $.each(obj.data,function(i,v){
                            strnotif+=i+1+"."+v.itm_nama+", ";
                            v.qty=1;
                            v.diskon=0;
                            v.disnom=0;
                            v.konvidx=0;
                            v.total=v.itm_satuan1hrg;
                            v.satuan1hrg=v.itm_satuan1hrg;
                            v.satuan1=v.itm_satuan1;
                            v.satuan1hpp=v.itm_satuan1hpp;
                            v.id=v.itm_id;
                            v.nama=v.itm_nama;
                            v.tipe=1;
                            globalnotif.push(v);
                            globalharga+=parseInt(v.itm_satuan1hrg)
                        })
                        // if ($('#pos-layout').layout('panel','east').panel('options').collapsed)
                        // $('#pos-layout').layout('panel','expandEast').panel('open');
                        // $('#pos-layout').layout('resize');
                        //$('#pos-layout').layout('panel', 'expandEast').panel('open')
                        //$('#pos-layout').layout('panel', 'east').panel('setTitle', 'AAAAAAAAA');
                        $('#pos-layout').layout('expand','east');
                        $('#globalkurang').textbox('setValue',globalharga)
                        strnotif+="<br> Pastikan memasukkan data yang lengkap, atau yang mudah dikenali misalkan 'degan' "
                        $.messager.alert("Error multi item", strnotif);
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
                        obj.data[0].satuan1hrg=obj.data[0].itm_satuan1hrg;
                        obj.data[0].satuan1=obj.data[0].itm_satuan1;
                        obj.data[0].satuan1hpp=obj.data[0].itm_satuan1hpp;
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
                        globalharga+=parseInt(obj.data[0].satuan1hrg)
                        globalrow.push(obj.data[0]);
                        $('#pos-total').textbox('setValue',globalharga);
                        $('#pos-grid').datagrid('loadData',globalrow);
                        $('#globalkurang').textbox('setValue',globalharga)
                        $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
                        $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
                        }
                        else{
                            $.messager.alert("Error item kembar", "Item sudah terdaftar");  
                            itemflag=0;
                        }
                        
                    }
                })
            }
        },
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
                        var strnotif="Ditemukan "+obj.data.length+" nama yaitu: "
                        $.each(obj.data,function(i,v){
                            strnotif+=i+1+"."+v.cus_nama+", ";
                            v.id=v.cus_id;
                            v.nama=v.cus_nama;
                            v.tipe=2;
                            globalnotif.push(v);
                        })
                        strnotif+="<br> Pastikan memasukkan nama yang lengkap"
                        $.messager.alert("Error multi nama kustomer", strnotif);
                        $('#notif-grid').datagrid('loadData',globalnotif);
                    }
                    else if(obj.data.length<1)
                    {
                        $.messager.alert("Error data kosong", "Maaf item tidak terdeteksi");
                    }
                    else{
                        $('#pos-kustomer-nama').textbox('setValue',obj.data[0].cus_nama)
                        $('#pos-kustomer-id').textbox('setValue',obj.data[0].cus_id)
                    }
                })
            }
        },
        prompt:'Masukkan Nama Customer'
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
                       
                        strnotif+="<br> Pastikan memilih draft didalam draft yang hanya 1 memesannya atau cari catatan"
                        $.messager.alert("Error multi draft", strnotif);
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
                            v.satuan1hrg=v.satuan1hrg;
                            v.satuan1=v.satuan1;
                            v.satuan1hpp=v.satuan1hpp;
                            v.id=v.itm_id;
                            v.nama=v.itm_nama;
                            v.dot_id=obj.data[0].dot_id
                            v.tipe=3;
                            globalrow.push(v);
                            globalharga+=parseInt(v.total)
                        })
                        $('#pos-kustomer-nama').textbox('setValue',obj.data[0].cus_nama)
                        $('#pos-kustomer-id').textbox('setValue',obj.data[0].cus_id)
                        $('#pos-total').textbox('setValue',globalharga);
                        $('#pos-grid').datagrid('loadData',globalrow);
                        $('#calculator').textbox('setValue','Dibayar=Rp.'+currencyFormat(globaluang)+',00')
                        $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00')
                        $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
                    }
                })
            }
        },
        prompt:'Masukkan Draft'
    });
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
            if(row.tipe==1)
            {
                globalrow.push(row)
                globalharga+=parseInt(row.satuan1hrg);
                $('#pos-grid').datagrid('loadData',globalrow);
                globalnotif=[];
                $('#notif-grid').datagrid('loadData',globalnotif);
                $('#calculator').textbox('setValue','Dibayar=Rp.'+currencyFormat(globaluang)+',00')
                $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00')
                $('#searchitem').searchbox('setValue','')
                $('#pos-total').textbox('setValue',globalharga);
                $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            }
            else if(row.tipe==2){
                $('#pos-kustomer-nama').textbox('setValue',row.cus_nama)
                $('#pos-kustomer-id').textbox('setValue',row.cus_id)
                $('#searchkustomer').searchbox('setValue','')
                globalnotif=[];
                $('#notif-grid').datagrid('loadData',globalnotif);
            }
            else if(row.tipe==3)
            {
                globalrow=[];
                console.log($('#notif-grid').datagrid('getSelected'));
                $.each(row.notaitems,function(i,v){
                    globalharga+=parseInt(v.total);
                    globalrow.push(v);
                });
                $('#pos-kustomer-nama').textbox('setValue',row.cus_nama)
                $('#pos-kustomer-id').textbox('setValue',row.cus_id)
                $('#pos-grid').datagrid('loadData',globalrow);
                globalnotif=[];
                $('#notif-grid').datagrid('loadData',globalnotif);
                $('#calculator').textbox('setValue','Dibayar=Rp.'+currencyFormat(globaluang)+',00')
                $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00')
                $('#pos-total').textbox('setValue',globalharga);
                $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
                //$('#searchitem').searchbox('setValue','')
            }
            $('#pos-layout').layout('collapse','east');
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
        onSelect:function(index,row){
            $('#pos-qty').numberbox('setValue',parseInt(row.qty))
            $('#pos-dsc').numberbox('setValue',parseInt(row.diskon))
            globalid=row.itm_id;
        }
    });
    $('#pos-btn-05').linkbutton({
        text:'500',
        height:111,
        width:111,
        onClick:function() {
            bayaruang(500)
        }
    }).css('text-align', 'center').css('background-color','#89a692');
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
    }).css('text-align', 'center').css('background-color','#fbd4a9');
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
        globalrow=[];
        globalharga=0;
        $('#pos-grid').datagrid('loadData',globalrow);
        globalnotif=[];
        $('#notif-grid').datagrid('loadData',globalnotif);
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
        console.log(globalrow)
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
                globalrow=[];
                globalharga=0;
                $('#pos-grid').datagrid('loadData',globalrow);
            }
        })
    }
    function bayaruang(uang){
        globaluang=globaluang+uang
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
        else if(parseInt(globalharga)>parseInt(globaluang)){
            $.messager.alert("Error kurang bayar", "Pembayaran kurang");
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
                globalrow=[];
                globalharga=0;
                globalkembalian=0;
                $('#calculator').textbox('setValue',"Dibayar="+'Rp.'+currencyFormat(globalharga)+',00');
                $('#pos-grid').datagrid('loadData',globalrow);
                $('#pos-total').datagrid('loadData',globalharga);
                globalnotif=[];
                $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
                $('#totalkembalian').textbox('setValue',"kembalian="+currencyFormat(globalkembalian)+',00');
                $('#notif-grid').datagrid('loadData',globalnotif);
            }
        })
        }
    }
    function posAddKustomer(){
        $('#pos-kustomer-add-dlg').dialog({
            title:'Tambah Kustomer',
            width:455,
            height:400,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'customer/add'
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
            href:'customer/item'
        });
    }
    ///////////login/////////////////////
    function loginSubmit(u, p, byUser = true) {
    $.post(getRestAPI("kasir/read"), {
        username:u,
        password:p
    },
    function(data) {
        var obj = JSON.parse(data);
        if (obj.status == 'success') {
            watchObject(obj.data);
            
            globalConfig = getConfig(obj);
            //Dany: Siapkan global variable untuk menyimpan beberapa id yang dibutuhkan
            globalConfig.ids = {};
            watchObject(globalConfig);
            const string = "\xa0\xa0\xa0\xa0\xa0\xa0Selamat Datang "+globalConfig.data.fullname+" di "+globalConfig.data.nama+" beralamat di "+globalConfig.data.alamat
            document.getElementById("tulisanberjalan").innerHTML = string;
            
            showMenu();
            arrangeView(); // fungsi ini didefinisikan di utility.js
            if (byUser) {
                setCookie('grexdkiw', u);
                setCookie('zlpiwrhc', p);
                $('#login-dlg').dialog('close');
            }
        }
        else {
            if (!byUser) showLogin();
            else $.messager.alert(globalConfig.app_nama, "Maaf login gagal");
            
        }
    });
}
    ///////fungsi penunjang
    function changeFieldBorderColor(field, color){
        var t = $(field);
        var el = t.data('textbox') ? t.next() : $(t);
        el.css('border-color', color);
    }
</script>