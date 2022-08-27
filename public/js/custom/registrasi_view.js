$(function() {
    $('#regis-btn-submit').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Login',
        onClick:function() {
            var u = $("#regis-form-username").textbox('getValue');
            var p = $("#regis-form-password").passwordbox('getValue');
            var te=$('#register-dlg').data('id');
            console.log(te)
        }
    });
    $("#regis-btn-cancel").linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Cancel',
        onClick:function() {$('#register-dlg').dialog('close')}
    });
    $('#regis-form-username').textbox({
        label:'User Name',
        labelPosition:'top',
        value:getCookie('grexdkiw'),
        width:'100%',
        inputEvents:$.extend({}, $.fn.textbox.defaults.inputEvents, {
            keypress:function(e) {
                if (e.which == 13) { // enter
                    var u = $("#regis-form-username").textbox('getValue');
                    var p = $("#regis-form-password").passwordbox('getValue');
                    
                }
            }
        })
    });
    $('#regis-form-password').passwordbox({
        label:'Password',
        labelPosition:'top',
        value:getCookie('zlpiwrhc'),
        width:'100%',
        inputEvents:$.extend({}, $.fn.passwordbox.defaults.inputEvents, {
            keypress:function(e) {
                if (e.which == 13) { // enter
                    var u = $("#regis-form-username").textbox('getValue');
                    var p = $("#regis-form-password").passwordbox('getValue');
                    regisSubmit(u, p);
                }
            }
        })
    });
    $('#regis-form-username').textbox('textbox').focus();
});