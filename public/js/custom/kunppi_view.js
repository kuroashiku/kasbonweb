var kpiEditedId = -1;

// Ini untuk menandai apakah event onChange pada textbox dan combobox
// ditrigger oleh code atau oleh user. Defaultnya oleh user
var kpiChangeByUser = true;
var kpiControlHeight = 24;
$(function() {
	$("#kpi-btn-add").linkbutton({
		text: "Tambah",
		iconCls: "fa fa-plus-circle",
		onClick: function() {
			kpiAdd();
		}
	});
	$("#kpi-btn-save").linkbutton({
		disabled: true,
		text: "Simpan",
		iconCls: "fa fa-check-circle fa-lg",
		onClick: function() {
			kpiSave();
		}
	});
	$("#kpi-btn-cancel").linkbutton({
		disabled: true,
		text: "Batal",
		iconCls: "fa fa-times-circle fa-lg",
		onClick: function() {
			kpiCancel();
		}
	});
	$("#kpi-btn-del").linkbutton({
		disabled: true,
		text: "Hapus",
		onClick: function() {
			kpiDelete();
		}
	});
	$("#kpi-btn-del").hide();
	$("#kpi-form-id").textbox({
		label: "ID",
		labelWidth: 180,
		width: 355,
		prompt: "auto",
		height: kpiControlHeight,
		readonly: true
	});
	$("#kpi-form-tanggal").datetimebox({
		label: "Tgl. Periksa",
		labelWidth: 180,
		width: 355,
		editable: false,
		showSeconds: false,
		height: kpiControlHeight,
		onChange: function() {
			kpiSetEdited();
		}
	});
	$("#kpi-form-kateter").checkbox({
		label: "Penggunaan alat kateter",
		labelWidth: 180,
		onChange: function() {
			kpiSetEdited();
		}
	});
	$("#kpi-form-infus").checkbox({
		onChange: function() {
			kpiSetEdited();
		}
	});
	$("#kpi-form-plebitis").numberbox({
		label: "Penggunaan alat plebitis/hari",
		labelWidth: 180,
		width: 355,
		height: kpiControlHeight,
		onChange:function() {kpiTextboxOnChange(this);}
	});
	// $('#kpi-form-plebitis').textbox('textbox').bind('blur', function(e){
	// 	var nilai=$('#kpi-form-plebitis').textbox('getValue')
    //                 $('#kpi-form-plebitis').textbox('setValue',romanize(nilai))
	// });
	$("#kpi-form-postophari").numberbox({
		label: "Riwayat post-op hari ke",
		labelWidth: 180,
		width: 355,
		height: kpiControlHeight,
		onChange:function() {kpiSetEdited();}
	});
	$("#kpi-form-postopinfeksi").checkbox({
		label: "Riwayat post-op tanda infeksi",
		labelWidth: 180,
		onChange:function() {kpiSetEdited();}
	});
	$("#kpi-form-tirahbaring").checkbox({
		onChange:function() {kpiSetEdited();}
	});
	$("#kpi-form-hap").checkbox({
		onChange:function() {kpiSetEdited();}
	});
	$('#kpi-form-hasilkultur').textbox({
        height:60,
        label:'Hasil kultur',
        labelWidth: 180,
		width: 355,
        multiline:true,
        onChange:function() {kpiSetEdited();}
    });
	$('#kpi-form-gunaantibiotik').textbox({
        height:60,
        label:'Penggunaan Antibiotik',
        labelWidth: 180,
		width: 355,
        multiline:true,
        onChange:function() {kpiSetEdited();}
    });
	$('#kpi-form-klasinfeksi').textbox({
        height:60,
        label:'Klasifikasi Infeksi',
        labelWidth: 180,
		width: 355,
        multiline:true,
        onChange:function() {kpiSetEdited();}
    });
	$('#kpi-form-diagnosa').textbox({
        height:60,
        label:'Diagnosa Pasien',
        labelWidth: 180,
		width: 355,
        multiline:true,
        onChange:function() {kpiSetEdited();}
    });
	$('#kpi-form-ruangan').textbox({
        height:60,
        label:'Ruangan Pasien',
        labelWidth: 180,
		width: 355,
        multiline:true,
        onChange:function() {kpiSetEdited();}
    });
	$('#kpi-form-user').textbox({
        height:60,
        label:'User',
        labelWidth: 180,
		width: 355,
        multiline:true,
        onChange:function() {kpiSetEdited();}
    });
	$("#kpi-grid").datagrid({
		border: false,
		fit: true,
		toolbar: "#kpi-grid-tb",
		singleSelect: true,
		columns: [[{
				field: "kun_noregistrasi",
				title: "No. registrasi",
				resizable: false,
				width: 100
			},
			{
				field: "kpi_tanggal",
				title: "Tgl Periksa",
				resizable: false,
				width: 95
			},
			{
				field: "kpi_id",
				title: "ID",
				resizable: false,
				width: 100
		}]],
		queryParams: {db: getDB(), 
			man_id: $("#yan-grid").datagrid("getSelected").kun_man_id, 
			hwn_id: $("#yan-grid").datagrid("getSelected").kun_hwn_id  },
		url: getRestAPI("kunppi/read"),
		onLoadSuccess: function(data) {
			if (data.rows.length > 0) $(this).datagrid("selectRow", 0);
			else kpiClearForm();
			$("#kpi-form-plebitis").textbox("textbox").attr("maxlength", 10);
			$("#kpi-form-postopinfeksi").textbox("textbox").attr("maxlength", 10);
			kpiSetReadonly(true);
		},
		onSelect: function(index, row) {
			
			kpiChangeByUser = false;
			$("#kpi-form-id").textbox("setValue", row.kpi_id);
			kpiSetReadonly(false);
			$("#kpi-form-tanggal").datetimebox("setValue", row.kpi_tanggal);
			$("#kpi-form-kateter").checkbox(row.kpi_kateter=="true"?"check":"uncheck");
			$("#kpi-form-infus").checkbox(row.kpi_infus=="true"?"check":"uncheck");
			$("#kpi-form-plebitis").textbox("setValue",romanize(row.kpi_plebitis));
			$("#kpi-form-postophari").textbox("setValue",row.kpi_postophari);
			$("#kpi-form-postopinfeksi").checkbox(row.kpi_postopinfeksi=="true"?"check":"uncheck");
			$("#kpi-form-tirahbaring").checkbox(row.kpi_tirahbaring=="true"?"check":"uncheck");
			$("#kpi-form-hap").checkbox(row.kpi_hap=="true"?"check":"uncheck");
			$("#kpi-form-hasilkultur").textbox("setValue",row.kpi_hasilkultur);
			$("#kpi-form-gunaantibiotik").textbox("setValue",row.kpi_gunaantibiotik);
			$("#kpi-form-klasinfeksi").textbox("setValue",row.kpi_klasinfeksi);
			$("#kpi-form-diagnosa").textbox("setValue",row.kpi_diagnosa);
			$("#kpi-form-ruangan").textbox("setValue",row.kpi_ruangan);
			$("#kpi-form-user").textbox("setValue",row.kpi_user);
			kpiChangeByUser = true;
		}
	});

	function kpiSetReadonly(readonly) {
		$("#kpi-form-tanggal").datetimebox("readonly", readonly);
		$("#kpi-form-kateter").checkbox("readonly", readonly);
		$("#kpi-form-infus").checkbox("readonly", readonly);
		$("#kpi-form-plebitis").numberbox("readonly", readonly);
		$("#kpi-form-postophari").numberbox("readonly", readonly);
		$("#kpi-form-postopinfeksi").checkbox("readonly", readonly);
		$("#kpi-form-tirahbaring").checkbox("readonly", readonly);
		$("#kpi-form-hap").checkbox("readonly", readonly);
		$("#kpi-form-hasilkultur").textbox("readonly", readonly);
		$("#kpi-form-gunaantibiotik").textbox("readonly", readonly);
		$("#kpi-form-klasinfeksi").textbox("readonly", readonly);
		$("#kpi-form-diagnosa").textbox("readonly", readonly);
		$("#kpi-form-ruangan").textbox("readonly", readonly);
		$("#kpi-form-user").textbox("readonly", readonly);
	}
	
	function kpiSetEdited() {
		if (kpiEditedId == -1 && kpiChangeByUser) {
			kpiEditedId = $("#kpi-form-id").textbox("getValue");
			kpiSetEnableDisable();
		}
	}

	function kpiAdd() {
		kpiChangeByUser = false;
		kpiClearForm();
		var today = new Date();
		$("#kpi-form-tgperiksa").datebox("setValue", today.toLocaleDateString());
		kpiSetReadonly(false);
		kpiChangeByUser = true;
		kpiEditedId = 0; // mode tambah
		kpiSetEnableDisable();
		$("#kpi-form-s").textbox("textbox").focus();
	}

	const romanHash = {
		I: 1,
		V: 5,
		X: 10,
		L: 50,
		C: 100,
		D: 500,
		M: 1000,
	};
	//$('#kpi-form-kateter').checkbox('check')
	function romanToInt(s) {
		let accumulator = 0;
	  	for (let i = 0; i < s.length; i++) {
			if (s[i] === "I" && s[i + 1] === "V") {
				accumulator += 4;
				i++;
			} else if (s[i] === "I" && s[i + 1] === "X") {
				accumulator += 9;
				i++;
			} else if (s[i] === "X" && s[i + 1] === "L") {
				accumulator += 40;
				i++;
			} else if (s[i] === "X" && s[i + 1] === "C") {
				accumulator += 90;
				i++;
			} else if (s[i] === "C" && s[i + 1] === "D") {
				accumulator += 400;
				i++;
			} else if (s[i] === "C" && s[i + 1] === "M") {
				accumulator += 900;
				i++;
			} else {
				accumulator += romanHash[s[i]];
			}
		}
		return accumulator;
	}

	function kpiSave() {
		var selectedKunRow = $("#yan-grid").datagrid("getSelected");
		var data = {
			db: getDB(),
			kpi_id: kpiEditedId,
			kpi_kun_id:selectedKunRow.kun_id,
			kpi_tanggal:$('#kpi-form-tanggal').datetimebox('getValue'),
			kpi_kateter: $("#kpi-form-kateter").checkbox("options").checked,
			kpi_infus: $("#kpi-form-infus").checkbox("options").checked,
			kpi_plebitis: romanToInt($("#kpi-form-plebitis").textbox("getValue")),
			kpi_postophari: $("#kpi-form-postophari").textbox("getValue"),
			kpi_postopinfeksi: $("#kpi-form-postopinfeksi").checkbox("options").checked,
			kpi_tirahbaring: $("#kpi-form-tirahbaring").checkbox("options").checked,
			kpi_hap: $("#kpi-form-hap").checkbox("options").checked,
			kpi_hasilkultur: $("#kpi-form-hasilkultur").textbox("getValue"),
			kpi_gunaantibiotik: $("#kpi-form-gunaantibiotik").textbox("getValue"),
			kpi_klasinfeksi: $("#kpi-form-klasinfeksi").textbox("getValue"),
			kpi_diagnosa: $("#kpi-form-diagnosa").textbox("getValue"),
			kpi_ruangan: $("#kpi-form-ruangan").textbox("getValue"),
			kpi_user: $("#kpi-form-user").textbox("getValue"),
			username: globalConfig.login_data.username,
			db: globalConfig.login_data.db
		};
		$.ajax({
			type: "POST",
			data: data,
			url: getRestAPI("kunppi/save"),
			success: function(retval) {
				var obj = JSON.parse(retval);
				if (obj.status == "success") {
					if (kpiEditedId == 0) {
						$("#kpi-grid").datagrid("insertRow", {
							index: 0,
							row: obj.row
						});
						$("#kpi-grid").datagrid("selectRow", 0);
					} 
					else {
						var selectedRow = $("#kpi-grid").datagrid("getSelected");
						var index = $("#kpi-grid").datagrid("getRowIndex", selectedRow);
						$("#kpi-grid").datagrid("updateRow", {
							index: index,
							row: obj.row
						});
						$("#kpi-grid").datagrid("selectRow", index);
					}
				} 
				else alert(obj.errmsg);
				kpiEditedId = -1;
				kpiSetEnableDisable();
			}
		});
	}

	function kpiCancel() {
		kpiEditedId = -1;
		kpiSetEnableDisable();
		var row = $("#kpi-grid").datagrid("getSelected");
		if (row)
		$("#kpi-grid").datagrid(
			"selectRow",
			$("#kpi-grid").datagrid("getRowIndex", row)
		);
		else kpiSetReadonly(true);
	}

	function kpiDelete() {
		alert("Maaf menu ini belum bisa dipakai");
	}

	function kpiSetEnableDisable() {
		if (kpiEditedId >= 0) {
			// mode tambah atau edit
			$("#kpi-btn-add").linkbutton("disable");
			$("#kpi-btn-save").linkbutton("enable");
			$("#kpi-btn-cancel").linkbutton("enable");
			$("#kpi-btn-del").linkbutton("disable");
		} 
		else {
			// mode lihat, tombol hapus akan enable kalau ada row yg terselect
			$("#kpi-btn-add").linkbutton("enable");
			$("#kpi-btn-save").linkbutton("disable");
			$("#kpi-btn-cancel").linkbutton("disable");
			$("#kpi-btn-del").linkbutton("enable");
		}
	}

	function kpiTextboxOnChange(obj) {
		if (!kpiChangeByUser) return;
		var str = $(obj).textbox("getValue");
		str = romanize(str);
		var prev = kpiChangeByUser;
		kpiChangeByUser = false;
		$(obj).textbox("setValue", str);
		kpiChangeByUser = prev;
		kpiSetEdited();
	}
    
    function romanize(num) {
		var lookup = {M:1000,CM:900,D:500,CD:400,C:100,XC:90,L:50,XL:40,X:10,IX:9,V:5,IV:4,I:1},
			roman = '',
			i;
		for ( i in lookup ) {
			while ( num >= lookup[i] ) {
				roman += i;
				num -= lookup[i];
			}
		}
        return roman;
    }
    
	function kpiClearForm() {
		var prev = kpiChangeByUser;
		kpiChangeByUser = false;
		$("#kpi-form-tanggal").datetimebox("setValue", "");
			$("#kpi-form-kateter").checkbox("uncheck");
			$("#kpi-form-infus").checkbox("uncheck");
			$("#kpi-form-plebitis").numberbox("setValue","");
			$("#kpi-form-postophari").numberbox("setValue","");
			$("#kpi-form-postopinfeksi").checkbox("uncheck");
			$("#kpi-form-tirahbaring").checkbox("uncheck");
			$("#kpi-form-hap").checkbox("uncheck");
			$("#kpi-form-hasilkultur").textbox("setValue","");
			$("#kpi-form-gunaantibiotik").textbox("setValue","");
			$("#kpi-form-klasinfeksi").textbox("setValue","");
			$("#kpi-form-diagnosa").textbox("setValue","");
			$("#kpi-form-ruangan").textbox("setValue","");
			$("#kpi-form-user").textbox("setValue","");
		kpiChangeByUser = prev;
	}
});
