$("#umur").change(function () {
    var val = $(this).val();
    if (val == "dewasa") {
        for(var i=1;i<=32;i++){
            document.getElementById("gigi"+i).getContext("2d").clearRect(0, 0, 50, 50);
            var img = document.getElementById("gigi"+i).getContext("2d");
            $("#tindakan").val("").change();
            $("#keadaan").val("").change();
            $("#permukaan").val("").change();
            dewasa(); 
        }
        for(var i=33;i<=52;i++){    
            document.getElementById("gigi"+i).getContext("2d").clearRect(0, 0, 50, 50);
            var img = document.getElementById("gigi"+i).getContext("2d");
            $("#tindakan").val("").change();
            $("#keadaan").val("").change();
            $("#permukaan").val("").change();
            img.beginPath();
            img.moveTo(0, 25);
            img.lineTo(50, 25);
            img.closePath();
            img.stroke();
            susu();
        }
        $("#urutan").html("<option value=''>Pilih Nomor</option><option value='gigi1'>18</option><option value='gigi2'>17</option><option value='gigi3'>16</option><option value='gigi4'>15</option><option value='gigi5'>14</option><option value='gigi6'>13</option><option value='gigi7'>12</option><option value='gigi8'>11</option><option value='gigi9'>21</option><option value='gigi10'>22</option><option value='gigi11'>23</option><option value='gigi12'>24</option><option value='gigi13'>25</option><option value='gigi14'>26</option><option value='gigi15'>27</option><option value='gigi16'>28</option><option value='gigi17'>48</option><option value='gigi18'>47</option><option value='gigi19'>46</option><option value='gigi20'>45</option><option value='gigi21'>44</option><option value='gigi22'>43</option><option value='gigi23'>42</option><option value='gigi24'>41</option><option value='gigi25'>31</option><option value='gigi26'>32</option><option value='gigi27'>33</option><option value='gigi28'>34</option><option value='gigi29'>35</option><option value='gigi30'>36</option><option value='gigi31'>37</option><option value='gigi32'>38</option>");
    } else if (val == "susu") {
        for(var i=33;i<=52;i++){    
            document.getElementById("gigi"+i).getContext("2d").clearRect(0, 0, 50, 50);
            var img = document.getElementById("gigi"+i).getContext("2d");
            $("#tindakan").val("").change();
            $("#keadaan").val("").change();
            $("#permukaan").val("").change();
            susu();
        }
        for(var i=1;i<=32;i++){ 
            document.getElementById("gigi"+i).getContext("2d").clearRect(0, 0, 50, 50); 
            var img = document.getElementById("gigi"+i).getContext("2d");
            $("#tindakan").val("").change();
            $("#keadaan").val("").change();
            $("#permukaan").val("").change();
            img.beginPath();
            img.moveTo(0, 25);
            img.lineTo(50, 25);
            img.closePath();
            img.stroke();
            dewasa(); 
        }
        $("#urutan").html("<option value=''>Pilih Nomor</option><option value='gigi33'>55</option><option value='gigi34'>54</option><option value='gigi35'>53</option><option value='gigi36'>52</option><option value='gigi37'>51</option><option value='gigi38'>61</option><option value='gigi39'>62</option><option value='gigi40'>63</option><option value='gigi41'>64</option><option value='gigi42'>65</option><option value='gigi43'>85</option><option value='gigi44'>84</option><option value='gigi45'>83</option><option value='gigi46'>82</option><option value='gigi47'>81</option><option value='gigi48'>71</option><option value='gigi49'>72</option><option value='gigi50'>73</option><option value='gigi51'>74</option><option value='gigi52'>75</option>");
    
    } 
});
$('#pertama').hide();$('#kedua').hide();$('#urutan').show();$('.jembatan').hide();$('.ext').show();
$('#tindakan').change(function(e){
    if($(this).val() == "pob"){
        //$("#lanjut option[value='pon']").prop('disabled',true); 
        $('#pertama').show();$('#kedua').show();$('#urutan').hide();$('.jembatan').show();$('.ext').hide();
    }
    else {
        $('#pertama').hide();$('#kedua').hide();$('#urutan').show();$('.jembatan').hide();$('.ext').show();
    }
});
var patternCanvas = document.getElementById('strip')
var patternContext = patternCanvas.getContext('2d');
patternCanvas.width = 5;
patternCanvas.height = 5;
patternContext.fillStyle = "rgb(219,112,147)";
patternContext.fillRect(0, 0, patternCanvas.width, patternCanvas.height);
patternContext.moveTo(5,0);
patternContext.lineTo(0,5);
patternContext.stroke();
const canvas = document.createElement('canvas');
const ctxdental = canvas.getContext('2d');
const pattern = ctxdental.createPattern(patternCanvas, 'repeat');
ctxdental.fillStyle = pattern;
ctxdental.fillRect(0, 0, canvas.width, canvas.height);
////////////////////////////////////////////////////////////

