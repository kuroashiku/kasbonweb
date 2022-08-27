<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>ReenDoo</title>
    <link rel="stylesheet" type="text/css" href="<?= base_url('easyui/themes/default/easyui.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('easyui/themes/icon.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/fontawesome/css/all.min.css') ?>">
    <script type="text/javascript" src="<?= base_url('easyui/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('easyui/jquery.easyui.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('easyui/jquery.portal.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('easyui/datagrid-cellediting.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('easyui/datagrid-export.js') ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url('jqplot/jquery.jqplot.css') ?>" />
    <script type="text/javascript" src="<?= base_url('jqplot/jquery.jqplot.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('jqplot/plugins/jqplot.pieRenderer.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('jqplot/plugins/jqplot.donutRenderer.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('jqplot/plugins/jqplot.barRenderer.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('jqplot/plugins/jqplot.categoryAxisRenderer.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('jqplot/plugins/jqplot.pointLabels.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('js/Chart.js') ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/style-man.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/snackbar.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/bootstrap.min.css') ?>">
    <script type="text/javascript" src="<?= base_url('js/custom/login_view.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('js/custom/updatelogin_view.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('js/custom/registrasi_view.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('js/custom/utility.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('js/custom/snackbar_view.js') ?>"></script>
    <style>
        
    #tulisanberjalan {
        background-color:#e0ecff;
        border-color: #95b8e7;
        width:100%;
        height:25px;
        color:#000;
        font-weight: bold;
        font-family:'Trebuchet MS', sans-serif;
        font-size:16px;
    }
    
	span.tabs-title span.tabs-with-icon{
		padding: 0;
	}

    .tabs-header{
        display:none;
    }
    .tabs-wrap{
        height:0;
    }
	</style>
</style>
    </style>
</head>
<body style="margin:0" class="content">
    
    <div id="main-panel" data-options="fit:true">
    
        <div class="easyui-layout" id="main-layout"
            data-options="fit:true,border:false,animate:false">
            <div data-options="region:'west',
                collapsed:true,
                hideCollapsedContent:false,
                animation:false,
                split:false,
                border:false"
                title="Menu"
                style="width:45;background-color:#e8f0ff;padding:6px">
                <div id="menu-posjual" title="Pos/jual"></div>
                <div id="menu-cust" title="Kustomer"></div>
                <div id="menu-itm" title="Item"></div>
                <div id="menu-conv" title="Konversi"></div>
                <div id="menu-sales" title="Sales"></div>
                <div id="menu-trans" title="Transaksi"></div>
                <div id="menu-po" title="PO"></div>
                <div id="menu-rcv" title="Penerimaan"></div>
                <!-- <div id="main-login" title="Login" style="margin-top:270px;"></div> -->
            </div>
            <div data-options="region:'center',border:false">
                <div id="main-tab" style="padding:0px;">
                </div>
            </div>
            <div data-options="region:'south',border:false" style="height:30px">
            <table width="100%">
                <tr>
                    <td id="tulisanberjalan" style="font-size:18px; padding-left:10px" width="25%"></td>
                    <td id="main-buttons" width="0%">
                        <div class="easyui-linkbutton" id="login-btn-load"
                            data-options="iconCls:'fa fa-sign-in-alt fa-lg',
                                height:24,
                                onClick:function() {showLogin();}">
                        </div>
                    </td>
                </tr>
            </table>
            </div>
        </div>
    <div>
        
    <div id="login-dlg"></div>
    <div id="updatelogin-dlg"></div>
    <div id="register-dlg"></div>
    <div id="snackbar-container"></div>
    <!-- <div id="main-footer" style="padding:0px 5px;font-size:14px">
        <table width="100%">
            <tr>
                <td id="main-lokasi" style="font-size:14px" width="25%"></td>
                <td id="main-alamat" style="font-size:14px" width="75%"></td>
                <td id="main-see" style="font-size:10px;color:grey" width="0%"></td>
                <td id="main-buttons" width="0%">
                    <div class="easyui-linkbutton" id="login-btn-load"
                        data-options="iconCls:'fa fa-ellipsis-v fa-lg',
                            height:24,
                            plain:true,
                            onClick:function() {showLogin();}">
                    </div>
                </td>
            </tr>
        </table> -->
    </div> -->
</body>
</html>

<script type="text/javascript">
    var globalConfig = getConfig();
    var globalrow=[];
    var globalharga=0;
    var globaluang=0;
    var itemflag=0;
    $('#pos-item-gambar-dlg').dialog().dialog("close");
    $('#pos-item-gambar-dlg').remove();
    function watchObject(obj) {
        var i = 0;
    }
    (function($) {
        jQuery.fn.font_resizer = function () {
            var self = $(this);
            var fontSize = self.css('fontSize').slice(0, -2);
            var lineH = self.css('lineHeight').slice(0, -2);
            jQuery(self).resize_font(fontSize, lineH);
            jQuery(window).on('resize', function () {
                var p = $('#main-tab').tabs('getTab', 0);  
                p.panel('refresh');                
            });
        };

        //on window resize set the font and line height
        jQuery.fn.resize_font = function (fontSize, lineH) {        
            var self = $(this);
            var p = $('#main-tab').tabs('getTab', 0);  
            p.panel('refresh');                
        };
    }(jQuery));
    $(window).bind('resize', function(e) {   
        // get the first tab panel
        var p = $('#main-tab').tabs('getTab', 0);  
        p.panel('refresh');                
    });
    $(function() {
        $('#main-tab').tabs({
            border:false,
            fit:true,
            tabHeight:29,
            onAdd:function(title,index) {
                var id = $(this).tabs('getTab', title).panel('options').id;
                var tlc = id.split('-');
                if ($('#menu-'+tlc[2]).length)
                    $('#menu-'+tlc[2]).linkbutton('disable');
            },
            onBeforeClose:function(title,index) {
                var id = $(this).tabs('getTab', title).panel('options').id;
                var tlc = id.split('-');
                if ($('#menu-'+tlc[2]).length)
                    $('#menu-'+tlc[2]).linkbutton('enable');
            }
        });
        $('#main-panel').panel({
            fit:true,
            border:false,
            footer:'#main-footer',
            onResize:function(w,h) {
                // masih dikerjakan pak Mendin
            },
            onOpen:function() {
                showMenu();
                arrangeView();
                var u = getCookie('grexdkiw');
                var p = getCookie('zlpiwrhc');
                loginSubmit(u, p, false);
            }
        });
        $("body").font_resizer();
    });
</script>