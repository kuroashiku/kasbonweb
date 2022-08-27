
function getDB() {
    return globalConfig.login_data?globalConfig.login_data.db:'demodb';
}

function getRestAPI(method_name) {
    return 'https://'+window.location.hostname+'/reepos/'+method_name;
}

function getConfig(data=null) {
    var config = null;
    if (data) {
        $.ajax({
            type:'POST',
            data:{
                lok_id:data.data.kas_lok_id,
                kas_id:data.data.kas_id
            },
            url:getRestAPI('config/read'),
            async:false,
            success:function(retval) {
                config = JSON.parse(retval);
                config.login_data  = data;
            }
        });
    }
    else {
        config = {
            lok_nama:'PT. ReenDoo Profeta Nusantara',
            login_data:null
        };
    }
    return config;
}

function showLogin() {
    $('#login-dlg').dialog({
        title:'Login',
        iconCls:'icon-man',
        width:300,
        height:230,
        closable:false,
        border:true,
        modal:true,
        href:'main/login'
    });
}
function showRegisterLogin(nick) {
    $('#register-dlg').dialog({
        title:'Registrasi',
        iconCls:'icon-man',
        width:300,
        height:230,
        closable:false,
        border:true,
        modal:true,
        href:'main/register'
    });
    $('#register-dlg').data('id', nick ).dialog('open');
}
function showUpdateLogin(nick) {
    $('#updatelogin-dlg').dialog({
        title:'Update Login',
        iconCls:'icon-man',
        width:300,
        height:230,
        closable:false,
        border:true,
        modal:true,
        href:'main/updatelogin'
    });
    $('#updatelogin-dlg').data('id', nick ).dialog('open');
}

function openTab(index, id, title, href, closable)
{
    $('#main-tab').tabs('add',{
        index:index,
        id:id,
        title:title,
        href:href,
        closable:closable
    });
}

function closeTab(id) {
    if($(id).length) {
        var strip = $(id).panel('options').tab.find('.tabs-title');
        var str = strip.html();
        $('#main-tab').tabs('close', str);
    }
}

function showMenu() {
    $('#menu-posjual').linkbutton({
        width:'100%',
        text:'',
        disabled:true,
        iconCls:'fa fa-shopping-cart fa-lg',
        onClick:function() {
            closeAll();
            if ($('#main-tab-posjual').length) return;
            $('#main-layout').layout('collapse', 'west');
            openTab(2, 'main-tab-posjual', "<span class='easyui-tooltip' title='POS/Jual'><i class='fa fa-shopping-cart fa-lg ' style='padding-top:7'></i></span>", 'main/pos', false);
        }
    });
    $('#menu-cust').linkbutton({
        width:'100%',
        text:'',
        disabled:true,
        iconCls:'fa fa-id-badge fa-lg',
        onClick:function() {
            closeAll();
            if ($('#main-tab-cust').length) return;
            $('#main-layout').layout('collapse', 'west');
            openTab(3, 'main-tab-cust', "<span class='easyui-tooltip' title='Customer'><i class='fa fa-id-badge fa-lg ' style='padding-top:7'></i></span>", 'main/cust', false);
        }
    });
    $('#menu-itm').linkbutton({
        width:'100%',
        text:'',
        disabled:true,
        iconCls:'fa fa-gift fa-lg',
        onClick:function() {
            closeAll();
            if ($('#main-tab-itm').length) return;
            $('#main-layout').layout('collapse', 'west');
            openTab(4, 'main-tab-itm', "<span class='easyui-tooltip' title='Item'><i class='fa fa-gift fa-lg ' style='padding-top:7'></i></span>", 'main/itm', false);
        }
    });
    $('#menu-conv').linkbutton({
        width:'100%',
        text:'',
        disabled:true,
        iconCls:'fa fa-balance-scale fa-lg',
        onClick:function() {
            closeAll();
            if ($('#main-tab-conv').length) return;
            $('#main-layout').layout('collapse', 'west');
            openTab(5, 'main-tab-conv', "<span class='easyui-tooltip' title='Konversi'><i class='fa fa-balance-scale fa-lg ' style='padding-top:7'></i></span>", 'main/conv', false);
        }
    });
    $('#menu-sales').linkbutton({
        width:'100%',
        text:'',
        disabled:true,
        iconCls:'fa fa-chart-bar fa-lg',
        onClick:function() {
            closeAll();
            if ($('#main-tab-sales').length) return;
            $('#main-layout').layout('collapse', 'west');
            openTab(6, 'main-tab-sales', "<span class='easyui-tooltip' title='Sales'><i class='fa fa-chart-bar fa-lg ' style='padding-top:7'></i></span>", 'main/sales', false);
        }
    });
    $('#menu-trans').linkbutton({
        width:'100%',
        text:'',
        disabled:true,
        iconCls:'fa fa-history fa-lg',
        onClick:function() {
            closeAll();
            if ($('#main-tab-trans').length) return;
            $('#main-layout').layout('collapse', 'west');
            openTab(7, 'main-tab-trans', "<span class='easyui-tooltip' title='Trans'><i class='fa fa-history fa-lg ' style='padding-top:7'></i></span>", 'main/trans', false);
        }
    });
    $('#menu-po').linkbutton({
        width:'100%',
        text:'',
        disabled:true,
        iconCls:'fa fa-dolly-flatbed fa-lg',
        onClick:function() {
            closeAll();
            if ($('#main-tab-po').length) return;
            $('#main-layout').layout('collapse', 'west');
            openTab(8, 'main-tab-po', "<span class='easyui-tooltip' title='Purchase Order'><i class='fa fa-dolly-flatbed fa-lg ' style='padding-top:7'></i></span>", 'main/po', false);
        }
    });
    $('#menu-rcv').linkbutton({
        width:'100%',
        text:'',
        disabled:true,
        iconCls:'fa fa-truck fa-lg',
        onClick:function() {
            closeAll();
            if ($('#main-tab-rcv').length) return;
            $('#main-layout').layout('collapse', 'west');
            openTab(9, 'main-tab-rcv', "<span class='easyui-tooltip' title='Receiving Order'><i class='fa fa-truck fa-lg ' style='padding-top:7'></i></span>", 'main/rcv', false);
        }
    });
    $('#main-login').linkbutton({
        width:'100%',
        text:'',
        iconCls:'fa fa-sign-in-alt fa-lg',
        onClick:function() {showLogin();}
    });
}

