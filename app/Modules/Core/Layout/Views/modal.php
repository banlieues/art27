<div class="modal fade" tabindex="-1" id="modal">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable <?php $this->renderSection('modal_size');?>">
        <div class="modal-content">
            <div class="modal-header justify-content-center align-items-center mx-2">
                <h5 class="modal-title">

                    <?php $this->renderSection('modal_title');?>
                    
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mt-4 mx-4 mb-0">
                    <?php 
                        if(empty($error)) $this->renderSection('modal_body');
                        else $this->renderSection('modal_error')
                    ;?>

            </div>
            <div class="modal-footer d-flex align-items-end m-2">

                <?php $this->renderSection('modal_buttons');?>

                <button type="button" class="btn btn-sm btn-outline-secondary modal-close" data-bs-dismiss="modal">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalEditor" tabindex="1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header justify-content-center align-items-center mx-2">
                <h5 class="modal-title"> Editer le contenu </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mt-4 mx-4 mb-0">
                <!-- <div class="alert alert-success mb-2"> 
                    Pour faire une saut à la ligne sans marge inférieure : <kbd> shift + enter </kbd> 
                </div> -->
                <div class="summernote">
                </div>
            </div>
            <div class="modal-footer d-flex align-items-end m-2">
                <button type="button" class="btn btn-sm btn-primary modal-close valid-button" data-bs-dismiss="modal"> 
                    Valider 
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary modal-close" data-bs-dismiss="modal">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

