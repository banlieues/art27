<script>
    // ------------------------------------------------------------------
    // Console.log Formdata
    // ------------------------------------------------------------------
    function console_formdata(formdata) {
        for (var pair of formdata.entries()) {
            console.log(pair[0]+ ', ' + pair[1]); 
        } 
    }
</script>