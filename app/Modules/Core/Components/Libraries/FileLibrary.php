<?php  

namespace Components\Libraries;

use Base\Libraries\BaseLibrary;
use Components\Config\Globals;
use Components\Models\FileModel;
use CodeIgniter\Files\File;
use Dompdf\Dompdf;

class FileLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        helper(['file', 'form']);

        $this->FileModel = new FileModel();
        $this->pk = get_primary_key($this->t_file);
 
        // $this->load->add_package_path(APPPATH . 'modules/tamo');

        // $module = $this->get_param_by_module($param['module']);
        // $this->t_file = $module->t_file;
    }

    public function HtmlToPdf($html, $filename)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        file_put_contents(PATH_DOCU_DEMANDE . date('ymdHis') . '_' . $filename . '.pdf', $dompdf->output());
        $file = new File(PATH_DOCU_DEMANDE . date('ymdHis') . '_' . $filename . '.pdf');
        $id_file = $this->FileDatabaseInsert($file);

        return $id_file;
    }

    public function FilesUpload($data)
    {
        // for upload fields type file, image
        if(empty($this->request->getFiles())) return $data;

        $files = $this->request->getFiles();
        foreach($files as $key=>$file):
            if(is_object($file) && $file->isValid()) :
                $data->$key = $this->FileUpload($file);
            elseif(is_array($file)) :
                $ids_file = [];
                foreach($file as $f) :
                    if(is_object($f) && $f->isValid()) :
                        $ids_file[] = $this->FileUpload($f);
                    endif;
                endforeach;
                $ids_file = array_values(array_filter($ids_file));
                $upload_files = !empty($ids_file) ? $ids_file : [];
                $record_files = !empty($data->$key) ? $data->$key : [];
                $data->$key = array_merge($record_files, $upload_files);
            endif;
        endforeach;

        return $data;
    }

    public function ImageFileToBase64($id_file)
    {
        $file = $this->FileModel->FileGet($id_file);
        if(empty($file) || !file_exists(PATH_DOCU_DEMANDE . $file->url_file)) return false;

        $ext = !empty($file->ext) ? $file->ext : $file->clientExt;
        $base64 = file_to_base64(PATH_DOCU_DEMANDE . $file->url_file, $ext);
        return $base64;
    }
    
    public function FileUpload($file)
    {
        if(empty($file)) return false;

        $uploaded_file = $this->FileServerUpload($file);
        if(empty($uploaded_file)) return false;

        $id_file = $this->FileDatabaseInsert($uploaded_file);

        return $id_file;
    }

    // private function FileUploadDatabaseHomegrade($file, $uploaded_file)
    // {
    //     $data = (object) [];
    //     // $data->name = $uploaded_file->getBasename();
    //     $data->url_file = $uploaded_file->getBasename();
    //     $data->name = preg_replace('/^\d{12}_/', '', $data->url_file);
    //     $data->contentByte = $uploaded_file->getSize();
    //     $data->contentByte_Type = $uploaded_file->getMimeType();
    //     $data->date_created = date('Y-m-d H:i:s');

    //     $data->baseName = $uploaded_file->getBasename();
    //     $data->realPath = $uploaded_file->getRealPath();
    //     $data->size = $uploaded_file->getSize();
    //     $data->mimeType = $uploaded_file->getMimeType();
    //     $data->ext = $uploaded_file->guessExtension();
    //     $data->clientExt = $file->getClientExtension();
    //     $data->created_by = session('loggedUserId');
    //     $data->created_at = date('Y-m-d H:i:s');

    //     $this->db->table($this->t_file)->set(database_encode($this->t_file, $data))->insert();
    //     $id_file = $this->db->insertID();

    //     return $id_file;
    // }

    public function FileDatabaseInsert($file)
    {
        $data = (object) [];
        $data->name = $file->clientName;
        $data->url_file = $file->getBasename();
        $data->contentByte = $file->getSize();
        $data->contentByte_Type = $file->getMimeType();
        $data->commentaire = 'Téléchargé depuis le dépôt web.';
        $data->date_echeance = '000-00-00 00:00:00';
        $data->id_type = 0;
        // $data->ext = $uploaded_file->getClientExtension();
        // $data->clientExt = $file->guessExtension();
        // $data->created_by = session('loggedUserId');
        // $data->created_at = date('Y-m-d H:i:s');
        $data->date_created = !empty($file->date_created) ? $file->date_created : date('Y-m-d H:i:s');
        $data->id_user = !empty($file->id_user) ? $file->id_user : session('loggedUserId') ;
        $data->id_demande = !empty($file->id_demande) ? $file->id_demande : null;

        $this->db->table($this->t_file)->set(database_encode($this->t_file, $data))->insert();
        // dbdebug();
        $id_file = $this->db->insertID();

        if(!empty($file->id_demande)) :
            $data = (object) [];
            $data->id_demande = $file->id_demande;
            $data->id_document = $id_file;
            $data->id_message = $file->id_message ?? 0;
            $data->id_user = session('loggedUserId');
            $this->db->table($this->t_demande_file)->set(database_encode($this->t_demande_file, $data))->insert();
        endif;

        return $id_file;
    }

    public function FileServerMove($file)
    {
        $newName = preg_match('/^[0-9]{12}_/', $file->getBasename()) ? $file->getBasename() : date('ymdHis') . '_' . str_replace('+', '_', urlencode($file->getBasename()));
        $file->move(PATH_DOCU_DEMANDE, $newName);

        $moved_file = new File(PATH_DOCU_DEMANDE . $newName);
        $moved_file->clientName = $file->clientName;
        $moved_file->date_created = $file->date_created ?? date('Y-m-d H:i:s');
        $moved_file->id_user = $file->date_created ?? session('loggedUserId');

        return $moved_file;
    }

    public function FileServerUpload($uploadFile)
    {
        if(!$uploadFile->isValid() || $uploadFile->hasMoved()) return false;
        
        $newName = date('ymdHis') . '_' . str_replace('+', '_', urlencode($uploadFile->getClientName()));
        $uploadFile->move(PATH_DOCU_DEMANDE, $newName);

        $newFile = new File(PATH_DOCU_DEMANDE . $newName);
        $newFile->clientName = $uploadFile->getClientName();

        return $newFile;
    }

    public function FileGet($id_file)
    {
        return $this->FileModel->FileGet($id_file);
    }

    public function FilesGet($ids_file)
    {
        return $this->FileModel->FilesGet($ids_file);
    }

    public function DocsGet($ids_file)
    {
        return $this->FileModel->DocsGet($ids_file);
    }

    public function ImgsGet($ids_file)
    {
        return $this->FileModel->ImgsGet($ids_file);
    }

    // public function FileInsert($post)
    // {
    //     $post->created_by = session('loggedUserId');
    //     $post->updated_by = session('loggedUserId');
    //     $post = database_encode($this->t_file, $post);
    //     $this->db->table($this->t_file)->set($post)->insert();
    //     $id_file = $this->db->getInsertID();

    //     return $id_file;
    // }

    public function FileDeleteServer($id_file)
    {
        $file = $this->db->table($this->t_file)->where($this->pk, $id_file)->get()->getRow();
        if(empty($file)) return false;

        $filepath = PATH_DOCU_DEMANDE . $file->url_file;
        if(!file_exists($filepath)) return false;

        unlink($filepath);
        return true;
    }

    private function FileDeleteDatabase($id_file)
    {
        $this->db->table($this->t_file)->where($this->pk, $id_file)->delete();
    }

    // public function fileInsert($file)
    // {
    //     if(empty($file)) return false;

    //     $data = (object) [];
    //     $data->baseName = $file->getBasename();
    //     $data->realPath = $file->getRealPath();
    //     $data->size = $file->getSize();
    //     $data->mimeType = $file->getMimeType();
    //     $data->ext = $file->guessExtension();
    //     $data->created_id_user = session('loggedUserId');
    //     $data = database_encode($this->t_attach, $data);

    //     $this->db->table($this->t_attach)->insert($data);
    //     $id_attach = $this->db->insertID();

    //     return $id_attach;
    // }

    // public function upload($uploadFile, $destinationFolder)
    // {
    //     if(!$uploadFile->isValid() || $uploadFile->hasMoved()) return false;

    //     $newName = date('YmdHis') . '_' . str_replace(' ', '_', $uploadFile->getName());
    //     $uploadFile->move($destinationFolder, $newName);
    //     $file = new File($destinationFolder . '/' . $newName);

    //     return $file;
    // }

    // private function get_param_by_module($module)
    // {
    //     $param = (object) [];
    //     switch($module) :
    //         case 'co' : 
    //             $param->t_file = 'co_file'; 
    //             $param->file_folder = 'assets/uploads/company'; 
    //             break;
    //         case 'em' : 
    //         case 're' : 
    //             $param->t_file = 'document_upload'; 
    //             $param->file_folder = 'assets/demandes/documents'; 
    //             break;
    //         case 'rp' : 
    //             $param->t_file = 'rp_file'; 
    //             $param->file_folder = 'assets/uploads/report'; 
    //             break;
    //         // case 'em' : 
    //         //     $param->t_file = 'email_demande_depot'; 
    //         //     $param->file_folder = 'assets/uploads/emodel'; 
    //         //     break;
    //     endswitch;

    //     return $param;
    // }

    // public function get_files_by_ids_file($ids_file)
    // {
    //     $docs = [];
    //     $imgs = [];
    //     foreach($ids_file as $id_file) :
    //         $files = $this->db->table($this->t_file)->where($this->pk, $id_file)->get()->getResult();
    //         if(empty($files)) continue;

    //         $file = database_decode($files[0]);
    //         $path = FCPATH . $this->file_folder . '/' . $file->url_file;
    //         if(!file_exists($path)) continue;

    //         $docs[] = $this->get_doc_by_id_file($file);        
    //         $imgs[] = $this->get_img_by_id_file($file);
    //     endforeach;

    //     $data = (object) [];
    //     $data->docs = array_values(array_filter($docs));
    //     $data->imgs = array_values(array_filter($imgs));

    //     return $data;
    // }

    // public function get_file_by_id_file($id_file, $ref=null)
    // {
    //     $files = $this->db->table($this->t_file)->where($this->pk, $id_file)->get()->getResult();
    //     if(empty($files)) return false;

    //     $file = database_decode($files[0]);
    //     $path = FCPATH . $this->file_folder . '/' . $file->url_file;
    //     if(!file_exists($path)) return false;

    //     $data = $this->get_file_param_by_type($file);
    //     if(!empty($ref)) $data->ref = $ref;

    //     return $data;
    // }

    public function FileUploadFromUrl($url, $param=null)
    {
        $response = $this->curl->request('GET', $url);
        if($response->getStatusCode()!=200)  return false;

        $pathinfo = (object) pathinfo($url);
        $basename = !empty($param->basename) ? $param->basename : $pathinfo->basename;
        $datetime = !empty($param->datetime) ? $param->datetime : date('ymdHis');
        if(preg_match('/^\d{12}_/', $basename)==false) :
            $filename = $datetime . '_' . urlencode(str_replace(' ', '_', $basename));
        else :
            $datetime = substr($basename, 0, 12);
            $filename = urlencode(str_replace(' ', '_', $basename));
        endif;

        file_put_contents(PATH_DOCU_DEMANDE . $filename, $response->getBody());
        $file = new File(PATH_DOCU_DEMANDE . $filename);
        $file->clientName = $basename;

        $id_file = $this->FileDatabaseInsert($file);

        return $id_file;
    }
    public function FileLinkDemandeInsert($id_file, $id_demande)
    {
        if(empty($id_file)) return false;

        $data = (object) [];
        $data->id_document = $id_file;
        $data->id_demande = $id_demande;
        $data->id_user = session('loggedUserId');

        $this->db->table($this->t_demande_file)->set(database_encode($this->t_demande_file, $data))->insert();
    }

    // private function get_file_param_by_type($file)
    // {
    //     $path = $this->file_folder . '/' . $file->url_file;
    //     $data = (object) [];
    //     $data->{$this->pk} = $file->{$this->pk};
    //     $data->url = base_url($path);
    //     $data->name = $file->name;
    //     if(getimagesize(FCPATH . $path)) $data->type = 'img';
    //     else $data->type = 'doc';

    //     return $data;
    // }

    // public function file_upload_process($ref)
    // {
    //     // upload
    //     $ids_file = $this->file_upload_server();
    //     $result = $this->get_html_by_ids_file($ref, $ids_file);
        
    //     echo json_encode($result);
    // }

    // public function upload_img_summernote()
    // {
    //     $id_file = $this->file_upload_server();
    //     if(empty($id_file)) return false;

    //     $file = $this->get_file_by_id_file($id_file);
    //     if(empty($file->url)) return false;

    //     return $file->url;
    // }

    // private function get_html_by_ids_file($ref, $ids_file)
    // {
    //     $result = (object) [];
    //     $result->docs = '';
    //     $result->imgs = '';
    //     foreach($ids_file as $id_file) :
    //         $file = $this->get_file_by_id_file($id_file, $ref);
    //         $type = $file->type;

    //         $data = (object) [];
    //         $data->{$type} = $file;
    //         $data->ref = $ref;
    //         $data->is_new = 1;
    //         $result->{$type . 's'} .= view('templates/form_file_' . $type, $data, true);
    //     endforeach;

    //     return $result;
    // }

    // private function update_ids_file_in_table($action, $ids_to_modif, $param)
    // {
    //     // find files in table depot
    //     $elems = $this->db->select($param->ref)->where($param->pk, $param->pk_value)->get($param->table)->getResult();
    //     if(empty($elems)) return false;

    //     $elem = $elems[0];
    //     $elem = database_decode($elem);
    //     $ids_file = !empty($elem->{$param->ref}) ? $elem->{$param->ref} : [];
        
    //     $data = (object) [];
    //     if($action=='upload') $data->{$param->ref} = array_values(array_unique(array_merge($ids_file, $ids_to_modif)));
    //     elseif($action=='delete') $data->{$param->ref} = array_values(array_diff($ids_file, $ids_to_modif));
    //     $data = database_encode($param->table, $data);
    //     $this->db->where($param->pk, $param->pk_value)->update($param->table, $data);
    // }

    // public function file_upload_server() {
    //     foreach($_FILES as $ref=>$files) :
    //         if(!empty($files['name']) && !empty($files['size'])) :
    //             if(is_array($files['name'])) :
    //                 $ids_file = [];
    //                 $i=0;
    //                 foreach ($files['name'] as $key => $array) :
    //                     if(!empty($array)):
    //                         $filename = urlencode(iconv('UTF-8', 'ASCII//IGNORE', str_replace(' ', '_', $files['name'][$i])));
    //                         $_FILES['to_upload'] = [];
    //                         $_FILES['to_upload']['name']= date('ymdHis') . '_' . $filename;
    //                         $_FILES['to_upload']['type']= $files['type'][$i];
    //                         $_FILES['to_upload']['tmp_name']= $files['tmp_name'][$i];
    //                         $_FILES['to_upload']['error']= $files['error'][$i];
    //                         $_FILES['to_upload']['size']= $files['size'][$i];
    //                         $ids_file[] = $this->file_upload('to_upload');
    //                         $i++;
    //                     endif;
    //                 endforeach;

    //                 return $ids_file;
    //             else :
    //                 $filename = urlencode(iconv('UTF-8', 'ASCII//IGNORE', str_replace(' ', '_', $files['name'])));
    //                 $_FILES['to_upload'] = [];
    //                 $_FILES['to_upload']['name']= date('ymdHis') . '_' . $filename;
    //                 $_FILES['to_upload']['type']= $files['type'];
    //                 $_FILES['to_upload']['tmp_name']= $files['tmp_name'];
    //                 $_FILES['to_upload']['error']= $files['error'];
    //                 $_FILES['to_upload']['size']= $files['size'];
    //                 $id_file = $this->file_upload('to_upload');

    //                 return $id_file;
    //             endif;
               
    //         endif;
    //     endforeach;       
    // }

    // public function file_upload($reference, $config = null)
    // {
    //     $this->load->library('upload');
        
    //     $config['file_name'] = $_FILES[$reference]['name'];
    //     // $config['encrypt_name'] = true;
    //     $config['upload_path'] = FCPATH . $this->file_folder;
    //     if (!file_exists($config['upload_path'])) mkdir($config['upload_path'], 0777, true);
    //     $config['allowed_types'] = isset($config['allowed_types']) ? 
    //         $config['allowed_types'] : 
    //         'gif|jpg|png|doc|docx|odt|pdf|xls|xlsx|ods|ppt|pptx|odp';

    //     $this->upload->initialize($config);
    //     if (!$this->upload->do_upload($reference)) :
    //         // _print($this->upload->display_errors());
    //         return false;
    //     endif;

    //     $post = $this->database_set_post((object) $this->upload->data());
    //     $id_file = $this->database_insert($post);

    //     return $id_file;
    // }

    // public function file_upload($reference)
    // {
    //     $rules = new \Components\Config\Validation();

    //     if (! $this->validate($rules->ruleFile('file'))) :
    //         $data = ['errors' => $this->validator->getErrors()];

    //         return $data;
    //         // return view('upload_form', $data);
    //     endif;
    //     $file = $this->request->getFile('ids_file');

    //     if (! $filepath = $file->store($this->file_folder, $this->request->getFile('reference'))) {
    //         // return view('upload_form', ['error' => 'upload failed']);
    //     }
    //     $post = new File($filepath);

    //     $data[$this->pk] = $this->database_insert($post);

    //     return $data;
    // }

    // private function database_set_post($post)
    // {
    //     $data = (object) [];

    //     $data->name = urldecode(substr($post->file_name, 13));
    //     $data->url_file = $post->file_name;
    //     $data->contentByte_Type = $post->file_type;
    //     $data->commentaire = isset($post->comment) ? $post->comment : null;
    //     $data->id_message = 0;
    //     $data->id_demande = 0;
    //     $data->id_user = $this->session->userdata('id');
    //     $data->date_created = date('Y-m-d H:i:s');
    //     $data->display = 0;

    //     return $data;
    // }

    // private function database_insert($post)
    // {
    //     $post = database_encode($this->t_file, $post);
    //     $this->db->insert($this->t_file, $post);
    //     $id_file = $this->db->getInsertID();

    //     return $id_file;
    // }

    // public function file_delete($id_file)
    // {
    //     $this->file_delete_server($id_file);
    //     $this->file_delete_database($id_file);
    // }

    // public function file_delete_server($id_file)
    // {
    //     $pk = get_primary_key($this->t_file);
    //     $files = $this->db->table($this->t_file)->where($this->pk, $id_file)->get()->getResult();
    //     if(empty($files)) return false;

    //     $file = $files[0];
    //     $filepath = FCPATH . $this->file_folder . '/' . $file->url_file;
    //     if(!file_exists($filepath)) return false;

    //     unlink($filepath);
    //     return true;
    // }

    // private function file_delete_database($id_file)
    // {
    //     $pk = get_primary_key($this->t_file);
    //     $this->db->where($this->pk, $id_file)->delete($this->t_file);
    // }

}
