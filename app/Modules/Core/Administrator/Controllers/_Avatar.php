<?php

namespace Administrator\Controllers;

use Base\Controllers\BaseController;

use Administrator\Models\IdentificationModel;
use Administrator\Models\UserProfileModel;
use Administrator\Models\AvatarModel;

class Avatar extends BaseController
{
    protected $context;
    protected $avatar_path;
    protected $avatar_default;
    protected $avatar_name;
    protected $IdentificationModel;
    protected $UserProfileModel;
    protected $AvatarModel;

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->IdentificationModel = new IdentificationModel();
        $this->UserProfileModel = new UserProfileModel();
        $this->AvatarModel = new AvatarModel();

        $this->context = "avatar";

        $this->avatar_path = AVATAR_PATH;
        $this->avatar_default = AVATAR_DEFAULT;
        $this->avatar_name = AVATAR_DEFAULT;

        $this->datas->context = $this->context;
        $this->datas->loggedUser = sessionUser();
    }

    /* AVATAR UPLOAD (WITHOUT CROPPER) */
    public function index($id_user=null)
    {
        $user = sessionUser($id_user);
        $profile_infos = $this->UserProfileModel->where('user_id', $id_user)->first();

        $avatar_path = $this->avatar_path;
        // $avatar_default = $this->avatar_default;
        $avatar_name = !empty($profile_infos['avatar']) ? $profile_infos['avatar'] : $this->avatar_default;

        $cropper_settings = $this->AvatarModel->get_cropper_settings();
        $selected_cropper = $this->request->getPost('selectCropper') ?? $cropper_settings[0]->value;

        $this->datas->title = lang('Avatar.title');
        $this->datas->subtitle = lang('Avatar.upload');
        $this->datas->titleView = 'Dashboard';
        $this->datas->context = $this->context;
        $this->datas->icon = 'fa fa-image';
        $this->datas->avatar_path = $avatar_path;
        // $this->datas->avatar_default = $avatar_default;
        $this->datas->avatar_name = $avatar_name;
        $this->datas->selected_cropper = $cropper_settings[0]->value;
        $this->datas->user = $user;

        $this->datas->view_mode = $cropper_settings[1]->value;
        $this->datas->drag_mode = $cropper_settings[2]->value;
        $this->datas->aspect_ratio = $cropper_settings[3]->value;
        $this->datas->min_cropbox_width = $cropper_settings[4]->value;
        $this->datas->min_cropbox_height = $cropper_settings[5]->value;

        if (!$this->request->getPost()) 
        {
            return view($this->module . '\avatar/index', (array) $this->datas);
        }

        $validation = $this->validate([
            'avatar' => [
                'rules' => 'uploaded[avatar]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/gif,image/png]|max_dims[avatar,256,256]|max_size[avatar,4096]',
                // 'label' => 'Avatar',
                'errors' => [
                    'uploaded' => 'No file selected ...',
                    'is_image' => 'This file is not a image ...',
                    'mime_in' => 'Invalid file format ...',
                    'max_dims' => 'Invalid file dimension ...',
                    'max_size' => 'Invalid file size ...'
                ]
            ]
        ]);

        if (!$validation)
        {
            $this->datas->validation = $this->validator;

            return view($this->module . '\avatar/index', (array) $this->datas);
        }
        else
        {
            $avatar = $this->request->getFile('avatar');

            if ($avatar->isValid() && !$avatar->hasMoved())
            {
                $avatar_name = $avatar->getName();
                $random_name = $avatar->getRandomName();
                $avatar_path = $this->avatar_path;

                $this->datas->avatar_name = $avatar_name;
                $this->datas->random_name = $random_name;
                $this->datas->avatar_path = $avatar_path;

                $avatar->move($avatar_path, $random_name);
                $profile_infos = $this->UserProfileModel->where('user_id', $id_user)->first();

                if ($profile_infos)
                {
                    // /!\ *** DELETE ALL FILES *** /!\
                    // helper('filesystem');
                    // delete_files($avatar_path);

                    // DELETE OLD AVATAR ONLY IF IS NOT THE AVATAR_DEFAULT
                    $avatar_name_old = $profile_infos['avatar'];

                    if (file_exists($avatar_path.$avatar_name_old) && $avatar_name_old <> $this->avatar_default)
                    {
                        unlink($avatar_path.$avatar_name_old);
                    }

                    $values = [
                        'user_id' => $id_user,
                        'avatar' => $random_name,
                    ];

                    $result = $this->UserProfileModel->update($profile_infos['id'], $values);
                }

                else
                {
                    $values = [
                        'user_id' => $id_user,
                        'avatar' => $random_name,
                    ];

                    $result = $this->UserProfileModel->insert($values);
                }

                if ($result)
                {
                    session()->set('loggedUserAvatar', $random_name);
                    return view($this->module . '\avatar/index', (array) $this->data);
                }

                return redirect()->back()->with('danger', 'Something is wrong ...');
            }
        }	
    }

    /* AVATAR UPLOAD (WITH CROPPER) */
    public function cropper($id_user=NULL)
    {       
        $user = sessionUser($id_user);

        $this->datas->title = lang('Avatar.title');
        $this->datas->subtitle = '';
        $this->datas->titleView = 'Dashboard';
        $this->datas->context = $this->context;
        $this->datas->icon = 'fa fa-image';
        $this->datas->user = $user; 
        $this->datas->user = $user;

        $cropper_settings = $this->AvatarModel->get_cropper_settings();
        $selected_cropper = $this->request->getPost('selectCropper') ?? $cropper_settings[0]->value;

        $this->datas->selected_cropper = $cropper_settings[0]->value; 
        $this->datas->view_mode = $cropper_settings[1]->value;
        $this->datas->drag_mode = $cropper_settings[2]->value;
        $this->datas->aspect_ratio = $cropper_settings[3]->value;
        $this->datas->min_cropbox_width = $cropper_settings[4]->value;
        $this->datas->min_cropbox_height = $cropper_settings[5]->value;

        if (!$this->request->getPost()) 
        {
            return view($this->module . '\avatar/index/'. $id_user, (array) $this->datas);
        }

        $avatar = $this->request->getPost('image');

        if (isset($avatar) && !empty($avatar))
        {
            $parse = explode(";", $avatar);
            $parse = explode(",", $parse[1]);
            $avatar_blob = base64_decode($parse[1]);
            $random_name =  time() . '_' . uniqid() . '.png';
            $avatar_path = $this->avatar_path;
            $avatar_save = file_put_contents($avatar_path . $random_name, $avatar_blob);
            if (($avatar_save !== false) || ($avatar_save != -1))
            {
                $profile_infos = $this->UserProfileModel->where('user_id', $id_user)->first();

                if ($profile_infos)
                {
                    $avatar_name_old = $profile_infos['avatar'];

                    // DELETE OLD AVATAR ONLY IF IS NOT THE AVATAR_DEFAULT
                    if (!empty($avatar_name_old) && file_exists($avatar_path . $avatar_name_old) && $avatar_name_old <> $this->avatar_default)
                    {
                        unlink($avatar_path . $avatar_name_old);
                    }

                    $values = [
                        'user_id' => $id_user,
                        'avatar' => $random_name,
                    ];

                    $result = $this->UserProfileModel->update($profile_infos['id'], $values);
                }
                else
                {
                    $values = [
                        'user_id' => $id_user,
                        'avatar' => $random_name,
                    ];

                    $result = $this->UserProfileModel->insert($values);
                }
                
                if ($result)
                {
                    if($id_user==session('loggedUserId')) session()->set('loggedUserAvatar', $random_name);
                    $post = base_url($avatar_path . $random_name);
                }
            } 
        }

        if(!$post) $post = base_url($avatar_path . $this->avatar_default);
        
        echo $post;
    }

    public function settings()
    {
        $user = sessionUser();

        $this->datas->title = lang('Avatar.title');
        $this->datas->subtitle = lang('Avatar.view');
        $this->datas->titleView = 'Dashboard';
        $this->datas->context = $this->context;
        $this->datas->icon = 'fa fa-cog';
        $this->datas->user = $user;

        return view($this->module . '\avatar/user/settings', (array) $this->datas);
    }

}
