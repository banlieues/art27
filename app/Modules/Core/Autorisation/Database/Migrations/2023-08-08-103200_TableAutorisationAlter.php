<?php

namespace Autorisation\Database\Migrations;

use Base\Database\BaseMigration;
use Components\Libraries\MigrationLibrary;

class TableAutorisationAlter_230808 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableAutorisationRename();
        $this->TableAutorisationAlter();
        $this->TableAutorisationColumnsTinyInt();
        // $this->TableAutorisationAlterDefault0();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }
    
    private function TableAutorisationRename()
    {
        if($this->db->tableExists('users_autorisation')) :
            if($this->db->tableExists($this->t_autorisation)) :
                $this->forge->renameTable($this->t_autorisation, '_' . $this->t_autorisation . '_old');
            endif;
            $this->forge->renameTable('users_autorisation', $this->t_autorisation);
        endif;
    }

    private function TableAutorisationAlter()
    {
        if($this->db->fieldExists('id_user', $this->t_autorisation)) :
            $fields = [
                'id_user' => [ 'type' => 'int', 'null' => false, ],
            ];
            $this->forge->modifyColumn($this->t_autorisation, $fields);
            dbdebug();
        elseif($this->db->fieldExists('user_id', $this->t_autorisation)) :
            $fields = [
                'user_id' => [ 'name' => 'id_user', 'type' => 'int', 'null' => false, ],
            ];
            $this->forge->modifyColumn($this->t_autorisation, $fields);
            dbdebug();           
        endif;
        if(!$this->db->fieldExists('updated_at', $this->t_autorisation)) :
            $fields = [
                'updated_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
            ];
            $this->forge->addColumn($this->t_autorisation, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('updated_by', $this->t_autorisation)) :
            $fields = [
                'updated_by' => [ 'type' => 'int', 'null' => false, ],
            ];
            $this->forge->addColumn($this->t_autorisation, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('created_at', $this->t_autorisation)) :
            $fields = [
                'created_at' => [ 'type' => 'timestamp', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'), ],
            ];
            $this->forge->addColumn($this->t_autorisation, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('created_by', $this->t_autorisation)) :
            $fields = [
                'created_by' => [ 'type' => 'int', 'null' => false, ],
            ];
            $this->forge->addColumn($this->t_autorisation, $fields);
            dbdebug();
        endif;
        if($this->db->fieldExists('cevenement', $this->t_autorisation)) :
            $this->forge->dropColumn($this->t_autorisation, 'cevenement');
            dbdebug();
        endif;
        if($this->db->fieldExists('revenement', $this->t_autorisation)) :
            $this->forge->dropColumn($this->t_autorisation, 'revenement');
            dbdebug();
        endif;
        if($this->db->fieldExists('uevenement', $this->t_autorisation)) :
            $this->forge->dropColumn($this->t_autorisation, 'uevenement');
            dbdebug();
        endif;
        if($this->db->fieldExists('devenement', $this->t_autorisation)) :
            $this->forge->dropColumn($this->t_autorisation, 'devenement');
            dbdebug();
        endif;
    }
    
    private function TableAutorisationColumnsTinyInt()
    {
        $Migration = new MigrationLibrary();

        $this->TableAutorisationAlterColumn('autorisation', 'autorisation_r');
        $this->TableAutorisationAlterColumn('autorisation_modifier', 'autorisation_u');

        $this->TableAutorisationAlterColumn('rbien', 'bien_r');
        $this->TableAutorisationAlterColumn('ubien', 'bien_u');
        $this->TableAutorisationAlterColumn('cbien', 'bien_c');
        $this->TableAutorisationAlterColumn('dbien', 'bien_d');

        $Migration->TableAutorisationAddColumn('calculator_r');
        $Migration->TableAutorisationAddColumn('calculator_u');
        $Migration->TableAutorisationAddColumn('calculator_c');
        $Migration->TableAutorisationAddColumn('calculator_d');
        // access for Stephanie Morlot, Bruno Durant, Lina Jilkova
        $this->db->table($this->t_autorisation)->set('calculator_r', 1)->whereIn('id_user', [65, 116, 134])->update();
        dbdebug();
        $this->db->table($this->t_autorisation)->set('calculator_u', 1)->whereIn('id_user', [65, 116, 134])->update();
        dbdebug();
        $this->db->table($this->t_autorisation)->set('calculator_c', 1)->whereIn('id_user', [65, 116, 134])->update();
        dbdebug();
        $this->db->table($this->t_autorisation)->set('calculator_d', 2)->whereIn('id_user', [65, 116, 134])->update();
        dbdebug();

        $Migration->TableAutorisationAddColumn('company_r');
        $Migration->TableAutorisationAddColumn('company_u');
        $Migration->TableAutorisationAddColumn('company_c');
        $Migration->TableAutorisationAddColumn('company_d');

        $this->TableAutorisationAlterColumn('rpersonne', 'contact_r');
        $this->TableAutorisationAlterColumn('upersonne', 'contact_u');
        $this->TableAutorisationAlterColumn('cpersonne', 'contact_c');
        $this->TableAutorisationAlterColumn('dpersonne', 'contact_d');

        $this->TableAutorisationAlterColumn('admindashboard', 'dashboard_admin');
        $this->TableAutorisationAlterColumn('userdashboard', 'dashboard_user');

        $this->TableAutorisationAlterColumn('rdemande', 'demande_r');
        $this->TableAutorisationAlterColumn('udemande', 'demande_u');
        $this->TableAutorisationAlterColumn('cdemande', 'demande_c');
        $this->TableAutorisationAlterColumn('ddemande', 'demande_d');
        $Migration->TableAutorisationAddColumn('demande_web_c');

        $this->TableAutorisationAlterColumn('rdocument', 'document_r');
        $this->TableAutorisationAlterColumn('udocument', 'document_u');
        $this->TableAutorisationAlterColumn('cdocument', 'document_c');
        $this->TableAutorisationAlterColumn('ddocument', 'document_d');

        $this->TableAutorisationAlterColumn('rdoublon', 'doublon_r');

        $this->TableAutorisationAlterColumn('remail', 'email_r');
        $this->TableAutorisationAlterColumn('uemail', 'email_u');
        $this->TableAutorisationAlterColumn('cemail', 'email_c');
        $this->TableAutorisationAlterColumn('demail', 'email_d');
        $this->TableAutorisationAlterColumn('ointerface', 'email_all_r');

        $this->TableAutorisationAlterColumn('rmodele', 'email_template_r');
        $this->TableAutorisationAlterColumn('umodele', 'email_template_u');
        $this->TableAutorisationAlterColumn('cmodele', 'email_template_c');
        $this->TableAutorisationAlterColumn('dmodele', 'email_template_d');

        $this->TableAutorisationAlterColumn('rencodage', 'encodage_r');
        $this->TableAutorisationAlterColumn('uencodage', 'encodage_u');
        $this->TableAutorisationAlterColumn('cencodage', 'encodage_c');
        $this->TableAutorisationAlterColumn('dencodage', 'encodage_d');

        $this->TableAutorisationAlterColumn('renquete', 'enquete_r');
        $this->TableAutorisationAlterColumn('uenquete', 'enquete_u');
        $this->TableAutorisationAlterColumn('cenquete', 'enquete_c');
        $this->TableAutorisationAlterColumn('denquete', 'enquete_d');
        $this->TableAutorisationAlterColumn('allenquete', 'enquete_all_r');
        $Migration->TableAutorisationAddColumn('enquete_form_r');
        $Migration->TableAutorisationAddColumn('enquete_form_u');
        $Migration->TableAutorisationAddColumn('enquete_form_d');

        $this->TableAutorisationAlterColumn('rformulaire', 'formulaire_r');
        $this->TableAutorisationAlterColumn('uformulaire', 'formulaire_u');
        $this->TableAutorisationAlterColumn('cformulaire', 'formulaire_c');
        $this->TableAutorisationAlterColumn('dformulaire', 'formulaire_d');

        $Migration->TableAutorisationAddColumn('liste_r');
        $Migration->TableAutorisationAddColumn('liste_u');
        $Migration->TableAutorisationAddColumn('liste_c');
        $Migration->TableAutorisationAddColumn('liste_d');

        $this->TableAutorisationAlterColumn('upole', 'pole_u');

        $this->TableAutorisationAlterColumn('rrdv', 'rdv_r');
        $this->TableAutorisationAlterColumn('urdv', 'rdv_u');
        $this->TableAutorisationAlterColumn('crdv', 'rdv_c');
        $this->TableAutorisationAlterColumn('drdv', 'rdv_d');

        $this->TableAutorisationAlterColumn('rrequete', 'requete_r');
        $this->TableAutorisationAlterColumn('urequete', 'requete_u');
        $this->TableAutorisationAlterColumn('crequete', 'requete_c');
        $this->TableAutorisationAlterColumn('drequete', 'requete_d');
        $this->TableAutorisationAlterColumn('uuser_requete', 'requete_user_u');

        $this->TableAutorisationAlterColumn('rtache', 'tache_r');
        $this->TableAutorisationAlterColumn('utache', 'tache_u');
        $this->TableAutorisationAlterColumn('ctache', 'tache_c');
        $this->TableAutorisationAlterColumn('dtache', 'tache_d');

        $Migration->TableAutorisationAddColumn('tesorus_r');
        $Migration->TableAutorisationAddColumn('tesorus_u');
        $Migration->TableAutorisationAddColumn('tesorus_c');
        $Migration->TableAutorisationAddColumn('tesorus_d');

        $Migration->TableAutorisationAddColumn('translator_r');
        $Migration->TableAutorisationAddColumn('translator_u');
        $Migration->TableAutorisationAddColumn('translator_c');
        $Migration->TableAutorisationAddColumn('translator_d');

        $this->TableAutorisationAlterColumn('ruser', 'user_r');
        $this->TableAutorisationAlterColumn('uuser', 'user_u');
        $this->TableAutorisationAlterColumn('cuser', 'user_c');
        $this->TableAutorisationAlterColumn('duser', 'user_d');
    }

    private function TableAutorisationAlterColumn($current_name, $new_name)
    {
        if($this->db->fieldExists($current_name, $this->t_autorisation)) :
            if($this->db->fieldExists($new_name, $this->t_autorisation)) :
                $this->forge->dropColumn($this->t_autorisation, $new_name); dbdebug();
            endif;
            $fields = [
                $current_name => [ 'name' => $new_name, 'type' => 'tinyint', 'default' => 0, ],
            ];
            $this->forge->modifyColumn($this->t_autorisation, $fields); dbdebug();
        else :
            $Migration = new MigrationLibrary();
            $Migration->TableAutorisationAddColumn($new_name);
        endif;
    }
}