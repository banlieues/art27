<?php

namespace DocumentsTemplates\Controllers;

use Base\Controllers\BaseController;

use DocumentsTemplates\Models\DocumentsTemplatesModel;

use Dompdf\Dompdf;
use Dompdf\Options;

use Components\Libraries\ComponentOrderBy;


class DocumentsTemplates extends BaseController
{
    protected $context;
    protected $path;
    protected $DocumentsTemplatesModel;

    protected $request;

    protected $validateOptions;
 

    public function __construct()
    {
        if(session()->get("loggedUserRoleId")!=1)
       {
            header("Location:".base_url("identification/logout"));
       }
       
        parent::__construct(__NAMESPACE__);

        $this->componentOrderBy=new ComponentOrderBy();

        $this->context = "documentstemplates";
        $this->datas->context = $this->context;
        $this->path = "DocumentsTemplates\Views\/";
        $this->DocumentsTemplatesModel = new DocumentsTemplatesModel();
        $request = $this->request;
        $this->validateOptions=[
            'label' => [
                'rules' => 'required|string|min_length[2]|max_length[128]',
                'errors' => [
                    'required' => 'Label is required ...',
                    'string' => 'Label must be a string ...',
                    'min_length' => 'Label must have a minimum of 2 characters ...',
                    'max_length' => 'Label must have a maximum of 128 characters ...'
                ]
            ],
            
            'content' => [
                'rules' => 'required|string|min_length[2]|max_length[65536]',
                'errors' => [
                    'required' => 'Content is required ...',
                    'string' => 'Content must be a string value ...',
                    'min_length' => 'Content must have a minimum of 2 characters ...',
                    'max_length' => 'Content must have a maximum of 65536 characters ...'
                ]
            ],

          /*  'description' => [
                'rules' => 'required|string|min_length[2]|max_length[256]',
                'errors' => [
                    'required' => 'Description is required ...',
                    'string' => 'Description must be a string value ...',
                    'min_length' => 'Description must have a minimum of 2 characters ...',
                    'max_length' => 'Description must have a maximum of 256 characters ...'
                ]
            ],
            'email_subject' => [
                'rules' => 'required|string|min_length[2]|max_length[128]',
                'errors' => [
                    'required' => 'Email subject is required ...',
                    'string' => 'Email subject must be a string ...',
                    'min_length' => 'Email subject must have a minimum of 2 characters ...',
                    'max_length' => 'Email subject must have a maximum of 128 characters ...'
                ]
            ],
            'email_body' => [
                'rules' => 'required|string|min_length[2]|max_length[256]',
                'errors' => [
                    'required' => 'Email body is required ...',
                    'string' => 'Email body must be a string value ...',
                    'min_length' => 'Email body must have a minimum of 2 characters ...',
                    'max_length' => 'Email body must have a maximum of 256 characters ...'
                ]
            ],
            'actived' => [
                'rules' => 'required|numeric|min_length[1]|max_length[1]',
                'errors' => [
                    'required' => 'Actived is required ...',
                    'numeric' => 'Actived must be a numeric value ...',
                    'min_length' => 'Actived must have a minimum of 1 characters ...',
                    'max_length' => 'Actived must have a maximum of 1 characters ...'
                ]
            ],*/
        ];
     
    }

