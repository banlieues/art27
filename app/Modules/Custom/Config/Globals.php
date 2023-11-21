<?php

namespace Custom\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{

    public $type_dashboard="default"; //valer possible dynamic (façon homegrade), default(construit manuallement)
    public $has_note=false;
    public $has_email_outlook=false;

    public $webmasters = [2, 117]; // access for Jeremy, Tamo
    public $superadmins = [2, 24, 112, 117, 137]; // access for Jeremy, Lucrèce, Paulina, Tamo, Thomas

    public $prod_url = 'https://dev.art27.openbaz.be';
    public $dev_url = 'https://art27.crm.local';

    public $pk_file = 'id';
    public $clientExt = 'contentByte_Type';
    public $mimeType = 'contentByte_Type';

    public $t_answer = 'en_answer';
    public $t_autorisation = 'user_autorisation';
    public $t_building = 'bien';
    public $t_cell = 'tesorus_cell';
    public $t_company = 'co_company';
    public $t_co_deposit = 'co_deposit';
    public $t_contact = 'contact';
    public $t_date = 'en_dates';
    public $t_dw_deposit = 're_deposit';
    public $t_demande = 'demande';
    public $t_demande_carac = 'demande_caracteristique';
    public $t_demande_email = 'email_outlook_lien';
    public $t_demande_file = 'document_upload_lien';
    public $t_email = 'email_outlook';
    public $t_enquete = 'en_enquete';
    public $t_file = 'document_upload';
    public $t_mail_template = 'mt_template';
    public $t_mail_variable = 'mt_variable';
    public $t_bien_contact_demande_profil = 'personne_bien';
    public $t_profil = 'contact_profil';
    public $t_question = 'en_question';
    public $t_reminder = 'reminder';
    public $t_road_accomp = 'tesorus_road_accomp';
    public $t_road_demande = 'tesorus_road_demande';
    public $t_road_eco_impact = 'tesorus_road_eco_impact';
    public $t_road_them = 'tesorus_road_them';
    public $t_road_work = 'tesorus_road_work';
    public $t_user = 'user_accounts';

    public $t_list_accomp_type = 'liste_type_accompagnement';
    public $t_list_answer_status = 'en_liste_statut_answer';
    public $t_list_company_status = 'co_list_status';
    public $t_list_contact_type = 'co_list_contact_type';
    public $t_list_contact_schedule = 'co_list_contact_schedule';
    public $t_list_demande_type = 'liste_demande_type';
    public $t_list_gender = 'liste_civilite';
    public $t_list_juridic_form = 'co_list_juridic_form';
    public $t_list_lang = 'liste_langue';
    public $t_list_module = 'mt_list_module';
    public $t_list_moyen_contact = 'liste_moyen_contact ';
    public $t_list_origin = 'liste_origine_contact';
    public $t_list_question_type = 'liste_questions_type';

    public $t_fe_translation = 'fe_translation';

    public $t_account_user = 'user_accounts';
    public $t_avatar_settings  = 'user_avatar_settings';
    // public $t_component = 'ban_components';
    // public $t_contact_deposit = 'contact_deposit';
    // public $t_contact_profil = 'contact_profil';
    public $t_contact_user = 'user_contacts';
    public $t_entity = 'ban_entities_params';
  
    public $t_field = 'ban_fields';   
    
    public $t_import = 'ban_import';
    // public $t_l_city = 'crm_list_city';
    // public $t_l_contact_type = 'liste_type_personne';
    public $t_l_country = 'list_country';
    public $t_l_gender = 'liste_civilite';
   
    // public $t_l_lang = 'liste_langue';

    public $t_l_user_role = 'list_role';
    // public $t_mt_template = 'mt_template';
    public $t_mt_variable = 'mt_variable';

    public $t_profil_user = 'user_profiles';
}