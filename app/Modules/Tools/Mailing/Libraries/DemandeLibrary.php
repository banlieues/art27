<?php 

namespace Mailing\Libraries;

use Base\Libraries\BaseLibrary;
use Components\Libraries\FormLibrary;
use Components\Libraries\ListLibrary;
use Components\Libraries\ReminderLibrary;
use Enquete\Models\EnqueteModel;
use Mail\Libraries\MailLibrary;
use Mailing\Models\MailingModel;
use Mailing\Models\TemplateModel;

class DemandeLibrary extends BaseLibrary
{
    public function __construct()
    {   
        parent::__construct(__NAMESPACE__);

        // $this->json_library = new JsonLibrary(__NAMESPACE__);
        // $this->form_library = new FormLibrary(__NAMESPACE__);
        $this->ListLibrary = new ListLibrary(__NAMESPACE__);
        
        $this->MailingModel = new MailingModel();
        $this->MailLibrary = new MailLibrary();
        $this->ReminderLibrary = new ReminderLibrary();
        $this->TemplateModel = new TemplateModel();
    }

    public function EmailsGetByDemandeOutMessage($id_demande, $id_message)
    {
        return $this->MailingModel->EmailsGetByDemandeOutMessage($id_demande, $id_message);
    }

    public function id_request_type_get_by_demande($id_demande)
    {
        $request_type = $this->get_request_type_by_id_demande($id_demande);
        return $request_type->id_road;
    }
    public function get_request_type_by_id_demande($id_demande)
    {
        $accomp = $this->db
            ->table($this->t_demande_carac)
            ->where($this->t_demande_carac . '.id_demande', $id_demande)
            ->get()->getRow();
        $demande = $this->db
            ->table($this->t_demande)
            ->where('id_demande', $id_demande)
            ->get()->getRow();
                    
        if(!empty($accomp->id_type_accompagnement)) :
            $request_old = $this->db
                ->table($this->t_list_accomp_type)
                ->where('id', $accomp->id_type_accompagnement)
                ->get()->getRow();
        elseif(!empty($demande->id_type_demande)) :
            $request_old = $this->db
                ->table($this->t_list_demande_type)
                ->where('id', $demande->id_type_demande)
                ->get()->getRow();
        endif;

        $RoadModel = new \Tesorus\Models\RoadModel('demande');
        $roads = $RoadModel->get_roads_active_flat();

        foreach($roads as $road) :
            if(remove_accents(mb_strtolower($road->label_fr))==remove_accents(mb_strtolower($request_old->label))) return $road;
        endforeach;
    }

    // public function get_request_type_by_id_demande($id_demande)
    // {
    //     $accomps = $this->db->table('demande_caracteristique')->where('demande_caracteristique.id_demande', $id_demande)->get()->getResult();
    //     $id_type_accompagnement = !empty($accomps) ? $accomps[0]->id_type_accompagnement : null;

    //     $demandes = $this->db->table('demande')->where('id_demande', $id_demande)->get()->getResult();
    //     $demande = $demandes[0];
    //     $id_type_demande = !empty($demande) ? $demande->id_type_demande : null;

    //     if(empty($id_type_accompagnement) && empty($id_type_demande)) :
    //         return false;
    //     elseif(!empty($id_type_accompagnement)) :
    //         $request_old = $this->db->table('liste_type_accompagnement')->where('id', $id_type_accompagnement)->get()->getResult()[0];
    //     elseif(!empty($id_type_demande)) :
    //         $request_old = $this->db->table('liste_demande_type')->where('id', $id_type_demande)->get()->getResult()[0];
    //     endif;

    //     $RoadModel = new \Tesorus\Models\RoadModel('demande');
    //     $roads = $RoadModel->get_roads_active_flat();

    //     foreach($roads as $road) :
    //         if(
    //             (!empty($request_old) && $road->label_fr==$request_old->label) ||
    //             (empty($request_old) && $road->label_fr=='Information/conseil')
    //         ) return $road;
    //     endforeach;
    // }

    public function DemandeGet($id_demande)
    {
        return $this->MailingModel->DemandeGet($id_demande);
    }

    public function EmailSendGetParamsByDemande($template_ref, $id_demande)
    {
        $demande = $this->DemandeGet($id_demande);
        if($template_ref=='demande_close') :
            $EnqueteModel = new EnqueteModel();
            $enquete = $EnqueteModel->EnqueteGetByDemande($id_demande);
            $demande->path_fr = $enquete->path_fr;
            $demande->path_nl = $enquete->path_nl;
            $demande->id_request_type = $this->id_request_type_get_by_demande($demande->id_demande);
        endif;

        $profils = $this->MailingModel->ProfilsGetByDemande($id_demande);

        $params = [];
        foreach($profils as $profil) :
            $params[] = $this->EmailSendGetParamsByDemandeByProfil($template_ref, $demande, $profil);
        endforeach;

        return $params;
    }

    public function EmailSendConvertTemplateByLang($template, $lang=null)
    {
        $langs = $this->get_languages();
        $lang_array = [];
        foreach($langs as $l) $lang_array[] = mb_strtolower($l->label_fr);

        $mail = (object) [];
        $mail->subject = isset($template->subject_ref) ? $template->subject_ref . ' ' : '';
        if(isset($lang) && in_array($lang, $lang_array)) :
            $mail->subject .= $template->{'subject_' . $lang};
            $mail->message = $template->{'hello_' . $lang} . '<br><br>' . $template->{'content_' . $lang} . '<br><br>' . $template->{'greetings_' . $lang};
        else :
            shuffle($lang_array);
            $mail->message = '';
            $i=1;
            foreach ($lang_array as $l):
                $mail->subject .= $template->{'subject_' . $l};
                $mail->message .= $template->{'hello_' . $l} . '<br><br>' . $template->{'content_' . $l} . '<br><br>' . $template->{'greetings_' . $l};
                if($i<count($lang_array)):
                    $mail->subject .= ' / ';
                    $mail->message .= '<br><hr><br>';
                endif;
                $i++;
            endforeach;
        endif;

        return $mail;
    }

