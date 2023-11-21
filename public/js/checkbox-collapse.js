<script>
    // ------------------------------------------------------------------
    // Input checkbox collapse
    // ------------------------------------------------------------------
    $(document).on('click', 'input[type="checkbox"][data-bs-toggle="collapse"][data-bs-target]', function() {
        input_check_collapse(this);
    });
    function input_check_collapse(elem) {
        let button = $('button[data-bs-toggle="collapse"][data-bs-target="' + $(elem).attr('data-bs-target') + '"]');
        if(button.length==0) {
            $(elem).parent().append('<button data-bs-toggle="collapse" data-bs-target="' + $(elem).attr('data-bs-target') + '" hidden></button>');
            button = $('button[data-bs-toggle="collapse"][data-bs-target="' + $(elem).attr('data-bs-target') + '"]');
        }
        button.click();
    }
</script>