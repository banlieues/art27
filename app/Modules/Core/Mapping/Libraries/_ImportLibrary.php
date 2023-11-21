<?php

namespace Mapping\Libraries;

use Base\Libraries\BaseLibrary;
use CodeIgniter\Database\RawSql;

class ImportLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->forge = \Config\Database::forge();

        $this->t_component_bien = 'ban_components_bien';
        $this->t_component_demande = 'ban_components_demande';
    }

    public function HomegradeToH4()
    {
        $this->HomegradeToH4TableBienGeocode();
        $this->HomegradeToH4TableComponentBien();
        $this->HomegradeToH4TableComponentDemande();
    }

    private function HomegradeToH4TableComponentDemande()
    {
        $component_map = $this->db->table($this->t_component_demande)->where('type', 'google_map')->get()->getRow();

        if($component_map) return null;

        $this->db->table($this->t_component_demande)->set('rank', 2)->where('column', 1)->where('rank', 1)->update();
        dbdebug();

        $data = (object) [];
        $data->type = 'google_map';
        $data->fields = '-';
        $data->title = 'Localisation';
        $data->column = 1;
        $data->rank = 1;
        $data->is_search_contact = 0;
        $data->categorie_profil_contact = 0;
        $data->is_link_externe = 0;
        $data->is_multiple = 0;
// debugd(database_encode($this->t_component_bien, $data));
        $this->db->table($this->t_component_demande)->set(database_encode($this->t_component_demande, $data))->insert();
        dbdebug();
    }

    private function HomegradeToH4TableBienGeocode()
    {
        if($this->db->tableExists($this->t_bien_geocode)) return false;
        
        $fields = [
            'id_bien_geocode' => [ 'type' => 'int', 'auto_increment' => true, ],
            'id_bien' => [ 'type' => 'int', ],
            'lat' => [ 'type' => 'float', ],
            'lon' => [ 'type' => 'float', ],
            'updated_at' => [ 'type' => 'timestamp', 'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
            'created_at' => [ 'type' => 'timestamp', 'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), ],
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id_bien_geocode', 'id_bien_geocode');
        $this->forge->createTable($this->t_bien_geocode);
        dbdebug();
    }

    private function HomegradeToH4TableComponentBien()
    {
        $component_map = $this->db->table($this->t_component_bien)->where('type', 'google_map')->get()->getRow();

        if($component_map) return null;

        $this->db->table($this->t_component_bien)->set('rank', 2)->where('column', 2)->where('rank', 1)->update();
        dbdebug();

        $data = (object) [];
        $data->type = 'google_map';
        $data->fields = '-';
        $data->title = 'Localisation';
        $data->column = 2;
        $data->rank = 1;
        $data->is_search_contact = 0;
        $data->categorie_profil_contact = 0;
        $data->is_link_externe = 0;
        $data->is_multiple = 0;
// debugd(database_encode($this->t_component_bien, $data));
        $this->db->table($this->t_component_bien)->set(database_encode($this->t_component_bien, $data))->insert();
        dbdebug();
    }
}