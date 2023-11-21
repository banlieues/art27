<div class="d-flex justify-content-between px-2">
    <?php echo $controls->id_company;?>
    <?php echo $controls->id_status;?>
    <?php if(!empty($company->last_update)) echo $company->last_update;?>
</div>
<div class="row">
    <div class="col-2 order-last">
        <div class="sticky_button">
            <div class="bg-light border rounded p-2 mb-2">
                <div class="my-2">
                    <a class="text-body" href="#companyData"> <small> <?php echo t("Coordonnées de l'entreprise", $namespace);?> </small> </a>
                </div>
                <?php if(!empty($company->id_company)):?>
                    <div class="my-2">
                        <a class="text-body" href="#companyFiles"> <small> <?php echo t("Documents", $namespace);?> </small> </a>
                    </div>
                <?php endif;?>
                <div class="mb-2">
                    <a class="text-body" href="#companyContact"> <small> <?php echo t("Contact", $namespace);?> </small> </a>
                </div>
                <div class="mb-2">
                    <a class="text-body" href="#companyProfession"> <small> <?php echo t("Accès à la profession", $namespace);?> </small> </a>
                </div>
                <div class="mb-2">
                    <a class="text-body" href="#companyRoadWork"> <small> <?php echo t("Type de travaux", $namespace);?> </small> </a>
                </div>
                <div class="mb-2">
                    <a class="text-body" href="#companyRoadEcoImpact"> <small> <?php echo t("Impact environnemental", $namespace);?> </small> </a>
                </div>
                <div class="mb-2">
                    <a class="text-body" href="#companyComment"> <small> <?php echo t("Notes", $namespace);?> </small> </a>
                </div>
            </div>
            <?php if(!empty($company->id_company) && $Autorisation->is_autorise('company_d', $company->created_by)):?>
                <button type="button" 
                    class="btn btn-sm btn-outline-danger" 
                    onclick="company_delete_modal(this, <?php echo $company->id_company;?>);"
                    > 
                    Supprimer la fiche de l'entreprise 
                </button>
            <?php endif;?>
        </div>
    </div>
    <div class="col-10">
        <div id="companyData" class="card mb-4">
            <div class="card-header"> <?php echo t("Coordonnées de l'entreprise", $namespace);?> </div>
            <div class="card-body">
                <?php echo $controls->label;?>
                <?php echo $controls->website;?>
                <?php echo $controls->address;?>
                <?php echo $controls->address_number;?>
                <?php echo $controls->address_street;?>
                <?php echo $controls->address_pc;?>
                <?php echo $controls->address_city;?>
                <?php echo $controls->address_fr;?>
                <?php echo $controls->address_nl;?>
                <?php echo $controls->id_juridic_form;?>
                <?php echo $controls->bce;?>
                <?php echo $controls->birthdate;?>
                <?php echo $controls->nb_workers;?>               
            </div>
        </div>
        <?php if(!empty($company->id_company)):?>
            <div id="companyFiles" class="card mb-4">
                <div class="card-header"> <?php echo t("Documents", $namespace);?> </div>
                <div class="card-body">
                    <?php echo $controls->ids_file;?>
                </div>
            </div>
        <?php endif;?>
        <div id="companyContact" class="card mb-4">
            <div class="card-header"> <?php echo t("Contact", $namespace);?> </div>
            <div class="card-body">
                <?php echo $controls->contact_name;?>
                <?php echo $controls->contact_lastname;?>
                <?php echo $controls->id_lang;?>
                <?php echo $controls->id_contact_source;?>
                <?php echo $controls->ids_contact_type;?>
                <?php echo $controls->contact_phone;?>
                <?php echo $controls->contact_email;?>     
                <?php echo $controls->ids_contact_schedule;?>        
            </div>
        </div>
        <div id="companyProfession" class="card mb-4">
            <div class="card-header"> <?php echo t("Accès à la profession", $namespace);?> </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-auto"> 
                        <?php echo t("Vérifier les accès à la profession de l'entreprise", $namespace);?>
                    </div>
                    <div class="col-auto">
                        <a href="http://www.ejustice.just.fgov.be/cgi_loi/change_lg.pl?language=fr&la=F&cn=2007012950&table_name=loi" target="_blank"> FR </a> 
                        - 
                        <a href="http://www.ejustice.just.fgov.be/cgi_loi/change_lg.pl?language=nl&la=N&cn=2007012950&table_name=wet" target="_blank"> NL </a>
                    </div>
                </div>
                <?php echo $controls->ids_access_prof;?>
                <?php echo $controls->ids_declaration;?>
            </div>
        </div>
        <div id="companyRoadWork" class="card mb-4">
            <div class="card-header"> <?php echo t("Type de travaux", $namespace);?> </div>
            <div class="card-body">
                <?php echo $controls->ids_road_work;?>
            </div>
        </div>
        <div id="companyRoadEcoImpact" class="card mb-4">
            <div class="card-header"> <?php echo t("Impact environnemental", $namespace);?> </div>
            <div class="card-body">
                <?php echo $controls->ids_road_eco_impact;?>
            </div>
        </div>
        <!-- <fieldset>
            <legend> <?php echo t("Labels", $namespace);?> </legend>
            <?php echo $controls->is_asbestos_label;?>
            <?php echo $controls->ids_durability_label;?>
            <?php echo $controls->ids_ecological_dim;?>
            <fieldset>
                <legend> <?php echo t("Rénovation et construction circulaire", $namespace);?></legend>
                <?php echo $controls->ids_build_circular;?>
                <?php echo $controls->workshop_renovation;?>
                <?php echo $controls->is_interest_workshop_renovation;?>
            </fieldset>
            <fieldset>
                <legend> <?php echo t("Acoustique", $namespace);?></legend>
                <?php echo $controls->workshop_acoustic;?>
                <?php echo $controls->is_nbn_s_01_400_1;?>
                <?php echo $controls->is_code_practice;?>
            </fieldset>
            <?php echo $controls->is_group_buying;?>
            <fieldset>
                <legend> <?php echo t("Assurances litiges", $namespace);?></legend>
                <?php echo $controls->is_ccce;?>
            </fieldset>
        </fieldset> -->
        <div id="companyComment" class="card mb-4">
            <div class="card-header"> <?php echo t("Notes", $namespace);?> </div>
            <div class="card-body">
                <?php echo $controls->comment;?>
            </div>
        </div>
    </div>
</div>
