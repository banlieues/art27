
<div id="loading" class="vh-100 vw-100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="fa-stack fa-4x text-center">
            <?php echo fontawesome('spinner');?>
        </div>
    </div>
</div>

<style>
#loading {
    display:    none;
    position:   fixed;
    z-index:    10000;
    top:        0;
    left:       0;
    background: rgba( 255, 255, 255, .8 ) 
                50% 50% 
                no-repeat;
}

#loading.show {
    overflow: hidden;
    display: block; 
}

</style>