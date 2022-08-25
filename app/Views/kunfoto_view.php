<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'north',split:false,border:false"
        style="height:95px;background-color:#8ae0ed;padding:5px">
        <div>
            <input id="kfo-form-foto">
        </div>
        <div style="margin-top:5px">
            <input id="kfo-form-keterangan">
        </div>
        <div style="margin-top:5px">
            <div id="kfo-btn-upload"></div>
            <div id="kfo-btn-refresh"></div>
            <img src="public/images/transparent-dot.gif" onload="kfoRead()">
        </div>
    </div>
    <div id="kfo-form-gallery"
        data-options="region:'center',border:false"
        style="padding:5px 0 0 5px">
    </div>
</div>
<script type="text/javascript">
    $('#kfo-form-keterangan').textbox({
        width:'100%',
        prompt:'Keterangan foto',
        height:24
    });
    $('#kfo-form-foto').filebox({
        width:'100%',
        height:24
    });
    $('#kfo-btn-upload').linkbutton({
        iconCls:'fa fa-cloud-upload-alt fa-lg',
        text:'Upload',
        height:24,
        onClick:function() {kfoUpload();}
    });
    $('#kfo-btn-refresh').linkbutton({
        iconCls:'fa fa-sync-alt fa-lg',
        text:'Refresh',
        height:24,
        onClick:function() {kfoRead()}
    });

    function kfoRead(showProgress = true) {
        var selectedKunRow = $('#yan-grid').datagrid('getSelected');
        if (showProgress) $('#kfo-form-gallery').panel('showMask');
        $.ajax({
            type:'POST',
            data:{
                kun_id:selectedKunRow.kun_id,
                db:getDB()
            },
            url:getRestAPI('kunfoto/read'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                $('#kfo-form-gallery').empty();
                $.each(obj.rows, function(index, foto) {
                    $('#kfo-form-gallery').append(
                        "<div style='display:inline-block;padding:5px;background-color:lightgrey;"+
                            "margin:0 5px 5px 0'>"+
                            "<div style='text-align:center'><img src='public/viewfoto.php?id="+foto.kfo_id+
                                "&db="+getDB()+"&rnd="+Math.random().toString()+"' height='100' id='foto-"+
                                foto.kfo_id+"'></div>"+
                            "<div style='background-color:transparent;text-align:center;padding:3px'>"+
                                foto.kfo_keterangan+"</div>"+
                            "<div style='background-color:transparent;text-align:center'>"+
                                "<button onclick='kfoDelete(\""+foto.kfo_id+"\")'>Hapus</button>"+
                            "</div>"+
                        "</div>");
                });
                $('#kfo-form-gallery').panel('hideMask');
            },
            error:function() {
                if (showProgress) $('#kfo-form-gallery').panel('hideMask');
            }
        });
    }

    function kfoUpload() {
        if (isDemo()) return;
        var selectedKunRow = $('#yan-grid').datagrid('getSelected');
        var keterangan = $('#kfo-form-keterangan').textbox('getValue');
        var iObj = $('#kfo-form-foto').next().find('.textbox-value')[0];
        if (!selectedKunRow)
            $.messager.alert(globalConfig.app_nama, "Silahkan pilih data kunjungan lebih dulu");
        else if (iObj.files[0] == undefined)
            $.messager.alert(globalConfig.app_nama, "Silahkan pilih file JPG/PNG dulu, max 300KB");
        else if (iObj.files[0].size > 307200)
            $.messager.alert(globalConfig.app_nama, "Ukuran file terlalu besar, max 300KB");
        else if (keterangan == '')
            $.messager.alert(globalConfig.app_nama, "Keterangan harus diisi");
        else {
            var formData = new FormData();
            formData.append('kun_id', selectedKunRow.kun_id);
            formData.append('keterangan', keterangan);
            formData.append('username', globalConfig.login_data.username);
            formData.append('db', getDB());
            formData.append('foto', iObj.files[0]);
            $('#kfo-form-gallery').panel('showMask');
            $.ajax({
                type:'POST',
                contentType:false,
                processData:false,
                data:formData,
                url:getRestAPI('kunfoto/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#kfo-form-keterangan').textbox('setValue', '');
                        $('#kfo-form-foto').textbox('setValue', '');
                        kfoRead(false);
                        $('#kfo-form-gallery').panel('hideMask');
                    }
                },
                error:function() {
                    $('#kfo-form-gallery').panel('hideMask');
                }
            });
        }
    }

    function kfoDelete(id) {
        if (globalConfig.login_data.type == 'demo') {
            $.messager.alert(globalConfig.app_nama,
                'Maaf, login demo belum diperbolehkan melakukan aktivitas mengubah data');
            return;
        }
        $.messager.confirm('Konfirmasi', 'Apakah benar foto ini akan dihapus?', function(r) {
            if (r) {
                $('#kfo-form-gallery').panel('showMask');
                $.ajax({
                    type:'POST',
                    data:{
                        kfo_id:id,
                        db:getDB()
                    },
                    url:getRestAPI('kunfoto/delete'),
                    success:function(retval) {
                        kfoRead(false);
                        $('#kfo-form-gallery').panel('hideMask');
                    },
                    error:function() {
                        $('#kfo-form-gallery').panel('hideMask');
                    }
                });
            }
        });
    }
</script>