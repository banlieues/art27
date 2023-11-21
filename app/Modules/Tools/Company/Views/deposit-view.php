<form id="depositDublonForm">
    <table class="table table-sm">
        <?php if(count($posts)>1):?>
            <thead class="thead-light">
                <th scope="col" class="col-3"></th>
                <?php $i = 0;?>
                <?php foreach($posts as $post) :?>
                    <th scope="col" class="align-top text-right">
                        <input header type="radio" name="id_company" 
                            <?php if($i==0):?> checked
                            <?php else:?> value="<?php echo $post->id_company;?>" 
                            <?php endif;?>
                            onclick="select_dublon_bis(this);"
                        />
                        <?php if($i==0) :?> 
                            <input type="hidden" name="id_deposit" value="<?php echo $post->id_deposit;?>" />
                        <?php endif;?>
                    </th>
                    <th scope="col" class="align-top"> 
                        <?php if($i==0):?> Nouvelle fiche
                        <?php else:?> Fiche existante
                        <?php endif;?>
                    </th>
                    <?php $i++;?>
                <?php endforeach;?>
            </thead>
        <?php endif;?>
        <tbody>
            <?php echo view('Company\deposit-view-row', ['ref'=>'label']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'id_juridic_form']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'website']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'bce']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'address_street']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'address_pc']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'address_city']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'id_lang']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'contact_name']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'contact_lastname']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'contact_phone']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'contact_email']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'ids_contact_schedule']);?>
            <?php echo view('Company\deposit-view-row', ['ref'=>'comment']);?>
        </tbody>
    </table>
</form>