function jembatan(pertama,kedua){
    //$("#tindakan").val("pob").change();
    if((pertama<=16&&kedua<=16)||(pertama<=42&&pertama>=33&&kedua<=42&&kedua>=33)){
        // if(pertama<6||(pertama<=21&&pertama>=12)||(pertama<=34&&pertama>=28)||(pertama<=44&&pertama>=41)||pertama>=51){
        //     graham("gigi"+pertama);
        // }
        // else{
        //     seri("gigi"+pertama);
        // }
        // if(kedua<6||(kedua<=21&&kedua>=12)||(kedua<=34&&kedua>=28)||(kedua<=44&&kedua>=41)||kedua>=51){
        //     graham("gigi"+kedua);
        // }
        // else{
        //     seri("gigi"+kedua);
        // }
        var ponbfr=document.getElementById("lgigi"+kedua).getContext("2d")
        ponbfr.beginPath();
        ponbfr.moveTo(0, 20);
        ponbfr.lineTo(25, 20);
        ponbfr.lineTo(25, 0);
        ponbfr.lineTo(25, 20);
        ponbfr.closePath();
        ponbfr.stroke();
        
        var ponafr=document.getElementById("lgigi"+pertama).getContext("2d")
        ponafr.beginPath();
        ponafr.moveTo(50, 20);
        ponafr.lineTo(25, 20);
        ponafr.lineTo(25, 0);
        ponafr.lineTo(25, 20);
        ponafr.closePath();
        ponafr.stroke();
        var parspertama=parseInt(pertama)+1;
        var parskedua=parseInt(kedua)-1;
        for(var i=parspertama; i<=parskedua; i++){
            var img = document.getElementById("lgigi"+i).getContext("2d");
            img.beginPath();
            img.moveTo(0, 20);
            img.lineTo(50, 20);
            img.closePath();
            img.stroke();
        }
    }
    else if(pertama<=32&&pertama>=17&&kedua<=32&&kedua>=17||(pertama<=52&&pertama>=43&&kedua<=52&&kedua>=43)){
        // if(pertama<6||(pertama<=22&&pertama>=12)||(pertama<=34&&pertama>=28)||(pertama<=44&&pertama>=41)||pertama>=51){
        //     graham("gigi"+pertama);
        // }
        // else{
        //     seri("gigi"+pertama);
        // }
        // if(kedua<6||(kedua<=22&&kedua>=12)||(kedua<=34&&kedua>=28)||(kedua<=44&&kedua>=41)||kedua>=51){
        //     graham("gigi"+kedua);
        // }
        // else{
        //     seri("gigi"+kedua);
        // }
        var ponbfr=document.getElementById("lgigi"+kedua).getContext("2d")
        ponbfr.beginPath();
        ponbfr.moveTo(0, 30);
        ponbfr.lineTo(25, 30);
        ponbfr.lineTo(25, 50);
        ponbfr.lineTo(25, 30);
        ponbfr.closePath();
        ponbfr.stroke();
        
        var ponafr=document.getElementById("lgigi"+pertama).getContext("2d")
        ponafr.beginPath();
        ponafr.moveTo(50, 30);
        ponafr.lineTo(25, 30);
        ponafr.lineTo(25, 50);
        ponafr.lineTo(25, 30);
        ponafr.closePath();
        ponafr.stroke();
        var parspertama=parseInt(pertama)+1;
        var parskedua=parseInt(kedua)-1;
        for(var i=parspertama; i<=parskedua; i++){
            var img = document.getElementById("lgigi"+i).getContext("2d");
            img.beginPath();
            img.moveTo(0, 30);
            img.lineTo(50, 30);
            img.closePath();
            img.stroke();
        }
    }
}
$('.baca').click(function() {
    var yangrid = $('#yan-grid').datagrid('getSelected');
    $.ajax({
        url:getRestAPI('kundental/read'),
        type:'POST',
        data:{
            den_kun_id:yangrid.kun_id,
            db:getDB()
        },
        dataType: 'json',
        success: function(response) {
            console.log(response)
            $.each(response.rows,function(key, fields){ 
                var tindakan = $('#tindakan :selected').val();
                var nomor = $('#urutan :selected').val();
                tindakan=$("#tindakan").val(fields.den_tin).change();
                nomor="gigi"+fields.den_nogigi;
                keadaan=$("#keadaan").val(fields.den_keadaan).change();
                permukaan=$("#permukaan").val(fields.den_posisi).change();
                var pisah=(fields.den_jembatan).split("-")
                pertama=pisah[0];
                kedua=pisah[1];
                parspertama=parseInt(pertama);
                parskedua=parseInt(kedua);
                if(pertama!="NaN"||kedua!="NaN"){
                    console.log(parspertama)
                    console.log(parskedua)
                    jembatan(parspertama,parskedua)

                }
                // if(pertama!="NaN"||kedua!="NaN"){
                //     jembatan(parspertama,parskedua);
                // }
                if(fields.den_keterangan){
                    var string=(fields.den_keterangan).split(',');
                    $("#pilihanke1").val(string[0]).change();
                    $("#pilihanke2").val(string[1]).change();
                    $("#pilihanke3").val(string[2]).change();
                    $("#pilihanke4").val(string[3]).change();
                    $("#pilihanke5").val(string[4]).change();
                }
                document.getElementById(nomor).getContext("2d").clearRect(0, 0, 50, 50);
                var tipe=nomor.substring(0,2);
                $('#t'+nomor).html('');
                //$('#l'+nomor).html(''); 
                angka=nomor.substring(4, 6);
                angka=parseInt(angka);
                if(angka<6||(angka<=21&&angka>=12)||(angka<=34&&angka>=28)||(angka<=44&&angka>=41)||angka>=51){
                    if(fields.den_tin=="pob"){
                        graham("gigi"+pertama)
                        graham("gigi"+kedua)
                    }
                    else
                        graham(nomor);
                }
                else{
                    if(fields.den_tin=="pob"){
                        seri("gigi"+pertama)
                        seri("gigi"+kedua)}
                    else
                        seri(nomor);
                }
            });
        }
    });
});
$('.kirim').click(function() {
    if (isDemo()) return;
    var tindakan = $('#tindakan :selected').val();
    var keadaan = $('#keadaan :selected').val();
    var nomor = $('#urutan :selected').val();
    var pertamaasli=parseInt($('#pertama').val());
    var keduaasli=parseInt($('#kedua').val());
    var array = {18:1,17:2,16:3,15:4,14:5,13:6,12:7,11:8,21:9,22:10,23:11,24:12,25:13,26:14,27:15,28:16,
        48:17,47:18,46:19,45:20,44:21,43:22,42:23,41:24,31:25,32:26,33:27,34:28,35:29,36:30,37:31,38:32,
        55:33,54:34,53:35,52:36,51:37,61:38,62:39,63:40,64:41,65:42,85:43,84:44,83:45,82:46,81:47,71:48,72:49,73:50,74:51,75:52};
    var pertama=array[pertamaasli];
    var kedua=array[keduaasli];
    var jembatan;
    var yangrid = $('#yan-grid').datagrid('getSelected');
    jembatan=pertama+"-"+kedua;
    if(nomor=='')nomor="gigi0";
    var permukaan = $('#permukaan :selected').val(); 
    var string = $('#pilihanke1 :selected').val()+","+$('#pilihanke2 :selected').val()+","+
        $('#pilihanke3 :selected').val()+","+$('#pilihanke4 :selected').val()+","+
        $('#pilihanke5 :selected').val()
    angka=nomor.substring(4, 6);
    angka=parseInt(angka);
    $.ajax({
        url:getRestAPI('kundental/save'),
        type:'POST',
        data:{
            den_kun_id:yangrid.kun_id,
            nomor:angka,
            tindakan:tindakan,
            keadaan:keadaan,
            permukaan:permukaan,
            jembatan:jembatan,
            keterangan:string,
            db:getDB()
        },
        dataType: 'json',
        success: function(response) {
            $('.baca').click();
        }
    });
});
$('.jembatan').click(function() {
    var pertamaasli=parseInt($('#pertama').val());
    var keduaasli=parseInt($('#kedua').val());
    var array = {18:1,17:2,16:3,15:4,14:5,13:6,12:7,11:8,21:9,22:10,23:11,24:12,25:13,26:14,27:15,28:16,
        48:17,47:18,46:19,45:20,44:21,43:22,42:23,41:24,31:25,32:26,33:27,34:28,35:29,36:30,37:31,38:32,
        55:33,54:34,53:35,52:36,51:37,61:38,62:39,63:40,64:41,65:42,85:43,84:44,83:45,82:46,81:47,71:48,72:49,73:50,74:51,75:52};
    var pertama=array[pertamaasli];
    var kedua=array[keduaasli];
    jembatan(pertama,kedua);
});
$('.refresh').click(function() {
    for(var i=1;i<=52;i++){    
        document.getElementById("gigi"+i).getContext("2d").clearRect(0, 0, 50, 50);
        $('#tgigi'+i).html('');
        $('#lgigi'+i).html('');
    }
    $("#tindakan").val("").change();
    $("#keadaan").val("").change();
    $("#permukaan").val("").change();
    dewasa();
    susu();
});
$('.ext').click(function() {
    var tindakan = $('#tindakan :selected').val();
    var keadaan = $('#keadaan :selected').val();
    var nomor = $('#urutan :selected').val();
    document.getElementById(nomor).getContext("2d").clearRect(0, 0, 50, 50);
    var tipe=nomor.substring(0,2);
    $('#t'+nomor).html('');
    if(tindakan=="ano"||tindakan=="pre"||tindakan=="une"||tindakan=="non")
        $('#t'+nomor).html(tindakan.toUpperCase());
    angka=nomor.substring(4, 6);
    angka=parseInt(angka);
    if(angka<6||(angka<=21&&angka>=12)||(angka<=34&&angka>=28)||(angka<=44&&angka>=41)||angka>=51){
        graham(nomor);
    }
    else{
        seri(nomor);
    }
});
var angka;
dewasa();
susu();
function dewasa(){
    graham("gigi1");graham("gigi2");graham("gigi3");graham("gigi4");graham("gigi5");
    graham("gigi12");graham("gigi13");graham("gigi14");graham("gigi15");graham("gigi16");
    seri("gigi6");seri("gigi7");seri("gigi8");seri("gigi9");seri("gigi10");seri("gigi11");
    graham("gigi17");graham("gigi18");graham("gigi19");graham("gigi20");graham("gigi21");
    graham("gigi28");graham("gigi29");graham("gigi30");graham("gigi31");graham("gigi32");
    seri("gigi22");seri("gigi23");seri("gigi24");seri("gigi25");seri("gigi26");seri("gigi27");
    
}
function susu(){
    graham("gigi33");graham("gigi34");graham("gigi41");graham("gigi42")
    seri("gigi35");seri("gigi36");seri("gigi37");seri("gigi38");seri("gigi39");seri("gigi40");
    graham("gigi43");graham("gigi44");graham("gigi51");graham("gigi52")
    seri("gigi45");seri("gigi46");seri("gigi47");seri("gigi48");seri("gigi49");seri("gigi50");
}
var flag=0;
function graham(idgambar){ 
    var img = document.getElementById(idgambar).getContext("2d");
    var imgtambah = document.getElementById("l"+idgambar).getContext("2d");
    var nomor = $('#urutan :selected').val();
    var tindakan = $('#tindakan :selected').val();
    var keadaan = $('#keadaan :selected').val();
    var permukaan = $('#permukaan :selected').val();
    var umur = $('#umur :selected').val();
    angka=idgambar.substring(4, 6);
    angka=parseInt(angka);
    img.beginPath();
    // img.strokeStyle ="red";
    gerahammurni(idgambar)
    if(tindakan=="poc"||tindakan=="mpc"||tindakan=="pob"){
        img.fillStyle = "green";
        img.fill();
    }
    else if(tindakan=="gmc"){
        img.fillStyle = "red";
        img.fill();
    }
    else if(tindakan=="ipx"){
        img.fillStyle = "gray";
        img.fill();
        $('#t'+idgambar).html("IPX");
    }
    img.closePath();
    img.stroke();
    if(tindakan=="fmc"||tindakan=="poc"||tindakan=="mpc"||tindakan=="gmc"||tindakan=="ipx"||tindakan=="pob"){
        img.beginPath();
        img.lineWidth = 7;  
        img.moveTo(0, 0);
        img.lineTo(0, 50);
        img.lineTo(50, 50);
        img.lineTo(50, 0);
        img.lineTo(0, 0);
        img.closePath();
        img.stroke();
        img.lineWidth = 1;  
    }
    else if(tindakan=="fis"){
        img.beginPath();
        gerahamrestorasi(idgambar);
        img.fillStyle = img.createPattern(patternCanvas, 'repeat'); 
        img.fill();
        img.closePath();
        img.stroke();
    }
    else if(tindakan=="amf"){
        img.beginPath();
        gerahamrestorasi(idgambar);
        img.fillStyle = "black";
        img.fill();
        img.closePath();
        img.stroke();
    }
    else if(tindakan=="cof"){
        img.beginPath();
        gerahamrestorasi(idgambar);
        img.fillStyle = "green";
        img.fill();
        img.closePath();
        img.stroke();
    }
    else if(tindakan=="rct"){
        if(angka<=16||(angka<=42&&angka>=33)){
            imgtambah.beginPath();
            imgtambah.moveTo(17, 0);
            imgtambah.lineTo(25, 20);
            imgtambah.lineTo(33, 0);
            imgtambah.lineTo(17, 0);
            imgtambah.fillStyle = "black";
            imgtambah.fill();
            imgtambah.closePath();
            imgtambah.stroke();
        }
        else if(angka<=32&&angka>=17||(angka<=52&&angka>=43)){
            imgtambah.beginPath();
            imgtambah.moveTo(17, 30);
            imgtambah.lineTo(25, 10);
            imgtambah.lineTo(33, 30);
            imgtambah.lineTo(17, 30);
            imgtambah.fillStyle = "black";
            imgtambah.fill();
            imgtambah.closePath();
            imgtambah.stroke();
        }
    }
    if(keadaan=="mis"){
        img.beginPath();
        img.moveTo(10, 0);
        img.lineTo(40, 50);
        img.moveTo(40, 0);
        img.lineTo(10, 50);
        img.closePath();
        img.stroke();
    }
    else if(keadaan=="rrx"){
        img.beginPath();
        img.moveTo(10, 0);
        img.lineTo(25, 50);
        img.lineTo(40, 0);
        img.closePath();
        img.stroke();
    }
    else if(keadaan=="cfr"){
        img.beginPath();
        img.moveTo(22, 5);
        img.lineTo(14, 45);
        img.moveTo(36, 5);
        img.lineTo(28, 45);
        img.moveTo(10, 20);
        img.lineTo(40, 20);
        img.moveTo(10, 30);
        img.lineTo(40, 30);
        img.closePath();
        img.stroke();
    }
    else if(keadaan=="car"){
        img.beginPath();
        img.lineWidth = 3;  
        gerahamrestorasi(idgambar);
        img.closePath();
        img.stroke();
        img.lineWidth = 1; 
    }
    
    else if(keadaan=="nvt"){
        if(angka<=16||(angka<=42&&angka>=33)){
            imgtambah.beginPath();
            imgtambah.moveTo(17, 0);
            imgtambah.lineTo(25, 20);
            imgtambah.lineTo(33, 0);
            imgtambah.fillStyle = "white";
            imgtambah.fill();
            imgtambah.closePath();
            imgtambah.stroke();
        }
        else if(angka<=32&&angka>=17||(angka<=52&&angka>=43)){
            imgtambah.beginPath();
            imgtambah.moveTo(17, 30);
            imgtambah.lineTo(25, 10);
            imgtambah.lineTo(33, 30);
            imgtambah.fillStyle = "white";
            imgtambah.fill();
            imgtambah.closePath();
            imgtambah.stroke();
        }
    }
    else if(keadaan=="ano"||keadaan=="pre"||keadaan=="une"||keadaan=="non")
        $('#t'+idgambar).html(keadaan.toUpperCase());
}
function gerahammurni(idgambar){
    var img = document.getElementById(idgambar).getContext("2d");
    img.moveTo(39, 11);
    img.lineTo(50, 0);
    img.lineTo(50, 50);
    img.lineTo(39, 39);
    img.lineTo(39, 11);

    img.moveTo(0, 0);
    img.lineTo(11, 11);
    img.lineTo(11, 39);
    img.lineTo(0, 50);
    img.lineTo(0, 0);

    img.moveTo(0, 0);
    img.lineTo(50, 0);
    img.lineTo(39, 11);
    img.lineTo(11, 11);
    img.lineTo(0, 0);

    img.moveTo(0, 50);
    img.lineTo(11, 39);
    img.lineTo(39, 39);
    img.lineTo(50, 50);
    img.lineTo(0, 50);

    img.moveTo(11, 11);
    img.lineTo(11, 39);
    img.lineTo(39, 39);
    img.lineTo(39, 11);
    img.lineTo(11, 11);
}
function gerahamrestorasi(idgambar){
    var img = document.getElementById(idgambar).getContext("2d");
    var permukaan = $('#permukaan :selected').val();
    if(permukaan=='m'){
        if((angka<=8&&angka>=1)||(angka<=24&&angka>=17)||(angka<=37&&angka>=33)||(angka<=47&&angka>=43)){
            img.moveTo(38, 12);
            img.lineTo(50, 0);
            img.lineTo(50, 50);
            img.lineTo(38, 38);
            img.lineTo(38, 12);
        }
        else{
            img.moveTo(0, 0);
            img.lineTo(12, 12);
            img.lineTo(12, 38);
            img.lineTo(0, 50);
            img.lineTo(0, 0);
        }
    }
    else if(permukaan=='d'){
        if((angka<=8&&angka>=1)||(angka<=24&&angka>=17)||(angka<=37&&angka>=33)||(angka<=47&&angka>=43)){
            img.moveTo(0, 0);
            img.lineTo(12, 12);
            img.lineTo(12, 38);
            img.lineTo(0, 50);
            img.lineTo(0, 0);
        }
        else{
            img.moveTo(38, 12);
            img.lineTo(50, 0);
            img.lineTo(50, 50);
            img.lineTo(38, 38);
            img.lineTo(38, 12);
        }
    }
    else if(permukaan=='v'){
        img.moveTo(0, 0);
        img.lineTo(50, 0);
        img.lineTo(38, 12);
        img.lineTo(12, 12);
        img.lineTo(0, 0);
    }
    else if(permukaan=='l'){
        img.moveTo(0, 50);
        img.lineTo(12, 38);
        img.lineTo(38, 38);
        img.lineTo(50, 50);
        img.lineTo(0, 50);
    }
    else{
        img.moveTo(11, 11);
        img.lineTo(11, 39);
        img.lineTo(39, 39);
        img.lineTo(39, 11);
        img.lineTo(11, 11);
    }
}
function serirestorasi(idgambar){
    var img = document.getElementById(idgambar).getContext("2d");
    var permukaan = $('#permukaan :selected').val();
    if(permukaan=='m'){
        if((angka<=8&&angka>=1)||(angka<=24&&angka>=17)||(angka<=37&&angka>=33)||(angka<=47&&angka>=43)){
            img.moveTo(37, 25);
            img.lineTo(50, 0);
            img.lineTo(50, 50);
            img.lineTo(37, 25);
        }
        else{
            img.moveTo(0, 0);
            img.lineTo(12, 25);
            img.lineTo(0, 50);
            img.lineTo(0, 0);
        }
    }
    else if(permukaan=='d'){
        if((angka<=8&&angka>=1)||(angka<=24&&angka>=17)||(angka<=37&&angka>=33)||(angka<=47&&angka>=43)){
            img.moveTo(0, 0);
            img.lineTo(12, 25);
            img.lineTo(0, 50);
            img.lineTo(0, 0);
        }
        else{
            img.moveTo(37, 25);
            img.lineTo(50, 0);
            img.lineTo(50, 50);
            img.lineTo(37, 25);
        }
    }
    else if(permukaan=='v'){
        img.moveTo(0, 0);
        img.lineTo(50, 0);
        img.lineTo(37, 25);
        img.lineTo(12, 25);
        img.lineTo(0, 0);
    }
    else if(permukaan=='l'){
        img.moveTo(0, 50);
        img.lineTo(12, 25);
        img.lineTo(37, 25);
        img.lineTo(50, 50);
        img.lineTo(0, 50);
    }
    else{
        img.moveTo(12, 22);
        img.lineTo(37, 22);
        img.lineTo(37, 28);
        img.lineTo(12, 28);
        img.lineTo(12, 22);
    }
}
function serimurni(idgambar){
    var img = document.getElementById(idgambar).getContext("2d");
    img.moveTo(37, 25);
    img.lineTo(50, 0);
    img.lineTo(50, 50);
    img.lineTo(37, 25);

    img.moveTo(0, 0);
    img.lineTo(12, 25);
    img.lineTo(0, 50);
    img.lineTo(0, 0);

    img.moveTo(0, 0);
    img.lineTo(50, 0);
    img.lineTo(37, 25);
    img.lineTo(12, 25);
    img.lineTo(0, 0);

    img.moveTo(0, 50);
    img.lineTo(12, 25);
    img.lineTo(37, 25);
    img.lineTo(50, 50);
    img.lineTo(0, 50);

    img.moveTo(12, 25);
    img.lineTo(37, 25);
    img.lineTo(37, 25);
    img.lineTo(12, 25);
    img.lineTo(12, 25);
}
function seri(idgambar){
    var img = document.getElementById(idgambar).getContext("2d");
    var imgtambah = document.getElementById("l"+idgambar).getContext("2d");
    var nomor = $('#urutan :selected').val();
    var tindakan = $('#tindakan :selected').val();
    var keadaan = $('#keadaan :selected').val();
    var permukaan = $('#permukaan :selected').val();
    var umur = $('#umur :selected').val();
    angka=idgambar.substring(4, 6);
    angka=parseInt(angka);
    img.beginPath();
    serimurni(idgambar);
    if(tindakan=="poc"||tindakan=="mpc"||tindakan=="pob"){
        img.fillStyle = "green";
        img.fill();
    }
    else if(tindakan=="gmc"){
        img.fillStyle = "red";
        img.fill();
    }
    else if(tindakan=="ipx"){
        img.fillStyle = "gray";
        img.fill();
        $('#t'+idgambar).html("IPX");
    }
    img.closePath();
    img.stroke();
    if(tindakan=="fmc"||tindakan=="poc"||tindakan=="mpc"||tindakan=="gmc"||tindakan=="ipx"||tindakan=="pob"){
        img.beginPath();
        img.lineWidth = 7;  
        img.moveTo(0, 0);
        img.lineTo(0, 50);
        img.lineTo(50, 50);
        img.lineTo(50, 0);
        img.lineTo(0, 0);
        img.closePath();
        img.stroke();
        img.lineWidth = 1;  
    }
    else if(tindakan=="fis"){
        img.beginPath();
        serirestorasi(idgambar);
        img.fillStyle = img.createPattern(patternCanvas, 'repeat'); 
        img.fill();
        img.closePath();
        img.stroke();
    }
    else if(tindakan=="cof"){
        img.beginPath();
        serirestorasi(idgambar);
        img.fillStyle = "green"; 
        img.fill();
        img.closePath();
        img.stroke();
    }
    else if(tindakan=="amf"){
        img.beginPath();
        serirestorasi(idgambar);
        img.fillStyle = "black"; 
        img.fill();
        img.closePath();
        img.stroke();
    }
    else if(tindakan=="rct"){
        if(angka<=16||(angka<=42&&angka>=33)){
            imgtambah.beginPath();
            imgtambah.moveTo(17, 0);
            imgtambah.lineTo(25, 20);
            imgtambah.lineTo(33, 0);
            imgtambah.lineTo(17, 0);
            imgtambah.fillStyle = "black";
            imgtambah.fill();
            imgtambah.closePath();
            imgtambah.stroke();
        }
        else if(angka<=32&&angka>=17||(angka<=52&&angka>=43)){
            imgtambah.beginPath();
            imgtambah.moveTo(17, 30);
            imgtambah.lineTo(25, 10);
            imgtambah.lineTo(33, 30);
            imgtambah.lineTo(17, 30);
            imgtambah.fillStyle = "black";
            imgtambah.fill();
            imgtambah.closePath();
            imgtambah.stroke();
        }
    }
    if(keadaan=="mis"){
        img.beginPath();
        img.moveTo(10, 0);
        img.lineTo(40, 50);
        img.moveTo(40, 0);
        img.lineTo(10, 50);
        img.closePath();
        img.stroke();
    }
    else if(keadaan=="rrx"){
        img.beginPath();
        img.moveTo(10, 0);
        img.lineTo(25, 50);
        img.lineTo(40, 0);
        img.closePath();
        img.stroke();
    }
    else if(keadaan=="car"){
        img.beginPath();
        img.lineWidth = 3;
        serirestorasi(idgambar);
        img.closePath();
        img.stroke();
        img.lineWidth = 1; 
    }
    else if(keadaan=="cfr"){
        img.beginPath();
        img.moveTo(22, 5);
        img.lineTo(14, 45);
        img.moveTo(36, 5);
        img.lineTo(28, 45);
        img.moveTo(10, 20);
        img.lineTo(40, 20);
        img.moveTo(10, 30);
        img.lineTo(40, 30);
        img.closePath();
        img.stroke();
    }
    else if(keadaan=="nvt"){
        if(angka<=16||(angka<=42&&angka>=33)){
            imgtambah.beginPath();
            imgtambah.moveTo(17, 0);
            imgtambah.lineTo(25, 20);
            imgtambah.lineTo(33, 0);
            imgtambah.fillStyle = "white";
            imgtambah.fill();
            imgtambah.closePath();
            imgtambah.stroke();
        }
        else if(angka<=32&&angka>=17||(angka<=52&&angka>=43)){
            imgtambah.beginPath();
            imgtambah.moveTo(17, 30);
            imgtambah.lineTo(25, 10);
            imgtambah.lineTo(33, 30);
            imgtambah.fillStyle = "white";
            imgtambah.fill();
            imgtambah.closePath();
            imgtambah.stroke();
            
        }
    }
    else if(keadaan=="ano"||keadaan=="pre"||keadaan=="une"||keadaan=="non")
            $('#t'+idgambar).html(keadaan.toUpperCase());
}