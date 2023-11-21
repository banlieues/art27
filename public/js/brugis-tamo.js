<script type="text/javascript">

    $('.brugis-search').on('click', function() {
        brugis_autocomplete(this);
    });

    function brugis_autocomplete(elem)
    {
        $(elem).autocomplete({
            source: function(request, response) {
                brugis_source(elem, response);
            },
            minLength: 0,
            select: function( event, ui ) {
                brugis_select(elem, ui);
            }
        });
        $(elem).autocomplete("search", '');

        if($(elem).closest('#modal').length) $(elem).autocomplete("option", "appendTo", '#modal');
    }

    function brugis_select(elem, ui)
    {
        let name = $(elem).attr('name');
        const lang = ui.item.lang;
        name = name.split('_' + lang)[0];
        $('[name="' + name + '_number"]').val(ui.item.address.number);
        $('[name="' + name + '_street"]').val(ui.item.address.street);
        $('[name="' + name + '_pc"]').val(ui.item.address.pc);
        $('[name="' + name + '_city"]').val(ui.item.address.city);

        $('[name="' + name + '_' + lang + '"]').val(ui.item.value);
        if($('[name="' + name + '_' + lang + '"]').val().trim().length>0) {
            $('[name="' + name + '_' + lang + '"]').removeClass('bg-warning');
        } else {
            $('[name="' + name + '_' + lang + '"]').addClass('bg-warning');
        }

        if(lang=='fr') lang_search = 'nl';
        else if(lang=='nl') lang_search = 'fr';
        const url = "https://geoservices.irisnet.be/localization/Rest/Localize/getaddressesfields?json={'language':'','address':{'number':'" + ui.item.address.number + "','street':{'name':'" + ui.item.address.street + "','postcode':'" + ui.item.address.pc + "','municipality':'" + ui.item.address.city + "'}},'spatialReference':'31370'}";
        $.get(url, function(data) {
            for(let site of data.result) {
                let value = '';
                if(site.score<100) {
                    value = site.address.street.name + ', ' + site.address.number + ' - ' + site.address.street.postCode + ' ' + site.address.street.municipality;
                }
                $('[name="' + name + '_' + lang_search + '"]').val(value);
                if($('[name="' + name + '_' + lang_search + '"]').val().trim().length>0) {
                    $('[name="' + name + '_' + lang_search + '"]').removeClass('bg-warning');
                } else {
                    $('[name="' + name + '_' + lang_search + '"]').addClass('bg-warning');
                }

            }
        })
    }

    function brugis_source(elem, response)
    {
        const search = $(elem).val();
        const url_fr = "https://geoservices.irisnet.be/localization/Rest/Localize/getaddresses?address=" + search + "&language=fr&spatialReference=31370";
        const url_nl = "https://geoservices.irisnet.be/localization/Rest/Localize/getaddresses?address=" + search + "&language=nl&spatialReference=31370";
        $.get(url_fr, function(result_fr) {
            $.get(url_nl, function(result_nl) {
                if(!result_fr.result) result_fr.result = [];
                if(!result_nl.result) result_nl.result = [];
                result = result_fr.result.concat(result_nl.result);
                
                brugis_convert_data_for_autocomplete(result, function(data) {
                    response(data);
                });
            });
        });
    }
    
    function brugis_convert_data_for_autocomplete(data, callback)
    {
        let results = [];
        if(data.length>0) 
        {
            for(let site of data) 
            {
                if(site.qualificationText.policeNumber=='Found') 
                {
                    let result = {
                        address: {
                            number: site.address.number,
                            street: site.address.street.name,
                            pc: site.address.street.postCode,
                            city: site.address.street.municipality,
                        },
                        label: site.address.street.name + ', ' + site.address.number + ' - ' + site.address.street.postCode + ' ' + site.address.street.municipality,
                        lang: site.language,
                    };
                    results.push(result);
                }
            }
        }
        callback(results);
    }

</script>