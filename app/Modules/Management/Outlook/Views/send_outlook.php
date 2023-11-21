<div class="panel panel-primary" style="margin-bottom: 0; padding: 0px; ">
    <div class="panel-heading">
		<?php if($id_message>0): ?>
			Répondre à un message
		<?php else :?>
	       Envoyer un nouveau message
		 <?php endif;?>
		   
	</div>
    <div style="height:1500px" class="panel-body">
        <form id="form_send_new_message87" role="form" class="form-horizontal">
            <p id="error_form" class="alert alert-danger output" style="display:none;"></p>
            <div class="form-group">
                <label class="col-lg-1 control-label">Destinataire*</label>
                <div class="col-lg-11">
                    <div class="output" id="output_dest_msg" style="display:none;"></div>
					<?php 
					    if(!empty($message)): 
                            if($type=='response'):
							 $texte = affiche_balise($message[0]->sender_mail);
                            else :
                             $texte = affiche_balise($message[0]->to_mail);
                            endif;
							if(strstr($texte, '&lt') && strstr($texte, '&gt')):
								$marqueurDebutLien = "&lt";
								$debutLien = strpos( $texte, $marqueurDebutLien ) + strlen( $marqueurDebutLien );
								$marqueurFinLien = "&gt";
								$finLien = strpos( $texte, $marqueurFinLien );
								$value = substr( $texte, $debutLien, $finLien - $debutLien ); 
							else :
								$value = $texte;
							endif;
					    endif;
					?>
                    <input type="text" placeholder="" name="dest_msg" id="dest_msg" value="<?php if(!empty($message) && isset($type) &&  in_array($type, array('response', 'brouillon'))): echo $value;  endif; ?>" class="form-control input-sm">


                    <?php 
                          $deja_utilise = array();
                        //les mails des demandeurs
                       $emails_demandeurs = $this->db->select('personne.email')->join('personne', 'personne.id_personne=personne_bien.id_personne', 'left')->where('id_demande', $id_demande)->get('personne_bien')->result();
                        foreach ($emails_demandeurs as $email_d) {
                          if(!empty(extraire_mail($email_d->email))):
                            if(!in_array($email_d->email, $deja_utilise) ):
                                echo "&nbsp";
                                echo "<button style='margin-bottom: 2px;' class='btn btn-info btn-xs click_email'>";
                                echo extraire_mail($email_d->email);
                                echo "</button>";

                                array_push($deja_utilise, $email_d->email);
                            endif;
                          endif;
                        }

                        //les mails qui transite
                        $this->db->select('sender_mail, to_mail, cc_mail, bcc_mail');
                        $this->db->where('id_demande', $id_demande);
                        $this->db->group_by('id_email');
                        $this->db->join('email_outlook', 'email_outlook.id_primary=email_outlook_lien.id_email', 'left');
                        $emails = $this->db->get('email_outlook_lien')->result();

                       foreach ($emails as $email) {
                          if(!empty(extraire_mail($email->sender_mail))):
                            if(!in_array($email->sender_mail, $deja_utilise)):
                                echo "&nbsp";
                                echo "<button style='margin-bottom: 2px;' class='btn btn-info btn-xs click_email'>";
                                echo extraire_mail($email->sender_mail);
                                echo "</button>";

                                array_push($deja_utilise, $email->sender_mail);
                            endif;
                          endif;
                           if(!empty(extraire_mail($email->to_mail))):
                            if(!in_array($email->to_mail, $deja_utilise)):
                                echo "&nbsp";
                                echo "<button style='margin-bottom: 2px;' class='btn btn-info btn-xs click_email'>";
                                echo extraire_mail($email->to_mail);
                                echo "</button>";

                                array_push($deja_utilise, $email->to_mail);
                            endif;
                          endif;
                          if(!empty(extraire_mail($email->cc_mail))):
                            if(!in_array($email->cc_mail, $deja_utilise)):
                                echo "&nbsp";
                                echo "<button style='margin-bottom: 2px;' class='btn btn-info btn-xs click_email'>";
                                echo extraire_mail($email->cc_mail);
                                echo "</button>";

                                array_push($deja_utilise, $email->cc_mail);
                            endif;
                          endif;
                          if(!empty($email->bcc_mail)):
                            if(!in_array($email->bcc_mail, $deja_utilise)):
                                echo "&nbsp";
                                echo "<button style='margin-bottom: 2px;' class='btn btn-info btn-xs click_email'>";
                                echo extraire_mail($email->bcc_mail);
                                echo "</button>";

                                array_push($deja_utilise, $email->bcc_mail);
                            endif;
                          endif;
                       }

                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-1 control-label">Cc</label>
                <div class="col-lg-11">
                    <div class="output" id="output_cc_msg" style="display:none;"></div>
                    <?php 
                        if(!empty($message)): 
                            $texte = affiche_balise($message[0]->cc_mail);
                            if(strstr($texte, '&lt') && strstr($texte, '&gt')):
                                $marqueurDebutLien = "&lt";
                                $debutLien = strpos( $texte, $marqueurDebutLien ) + strlen( $marqueurDebutLien );
                                $marqueurFinLien = "&gt";
                                $finLien = strpos( $texte, $marqueurFinLien );
                                $value_cc_mail = substr( $texte, $debutLien, $finLien - $debutLien ); 
                            else :
                                $value_cc_mail = $texte;
                            endif;
                        endif;
                    ?>
                      <input type="text" placeholder="" name="cc_msg" id="cc_msg" value="<?php if(!empty($message) && isset($type) && in_array($type, array('response', 'brouillon'))): echo $value_cc_mail;  endif; ?>" class="form-control input-sm">
                      <?php 
                      foreach ($deja_utilise as  $value) {
                        echo "&nbsp";
                        echo "<button style='margin-bottom: 2px;' class='btn btn-info btn-xs click_email'>";
                        echo extraire_mail($value);
                        echo "</button>";

                      } 
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-1 control-label">Cci</label>
                <div class="col-lg-11">
                    <div class="output" id="output_cci_msg" style="display:none;"></div>
                     <?php 
                        if(!empty($message)): 
                            $texte = affiche_balise($message[0]->bcc_mail);
                            if(strstr($texte, '&lt') && strstr($texte, '&gt')):
                                $marqueurDebutLien = "&lt";
                                $debutLien = strpos( $texte, $marqueurDebutLien ) + strlen( $marqueurDebutLien );
                                $marqueurFinLien = "&gt";
                                $finLien = strpos( $texte, $marqueurFinLien );
                                $value_bcc_mail = substr( $texte, $debutLien, $finLien - $debutLien ); 
                            else :
                                $value_bcc_mail = $texte;
                            endif;
                        endif;
                    ?>
                      <input type="text" placeholder="" name="cci_msg" id="cci_msg" value="<?php if(!empty($message) && isset($type) && in_array($type, array('response', 'brouillon'))): echo $value_bcc_mail;  endif; ?>" class="form-control input-sm">

                    <?php 
                      foreach ($deja_utilise as  $value) {
                        echo "&nbsp";
                        echo "<button style='margin-bottom: 2px;' class='btn btn-info btn-xs click_email'>";
                        echo extraire_mail($value);
                        echo "</button>";

                      } 
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-1 control-label">Objet*</label>
                <div class="col-lg-11">
                    <div class="output" id="output_objet_msg"style="display:none;"></div>
                    <?php 
                        $value_objet = "";
                        $var="";
			if(isset($id_demande)): $refc='#Ref:'.$id_demande.'# '; else: $refc=NULL; endif;
                        if(!empty($message)): 
			    if(is_reference_outlook($message[0]->subject)): 
                                $value_objet = $message[0]->subject; 
				
				
                            else:
                                 $value_objet = $message[0]->subject; 
                                //$var .= $var.' #Ref:'.$id_demande.'# ';
                            endif;
                            if(isset($type) && $type=='response') : 
				
                                $var= 'Re:'; 
				$value_re = $message[0]->subject; 
				$cre=substr_count ( $value_re,"Re:");
				
				for($i=0;$i<$cre;$i++):
				    $var.="Re:";
				endfor;
				$var.=" ".$refc;
				$value_objet=str_replace("Re:",NULL,str_replace($refc,NULL,$value_objet));
                            elseif(isset($type) && $type=='transfere'): 
                                $var = 'Fwd: ';
				$var.=$refc;
                            else : 
                                $var=""; 
                            endif;
                            
                        else : 
                            $var = '#Ref:'.$id_demande.'# '; 
                        endif;
                    ?>
                    <div class="form-inline">
                    <input class="form-control input-sm" name="objet_prefixe" id="objet_prefixe" disabled="" value="<?=$var;?>">
                    <input type="text" placeholder="" name="objet_msg" id="objet_msg" value="<?=str_replace($refc,NULL,$value_objet);?>" size="70" class="form-control input-sm">
                    </div>
                </div>
            </div>

            <div class="form-group container_all_attachement">
                 <label class="col-lg-1 control-label" for="files_email_demande">Attachement(s)</label>
                  <div class="col-lg-11">
                    <div class="form-inline"> 
                        <table class="container_attachement">
                            <tr>
                                <td>
                                    <div class="output" id="output_files_email_demande"style="display:none;"></div>
                                    <input type="file" name="files_email_demande[]" class="upload_file_pub myfilejoin" id="files_email_demande"> 
                                </td>
                                <td>
                                <button style="margin:5px" href="toto" class="btn btn-info btn-xs firstClone clone_attachement"><i class="fa fa-plus" aria-hidden="true"></i> </button>
                                </td>
                                <td>
                                    <button style="margin:5px" href="toto" class="btn btn-danger btn-xs firstClone declone_attachement"><i class="fa fa-minus" aria-hidden="true"></i> </button>
                                </td>
                            </tr>   
                        </table>
                    </div>

                 </div>
                <table style="display:none" >
                    <tr class="acloner">
                        <td>
                            <div class="output" id="output_files_email_demande"style="display:none;"></div>
                            <input type="file"  name="files_email_demande[]" class="upload_file_pub myfilejoinchange" id="files_email_demande"> 
                        </td>
                        <td>
                        <button style="margin:5px" href="toto" class="btn btn-info btn-xs clone_attachement otherClone"><i class="fa fa-plus" aria-hidden="true"></i> </button>
                        </td>
                        <td>
                            <button style="margin:5px" href="toto" class="btn btn-danger btn-xs declone_attachement otherClone"><i class="fa fa-minus" aria-hidden="true"></i> </button>
                        </td>
                    </tr>   
                    </table>        
             
            </div>    

          

        <?php
            if(isset($message[0]->id_primary)):
                $this->db->select('*');
                $this->db->from('email_demande_depots');
                $this->db->where('id_message', $message[0]->id_primary);
                $this->db->group_by("email_demande_depots.name");
                $files = $this->db->get()->result();
                ?>
               <?php $files_attache=array();?>
                <?php if(count($files)>0): ?>
                            <div class="form-group container_all_attachement">
                        <label class="col-lg-1 control-label" for="files_email_demande">Attachement(s) du message source</label>
                        <div class="col-lg-11">
                            <?php
                           
                            foreach ($files as $file) { array_push($files_attache,$file->name);?>
                             
                                <?Php if(!empty(trim($file->url_file))): ?>
                                <div><input <?php if(isset($type) && $type=='transfere'):?> checked <?php endif;?> type="checkbox" class="other_attachement" value="<?=$file->id?>"> <a  target="blank" href="<?=base_url()?>assets/demandes/documents/<?=$file->url_file?>"><?=$file->name?></a></div>
                            <?php else : ?>
                                    <div><input <?php if(isset($type) && $type=='transfere'):?> checked <?php endif;?> type="checkbox" class="other_attachement" value="<?=$file->id?>"><a target="blank" href="<?=base_url()?>fh/myoutlook/download_base64_document/<?=$file->id?>"><?=$file->name?></a></div>
                            <?php endif; ?>
                        <?Php  }; 
                            ?>
                            </div>
                        </div>  
                
                <?php endif; ?>
            <?php endif;    ?>

           
  <?php 
 
            if(isset($id_demande)):
                $this->db->select('*');
                $this->db->from('email_outlook_lien');
                $this->db->where('email_outlook_lien.id_demande', $id_demande);
                if(isset($files_attache)&&count($files_attache)>0):
                    foreach($files_attache as $nameFile):
                        
                        $this->db->where("name!=",$nameFile);
                    endforeach;

                endif;    

                $this->db->join("email_demande_depots","email_demande_depots.id_message=email_outlook_lien.id_email");
                $this->db->group_by("email_demande_depots.name");
                $files = $this->db->get()->result();
                ?>
              
                <?php if(count($files)>0): ?>
                            <div class="form-group container_all_attachement">
                        <label class="col-lg-1 control-label" for="files_email_demande">
                        <?php if(isset($files_attache)&&count($files_attache)>0):?>
                            Autre(s) document(s) liés à cette demande 
                        <?php else:?>
                            Document(s) liés à cette demande 
                        <?php endif;?>    
                        </label>
                        <div class="col-lg-11">
                            <?php
                            foreach ($files as $file) { ?>
                                <?Php if(!empty(trim($file->url_file))): ?>
                                <div><input  type="checkbox" class="other_attachement" value="<?=$file->id?>"> <a  target="blank" href="<?=base_url()?>assets/demandes/documents/<?=$file->url_file?>"><?=$file->name?></a></div>
                            <?php else : ?>
                                    <div><input type="checkbox" class="other_attachement" value="<?=$file->id?>"><a target="blank" href="<?=base_url()?>fh/myoutlook/download_base64_document/<?=$file->id?>"><?=$file->name?></a></div>
                                <?php endif; ?>
                        <?Php  }
                            ?>
                            </div>
                        </div>  
                
                <?php endif; ?>
            <?php endif;    ?>
            
         
            <div class="form-group">
                <label class="col-lg-1 control-label">Message*</label>
                <div class="col-lg-11">
                    <div class="output" id="outputmessage" style="display:none;"></div>
                    <textarea style="min-height:300px;" rows="10" cols="30" class="form-control input-sm" name="message" id="message"><?php 
                    $user_session = $this->db->select('*')->where('id_user', $_SESSION['id'])->get('users')->result();
                            if(isset($message)):
                                if($type=='brouillon'):
                                    echo trim(($message[0]->body_content));
                                else : 
                                    $sender_mail = str_replace('<', '&lt', $message[0]->sender_mail);
                                    $sender_mail = str_replace('>', '&gt', $message[0]->sender_mail);
                                    $to_mail = str_replace('<', '&lt', str_replace('>', '&gt', $message[0]->to_mail));

                                    $date_send = new Datetime($message[0]->send_datetime);
                                    $date_send_fr =  $date_send->format('d/m/Y H:i:s');
                                     echo '<br><br>';
                                     echo '<div style="float:clear;"><p>'.$user_session[0]->prenom.' '.$user_session[0]->nom.'</p>';
                                    echo signature_homegrade();
                                    echo "<br><br>";
                                    echo "<hr></div>";
                                    
                                  /*  if($type=='response'):   
                                        echo "Le ".$date_send_fr.", ".$sender_mail." a écrit :<br>";
                                        echo "<blockquote style='font-size:12px;'>".trim(($message[0]->body_content))."</blockquote>";
                                    else : */
                                        echo "<blockquote style='font-size:12px;'>";
                                        echo "De : ".$sender_mail.'<br>';
                                        echo "Envoyé le : ".$date_send_fr.'<br>';
                                        echo "A : ".$to_mail.'<br>';
                                        if(!empty($value_cc_mail) && isset($value_cc_mail)): echo 'Cc : '.$value_cc_mail.'<br>'; endif;
                                        echo "Objet : ".$message[0]->subject.'<br>';
                                        echo "<br>";
                                        echo trim(($message[0]->body_content));
                                        echo "</blockquote>";
                                    //endif;
                                endif;
                                else:
                                     echo '<div style="float:clear;"><br><br>';
                                echo '<p>'.$user_session[0]->prenom.' '.$user_session[0]->nom.'</p>';
                                    echo signature_homegrade();
                                    echo "</div>";
                            endif;
                    ?>
                   
                    
                    </textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-1 control-label"></label>
                <div class="col-lg-11">
                    <button class="btn btn-send" type="submit" id="sumit_button">Envoyer</button>
                     <button class="btn btn-default btn-brouillon"  id="">Enregistrer comme brouillon</button>
                        </div>
            </div>
        </form>
    </div>
