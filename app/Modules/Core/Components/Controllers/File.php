<?php 

namespace Components\Controllers;

use Base\Controllers\BaseController;
use Components\Libraries\FileLibrary;

class File extends BaseController 
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->FileLibrary = new FileLibrary();
    }

    public function Export($filename, $module, $model, $method)
    {
        $namespace = "\\$module\Models\\$model";
        $model = new $namespace();
        $datas = $model->$method(null, $this->request, 'no_pager');
        $primaryKey = $model->primaryKey;
        $modif = [];
        $i = 0;
        foreach($datas as $data) :
            foreach($data as $key=>$value):
                if($key==$primaryKey) : 
                    $primaryKeyValue = $data->$primaryKey;
                    unset($data->$key);
                elseif(preg_match('/^updated_/', $key) || preg_match('/^created_/', $key)) :
                    $modif[$key] = $value;
                    unset($data->$key);
                endif;
            endforeach;
            $data = (array) $data;
            ksort($data);
            ksort($modif);
            $data = array_merge([$primaryKey => $primaryKeyValue], $data, $modif);
            $datas[$i] = database_decode($data);
            $i++;
        endforeach;
        
        $labels = object_keys($datas[0]);

        export_csv($filename, $datas, $labels);
    }

    public function ExportCsv()
    {
        $post = (object) $this->request->getPost();
        $data = json_decode($post->table);
        export_csv($post->filename, $data);
    }

    public function FileDisplay($id_file)
    {
        $file = $this->FileLibrary->FileGet($id_file);

        $content = file_get_contents(PATH_DOCU_DEMANDE . $file->url_file);

        if($content === FALSE) show_404();

        ob_clean();
        $this->response
            ->setStatusCode(200)
            ->setContentType($file->contentByte_Type)
            ->setBody($content)
            ->send();

        // return $this->response->download(PATH_DOCU_DEMANDE . $file->baseName, $content, true)->setFileName($file->clientName);
    }

//     public function PublicFileDisplay($path)
//     {
//         debugd($path);
//         $path = explode('ext/file/', $path)[1];
// debugd($path);

//         if(!file_exists(WRITEPATH . 'uploads/' . $path)) show_404();

//         $content = file_get_contents(WRITEPATH . 'uploads/' . $path);

//         if($content === FALSE) show_404();

//         ob_clean();
//         $this->response
//             ->setStatusCode(200)
//             ->setContentType(mime_content_type(WRITEPATH . 'uploads/' . $path))
//             ->setBody($content)
//             ->send();
//     }
    
    public function summernoteUploadImage()
    {
        $img = $this->request->getFile('img');
        $id_file = $this->FileLibrary->FileUpload($img);
        $file = $this->FileLibrary->FileGet($id_file);

        $result = (object) [];
        $result->src = base_url('file/display/' . $id_file . '/' . $file->clientName);
        $result->id_attach = $id_file;
        // $result->clientName = $file->clientName;

        echo json_encode($result);
    }

    // public function summernoteUploadImage()
    // {
    //     $validationRule = [
    //         'img' => [
    //             'label' => 'Fichier image',
    //             'rules' => 'uploaded[img]'
    //                 . '|is_image[img]'
    //                 . '|mime_in[img,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
    //         ],
    //     ];


    //     $result = (object) [];
    //     if (! $this->validate($validationRule)) :
    //         $result->error = $this->validator->getErrors();
    //     endif;

    //     $img = $this->request->getFile('img');
    //     $file = $this->FileLibrary->upload($img, WRITEPATH . 'uploads/attachment');
    //     $result->src = img_data($file->getRealPath());

    //     $id_attach = $this->FileLibrary->fileInsert($file);
    //     $result->id_attach = $id_attach;
        
    //     preg_match('/\d{14}_/', $file->getBaseName(), $matches);
    //     $baseName = explode($matches[0], $file->getBaseName())[1];
    //     $result->baseName = $baseName;
        
    //     echo json_encode($result);
    // }

    public function FileRead($filename)
    {
        $file = new \CodeIgniter\Files\File(PATH_DOCU_DEMANDE . $filename);

        ob_clean();
        
        $this->response
            ->setStatusCode(200)
            ->setContentType($file->getMimeType())
            ->setBody(file_get_contents($file->getRealPath()))
            ->send();
    }

    // public function upload($ref, $module)
    // {
    //     $this->FileLibrary->file_upload_process($ref);
    // }

    // public function upload_img_summernote($module)
    // {
    //     $this->load->library('file_library', ['module' => $module]);
    //     $url = $this->file_library->upload_img_summernote();

    //     echo $url;
    // }

    // public function read($type, $path)
    // {
    //     $path = base64_decode(urldecode($path));
    //     $realPath = str_replace('writable/', WRITEPATH, $path);
    //     if($type=='doc') :
    //         preg_match('/\d{14}_/', $realPath, $matches);
    //         $name = explode($matches[0], $realPath)[1];
    //         $this->response
    //             ->download($realPath, null)
    //             ->setFileName($name)
    //             ->send();
    //     elseif($type=='img') :
    //         $file = new \CodeIgniter\Files\File($realPath, true);
    //         $this->response
    //             ->setStatusCode(200)
    //             ->setContentType($file->getMimeType())
    //             ->setBody(file_get_contents($file->getRealPath()))
    //             ->send();
    //     endif;
    // }


    public function FileDelete($id_file)
    {
        $this->FileLibraryibrary->FileDeleteServer($id_file);
        $this->FileLibraryibrary->FileDeleteDatabase($id_file);

    }

    // public function delete($id_file)
    // {
    //     $this->file_library->file_delete($id_file);
    // }
}