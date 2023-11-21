<div class='tableauPlaceholder' id='<?php echo $id;?>' style='position: relative'>
    <noscript>
        <a href='#'>
            <img alt=' ' src='https://public.tableau.com/static/images/<?php echo $section;?>/<?php echo $worksheet;?>_<?php echo $workcode;?>/<?php echo $sheet;?>/1_rss.png' style='border: none' />
        </a>
    </noscript>
    <object class='tableauViz'  style='display:none;'>
        <param name='host_url' value='https://public.tableau.com/' />
        <param name='embed_code_version' value='3' />
        <param name='site_root' value='' />
        <param name='name' value='<?php echo $worksheet;?>_<?php echo $workcode;?>/<?php echo $sheet;?>' />
        <param name='tabs' value='yes' />
        <param name='toolbar' value='yes' />
        <param name='static_image' value='https://public.tableau.com/static/images/<?php echo $section;?>/<?php echo $worksheet;?>_<?php echo $workcode;?>/<?php echo $sheet;?>/1.png' />
        <param name='animate_transition' value='yes' />
        <param name='display_static_image' value='yes' />
        <param name='display_spinner' value='yes' />
        <param name='display_overlay' value='yes' />
        <param name='display_count' value='yes' />
        <param name='language' value='fr-FR' />
    </object>
</div>
<script type='text/javascript'>
    var divElement = document.getElementById('<?php echo $id;?>');
    var vizElement = divElement.getElementsByTagName('object')[0];
    vizElement.style.width = '100%';
    console.log(screen.height);
    const height = window.innerHeight - $('#nav_general').outerHeight() - $('#topbar').outerHeight() - 25;
    vizElement.style.height = height + 'px';
    var scriptElement = document.createElement('script');
    scriptElement.src = 'https://public.tableau.com/javascripts/api/viz_v1.js';
    vizElement.parentNode.insertBefore(scriptElement, vizElement);
</script>



<!-- <div class="tableauPlaceholder" id="<?php echo $id;?>" style="position: relative">
    <noscript>
        <a href="#">
            <img alt="<?php echo $title;?>" src="<?php echo 'https://public.tableau.com/static/images/' . $section . '/' . $value . '/' . $title . '/1_rss.png';?>" style="border: none" />
        </a>
    </noscript>
    <object class="tableauViz"  style="display:none;">
        <param name="host_url" value="https://public.tableau.com/" />
        <param name="embed_code_version" value="3" />
        <param name="site_root" value="" />
        <param name="name" value="<?php echo $title . '_' . $value . '/' . $title;?>" />
        <param name="tabs" value="no" />
        <param name="toolbar" value="yes" />
        <param name="static_image" value="<?php echo 'https://public.tableau.com/static/images/' . $section . '/' . $value . '/' . $title . '/1.png';?>" />
        <param name="animate_transition" value="yes" />
        <param name="display_static_image" value="yes" />
        <param name="display_spinner" value="yes" />
        <param name="display_overlay" value="yes" />
        <param name="display_count" value="yes" />
        <param name="language" value="fr-FR" />
        <param name="filter" value="publish=yes" />
    </object>
</div>                

<script type='text/javascript'>
    var divElement = document.getElementById('<?php echo $id;?>');
    var vizElement = divElement.getElementsByTagName('object')[0];
    vizElement.style.width = '100%';
    console.log(screen.height);
    const height = window.innerHeight - $('#nav_general').outerHeight() - $('#topbar').outerHeight() - 25;
    vizElement.style.height = height + 'px';
    var scriptElement = document.createElement('script');
    scriptElement.src = 'https://public.tableau.com/javascripts/api/viz_v1.js';
    vizElement.parentNode.insertBefore(scriptElement, vizElement);
</script> -->