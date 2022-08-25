var kedEditedId = -1;

// Ini untuk menandai apakah event onChange pada textbox dan combobox
// ditrigger oleh code atau oleh user. Defaultnya oleh user
var kedChangeByUser = true;
var kedControlHeight = 24;
$(function() {
	$("#ked-btn-add").linkbutton({
		text: "Tambah",
		iconCls: "fa fa-plus-circle",
		onClick: function() {
			kedAdd();
		}
	});
	$("#ked-btn-save").linkbutton({
		disabled: true,
		text: "Simpan",
		iconCls: "fa fa-check-circle fa-lg",
		onClick: function() {
			kedSave();
		}
	});
	$("#ked-btn-cancel").linkbutton({
		disabled: true,
		text: "Batal",
		iconCls: "fa fa-times-circle fa-lg",
		onClick: function() {
			kedCancel();
		}
	});
	$("#ked-btn-del").linkbutton({
		disabled: true,
		text: "Hapus",
		onClick: function() {
			kedDelete();
		}
	});
	$("#ked-btn-del").hide();
	$("#ked-form-id").textbox({
		width: 220,
		label: "ID",
		labelWidth: 60,
		prompt: "auto",
		height: kedControlHeight,
		readonly: true
	});
	$("#ked-form-tgperiksa").datetimebox({
		width: 155,
		editable: false,
		showSeconds: false,
		height: kedControlHeight,
		onChange: function() {
			kedSetEdited();
		}
	});
	$("#ked-form-dpjp").combobox({
		width: "90%",
		label: "DPJP",
		labelWidth: 60,
		height: kedControlHeight,
		valueField: "sdm_id",
		textField: "sdm_nama",
		panelHeight: "auto",
		editable: false,
		panelMaxHeight: 200,
		mode: "remote",
		method: "post",
		queryParams: {
			db:getDB(),
			com_id:globalConfig.com_id,
			jab_tipe:'D'
		},
		url: getRestAPI("sdm/search"),
		onChange: function() {
			kedSetEdited();
		},
		keyHandler: comboboxKeyHandler("#ked-form-s")
	});
	$("#ked-form-s").textbox({
		width: "90%",
		height: 80,
		label: "S",
		labelWidth: 60,
		multiline: true,
		onChange: function() {
			kedSetEdited();
		}
	});
	$("#ked-form-o").textbox({
		width: "90%",
		height: 80,
		label: "O",
		labelWidth: 60,
		multiline: true,
		onChange: function() {
			kedSetEdited();
		}
	});
	$("#ked-form-a").combobox({
		width: "90%",
		height: kedControlHeight,
		valueField: "tin_id",
		textField: "tin_nama",
		editable: false,
		label: "A",
		panelHeight: "auto",
		labelWidth: 60,
		panelMaxHeight: 200,
		onChange: function() {
			kedSetEdited();
		},
		queryParams: {
			db:getDB()
		},
		url: getRestAPI("tindakan/search"),
		keyHandler: comboboxKeyHandler("#ked-form-p")
	});
	$("#ked-form-p").textbox({
		width: "90%",
		height: 80,
		label: "P",
		labelWidth: 60,
		multiline: true,
		onChange: function() {
			kedSetEdited();
		}
	});
	$("#ked-grid").datagrid({
		border: false,
		fit: true,
		toolbar: "#ked-grid-tb",
		singleSelect: true,
		columns: [[{
			field: "kun_noregistrasi",
			title: "No. registrasi",
			resizable: false,
			width: 100
		},
		{
			field: "ked_tanggal",
			title: "Tgl Periksa",
			resizable: false,
			width: 95
		},
		{
			field: "ked_id",
			title: "ID",
			resizable: false,
			width: 100
		}]],
		queryParams: {
			db:getDB()
		},
		url: getRestAPI("kunevaldok/read"),
		onLoadSuccess: function(data) {
			if (data.rows.length > 0) $(this).datagrid("selectRow", 0);
			else kedClearForm();
			$("#ked-form-s").textbox("textbox").attr("maxlength", 500);
			$("#ked-form-o").textbox("textbox").attr("maxlength", 500);
			$("#ked-form-p").textbox("textbox").attr("maxlength", 500);
			kedSetReadonly(true);
		},
		onSelect: function(index, row) {
			kedChangeByUser = false;
			$("#ked-form-id").textbox("setValue", row.ked_id);
			kedSetReadonly(false);
			$("#ked-form-tgperiksa").datetimebox("setValue", row.ked_tanggal);
			$("#ked-form-dpjp").combobox("loadData", [{
				sdm_id: row.ked_sdm_id,
				sdm_nama: row.sdm_nama,
				db: getDB()
			}]);
			$("#ked-form-dpjp").combobox("setValue", row.ked_sdm_id);
			$("#ked-form-s").textbox("setValue", row.ked_s);
			$("#ked-form-o").textbox("setValue", row.ked_o);
			$("#ked-form-a").combobox("loadData", [{
				tin_id: row.ked_tin_id,
				tin_nama: row.tin_nama,
				db: getDB()
			}]);
			$("#ked-form-a").combobox("setValue", row.ked_tin_id);
			$("#ked-form-p").textbox("setValue", row.ked_p);
			kedChangeByUser = true;
			$("#ked-form-s").textbox("textbox").focus();
		}
	});

	function kedSetReadonly(readonly) {
		$("#ked-form-tgperiksa").datetimebox("readonly", readonly);
		$("#ked-form-dpjp").combobox("readonly", readonly);
		$("#ked-form-s").textbox("readonly", readonly);
		$("#ked-form-o").textbox("readonly", readonly);
		$("#ked-form-a").combobox("readonly", readonly);
		$("#ked-form-p").textbox("readonly", readonly);
	}

	function kedSetEdited() {
		if (kedEditedId == -1 && kedChangeByUser) {
			kedEditedId = $("#ked-form-id").textbox("getValue");
			kedSetEnableDisable();
		}
	}

	function kedAdd() {
		kedChangeByUser = false;
		kedClearForm();
		var today = new Date();
		$("#ked-form-tgperiksa").datebox("setValue", today.toLocaleDateString());
		kedSetReadonly(false);
		kedChangeByUser = true;
		kedEditedId = 0; // mode tambah
		kedSetEnableDisable();
		$("#ked-form-s").textbox("textbox").focus();
	}

	function kedSave() {
        if (isDemo()) return;
		var selectedKunRow = $("#yan-grid").datagrid("getSelected");
		var data = {
			ked_id: kedEditedId,
			ked_tanggal: $("#ked-form-tgperiksa").datetimebox("getValue"),
			ked_kun_id: selectedKunRow.kun_id,
			ked_s: $("#ked-form-s").textbox("getValue"),
			ked_o: $("#ked-form-o").textbox("getValue"),
			ked_tin_id: $("#ked-form-a").combobox("getValue"),
			ked_sdm_id: $("#ked-form-dpjp").combobox("getValue"),
			ked_p: $("#ked-form-p").textbox("getValue"),
			username: globalConfig.login_data.username,
			db: getDB()
		};
		$.ajax({
			type: "POST",
			data: data,
			url: getRestAPI("kunevaldok/save"),
			success: function(retval) {
				var obj = JSON.parse(retval);
				if (obj.status == "success") {
					if (kedEditedId == 0) {
						$("#ked-grid").datagrid("insertRow", {
							index: 0,
							row: obj.row
						});
						$("#ked-grid").datagrid("selectRow", 0);
					} 
					else {
						var selectedRow = $("#ked-grid").datagrid("getSelected");
						var index = $("#ked-grid").datagrid("getRowIndex", selectedRow);
						$("#ked-grid").datagrid("updateRow", {
							index: index,
							row: obj.row
						});
						$("#ked-grid").datagrid("selectRow", index);
					}
				} 
				else alert(obj.errmsg);
				kedEditedId = -1;
				kedSetEnableDisable();
			}
		});
	}

	function kedCancel() {
		kedEditedId = -1;
		kedSetEnableDisable();
		var row = $("#ked-grid").datagrid("getSelected");
		if (row)
		$("#ked-grid").datagrid(
			"selectRow",
			$("#ked-grid").datagrid("getRowIndex", row)
		);
		else kedSetReadonly(true);
	}

	function kedDelete() {
		alert("Maaf menu ini belum bisa dipakai");
	}

	function kedSetEnableDisable() {
		if (kedEditedId >= 0) {
			// mode tambah atau edit
			$("#ked-btn-add").linkbutton("disable");
			$("#ked-btn-save").linkbutton("enable");
			$("#ked-btn-cancel").linkbutton("enable");
			$("#ked-btn-del").linkbutton("disable");
		} 
		else {
			// mode lihat, tombol hapus akan enable kalau ada row yg terselect
			$("#ked-btn-add").linkbutton("enable");
			$("#ked-btn-save").linkbutton("disable");
			$("#ked-btn-cancel").linkbutton("disable");
			$("#ked-btn-del").linkbutton("enable");
		}
	}

	function kedTextboxOnChange(obj) {
		if (!kedChangeByUser) return;
		var str = $(obj).textbox("getValue");
		str = setCapitalSentenceEveryWord(str);
		var prev = kedChangeByUser;
		kedChangeByUser = false;
		$(obj).textbox("setValue", str);
		kedChangeByUser = prev;
		kedSetEdited();
	}

	function kedClearForm() {
		var prev = kedChangeByUser;
		kedChangeByUser = false;
		$("#ked-form-id").textbox("setValue", "");
		$("#ked-form-tgperiksa").datebox("setValue", "");
		$("#ked-form-dpjp").combobox("setValue", "");
		$("#ked-form-s").textbox("setValue", "");
		$("#ked-form-o").textbox("setValue", "");
		$("#ked-form-a").combobox("setValue", "");
		$("#ked-form-p").textbox("setValue", "");
		kedChangeByUser = prev;
	}
});
