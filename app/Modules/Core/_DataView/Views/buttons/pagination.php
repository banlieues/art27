<div class="d-flex align-items-center">
    <div>
        <div class="input-group input-group-sm">
            <span class="input-group-text"> Pagination </span>
            <select class="form-select form-select-sm" name="per_page"
                form="searchOrderForm"
                onchange="$('#searchOrderForm').submit();"
                >
                <option value="20" <?php if($per_page=='20'):?> selected <?php endif;?>> 20 </option>
                <option value="50" <?php if($per_page=='50'):?> selected <?php endif;?>> 50 </option>
                <option value="100" <?php if($per_page=='100'):?> selected <?php endif;?>> 100 </option>
                <option value="200" <?php if($per_page=='200'):?> selected <?php endif;?>> 200 </option>
                <option value="500" <?php if($per_page=='500'):?> selected <?php endif;?>> 500 </option>
                <!-- <option value="all" <?php //if($per_page=='all'):?> selected <?php //endif;?>> Tout </option> -->
            </select>
        </div>
    </div>
    <?php if(!empty($pager) && $pager->getPageCount()>1):?>
        <div class="ms-2">
            <?php echo $pager->links('default', 'custom_pager');?>
        </div>
    <?php endif;?>
</div>

