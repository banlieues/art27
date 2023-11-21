<script type="text/javascript">

// ------------------------------------------------------------------
// JS_FILTER
// ------------------------------------------------------------------

function filter_by_entity_show(elem, id_query=0)
{
    const entity_ref = $(elem).val();
    const url = "<?php echo base_url('query/filter');?>/" + entity_ref;
    const container = $('#filter-entity-' + id_query);
    $(container).hide();
    $.get(url, function(view) {
        $(container).html(view);
        $('.bs-multi-select', container).bsMultiSelect('Update');
        $(container).show();
    }); 
}


</script>

