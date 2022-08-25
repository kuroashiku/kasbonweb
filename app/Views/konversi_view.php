<html>
    <body>
        
    <script type="text/javascript">
        $.parser.onComplete = function(){
            $('body').css('visibility','visible');
        };
    </script>
    <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'north',split:false,border:false"
            style="height:150px;background-color:#8ae0ed;padding:5px">
            <table width="100%" height="0%">
                <tr>
                    <td width="100%" style="vertical-align:top">
                        <table width="100%" height="0%" style="font-size:14px">
                            <tr height="0%">
                                <input id="kvs-form-id">
                            </tr>
                            <tr height="0%">
                                <input id="kvs-form-harga0">
                            </tr>
                            <tr height="0%">
                                <input id="kvs-form-harga-awal0">
                            </tr>
                            <tr height="0%">
                                <input id="kvs-form-gambar-edit">
                            </tr>
                            <tr height="0%">
                                <input id="kvs-form-nama">
                            </tr>
                            <tr height="0%">
                                <input id="kvs-form-kode">
                            </tr>
                            <tr height="0%">
                                <input id="kvs-form-stok">
                            </tr>
                            <tr height="0%">
                                <input id="kvs-form-satuan0">
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="kvs-form-kode-label" width="19%">Satuan</td>
                                <td style="white-space:nowrap" width="81%"><input id="kvs-form-satuan1"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="kvs-form-harga-awal-label" width="19%">HPP</td>
                                <td style="white-space:nowrap" width="81%"><input id="kvs-form-harga-awal1"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="kvs-form-harga-label" width="19%">Satuan</td>
                                <td style="white-space:nowrap" width="81%"><input id="kvs-form-satuan2"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="kvs-form-harga-awal-label" width="19%">HPP</td>
                                <td style="white-space:nowrap" width="81%"><input id="kvs-form-harga-awal2"></td>
                            </tr>
                        </table>
                    </td>
                    <td width="0%" style="vertical-align:top">
                        <table width="100%" height="0%" style="font-size:14px">
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="kvs-form-nama-label" width="19%">Faktor Kali</td>
                                <td style="white-space:nowrap" width="81%"><input id="kvs-form-of1"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="kvs-form-satuan-label" width="19%">Harga</td>
                                <td style="white-space:nowrap" width="81%"><input id="kvs-form-harga1"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="kvs-form-stok-label" width="19%">Faktor Kali</td>
                                <td style="white-space:nowrap" width="81%"><input id="kvs-form-of2"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="kvs-form-satuan-label" width="19%">Harga</td>
                                <td style="white-space:nowrap" width="81%"><input id="kvs-form-harga2"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div data-options="region:'center',border:false">
            <div id="kvs-grid"></div>
            <div id="kvs-grid-tb" style="padding:5px">
                <div id="kvs-btn-konversisave"></div>
                <div id="kvs-kustomersearch"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
        var flagkvs=0;
        var indexkvs=-1;
        $('#kvs-form-id').textbox({
            width:80,
            readonly:true,
        });
        $('#kvs-form-gambar-edit').textbox({
            width:80,
            readonly:true,
        });
        $('#kvs-form-nama').textbox({
            width:80,
            readonly:true,
        });
        $('#kvs-form-kode').textbox({
            width:80,
            readonly:true,
        });
        $('#kvs-form-satuan0').textbox({
            width:80,
            readonly:true,
        });
        $('#kvs-form-stok').numberbox({
            width:80,
            readonly:true,
        });
        $('#kvs-form-harga0').numberbox({
            width:80,
            readonly:true,
        });
        $('#kvs-form-harga-awal0').numberbox({
            width:80,
            readonly:true,
        });
        $('#kvs-form-id').textbox('textbox').parent().hide();
        $('#kvs-form-harga0').textbox('textbox').parent().hide();
        $('#kvs-form-harga-awal0').textbox('textbox').parent().hide();
        $('#kvs-form-gambar-edit').textbox('textbox').parent().hide();
        $('#kvs-form-nama').textbox('textbox').parent().hide();
        $('#kvs-form-kode').textbox('textbox').parent().hide();
        $('#kvs-form-stok').textbox('textbox').parent().hide();
        $('#kvs-form-satuan0').textbox('textbox').parent().hide();
        $('#kvs-form-satuan1').textbox({
            prompt:'Satuan item',
            width:300, 
        });
        $('#kvs-form-satuan2').textbox({
            prompt:'Satuan item',
            width:300, 
        });
        $('#kvs-form-of1').numberbox({
            prompt:'Faktor Kali',
            width:300, 
            onChange:function(value) {
                $('#kvs-form-harga1').numberbox('setValue',value*$('#kvs-form-harga0').numberbox('getValue'))
                $('#kvs-form-harga-awal1').numberbox('setValue',value*$('#kvs-form-harga-awal0').numberbox('getValue'))
            }
        });
        $('#kvs-form-of2').numberbox({
            prompt:'Faktor Kali',
            width:300, 
        });
        $('#kvs-form-harga1').numberbox({
            prompt:'Isi harga',
            width:300, 
        });
        $('#kvs-form-harga2').numberbox({
            prompt:'Isi harga',
            width:300, 
        });
        $('#kvs-form-harga-awal1').numberbox({
            prompt:'Isi harga awal',
            width:300, 
        });
        $('#kvs-form-harga-awal2').numberbox({
            prompt:'Isi harga awal',
            width:300, 
        });
        $('#kvs-btn-konversisave').linkbutton({
            text: 'Simpan',
            iconCls:'fa fa-user-plus fa-lg',
            onClick:function() {kvsSaveKonversi();}
        });
        $.post(getRestAPI("item/readgallery"), {
            lok_id:globalConfig.login_data.data.kas_lok_id,
        },
        function(data) {
            var obj = JSON.parse(data);
            $('#kvs-grid').datagrid({
                border:false,
                singleSelect:true,
                editorHeight:22,
                toolbar:'#kvs-grid-tb',
                fit:true,
                columns:[[{
                    field:'itm_id',
                    title:'ID',
                    resizable:false,
                    width:40,
                    hidden:true
                },{
                    field:'itm_nama',
                    title:'Nama',
                    resizeble:false,
                    width:200
                },{
                    field:'itm_satuan1hrg',
                    title:'Harga',
                    resizeble:false,
                    width:200,
                    align:'right',
                    formatter: function(value, row) {return currencyFormat(row.itm_satuan1hrg)}
                }]],
                data:obj.data,
                onSelect:function(index, row) {
                    indexkvs=index;
                    $('#kvs-form-harga0').numberbox('setValue',row.itm_satuan1hrg);
                    $('#kvs-form-harga-awal0').numberbox('setValue',row.itm_satuan1hpp);
                    $('#kvs-form-harga1').numberbox('setValue',row.itm_satuan2hrg);
                    $('#kvs-form-of1').numberbox('setValue',row.itm_satuan2of1);
                    $('#kvs-form-of2').numberbox('setValue',row.itm_satuan3of1);
                    $('#kvs-form-harga-awal1').numberbox('setValue',row.itm_satuan2hpp);
                    $('#kvs-form-harga2').numberbox('setValue',row.itm_satuan3hrg);
                    $('#kvs-form-harga-awal2').numberbox('setValue',row.itm_satuan3hpp);
                    $('#kvs-form-satuan1').textbox('setValue',row.itm_satuan2);
                    $('#kvs-form-satuan2').textbox('setValue',row.itm_satuan3);
                    $('#kvs-form-gambar-edit').textbox('setValue',row.itm_photo);
                    $('#kvs-form-nama').textbox('setValue',row.itm_nama);
                    $('#kvs-form-stok').numberbox('setValue',row.itm_stok);
                    $('#kvs-form-kode').textbox('setValue',row.itm_kode);
                    $('#kvs-form-id').textbox('setValue',row.itm_id);
                    $('#kvs-form-satuan0').textbox('setValue',row.itm_satuan1);
                }
            });
        });
        function kvsClearKustomer(){
            indexkvs=-1;
            $('#kvs-form-nama').textbox('setValue','');
            $('#kvs-form-stok').numberbox('setValue','');
            $('#kvs-form-harga').numberbox('setValue','');
            $('#kvs-form-kode').textbox('setValue','');
            $('#kvs-form-satuan').textbox('setValue','');
            $('#kvs-form-gambar').textbox('setValue','');
            $('#kvs-form-gambar-edit').textbox('setValue','');
            $('#kvs-form-harga-awal').numberbox('setValue','');
            $('#kvs-grid').datagrid('unselectRow', indexkvs);
        }
        function kvsSaveKonversi(){
            
            $.ajax({
            type:'POST',
            data:{
                itm_nama:$('#kvs-form-nama').textbox('getValue'),
                itm_stok:$('#kvs-form-stok').numberbox('getValue'),
                itm_kode:$('#kvs-form-kode').textbox('getValue'),
                itm_satuan1hrg:$('#kvs-form-harga0').numberbox('getValue'),
                itm_satuan1:$('#kvs-form-satuan0').textbox('getValue'),
                itm_satuan1hpp:$('#kvs-form-harga-awal0').numberbox('getValue'),
                itm_satuan2hrg:$('#kvs-form-harga1').numberbox('getValue'),
                itm_satuan2:$('#kvs-form-satuan1').textbox('getValue'),
                itm_satuan2hpp:$('#kvs-form-harga-awal1').numberbox('getValue'),
                itm_satuan3hrg:$('#kvs-form-harga2').numberbox('getValue'),
                itm_satuan3:$('#kvs-form-satuan2').textbox('getValue'),
                itm_satuan3hpp:$('#kvs-form-harga-awal2').numberbox('getValue'),
                itm_photo:$('#kvs-form-gambar-edit').textbox('getValue'),
                itm_lok_id:globalConfig.login_data.data.kas_lok_id,
                itm_id:$('#kvs-form-id').textbox('getValue'),
                itm_satuan2of1:$('#kvs-form-of1').numberbox('getValue'),
                itm_satuan3of1:$('#kvs-form-of2').numberbox('getValue'),
                itm_gallery:1
            },
            url:getRestAPI('item/save'),
            success:function(retval) {
                if(indexkvs!=-1)$('#kvs-grid').datagrid('unselectRow', indexkvs);
                indexkvs=-1;
                $.post(getRestAPI("item/read"), {
                    lok_id:globalConfig.login_data.data.kas_lok_id,
                },
                function(data) {
                    var obj = JSON.parse(data);
                    $('#kvs-grid').datagrid('loadData',obj.data)
                    });
                }
            })
            
        }
    })
    
    </script>
    </body>
</html>