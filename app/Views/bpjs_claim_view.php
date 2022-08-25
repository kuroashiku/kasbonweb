<script type="text/javascript" src="<?= base_url('js/custom/bpjs_claim_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/utility.js') ?>"></script>

<div id="bpjs-claim-view" class="p-4">
    <div class="flex justify-content-center">
        <span id="bpjs-loading">
            <span class="spinner-border spinner-border-sm text-info mr-2"></span>
            <span class="text-info">Calculating</span>
        </span>
    </div>
    <div id="bpjs-response">
        <div class="text-center">Biaya yang bisa di-cover BPJS adalah sebesar</div>
        <div id="bpjs-value" class="text-center font-weight-bold"></div>
        <div class="text-center mt-3">
        <div id="bpjs-btn-copy"></div>
        </div>
    </div>
</div>