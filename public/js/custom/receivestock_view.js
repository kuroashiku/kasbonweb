// Minus satu artinya mode lihat
// 0 berarti mode tambah
// selain itu berarti sedang ada record yang diedit
var rcvEditedId = 0;

// Ini untuk menandai apakah event onChange pada textbox dan combobox
// ditrigger oleh code atau oleh user. Defaultnya oleh user
var rcsChangeByUser = true;
var rcsList = [];


$(function(){
    $('#rcs-save').linkbutton({
        text:'Simpan',
        iconCls:'fa fa-save',
        onClick: rcsSave
    });
    $('#receivestock-view').find('.dat-col').map(function() {
        initFieldById(`#${this.id}`);
    });
    initData();
    /**
     * Render form pertama kali bedasarkan ID dan tipenya 
     * @param {String} fieldId ID dari div container inputnya
     */
    function initFieldById(fieldId){
        $(fieldId).find('input.in').map(function(){
            const inputId = `#${this.id}`;
            const type = this.className.split(' ')[0];
            switch(type){
                case 'text' :
                    $(inputId).textbox({
                        multiline: this.ariaMultiLine,
                        height: this.ariaMultiLine ? 80 : undefined,
                        prompt: this.placeholder,
                        readonly: this.readOnly,
                        onChange: function(changes){
                            rcsSetEdited();
                        }
                    });
                    break;
                case 'number' :
                    $(inputId).numberbox({
                        width: 80,
                        min: parseInt(this.min),
                        max: parseInt(this.max),
                        onChange: function(changes){
                            rcsSetEdited();
                        }
                    });
                    break;
                case 'date' :
                    $(inputId).datebox({
                        onChange: function(changes){
                            rcsSetEdited();
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
        $(fieldId).find('input.in').map(function(){
            const inputId = `#${this.id}`;
            const type = this.className.split(' ')[0];
            switch(type){
                case 'text' :
                    $(inputId).textbox('setValue', fieldValue);
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

    function getFieldValueById(fieldId){
        if(!$(fieldId)[0]){
            return null;
        }
        let fieldValue = null;
        $(fieldId).find('input.in').map(function(){
            const inputId = `#${this.id}`;
            let val = null;
            const type = this.className.split(' ')[0];
            switch(type){
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
            fieldValue = val;
        });
        return fieldValue;
    }

    function initData() {
        rcsSetEnableDisable();
        var rcvRow = $('#rcv-grid').datagrid('getSelected');
        $.ajax({
            type:'POST',
            data: {
                rcv_id: rcvRow.rcv_id
            },
            url:getRestAPI('receive/ordered_item_list'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    rcsList = obj.list;
                    rcsList.forEach(function(item, index){
                        Object.keys(item).forEach(function(key){
                            updateFieldById(`#${key}-${index}`, item[key]);
                        });
                    });
                    rcvEditedId = rcvRow.rcv_id;
                }else{
                    rcvEditedId = 0;
                }
            }
        });
    };
    
    function rcsSetEdited() {
        rcsChangeByUser = true;
        rcsSetEnableDisable();
    }

    function rcsSetEnableDisable() {
        $('#rcs-save').linkbutton(rcsChangeByUser ? 'enable':'disable');
    }

    function rcsSave(){
        rcsList.forEach(function(item, index){
            Object.keys(item).forEach(function(key){
                updateFieldById(`#${key}-${index}`, item[key]);
            });
        });        
        $('#receivestock-view').find('.dat-col').map(function() {
            data[this.id] = getFieldValueById(`#${this.id}`);
        });
        $.ajax({
            type: 'POST',
            data: rcsList,
            url: getRestAPI('receive/stockSave'),
            success:function(retval){
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    $('#kpr-obgyn-dlg').dialog('close');
                    window.showSnackbar("Data poli kandungan berhasil disimpan");
                    if(rcvEditedId == 0){
                        const patient = $('#yan-grid').datagrid('getSelected');
                        const ptIndex = $('#yan-grid').datagrid('getRowIndex', patient);
                        $('#yan-grid').datagrid('selectRow', ptIndex);
                    }
                }else{
                    alert(obj.errmsg);
                }
                rcvEditedId = 0;
                rcsSetEnableDisable();
            }
        });
    }
});