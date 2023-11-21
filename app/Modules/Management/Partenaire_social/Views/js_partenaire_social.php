<script>
        jQuery(document).ready(function()
        {
            $(document).on('change', '#select_annee', function()
            {
                var url=$(this).val();
                window.location.href=url;
                
            });
        });

</script>