$.extend($.fn.layout.methods, {
    close: function(jq, region) {
        return jq.each(function() {
            var c = $(this);
            closePanel(region);
            closePanel('expand'+region.substr(0,1).toUpperCase()+region.substr(1));
            c.layout('resize');
            function closePanel(region) {
                var p = c.layout('panel', region);
                if (p) {
                    p.panel('close');
                }
            }
        });
    },
    open: function(jq, region){
        return jq.each(function(){
            var c = $(this);
            var p = $(this).layout('panel', region);
            var p1 = $(this).layout('panel', 'expand'+region.substr(0,1).toUpperCase()+region.substr(1));
            if (p.panel('options').collapsed){
                p1.panel('open');
            } else {
                p.panel('open');
            }
            $(this).layout('resize');
        });
    }
}); 
function closeAll(){
    closeTab('#main-tab-posjual');
    closeTab('#main-tab-cust');
    closeTab('#main-tab-itm');
    closeTab('#main-tab-conv');
    closeTab('#main-tab-sales');
    closeTab('#main-tab-trans');
    closeTab('#main-tab-po');
    closeTab('#main-tab-rcv');
}
function arrangeView()
{
    // var link = $('#content').find('link:first');
    // link.attr('href', "<?= base_url('css/style-hwn.css') ?>");

    // fungsi ini dipanggil di login_view.js setelah login sukses
    // pertama adalah menutup semua tab yang terbuka kecuali dashboard
    
    closeTab('#main-tab-dashboard');
    closeAll();
    if(globalConfig.data){
        globalConfig.lok_nama=globalConfig.data.nama;
        globalConfig.alamat=globalConfig.data.alamat;
    }
    var modul =[];
    if(globalConfig.login_data){
        modul=globalConfig.login_data.funkode
        modul.indexOf("POS") > -1?modul.pos=1:modul.pos=0;
        modul.indexOf("CUST") > -1?modul.cust=1:modul.cust=0;
        modul.indexOf("ITM") > -1?modul.itm=1:modul.itm=0;
        modul.indexOf("CONV") > -1?modul.conv=1:modul.conv=0;
        modul.indexOf("SALES") > -1?modul.sales=1:modul.sales=0;
        modul.indexOf("TRANS") > -1?modul.trans=1:modul.trans=0;
        modul.indexOf("PO") > -1?modul.po=1:modul.po=0;
        modul.indexOf("RCV") > -1?modul.rcv=1:modul.rcv=0;
    }
    //$('#main-lokasi').html('Lokasi: <b>'+globalConfig.lok_nama+'</b>');
    var dashboardTitle = globalConfig.lok_nama+' (belum ada yang login)';
    if (globalConfig.login_data) {
        //$('#main-alamat').html('Alamat: <b>'+globalConfig.alamat+'</b>');
        var d = globalConfig.login_data;
        dashboardTitle = 'Selamat datang '+globalConfig.login_data.data.kas_nama+' di '+globalConfig.lok_nama;
        $('#menu-posjual').linkbutton(modul.pos==1?'enable':'disable');
        $('#menu-cust').linkbutton(modul.cust==1?'enable':'disable');
        $('#menu-itm').linkbutton(modul.itm==1?'enable':'disable');
        $('#menu-conv').linkbutton(modul.conv==1?'enable':'disable');
        $('#menu-sales').linkbutton(modul.sales==1?'enable':'disable');
        $('#menu-trans').linkbutton(modul.trans==1?'enable':'disable');
        $('#menu-po').linkbutton(modul.po==1?'enable':'disable');
        $('#menu-rcv').linkbutton(modul.rcv==1?'enable':'disable');
    }
    $('#main-tab').tabs('add',{
        index:0,
        id:'main-tab-posjual',
        title:dashboardTitle,
        href:'main/pos',
        closable:false
    });
    $('#main-layout').layout('close', 'west');
    var tabStripColor = 'yellow' // default
    $('#main-tab').tabs('setTabStyle', {
        which:0,
        background:tabStripColor,
        color:'black'
    });
}

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
            //spasi pakai \xa0\xa0\xa0\xa0\xa0\xa0
            const string = "Selamat Datang "+globalConfig.data.fullname+" di "+globalConfig.data.nama+" beralamat di "+globalConfig.data.alamat
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

