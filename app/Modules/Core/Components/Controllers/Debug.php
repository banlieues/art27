<?php

namespace Components\Controllers;

use Base\Controllers\BaseController;
use CodeIgniter\Database\RawSql;
use Enquete\Libraries\EnqueteLibrary;

class Debug extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        // only for Jeremy and Tamo
        if(!in_array(session('loggedUserId'), [2, 117])) debugd("Sorry, vous n'avez pas les autorisations pour exécuter ce script. Il faut les pouvoirs de Jeremy ou Tamo. HAHAHAHAHA...");
    }

    public function Enquete()
    {
        // $q = $this->db->table($this->t_answer);
        // $q->distinct();
        // $q->whereIn("id_statut_answer", [1, 2]);
        // $q->where("date_envoi between '2023-08-28 00:00:00' and '2023-09-11 00:00:00'");
        // $answers = $q->get()->getResult();

        // $ids_demande = array_values(array_unique(array_filter(array_column($answers, 'id_demande'))));
        
        // $EnqueteLibrary = new EnqueteLibrary();
        // foreach($ids_demande as $id_demande) :
        //     $EnqueteLibrary->DemandeClose($id_demande);
        //     dbdebug();
        // endforeach;
    }

    public function Demande()
    {
        // return false;
        // $users = $this->db->table('user_accounts')->get()->getResult();
        // $users_nom = array_column($users, 'nom');

        // $demandes = $this->db->table("demande d")
        //     ->select('d.id_demande, d.nom as demande_nom, u.nom as user_nom, h.*')
        //     ->join("user_accounts u", "u.id = d.id_user", 'left')
        //     ->join("demande_h h", "h.key_primary = d.id_demande", 'left')
        //     ->where('h.date_modification > ', '2023-09-01 00:00:00')
        //     ->where('h.index', 'demande_nom')
        //     ->whereIn('d.nom', $users_nom)
        //     ->whereNotIn('h.value_old', $users_nom)
        //     ->where('h.value_new !=', '')
        //     ->where('h.value_old !=', '')
        //     ->orderBy('d.id_demande')
        //     ->get()->getResult();
        // debugd($demandes);
        // foreach($demandes as $demande) :
        //     // $this->db->table('demande')->set('nom', $demande->value_old)->where('id_demande', $demande->id_demande)->update();
        //     // dbdebug();
        // endforeach;
        // // die;
    }

    public function DemandeHistorique()
    {
        // return false;
        // $users = $this->db->table('user_accounts')->get()->getResult();
        // $users_nom = array_column($users, 'nom');

        // $historiques = $this->db->table("demande_h h")
        //     ->select("d.id_demande, d.nom as demande_nom, u.nom, h.date_modification, h.id, h.index, h.value_old, h.value_new")
        //     ->join('demande d', "d.id_demande = h.key_primary", 'left')
        //     ->join('user_accounts u', "u.id = d.id_user", 'left')
        //     ->where('h.index', 'demande_nom')
        //     ->groupStart()
        //         ->where(new RawSql('binary d.nom = h.value_old'))
        //         ->orWhereIn('h.value_new', ['', 'Ahmed'])
        //         ->orWhereIn('h.value_new', $users_nom)
        //     ->groupEnd()
        //     ->orderBy('d.id_demande')
        //     ->get()->getResult();
        // // dbdebug();
        // debugd($historiques);
        // foreach($historiques as $historique) :
        //     // $this->db->table('demande_h')->where('id', $historique->id)->delete();
        //     // dbdebug();
        // endforeach;
        // // die;
    }

    // private function EmailQuerySubject()
    // {
    //     $q = $this->db->table("email_outlook e");
    //     $q->resetQuery();
    //     $q->select("e.id_primary, e.sender_mail, e.send_name, e.subject, e.isEmailAuto, e.mail_template, substring(e.body_content, 1, 10) as body, e.to_mail, e.isEmailAuto, e.created_datetime");
    //     $q->where('e.isEmailAuto', 1);
    //     $q->where('e.sender_mail', 'info@homegrade.brussels');
    //     $q->where('e.mail_template is null');

    //     return $q;
    // }

    // public function Email()
    // {
    //     $html = '<tbody><tr style="height:57.0pt"><td width="52" valign="top" style="width:39.2pt; border:none; border-right:solid #595959 1.0pt; padding:0cm 5.4pt 0cm 5.4pt; height:57.0pt"><p class="MsoNormal"><span style="font-size:10.0pt"><img width="100%" height="83" id="Image_x0020_10" src="https://crm.homegrade.banlieues.be/./demandes/documents/380272_image001.png" alt="BXL_logo_vertical_FILET_FR_NL_72" style="width:.4416iii; height:.8666iii"></span><span style="font-size:10.0pt"></span></p></td><td width="646" valign="top" style="width:484.45pt; border:none; padding:0cm 5.4pt 0cm 5.4pt; height:57.0pt"><p class="MsoNormal" style="text-autospace:none"><span style="font-size:9.0pt; font-family:&quot;Arial Black&quot;,sans-serif; color:black; text-transform:uppercase; letter-spacing:.1pt">Anne Capel</span></p><p class="MsoNormal" style="text-autospace:none"><i><span style="font-size:8.0pt; font-family:&quot;Times New Roman&quot;,serif; color:#595959; letter-spacing:.1pt">Urbaniste</span></i><i><span style="font-size:8.0pt; font-family:&quot;Times New Roman&quot;,serif; color:#595959; letter-spacing:.1pt"> - T. +32 (0)2 279 31 46 </span></i><i><span style="font-size:8.0pt; font-family:&quot;Times New Roman&quot;,serif; color:#595959">– anne.capel</span></i><span style="font-size:10.0pt; color:#595959"><a href="mailto:xxxxxx@brucity.be"><i><span style="font-size:8.0pt; font-family:&quot;Times New Roman&quot;,serif; color:#595959; letter-spacing:.1pt; text-decoration:none">@brucity.be</span></i></a></span><u><span style="font-size:8.0pt; font-family:&quot;Arial&quot;,sans-serif; color:#595959; letter-spacing:.1pt"> </span></u><u><span style="font-size:8.0pt; font-family:&quot;Arial&quot;,sans-serif; letter-spacing:.1pt"><br></span></u><span style="font-size:8.0pt; font-family:&quot;Arial Black&quot;,sans-serif; color:black; letter-spacing:.1pt">VILLE DE BRUXELLES </span><span style="font-size:8.0pt; font-family:&quot;Arial Black&quot;,sans-serif; color:black; letter-spacing:.1pt">• </span><span style="font-size:8.0pt; font-family:&quot;Arial Black&quot;,sans-serif; color:black; letter-spacing:.1pt">STAD BRUSSEL</span><span style="font-size:8.0pt; font-family:&quot;Arial Black&quot;,sans-serif; letter-spacing:.1pt"><br></span><i><span style="font-size:8.0pt; font-family:&quot;Times New Roman&quot;,serif; color:#595959; letter-spacing:.1pt">Département Développement urbain </span></i><i><span style="font-size:8.0pt; font-family:&quot;Times New Roman&quot;,serif; color:#595959">• Departement Stadsontwikkeling</span></i></p><p class="MsoNormal" style="text-autospace:none"><i><span style="font-size:8.0pt; font-family:&quot;Times New Roman&quot;,serif; color:#595959; letter-spacing:.1pt">Direction Permis et Renseignements urbanistiques </span></i><i><span style="font-size:8.0pt; font-family:&quot;Times New Roman&quot;,serif; color:#595959">•</span></i></p><p class="MsoNormal" style="text-autospace:none"><i><span style="font-size:8.0pt; font-family:&quot;Times New Roman&quot;,serif; color:#595959">Directie Vergunningen en Stedenbouwkundige inlichtingen</span></i></p><p class="MsoNormal" style="text-autospace:none"><i><span style="font-size:8.0pt; font-family:&quot;Times New Roman&quot;,serif; color:#595959; letter-spacing:.1pt">Rue des Halles 4, 1000 Bruxelles </span></i><i><span style="font-size:8.0pt; font-family:&quot;Times New Roman&quot;,serif; color:#595959">• Hallenstraat 4, 1000 Brussel</span></i><i><span style="font-size:8.0pt; font-family:&quot;Times New Roman&quot;,serif; color:#595959; letter-spacing:.1pt"><br></span></i><span style="font-size:10.0pt; color:#595959"><a href="http://www.bruxelles.be/"><u><span style="font-size:8.0pt; font-family:&quot;Arial&quot;,sans-serif; color:#595959">www.bruxelles.be</span></u></a></span><span style="font-size:8.0pt; font-family:&quot;Arial&quot;,sans-serif; color:#595959"> • </span><span style="font-size:10.0pt; color:#595959"><a href="http://www.brussel.be/"><u><span style="font-size:8.0pt; font-family:&quot;Arial&quot;,sans-serif; color:#595959">www.brussel.be</span></u></a></span><span style="font-size:8.0pt; font-family:&quot;Arial&quot;,sans-serif; color:#595959"> • </span><span style="font-size:10.0pt; color:#595959"><a href="http://www.bruxelles.be/6762"><u><span style="font-size:8.0pt; font-family:&quot;Arial&quot;,sans-serif; color:#595959">Suivez-nous sur les réseaux sociaux</span></u></a></span><span style="font-size:8.0pt; font-family:&quot;Arial&quot;,sans-serif; color:#595959"> • </span><span style="font-size:10.0pt; color:#595959"><a href="http://www.brussel.be/6762"><u><span style="font-size:8.0pt; font-family:&quot;Arial&quot;,sans-serif; color:#595959">Volg ons op social media</span></u></a></span><i><span style="font-size:9.0pt; font-family:&quot;Times New Roman&quot;,serif; letter-spacing:.1pt"></span></i></p></td></tr></tbody>
    //     ';

    //     $html = preg_replace("/(min-|max-)?height\s?:\s?[a-zA-Z0-9_]*.?[a-zA-Z0-9_]*%?(\s|;|\")/", '${1}height:auto${2}', $html);
    //     $html = preg_replace("/(min-|max-)?width\s?:\s?[a-zA-Z0-9_]*.?[a-zA-Z0-9_]*%?(\s|;|\")/", '${1}width:auto${2}', $html);
    //     $html = preg_replace("/height\s?=\s?\".[^\"]*\"/", '', $html);
    //     $html = preg_replace("/width\s?=\s?\".[^\"]*\"/", '', $html);
    //     debugd($html);
    // }

    // public function Email()
    // {
    //     $emails = $this->db->table('email_outlook')->where('body_content REGEXP "width:[0-9]+000px"')->countAllResults();
    //     debug('count "width:[0-9]+000px" : ' . $emails);
    //     if($emails>0) :
    //         $this->db->table('email_outlook')
    //             ->where('body_content REGEXP "width:[0-9]+000px"')
    //             ->set('body_content', new RawSql('REGEXP_REPLACE(body_content, "width:([0-9]+)000px", "width:\\\1000zzz")'))
    //             ->set('body_preview', new RawSql('REGEXP_REPLACE(body_content, "width:([0-9]+)000px", "width:\\\1000zzz")'))
    //             ->update();
    //         dbdebug();
    //     endif;

    //     $emails = $this->db->table('email_outlook')->where('body_content REGEXP "width:[0-9]*\.{0,1}[0-9]*in[^i|h]"')->countAllResults();
    //     debug('count "width:[0-9]*\.{0,1}[0-9]*in[^i|h]" : ' . $emails);
    //     if($emails>0) :
    //         $this->db->table('email_outlook e')
    //             ->where('e.body_content REGEXP "width:[0-9]*\.{0,1}[0-9]*in[^i]"')
    //             ->set('e.body_content', new RawSql('REGEXP_REPLACE(e.body_content, "width:([0-9]*\.{0,1}[0-9]*)in([^i|h])", "width:\\\1iii\\\2")'))
    //             ->set('e.body_preview', new RawSql('REGEXP_REPLACE(e.body_preview, "width:([0-9]*\.{0,1}[0-9]*)in([^i|h])", "width:\\\1iii\\\2")'))
    //             ->update();
    //         dbdebug();
    //         $emails = $this->db->table('email_outlook')->where('body_content REGEXP "width:[0-9]*\.{0,1}[0-9]*iii"')->countAllResults();
    //         debug('count "width:[0-9]*\.{0,1}[0-9]*iii" : ' . $emails);
    //     endif;

    //     $emails = $this->db->table('email_outlook')->where('body_content REGEXP "height:[0-9]*\.{0,1}[0-9]*in[^i|h]"')->countAllResults();
    //     debug('count "height:[0-9]*\.{0,1}[0-9]*in[^i|h]" : ' . $emails);
    //     if($emails>0) :
    //         $this->db->table('email_outlook e')
    //             ->where('e.body_content REGEXP "height:[0-9]*\.{0,1}[0-9]*in[^i|h]"')
    //             ->set('e.body_content', new RawSql('REGEXP_REPLACE(e.body_content, "height:([0-9]*\.{0,1}[0-9]*)in([^i|h])", "height:\\\1iii\\\2")'))
    //             ->set('e.body_preview', new RawSql('REGEXP_REPLACE(e.body_preview, "height:([0-9]*\.{0,1}[0-9]*)in([^i|h])", "height:\\\1iii\\\2")'))
    //             ->update();
    //         dbdebug();
    //         $emails = $this->db->table('email_outlook')->where('body_content REGEXP "height:[0-9]*\.{0,1}[0-9]*iii"')->countAllResults();
    //         debug('count "height:[0-9]*\.{0,1}[0-9]*iii" : ' . $emails);
    //     endif;
    // }

    public function Email()
    {
        // $q = $this->db->table("email_outlook e");
        // $q->resetQuery();
        // $q->select("e.id_primary, e.sender_mail, e.send_name, e.subject, e.isEmailAuto, e.mail_template, substring(e.body_content, 1, 10) as body, e.to_mail, e.isEmailAuto, e.created_datetime");
        // $q->groupStart();
        //     $q->where('e.isEmailAuto', 0);
        //     $q->orWhere('e.isEmailAuto is null');
        // $q->groupEnd();
        // $q->groupStart();
        //     $q->like('e.sender_mail', 'info@homegrade.brussels');
        //     $q->orLike('e.sender_mail', 'contact.crm@homegrade.brussels');
        // $q->groupEnd();
        // $q->where('e.mail_template is null');
        // $q->like('e.subject', "#Ref:", 'after');
        // $q->like('e.subject', "Ontvangstbevestiging van uw aanvraag", 'before');

        // $emails = $q->get()->getResult();
        // dbdebug();
        // debugd($emails);
        // foreach($emails as $email) :
        //     $this->db->table('email_outlook')->set('isEmailAuto', 1)->set('mail_template', 'confirm_nl')->where('id_primary', $email->id_primary)->update();
        // endforeach;
        // debugd('confirm_nl = ' . count($emails));

    //     $q = $this->EmailQuerySubject();
    //     $q->where('e.subject', "Update d’une Demande");
    //     $q->like('e.body_content', "dont tu es en charge");
    //     $emails = $q->get()->getResult();
    //     // debugd($emails);
    //     foreach($emails as $email) :
    //         $this->db->table('email_outlook')->set('mail_template', 'update_assign')->where('id_primary', $email->id_primary)->update();
    //     endforeach;
    //     debug('update_assign_fr = ' . count($emails));

    //     $q = $this->EmailQuerySubject();
    //     $q->where('e.subject', "Update d’une Demande");
    //     $q->like('e.body_content', "est en charge");
    //     $emails = $q->get()->getResult();
    //     // debugd($emails);
    //     foreach($emails as $email) :
    //         $this->db->table('email_outlook')->set('mail_template', 'update_suiveur')->where('id_primary', $email->id_primary)->update();
    //     endforeach;
    //     debug('update_suiveur_fr = ' . count($emails));

    //     $q = $this->EmailQuerySubject();
    //     $q->where('e.subject', "Updating van een aanvraag");
    //     $q->like('e.body_content', "waarvoor jij verantwoordelijk bent");
    //     $emails = $q->get()->getResult();
    //     // debugd($emails);
    //     foreach($emails as $email) :
    //         $this->db->table('email_outlook')->set('mail_template', 'update_assign')->where('id_primary', $email->id_primary)->update();
    //     endforeach;
    //     debug('update_assign_nl = ' . count($emails));

    //     $q = $this->EmailQuerySubject();
    //     $q->where('e.subject', "Updating van een aanvraag");
    //     $q->like('e.body_content', "verantwoordelijk is");
    //     $emails = $q->get()->getResult();
    //     // debugd($emails);
    //     foreach($emails as $email) :
    //         $this->db->table('email_outlook')->set('mail_template', 'update_suiveur')->where('id_primary', $email->id_primary)->update();
    //     endforeach;
    //     debug('update_suiveur_nl = ' . count($emails));

    //     $q = $this->EmailQuerySubject();
    //     $q->like('e.subject', '#Ref:', 'after');
    //     $q->like('e.subject', "Enquête de satisfaction", 'before');
    //     $emails = $q->get()->getResult();
    //     // debugd($emails);
    //     foreach($emails as $email) :
    //         $this->db->table('email_outlook')->set('mail_template', 'demande_close')->where('id_primary', $email->id_primary)->update();
    //     endforeach;
    //     debug('demande_close_fr = ' . count($emails));

    //     $q = $this->EmailQuerySubject();
    //     $q->like('e.subject', '#Ref:', 'after');
    //     $q->like('e.subject', "Tevredenheidsenquête", 'before');
    //     $emails = $q->get()->getResult();
    //     // debugd($emails);
    //     foreach($emails as $email) :
    //         $this->db->table('email_outlook')->set('mail_template', 'demande_close')->where('id_primary', $email->id_primary)->update();
    //     endforeach;
    //     debug('demande_close_nl = ' . count($emails));

    //     $q = $this->EmailQuerySubject();
    //     $q->like('e.subject', '#Ref:', 'after');
    //     $q->like('e.subject', "Assignation d’une demande", 'before');
    //     $emails = $q->get()->getResult();
    //     // debugd($emails);
    //     foreach($emails as $email) :
    //         $this->db->table('email_outlook')->set('mail_template', 'assign')->where('id_primary', $email->id_primary)->update();
    //     endforeach;
    //     debug('assign_fr = ' . count($emails));

    //     $q = $this->EmailQuerySubject();
    //     $q->like('e.subject', '#Ref:', 'after');
    //     $q->like('e.subject', "Toewijzing van een aanvraag", 'before');
    //     $emails = $q->get()->getResult();
    //     // debugd($emails);
    //     foreach($emails as $email) :
    //         $this->db->table('email_outlook')->set('mail_template', 'assign')->where('id_primary', $email->id_primary)->update();
    //     endforeach;
    //     debug('assign_nl = ' . count($emails));

    //     $q = $this->EmailQuerySubject();
    //     $q->like('e.subject', '#Ref:', 'after');
    //     $q->like('e.subject', "Accusé de réception Demande", 'before');
    //     $emails = $q->get()->getResult();
    //     // debugd($emails);
    //     foreach($emails as $email) :
    //         $this->db->table('email_outlook')->set('mail_template', 'confirm')->where('id_primary', $email->id_primary)->update();
    //     endforeach;
    //     debug('confirm_fr = ' . count($emails));

    //     $q = $this->EmailQuerySubject();
    //     $q->like('e.subject', '#Ref:', 'after');
    //     $q->like('e.subject', "Ontvangstbevestiging van uw aanvraag", 'before');
    //     $emails = $q->get()->getResult();
    //     // debugd($emails);
    //     foreach($emails as $email) :
    //         $this->db->table('email_outlook')->set('mail_template', 'confirm')->where('id_primary', $email->id_primary)->update();
    //     endforeach;
    //     debug('confirm_nl = ' . count($emails));

    //     die;
    }

}