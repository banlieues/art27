<?php
$color_class = 'text-white';
$span_color = 'text-white';

if (session()->has('loggedUserRoleId') && session('loggedUserRoleId') <= BACKEND_ACCESS_LEVEL)
{
    $color_class = 'text-body-secondary';
    $span_color = 'text-theme';
}    
?>

<footer class="footer bg-white text-white bg-gradient-off py-2 border-top">

    <div class="container-fluid">
        <div class="d-flex justify-content-between copyright <?php echo $color_class; ?>">
            <div></div>
            <div>
                <?php echo fontawesome('copyleft');?>
                <?php echo date('Y').' - '. (date('Y') + 1); ?> 
                <a href="<?php echo base_url(); ?>" class="link fw-bold <?php echo $color_class; ?>">
                    <?php echo $themes->main->name;?>
                </a>
            </div>
            <!-- <div> -->
                <!-- <ul class="list-inline"> -->
                    <!--
                    <li class="list-inline-item"><a class="link text-body-secondary" href="#">Support</a></li>
                    <li class="list-inline-item"><a class="link text-body-secondary" href="#">Help Center</a></li>
                    <li class="list-inline-item"><a class="link text-body-secondary" href="#">Privacy</a></li>
                    <li class="list-inline-item"><a class="link text-body-secondary" href="#">Terms</a></li>
                    -->
                    <!-- <li class="list-inline-item">
                        <a class="text-decoration-none <?php echo $color_class; ?>" target="_blank" href="https://codeigniter4.github.io/userguide/">
                            <i class="<?php echo icon('book'); ?>"></i> Docs
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-decoration-none <?php echo $color_class; ?>" target="_blank" href="https://forum.codeigniter.com/">
                            <i class="<?php echo icon('forum'); ?>"></i> Forum
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-decoration-none <?php echo $color_class; ?>" target="_blank" href="https://github.com/codeigniter4/CodeIgniter4">
                            <i class="<?php echo icon('github'); ?>"></i> Github
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-decoration-none <?php echo $color_class; ?> 
                            <?php echo (!empty($context) && $context == 'tutorials') ? 'active' : null; ?>" 
                            href="<?php echo base_url('tutorials') ?>"
                            >
                            <i class="<?php echo icon('tutorials'); ?>"></i> Tutorials
                        </a>
                    </li> -->
                <!-- </ul> -->
            <!-- </div> -->
        </div>
    </div>
</footer>
