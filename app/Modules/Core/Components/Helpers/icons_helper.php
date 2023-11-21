<?php
// icons_helper

if (!function_exists('icon'))
{
    function icon($name)
    {
        switch ($name)
        {

            case 'historique': return "fa-solid fa-calendar-week";
            case 'log': return "fa-solid fa-server";

            case 'expand_old': return "fa-solid fa-expand";
            case 'message_view': return "fa-solid fa-magnifying-glass";

            case 'link_relation': return "fas fa-link";

            case 'orientation_pdf' : return "fas fa-arrows-alt";
            case "yes_autorisation": return "fas fa-check"; 
            case "autorisation":  return "fas fa-ruler-vertical";
            case "remarques":  return "fas fa-comment-dots";

            case "triangle_warning": return "fas fa-exclamation-triangle";
            case "confirmation_ok": return "fas fa-check"; 
            case "confirmation_pdf": return "far fa-thumbs-up"; 
            
            case 'unlink': return 'fas fa-unlink';
            case "expand": return 'fas fa-angle-double-down';
            case "reduire": return 'fas fa-angle-double-up';

            case 'menu_contextuel': return "fas fa-ellipsis-h";
           
            case "triangle_warning": return "fas fa-exclamation-triangle";
            case "confirmation_ok": return "fas fa-check"; 

            case "contacts": return "fas fa-id-card-alt";
            case "activities": return "fas fa-campground";
            case "module": return "fas fa-tasks";
            case "registrations": return "fas fa-user-plus";
            case "payments": return "fas fa-credit-card";
            case "queries": return "fas fa-database";
            case "exportcard": return "fas fa-file-export";
            case "employer": return "fab fa-watchman-monitoring";
            case "recipients": return "fas fa-users";

            case "id": return "fa fa-hashtag";
            case "password": return "fa fa-key";
            case "token": return "fa fa-key";
            case "key": return "fa fa-key";
            case "keywords": return "fa fa-key";

            case "power": return "fa fa-power-off";
            case "lock": return "fa fa-lock";
            case "home": return "fa fa-home";
            case "dashboard": return "fa fa-tachometer-alt";
            case "profile": return "fa fa-id-card";
            case "avatar": return "fa fa-image";
            case "user": return "fa fa-user";
            case "user-no-fill": return "far fa-user";
            case "users": return "fa fa-users";
            case "tools": return "fa fa-tools";
            case "setting": return "fa fa-cog";
            case "settings": return "fa fa-cogs";
            case "confidentiality": return "fa fa-user-secret";

            case "infos": return "fas fa-info-circle";
            case "details": return "fas fa-info-circle";

            case "edit": return "fas fa-edit";

            case "venus": return "fa fa-venus";
            case "mars": return "fa fa-mars";
            case "genders": return "fa fa-venus-mars";
            case "phone": return "fa fa-phone";
            case "gsm": return "fa fa-mobile";
            case "mobile": return "fa fa-mobile";
            case "mail": return "fa fa-envelope";
            case "email": return "fa fa-envelope";
            case "envelope": return "fas fa-envelope";
            case "envelope-empty": return "far fa-envelope";
            case "envelope-open": return "fas fa-envelope-open";
            case "envelope-open-empty": return "far fa-envelope-open-empty";
            case "envelope-open-text": return "fas fa-envelope-open-text";

            case "comment": return "fas fa-comment";
            case "comment-empty": return "far fa-comment";    
            case "comments": return "fas fa-comments";
            case "comments-empty": return "far fa-comments";     
            case "comment-alt": return "fas fa-comment-alt";
            case "comment-alt-empty": return "far fa-comment-alt";
            case "comment-dots": return "fas fa-comment-dots";
            case "comment-dots-empty": return "far fa-comment-dots";
            case "sticky-note": return "fas fa-sticky-note";
            case "sticky-note-empty": return "far fa-sticky-note"; 
            case "country": return "fa fa-globe-europe";
            case "flag": return "fa fa-flag";
            case "capitale": return "fa fa-flag";
            case "devise": return "fas fa-money-bill-wave";
            case "money": return "fa fa-money-bill";

            case "at": return "fas fa-at";
            case "reply": return "fas fa-reply";

            case "website": return "fa fa-globe";
            case "birthday": return "fa fa-gift";
            case "calendar": return "fa fa-calendar-alt";
            case "valided": return "fa fa-check-circle";
            case "actived": return "fa fa-check-circle";
            case "success": return "fa fa-check";
            case "ok": return "fa fa-check";
            case "yes": return "fa fa-check";
            case "role": return "fa fa-user-graduate";
            case "roles": return "fa fa-user-graduate";
            case "label": return "fa fa-tag";
            case "tag": return "fas fa-tag";
            case "tag-empty": return "far fa-tag";
            case "tags": return "fas fa-tags";
            case "tags-empty": return "far fa-tags";
            case "bookmark": return "fas fa-bookmark";
            case "bookmark-empty": return "far fa-bookmark";
            case "heart": return "fas fa-heart";
            case "heart-empty": return "far fa-heart";
            case "thumbs-up": return "fas fa-thumbs-up";
            case "thumbs-up-empty": return "far fa-thumbs-up";
            case "crown": return "fas fa-crown";
            case "crown-empty": return "far fa-crown";
            case "seen": return "fas fa-eye";
            case "seen-empty": return "far fa-eye";
            case "answered": return "fas fa-paper-plane";
            case "answered-empty": return "far fa-paper-plane";

            case "description": return "fa fa-info";
            case "rank": return "fa fa-sort";
            case "download": return "fa fa-download";
            case "add": return "fa fa-plus";
            case "edit": return "fa fa-edit";
            case "delete": return "fa fa-trash";
            case "save": return "fa fa-save";
            case "cancel": return "fa fa-times";
            case "no": return "fa fa-times";
            case "warning": return "fas fa-exclamation-triangle";
            case "danger": return "fas fa-exclamation-triangle";
            case "primary": return "fas fa-info-circle";
            case "secondary": return "fas fa-info-circle";
            case "info": return "fas fa-info-circle";
            case "light": return "fas fa-info-circle";
            case "dark": return "fas fa-info-circle";
            case "print": return "fas fa-print";
            case "paperclip": return "fas fa-paperclip";

            case "cropper": return "fa fa-crop";
            case "upload": return "fa fa-upload";
            case "view": return "fa fa-eye";
            case "drag": return "fa fa-arrows-alt";
            case "ratio": return "fa fa-expand-alt"; 
            case "width": return "fa fa-arrows-alt-h";
            case "height": return "fa fa-arrows-alt-v";

            case "image": return "fa fa-image";
            case "images": return "fa fa-images";
            case "path": return "fas fa-wave-square";
            case "paths": return "fas fa-bezier-curve";
            case "folder": return "fas fa-folder";
            case "folder-open": return "fas fa-folder-open";

            case "select": return "fa fa-bars";
            case "switch": return "fa fa-toggle-off";
            case "radio": return "far fa-circle";
            case "checkbox": return "far fa-square";

            case "utilisator": return "fa fa-user";
            case "educator": return "fa fa-user-graduate";
            case "redactor": return "fa fa-user-edit";
            case "moderator": return "fa fa-user-shield";
            case "administrator": return "fa fa-user-tie"; // tie, secret
            case "menu": return "fa fa-bars";
            case "smile": return "far fa-smile";
            case "helloworld": return "fa fa-smile";
            case "bell": return "far fa-bell";
            case "comment": return "far fa-comment-alt";
            case "book": return "fa fa-book";
            case "help": return "fa fa-life-ring";
            case "forum": return "fab fa-wpforms";
            case "tutorials": return "fa fa-user-graduate";
            case "github": return "fab fa-github-square";
            case "undo": return "fa fa-undo";
            case "goback": return "fa fa-undo";

            case "moveVertical": return "far fa-hand-rock";
            case "moveHorizontal": return "far fa-hand-rock";

            case "modelisation": return "fas fa-tools";

            case "minus-field": return "fas fa-minus-circle";
            case "plus-field": return "fas fa-plus-circle";
            case "check-field": return "fas fa-check";

            case "file": return "fas fa-file";
            case "file-alt": return "fas fa-file-alt";
            case "file-pdf": return "fas fa-file-pdf";
            case "file-word": return "fas fa-file-word";
            case "file-excel": return "fas fa-file-excel";
            case "file-powerpoint": return "fas fa-file-powerpoint";
            case "file-csv": return "fas fa-file-csv";
            case "file-audio": return "fas fa-file-audio";

            case "mail-open": return "fas fa-envelope-open";
            case "email-open": return "fas fa-envelope-open";
            case "mail-square": return "fas fa-envelope-square";
            case "email-square": return "fas fa-envelope-square";
            case "mail-text": return "fas fa-envelope-open-text";
            case "email-text": return "fas fa-envelope-open-text";
            case "send-mail": return "fas fa-paper-plane";
            case "send-email": return "fas fa-paper-plane";
            case "sendmail": return "fas fa-paper-plane";
            case "duplicate": return "fa fa-clone";
            case "clone": return "fa fa-clone";
            case "code": return "fa fa-code";
            case "iso_code": return "fab fa-slack"; // fab fa-slack-hash
            case "isd_code": return "fab fa-slack"; // fas fa-hashtag
            case "tel_code": return "fab fa-slack"; // fas fa-tty

            case "export-csv":  return "fas fa-file-csv"; // doublon

            case "inbox": return "fas fa-inbox";
            case "inbox-in": return "fas fa-inbox-in";
            case "outbox": return "fas fa-inbox";
            case "draft": return "fas fa-inbox";
            case "trash": return "fas fa-trash";
            case "star": return "fas fa-star";
            case "star-empty": return "far fa-star";

            case 'no-meat': return "fas fa-leaf";

            case 'sort-down': return "fas fa-sort-down";
            case 'sort-up': return "fas fa-sort-up";

            case 'doublon': return "fas fa-check-double"; 

            case 'import': return "fas fa-file-import";
       

            default: return "fa fa-home";
        }
    }

    function iconNotificationError()
    {
        return NULL;

    }

       
    
}