    public function index()
    {

        $orderBy=$this->componentOrderBy->getOrderBy("label",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("ASC",$this->request);

        $fieldsOrder=
        [
            
            "id"=>["Id",true],
            "label"=>["Libellé",true],
            "type_asbl"=>["Asbl Concernée",true],
            "description"=>["Description",true],
            "email_subject"=>["Sujet du mail",true],
            "updated_at"=>["Mise à jour à",true],
            "name_updated"=>["Mise à jour par",true],
            "actived"=>["Statut",true],
            "action"=>[null, false]
            
            

        ];

        $documents_template = $this->DocumentsTemplatesModel->getListTemplatesModel($this->request,$orderBy,$orderDirection);
        $pager = $this->DocumentsTemplatesModel->pager;
        $documents_template_total = $pager->getTotal();
        $page = $this->request->getGet('page') ?? 1;
      
        $this->datas->title = lang('DocumentsTemplates.title');
        $this->datas->subtitle = lang('DocumentsTemplates.view');
        $this->datas->titleView = lang('DocumentsTemplates.title');
        $this->datas->icon = icon('pdf-file');
        $this->datas->documents_template = $documents_template;
        $this->datas->documents_template_total = $documents_template_total;
        $this->datas->pager = $pager;
        $this->datas->page = $page;
        $this->datas->itemSearch = $this->request->getVar("itemSearch");
        $this->datas->getTh = $this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);

        // $data = [
        //     'title' => lang('DocumentsTemplates.title'),
        //     'subtitle' => lang('DocumentsTemplates.view'),
        //     'titleView' => lang('DocumentsTemplates.title'),
        //     'context' => $this->context,
        //     'icon' => icon('pdf-file'),
        //     'documents_template' => $documents_template,
        //     'documents_template_total' => $documents_template_total,
        //     'pager' => $pager,
        //     'page' => $page,
        //     'itemSearch'=>$this->request->getVar("itemSearch"),
        //     "getTh"=>$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request)

        // ];

        return view($this->path . 'index', (array) $this->datas);
    }

    public function testmail($template_id)
    {   
        return view($this->path . 'modal_test_mail', [
            "value"=>$this->DocumentsTemplatesModel->getOneTemplateModel($template_id)
        ]);
    }

    public function add()
    {
        $this->datas->title = lang('DocumentsTemplates.title');
        $this->datas->subtitle = lang('DocumentsTemplates.add');
        $this->datas->titleView = lang('DocumentsTemplates.title');
        $this->datas->icon = icon('pdf');
        $this->datas->list_statut_template = $this->DocumentsTemplatesModel->get_list_statut_docu();
        $this->datas->liste_type_asbl = $this->DocumentsTemplatesModel->get_liste_type_asbl();

        if ($this->request->getMethod() <> 'post') 
        {
            return view($this->path . 'add', (array) $this->datas);
        }

        $validation = $this->validate($this->validateOptions);

        if (!$validation)
        {
            $data = array_merge((array) $this->datas, ['validation' => $this->validator]);
            return view($this->path . 'add', $data);
        }

        else
        {
            $label = $this->request->getPost('label');
            $description = $this->request->getPost('description');
            $content = $this->request->getPost('content');
            $email_subject = $this->request->getPost('email_subject');
            $email_body = $this->request->getPost('email_body');
            $created_at = date('y-m-d h:m:s');
            $updated_at = date('y-m-d h:m:s');
            $created_by = session()->get('loggedUserId');
            $updated_by = session()->get('loggedUserId');
            $actived = $this->request->getPost('actived');
            $id_type_asbl = $this->request->getPost('id_type_asbl');

            $values = [
                'label' => $label, 
                'description' => $description, 
                'content' => $content, 
                'email_subject' => $email_subject, 
                'email_body' => $email_body, 
                'created_at' => $created_at, 
                'updated_at' => $updated_at, 
                'created_by' => $created_by, 
                'updated_by' => $updated_by, 
                'actived' => $actived, 
                'id_type_asbl'=>$id_type_asbl
            ];

            $insert_documents_template = $this->DocumentsTemplatesModel->insert($values);

            if ($insert_documents_template)
            {
                return redirect()->to('documentstemplates')->with('success', 'Le modèle de document a été créé ...');
            }

            return redirect()->back()->with('danger', "Le modèle de document n'a pas été créé...");
        }
    }

    public function activate()
    {
        $template_id = $this->request->getPost('id');
        $page = $this->request->getPost('page') ?? 1;

        if (isset($template_id) && !empty($template_id) && $template_id > 0)
        {
            $updated_at = date('y-m-d h:m:s');
            $updated_by = session()->get('loggedUserId');
            $actived = $this->request->getPost('actived') ? 0 : 1;

            $values = [
                'updated_at' => $updated_at,
                'updated_by' => $updated_by,
                'actived' => $actived,
            ];

            $update_documents_template = $this->DocumentsTemplatesModel->update($template_id, $values);

            if ($update_documents_template)
            {
                return redirect()->to('documentstemplates/?page='.$page)->with('success', 'Le modèle de document a été mis à jour ...');
            }

            return redirect()->to('documentstemplates/?page='.$page)->with('danger', "Le modèle de document n'a pas été mis à jour ...");
        }

        return redirect()->to('documentstemplates/?page='.$page)->with('danger', "Désolé, ce modèle de document n'existe pas ... ");
    }

