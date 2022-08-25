// Minus satu artinya mode lihat
// 0 berarti mode tambah
// selain itu berarti sedang ada record yang diedit
var kogEditedId = 0;

// Ini untuk menandai apakah event onChange pada textbox dan combobox
// ditrigger oleh code atau oleh user. Defaultnya oleh user
var kogChangeByUser = true;
var kogControlHeight = 24;
var kogControlWidth = (globalConfig.login_data.rsa==1 || globalConfig.login_data.rsa!=undefined) ? 150 : 120;
var contentNavigation = ["kondisi", "masalah", "riwayat", "kb", "fisik", "nifas", "lain"];

$(function(){
    $('#kog-reset').linkbutton({
        text:'Reset',
        iconCls:'fa fa-broom',
        onClick: function(){
            if(window.confirm("Anda yakin akan menghapus isian poli kandungan?")){
                kogClearForm();
            }
        }
    });
    $('#kog-save').linkbutton({
        text:'Simpan',
        iconCls:'fa fa-save',
        onClick: kogSave
    });
    $('#kunobgyn-view').find('.dat-col').map(function() {
        initFieldById(`#${this.id}`);
    });
    initData();
    initNavigation();

    /**
     * Menambahkan fungsi navigasi pada sidebar
     */
    function initNavigation(){
        //sidebar navigation
        for(const nav of contentNavigation){
            const tab = document.getElementById(`tab-${nav}`);
            tab.addEventListener("click", function(){
                const content = document.getElementById(`content-${nav}`);
                content.scrollIntoView({behavior:"smooth"});
                for(const _nav of contentNavigation){
                    const _tab = document.getElementById(`tab-${_nav}`);
                    _tab.classList.remove("active");
                }
                tab.classList.add("active");
            });
        }
    }

    /**
     * Render form pertama kali bedasarkan ID dan tipenya 
     * @param {String} fieldId ID dari div container inputnya
     */
    function initFieldById(fieldId){
        $(fieldId).find('input.in').map(function(){
            const inputId = `#${this.id}`;
            const type = this.className.split(' ')[0];
            switch(type){
                case 'check':
                    $(inputId).checkbox({
                        labelPosition: 'after',
                        checked: false,
                        label: this.ariaLabel? this.ariaLabel : this.value,
                        value: this.value,
                        onChange: function(checked){
                            //special cases
                            switch(inputId){
                                case '#kog_kunulang_1':
                                    $('#kog_efeksmp_1').textbox('readonly', !checked);
                                    if(!checked){
                                        $('#kog_efeksmp_1').textbox('setValue', '');
                                    }
                                    break;
                            }
                            kogSetEdited();
                        }
                    });
                    break;
                case 'radio':
                    $(inputId).radiobutton({
                        labelPosition: 'after',
                        checked: false,
                        label: this.value,
                        value: this.value,
                        onChange: function(checked){
                            //special cases
                            switch(inputId){
                                case '#kog_kondisi_1':
                                    if(checked){
                                        $('#kog_kondisi_2').radiobutton('uncheck');
                                    }
                                    break;
                                case '#kog_kondisi_2':
                                    if(checked){
                                        $('#kog_kondisi_1').radiobutton('uncheck');
                                    }
                                    break;
                                case '#kog_ob_kulstat_1':
                                    if(checked){
                                        $('#kog_ob_kulstat_2').radiobutton('uncheck');
                                    }
                                    break;
                                case '#kog_ob_kulstat_2':
                                    if(checked){
                                        $('#kog_ob_kulstat_1').radiobutton('uncheck');
                                    }
                                    break;
                                case '#kog_ob_konstat_1':
                                    if(checked){
                                        $('#kog_ob_konstat_2').radiobutton('uncheck');
                                    }
                                    break;
                                case '#kog_ob_konstat_2':
                                    if(checked){
                                        $('#kog_ob_konstat_1').radiobutton('uncheck');
                                    }
                                    break;
                            }
                            kogSetEdited();
                        }
                    });
                    break;
                case 'text' :
                    $(inputId).textbox({
                        multiline: this.ariaMultiLine,
                        height: this.ariaMultiLine ? 80 : undefined,
                        width: 250,
                        prompt: this.placeholder,
                        readonly: this.readOnly,
                        onChange: function(changes){
                            kogSetEdited();
                        }
                    });
                    break;
                case 'number' :
                    $(inputId).numberbox({
                        width: 80,
                        min: parseInt(this.min),
                        max: parseInt(this.max),
                        onChange: function(changes){
                            kogSetEdited();
                        }
                    });
                    break;
                case 'date' :
                    $(inputId).datebox({
                        onChange: function(changes){
                            kogSetEdited();
                        }
                    });
                    break;
            }
        });
    }
    /**
     * Update field berdasarkan ID nya
     * @param {String} fieldId 
     * @param {any} fieldValue 
     */
    function updateFieldById(fieldId, fieldValue){
        if(!$(fieldId)[0]){
            return;
        }
        const isMultiValue = $(fieldId)[0].ariaMultiSelectable;
        let valueArr = isMultiValue ? fieldValue.split(',') : [];
        valueArr = valueArr.map(function(val){
            return val.trim();
        });
        $(fieldId).find('input.in').map(function(){
            const inputId = `#${this.id}`;
            const type = this.className.split(' ')[0];
            switch(type){
                case 'check':
                    if(isMultiValue){
                        if(valueArr.includes(this.value)){
                            $(inputId).checkbox('check');
                            let index = valueArr.indexOf(this.value);
                            if (index >= 0) {
                                valueArr.splice(index, 1);
                            }
                        }else{
                            $(inputId).checkbox('uncheck');
                        }
                    }else{
                        $(inputId).checkbox((!fieldValue || fieldValue == '0' ||
                            fieldValue == 'false') ? 'uncheck': 'check');
                    }
                    break;
                case 'radio':
                    if(valueArr.includes(this.value)){
                        $(inputId).radiobutton('check');
                        let index = valueArr.indexOf(this.value);
                        if (index >= 0) {
                            valueArr.splice(index, 1);
                        }
                    }else{
                        $(inputId).radiobutton('uncheck');
                    }
                    break;
                case 'text' :
                    if(isMultiValue){
                        if(valueArr.length > 1){
                            $(inputId).textbox('setValue', valueArr.join(", "));
                        }else{
                            $(inputId).textbox('setValue', valueArr[0]);
                        }
                        valueArr = [];
                    }else{
                        $(inputId).textbox('setValue', fieldValue);
                    }
                    break;
                case 'number' :
                    $(inputId).numberbox('setValue', fieldValue);
                    break;
                case 'date' :
                    $(inputId).datebox('setValue', fieldValue);
                    break;
            }
        });
    }

    /**
     * Kosongkan field berdasarkan IDnya
     * @param {String} fieldId 
     */
     function clearFieldById(fieldId){
        $(fieldId).find('input.in').map(function(){
            const inputId = `#${this.id}`;
            const type = this.className.split(' ')[0];
            switch(type){
                case 'check':
                    $(inputId).checkbox('uncheck');
                    break;
                case 'radio':
                    $(inputId).radiobutton('uncheck');
                    break;
                case 'text' :
                    $(inputId).textbox('setValue', '');
                    break;
                case 'number' :
                    $(inputId).numberbox('setValue', '');
                    break;
                case 'date' :
                    $(inputId).datebox('setValue', '');
                    break;
            }
        });
    }

    function getFieldValueById(fieldId){
        if(!$(fieldId)[0]){
            return null;
        }
        const isMultiValue = $(fieldId)[0].ariaMultiSelectable;
        let valueArr = [];
        let fieldValue = null;
        $(fieldId).find('input.in').map(function(){
            const inputId = `#${this.id}`;
            let val = null;
            let options = null;
            const type = this.className.split(' ')[0];
            switch(type){
                case 'check':
                    options = $(inputId).checkbox('options');
                    if(options){
                        val = options.value ? ( options.checked ? options.value : null) : options.checked;
                    }
                    break;
                case 'radio':
                    options = $(inputId).radiobutton('options');
                    if(options){
                        val = options.value ? ( options.checked ? options.value : null) : options.checked;
                    }
                    break;
                case 'text' :
                    val = $(inputId).textbox('getValue');
                    break;
                case 'number' :
                    val = $(inputId).numberbox('getValue');
                    break;
                case 'date' :
                    val = $(inputId).datebox('getValue');
                    break;
            }
            if(isMultiValue){
                if(val){
                    valueArr.push(val);
                }
            }else{
                fieldValue = val;
            }
        });
        if(isMultiValue) {
            return valueArr.join(', ');
        }
        return fieldValue;
    }

    function initData() {
        kogSetEnableDisable();
        var kprRow = $('#kpr-grid').datagrid('getSelected');
        if(!kprRow){
            kogEditedId = 0; //mode tambah
            return;
        }
        $.ajax({
            type:'POST',
            data: {
                kog_kpr_id: kprRow.kpr_id,
                db: getDB()
            },
            url:getRestAPI('kunobgyn/read'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    const data = obj.data;
                    Object.keys(data).forEach(function(key){
                        updateFieldById(`#${key}`, data[key]);
                    });
                    kogEditedId = data.kog_id;
                }else{
                    kogEditedId = 0;
                }
            }
        });
    };

    function kogClearForm(){
        $('#kunobgyn-view').find('.dat-col').map(function() {
            if(this.id !== 'kog_id'){
                clearFieldById(`#${this.id}`);
            }
        });
    }
    
    function kogSetEdited() {
        kogChangeByUser = true;
        kogSetEnableDisable();
    }

    function kogSetEnableDisable() {
        $('#kog-reset').linkbutton(kogChangeByUser ? 'enable':'disable');
        $('#kog-save').linkbutton(kogChangeByUser ? 'enable':'disable');
    }

    function kogSave(){
        if (isDemo()) return;
        var kunRow = $('#yan-grid').datagrid('getSelected');
        var kprRow = $('#kpr-grid').datagrid('getSelected');
        let data= {
            kog_id: kogEditedId,
            kog_kun_id: kunRow.kun_id,
            kog_kpr_id: kprRow? kprRow.kpr_id : null,
            username:globalConfig.login_data.username,
            db:getDB()
        };
        
        $('#kunobgyn-view').find('.dat-col').map(function() {
            data[this.id] = getFieldValueById(`#${this.id}`);
        });
        $.ajax({
            type: 'POST',
            data: data,
            url: getRestAPI('kunobgyn/save'),
            success:function(retval){
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    $('#kpr-obgyn-dlg').dialog('close');
                    window.showSnackbar("Data poli kandungan berhasil disimpan");
                    if(kogEditedId == 0){
                        const patient = $('#yan-grid').datagrid('getSelected');
                        const ptIndex = $('#yan-grid').datagrid('getRowIndex', patient);
                        $('#yan-grid').datagrid('selectRow', ptIndex);
                    }
                }else{
                    alert(obj.errmsg);
                }
                kogEditedId = 0;
                kogSetEnableDisable();
            }
        });
    }
});