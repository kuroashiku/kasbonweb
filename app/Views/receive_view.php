<div id="ccc" style="width:400px;height:300px;padding:5px">
  <div id="0" class="easyui-panel" data-options="fit:true,closable:true,collapsible:true,maximizable:true"/>
</div>
<script>
    $('#ccc').resizable({
		edge:5,
		onResize:function(){
			$('#p').panel('resize');
		},
		onStopResize:function(){
			$('#p').panel('resize');
		}
	});
</script>