    public function details()
    {
        $template_id = $this->request->getGet('id');

        if (isset($template_id) && !empty($template_id) && $template_id > 0)
        {
            $documents_template = $this->DocumentsTemplatesModel->find($template_id);

            if (isset($documents_template) && !empty($documents_template))
            {
                $page = $this->request->getGet('page') ?? 1;

                $this->datas->title = lang('DocumentsTemplates.title');
                $this->datas->subtitle = lang('DocumentsTemplates.details');
                $this->datas->titleView = lang('DocumentsTemplates.title');
                $this->datas->action = 'edit';
                $this->datas->icon = icon('pdf');
                $this->datas->template_id = $template_id;
                $this->datas->documents_template = $documents_template;
                $this->datas->page = $page;

                return view($this->path . 'details', (array) $this->datas);
            }

            return redirect()->to('documentstemplates')->with('danger', "Désolé, ce modèle de document n'existe pas ...");
        }

        return redirect()->back()->with('danger', "Une erreur s'est produite ...");
    }

    public function edit()
    {
        $template_id = $this->request->getGet('id');

        if (isset($template_id) && !empty($template_id) && $template_id > 0)
        {
            $documents_template = $this->DocumentsTemplatesModel->find($template_id);
            $page = $this->request->getGet('page') ?? 1;

            if (isset($documents_template) && !empty($documents_template))
            {
                $this->datas->title = lang('DocumentsTemplates.title');
                $this->datas->subtitle = lang('DocumentsTemplates.edit');
                $this->datas->titleView = lang('DocumentsTemplates.title');
                $this->datas->action = 'edit';
                $this->datas->icon = icon('pdf');
                $this->datas->template_id = $template_id;
                $this->datas->documents_template = $documents_template;
                $this->datas->page = $page;
                $this->datas->list_statut_template = $this->DocumentsTemplatesModel->get_list_statut_docu();
                $this->datas->liste_type_asbl = $this->DocumentsTemplatesModel->get_liste_type_asbl();

                return view($this->path . 'edit', (array) $this->datas);
            }

            return redirect()->to('documentstemplates')->with('danger', "Désolé, ce modèle de document n'existe pas  ...");
        }

        return redirect()->back()->with('danger', "Une erreur s'est produite ...");
    }

    public function save()
    {
        $template_id = $this->request->getPost('id');

        if (isset($template_id) && !empty($template_id) && $template_id > 0)
        {
            $documents_template = $this->DocumentsTemplatesModel->find($template_id);
            $documents_template_total = $this->DocumentsTemplatesModel->countAll();
            $page = $this->request->getPost('page') ?? 1;

            $this->datas->title = lang('DocumentsTemplates.title');
            $this->datas->subtitle = lang('DocumentsTemplates.save');
            $this->datas->titleView = lang('DocumentsTemplates.title');
            $this->datas->icon = icon('pdf');
            $this->datas->template_id = $template_id;
            $this->datas->documents_template = $this->request->getPost();
            $this->datas->documents_template_total = $documents_template_total;
            $this->datas->page = $page;

            $validation = $this->validate($this->validateOptions);

            if (!$validation)
            {    
                $data = array_merge((array) $this->datas, ['validation' => $this->validator]);
                return view($this->path . 'edit', $data);
            }

            else
            {
                $label = $this->request->getPost('label');
                $description = $this->request->getPost('description');
                $content = $this->request->getPost('content');
                $email_subject = $this->request->getPost('email_subject');
                $email_body = $this->request->getPost('email_body');
                $updated_at = date('y-m-d h:m:s');
                $updated_by = session()->get('loggedUserId');
                $actived = $this->request->getPost('actived');
                $id_type_asbl = $this->request->getPost('id_type_asbl');


                $values = [
                    'label' => $label, 
                    'description' => $description, 
                    'content' => $content, 
                    'email_subject' => $email_subject, 
                    'email_body' => $email_body, 
                    'updated_at' => $updated_at, 
                    'updated_by' => $updated_by, 
                    'actived' => $actived, 
                    'id_type_asbl'=>$id_type_asbl
                ];

                $update_documents_template = $this->DocumentsTemplatesModel->update($template_id, $values);

                if ($update_documents_template)
                {
                    return redirect()->to('documentstemplates/?page='.$page)->with('success', 'Le modèle de document a été mis à jour ...');
                }

                return redirect()->back()->with('danger', "Le modèle de document n'a pas été mis à jour ...");
            }
        }

        return redirect()->back()->with('danger', 'Document invalide ...');
    }

