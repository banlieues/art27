<?php $this->extend("Layout\index"); ?>

<?php $this->section("body"); ?>

<?php if ($selected_cropper < 1): ?>

    <form method="post" enctype="multipart/form-data">
        <div><?php echo csrf_field(); ?></div>
        <div class="float-end">
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fa fa-upload"></i> <?php echo lang('Buttons.upload'); ?>
            </button>
            <a class="btn btn-danger btn-sm" href="<?php echo base_url('user/avatar'); ?>">
                <i class="fa fa-times"></i> <?php echo lang('Buttons.cancel'); ?>
            </a>
        </div>
        <h4><?php echo $title.' : '.$subtitle; ?></h4>
        <div class="clearfix"></div>

        <div class="card flex-fill mb-4">
            <h5 class="card-header">
                <i class="<?php echo icon('avatar'); ?> float-end mt-1"></i>
                <?php if($user->id!=session('loggedUserId')):?>
                    <?php echo lang('Avatar.avatar_of'); ?>
                    <?php echo $user->prenom;?> <?php echo $user->nom;?>
                    <small>(<?php echo $user->username;?>)</small>
                <?php else:?>
                    Mon avatar
                <?php endif;?>
            </h5>
            <div class="card-body-off">
                <div class="row">
                    <div class="col-md-3 col-xs-12 col-sm-6 col-lg-3 text-center ">
                        <div class="text-center m-2">

                            <?php if(isset($avatar_name) && isset($avatar_path)): ?>
                                <figure>
                                    <img src="<?php echo $avatar_path.$avatar_name; ?>" alt="<?php echo $avatar_name; ?>" class="figure-img img-fluid img-thumbnail preview" />
                                    <!-- HAHA <figcaption class="figure-caption bg-primary rounded text-white text-center caption"><?php // echo pathinfo($avatar_name, PATHINFO_FILENAME); ?></figcaption> -->
                                </figure>
                            <?php //else: ?>
                                <!-- <figure class="" style="max-width:256px; height: auto; margin: 0 auto;">
                                    <img src="<?php echo base_url($avatar_path.$avatar_default); ?>" id="uploaded_image" class="figure-img img-fluid img-thumbnail preview" />
                                    <figcaption class="figure-caption bg-primary rounded text-white text-center caption"><?php // echo AVATAR_DEFAULT; ?></figcaption>
                                </figure> -->
                            <?php endif; ?>

                        </div>
                    </div>
                    <div class="col-md-9 col-xs-12 col-sm-6 col-lg-9">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="cropper" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('cropper'); ?>" style="width:20px;"></i> <?php echo lang('Avatar.cropper'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="cropper" name="cropper" value="<?php echo lang('Avatar.nocropper'); ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="avatar_name" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('avatar'); ?>" style="width:20px;"></i> <?php echo lang('Avatar.filename'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="avatar_name" name="avatar_name" value="<?php echo $avatar_name; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="avatar" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('avatar'); ?>" style="width:20px;"></i> <?php echo lang('Avatar.avatar'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input class="form-control mb-2" type="file" name="avatar" id="avatar">
                                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'avatar') : '' ?></span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary"></div>
        </div>
    </form>


    <script>
    function preview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.preview').attr('src', e.target.result);
                $('.caption').text(input.files[0].name);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        $("#avatar").change(function () {
            preview(this);
        });
    });
    </script>

