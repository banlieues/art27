<?php

namespace Administrator\Controllers;

use App\Controllers\BaseController;

use Administrator\Models\IdentificationModel;
use Administrator\Models\UserProfileModel;
use Administrator\Models\AvatarModel;

class Cropper extends BaseController
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->context = "cropper";

        $this->avatar_path = AVATAR_PATH;
        $this->avatar_default = AVATAR_DEFAULT;

        $this->IdentificationModel = new IdentificationModel();
        $this->UserProfileModel = new UserProfileModel();
        $this->AvatarModel = new AvatarModel();
    }

    public function index()
    {
        $profile_infos = $this->UserProfileModel->where('up_user_id', session('loggedUserId'))->first();
        $avatar_name = !empty($profile_infos['up_avatar']) ? $profile_infos['up_avatar'] : $this->avatar_default;

        $this->datas->title = lang('Cropper.settings_of');
        $this->datas->subtitle = lang('Cropper.view');
        $this->datas->titleView = 'Dashboard';
        $this->datas->icon = icon('dashboard');
        $this->datas->context = $this->context;
        $this->datas->avatar_name = $avatar_name;

        $cropper_settings = $this->AvatarModel->get_cropper_settings();
        $selected_cropper = $this->request->getPost('selectCropper') ?? $cropper_settings[0]->value;
        $cropper_viewMode = $this->request->getPost('viewMode') ?? $cropper_settings[1]->value;
        $cropper_dragMode = $this->request->getPost('dragMode') ?? $cropper_settings[2]->value;
        $cropper_aspectRatio = $this->request->getPost('aspectRatio') ?? $cropper_settings[3]->value;
        $cropper_minCropBoxWidth = $this->request->getPost('minCropBoxWidth') ?? $cropper_settings[4]->value;
        $cropper_minCropBoxHeight = $this->request->getPost('minCropBoxHeight') ?? $cropper_settings[5]->value;

        $selected_cropper_name = lang('Cropper.nocropper'); // simple, no
        if ($selected_cropper == '1') $selected_cropper_name = lang('Cropper.simplecropper');

        $cropper_dragmode_name = lang('Cropper.none'); // crop, move, none
        if ($cropper_dragMode == 'crop') $cropper_dragmode_name = lang('Cropper.crop');
        else if ($cropper_dragMode == 'move') $cropper_dragmode_name = lang('Cropper.move');

        $cropper_aspectratio_name = '1/1';
        if ($cropper_aspectRatio == '43') $cropper_aspectratio_name = '4/3';
        if ($cropper_aspectRatio == '169') $cropper_aspectratio_name = '16/9';

        $this->datas->selected_cropper = $selected_cropper;
        $this->datas->cropper_viewMode = $cropper_viewMode;
        $this->datas->cropper_dragMode = $cropper_dragMode;
        $this->datas->cropper_aspectRatio = $cropper_aspectRatio;
        $this->datas->cropper_minCropBoxWidth = $cropper_minCropBoxWidth;
        $this->datas->cropper_minCropBoxHeight = $cropper_minCropBoxHeight;
        $this->datas->selected_cropper_name = $selected_cropper_name;
        $this->datas->cropper_dragmode_name = $cropper_dragmode_name;
        $this->datas->cropper_aspectratio_name = $cropper_aspectratio_name;

        return view('Administrator\cropper/index', (array) $this->datas);
    }

    public function settings()
    {
        $this->datas->title = lang('Cropper.settings_of');
        $this->datas->subtitle = lang('Cropper.view');
        $this->datas->titleView = lang('Cropper.settings_of');
        $this->datas->icon = 'fa fa-crop';
        $this->datas->context = 'cropper_settings';

        $cropper_settings = $this->AvatarModel->get_cropper_settings();
        $selected_cropper = $this->request->getPost('selectCropper') ?? $cropper_settings[0]->value;
        $cropper_viewMode = $this->request->getPost('viewMode') ?? $cropper_settings[1]->value;
        $cropper_dragMode = $this->request->getPost('dragMode') ?? $cropper_settings[2]->value;
        $cropper_aspectRatio = $this->request->getPost('aspectRatio') ?? $cropper_settings[3]->value;
        $cropper_minCropBoxWidth = $this->request->getPost('minCropBoxWidth') ?? $cropper_settings[4]->value;
        $cropper_minCropBoxHeight = $this->request->getPost('minCropBoxHeight') ?? $cropper_settings[5]->value;

        $this->datas->selected_cropper = $selected_cropper;
        $this->datas->cropper_viewMode = $cropper_viewMode;
        $this->datas->cropper_dragMode = $cropper_dragMode;
        $this->datas->cropper_aspectRatio = $cropper_aspectRatio;
        $this->datas->cropper_minCropBoxWidth = $cropper_minCropBoxWidth;
        $this->datas->cropper_minCropBoxHeight = $cropper_minCropBoxHeight;

        if (!$this->request->getPost())
        {
            return view('Administrator\cropper/settings', (array) $this->datas);
        }

        else
        {
            $cropper_settings = $this->AvatarModel->set_cropper_settings(session('loggedUserId'), $cropper_settings);

            if ($cropper_settings == true)
            {
                return redirect()->to('/cropper/settings')->with('success', 'Cropper settings saved with success ...');
                // session()->setFlashdata('success', 'Cropper settings saved with success ...');
                // return view('cropper/settings', $data);
            }

            return redirect()->back()->with('danger', 'Something is wrong ...');
        }
    }

}
