$(function() {
    
    var ul = $('#updatelogin-dlg').data('id');
    console.log(ul)
    setTimeout(function(){
        $('#uplog-form-username').textbox('setValue',ul)
    },100);
    $('#uplog-btn-submit').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Change',
        onClick:function() {
            var p = $("#uplog-form-password").passwordbox('getValue');
            $.ajax({
                type:'POST',
                data:{
                    kas_password:p,
                    kas_nick:ul
                },
                url:getRestAPI('kasir/updatelogin'),
            })
            $('#updatelogin-dlg').dialog('close');
            showLogin();
        }
    });
    $("#uplog-btn-cancel").linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Cancel',
        onClick:function() {$('#updatelogin-dlg').dialog('close');    console.log(document.cookie)}
    });
    $('#uplog-form-username').textbox({
        label:'User Name',
        labelPosition:'top',
        width:'100%',
    });
    $('#uplog-form-username').textbox('disable');
    $('#uplog-form-password').passwordbox({
        label:'Password',
        labelPosition:'top',
        value:getCookie('zlpiwrhc'),
        width:'100%',
        inputEvents:$.extend({}, $.fn.passwordbox.defaults.inputEvents, {
            keypress:function(e) {
                if (e.which == 13) { // enter
                    var u = $("#uplog-form-username").textbox('getValue');
                    var p = $("#uplog-form-password").passwordbox('getValue');
                    
                }
            }
        })
    });
    // $('#uplog-form-username').textbox('textbox').focus();
});