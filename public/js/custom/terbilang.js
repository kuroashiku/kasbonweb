/*
| @Author Kukuh Prabowo
| 2015
| version 0.1
| @return String
*/
(function(e){e.fn.terbilang=function(t){
    var n;
    var r=e.extend({
        lang:"id",
        output:e("#terbilang-output")
    },t);
    var i=function(e,t,n,r){
        var i="";
        var s=Math.floor(e/100);
        var o=Math.floor((e-s*100)/10);
        var u=e-s*100-o*10;
        if(s>0){
            if(s==1){
                i=i+"seratus "
            }
            else{
                i=i+n[s]+" ratus "
            }
        }
        if(o>0){
            if(o==1){
                switch(u){
                case 0:i=i+"sepuluh ";break;
                case 1:i=i+"sebelas ";break;
                default:i=i+n[u]+" belas "
                }
            }
            else{
                i=i+n[o]+" puluh "
            }
        }
        if(u>0){if(o>1||o==0){if(t==1&u==1){i=i+"se"}else{i=i+n[u]+" "}}}return i};var s=function(e,t,n,r,i){var s="";var o=Math.floor(e/100);var u=Math.floor((e-o*100)/10);var a=e-o*100-u*10;if(o>0){s=s+t[o]+" hundred "}if(u>0){if(u==1){s=s+r[a]+" "}else{s=s+i[u]+" "}}if(a>0){if(u>1||u==0){s=s+t[a]+" "}}return s};var o=function(e,t){var n="";var r=0;var o=false;switch(t){case"id":var u=new Array("nol","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan");var a=new Array("","ribu","juta","miliar","triliun");break;case"en":var u=new Array("zero","one","two","three","four","five","six","seven","eight","nine");var a=new Array("","thousand","million","billion","trillion");var f=new Array("ten","eleven","twelve","thirteen","fourteen","fifteen","sixteen","seventeen","eighteen","nineteen");var l=new Array("","","twenty","thirty","forty","fifty","sixty","seventy","eighty","ninety");break;default:alert("Unknown language");o=true}if(isNaN(e)){o=true;alert("Not a number!")}else{e=parseFloat(e)}if(o==false){do{var c=Math.round((Math.floor(e)/1e3-Math.floor(Math.floor(e)/1e3))*1e3);if(t=="id"){n=i(c,r,u,a)+a[r]+" "+n;if(e==0){n="nol"}}if(t=="en"){n=s(c,u,a,f,l)+a[r]+" "+n;if(e==0){n="zero"}}e=Math.floor(Math.floor(e)/1e3);r++}while(e>0);return n.replace(/^\s*|\s(?=\s)|\s*$/g,"")}else{return"NaN"}};var u=function(){var e=r.output;switch(r.lang){case"id":var t=r.lang;var i="rupiah";var s="sen";break;case"en":var t=r.lang;var i="dollar";var s="cent";break}var u=n.val();var u=n.val().split(/[.]|[,]/);var a=o(u,t)+" "+i;var f="";if(u[1]!=undefined){f=o(u[1].substr(0,2),t)+" "+s}if(a.search("NaN")!=-1||f.search("NaN")!=-1){n.val("");e.val("")}else{e.val(a+" "+f)}};return this.each(function(){var t=e(this);e(document).on("keyup change",t,function(e){n=t;if(n.val()!=""){u()}else{n.val("")}})})}})(jQuery)