    // CREATE A DOCUMENT TEMPLATE AS PDF
    public function dompdf()
    {
        $template_id = $this->request->getGet('id');
        $document_attachment = $this->request->getGet('attachment');
        $document_attachment = filter_var($document_attachment, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (isset($template_id) && !empty($template_id) && $template_id > 0)
        {
            $document_template = $this->DocumentsTemplatesModel->where('id', $template_id)->first();

            if (isset($document_template) && !empty($document_template))
            {
                $html = '<style>table {width:100%; border-collapse: collapse;} td {border: 1px solid #EEE; padding: 10px 5px;}</style>';
                $filename = $document_template['label'];
                $html = $document_template['content'];

                // NEED A BETTER WAY TO FIX THAT
                require_once APPPATH.'ThirdParty/dompdf/lib/Cpdf.php';

                $options = new Options();
                $options->setIsHtml5ParserEnabled(true);
                $dompdf = new Dompdf($options);

                // $dompdf = new Dompdf();
                $dompdf->loadHtml($html, 'UTF-8');
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->set_option('defaultMediaType', 'all');
                $dompdf->render();
                $dompdf->stream($filename, ["Attachment" => $document_attachment]);
                exit();
            }
        }
	}

    // TESTS (TODO) ...
    public function phpword() // $format = 'docx'
    {
        $template_id = $this->request->getGet('id');
        $document_attachment = $this->request->getGet('attachment');
        $document_attachment = filter_var($document_attachment, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (isset($template_id) && !empty($template_id) && $template_id > 0)
        {
            $document_template = $this->DocumentsTemplatesModel->where('id', $template_id)->first();

            if (isset($document_template) && !empty($document_template))
            {
                $filename = $document_template['label'];
                $html = $document_template['content'];

                debug($html, 1);

                // NEED A BETTER WAY TO FIX THAT
                require_once APPPATH.'ThirdParty/PhpWord/bootstrap.php';
                // require_once APPPATH.'ThirdParty/PhpWord/src/PhpWord/PhpWord.php';

                // $this->load->library('PhpWord');
                // $phpword = new PhpWord();
                // $phpword = new \PhpWord\PhpWord();
                $phpword = new \PhpOffice\PhpWord\PhpWord();
                $phpWord = new \PhpOffice\PhpWord\PhpWord();

                $section = $phpword->addSection();
                Html::addHtml($section, $html, true);
                $objWriter = IOFactory::createWriter($phpword, 'Word2007');
                ob_start();
                $objWriter->save('php://output');
                $contents = ob_get_clean();

                // header('Content-Type: application/vnd.ms-word');
                // header('Content-Disposition: attachment; filename="'.$filename.'"');
            }

            // return redirect()->to('documentstemplates')->with('danger', 'Document template not found ...');
        }

        // return redirect()->back()->with('danger', 'Something is wrong ...');
	}

    // SEND A DOCUMENT TEMPLATE BY MAIL
    public function sendmail()
    {
        $page = $this->request->getPost('page') ?? 1;

        $validation = $this->validate([
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email is required ...',
                    'valid_email' => 'This email is invalid ...',
                ]
            ],
        ]);

        if (!$validation)
        {
            return redirect()->to('documentstemplates?page='.$page)->with('danger', 'Email invalide...');
        }

        $template_id = $this->request->getPost('id');

        if (isset($template_id) && !empty($template_id) && $template_id > 0)
        {
            $document_list = $this->DocumentsTemplatesModel->where('id', $template_id)->first();

            if (isset($document_list) && !empty($document_list))
            {
                $filename = $document_list['label'];
                $html = $document_list['content'];

                // NEED A BETTER WAY TO FIX THAT
                require_once APPPATH.'ThirdParty/dompdf/lib/Cpdf.php';

                $dompdf = new Dompdf();
                $dompdf->loadHtml($html, 'UTF-8');
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();      
                $attachment = $dompdf->output();

                // SEND MAIL WITH ATTACHMENT
                $email = $this->request->getPost('email');
                $subject = $document_list['email_subject'];
                $message = $document_list['email_body'];

                send_email_with_attachment_on_fly($email, '', '', $subject, $message, $attachment, $filename);
                return redirect()->to('documentstemplates?page='.$page)->with('success', "EMail envoyé ...");
            }

            return redirect()->to('documentstemplates')->with('danger', "Le module de document n'a pas été trouvé ...");
        }

        return redirect()->back()->with('danger', "Une erreur s'est produite ...");
	}

