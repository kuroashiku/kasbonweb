$(function() {
    $('#login-btn-submit').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Login',
        onClick:function() {
            var u = $("#login-form-username").textbox('getValue');
            var p = $("#login-form-password").passwordbox('getValue');
            loginSubmit(u, p);
        }
    });
    $('#login-btn-update').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Update',
        onClick:function() {
            var u = $("#login-form-username").textbox('getValue');
            $('#login-dlg').dialog('close');
            showUpdateLogin(u);
        }
    });
    $("#login-btn-cancel").linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Cancel',
        onClick:function() {$('#login-dlg').dialog('close')}
    });
    $('#login-form-username').textbox({
        label:'User Name',
        labelPosition:'top',
        value:getCookie('grexdkiw'),
        width:'100%',
        inputEvents:$.extend({}, $.fn.textbox.defaults.inputEvents, {
            keypress:function(e) {
                if (e.which == 13) { // enter
                    var u = $("#login-form-username").textbox('getValue');
                    var p = $("#login-form-password").passwordbox('getValue');
                    loginSubmit(u, p);
                }
            }
        })
    });
    $('#login-form-password').passwordbox({
        label:'Password',
        labelPosition:'top',
        value:getCookie('zlpiwrhc'),
        width:'100%',
        inputEvents:$.extend({}, $.fn.passwordbox.defaults.inputEvents, {
            keypress:function(e) {
                if (e.which == 13) { // enter
                    var u = $("#login-form-username").textbox('getValue');
                    var p = $("#login-form-password").passwordbox('getValue');
                    loginSubmit(u, p);
                }
            }
        })
    });
    $('#login-form-username').textbox('textbox').focus();
});