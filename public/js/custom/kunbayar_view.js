var kbyEdited = false;
$(function() {
    
    $('#kby-btn-save').linkbutton({
        text:'Simpan',
        iconCls:'fa fa-check-circle',
        disabled:true,
        onClick:function() {kbySave();}
    });
    $('#kby-btn-cancel').linkbutton({
        text:'Batal',
        iconCls:'fa fa-times-circle',
        disabled:true,
        onClick:function() {kbyCancel();}
    });
    $('#kby-btn-bpjs').linkbutton({
        text:'Pakai BPJS',
        iconCls:'fa fa-book-medical',
        disabled:true,
        onClick:function() {
            $('#kas-bpjs-dlg').dialog({
                title:'BPJS Info',
                width:300,
                height:200,
                border:true,
                modal:true,
                resizable:false,
                maximizable:false,
                href:'bpjs/potongan_bpjs'
            });
        }
    });
    $('#kby-grid').propertygrid({
        border:false,
        toolbar:'#kby-grid-tb',
        fit:true,
        fitColumns:true,
        editorHeight:23,
        showGroup:true,
        showHeader:false,
        idField:'id',
        queryParams:{
            db:getDB(),
            is_bpjs: false
        },
        url:getRestAPI('kunbayar/read'),
        columns:[[{
            field:'name',
            width:150,
            styler:function(value,row,index) {
                if(row.editor) return 'color:blue';
            }
        },{
            field:'value',
            formatter:function(value,row) {
                var str = row.value;
                if (row.money) str = parseInt(row.value).toLocaleString();
                return str;
            },
            styler:function(value,row,index) {
                if(row.money) return 'text-align:right';
            },
            width:150
        }]],
        onBeginEdit:function(index,row) {
            var pg = $(this);
            var ed = pg.propertygrid('getEditors',index)[0];
            if (ed) {
                var input = $(ed.target).textbox('textbox');
                input.bind('keydown', function(e) {
                    if (e.keyCode == 27)
                        $(ed.target).textbox('textbox').val(row.value);
                    if (e.keyCode == 13) {
                        if ($(ed.target).textbox('textbox').val()=='')
                            $(ed.target).textbox('textbox').val(0);
                        kbyEdited = true;
                    }
                    if (e.keyCode == 27 || e.keyCode == 13) { // esc or enter
                        var cell = pg.propertygrid('options')
                        .finder.getTr(pg[0], 1)// sebelumnya index+1
                        .find('td[field="value"] div.datagrid-cell');
                        cell.trigger('click');
                        return false;
                    }
                });
            }
        },
        onLoadSuccess: function(data) {
            calcKembalian();
            disableButton();
        },
        onEndEdit:function(index, row) {
            if (!kbyEdited) return;
            var today = new Date();
            setPropValue('kun_tgbayar', today.toLocaleDateString(['ban', 'id']));
            setPropValue('kun_kasir_sdm_id', globalConfig.login_data.nama);
            if (row.payment) calcTotalPembayaran();
            else if (row.discount) calcTotalPotongan();
            enableButton();
        }
        
    });

    function kbySave() {
        if (isDemo()) return;
        var row = $('#kas-grid').datagrid('getSelected');
        var p = $('#kby-grid').propertygrid('getData');
        console.log(p);
        var data = {
            kun_id:row.kun_id,
            sdm_id:globalConfig.login_data.sdm_id,
            prop:p,
            //edited by naufal
            username:globalConfig.login_data.username,
            db:getDB()
        }
        var kurangbayar = getPropValue('kun_kurangbayar');
        if(kurangbayar<0){
            $.messager.alert(globalConfig.app_nama, 'Pembayaran kurang');
        }
        else
        $.ajax({
            type:'POST',
            data:data,
            url:getRestAPI('kunbayar/save'),
            success:function(retval) {
                kbyCancel(); // untuk merefresh grid
                var obj = JSON.parse(retval);
                if (obj.status != 'success')
                    $.messager.alert(globalConfig.app_nama, obj.errmsg);
            }
        });
    }

    function kbyCancel() {
        var row = $('#kas-grid').datagrid('getSelected');
        $('#kby-grid').propertygrid('load', {
            kun_id:row.kun_id
        });
        kasReload();
    }
    function kasReload() {
        var kun_statusbayar = $('#kas-filter-lunas').combobox('getValue');
        $('#kas-grid').datagrid('load', {
            lok_id:globalConfig.login_data.lok_id,
            kun_statusbayar:kun_statusbayar,
            db:getDB()
        });
    }
    function getPropValue(id) {
        var rows = $('#kby-grid').propertygrid('getRows');
        var value = 0;
        $.each(rows, function(index, row) {
            if (row.id == id) {
                value = row.value;
                return false;
            }
        });
        return value;
    }

    function setPropValue(id, value, name=false) {
        var rows = $('#kby-grid').propertygrid('getRows');
        var index = 0;
        $.each(rows, function(index, row) {
            if (row.id == id) {
                row.value = value;
                if (name) row.name = name;
                $('#kby-grid').propertygrid('updateRow', {index:index,row:row});
                $('#kby-grid').propertygrid('refreshRow', index);
                return false;
            }
            index++;
        });
    }

    function calcKembalian() {
        var totalBiaya = getPropValue('kun_totalbiaya');
        var totalPotongan = getPropValue('kun_totalpotongan');
        var totalBiayaBaru = totalBiaya-totalPotongan;
        setPropValue('kun_totalbiayabaru', totalBiayaBaru);
        if (totalBiayaBaru < 0) {
            //disableButton();
            $.messager.alert(globalConfig.app_nama,
                'Total tagihan setelah dipotong tidak boleh negatif');
        }
        var totalBayar = getPropValue('kun_totalbayar');
        var kembalian = totalBayar-totalBiayaBaru;
        var name = 'Kembalian';
        var kurang=kembalian;
        if (kembalian < 0){ kembalian = 0;setPropValue('kun_kurangbayar', kurang, "Kurang Bayar");}
        else setPropValue('kun_kurangbayar', 0, "Kurang Bayar");
        setPropValue('kun_kembalian', kembalian, name);
        
    }

    function calcTotalPembayaran() {
        var rows = $('#kby-grid').propertygrid('getRows');
        var totalBayar = 0;
        $.each(rows, function(index, row) {
            if (row.payment)
                totalBayar += parseInt(row.value);
        });
        setPropValue('kun_totalbayar', totalBayar);
        calcKembalian();
    }

    function calcTotalPotongan() {
        var rows = $('#kby-grid').propertygrid('getRows');
        var totalPotongan = 0;
        $.each(rows, function(index, row) {
            if (row.discount)
                totalPotongan += parseInt(row.value);
        });
        setPropValue('kun_totalpotongan', totalPotongan);
        calcKembalian();
    }

    function enableButton() {
        $('#kby-btn-save').linkbutton('enable');
        $('#kby-btn-cancel').linkbutton('enable');
    }

    function disableButton() {
        $('#kby-btn-save').linkbutton('disable');
        $('#kby-btn-cancel').linkbutton('disable');
    }
});