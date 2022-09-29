<table id="gambar-html">

</table>
<script>
    $.post(getRestAPI("item/readgallery"), {
        lok_id:globalConfig.login_data.data.kas_lok_id
    },
    function(data) {
        var obj = JSON.parse(data);
        var count=0;
        var rowjson=[];
        for(var j=0; j<=(obj.data.length/4)+1;j++){
            $('#gambar-html').append('<tr id="gambar-html-tr-'+j+'">');
            $('#gambar-html').append('</tr>');
        }
        
        $.each(obj.data,function(i,v){
            if(i%4==0){
                count=count+1;
            }
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
            v.satuan2of1=v.itm_satuan2of1;
            v.satuan3of1=v.itm_satuan3of1;
            v.id=v.itm_id;
            v.nama=v.itm_nama;
            v.tipe=1;
            var nama=v.itm_nama.replace(' ', '\xa0');
            $('#gambar-html-tr-'+count).append('<td style="text-align: center;font-size:16;">'+
            '<a href="#" onClick="entryGambar('+v.itm_id+',\''+v.itm_nama+'\','+v.itm_satuan1hrg+',\''+v.itm_satuan1+'\','+v.itm_satuan1hpp+','+v.itm_satuan2hrg+',\''+v.itm_satuan2+'\','+v.itm_satuan2hpp+','+v.itm_satuan3hrg+',\''+v.itm_satuan3+'\','+v.itm_satuan3hpp+','+v.itm_satuan2of1+','+v.itm_satuan3of1+')">'+
            '<div id="gambar-html-td-'+i+'"></div></a>'+nama+'</td>')
            var item_image=v.itm_photo
            var src = "data:image/jpeg;base64,";
            src += item_image;
            var newImage = document.createElement('img');
            newImage.src = src;
            newImage.width = newImage.height = "98";
            document.querySelector('#gambar-html-td-'+i).innerHTML = newImage.outerHTML;
        })
    });
    function entryGambar(itmid,itmnama,itmharga1,itmsatuan1,itmhpp1,itmharga2,itmsatuan2,itmhpp2,itmharga3,itmsatuan3,itmhpp3,itmof2,itmof3){
        console.log(itmid)
        $.each(globalrow,function(i,v){
            if(itmid==v.itm_id)
            {
                itemflag=1;
            }
        })
        if(itemflag==0){
            globalharga=0;
            globalrow.push(JSON.parse('{"itm_id":'+itmid+',"itm_nama":"'+itmnama+'","satuan1hrg":"'+itmharga1+'",'+
            '"satuan1":"'+itmsatuan1+'","satuan1hpp":'+itmhpp1+',"total":'+itmharga1+',"qty":1,"diskon":0,"disnom":0,"konvidx":0,"tipe":1,"id":'+
            itmid+',"nama":"'+itmnama+'","satuan2hrg":"'+itmharga2+'",'+
            '"satuan2":"'+itmsatuan2+'","satuan2hpp":'+itmhpp2+',"satuan3hrg":"'+itmharga3+'",'+
            '"satuan3":"'+itmsatuan3+'","satuan3hpp":'+itmhpp3+',"satuan0hrg":"'+itmharga1+'",'+
            '"satuan0":"'+itmsatuan1+'","satuan0hpp":'+itmhpp1+',"satuan2of1":"'+itmof2+'",'+
            '"satuan3of1":"'+itmof3+'"}'));
            $('#pos-grid').datagrid('loadData',globalrow);
            $.each(globalrow,function(i,v){
                globalharga=globalharga+parseInt(v.total)
            })
            $('#calculator').textbox('setValue',"Dibayar="+'Rp.'+currencyFormat(globaluang)+',00');
            $('#pos-total').textbox('setValue',globalharga);
            var lastIndex = $('#pos-grid').datagrid('getRows').length - 1;
            $('#pos-grid').datagrid('selectRow',lastIndex);
            $('#globalkurang').textbox('setValue',globalharga)
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
        }
        else{
            $.messager.alert("Error item kembar", "Item sudah terdaftar");  
            itemflag=0;
        }
        $('#pos-item-gambar-dlg').dialog().dialog('close');
    }
</script>