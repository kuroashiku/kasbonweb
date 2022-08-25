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
            v.satuan1hrg=v.itm_satuan1hrg;
            v.satuan1=v.itm_satuan1;
            v.satuan1hpp=v.itm_satuan1hpp;
            v.id=v.itm_id;
            v.nama=v.itm_nama;
            v.tipe=1;
            var nama=v.itm_nama.replace(' ', '\xa0');
            $('#gambar-html-tr-'+count).append('<td style="text-align: center;font-size:16;">'+
            '<a href="#" onClick="entryGambar('+v.itm_id+',\''+v.itm_nama+'\','+v.itm_satuan1hrg+',\''+v.itm_satuan1+'\','+v.itm_satuan1hpp+')">'+
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
    function entryGambar(itmid,itmnama,itmharga,itmsatuan,itmhpp){
        console.log(itmid)
        $.each(globalrow,function(i,v){
            if(itmid==v.itm_id)
            {
                itemflag=1;
            }
        })
        if(itemflag==0){
            globalharga=0;
            globalrow.push(JSON.parse('{"itm_id":'+itmid+',"itm_nama":"'+itmnama+'","satuan1hrg":"'+itmharga+'",'+
            '"satuan1":"'+itmsatuan+'","satuan1hpp":'+itmhpp+',"total":'+itmharga+',"qty":1,"diskon":0,"disnom":0,"konvidx":0,"tipe":1,"id":'+itmid+',"nama":"'+itmnama+'"}'));
            $('#pos-grid').datagrid('loadData',globalrow);
            $.each(globalrow,function(i,v){
                globalharga=globalharga+parseInt(v.total)
            })
            $('#calculator').textbox('setValue',"Dibayar="+'Rp.'+currencyFormat(globaluang)+',00');
            $('#pos-total').textbox('setValue',globalharga);
            $('#totalkurang').textbox('setValue',"kurang="+'Rp.'+currencyFormat(globalharga)+',00');
            $('#pos-btn-now').linkbutton({text:currencyFormat(globalharga)});
        }
        else{
            $.messager.alert("Error item kembar", "Item sudah terdaftar");  
            itemflag=0;
        }
        $('#pos-item-gambar-dlg').dialog().dialog('close');
    }
</script>