    // DUPLICATE A MODEL WITHOUTTHE POSSIBILITY OF EDIT BEFORE
    public function duplicate_without_edit()
    {
        $this->datas->title = lang('DocumentsTemplates.title');
        $this->datas->subtitle = lang('DocumentsTemplates.duplicate');
        $this->datas->titleView = lang('DocumentsTemplates.title');
        $this->datas->icon = icon('duplicate');

        if ($this->request->getMethod() <> 'post') 
        {
            $template_id = $this->request->getGet('id');

            if (isset($template_id) && !empty($template_id) && $template_id > 0)
            {
                $documents_template = $this->DocumentsTemplatesModel->find($template_id);

                if (isset($documents_template) && !empty($documents_template))
                {
                    $page = $this->request->getGet('page') ?? 1;

                    $data = array_merge((array) $this->datas, [
                        'template_id' => $template_id,
                        'documents_template' => $documents_template,
                        'page' => $page,
                    ]);

                    return view($this->path . 'duplicate', $data);
                }

                return redirect()->to('documentstemplates')->with('danger', "Désolé, ce modèle de document n'existe pas ...");
            }

            return redirect()->to('documentstemplates')->with('danger', "Désolé, ce modèle de document n'existe pas...");
        }

        $template_id = $this->request->getPost('id');
        $page = $this->request->getPost('page') ?? 1;

        if (isset($template_id) && !empty($template_id) && $template_id > 0)
        {
            $documents_template = $this->DocumentsTemplatesModel->find($template_id);

            if (isset($documents_template) && !empty($documents_template))
            {
                $label = $documents_template['label'].' COPY';
                $description = $documents_template['description'];
                $content = $documents_template['content'];
                $email_subject = $documents_template['email_subject'];
                $email_body = $documents_template['email_body'];
                $created_at = date('y-m-d h:m:s');
                $updated_at = date('y-m-d h:m:s');
                $created_by = session()->get('loggedUserId');
                $updated_by = session()->get('loggedUserId');
                $id_type_asbl = $documents_template['id_type_asbl'];

                $values = [
                    'label' => $label,
                    'description' => $description,
                    'content' => $content,
                    'email_subject' => $email_subject,
                    'email_body' => $email_body,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'created_by' => $created_by,
                    'updated_by' => $updated_by,
                    'id_type_asbl'=>$id_type_asbl
                ];

                $duplicate_documents_template = $this->DocumentsTemplatesModel->insert($values);

                if ($duplicate_documents_template)
                {
                    return redirect()->to('documentstemplates?page='.$page)->with('success', "Le modèle de document a été dupliqué ...");
                }

                return redirect()->back()->with('danger', "La duplication du modèle de document a échoué ...");
            }

            return redirect()->to('documentstemplates?page='.$page)->with('danger', "Désolé ce modèle de document n'existe pas ...");
        }

        return redirect()->back()->with('danger', "Une erreur s'est produite ...");
    }