<?php else: ?>

    <link rel="stylesheet" href="<?php echo base_url('node_modules/cropperjs/dist/cropper.min.css'); ?>" />
    <style>
    .preview {
        overflow: hidden;
        width: 256px; 
        height: 256px;
        margin: 0px 10px;
        border: 1px solid #ddd;
    }
    .modal-lg {max-width: 1000px !important;}
    </style>


    <form  method="post" enctype="multipart/form-data">
        <div><?php echo csrf_field(); ?></div>
        <div class="float-end">
        
        
        </div>
        <h4>
            <i class="<?php echo icon('avatar'); ?>"></i>
            <?php if($user->id!=session('loggedUserId')):?>
                <?php echo lang('Avatar.avatar_of'); ?>
                <?php echo $user->prenom;?> <?php echo $user->nom;?>
                <small>(<?php echo $user->username;?>)</small>
            <?php else:?>
                Mon avatar
            <?php endif;?>
        </h4>
        <div class="clearfix"></div>

        <div class="card flex-fill mb-4">
        
            <div class="card-body-off">
                <div class="row">
                    <div class="col-md-3 col-xs-12 col-sm-6 col-lg-3 text-center ">
                        <div class="text-center m-2">

                            <figure class="">
                                <img src="<?php echo base_url($avatar_path.$avatar_name); ?>" id="uploaded_image" class="figure-img img-fluid img-thumbnail" />
                                <!--
                                <figcaption class="figure-caption bg-primary rounded text-white text-center">
                                    <?php // echo pathinfo($avatar_name, PATHINFO_FILENAME); ?>
                                </figcaption>
                                -->
                            </figure>

                        </div>
                    </div>
                    <div class="col-md-9 col-xs-12 col-sm-6 col-lg-9">
                        <ul class="list-group list-group-flush">
                            <!-- <li class="list-group-item">
                                <div class="row">
                                    <label for="cropper" class="col-sm-3 col-form-label">
                                        <i class="<?php //echo icon('cropper'); ?>" style="width:20px;"></i> <?php //echo lang('Avatar.cropper'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="cropper" name="cropper" value="<?php //echo lang('Avatar.simplecropper'); ?>" placeholder="">
                                    </div>
                                </div>
                            </li> -->
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="avatar_name" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('avatar'); ?>" style="width:20px;"></i> <?php echo lang('Avatar.filename'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="avatar_name" name="avatar_name" value="<?php echo $avatar_name; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="avatar" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('avatar'); ?>" style="width:20px;"></i> <?php echo lang('Avatar.avatar'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input class="form-control mb-2" type="file" name="avatar" id="avatar">
                                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'avatar') : '' ?></span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo lang('Avatar.cropbeforeup'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">
                                <img src="" id="sample_image" />
                            </div>
                            <div class="col-md-4">
                                <div class="preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="crop" class="btn btn-primary"><?php echo lang('Avatar.crop'); ?></button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <?php echo lang('Buttons.cancel'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('node_modules/cropperjs/dist/cropper.min.js'); ?>"></script>
    <script>
    // $(document).ready(function() {
    document.addEventListener("DOMContentLoaded", function() {
        var $modal = $('#modal');
        var image = document.getElementById('sample_image');
        var cropper;

        // $('#avatar').change(function(event) {
        var input = $("#avatar");
        input.change(function(event) {
            var files = event.target.files;
            var done = function(url) {
                image.src = url;
                $modal.modal('show');
                input.val(null);
            };

            if (files && files.length > 0)
            {
                reader = new FileReader();

                reader.onload = function(event)
                {
                    done(reader.result);
                };
                reader.readAsDataURL(files[0]);
            }
        });

        let view_mode = parseInt(<?php echo $view_mode; ?>); // default is 1
        let drag_mode = (<?php echo $drag_mode; ?>).toString(); // default is crop
        let aspect_ratio = parseInt(<?php echo $aspect_ratio; ?>); // default is 1
        let min_cropbox_width = parseFloat(<?php echo $min_cropbox_width; ?>); // default is 256
        let min_cropbox_height = parseFloat(<?php echo $min_cropbox_height; ?>); // default is 256

        if (aspect_ratio == 43) aspect_ratio = 4/3;
        else if (aspect_ratio == 169) aspect_ratio = 16/9;
        else aspect_ratio = 1/1;

        $modal.on('shown.bs.modal', function() {
            cropper = new Cropper(image, {
                viewMode: view_mode, // default is 1
                dragMode: drag_mode, // default is crop
                aspectRatio: aspect_ratio, // default is 1
                preview: '.preview', // default is ''
                // autoCrop: true,
                // autoCropArea: 1.0,
                // minContainerWidth: 256,
                // minContainerHeight: 256,
                // minCanvasWidth: 256,
                // minCanvasHeight: 256,
                minCropBoxWidth: min_cropbox_width, // default is 256
                minCropBoxHeight: min_cropbox_height, // default is 256
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        $('#crop').click(function() {
            canvas = cropper.getCroppedCanvas({
                width: min_cropbox_width, // default is 256
                height: min_cropbox_height, // default is 256
                // minWidth: 256,
                // minHeight: 256,
                // maxWidth: 512,
                // maxHeight: 512,
                // fillColor: '#fff',
                imageSmoothingEnabled: true, // default is true
                imageSmoothingQuality: 'high', // default is low (low, medium, high)
            }, 'image/jpeg', 1);

            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    $.ajax({
                        url: "<?php if($user->id==session('loggedUserId')) echo base_url("user/avatar"); else echo base_url("user/avatar?id_user=$user->id");?>",
                        method: 'POST',
                        data:{image: base64data},
                        headers: {'X-Requested-With': 'XMLHttpRequest'},
                        success:function(data) {
                            $modal.modal('hide');
                            $('#uploaded_image').attr('src', data);
                            <?php if($user->id==session('loggedUserId')):?>
                                $('.avatar').attr('src', data);
                        <?php endif;?>
                        },
                        error() {console.log('Upload error ...');},
                    });
                };
            });
        });
    });
    </script>
<?php endif; ?>


<?php $this->endSection(); ?>
