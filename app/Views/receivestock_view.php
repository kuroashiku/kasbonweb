<script type="text/javascript" src="<?= base_url('js/custom/receivestock_view.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('css/dialog.css') ?>">
<div class="easyui-layout" data-options="fit:true" id="dlg-toolbar">
    <div id="receivestock-view">
        <div id="dialog-toolbar">
            <div class="flex-grow"></div>
            <div id="rcs-save" class="clickbox"></div>
        </div>
        <div data-options="region:'north',split:false,border:false" style="height: 100%; background-color:#8ae0ed; padding: 5rem 1rem 3rem 1rem; overflow:auto">  
            this is
            <div class="obg-container">
                <?php
                    use App\Models\ReceiveModel;
                    $receiveModel = new ReceiveModel();
                    $result = $receiveModel->orderedItemList($_POST);
                    echo json_encode($_POST);
                    echo $result['sql'];
                    if($result['status'] == 'success'):
                        $i=0;
                        while($result['item-'.$i]):
                            echo 'item-'.$i;
                ?>
                
                <?php
                        $i++;
                        endwhile;
                    endif;
                ?>
            </div>
        </div>
    </div>
</div>