function setTabTitle(id, title) {
    var strip = $(id).panel('options').tab.find('.tabs-title');
    strip.html(title);
}

function comboboxKeyHandler(nextid) {
    return $.extend({}, $.fn.combobox.defaults.keyHandler, {
        down:function(e) {
            $(this).combobox('showPanel');
            $.fn.combobox.defaults.keyHandler.down.call(this,e);
        },
        enter:function(e) {
            $(this).combobox('hidePanel');
            $.fn.combobox.defaults.keyHandler.down.call(this,e);
            $(nextid).textbox('textbox').focus();
        }
    });
}

function textboxInputEvents(nextid) {
    return $.extend({}, $.fn.textbox.defaults.inputEvents, {
        keypress:function(e) {
            if (e.which == 13) // enter
                $(nextid).textbox('textbox').focus();
        }
    });
}

function dateboxInputEvents(id, nextid) {
    return $.extend({}, $.fn.textbox.defaults.inputEvents, {
        keypress:function(e) {
            if (e.which == 13) // enter
                $(nextid).textbox('textbox').focus();
            else if (e.which == 99 || e.which == 67) // C or c for clear
                $(id).datebox('setValue', '');
        }
    });
}

$.extend($.fn.tabs.methods, {
    setTabStyle: function(jq, param){
        return jq.each(function(){
            var opts = $(this).tabs('options');
            var tab = $(this).tabs('getTab', param.which).panel('options').tab.find('.tabs-inner');
            tab.css(param);
            tab._outerHeight(param.height);
            var margin = opts.tabHeight-param.height;
            tab.css({
                lineHeight:tab[0].style.height,
                marginTop:(opts.tabPosition=='top'?margin+'px':0),
                marginBottom:(opts.tabPosition=='bottom'?margin+'px':0)
            });
        });
    }
});

$.fn.redraw = function(){
    $(this).each(function(){
      var redraw = this.offsetHeight;
    });
};

function setCookie(key, value) {
    document.cookie = key + '=' + value;
}

function getCookie(key) {
    var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
    return keyValue ? keyValue[2] : null;
}

