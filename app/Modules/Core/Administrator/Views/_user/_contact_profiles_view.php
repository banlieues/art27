<?php if(count($profiles)>1):?>
    <div id="collapseProfiles" class=" mb-2">
        <ul class="nav nav-tabs" role="tablist">
            <?php $i = 1;?>
            <?php foreach($profiles as $profile):?>
                <li class="nav-item">
                    <button type="button" class="nav-link py-1 px-2 <?php if($i==1 || !empty($profile->is_default)):?>active<?php endif;?>" 
                        role="tab" data-bs-toggle="tab" data-bs-target="#collapseProfile<?php echo $profile->id_contact_profil;?>"
                        >
                        <small> <b><?php echo !empty($profile->profile_label) ? $profile->profile_label : $i;?></b></small>
                    </button>
                </li>
                <?php $i++;?>
            <?php endforeach;?>
        </ul>

        <div class="tab-content border border-top-0 rounded p-2">
            <?php $i = 1;?>
            <?php foreach($profiles as $profile):?>
                <div class="tab-pane fade <?php if($i==1 || !empty($profile->is_default)):?>show active<?php endif;?>" 
                    id="collapseProfile<?php echo $profile->id_contact_profil;?>"
                    role="tabpanel" data-bs-parent="#collapseProfiles"
                    >
                    
                        <?php echo view('\Administrator\user\contact_profiles_info', ['profile'=>$profile]);?>

                </div>
                <?php $i++;?>
            <?php endforeach;?>
        </div>
    </div>
<?php elseif(count($profiles)==1):?>
    <div class="border rounded p-2 mb-2">
        
        <?php echo view('\Administrator\user\contact_profiles_info', ['profile'=>$profiles[0]]);?>

    </div>
<?php endif;?>