    public function EmailSendGetParamsByProfil($profil)
    {
        $param = (object) [];
        $param->id_contact = $profil->id_contact;
        $param->id_contact_profil = $profil->id_contact_profil;
        $param->fullname = fullname($profil->prenom_contact, mb_strtoupper($profil->nom_contact));
        $param->email = $profil->email;
        $param->lang = mb_strtolower($profil->langue);

        return $param;
    }
    
    private function EmailSendGetParamToken($param)
    {
        do{ //creation d'unique token
            $token = $this->ReminderLibrary->TokenSet(30);
            $tokens = $this->db->table($this->t_reminder)->like('token_reminder', $token)->countAllResults();
        } while($tokens>0);

        $param->token = $token;
        $param->url = "demande/fiche/$param->id_demande";
        $param->duree_token = 24*5; // 5 jours exprimé 
        $param->id_reminder = $this->ReminderLibrary->TokenInsert($param);

        return $param;
    }

    public function EmailSendGetParamsByDemandeByProfil($emodel_ref, $demande, $profil)
    {
        $param_profil = $this->EmailSendGetParamsByProfil($profil);
        $param = object_merge($demande, $param_profil);

        switch($emodel_ref):
            case 'demande_close' :
                $param->token = $this->ReminderLibrary->TokenSet(25);
                $param->recipient = $param_profil->email;
            break;
            case 'update_assign' :
            case 'assign' :
                $param->recipient = $demande->assign_mail;
                $param->id_user = $demande->assign_id;
                $param = $this->EmailSendGetParamToken($param);
            break;
            case 'update_suiveur' :
                if($demande->suiveur_mail > 0 && !is_null($demande->suiveur_mail) && $demande->suiveur_mail != ''):
                    $param->recipient = $demande->suiveur_mail;
                    $param->id_user = $demande->suiveur_id;
                else:
                    $param->recipient = $demande->suiveur_mail_default;
                    $param->id_user = $demande->suiveur_id_default;
                endif;
                $param = $this->EmailSendGetParamToken($param);
            break;
            default :
                $param->recipient = $param_profil->email;
            break;
        endswitch;

        $param->tags = $this->EmailSendGetTagsDemande($emodel_ref, $demande, $profil, $param);

        return $param;
    }
    
    public function TagsGetByUserSession()
    {
        $user = sessionUser();

        $tags = (object) [];

        if(isset($user->username))
        {
            $tags->utilisateur_qui_assign = $user->username;

        }
        else
        {
            $tags->utilisateur_qui_assign = 0;
        }

        return $tags;
    }
    
    public function TagsGetByDemande($demande)
    {
        $tags = (object) [];
        $tags->numero_demande = $demande->id_demande;
        $tags->utilisateur_en_charge = $demande->name_assign;
        $tags->demande_numero = $tags->numero_demande;
        $tags->type_demande_fr = !empty($demande->path_fr) ? strip_tags($demande->path_fr) : '';
        $tags->type_demande = $tags->type_demande_fr;
        $tags->type_demande_nl = !empty($demande->path_nl) ? strip_tags($demande->path_nl) : '';
        $tags->demande_sujet = !empty($demande->subject_demande) ? strip_tags($demande->subject_demande) : '(vide)';
        $tags->sujet_demande = $tags->demande_sujet;

        return $tags;
    }

    private function TagsGetByProfil($profil)
    {
        $tags = (object) [];
        $tags->titre = $profil->civilite_fr;
        $tags->titre_nl = $profil->civilite_nl;
        $tags->prenom = $profil->prenom_contact;
        $tags->nom = $profil->nom_contact;
        $tags->prenom_nom = fullname($profil->prenom_contact, $profil->nom_contact);
        $tags->numero_telephone = $profil->telephone;

        return $tags;
    }

    private function EmailSendGetLink($tagname, $param)
    {
        switch($tagname) :
            case 'enquete_link_fr' :
                return '
                    <a href="' . base_url("enquete/external/form/$param->token/$param->id_demande/$param->id_contact_profil/fr") . '">
                        - Lien vers l\'enquête de satisfaction - 
                    </a>
                ';
            break;
            case 'enquete_link_nl' :
                return '
                    <a href="' . base_url("enquete/external/form/$param->token/$param->id_demande/$param->id_contact_profil/nl") . '"> 
                        - Link naar de tevredenheidsenquête - 
                    </a>
                ';
            break;
            case 'lien_page' :
                return '
                    <a href="' . base_url("reminder/access/$param->token/$param->id_user") . '">
                        -> Lien <- 
                    </a>
                ';
            break;
        endswitch;
    }

    private function EmailSendGetTagsDemande($emodel_ref, $demande, $profil, $param)
    {
        $tags_user = $this->TagsGetByUserSession();
        $tags_demande = $this->TagsGetByDemande($demande);
        $tags_profil = $this->TagsGetByProfil($profil);
        $tags = object_merge($tags_user, object_merge($tags_demande, $tags_profil));

        switch($emodel_ref):
            case 'demande_close' :
                $tags->enquete_link_fr = $this->EmailSendGetLink('enquete_link_fr', $param);
                $tags->enquete_link_nl = $this->EmailSendGetLink('enquete_link_nl', $param);
            break;
            case 'assign' :
            case 'update_assign' :
            case 'update_suiveur' :
                $tags->lien_page = $this->EmailSendGetLink('lien_page', $param);
            break;
        endswitch;

        return $tags;
    }
}
    