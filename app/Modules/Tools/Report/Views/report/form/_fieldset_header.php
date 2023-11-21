<fieldset id="emodelSectionFieldset">
    <legend id="emodelSectionLegend">  En-tête et pied de page </legend>

    <div class="row">
        <label for="reportHeader" class="col-sm-2">En-tête</label>
        <div id="reportHeader" class="col-10">
            <div class="form-group row">
                <div class="form-check form-check-inline">
                    <input type="radio"  id="reportHeaderNo"class="form-check-input" name="isHeader" <?php if(isset($isHeader) && $isHeader == 0) {echo 'checked';}?> data-bs-toggle="collapse" href="#collapseHeaderNo" aria-expanded="false" value="0"/>
                    <label class="form-check-label" for="isHeader"> Non </label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="reportHeaderDefault" class="form-check-input" name="isHeader" <?php if(!isset($isHeader) || $isHeader > 0) {echo 'checked';}?>  data-bs-toggle="collapse" href="#collapseHeaderDefault" aria-expanded="false" value="1"/>
                    <label class="form-check-label" for="isHeaderDefault"> Par défaut </label>
                </div>
<!--                <div class="form-check form-check-inline">
                    <input type="radio" id="reportHeaderCustom" class="form-check-input" name="isHeader" <?php // if(isset($isHeader) && $isHeader == 'custom') {echo 'checked';}?>  data-bs-toggle="collapse" href="#collapseHeaderCustom" aria-expanded="false" value="custom"/>
                    <label class="form-check-label" for="isHeaderCustom"> Personnalisée </label>
                </div>-->
            </div>
            <div id="collapseHeaderDefault" class="border mb-3 p-3 collapse <?php if(!isset($isHeader) || $isHeader > 0) {echo 'show';}?>" data-parent="#reportHeader">
                <?php if(isset($default['header'])) {echo $default['header'];}?>
            </div>
            <div id="collapseHeaderNo" class="collapse <?php if(!isset($isHeader) || $isHeader > 0) {echo 'show';}?>" data-parent="#reportHeader">
            </div>
<!--            <div id="collapseHeaderCustom" class="mb-3 collapse <?php // if(isset($isHeader) && $isHeader == 'custom') {echo 'show';}?>" data-parent="#reportHeader">
                <div class="summernote" name="header_fr"><?php // if(isset($header_fr)) {echo $header_fr;} ?> </div>
            </div>-->
        </div>
    </div>
    <div class="row">
        <label for="reportFooter" class="col-sm-2">En-tête</label>
        <div id="reportFooter" class="col-10">
            <div class="form-group row">
                <div class="form-check form-check-inline">
                    <input type="radio"  id="reportFooterNo"class="form-check-input" name="isFooter" <?php if(isset($isFooter) && $isFooter == 0) {echo 'checked';}?> data-bs-toggle="collapse" href="#collapseFooterNo" aria-expanded="false" value="0"/>
                    <label class="form-check-label" for="isFooter"> Non </label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="reportFooterDefault" class="form-check-input" name="isFooter" <?php if(!isset($isFooter) || $isFooter > 0) {echo 'checked';}?>  data-bs-toggle="collapse" href="#collapseFooterDefault" aria-expanded="false" value="1"/>
                    <label class="form-check-label" for="isFooterDefault"> Par défaut </label>
                </div>
<!--                <div class="form-check form-check-inline">
                    <input type="radio" id="reportFooterCustom" class="form-check-input" name="isFooter" <?php // if(isset($isFooter) && $isFooter == 'custom') {echo 'checked';}?>  data-bs-toggle="collapse" href="#collapseFooterCustom" aria-expanded="false" value="custom"/>
                    <label class="form-check-label" for="isFooterCustom"> Personnalisée </label>
                </div>-->
            </div>
            <div id="collapseFooterDefault" class="border mb-3 p-3 collapse <?php if(!isset($isFooter) || $isFooter > 0) {echo 'show';}?>" data-parent="#reportFooter">
                <?php if(isset($default['footer'])) {echo $default['footer'];}?>
            </div>
            <div id="collapseFooterNo" class="collapse <?php if(isset($isFooter) && $isFooter == 0) {echo 'show';}?>" data-parent="#reportFooter">
            </div>
<!--            <div id="collapseFooterCustom" class="mb-3 collapse <?php // if(isset($isFooter) && $isFooter == 'custom') {echo 'show';}?>" data-parent="#reportFooter">
                <div class="summernote" name="footer_fr"><?php // if(isset($footer_fr)) {echo $footer_fr;} ?> </div>
            </div>-->
        </div>
    </div>
</fieldset>