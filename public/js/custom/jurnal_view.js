$(function() {
    $('#jurnal-form-tgldari').datebox({
        width:120,
        editable:false
    });
    $('#jurnal-form-tglke').datebox({
        width:120,
        editable:false
    });
    $('#jurnal-btn-create').linkbutton({
        text:'Ok',
        iconCls:'fa fa-check-circle',
        onClick:function() {drawReport('#jurnal-rep');}
    });
    $('#jurnal-btn-pdf').linkbutton({
        text:'Generate PDF',
        iconCls:'fa fa-file-pdf',
        onClick:function() {jurPDF();}
    });
    $('#jurnal-rep').panel({
        height:794,
        width:1123,
        border:false,
        style:{backgroundColor:'#ffffff',padding:50},
        onOpen:function() {
            var today = new Date();
            $('#jurnal-form-tgldari').datebox('setValue', today.toLocaleDateString());
            $('#jurnal-form-tglke').datebox('setValue', today.toLocaleDateString());
            drawReport(this);
        }
    });

    function drawReport(obj) {
        var tglDari = $('#jurnal-form-tgldari').datebox('getDate');
        var tglKe = $('#jurnal-form-tglke').datebox('getDate');
        if (tglDari.toDateString() == tglKe.toDateString())
            subTitle = 'Tanggal: '+tglDari.toDateString();
        else
            subTitle = 'Tanggal: '+tglDari.toDateString()+' s/d '+tglKe.toDateString();

        $('#accrep-jurnal').remove();
        $(obj).append('<table id="accrep-jurnal" width="100%" height="0%" border="0"></table>');
        $('#accrep-jurnal').append('<tr><td style="font-size:15px;font-weight:bold">'+
            globalConfig.lok_nama+'</td></tr>');
        $('#accrep-jurnal').append('<tr><td style="font-size:12px;font-weight:bold">'+
            globalConfig.lok_alamat+' '+globalConfig.lok_kodepos+'</td></tr>');
        $('#accrep-jurnal').append('<tr><td style="font-size:20px;font-weight:bold;text-align:center;'+
            'padding-top:10px">TRANSAKSI JURNAL</td></tr>');
        $('#accrep-jurnal').append('<tr><td style="text-align:center;padding-bottom:10px">'+
            subTitle+'</td></tr>');

        /////// draw header ////////
        $('#accrep-jurnal').append('<tr><td><table id="accrep-jurnal-detail" border="0" '+
            'cellspacing="0" width="100%"></table></td></tr>');
        $('#accrep-jurnal-detail').append('<tr id="accrep-jurnal-header"></tr>');
        drawHeader('Tanggal', 0);
        drawHeader('Transaksi', 0);
        drawHeader('No. dokumen', 0);
        drawHeader('Item', 100);
        drawHeader('Account', 0);
        drawHeader('', 0);
        drawHeader('Debet', 0, 'right');
        drawHeader('Kredit', 0, 'right');

        $.ajax({
            type:'POST',
            data:{
                tgldari:tglDari.toLocaleDateString(),
                tglke:tglKe.toLocaleDateString(),
                db:getDB()
            },
            url:getRestAPI('accreport/jurnal'),
            success:function(retval) {
                var rows = JSON.parse(retval);
                var stripped = false;
                for(var i=0;i<rows.length;i++) {
                    // bagian debet
                    $('#accrep-jurnal-detail').append('<tr id="accrep-jurnal-d'+i+'"></tr>');
                    drawRow('#accrep-jurnal-d'+i, rows[i].jur_tgl, 0, stripped);
                    drawRow('#accrep-jurnal-d'+i, rows[i].jur_nama, 0, stripped);
                    drawRow('#accrep-jurnal-d'+i, rows[i].jur_kode, 0, stripped);
                    drawRow('#accrep-jurnal-d'+i, rows[i].jur_ditem, 1, stripped);
                    drawRow('#accrep-jurnal-d'+i, rows[i].jur_dcoakode, 1, stripped);
                    drawRow('#accrep-jurnal-d'+i, rows[i].jur_dcoanama, 1, stripped);
                    drawRow('#accrep-jurnal-d'+i, rows[i].jur_nilai, 1, stripped, 'right');
                    drawRow('#accrep-jurnal-d'+i, '', 1, stripped);
                    // bagian kredit
                    $('#accrep-jurnal-detail').append('<tr id="accrep-jurnal-k'+i+'"></tr>');
                    drawRow('#accrep-jurnal-k'+i, '', 1, stripped);
                    drawRow('#accrep-jurnal-k'+i, '', 1, stripped);
                    drawRow('#accrep-jurnal-k'+i, '', 1, stripped);
                    drawRow('#accrep-jurnal-k'+i, rows[i].jur_kitem, 1, stripped);
                    drawRow('#accrep-jurnal-k'+i, rows[i].jur_kcoakode, 1, stripped);
                    drawRow('#accrep-jurnal-k'+i, rows[i].jur_kcoanama, 1, stripped);
                    drawRow('#accrep-jurnal-k'+i, '', 1, stripped);
                    drawRow('#accrep-jurnal-k'+i, rows[i].jur_nilai, 1, stripped, 'right');
                    stripped = !stripped;
                }
            }
        });
    }

    function drawHeader(title, width, align='left') {
        $('#accrep-jurnal-header').append('<td class="accrep-h" style="width:'+
            width+'%;text-align:'+align+'">'+title+'</td>');
    }

    function drawRow(obj, title, border, stripped, align='left') {
        var style = 'text-align:'+align+';border-bottom:'+border+'px solid black;';
        style += stripped?'background-color:#fff6c5;':'';
        $(obj).append('<td class="accrep-r" style="'+style+'">'+title+'</td>');
    }

    function jurPDF() {
        var divContents = $("#jurnal-rep").html();
        var printWindow = window.open('', '', 'height=400,width=800');
        printWindow.document.write('<html><head>');
        printWindow.document.write('<title>Generate PDF</title>');
        printWindow.document.write('<style type="text/css">.accrep-h {'+
            'padding: 3px 6px 3px 6px;'+
            'font-weight: bold;'+
            'font-size: 14px;'+
            'vertical-align: middle;'+
            'border-top: 1px solid black;'+  
            'border-bottom: 1px solid black;'+
            'white-space: nowrap;'+
            'background-color: #eeeeee;}'+
            '.accrep-r {'+
            'padding: 3px 6px 3px 6px;'+
            'font-size: 14px;'+
            'border-top: 1px solid black;'+  
            'border-bottom: 1px solid black;'+
            'vertical-align: middle;'+
            'white-space: nowrap;'+
        '}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(divContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
});