var kepEditedId = -1;

// Ini untuk menandai apakah event onChange pada textbox dan combobox
// ditrigger oleh code atau oleh user. Defaultnya oleh user
var kepChangeByUser = true;
var kepControlHeight = 24;
$(function() {
	$("#kep-btn-add").linkbutton({
		text: "Tambah",
		iconCls: "fa fa-plus-circle",
		onClick: function() {
			kepAdd();
		}
	});
	$("#kep-btn-save").linkbutton({
		disabled: true,
		text: "Simpan",
		iconCls: "fa fa-check-circle fa-lg",
		onClick: function() {
			kepSave();
		}
	});
	$("#kep-btn-cancel").linkbutton({
		disabled: true,
		text: "Batal",
		iconCls: "fa fa-times-circle fa-lg",
		onClick: function() {
			kepCancel();
		}
	});
	$("#kep-btn-del").linkbutton({
		disabled: true,
		text: "Hapus",
		onClick: function() {
			kepDelete();
		}
	});
	$("#kep-btn-del").hide();
	$("#kep-form-id").textbox({
		width: 220,
		label: "ID",
		labelWidth: 60,
		prompt: "auto",
		height: kepControlHeight,
		readonly: true
	});
	$("#kep-form-tgperiksa").datetimebox({
		width: 155,
		editable: false,
		showSeconds: false,
		height: kepControlHeight,
		onChange: function() {
			kepSetEdited();
		}
	});
	$("#kep-form-dpjp").combobox({
		width: "90%",
		label: "DPJP",
		labelWidth: 60,
		height: kepControlHeight,
		valueField: "sdm_id",
		textField: "sdm_nama",
		panelHeight: "auto",
		editable: false,
		panelMaxHeight: 200,
		mode: "remote",
		method: "post",
		queryParams: {
			db:getDB(),
			com_id:globalConfig.com_id
		},
		url: getRestAPI("sdm/search"),
		onChange: function() {
			kepSetEdited();
		},
		keyHandler: comboboxKeyHandler("#kep-form-s")
	});
	$("#kep-form-konsul").textbox({
		width: "90%",
		height: 80,
		label: "Konsultasi ke Dokter",
		labelWidth: 60,
		multiline: true,
		onChange: function() {
			kepSetEdited();
		}
	});
	$("#kep-form-s").textbox({
		width: "90%",
		height: 80,
		label: "S",
		labelWidth: 60,
		multiline: true,
		onChange: function() {
			kepSetEdited();
		}
	});
	$("#kep-form-o").textbox({
		width: "90%",
		height: 80,
		label: "O",
		labelWidth: 60,
		multiline: true,
		onChange: function() {
			kepSetEdited();
		}
	});
	$("#kep-form-a").combobox({
		width: "90%",
		height: kepControlHeight,
		valueField: "tin_id",
		textField: "tin_nama",
		editable: false,
		label: "A",
		panelHeight: "auto",
		labelWidth: 60,
		panelMaxHeight: 200,
		onChange: function() {
			kepSetEdited();
		},
		queryParams: {
			db: getDB()
		},
		url: getRestAPI("tindakan/search"),
		keyHandler: comboboxKeyHandler("#kep-form-p")
	});
	$("#kep-form-p").textbox({
		width: "90%",
		height: 80,
		label: "P",
		labelWidth: 60,
		multiline: true,
		onChange: function() {
			kepSetEdited();
		}
	});
	$("#kep-grid").datagrid({
		border: false,
		fit: true,
		toolbar: "#kep-grid-tb",
		singleSelect: true,
		columns: [[{
			field: "kun_noregistrasi",
			title: "No. registrasi",
			resizable: false,
			width: 100
		},{
			field: "kep_tanggal",
			title: "Tgl Periksa",
			resizable: false,
			width: 95
		},{
			field: "kep_id",
			title: "ID",
			resizable: false,
			width: 100
		}]],
		queryParams: {
			db: getDB()
		},
		url: getRestAPI("kunevalprw/read"),
		onLoadSuccess: function(data) {
			if (data.rows.length > 0) $(this).datagrid("selectRow", 0);
			else kepClearForm();
			$("#kep-form-konsul").textbox("textbox").attr("maxlength", 500);
			$("#kep-form-s").textbox("textbox").attr("maxlength", 500);
			$("#kep-form-o").textbox("textbox").attr("maxlength", 500);
			$("#kep-form-p").textbox("textbox").attr("maxlength", 500);
			kepSetReadonly(true);
		},
		onSelect: function(index, row) {
			kepChangeByUser = false;
			$("#kep-form-id").textbox("setValue", row.kep_id);
			kepSetReadonly(false);
			$("#kep-form-tgperiksa").datetimebox("setValue", row.kep_tanggal);
			$("#kep-form-dpjp").combobox("loadData", [{
				sdm_id: row.kep_sdm_id,
				sdm_nama: row.sdm_nama,
				db: getDB()
			}]);
			$("#kep-form-dpjp").combobox("setValue", row.kep_sdm_id);
			$("#kep-form-konsul").textbox("setValue", row.kep_konsul);
			$("#kep-form-s").textbox("setValue", row.kep_s);
			$("#kep-form-o").textbox("setValue", row.kep_o);
			$("#kep-form-a").combobox("loadData", [{
				tin_id: row.kep_tin_id,
				tin_nama: row.tin_nama,
				db: getDB()
			}]);
			$("#kep-form-a").combobox("setValue", row.kep_tin_id);
			$("#kep-form-p").textbox("setValue", row.kep_p);
			kepChangeByUser = true;
			$("#kep-form-s").textbox("textbox").focus();
		}
	});
	function kepSetReadonly(readonly) {
		$("#kep-form-tgperiksa").datetimebox("readonly", readonly);
		$("#kep-form-dpjp").combobox("readonly", readonly);
		$("#kep-form-konsul").textbox("readonly", readonly);
		$("#kep-form-s").textbox("readonly", readonly);
		$("#kep-form-o").textbox("readonly", readonly);
		$("#kep-form-a").combobox("readonly", readonly);
		$("#kep-form-p").textbox("readonly", readonly);
	}

	function kepSetEdited() {
		if (kepEditedId == -1 && kepChangeByUser) {
			kepEditedId = $("#kep-form-id").textbox("getValue");
			kepSetEnableDisable();
		}
	}

	function kepAdd() {
		kepChangeByUser = false;
		kepClearForm();
		var today = new Date();
		$("#kep-form-tgperiksa").datebox("setValue", today.toLocaleDateString());
		kepSetReadonly(false);
		kepChangeByUser = true;
		kepEditedId = 0; // mode tambah
		kepSetEnableDisable();
		$("#kep-form-s").textbox("textbox").focus();
	}

	function kepSave() {
        if (isDemo()) return;
		var selectedKunRow = $("#yan-grid").datagrid("getSelected");
		var data = {
			kep_id: kepEditedId,
			kep_tanggal: $("#kep-form-tgperiksa").datetimebox("getValue"),
			kep_kun_id: selectedKunRow.kun_id,
			kep_konsul: $("#kep-form-konsul").textbox("getValue"),
			kep_s: $("#kep-form-s").textbox("getValue"),
			kep_o: $("#kep-form-o").textbox("getValue"),
			kep_tin_id: $("#kep-form-a").combobox("getValue"),
			kep_sdm_id: $("#kep-form-dpjp").combobox("getValue"),
			kep_p: $("#kep-form-p").textbox("getValue"),

			//edited by naufal
			username: globalConfig.login_data.username,
			db: getDB()
		};
		$.ajax({
			type: "POST",
			data: data,
			url: getRestAPI("kunevalprw/save"),
			success: function(retval) {
				var obj = JSON.parse(retval);
				if (obj.status == "success") {
					if (kepEditedId == 0) {
						$("#kep-grid").datagrid("insertRow", {
							index: 0,
							row: obj.row
						});
						$("#kep-grid").datagrid("selectRow", 0);
					} else {
						var selectedRow = $("#kep-grid").datagrid("getSelected");
						var index = $("#kep-grid").datagrid("getRowIndex", selectedRow);
						$("#kep-grid").datagrid("updateRow", {
							index: index,
							row: obj.row
						});
						$("#kep-grid").datagrid("selectRow", index);
					}
				} 
				else alert(obj.errmsg);
				kepEditedId = -1;
				kepSetEnableDisable();
			}
		});
	}

	function kepCancel() {
		kepEditedId = -1;
		kepSetEnableDisable();
		var row = $("#kep-grid").datagrid("getSelected");
		if (row)
			$("#kep-grid").datagrid("selectRow",$("#kep-grid").datagrid("getRowIndex", row));
		else kepSetReadonly(true);
	}

	function kepDelete() {
		alert("Maaf menu ini belum bisa dipakai");
	}

	function kepSetEnableDisable() {
		if (kepEditedId >= 0) {
			// mode tambah atau edit
			$("#kep-btn-add").linkbutton("disable");
			$("#kep-btn-save").linkbutton("enable");
			$("#kep-btn-cancel").linkbutton("enable");
			$("#kep-btn-del").linkbutton("disable");
		} 
		else {
			// mode lihat, tombol hapus akan enable kalau ada row yg terselect
			$("#kep-btn-add").linkbutton("enable");
			$("#kep-btn-save").linkbutton("disable");
			$("#kep-btn-cancel").linkbutton("disable");
			$("#kep-btn-del").linkbutton("enable");
		}
	}

	function kepTextboxOnChange(obj) {
		if (!kepChangeByUser) return;
		var str = $(obj).textbox("getValue");
		str = setCapitalSentenceEveryWord(str);
		var prev = kepChangeByUser;
		kepChangeByUser = false;
		$(obj).textbox("setValue", str);
		kepChangeByUser = prev;
		kepSetEdited();
	}

	function kepClearForm() {
		var prev = kepChangeByUser;
		kepChangeByUser = false;
		$("#kep-form-id").textbox("setValue", "");
		$("#kep-form-tgperiksa").datebox("setValue", "");
		$("#kep-form-dpjp").combobox("setValue", "");
		$("#kep-form-konsul").textbox("setValue", "");
		$("#kep-form-s").textbox("setValue", "");
		$("#kep-form-o").textbox("setValue", "");
		$("#kep-form-a").combobox("setValue", "");
		$("#kep-form-p").textbox("setValue", "");
		kepChangeByUser = prev;
	}
});