</div>

<!-- include summernote css/js -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/evenement/css/summernote_custom.css"); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('node_modules/summernote/dist/summernote-lite.min.css'); ?>"/> 
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/evenement/plugin/summernote-ext-emoji-ajax/summernote-ext-emoji-ajax.css');?>"/>

<script type="text/javascript" src="<?php echo base_url("assets/evenement/node_modules/summernote/dist/summernote.min.js"); ?>"></script>

<style>
    .note-editable ul{
        .list-style-list: square !important;
        margin-top:0;
        margin-bottom: 10px;
        padding-left: 40px;

    }
    .note-editable a{
        color:blue;
        text-decoration: underline;

    }
</style>
<script type="text/javascript">
  $(function() {
    $('#message').summernote({
    lang: 'fr-FR',
            height: 800,
            tabsize: 2,
            toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            //['view', ['fullscreen', 'codeview']]
            ],
            lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
            dialogsInBody: true,
            tableClassName: function()
                {
                    $(this).addClass('table table-bordered')
                        .attr('cellspacing', 0)
                        .attr('border', 1)
                        .css('borderCollapse', 'collapse')
                        .css('width', '99%');
                    $(this).find('td').css('borderColor', '#ccc');
                },
            callbacks: {
               onImageUpload: function(image) {
                uploadImage(image[0]);
               },
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

                    e.preventDefault();

                    // Firefox fix
                    setTimeout(function () {
                        document.execCommand('insertText', false, bufferText);
                    }, 10);
                },
            }
        });

    // $('#message').summernote({
    //     height: 450,
    //     lang: 'fr-FR', // default: 'en-US'
    //     codemirror: { // codemirror options
    //         theme: 'monokai'
    //       },
    //     toolbar: [
    //         // [groupName, [list of button]]
    //         ['style', ['bold', 'italic', 'underline']], //'bold', 'italic', 'underline', 'clear'
    //         //['font', ['strikethrough', 'superscript', 'subscript']],
    //         //['fontsize', ['fontsize']],
    //         //['color', ['color']],
    //          ['insert', ['picture', 'link','table']],
    //         ['para', ['ul', 'ol']], //'ul', 'ol', 'paragraph'
    //         //['height', ['height']]
    //     ],
    //      dialogsInBody: true,
    //         callbacks: {
    //             onImageUpload: function(image) {
    //                 uploadImage(image[0]);
    //             }
    //         }
    // });

    function uploadImage(image) {
        var data = new FormData();
        data.append("image", image);
        $.ajax({
            url: '<?php echo base_url();?>fh/myoutlook/add_image_content',
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: "post",
            dataType: "json",
            success: function(data) 
            { 
                //alert(data);
                if(data.is_error==0){
                  var image = $('<img>').attr('src', data.url);
                   $('#message').summernote("insertNode", image[0]);
                }else{
                    alert(data.message);
                }
            }
        });
    }

    $('.selectpicker').selectpicker();


    $(document).off("click",".clone_attachement").on("click",".clone_attachement",function(e){
        var container=$(this).closest(".container_all_attachement");
        var container_ajout_clone=$(".container_attachement",container);
        var cloned=$(".acloner",container).clone().show();
        $(".upload_file_pub",cloned).addClass("myfilejoin");
        $(".upload_file_pub",cloned).closest("tr").removeClass("acloner");
        container_ajout_clone.append(cloned.show());
        return false;

    });

    $(document).off("click",".declone_attachement").on("click",".declone_attachement",function(e){
        if($(this).hasClass("otherClone"))
        {
             $(this).closest("tr").remove();
        }
        else
        {
            
            var container=$(this).closest(".container_all_attachement");
            var container_ajout_clone=$(".container_attachement",container);
            var nbtr=$("tr",container_ajout_clone).length;
          
           
            var cloned=$(".acloner",container).clone().show();
            $(".upload_file_pub",cloned).addClass("myfilejoin");
            $(".clone_attachement",cloned).removeClass("otherClone").addClass("firstClone");
            $(".declone_attachement",cloned).removeClass("otherClone").addClass("firstClone");
            $(".upload_file_pub",cloned).closest("tr").removeClass("acloner");
            
            container_ajout_clone.prepend(cloned.show());
        
            $(this).closest("tr").remove();
            
        }

        return false;

    });



    $(document).off('click', '.btn-brouillon').on('click', '.btn-brouillon', function(e){
        e.preventDefault();
      

        var adresse = '<?=base_url();?>fh/myoutlook/save_brouillon';
        var form = $(this).closest('form');
        var btn = $(this);

        var data = new FormData(); 
        //add id_demande dans data
   
        
        data.append('destinataire', $("#dest_msg", form).val());
        data.append('cc', $("#cc_msg", form).val());
        data.append('cci', $("#cci_msg", form).val());
         data.append('objet_prefixe', $("#objet_prefixe", form).val());
        data.append('objet', $("#objet_msg", form).val());
        data.append('message', $("#message", form).val());
        data.append('id_demande', "<?=$id_demande;?>");

        <?php if(isset($id_message) && isset($type) && $type=='brouillon'){?>
             data.append('id_message', "<?=$id_message;?>");
        <?php } ?>

        jQuery.ajax({ 
            url : adresse, 
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: "post",
            dataType: "json",
                
            beforeSend: function(){
               btn.append(' <i class="fa fa-spinner fa-pulse Loading"></i>').fadeIn();
               btn.prop('disabled', true);
            },

            success : function(data){
                $('.Loading', form).remove();

                if(data.id){
                    console.log(data);
                    form.html('<p class="alert alert-success">Message enregistré.</p>');
                       
                }
            }
        });
    });

    $(document).off('submit', '#form_send_new_message87').on('submit', '#form_send_new_message87', function(e){
        e.preventDefault();
        

        //
        $('.output').html('').hide();
        $('.temp_error').remove();

        var adresse = '<?=base_url();?>fh/myoutlook/send_message';
        var form = $(this);
        var submit = $(this).find('#sumit_button');

        //var form_data = $(this).serialize();

        var data = new FormData();  

        var fileSelect = document.getElementsByClassName('myfilejoin').length;
        //alert(fileSelect);
        
        for (var x = 0; x < fileSelect; x++) {
            data.append("files_email_demande[]", document.getElementsByClassName('myfilejoin')[x].files[0]);
        }

        $(".other_attachement",form).each(function(){
            if($(this).is(':checked') )
            {
                data.append("other_attachement[]",$(this).val());
            }
        });

        //add id_demande dans data
        data.append('destinataire', $("#dest_msg", form).val());
        data.append('cc', $("#cc_msg", form).val());
        data.append('cci', $("#cci_msg", form).val());
         data.append('objet_prefixe', $("#objet_prefixe", form).val());
        data.append('objet', $("#objet_msg", form).val());
        data.append('message', $("#message", form).val());
        data.append('id_demande', "<?=$id_demande;?>");

        
        <?php if(isset($id_message) && isset($type) && $type=='brouillon'){?>
             data.append('id_message', "<?=$id_message;?>");
        <?php } ?>

//alert(adresse);
        jQuery.ajax({ 
            url : adresse, 
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: "post",
            dataType: "json",
                
            beforeSend: function(){
               submit.append(' <i class="fa fa-spinner fa-pulse Loading"></i>').fadeIn();
               submit.prop('disabled', true);
            },

            success : function(data){
                $('.Loading', form).remove();

                if(data.id){
                    console.log(data);
                    //$('#send_message789', form).trigger('click');
                    form.html('<p class="alert alert-success">Message envoyé avec succès.</p>');
                       
                }else{
                    console.log(data);
                    var somme_errors = 0;
                    $.each(data, function(i, obj) {
                        somme_errors++;
                        $('#'+obj.cible+'', form).css('color', 'red').html('<span class="label label-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+obj.msg+"</span>").show();
                    });

                    submit.append(' \
                        <span class="temp_error label label-danger" ><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+somme_errors+'</span>');
                    submit.prop('disabled', false);
                }
            }

        }).fail(function(jqXHR, textStatus, errorThrown) {
            submit.find('#Loading').remove();
            submit.prop('disabled', false);

            alert("error");
            alert(jqXHR.responseText);
        });

    });

    $(document).off('click', '.click_email').on('click', '.click_email', function(){
    
        var val = $(this).html(); //recupere l'email sur le boutton
        var input_id = $(this).prevAll('input').attr('id');
        var val_input = $("#"+input_id).val();

     

        if($.trim(val_input)==""){
            $("#"+input_id).val(val);
        }else{
            var val_array = val_input.split(',');
            var error = 0;

            $.each(val_array, function (index, value){
                var email = $.trim(value);
                if(email == val){
                    error = error +1;
                }
            });

            if(error == 0){
                $("#"+input_id).val(val_input+" , "+val);
            }else{
		alert("Cette adresse email a déjà été ajoutée!");
               /* $.alert({
                    title: 'Alert!',
                    content: 'Cette adresse email est déjà utilisée!'
                });*/
            }
        }

        return false;
    });


  });
</script>