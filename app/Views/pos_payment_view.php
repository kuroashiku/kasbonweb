<div class="easyui-layout" data-options="fit:true">

<div data-options="region:'north',split:false,border:false"
        style="height:100px;background-color:#8ae0ed;padding:5px">
        <div id="pop-title"></div>
        <table width="100%" height="0%">
            <tr>
                <td width="0%" style="vertical-align:top">
                    <table width="100%" height="0%" style="font-size:14px">
                        <tr height="0%">
                            <td style="white-space:nowrap;vertical-align:middle"
                                id="pop-pembayaran-label" width="0%">Pembayaran</td>
                            <td style="white-space:nowrap" width="100%"><input id="pop-pembayaran"></td>
                        </tr>
                    </table>
                </td>
                <td width="100%" style="vertical-align:top">
                    <table width="100%" height="0%" style="font-size:14px">
                        <tr height="0%">
                            <td style="white-space:nowrap;vertical-align:middle"
                                id="pop-kurangbayar-label" width="0%">Kurang Bayar</td>
                            <td style="white-space:nowrap" width="100%"><input id="pop-kurangbayar"></td>
                        </tr>
                        <tr height="0%">
                            <td style="white-space:nowrap;vertical-align:middle"
                                id="pop-kembalian-label" width="0%">Kembalian</td>
                            <td style="white-space:nowrap" width="100%"><input id="pop-kembalian"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div data-options="region:'center',split:false,border:false"
        style="height:95px;background-color:#8ae0ed;padding:5px">
        <div style="margin-top:5px">
            <div id="pop-btn-05"></div>
            <div id="pop-btn-1"></div>
            <div id="pop-btn-2"></div>
            <div id="pop-btn-5"></div>
            <div id="pop-btn-10"></div>
            <div id="pop-btn-20"></div>
            <div id="pop-btn-50"></div>
            <div id="pop-btn-100"></div>
        </div>
    </div>
    <div data-options="region:'south',split:false,border:false"
        style="height:95px;background-color:#8ae0ed;padding:5px">
        <div style="margin-top:5px">
            <div id="pop-btn-pay"></div>
            <div id="pop-btn-clear"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var total=0; var globalbayar=0;
    var pembayaran=0; var kurang=0; var kembali=0; 
    $.each(rowglobal,function(ic,vc){
        total=total+parseInt(vc.total);
    })
    $('#pop-btn-pay').linkbutton({
        text:'Bayar',
        iconCls:'fa fa-plus-circle fa-lg',
        onClick:function() {popPay();}
    });
    $('#pop-btn-clear').linkbutton({
        text:'Bersihkan',
        iconCls:'fa fa-plus-circle fa-lg',
        onClick:function() {
            $('#pop-pembayaran').numberbox('setValue',0)
            $('#pop-kurangbayar').numberbox('setValue',0)
            $('#pop-kembalian').numberbox('setValue',0)
        }
    });
    $('#pop-title').html('Total biaya=Rp.'+currencyFormat(total)+',00');
    $('#pop-btn-05').linkbutton({
        text:'500',
        onClick:function() {
            bayaruang(500)
        }
    });
    $('#pop-btn-1').linkbutton({
        text:'1.000',
        onClick:function() {
            bayaruang(1000)
        }
    });
    $('#pop-btn-2').linkbutton({
        text:'2.000',
        onClick:function() {
            bayaruang(2000)
        }
    });
    $('#pop-btn-5').linkbutton({
        text:'5.000',
        onClick:function() {
            bayaruang(5000)
        }
    });
    $('#pop-btn-10').linkbutton({
        text:'10.000',
        onClick:function() {
            bayaruang(10000)
        }
    });
    $('#pop-btn-20').linkbutton({
        text:'20.000',
        onClick:function() {
            bayaruang(20000)
        }
    });
    $('#pop-btn-50').linkbutton({
        text:'50.000',
        onClick:function() {
            bayaruang(50000)
        }
    });
    $('#pop-btn-100').linkbutton({
        text:'100.000',
        onClick:function() {
            bayaruang(100000)
        }
    });
    $('#pop-pembayaran').numberbox({
        width:150,
        height:24,
        inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
            keyup:function(e){
                pembayaran = $(e.data.target).textbox('getText');
                if(!pembayaran)
                globalbayar=0
                console.log(pembayaran)
                globalbayar=pembayaran
                kurang=total-pembayaran;
                if(kurang<0){
                    kurang=0;
                    kembali=Math.abs(total-pembayaran);
                }
                else kembali=0;
                $('#pop-kurangbayar').numberbox('setValue',kurang)
                $('#pop-kembalian').numberbox('setValue',kembali)
            }
        })
    });
    $('#pop-globalbayar').numberbox({
        width:150,
        height:24,
        disabled:true
    });
    $('#pop-kurangbayar').numberbox({
        width:150,
        height:24,
        disabled:true
    });
    $('#pop-kembalian').numberbox({
        width:150,
        height:24,
        disabled:true
    });
    $('#pop-pembayaran').numberbox('setValue',globalbayar)
    $('#pop-kurangbayar').numberbox('setValue',kurang)
    $('#pop-kembalian').numberbox('setValue',kembali)
    function popPay(){
        var bayar=$('#pop-pembayaran').numberbox('getValue')
        if(bayar<=0)
            alert("pembayaran tidak boleh nol atau kosong")
        else if(parseInt(total)>parseInt(bayar)){
            alert("pembayaran kurang dari total pembayaran")
        }
        else if(parseInt(total)<=parseInt(bayar)){
        if(rowglobal[0].dot_id){
            $.ajax({
                type:'POST',
                data:{dot_id:rowglobal[0].dot_id
                },
                url:getRestAPI('draftnota/delete'),
            })
        }
        $.ajax({
            type:'POST',
            data:{rows:rowglobal,
                total:total,
                kas_id:globalConfig.login_data.data.kas_id,
                kas_nama:globalConfig.login_data.data.kas_nama,
                dibayar:$('#pop-pembayaran').numberbox('getValue'),
                kembalian:$('#pop-kembalian').numberbox('getValue'),
                cus_id:parseInt($('#pos-form-customer').combobox('getValue'))?parseInt($('#pos-form-customer').combobox('getValue')):0,
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
                var value = $('#pos-form-draft').combobox('getValue');
                var data_combobox = $('#pos-form-draft').combobox('getData');
                const key = Object.keys(data_combobox).find(item => data_combobox[item].dot_id === value)
                var customer_value = $('#pos-form-customer').combobox('getValue');
                $.post(getRestAPI("draftnota/read"), {
                    kas_id:globalConfig.login_data.data.kas_id
                },
                function(data) {
                    var obj = JSON.parse(data);
                    console.log(obj)
                    var draft_data=obj.data;
                    $('#pos-form-draft').combobox('loadData',draft_data);
                });
                rowglobal=[];
                harga=0;
                $('#pos-form-customer').combobox('unselect',customer_value);
                $('#pos-grid').datagrid('loadData',rowglobal);
                $('#pos-form-draft').combobox('getData');
                $('#pos-btn-save').linkbutton('disable');
                $('#pos-btn-bayar').linkbutton('disable');
                $('#pos-btn-tampil').linkbutton('disable');
                $('#pos-payment-dlg').dialog().dialog('close');
            }
        })
        }
    }
    function bayaruang(uang){
        pembayaran = $('#pop-pembayaran').numberbox('getValue')
        if(!pembayaran){
        pembayaran=0;
        }
        globalbayar=parseInt(pembayaran)+uang
        kurang=total-globalbayar;
        if(kurang<0){
            kurang=0;
            kembali=Math.abs(total-globalbayar);
        }
        else kembali=0;
        $('#pop-pembayaran').numberbox('setValue',globalbayar)
        $('#pop-kurangbayar').numberbox('setValue',kurang)
        $('#pop-kembalian').numberbox('setValue',kembali)
    }
</script>