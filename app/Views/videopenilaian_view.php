<div class="easyui-layout" style="width:700px;height:400px;">
    <div data-options="region:'east',
        split:true" title="East" style="width:100px;">
        <div style="margin-bottom:20px">
            <input class="easyui-checkbox" name="Paham" value="Paham" label="Paham :">
        </div>
        <div style="margin-bottom:20px">
            <input class="easyui-textbox" label="Deskripsi:" labelPosition="top" style="width:100%;">
        </div>
        <div style="margin-bottom:20px">
            <select class="easyui-combobox" name="akurasi" label="Akurasi" labelPosition="top" style="width:50%;">
                <option value="20">20%</option>
                <option value="40">40%</option>
                <option value="60">60%</option>
                <option value="80">80%</option>
                <option value="100">100%</option>
            </select>
        </div>
    </div>
    <div data-options="region:'center',title:'Penilaian Video',iconCls:'icon-ok'">
        <table class="easyui-datagrid"
            data-options="url:'public/data_video.json',
            method:'POST',
            border:false,singleSelect:true,
            fit:true,
            fitColumns:true">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="10">ID</th>
                    <th data-options="field:'detik'" width="30">Detik</th>
                </tr>
            </thead>
        </table>
    </div>
</div>