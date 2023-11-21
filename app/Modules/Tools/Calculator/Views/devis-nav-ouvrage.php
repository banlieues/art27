<a class="list-group-item d-flex"
    href="#DevisWork<?php echo $work->id_work;?>Anchor"
    >
    <div class="ms-3"> </div>
    <div>
        <?php echo $work->label;?>
        <button type="button"
            data-bs-toggle="collapse"
            data-bs-target="#DevisNavWork<?php echo $work->id_work;?>Collapse"
            class="btn-caret btn btn-sm py-0">
            <?php echo fontawesome('caret-down');?>
        </button>
    </div>
</a>
<div id="DevisNavWork<?php echo $work->id_work;?>Collapse"
    class="collapse show"
    >
    <?php if(!empty($work->groups)):?>
        <?php foreach($work->groups as $group):?>
            <a class="list-group-item d-flex"
                href="#DevisWork<?php echo $work->id_work;?>Group<?php echo $group->id_group;?>Anchor"
                >
                <div class="ms-3"> </div>
                <div class="ms-3"> </div>
                <div> <?php echo $group->label_fr;?> </div>
            </a>
        <?php endforeach;?>
    <?php endif;?>
</div>