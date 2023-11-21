<?php
/**
 * This is DepositBox Module Controller 
**/

namespace DepositBox\Controllers;

use App\Controllers\BaseController;

use DepositBox\Models\DepositBoxModel;

class DepositBox extends BaseController
{
    protected $context;
    protected $path;
    protected $depositbox_path;
    protected $depositbox_quota;
    protected $depositbox_filesize;

    public function __construct()
    {
        $this->context = "depositbox";
        $this->path = "DepositBox\Views\/";
        $this->depositbox_path = DEPOSITBOX_PATH;
        $this->depositbox_quota = DEPOSITBOX_QUOTA;
        $this->depositbox_filesize = DEPOSITBOX_FILESIZE;
        $this->DepositBoxModel = new DepositBoxModel();
    }

    public function index()
    {
        $deposit_box = $this->DepositBoxModel->paginate(3, 'default');
        $deposit_box_total = $this->DepositBoxModel->countAll();
        $depositbox_path = $this->depositbox_path;
        $pager = $this->DepositBoxModel->pager;
        $page = $this->request->getGet('page') ?? 1;

        $depositbox_quota = $this->depositbox_quota;
        // $total_filesize = $this->DepositBoxModel->selectSum('filesize')->get()->getResult();
        $total_filesize = $this->DepositBoxModel->selectSum('filesize')->get()->getRow();
        $percentage = round($total_filesize->filesize / $depositbox_quota * 100, 2).'%';
        $depositbox_quota = format_filesize($depositbox_quota, 2);
        $total_filesize = format_filesize($total_filesize->filesize, 2);

        $data = [
            'title' => lang('DepositBox.title'),
            'subtitle' => lang('DepositBox.view'),
            'titleView' => lang('DepositBox.title'),
            'context' => $this->context,
            'icon' => icon('depositbox'),
            'deposit_box' => $deposit_box,
            'deposit_box_total' => $deposit_box_total,
            'depositbox_path' => $depositbox_path,
            'depositbox_quota' => $depositbox_quota,
            'total_filesize' => $total_filesize, 
            'percentage' => $percentage, 
            'pager' => $pager,
            'page' => $page,
        ];

        return view($this->path . 'index', $data);
    }

