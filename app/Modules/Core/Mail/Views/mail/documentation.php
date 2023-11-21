<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom">
        <div> <?php echo $titleView;?> </div>
        <div class="d-flex"><?php echo $button_menu;?></div>
    </div>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div style="padding: 20px;">
    <p> 
        DOCUMENTATION 
    </p>  
    <p> 
        Check the script used for the example : views/example/new.php
    </p>
    <p> Initialize the module inside your controller : 
        <ul>
            <li> use Mail\Libraries\MailLibrary; </li>
            <li> use Mail\Models\MailModel; </li>
        </ul>
    </p>
    <hr>
    <p> Methods to custom inside your controller (example in Mail controller) : 
        <ul>
            <li> for new mail : method_new_example(), get_param_new_example() </li>
            <li> for reply mail : email_reply(), get_param_reply() </li>
            <li> for forward mail : email_forward(), get_param_forward() </li>
            <li> for all : EmailSend(), EmailSave() </li>
        </ul>
    </p>
    <hr>
    <p> REQUIRED methods (in Mail_library) : 
        <ul>
            <li> get_view_by_type($type, $param) : return form view or error </li>
        </ul>
    </p>
    <hr>
    <p>
        List of input parameters :
        <ul>
            <li> required 
                <ul>
                    <li> controller : string </li>
                    <li> js_function_send : string </li>
                    <li> js_function_save : string </li>
                    <li> (only for reply and forward) send_datetime : string </li>
                    <li> (only for reply and forward) sender_old : object </li>
                    <li> reference : string </li>
                    <li> subject : string </li>
                    <li> message : string </li>
                </ul>
            </li>
            <li> optional 
                <ul>
                    <li> sender : object (default sender) </li>
                    <li> to_selected, to_unselected, cc_selected, cc_unselected, cci_selected, cci_unselected : array of objects </li>
                    <li> recip_option_text : array of objects (default Homegrade users list) </li>
                    <li> attachment_selected, attachment_unselected : array </li>
                    <li> signature : string (default Homegrade current signature) </li>
                    <li> templates : array of objects </li>
                    <li> hidden_input : object </li>
                </ul>
            </li>
        </ul>
    </p>    
    <hr>
    <p>
        List of output data :
        <ul>
            <li> isSended : boolean </li>
            <li> sender : json (object) </li>
            <li> to_selected, cc_selected, cci_selected : json (array of objects) </li>
            <li> reply_text, to_text, cc_text, cci_text : json (array) </li>
            <li> reference : string </li>
            <li> subject : string </li>
            <li> message : string </li>
            <li> isSignature : boolean </li>
            <li> signature : string </li>
            <li> attachment_selected : json (array) </li>
            <li> created_at : datetime </li>
            <li> created_by : int </li>
            <li> custom extra fields added with hidden_input : mixed </li>
            <li> only for draft saved :
                <ul>
                    <li> sender_option : json (array of objects) </li>  
                    <li> reply_option : json (array of objects) </li>  
                    <li> to_option, cc_option, cci_option : json (array of objects) </li> 
                    <li> recip_option_text : json (array of objects) </li> 
                    <li> id_template : int </li>
                    <li> attachment_option : json (array) </li>
                </ul>
            </li>
        </ul>
    </p>
    <hr>
    <p> Useful methods : 
        <ul>
            <li> MailLibrary::replace_mail_tag($string, $tags) : replace "[tag]" inside $string by the $tags definition </li>
            <li> MailModel::sendersDefault() : return formated senders with user email and homegrade global mail </li>
            <li> MailModel::SenderGetByUser($id_user) : return formatted id_user mail </li>
            <li> MailModel::get_homegrade_global_email() : return formatted homegrade global mail </li>
        </ul>
    </p>
</div>

<?php $this->endSection(); ?>