    // DUPLICATE A MODEL WITH THE POSSIBILITY OF EDIT BEFORE
    public function duplicate()
    {
        $this->datas->title = lang('DocumentsTemplates.title');
        $this->datas->subtitle = lang('DocumentsTemplates.duplicate');
        $this->datas->titleView = lang('DocumentsTemplates.title');
        $this->datas->icon = icon('duplicate');
        $this->datas->list_statut_template = $this->DocumentsTemplatesModel->get_list_statut_docu();
        $this->datas->liste_type_asbl = $this->DocumentsTemplatesModel->get_liste_type_asbl();

        if ($this->request->getMethod() <> 'post') 
        {
            $template_id = $this->request->getGet('id');

            if (isset($template_id) && !empty($template_id) && $template_id > 0)
            {
                $documents_template = $this->DocumentsTemplatesModel->find($template_id);

                if (isset($documents_template) && !empty($documents_template))
                {
                    $page = $this->request->getGet('page') ?? 1;

                    $data = array_merge((array) $this->data, [
                        'template_id' => $template_id,
                        'documents_template' => $documents_template,
                        'page' => $page,
                    ]);

                    return view($this->path . 'duplicate', $data);
                }

                return redirect()->to('documentstemplates')->with('danger', "Désolé, ce module de document n'existe pas ...");
            }

            return redirect()->to('documentstemplates')->with('danger', "Désolé ce module de document n'existe pas...");
        }

        $validation = $this->validate($this->validateOptions);

        if (!$validation)
        {
            $data = array_merge((array) $this->datas, ['validation' => $this->validator]);
            return view($this->path . 'add', $data);
        }

        else
        {
            $label = $this->request->getPost('label');
            $description = $this->request->getPost('description');
            $content = $this->request->getPost('content');
            $email_subject = $this->request->getPost('email_subject');
            $email_body = $this->request->getPost('email_body');
            $created_at = date('y-m-d h:m:s');
            $updated_at = date('y-m-d h:m:s');
            $created_by = session()->get('loggedUserId');
            $updated_by = session()->get('loggedUserId');
            $actived = $this->request->getPost('actived');
            $id_type_asbl = $this->request->getPost('id_type_asbl');


            $values = [
                'label' => $label, 
                'description' => $description, 
                'content' => $content, 
                'email_subject' => $email_subject, 
                'email_body' => $email_body, 
                'created_at' => $created_at, 
                'updated_at' => $updated_at, 
                'created_by' => $created_by, 
                'updated_by' => $updated_by, 
                'actived' => $actived, 
                'id_type_asbl' => $id_type_asbl

            ];

            $duplicate_documents_template = $this->DocumentsTemplatesModel->insert($values);

            if ($duplicate_documents_template)
            {
                return redirect()->to('documentstemplates')->with('success', "Le modèle de document a été dupliqué ...");
            }

            return redirect()->back()->with('danger', "La duplication du modèle de document a échoué ...");
        }
        return redirect()->back()->with('danger', "Une erreur s'est produite ...");
    }

    // DELETE A DOCUMENT TEMPLATE
    public function delete()
    {
        $this->datas->title = lang('DocumentsTemplates.title');
        $this->datas->subtitle = lang('DocumentsTemplates.delete');
        $this->datas->titleView = lang('DocumentsTemplates.title');
        $this->datas->icon = icon('delete');

        if ($this->request->getMethod() <> 'post') 
        {
            $template_id = $this->request->getGet('id');
            $documents_template = $this->DocumentsTemplatesModel->find($template_id);
            $page = $this->request->getGet('page') ?? 1;

            if (isset($documents_template) && !empty($documents_template))
            {
                $data = array_merge((array) $this->datas, [
                    'template_id' => $template_id,
                    'documents_template' => $documents_template,
                    'page' => $page,
                ]);

                return view($this->path . 'delete', $data);
            }

            return redirect()->to('documentstemplates')->with('danger', "Désolé, ce modèle de document n'existe pas ...");
        }

        $template_id = $this->request->getPost('id');
        $page = $this->request->getPost('page') ?? 1;

        if (isset($template_id) && !empty($template_id) && $template_id > 0)
        {
            $delete_document = $this->DocumentsTemplatesModel->delete(['id' => $template_id]);

            if ($delete_document)
            {
                return redirect()->to('documentstemplates?page='.$page)->with('success', "Le modèle de document a été supprimé ...");
            }

            return redirect()->to('documentstemplates?page='.$page)->with('danger', "Le modèle de document n'a pas été effacé ...");
        }

        return redirect()->back()->with('danger', "Une erreur s'est produite ...");
    }

}