    public function add()
    {
        $data = [
            'title' => lang('DepositBox.title'),
            'subtitle' => lang('DepositBox.add'),
            'titleView' => lang('DepositBox.title'),
            'context' => $this->context,
            'icon' => icon('depositbox'),
        ];

        if ($this->request->getMethod() <> 'post') 
        {
            return view($this->path . 'add', $data);
        }

        $validation = $this->validate([
            'label' => [
                'rules' => 'required|string|min_length[2]|max_length[128]',
                'errors' => [
                    'required' => 'Label is required ...',
                    'string' => 'Label must be a string ...',
                    'min_length' => 'Label must have a minimum of 2 characters ...',
                    'max_length' => 'Label must have a maximum of 128 characters ...'
                ]
            ],
            'description' => [
                'rules' => 'required|string|min_length[2]|max_length[256]',
                'errors' => [
                    'required' => 'Description is required ...',
                    'string' => 'Description must be a string value ...',
                    'min_length' => 'Description must have a minimum of 2 characters ...',
                    'max_length' => 'Description must have a maximum of 256 characters ...'
                ]
            ],
            'comment' => [
                'rules' => 'required|string|min_length[2]|max_length[65536]',
                'errors' => [
                    'required' => 'Content is required ...',
                    'string' => 'Content must be a string value ...',
                    'min_length' => 'Content must have a minimum of 2 characters ...',
                    'max_length' => 'Content must have a maximum of 65536 characters ...'
                ]
            ],
            'keywords' => [
                'rules' => 'required|string|min_length[2]|max_length[256]',
                'errors' => [
                    'required' => 'Keywords is required ...',
                    'string' => 'Keywords must be a string value ...',
                    'min_length' => 'Keywords must have a minimum of 2 characters ...',
                    'max_length' => 'Keywords must have a maximum of 256 characters ...'
                ]
            ],
            'filename' => [
                'rules' => 'required|string|min_length[2]|max_length[128]',
                'errors' => [
                    'required' => 'Description is required ...',
                    'string' => 'File name must be a string value ...',
                    'min_length' => 'File name must have a minimum of 2 characters ...',
                    'max_length' => 'File name must have a maximum of 256 characters ...'
                ]
            ],
            'filesize' => [
                'rules' => 'required|numeric|min_length[1]|max_length[9]',
                'errors' => [
                    'required' => 'File size is required ...',
                    'string' => 'File size must be a string value ...',
                    'min_length' => 'File size must have a minimum of 2 characters ...',
                    'max_length' => 'File size must have a maximum of 256 characters ...'
                ]
            ],
            'extension' => [
                'rules' => 'required|string|min_length[1]|max_length[5]',
                'errors' => [
                    'required' => 'Extension is required ...',
                    'string' => 'Extension must be a string value ...',
                    'min_length' => 'Extension must have a minimum of 2 characters ...',
                    'max_length' => 'Extension must have a maximum of 256 characters ...'
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
            ],
        ]);

        if (!$validation)
        {
            $data = array_merge($data, ['validation' => $this->validator]);
            return view($this->path . 'add', $data);
        }

        else
        {
            $file = $this->request->getFile('uploadfile');

            if (isset($file) && !empty($file))
            {
                $label = $this->request->getPost('label');
                $description = $this->request->getPost('description');
                $comment = $this->request->getPost('comment');
                $keywords = $this->request->getPost('keywords');
                $filename = $this->request->getPost('filename');
                $filesize = $this->request->getPost('filesize');
                $extension = $this->request->getPost('extension');
                $created_at = date('y-m-d h:m:s');
                $updated_at = date('y-m-d h:m:s');
                $created_by = session()->get('loggedUserId');
                $updated_by = session()->get('loggedUserId');
                $actived = $this->request->getPost('actived');
                $depositbox_path = $this->depositbox_path;

                /* Remove all # to prevent broken download */
                $filename = str_replace("#", "", $filename);
                $filename = str_replace(" ", "-", $filename);

                if ($filesize > $this->depositbox_filesize)
                {
                    return redirect()->back()->with('danger', 'File size is to big, max value is '.$this->depositbox_filesize.' octets (50Mb)');
                }

                // $total_filesize = $this->DepositBoxModel->selectSum('filesize')->get()->getResult();
                $total_filesize = $this->DepositBoxModel->selectSum('filesize')->get()->getRow();

                if (($total_filesize->filesize + $filesize) > $this->depositbox_quota)
                {
                    return redirect()->back()->with('danger', 'Your quota of '.$this->depositbox_quota.' octets (1Gb) is exceeded.');
                }

                if (file_exists($depositbox_path.$file->getName()))
                {
                    unlink($depositbox_path.$file->getName());
                }

                $file->move($depositbox_path, $filename);

                $values = [
                    'label' => $label, 
                    'description' => $description, 
                    'comment' => $comment, 
                    'keywords' => $keywords, 
                    'filename' => $filename, 
                    'filesize' => $filesize, 
                    'extension' => $extension, 
                    'created_at' => $created_at, 
                    'updated_at' => $updated_at, 
                    'created_by' => $created_by, 
                    'updated_by' => $updated_by, 
                    'actived' => $actived, 
                ];

                $result = $this->DepositBoxModel->insert($values);

                if ($result)
                {
                    return redirect()->to('depositbox')->with('success', 'File added with success ...');
                }

                return redirect()->back()->with('danger', 'File added without success ...');
            }
            
            else
            {
                return redirect()->back()->with('danger', 'Missing uploaded file ...');
            }
        }
    }

    public function activate()
    {
        $file_id = $this->request->getPost('id');
        $page = $this->request->getPost('page') ?? 1;

        if (isset($file_id) && !empty($file_id) && $file_id > 0)
        {
            $updated_at = date('y-m-d h:m:s');
            $updated_by = session()->get('loggedUserId');
            $actived = $this->request->getPost('actived') ? 0 : 1;

            $values = [
                'updated_at' => $updated_at,
                'updated_by' => $updated_by,
                'actived' => $actived,
            ];

            $update_file = $this->DepositBoxModel->update($file_id, $values);

            if ($update_file)
            {
                return redirect()->to('depositbox/?page='.$page)->with('success', 'File updated with success ...');
            }

            return redirect()->to('depositbox/?page='.$page)->with('danger', 'File updated without success ...');
        }

        return redirect()->to('depositbox/?page='.$page)->with('danger', 'Sorry, this file does not exist ... ');
    }

    public function details()
    {
        $file_id = $this->request->getGet('id');

        if (isset($file_id) && !empty($file_id) && $file_id > 0)
        {
            $depositbox_file = $this->DepositBoxModel->find($file_id);

            if (isset($depositbox_file) && !empty($depositbox_file))
            {
                $page = $this->request->getGet('page') ?? 1;

                $data = [
                    'title' => lang('DepositBox.title'),
                    'subtitle' => lang('DepositBox.details'),
                    'titleView' => lang('DepositBox.title'),
                    'context' => $this->context,
                    'action' => 'edit',
                    'icon' => icon('file'),
                    'file_id' => $file_id,
                    'depositbox_file' => $depositbox_file,
                    'page' => $page,
                ];

                return view($this->path . 'details', $data);
            }

            return redirect()->to('depositbox')->with('danger', 'Sorry, this file does not exist ...');
        }

        return redirect()->back()->with('danger', 'Something is wrong ...');
    }

    public function edit()
    {
        $file_id = $this->request->getGet('id');

        if (isset($file_id) && !empty($file_id) && $file_id > 0)
        {
            $depositbox_file = $this->DepositBoxModel->find($file_id);
            $page = $this->request->getGet('page') ?? 1;

            if (isset($depositbox_file) && !empty($depositbox_file))
            {
                $data = [
                    'title' => lang('DepositBox.title'),
                    'subtitle' => lang('DepositBox.edit'),
                    'titleView' => lang('DepositBox.title'),
                    'context' => $this->context,
                    'action' => 'edit',
                    'icon' => icon('pdf'),
                    'file_id' => $file_id,
                    'depositbox_file' => $depositbox_file,
                    'page' => $page,
                ];

                return view($this->path . 'edit', $data);
            }

            return redirect()->to('depositbox')->with('danger', 'Sorry, this file does not exist ...');
        }

        return redirect()->back()->with('danger', 'Something is wrong ...');
    }

    // SAVE A FILE (UPDATE)
    public function save()
    {
        $file_id = $this->request->getPost('id');

        if (isset($file_id) && !empty($file_id) && $file_id > 0)
        {
            $depositbox_file = $this->DepositBoxModel->find($file_id);
            $depositbox_total = $this->DepositBoxModel->countAll();
            $page = $this->request->getPost('page') ?? 1;

            $data = [
                'title' => lang('DocumentsTemplates.title'), 
                'subtitle' => lang('DocumentsTemplates.save'), 
                'titleView' => lang('DocumentsTemplates.title'), 
                'context' => $this->context, 
                'icon' => icon('pdf'), 
                'file_id' => $file_id, 
                'depositbox_file' => $depositbox_file, 
                'depositbox_total' => $depositbox_total, 
                'page' => $page, 
            ];

            $validation = $this->validate([
                'label' => [
                    'rules' => 'required|string|min_length[2]|max_length[128]',
                    'errors' => [
                        'required' => 'Label is required ...',
                        'string' => 'Label must be a string ...',
                        'min_length' => 'Label must have a minimum of 2 characters ...',
                        'max_length' => 'Label must have a maximum of 128 characters ...'
                    ]
                ],
                'description' => [
                    'rules' => 'required|string|min_length[2]|max_length[256]',
                    'errors' => [
                        'required' => 'Description is required ...',
                        'string' => 'Description must be a string value ...',
                        'min_length' => 'Description must have a minimum of 2 characters ...',
                        'max_length' => 'Description must have a maximum of 256 characters ...'
                    ]
                ],
                'comment' => [
                    'rules' => 'required|string|min_length[2]|max_length[65536]',
                    'errors' => [
                        'required' => 'Content is required ...',
                        'string' => 'Content must be a string value ...',
                        'min_length' => 'Content must have a minimum of 2 characters ...',
                        'max_length' => 'Content must have a maximum of 65536 characters ...'
                    ]
                ],
                'keywords' => [
                    'rules' => 'required|string|min_length[2]|max_length[256]',
                    'errors' => [
                        'required' => 'Keywords is required ...',
                        'string' => 'Keywords must be a string value ...',
                        'min_length' => 'Keywords must have a minimum of 2 characters ...',
                        'max_length' => 'Keywords must have a maximum of 256 characters ...'
                    ]
                ],
                'filename' => [
                    'rules' => 'required|string|min_length[2]|max_length[128]',
                    'errors' => [
                        'required' => 'Description is required ...',
                        'string' => 'File name must be a string value ...',
                        'min_length' => 'File name must have a minimum of 2 characters ...',
                        'max_length' => 'File name must have a maximum of 256 characters ...'
                    ]
                ],
                'filesize' => [
                    'rules' => 'required|numeric|min_length[1]|max_length[9]',
                    'errors' => [
                        'required' => 'File size is required ...',
                        'string' => 'File size must be a string value ...',
                        'min_length' => 'File size must have a minimum of 2 characters ...',
                        'max_length' => 'File size must have a maximum of 256 characters ...'
                    ]
                ],
                'extension' => [
                    'rules' => 'required|string|min_length[1]|max_length[5]',
                    'errors' => [
                        'required' => 'Extension is required ...',
                        'string' => 'Extension must be a string value ...',
                        'min_length' => 'Extension must have a minimum of 2 characters ...',
                        'max_length' => 'Extension must have a maximum of 256 characters ...'
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
                ],
            ]);

            if (!$validation)
            {
                $data = array_merge($data, ['validation' => $this->validator]);
                return view($this->path . 'edit', $data);
            }

            else
            {
                $label = $this->request->getPost('label');
                $description = $this->request->getPost('description');
                $comment = $this->request->getPost('comment');
                $keywords = $this->request->getPost('keywords');
                $updated_at = date('y-m-d h:m:s');
                $updated_by = session()->get('loggedUserId');
                $actived = $this->request->getPost('actived');

                $values = [
                    'label' => $label, 
                    'description' => $description, 
                    'comment' => $comment, 
                    'keywords' => $keywords, 
                    'updated_at' => $updated_at, 
                    'updated_by' => $updated_by, 
                    'actived' => $actived, 
                ];

                $depositbox_file = $this->DepositBoxModel->update($file_id, $values);

                if ($depositbox_file)
                {
                    return redirect()->to('depositbox/?page='.$page)->with('success', 'File updated with success ...');
                }

                return redirect()->back()->with('danger', 'File updated without success ...');
            }
        }

        return redirect()->back()->with('danger', 'Invalid file id detected ...');
    }

    // DELETE A FILE
    public function delete()
    {
        $data = [
            'title' => lang('DepositBox.title'),
            'subtitle' => lang('DepositBox.delete'),
            'titleView' => lang('DepositBox.title'),
            'context' => $this->context,
            'icon' => icon('delete'),
        ];

        if ($this->request->getMethod() <> 'post') 
        {
            $file_id = $this->request->getGet('id');
            $depositbox_file = $this->DepositBoxModel->find($file_id);
            $page = $this->request->getGet('page') ?? 1;

            if (isset($depositbox_file) && !empty($depositbox_file))
            {
                $data = array_merge($data, [
                    'file_id' => $file_id,
                    'depositbox_file' => $depositbox_file,
                    'page' => $page,
                ]);

                return view($this->path . 'delete', $data);
            }

            return redirect()->to('depositbox')->with('danger', 'Sorry, this file does not exist ...');
        }

        $file_id = $this->request->getPost('id');
        $depositbox_file = $this->DepositBoxModel->find($file_id);
        $depositbox_path = $this->depositbox_path;
        $page = $this->request->getPost('page') ?? 1;

        if (file_exists($depositbox_path.$depositbox_file['filename']))
        {
            unlink($depositbox_path.$depositbox_file['filename']);
        }

        if (isset($file_id) && !empty($file_id) && $file_id > 0)
        {
            $delete_file = $this->DepositBoxModel->delete(['id' => $file_id]);

            if ($delete_file)
            {
                return redirect()->to('depositbox?page='.$page)->with('success', 'File deleted with success ...');
            }

            return redirect()->to('depositbox?page='.$page)->with('danger', 'File deleted without success ...');
        }

        return redirect()->back()->with('danger', 'Something is wrong ...');
    }
}
