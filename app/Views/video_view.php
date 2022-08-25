<link rel="stylesheet" type="text/css" href="<?= base_url('easyui/themes/default/easyui.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('easyui/themes/icon.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('css/fontawesome/css/all.min.css') ?>">
<script type="text/javascript" src="<?= base_url('easyui/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('easyui/jquery.easyui.min.js') ?>"></script>
<div class="easyui-panel"
    id="vid-panel"
    data-options="
        fit:true,
        border:false,
        title:'Video Tutorial'
    ">
</div>
<div id="dlg-penilaian"></div>
<script type="text/javascript">
    var urlList = [
        'https://www.youtube.com/embed/B71knhpJNyo?rel=0',
        'https://www.youtube.com/embed/7FeTMZT82HE?rel=0',
        'https://www.youtube.com/embed/T-eMH60VmgU?rel=0',
        'https://www.youtube.com/embed/zhrWC_3JhWw?rel=0',
        'https://www.youtube.com/embed/Zi1NUsgsAiQ?rel=0',
        'https://www.youtube.com/embed/_Zr3eCajoaM?rel=0',
        'https://www.youtube.com/embed/sJgeO4BW4cg?rel=0',
        'https://www.youtube.com/embed/plXQLLaV9uI?rel=0'
    ];
    $.each(urlList, function(index, mp4url) {
        $('#vid-panel').append('<div id="vid-'+index+'" style="float:left;margin:20px 0 0 20px"></div>'); 
        $('#vid-'+index).append('<div><iframe width="480" height="270" '+
            'style="border:none" '+
            'src='+mp4url+' allowfullscreen></iframe></div>'); 
        $('#vid-'+index).append('<div id="vid-btn-'+index+'" style="margin:5px 0 0 0"></div>')
        $('#vid-btn-'+index).linkbutton({
            text:'Penilaian',
            onClick:function() {
                $('#dlg-penilaian').dialog({
                    title: 'Penilaian',
                    width: 600,
                    height: 400,
                    closed: false,
                    cache: false,
                    modal: true,
                    href: 'video/penilaian'
                });
            }
        }) 
    });
</script>