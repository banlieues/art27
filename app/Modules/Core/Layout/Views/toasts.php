<?php
/**
 * Toasts Switch v0.1 by djphil (CC-BY-NC-SA 4.0)
 * A simple array swith to display toast message
**/
?>

<button type="button" class="btn btn-primary" id="toastbtn">Show live toast</button>
<!-- <div class="toast-container position-absolute p-3" id="toastPlacement"> -->
<!-- <div class="position-fixed top-2 start-50 translate-middle-x" style="z-index: 11"> -->
<div class="position-fixed top-0 start-50 translate-middle-x p-3 toast-container" style="z-index: 11">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header"><!-- bg-theme text-white -->
            <!-- <img src="..." class="rounded me-2" alt="..."> -->
            <i class="<?php echo icon('info'); ?> me-2"></i> <strong class="me-auto">Bootstrap</strong>
            <small>11 mins ago</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Hello, world! This is a toast message.
        </div>
    </div>
</div>

<?php
$flash = session()->getFlashdata();

foreach($flash as $key => $value)
{
    if(!in_array($key, ['primary', 'secondary', 'success', 'warning', 'danger', 'info', 'light', 'dark'])) $key = 'primary';
    echo '
        <div class="alert alert-' . $key . ' alert-dismissible fade show" role="alert">
            <i class="' . icon($key) . '"></i>
            ' . $value . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    ';

    // switch ($key)
    // {
    //     case 'primary':
    //         echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">';
    //         echo '<i class="'.icon($key).'"></i> '.$value;
    //         break;
    //     case 'secondary':
    //         echo '<div class="alert alert-secondary alert-dismissible fade show" role="alert">';
    //         echo '<i class="'.icon($key).'"></i> '.$value;
    //         break;
    //     case 'success':
    //         echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    //         echo '<i class="'.icon($key).'"></i> '.$value;
    //         break;
    //     case 'warning':
    //         echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
    //         echo '<i class="'.icon($key).'"></i> '.$value;
    //         break;
    //     case 'danger':
    //         echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    //         echo '<i class="'.icon($key).'"></i> '.$value;
    //         break;
    //     case 'info':
    //         echo '<div class="alert alert-info alert-dismissible fade show" role="alert">';
    //         echo '<i class="'.icon($key).'"></i> '.$value;
    //         break;
    //     case 'light':
    //         echo '<div class="alert alert-light alert-dismissible fade show" role="alert">';
    //         echo '<i class="'.icon($key).'"></i> '.$value;
    //         break;
    //     case 'dark':
    //         echo '<div class="alert alert-dark alert-dismissible fade show" role="alert">';
    //         echo '<i class="'.icon($key).'"></i> '.$value;
    //         break;
    //     default:
    //     echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">';
    //     echo '<i class="'.icon($key).'"></i> '.$value;
    // }

    // echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    // echo '</div>';
}
?>


<script>
// $(document).ready(function() {
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("toastbtn").onclick = function() {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl)
        })
        toastList.forEach(toast => toast.show())
    }
});
</script>