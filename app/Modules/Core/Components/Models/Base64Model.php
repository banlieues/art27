<?php

namespace Components\Models;

use CodeIgniter\Model;

class Base64Model extends Model
{
    public function convert_direct_base64($base64,$name_file)
    {

      /*  if(!is_null($id_document))
        {
            $name=date("Ymdhis").'_'.$id_document."_".slugify_name_file($file[0]->name);
        }
        else
        {
            $name=date("Ymdhis").'_xxx_'.slugify_name_file($file[0]->name);
        }*/

        $path="./demandes/documents_test/";
        file_put_contents($path.$name_file, base64_decode($base64), LOCK_EX);

    }


    
    public function clean_base64_from_message($message)
    {
        //Voir s'il y de la base 64
        $has_base_64=$this->has_base64($message);

        if($has_base_64)
        {
           return convert_base64($message);
        }
        else
        {
            return null;
        }
    }


/*
    public function download_base64_document($id_document=0){
        $this->db->select('*');
        $this->db->from('email_demande_depots');
        $this->db->where('id', $id_document);
        $file = $this->db->get()->result();

        $name=$file[0]->name;
        $name = str_replace("e?", "é", $name);
        $name = str_replace("o?", "ô", $name);
        $name = str_replace("u?", "û", $name);
        $name = str_replace("c?", "ç", $name);
        $name = str_replace("a?", "à", $name);
        $name=$id_document."_".$this->slugify_name_file($file[0]->name);
        $path="assets/demandes/documents/";
        file_put_contents($path.$name, base64_decode($file[0]->contentByte), LOCK_EX);

                
               $sql_update="UPDATE email_demande_depots SET url_file='$name' WHERE id=$id_document";

                $this->db->query($sql_update);

                $this->download_document($id_document);
                exit;
                return;

        $file = $this->db->get()->result();




        if(count($file)>0):
                            header("Content-Type: ".$file[0]->contentByte_Type);
                            header('Content-Disposition: attachment; filename="'.$file[0]->name.'"');
                            
                           // header("Content-disposition: attachment; filename=toto.pdf");
                          // ob_clean();
                           //flush();
            echo base64_decode($file[0]->contentByte); 
                            exit;
            //force_download($file[0]->name,base64_decode($file[0]->contentByte));
        else : 
            echo 'fichier non trouvé dans la base de données.';
        endif;
    }
    */



    public function clean_massif_base64($table,$champ,$name_key_primary)
    {
        //Lancer une requête sur table et champ, 
        //On fait un foreach et on execute pour chaque itération $this->clean_base64_from_message
        $builder=$this->db->table($table);
        $builder->select("$champ,$key_primary");
        $messages=$builer->get()->getResult();

        //Ici insérer créer un cham texte dans champ_sql, qui porte comme nom, $champ_provisoire
        $champ_provisoire=$champ."_provisoire";

        if(!empty($messages))
        {
            foreach($messages as $message)
            {
                $message_clean=$this->clean_base64_from_message($message->$champ);

                if(is_null($message))
                {
                    continue;
                }
                else
                {
                    $builder=$this->db->table($table);
                    $data[$champ_provisoire]=$message_clean;
                    $where[$name_key_primary]=$message->$name_key_primary;
                    $builder->update($data,$where);
                }
            }
        }
    }


    public function has_base_64($message)
    {
        //regarde s'il a un base_64, et retourne TRUE ou FALSE
    }


    public function convert_file_to_base64_message($message)
    {
        //code qui repère la zone où se trouve le base64
        

    }
}