function eraseCookie(key) {
    var keyValue = getCookie(key);
    setCookie(key, keyValue, '-1');
}

var mainSeeOccupied = null;

function showID(id) {
    if (mainSeeOccupied) {
        clearTimeout(mainSeeOccupied);
        hideID();
        showID(id);
    }
    else {
        //$('#main-see').html(id);
        mainSeeOccupied = setTimeout(function() {hideID()}, 5000);
    }
}

function hideID() {
    //$('#main-see').html('');
    mainSeeOccupied = null;
}

function formatMoney(number, decPlaces, decSep, thouSep) {
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSep = typeof decSep === "undefined" ? "." : decSep;
    thouSep = typeof thouSep === "undefined" ? "," : thouSep;
    var sign = number < 0 ? "-" : "";
    var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
    var j = (j = i.length) > 3 ? j % 3 : 0;

    return sign +
        (j ? i.substr(0, j) + thouSep : "") +
        i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
        (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
}

function printDiv(divid,title) {
    var contents = document.getElementById(divid).innerHTML;
    var frame1 = document.createElement('iframe');
    frame1.name = "frame1";
    frame1.style.position = "absolute";
    frame1.style.top = "-1000000px";
    document.body.appendChild(frame1);
    var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
    frameDoc.document.open();
    frameDoc.document.write('<html><head><title>'+title+'</title>');
    frameDoc.document.write('</head><body>');
    frameDoc.document.write(contents);
    frameDoc.document.write('</body></html>');
    frameDoc.document.close();
    setTimeout(function () {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        document.body.removeChild(frame1);
    }, 500);
    return false;
}

function printCompanyHeader(divid) {
    $('#'+divid).append('<tr><td><table border="1" cellspacing="0" '+
        'style="border-collapse:unset;font-size:15px"><tr>'+
        '<td width="0%" style="padding:5px">'+
            '<img src="public/images/Logo-Kabupaten-Situbondo-Vector.jpg" height="120px">'+
        '</td>'+
        '<td width="100%" style="padding:5px;text-align:center;vertical-align:middle">'+
            '<b style="font-size:24px">RS ProfeDemo Batam</b><br>'+
            '<span style="font-size:16px; padding:5px;">Komplek Bisnis Alexandria, Jl. Ahmad Yani, Batam 29444 </span>'+
            '<span style="font-size:16px; padding:5px;">Telp. (0778) 5120719, Email: management@reendoo.com </span>'+
        '</td>'+
        '<td width="0%" style="padding:5px">'+
            '<img src="public/images/logo-kop-rsa.jpg" height="120px">'+
        '</td>'+
    '</tr></table></td></tr>');
}

function printInfo(divid, items) {
    var i = 0;
    $.each(items, function(index, obj) {
        var label = obj.label;
        var value = obj.value;
        var whiteSpace = obj.nowarp?'nowrap':'normal';
        $('#'+divid).append('<tr><td style="'+
            'padding: 3px 6px 3px 6px;'+
            'font-weight: bold;'+
            'font-size: 14px;'+
            'vertical-align: middle;'+
            'white-space: nowrap;'+
            'width: 0%;'+
            'background-color: #bbbbbb">'+label+'</td><td style="'+
            'padding: 3px 6px 3px 6px;'+
            'font-size: 14px;'+
            'vertical-align: middle;'+
            'width: 100%;'+
            'white-space: '+whiteSpace+';'+
            'background-color: #eeeeee">'+value+'</td></tr>');
        i++;
    });
}

function printInfoMC(divid, items) {
    var i = 0;
    $.each(items, function(index, obj) {
        var label = obj.label;
        var value1 = obj.value1;
        var value2 = obj.value2;
        var whiteSpace = obj.nowarp?'nowrap':'normal';
        $('#'+divid).append('<tr><td style="'+
            'padding: 3px 6px 3px 6px;'+
            'font-weight: bold;'+
            'font-size: 14px;'+
            'vertical-align: middle;'+
            'white-space: nowrap;'+
            'width: 0%;'+
            'background-color: #bbbbbb">'+label+'</td><td style="'+
            'padding: 3px 6px 3px 6px;'+
            'font-size: 14px;'+
            'vertical-align: middle;'+
            'width: 0%;'+
            'white-space: nowrap;'+
            'background-color: #eeeeee">'+value1+'</td><td style="'+
            'padding: 3px 6px 3px 6px;'+
            'font-size: 14px;'+
            'vertical-align: middle;'+
            'width: 100%;'+
            'white-space: '+whiteSpace+';'+
            'background-color: #eeeeee">'+value2+'</td></tr>');
        i++;
    });
}
function printInfoLab(divid, items) {
    var i = 0;
    $.each(items, function(index, obj) {
        var label = obj.label;
        var value1 = obj.value1;
        var value2 = obj.value2;
        var value3 = obj.value3;
        var whiteSpace = obj.nowarp?'nowrap':'normal';
        $('#'+divid).append('<tr><td style="'+
            'padding: 3px 6px 3px 6px;'+
            'font-weight: bold;'+
            'font-size: 14px;'+
            'white-space: nowrap;'+
            'width: 20%;'+
            'background-color: #bbbbbb">'+label+'</td><td style="'+
            'padding: 3px 6px 3px 6px;'+
            'font-size: 14px;'+
            'width: 5%;'+
            'white-space: nowrap;'+
            'background-color: #eeeeee">'+value1+'</td><td style="'+
            'padding: 3px 6px 3px 6px;'+
            'font-size: 14px;'+
            'width: 18%;'+
            'white-space: nowrap;'+
            'background-color: #eeeeee">'+value2+'</td><td style="'+
            'padding: 3px 6px 3px 6px;'+
            'font-size: 14px;'+
            'width: 100%;'+
            'white-space: '+whiteSpace+';'+
            'background-color: #eeeeee">'+value3+'</td></tr>');
        i++;
    });
}

function printColumnHeader(divid, columns) {
    $('#'+divid).append('<tr><td><table id="'+divid+'-detail" border="0" '+
    'cellspacing="0" width="100%"></table></td></tr>');
    $('#'+divid+'-detail').append('<tr id="'+divid+'-header"></tr>');
    $.each(columns, function(index, obj) {
        var title = obj.title?obj.title:'';
        var width = obj.width?obj.width:0;
        var align = obj.align?obj.align:'left';
        $('#'+divid+'-header').append('<td style="width:'+
            width+'%;text-align:'+align+';'+
            'padding: 3px 6px 3px 6px;'+
            'font-weight: bold;'+
            'font-size: 14px;'+
            'vertical-align: middle;'+
            'border-top: 1px solid black;'+
            'border-bottom: 1px solid black;'+
            'white-space: nowrap;'+
            'background-color: #eeeeee">'+title+'</td>');
    });
}

function printRow(divid, index, stripped, row, group = false) {
    $('#'+divid).append('<tr><td><table id="'+divid+'-detail" border="0" '+
    'cellspacing="0" width="100%"></table></td></tr>');
    var rowid = divid+'-row-'+index;
    $('#'+divid+'-detail').append('<tr id="'+rowid+'"></tr>');
    $.each(row, function(index, obj) {
        var value = obj.value?obj.value:'';
        var width = obj.width?obj.width:0;
        var align = obj.align?obj.align:'left';
        $('#'+rowid).append('<td style="width:'+
            width+'%;text-align:'+align+';'+
            'padding: 3px 6px 3px 6px;'+
            'font-weight: '+(group?'bold':'normal')+';'+
            'font-size: 14px;'+
            'vertical-align: middle;'+
            'border-bottom: 0px solid black;'+
            'white-space: nowrap;'+
            'background-color: #'+(stripped?'fff6c5':'ffffff')+'">'+
            value+'</td>');
    });
}

function printSignature(divid, columns) {
    $('#'+divid).append('<tr><td><table id="'+divid+'-detail" border="0" '+
    'cellspacing="0" width="100%"></table></td></tr>');
    $('#'+divid+'-detail').append('<tr id="'+divid+'-signature"></tr>');
    $.each(columns, function(index, obj) {
        var str = '';
        if (obj.title) {
            var title = obj.title?obj.title:'';
            var name = obj.name?obj.name:'';
            var id = obj.id?obj.id:'';
            str = title+'<br><br><br><br><br><b>'+name+'</b><br>'+id;
        }
        $('#'+divid+'-signature').append('<td style="width:33%;'+
            'text-align:center;'+
            'padding: 3px 6px 3px 6px;'+
            'font-size: 14px;'+
            'vertical-align: middle;'+
            'border-top: 1px solid black;'+
            'border-bottom: 1px solid black;'+
            'white-space: nowrap;'+
            'background-color: #ffffff">'+str+'</td>');
    });
}

function terbilang(bilangan) {
    bilangan    = String(bilangan);
    var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
    var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
    var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');
   
    var panjang_bilangan = bilangan.length;
   
    /* pengujian panjang bilangan */
    if (panjang_bilangan > 15) {
        kaLimat = "Diluar Batas";
        return kaLimat;
    }
   
    /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
    for (i = 1; i <= panjang_bilangan; i++) {
        angka[i] = bilangan.substr(-(i),1);
    }
   
    i = 1;
    j = 0;
    kaLimat = "";
   
    /* mulai proses iterasi terhadap array angka */
    while (i <= panjang_bilangan) {
        subkaLimat = "";
        kata1 = "";
        kata2 = "";
        kata3 = "";

        /* untuk Ratusan */
        if (angka[i+2] != "0") {
            if (angka[i+2] == "1") kata1 = "Seratus";
            else kata1 = kata[angka[i+2]] + " Ratus";
        }

        /* untuk Puluhan atau Belasan */
        if (angka[i+1] != "0") {
            if (angka[i+1] == "1") {
                if (angka[i] == "0") kata2 = "Sepuluh";
                else if (angka[i] == "1") kata2 = "Sebelas";
                else kata2 = kata[angka[i]] + " Belas";
            }
            else
                kata2 = kata[angka[i+1]] + " Puluh";
        }

        /* untuk Satuan */
        if (angka[i] != "0")
            if (angka[i+1] != "1")
                kata3 = kata[angka[i]];

        /* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
        if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0"))
            subkaLimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";

        /* gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat */
        kaLimat = subkaLimat + kaLimat;
        i = i + 3;
        j = j + 1;
    }
   
    /* mengganti Satu Ribu jadi Seribu jika diperlukan */
    if ((angka[5] == "0") && (angka[6] == "0"))
        kaLimat = kaLimat.replace("Satu Ribu","Seribu");
   
    return kaLimat + "Rupiah";
}

$.extend($.fn.panel.methods, {
    showMask: function(jq) {
        return jq.each(function() {
            var pal = $(this).panel('panel');
            if (pal.css('position').toLowerCase() != 'absolute')
                pal.css('position','relative');
            var borderSize = parseInt(pal.css('padding')||0);
            var m = pal.children('div.panel-mask');
            if (!m.length)
                m = $('<div class="panel-mask"></div>').appendTo(pal);
            m.css({
                position:'absolute',
                backgroundColor:'rgba(255,255,255,0.4)',
                left:borderSize,
                top:(borderSize+pal.children('.panel-header')._outerHeight()),
                right:borderSize,
                bottom:borderSize
            });
            m.children('div.panel-mask-msg').remove();
            var mm = $('<div class="panel-mask-msg"></div>').appendTo(m);
            var msg = '<span class="tree-loading" '+
                'style="display:inline-block;width:30px;height:24px;vertical-align:middle">'+
                '</span><span style="display:inline-block;line-height:24px;vertical-align:middle;">'+
                'Processing, please wait...</span>';
            mm.html(msg).css({position:'absolute'}).css({
                position:'absolute',
                top: '50%',
                left: '50%',
                backgroundColor:'rgba(255,255,255,1)',
                marginTop: -mm._outerHeight()/2,
                marginLeft: -mm._outerWidth()/2,
                padding: '5px 8px 5px 5px',
                borderStyle: 'solid',
                borderColor: '#95b8e7',
                borderWidth: '2px'
            })
        });
    },
    hideMask: function(jq){
        return jq.each(function(){
            $(this).panel('panel').children('div.panel-mask').remove();
        })
    }
});

function currencyFormat(number) {
    return new Intl.NumberFormat('de-DE').format(number);
}

function isDemo() {
    var demo = false;

    // fungsi ini dinonaktifkan dengan cara selalu memberi nilai 'live'
    // pada type. Meskipun asalnya diset 'demo'
    // karena database sudah dipisah, sehingga 'demo'
    // bisa mengubah data
    
    globalConfig.login_data.type = 'live';
    if (globalConfig.login_data.type == 'demo') {
        $.messager.alert(globalConfig.app_nama,
            'Maaf, untuk login demo belum diperbolehkan melakukan aktivitas mengubah data');
        demo = true;
    }
    return demo;
}
function setCapitalSentenceEveryWord(str) {
    var spart = str.split(" ");
    var u,l;
    for (var i=0;i<spart.length;i++) {
        u = spart[i].charAt(0).toUpperCase();
        l = spart[i].substr(1).toLowerCase();
        spart[i] = u+l;
    }
    return spart.join(" ");
}
function getLabel(){
    if(globalConfig.login_data.lang == 1){
        $('#man-btn-add').linkbutton({text: 'Add'});
        $('#man-btn-save').linkbutton({text: 'Save'});
        $('#man-btn-cancel').linkbutton({text: 'Cancel'});
        $('#man-btn-del').linkbutton({text: 'Delete'});
        $('#man-btn-kartupas').linkbutton({text: 'Print Card'});
        //
        $('#man-form-id-label').html('ID');
        $('#man-form-norm-label').html('No MR');
        $('#man-form-nama-label').html('Name');
        $('#man-form-kotalahir-label').html('Place of birth');
        $('#man-form-tglahir-label').html('Date of birth');
        $('#man-form-alamatktp-label').html('Address On ID Card');
        $('#man-form-alamatskrng-label').html('Address Now');
        $('#man-form-kodepos-label').html('Postal Code');
        $('#man-form-area-label').html('City');
        $('#man-form-area-desa-label').html('Village');
        $('#man-form-dusun-label').html('Dusun');
        $('#man-form-rw-label').html('RT');
        $('#man-form-rt-label').html('RW');
        $('#man-form-bahasa-label').html('Language');
        $('#man-form-pembayaran-label').html('Payment');
        $('#man-form-noasuransi-label').html('Assurance Number');
        $('#man-form-nik-label').html('National Identity Number');
        $('#man-form-nokk-label').html('KK');
        $('#man-form-telpon-label').html('Telp. number ');
        $('#man-form-kelamin-label').html('Sex');
        $('#man-form-goldarah-label').html('Blood Type');
        $('#man-form-rmibu-label').html('RM Mother');
        $('#man-form-kebangsaan-label').html('Nationality');
        $('#man-form-sukubangsa-label').html('Ethnic');
        $('#man-form-pekerjaan-label').html('Job');
        $('#man-form-pendakhir-label').html('Last Education');
        $('#man-form-statusnikah-label').html('Marital Status');
        $('#man-form-agama-label').html('Religion');
        $('#man-form-namafam-label').html('Name');
        $('#man-form-hubfam-label').html('Relation');
        $('#man-form-alamatfam-label').html('Address');
        $('#man-form-telpfam-label').html('Telp. Number');
        $('#man-ket').html('Name of family member who can be called when emergency');
        $('#man-pencarian').html('Searching based on');
        var manusia = $('#man-grid');
        var columns = manusia.datagrid('options').columns;
        columns[0][1].title = 'No. MR';
        columns[0][2].title = 'Name';
        columns[0][3].title = 'Place of Birth';
        columns[0][4].title = 'Date of Birth';
        columns[0][5].title = 'Age';
        columns[0][6].title = 'Sex';
        columns[0][7].title = 'Address On ID Card';
        columns[0][8].title = 'Address Now';
        columns[0][9].title = 'City';
        columns[0][10].title = 'Postal Code';
        columns[0][11].title = 'Payment';
        columns[0][12].title = 'Assurance Number';
        columns[0][13].title = 'National Identity Number';
        columns[0][15].title = 'Telp Number';
        columns[0][16].title = 'Blood Type';
        columns[0][17].title = 'Nationality';
        columns[0][18].title = 'Ethnic';
        columns[0][19].title = 'Job';
        columns[0][20].title = 'Last Education';
        columns[0][21].title = 'Marital Status';
        columns[0][22].title = 'Religion';
        columns[0][23].title = 'Name of Member Family';
        columns[0][24].title = 'Relation';
        columns[0][25].title = 'Address of Member Family';
        columns[0][26].title = 'Telp. Number of Member Family';
        manusia.datagrid({columns:columns});
    }
}