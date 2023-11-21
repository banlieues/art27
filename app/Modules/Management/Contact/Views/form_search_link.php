
<div class="form_search_link" action="<?=base_url()?>/contact/result_search_link">

    <div class="input-group input-group-navbar text-lg-end mb-2 form_searh_link">
        <input name="itemSearch" type="text" class="itemSearch form-control" placeholder="Rechercher un contact" aria-label="Rechercher un contact" value="">
        <button class="btn btn-<?php echo $themes->contact->color;?> text-white btn-sm btn_search_link"><i class="fa fa-search"></i></button>
    </div>

    <div style="display:none; max-height: 40vh!important; overflow: auto " class="p-2 bg-light mb-2 form_search_link_result">
    </div>
</div>
    
