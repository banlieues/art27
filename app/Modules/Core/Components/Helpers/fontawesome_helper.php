<?php

function fontawesome($name) {
    
    $iconArray = array(
        'gasap-group'=>'<i class="fas fa-people-arrows"></i>',
        'gasap-farmer' => '<img class="mx-1" height="18px" src="' . base_url('public/images/logos/pic_producteur.png') . '" />',
        'gasap-eater' => '<img class="mx-1" height="18px" src="' . base_url('public/images/logos/pic_mangeurs.png') . '"/>',
        'gasap-farmer' => '<i class="fas fa-warehouse"></i>',
        'gasap-eater' => '<i class="fas fa-utensils"></i>',
        //'gasap-eater' => '<i class="fas fa-users"></i>',
        'gasap-user' => '<img class="mx-1 mb-1" height="18px" src="' . base_url('public/images/logos/pic_reseau.png') . '"/>',

        'angle-double-down' => '<i class="fas fa-angle-double-down"></i>',
        'angle-double-left' => '<i class="fas fa-angle-double-left"></i>',
        'angle-double-right' => '<i class="fas fa-angle-double-right"></i>',
        'angle-double-up' => '<i class="fas fa-angle-double-up"></i>',
        'angle-down' => '<i class="fas fa-angle-down"></i>',
        'angle-left' => '<i class="fas fa-angle-left"></i>',
        'angle-right' => '<i class="fas fa-angle-right"></i>',
        'angle-up' => '<i class="fas fa-angle-up"></i>',
        'archive' => '<i class="fas fa-archive"></i>',
        'bicycle' => '<i class="fa-solid fa-bicycle"></i>',
        'bomb' => '<i class="fas fa-bomb"></i>',
        'bread-slice' => '<i class="fas fa-bread-slice"></i>',
        'building' => '<i class="fas fa-building"></i>',
        'business-time' => '<i class="fas fa-business-time"></i>',
        'building' => '<i class="far fa-building"></i>',
        'bus' => '<i class="fa-solid fa-bus"></i>',
        'calculator'=>'<i class="fas fa-calculator"></i>',
        'calculator-no' => str_replace(array("\r", "\n"), '', '
            <span class="fa-stack small">
                <i class="fa-solid fa-calculator fa-stack-1x"></i>
                <i class="fa-solid fa-slash fa-stack-1x"></i>
            </span>
        '),
        'calendar-alt' => '<i class="fas fa-calendar-alt"></i>',
        'calendar-check' => '<i class="fas fa-calendar-check"></i>',
        'calendar-day' => '<i class="fas fa-calendar-day"></i>',
        'campground' => '<i class="fas fa-campground"></i>',
        'car' => '<i class="fa-solid fa-car"></i>',
        'car-side' => '<i class="fa-solid fa-car-side"></i>',
        'caret-down' => '<i class="fas fa-caret-down"></i>',
        'caret-right' => '<i class="fas fa-caret-right"></i>',
        'carrot' => '<i class="fas fa-carrot"></i>',
        'cash-register' => '<i class="fas fa-cash-register"></i>',
        'chair' => '<i class="fas fa-chair"></i>',
        'chart-bar' => '<i class="fas fa-chart-bar"></i>',
        'check' => '<i class="fas fa-check"></i>',
        'check-circle' => '<i class="far fa-check-circle"></i>',
        'check-double' => '<i class="fas fa-check-double"></i>',
        'check-square' => '<i class="far fa-check-square"></i>',
        'chevron-down' => '<i class="fas fa-chevron-down"></i>',
        'chevron-right' => '<i class="fas fa-chevron-right"></i>',
        'chevron-up' => '<i class="fas fa-chevron-up"></i>',
        'clipboard' => '<i class="far fa-clipboard"></i>',
        'clipboard-check' => '<i class="fa-solid fa-clipboard-check"></i>',
        'clipboard-list' => '<i class="fas fa-clipboard-list"></i>',
        'clock'=>'<i class="far fa-clock"></i>',
        'cloud-upload-alt' => '<i class="fas fa-cloud-upload-alt"></i>',
        'code-branch' => '<i class="fas fa-code-branch"></i>',
        'cogs' => '<i class="fas fa-cogs"></i>',
        'comment'=>'<i class="far fa-comment"></i>',
        'contact'=>'<i class="far fa-address-book"></i>',
        'copyleft'=>'<i class="fa-solid fa-copyright fa-rotate-180"></i>',
        'copyright'=>'<i class="fa-solid fa-copyright"></i>',
        'credit-card' => '<i class="fas fa-credit-card"></i>',
        'database' => '<i class="fas fa-database"></i>',
        'desktop' => '<i class="fas fa-desktop"></i>',
        'directions' => '<i class="fas fa-directions"></i>',
        'drafting-compass' => '<i class="fas fa-drafting-compass"></i>',
        'down-left-and-up-right-to-center' => '<i class="fa-solid fa-down-left-and-up-right-to-center"></i>',
        'download' => '<i class="fas fa-download"></i>',
        'edit' => '<i class="far fa-edit"></i>',
        'ellipsis' => '<i class="fa-solid fa-ellipsis"></i>',
        'ellipsis-h' => '<i class="fas fa-ellipsis-h"></i>',
        'envelope' => '<i class="fas fa-envelope"></i>',
        'envelope_open' => '<i class="fas fa-envelope-open-text"></i>',
        'euro-sign'=>'<i class="fas fa-euro-sign"></i>',
        'exchange-alt' => '<i class="fas fa-exchange-alt"></i>',
        'exclamation' => '<i class="fas fa-exclamation"></i>',
        'exclamation-triangle' => '<i class="fas fa-exclamation-triangle"></i>',
        'eye' => '<i class="far fa-eye"></i>',
        'eye-slash' => '<i class="fas fa-eye-slash"></i>',
        // 'eye-slash' => str_replace(array("\r", "\n"), '', '
        //     <span class="fa-stack">
        //         <i class="fas fa-eye fa-stack-1x"></i>
        //         <i class="fas fa-slash fa-stack-1x" style="color:Tomato"></i>
        //     </span>'),
        'file' => '<i class="fas fa-file"></i>',
        'file-alt' => '<i class="far fa-file-alt"></i>',
        'file-audio' => '<i class="far fa-file-audio"></i>',
        'file-csv' => '<i class="fas fa-file-csv"></i>',
        'file-download' => '<i class="fas fa-file-download"></i>',
        'file-import' => '<i class="fas fa-file-import"></i>',
        'file-invoice-dollar' => '<i class="fas fa-file-invoice-dollar"></i>',
        'file-pdf' => '<i class="far fa-file-pdf"></i>',
        'file-powerpoint' => '<i class="far fa-file-powerpoint"></i>',
        'file-signature' => '<i class="fas fa-file-signature"></i>',
        'file-video' => '<i class="far fa-file-video"></i>',
        'file-word' => '<i class="fas fa-file-word"></i>',
        'filter' => '<i class="fas fa-filter"></i>',
        'filter-clear' => str_replace(array("\r", "\n"), '', '
            <span>
                <div class="d-flex align-items-center h-100">
                    <div class="fa-stack fa-2xs">
                        <i class="fas fa-filter fa-stack-1x"></i>
                        <i class="fa-solid fa-ban fa-stack-2x"></i>
                    </div>
                </div>
            </span>
        '),
        'filter-warning' => str_replace(array("\r", "\n"), '', '
            <span class="fa-layers fa-fw">
                <i class="fas fa-filter ms-0"></i>
                <i class="fa-solid fa-triangle-exclamation" data-fa-transform="shrink-3 down-6 right-5"></i>
            </span>
        '),
        'contact'=>'<i class="far fa-address-book"></i>',
        'gear' => '<i class="fa-solid fa-gear"></i>',
        'globe' => '<i class="fas fa-globe"></i>',
        'globe-europe' => '<i class="fas fa-globe-europe"></i>',
        'ghost' => '<i class="fas fa-ghost"></i>',
        'grin-beam-sweat' => '<i class="far fa-grin-beam-sweat"></i>',
        'h-square' => '<i class="fas fa-h-square"></i>',
        'hammer' => '<i class="fas fa-hammer"></i>',
        'hand-paper' => '<i class="fas fa-hand-paper"></i>',
        'hand-peace' => '<i class="fas fa-hand-peace"></i>',
        'hand-spock' => '<i class="fas fa-hand-spock"></i>',
        'hard-hat' => '<i class="fas fa-hard-hat"></i>',
        'hippo' => '<i class="fas fa-hippo"></i>',
        'home' => '<i class="fas fa-home"></i>',
        'homegrade' => '<img class="mx-1" height="18px" src="' . base_url('images/logos/homegrade-red-notext.jpg') . '" />',
        'id-card-alt' => '<i class="fas fa-id-card-alt"></i>',
        'image' => '<i class="far fa-image"></i>',
        'info-circle' => '<i class="fas fa-info-circle"></i>',
        'keyboard' => '<span> <i class="fas fa-keyboard"></i> </span>',
        'keyboard-slash' => str_replace(array("\r", "\n"), '', '
            <span class="fa-stack w-100">
                <i class="fas fa-keyboard fa-stack-1x"></i>
                <i class="fas fa-slash fa-stack-1x" style="color:Tomato"></i>
                <i class="fas fa-keyboard invisible"></i>
            </span>
        '),
        'language' => '<i class="fas fa-language"></i>',
        'layer-group' => '<i class="fas fa-layer-group"></i>',
        'level-down-alt' => '<i class="fas fa-level-down-alt"></i>',
        'link' => '<i class="fas fa-link"></i>',
        'link-slash' => '<i class="fa-solid fa-link-slash"></i>',
        'list-ol' => '<i class="fas fa-list-ol"></i>',
        'list-ul' => '<i class="fas fa-list-ul"></i>',
        'location-crosshairs' => '<i class="fa-solid fa-location-crosshairs"></i>',
        'location-dot' => '<i class="fa-solid fa-location-dot"></i>',
        'long-arrow-alt-down' => '<i class="fas fa-long-arrow-alt-down"></i>',
        'long-arrow-alt-right' => '<i class="fas fa-long-arrow-alt-right"></i>',
        'long-arrow-alt-up' => '<i class="fas fa-long-arrow-alt-up"></i>',
        'luggage-cart' => '<i class="fas fa-luggage-cart"></i>',
        'magic' => '<i class="fas fa-magic"></i>',
        'magnifying-glass' => '<i class="fa-solid fa-magnifying-glass"></i>',
        'map' => '<i class="fas fa-map"></i>',
        'map-marker-alt' => '<i class="fas fa-map-marker-alt"></i>',
        'map-marked-alt' => '<i class="fas fa-map-marked-alt"></i>',
        'mailjet' => '<img src="'.base_url('assets/evenement/img/Logo-MailJet.png').'" alt="Mailjet" height="24px">',
        'microscope' => '<i class="fas fa-microscope"></i>',
        'minus' => '<i class="fas fa-minus"></i>',
        'nb_max' => str_replace(array("\r", "\n"), '', '
            <span class="fa-layers fa-fw">
                <i class="fas fa-user" data-fa-transform="shrink-8 up-5 left-6"></i>
                <i class="fas fa-user" data-fa-transform="shrink-8 up-5"></i>
                <i class="fas fa-user" data-fa-transform="shrink-8 up-5 right-6"></i>
                <i class="fas fa-user" data-fa-transform="shrink-8 down-5 left-6"></i>
                <i class="fas fa-user" data-fa-transform="shrink-8 down-5"></i>
                <i class="fas fa-user" data-fa-transform="shrink-8 down-5 right-6"></i>
            </span>
        '),
        'nb_waiting_list' => str_replace(array("\r", "\n"), '', '
            <span class="fa-layers fa-fw">
                <i class="fas fa-list"></i>
                <i class="fas fa-clock" data-fa-transform="shrink-2 down-4 right-8"></i>
            </span>
        '),
        'no-basket' => str_replace(array("\r", "\n"), '', '
            <style>
                .fa-stack.small { font-size: 0.7em; }
                i { vertical-align: middle; }
            </style>
            <span class="fa-stack small">
                <i class="fas fa-shopping-basket fa-stack-1x"></i>
                <i class="fas fa-ban fa-stack-2x"></i>
            </span>
        '),
        'paint-brush' => '<i class="fas fa-paint-brush"></i>',
        'paint-roller' => '<i class="fas fa-paint-roller"></i>',
        'palette' => '<i class="fas fa-palette"></i>',
        'paper-plane' => '<i class="far fa-paper-plane"></i>',
        'paper-plane-test' => str_replace(array("\r", "\n"), '', '
            <span class="fa-layers fa-fw">
                <i class="far fa-paper-plane" data-fa-transform="left-8"></i>
                <span class="fa-layers-text font-weight-bold" data-fa-transform="shrink-8 down-4 right-8">TEST</span>
            </span>'),
        'pen-alt' => '<i class="fas fa-pen-alt"></i>',
        'pencil-alt' => '<i class="fas fa-pencil-alt"></i>',
        'phone-alt' => '<i class="fas fa-phone-alt"></i>',
        'plus' => '<i class="fas fa-plus"></i>',
        'plus-square' => '<i class="fas fa-plus-square"></i>',
        'print' => '<i class="fa-solid fa-print"></i>',
        'question' => '<i class="fas fa-question"></i>',
        'road' => '<i class="fas fa-road"></i>',
        'route' => '<i class="fa-solid fa-route"></i>',
        'ruler-combined' => '<i class="fa-solid fa-ruler-combined"></i>',
        'save' => '<i class="far fa-save"></i>',
        'screwdriver' => '<i class="fas fa-screwdriver"></i>',
        'search' => '<i class="fas fa-search"></i>',
        'seedling' => '<i class="fas fa-seedling"></i>',
        'shoe-prints' => '<i class="fa-solid fa-shoe-prints fa-rotate-270"></i>',
        'shopping-basket' => '<i class="fas fa-shopping-basket"></i>',
        'skull' => '<i class="fas fa-skull"></i>',
        'sort' => '<i class="fas fa-sort"></i>',
        'spinner' => '<i class="fa fa-spinner fa-spin"></i>',
        'spinner_div' => str_replace(array("\r", "\n"), '', '
            <div class="loader_execution_sous align-middle d-inline">
                <i class="fa fa-spin fa-spinner"></i>
            </div>'),
        'star' => '<i class="fas fa-star"></i>',
        'store' => '<i class="fas fa-store"></i>',
        'stream' => '<i class="fas fa-stream"></i>',
        'street-view' => '<i class="fa-solid fa-street-view"></i>',
        'sun' => '<i class="fas fa-sun"></i>',
        'syringe' => '<i class="fas fa-syringe"></i>',
        'table' => '<i class="fas fa-table"></i>',
        'table-list' => '<i class="fa-regular fa-rectangle-list"></i>',
        'tachometer-alt' => '<i class="fas fa-tachometer-alt"></i>',
        'tag' => '<i class="fas fa-tag"></i>',
        'tags' => '<i class="fas fa-tags"></i>',
        'times' => '<i class="fas fa-times"></i>',
        'toolbox' => '<i class="fas fa-toolbox"></i>',
        'tools' => '<i class="fas fa-tools"></i>',
        'trailer' => '<i class="fas fa-trailer"></i>',
        'triangle-exclamation' => '<i class="fa-solid fa-triangle-exclamation"></i>',
        'trash-alt' => '<i class="far fa-trash-alt"></i>',
        'tumblr-square' => '<i class="fab fa-tumblr-square"></i>',
        'turn-up' => '<i class="fa-solid fa-turn-up"></i>',
        'turn-up-flip-horizontal' => '<i class="fa-solid fa-turn-up fa-flip-horizontal"></i>',
        'undo' => '<i class="fas fa-undo"></i>',
        'unlink' => '<i class="fas fa-unlink"></i>',
        'unlock' => '<i class="fa-solid fa-unlock"></i>',
        'up-right-and-down-left-from-center' => '<i class="fa-solid fa-up-right-and-down-left-from-center"></i>',
        'up-right-from-square' => '<i class="fa-solid fa-up-right-from-square"></i>',
        'user' => '<i class="fas fa-user"></i>',
        'user-circle' => '<i class="fas fa-user-circle"></i>',
        'user-cog' => '<i class="fas fa-user-cog"></i>',
        'user-plus' => '<i class="fas fa-user-plus"></i>',
        'user-tie' => '<i class="fas fa-user-tie"></i>',
        'users' => '<i class="fas fa-users"></i>',
        'utensils' => '<i class="fas fa-utensils"></i>',
        'warning' => '<i class="fas fa-wrench"></i>',
        'wordpress' => '<i class="fa-brands fa-wordpress"></i>',
        'wrench' => '<i class="fas fa-wrench"></i>',
    );
    
    return $iconArray[$name];
}


