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
                                <input id="ita-form-id">
                            </tr>
                            <tr height="0%" style="padding-bottom:5px;">
                                <input id="ita-form-gambar-edit">
                            </tr>
                            <tr>
                                <input id="ita-form-gambar" class="easyui-filebox" name="file1" style="width:300px">
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="ita-form-kode-label" width="19%">Kode Item</td>
                                <td style="white-space:nowrap" width="81%"><input id="ita-form-kode"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="ita-form-harga-awal-label" width="19%">Harga Awal</td>
                                <td style="white-space:nowrap" width="81%"><input id="ita-form-harga-awal"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="ita-form-harga-label" width="19%">Harga</td>
                                <td style="white-space:nowrap" width="81%"><input id="ita-form-harga"></td>
                            </tr>
                        </table>
                    </td>
                    <td width="0%" style="vertical-align:top">
                        <table width="100%" height="0%" style="font-size:14px">
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="ita-form-nama-label" width="19%">Nama Item</td>
                                <td style="white-space:nowrap" width="81%"><input id="ita-form-nama"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="ita-form-satuan-label" width="19%">Satuan</td>
                                <td style="white-space:nowrap" width="81%"><input id="ita-form-satuan"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="ita-form-stok-label" width="19%">Stok</td>
                                <td style="white-space:nowrap" width="81%"><input id="ita-form-stok"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div data-options="region:'center',border:false">
            <div id="ita-grid"></div>
            <div id="ita-grid-tb" style="padding:5px">
                <div id="ita-btn-kustomerclear"></div>
                <div id="ita-btn-kustomersave"></div>
                <div id="ita-kustomersearch"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
        var flagita=0;
        var indexita=-1;
        $('#ita-form-gambar').filebox({
            width:300, 
            buttonText: 'Choose File',
            buttonAlign: 'left',
            accept:'image/*',
        })
        $('#ita-form-id').textbox({
            width:80,
            readonly:true,
        });
        $('#ita-form-gambar-edit').textbox({
            width:80,
            readonly:true,
        });
        $('#ita-form-id').textbox('textbox').parent().hide();
        $('#ita-form-gambar-edit').textbox('textbox').parent().hide();
        $('#ita-form-nama').textbox({
            prompt:'Isi nama item',
            width:300, 
        });
        $('#ita-form-stok').numberbox({
            prompt:'Isi stok',
            width:300, 
        });
        $('#ita-form-harga').numberbox({
            prompt:'Isi harga',
            width:300, 
        });
        $('#ita-form-harga-awal').numberbox({
            prompt:'Isi harga awal',
            width:300, 
        });
        $('#ita-form-kode').textbox({
            prompt:'Isi kode',
            width:300, 
        });
        $('#ita-form-satuan').textbox({
            prompt:'Isi satuan',
            width:300, 
        });
        $('#ita-btn-kustomerclear').linkbutton({
            text: 'Bersihkan',
            iconCls:'fa fa-brush fa-lg',
            onClick:function() {itaClearKustomer();}
        });
        $('#ita-btn-kustomersave').linkbutton({
            text: 'Simpan',
            iconCls:'fa fa-user-plus fa-lg',
            onClick:function() {itaSaveKustomer();}
        });
        $.post(getRestAPI("item/readgallery"), {
            lok_id:globalConfig.login_data.data.kas_lok_id,
        },
        function(data) {
            var obj = JSON.parse(data);
            $('#ita-grid').datagrid({
                border:false,
                singleSelect:true,
                editorHeight:22,
                toolbar:'#ita-grid-tb',
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
                    indexita=index;
                    $('#ita-form-nama').textbox('setValue',row.itm_nama);
                    $('#ita-form-stok').textbox('setValue',row.itm_stok);
                    $('#ita-form-kode').textbox('setValue',row.itm_kode);
                    $('#ita-form-satuan').textbox('setValue',row.itm_satuan1);
                    $('#ita-form-harga-awal').textbox('setValue',row.itm_satuan1hpp);
                    $('#ita-form-harga').textbox('setValue',row.itm_satuan1hrg);
                    $('#ita-form-id').textbox('setValue',row.itm_id);
                    $('#ita-form-gambar-edit').textbox('setValue',row.itm_photo);
                }
            });
        });
        function itaClearKustomer(){
            indexita=-1;
            $('#ita-form-nama').textbox('setValue','');
            $('#ita-form-stok').numberbox('setValue','');
            $('#ita-form-harga').numberbox('setValue','');
            $('#ita-form-kode').textbox('setValue','');
            $('#ita-form-satuan').textbox('setValue','');
            $('#ita-form-gambar').textbox('setValue','');
            $('#ita-form-gambar-edit').textbox('setValue','');
            $('#ita-form-harga-awal').numberbox('setValue','');
            $('#ita-grid').datagrid('unselectRow', indexita);
        }
        function itaSaveKustomer(){
            var files = $('#ita-form-gambar').filebox('files')
            if(files[0]){
                const getBase64StringFromDataURL = (dataURL) =>
                    dataURL.replace('data:', '').replace(/^.+,/, '');
                var files = $('#ita-form-gambar').filebox('files')
                var file = files[0];
                var reader = new FileReader();
                reader.onloadend = function() {
                    console.log(reader.result);
                    console.log(getBase64StringFromDataURL(reader.result))
                    $.ajax({
                    type:'POST',
                    data:{
                        itm_nama:$('#ita-form-nama').textbox('getValue'),
                        itm_stok:$('#ita-form-stok').numberbox('getValue'),
                        itm_satuan1hrg:$('#ita-form-harga').numberbox('getValue'),
                        itm_kode:$('#ita-form-kode').textbox('getValue'),
                        itm_satuan1:$('#ita-form-satuan').textbox('getValue'),
                        itm_satuan1hpp:$('#ita-form-harga-awal').numberbox('getValue'),
                        itm_photo:getBase64StringFromDataURL(reader.result),
                        itm_lok_id:globalConfig.login_data.data.kas_lok_id,
                        itm_id:indexita==-1?-1:$('#ita-form-id').textbox('getValue'),
                        itm_gallery:1,
                        itm_satuan2hpp:0,
                        itm_satuan2hrg:0,
                        itm_satuan2of1:0,
                        itm_satuan3hpp:0,
                        itm_satuan3hrg:0,
                        itm_satuan3of1:0,
                    },
                    url:getRestAPI('item/save'),
                    success:function(retval) {
                        if(indexita!=-1)$('#ita-grid').datagrid('unselectRow', indexita);
                        indexita=-1;
                        $('#ita-form-gambar-edit').textbox('setValue','');
                        $('#ita-form-nama').textbox('setValue','');
                        $('#ita-form-stok').numberbox('setValue','');
                        $('#ita-form-harga').numberbox('setValue','');
                        $('#ita-form-kode').textbox('setValue','');
                        $('#ita-form-satuan').textbox('setValue','');
                        $('#ita-form-gambar').textbox('setValue','');
                        $('#ita-form-harga-awal').numberbox('setValue','');
                        $.post(getRestAPI("item/read"), {
                            lok_id:globalConfig.login_data.data.kas_lok_id,
                        },
                        function(data) {
                            var obj = JSON.parse(data);
                            $('#ita-grid').datagrid('loadData',obj.data)
                            });
                        }
                    })
                }
                reader.readAsDataURL(file);
            }
            else{
                $.ajax({
                type:'POST',
                data:{
                    itm_nama:$('#ita-form-nama').textbox('getValue'),
                    itm_stok:$('#ita-form-stok').numberbox('getValue'),
                    itm_satuan1hrg:$('#ita-form-harga').numberbox('getValue'),
                    itm_kode:$('#ita-form-kode').textbox('getValue'),
                    itm_satuan1:$('#ita-form-satuan').textbox('getValue'),
                    itm_satuan1hpp:$('#ita-form-harga-awal').numberbox('getValue'),
                    itm_photo:$('#ita-form-gambar-edit').textbox('getValue'),
                    itm_lok_id:globalConfig.login_data.data.kas_lok_id,
                    itm_id:indexita==-1?-1:$('#ita-form-id').textbox('getValue'),
                    itm_gallery:1,
                    itm_pakaistok:1
                },
                url:getRestAPI('item/save'),
                success:function(retval) {
                    if(indexita!=-1)$('#ita-grid').datagrid('unselectRow', indexita);
                    indexita=-1;
                    $('#ita-form-gambar-edit').textbox('setValue','');
                    $('#ita-form-nama').textbox('setValue','');
                    $('#ita-form-stok').numberbox('setValue','');
                    $('#ita-form-harga').numberbox('setValue','');
                    $('#ita-form-kode').textbox('setValue','');
                    $('#ita-form-satuan').textbox('setValue','');
                    $('#ita-form-gambar').textbox('setValue','');
                    $('#ita-form-harga-awal').numberbox('setValue','');
                    $.post(getRestAPI("item/read"), {
                        lok_id:globalConfig.login_data.data.kas_lok_id,
                    },
                    function(data) {
                        var obj = JSON.parse(data);
                        $('#ita-grid').datagrid('loadData',obj.data)
                        });
                    }
                })
            }
        }
    })
    
    </script>
    </body>
</html>