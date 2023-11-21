<div class="panel panel-primary" style="margin-bottom: 0; ">
    <div class="panel-heading">
		<?php if($id_message>0): ?>
			Reponse à un message
		<?php else :?>
	       Envoyer un nouveau message
		 <?php endif;?>
		   
	</div>
    <div class="panel-body">
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
                          if(!empty($email_d->email)):
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
                          if(!empty($email->sender_mail)):
                            if(!in_array($email->sender_mail, $deja_utilise)):
                                echo "&nbsp";
                                echo "<button style='margin-bottom: 2px;' class='btn btn-info btn-xs click_email'>";
                                echo extraire_mail($email->sender_mail);
                                echo "</button>";

                                array_push($deja_utilise, $email->sender_mail);
                            endif;
                          endif;
                           if(!empty($email->to_mail)):
                            if(!in_array($email->to_mail, $deja_utilise)):
                                echo "&nbsp";
                                echo "<button style='margin-bottom: 2px;' class='btn btn-info btn-xs click_email'>";
                                echo extraire_mail($email->to_mail);
                                echo "</button>";

                                array_push($deja_utilise, $email->to_mail);
                            endif;
                          endif;
                          if(!empty($email->cc_mail)):
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
                        if(!empty($message)): 
                            if(isset($type) && $type=='response') : 
                                $var= 'Re: '; 
                            elseif(isset($type) && $type=='transfere'): 
                                $var = 'Fwd: '; 
                            else : 
                                $var=""; 
                            endif;
                            if(is_reference_outlook($message[0]->subject)): 
                                $value_objet = $message[0]->subject; 
                            else:
                                 $value_objet = $message[0]->subject; 
                                $var .= $var.' #Ref:'.$id_demande.'# ';
                            endif;
                        else : 
                            $var = '#Ref:'.$id_demande.'# '; 
                        endif;
                    ?>
                    <div class="form-inline">
                    <input class="form-control input-sm" name="objet_prefixe" id="objet_prefixe" disabled="" value="<?=$var;?>">
                    <input type="text" placeholder="" name="objet_msg" id="objet_msg" value="<?=$value_objet;?>" size="70" class="form-control input-sm">
                    </div>
                </div>
            </div>
            <div class="form-group">
                 <label class="col-lg-1 control-label" for="files_email_demande">Attachment(s)</label>
                  <div class="col-lg-11">
                    <div class="output" id="output_files_email_demande"style="display:none;"></div>
                    <input type="file" name="files_email_demande[]" class="upload_file_pub" id="files_email_demande" multiple="">
                 </div>
            </div>
            <div class="form-group">
                <label class="col-lg-1 control-label">Message*</label>
                <div class="col-lg-11">
                    <div class="output" id="output_message" style="display:none;"></div>
                    <textarea style="height:400px" rows="10" cols="30" class="form-control input-sm" name="message" id="message"><?php 
                            if(isset($message)):
                                if($type=='brouillon'):
                                    echo trim(($message[0]->body_content));
                                else : 
                                    $sender_mail = str_replace('<', '&lt', $message[0]->sender_mail);
                                    $sender_mail = str_replace('>', '&gt', $message[0]->sender_mail);
                                    $to_mail = str_replace('<', '&lt', str_replace('>', '&gt', $message[0]->to_mail));

                                    $date_send = new Datetime($message[0]->send_datetime);
                                    $date_send_fr =  $date_send->format('d/m/Y H:i:s');
                                    echo "<br><br>";
                                    echo "<hr>";
                                    
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
                            endif;
                    ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-11">
                    <button class="btn btn-send" type="submit" id="sumit_button">Envoyer</button>
                     <button class="btn btn-default btn-brouillon"  id="">Enregistrer comme brouillon</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- include summernote css/js -->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
<script type="text/javascript">
  $(function() {

    $('#message').summernote({
        height: 450,
        lang: 'fr-FR', // default: 'en-US'
        codemirror: { // codemirror options
            theme: 'monokai'
          },
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline']], //'bold', 'italic', 'underline', 'clear'
            //['font', ['strikethrough', 'superscript', 'subscript']],
            //['fontsize', ['fontsize']],
            //['color', ['color']],
             ['insert', ['picture', 'link','table']],
            ['para', ['ul', 'ol']], //'ul', 'ol', 'paragraph'
            //['height', ['height']]
        ],
         dialogsInBody: true,
            callbacks: {
                onImageUpload: function(image) {
                    uploadImage(image[0]);
                }
            }
    });

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

        var fileSelect = document.getElementById('files_email_demande').files.length;
        for (var x = 0; x < fileSelect; x++) {
            data.append("files_email_demande[]", document.getElementById('files_email_demande').files[x]);
        }

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