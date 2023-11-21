<style type="text/css">
	/* .list-group-item:hover{
	    cursor: pointer;
	    background-color:#eee; 
	} */
</style>


<script type="text/javascript">
    
    

    
    function url_slug(s, opt) {
	s = String(s);
	opt = Object(opt);
	
	var defaults = {
		'delimiter': '-',
		'limit': undefined,
		'lowercase': true,
		'replacements': {},
		'transliterate': (typeof(XRegExp) === 'undefined') ? true : false
	};
	
	// Merge options
	for (var k in defaults) {
		if (!opt.hasOwnProperty(k)) {
			opt[k] = defaults[k];
		}
	}
	
	var char_map = {
		// Latin
		'À': 'A', 'Á': 'A', 'Â': 'A', 'Ã': 'A', 'Ä': 'A', 'Å': 'A', 'Æ': 'AE', 'Ç': 'C', 
		'È': 'E', 'É': 'E', 'Ê': 'E', 'Ë': 'E', 'Ì': 'I', 'Í': 'I', 'Î': 'I', 'Ï': 'I', 
		'Ð': 'D', 'Ñ': 'N', 'Ò': 'O', 'Ó': 'O', 'Ô': 'O', 'Õ': 'O', 'Ö': 'O', 'Ő': 'O', 
		'Ø': 'O', 'Ù': 'U', 'Ú': 'U', 'Û': 'U', 'Ü': 'U', 'Ű': 'U', 'Ý': 'Y', 'Þ': 'TH', 
		'ß': 'ss', 
		'à': 'a', 'á': 'a', 'â': 'a', 'ã': 'a', 'ä': 'a', 'å': 'a', 'æ': 'ae', 'ç': 'c', 
		'è': 'e', 'é': 'e', 'ê': 'e', 'ë': 'e', 'ì': 'i', 'í': 'i', 'î': 'i', 'ï': 'i', 
		'ð': 'd', 'ñ': 'n', 'ò': 'o', 'ó': 'o', 'ô': 'o', 'õ': 'o', 'ö': 'o', 'ő': 'o', 
		'ø': 'o', 'ù': 'u', 'ú': 'u', 'û': 'u', 'ü': 'u', 'ű': 'u', 'ý': 'y', 'þ': 'th', 
		'ÿ': 'y',

		// Latin symbols
		'©': '(c)',

		// Greek
		'Α': 'A', 'Β': 'B', 'Γ': 'G', 'Δ': 'D', 'Ε': 'E', 'Ζ': 'Z', 'Η': 'H', 'Θ': '8',
		'Ι': 'I', 'Κ': 'K', 'Λ': 'L', 'Μ': 'M', 'Ν': 'N', 'Ξ': '3', 'Ο': 'O', 'Π': 'P',
		'Ρ': 'R', 'Σ': 'S', 'Τ': 'T', 'Υ': 'Y', 'Φ': 'F', 'Χ': 'X', 'Ψ': 'PS', 'Ω': 'W',
		'Ά': 'A', 'Έ': 'E', 'Ί': 'I', 'Ό': 'O', 'Ύ': 'Y', 'Ή': 'H', 'Ώ': 'W', 'Ϊ': 'I',
		'Ϋ': 'Y',
		'α': 'a', 'β': 'b', 'γ': 'g', 'δ': 'd', 'ε': 'e', 'ζ': 'z', 'η': 'h', 'θ': '8',
		'ι': 'i', 'κ': 'k', 'λ': 'l', 'μ': 'm', 'ν': 'n', 'ξ': '3', 'ο': 'o', 'π': 'p',
		'ρ': 'r', 'σ': 's', 'τ': 't', 'υ': 'y', 'φ': 'f', 'χ': 'x', 'ψ': 'ps', 'ω': 'w',
		'ά': 'a', 'έ': 'e', 'ί': 'i', 'ό': 'o', 'ύ': 'y', 'ή': 'h', 'ώ': 'w', 'ς': 's',
		'ϊ': 'i', 'ΰ': 'y', 'ϋ': 'y', 'ΐ': 'i',

		// Turkish
		'Ş': 'S', 'İ': 'I', 'Ç': 'C', 'Ü': 'U', 'Ö': 'O', 'Ğ': 'G',
		'ş': 's', 'ı': 'i', 'ç': 'c', 'ü': 'u', 'ö': 'o', 'ğ': 'g', 

		// Russian
		'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', 'Ё': 'Yo', 'Ж': 'Zh',
		'З': 'Z', 'И': 'I', 'Й': 'J', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N', 'О': 'O',
		'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T', 'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'C',
		'Ч': 'Ch', 'Ш': 'Sh', 'Щ': 'Sh', 'Ъ': '', 'Ы': 'Y', 'Ь': '', 'Э': 'E', 'Ю': 'Yu',
		'Я': 'Ya',
		'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh',
		'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
		'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c',
		'ч': 'ch', 'ш': 'sh', 'щ': 'sh', 'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu',
		'я': 'ya',

		// Ukrainian
		'Є': 'Ye', 'І': 'I', 'Ї': 'Yi', 'Ґ': 'G',
		'є': 'ye', 'і': 'i', 'ї': 'yi', 'ґ': 'g',

		// Czech
		'Č': 'C', 'Ď': 'D', 'Ě': 'E', 'Ň': 'N', 'Ř': 'R', 'Š': 'S', 'Ť': 'T', 'Ů': 'U', 
		'Ž': 'Z', 
		'č': 'c', 'ď': 'd', 'ě': 'e', 'ň': 'n', 'ř': 'r', 'š': 's', 'ť': 't', 'ů': 'u',
		'ž': 'z', 

		// Polish
		'Ą': 'A', 'Ć': 'C', 'Ę': 'e', 'Ł': 'L', 'Ń': 'N', 'Ó': 'o', 'Ś': 'S', 'Ź': 'Z', 
		'Ż': 'Z', 
		'ą': 'a', 'ć': 'c', 'ę': 'e', 'ł': 'l', 'ń': 'n', 'ó': 'o', 'ś': 's', 'ź': 'z',
		'ż': 'z',

		// Latvian
		'Ā': 'A', 'Č': 'C', 'Ē': 'E', 'Ģ': 'G', 'Ī': 'i', 'Ķ': 'k', 'Ļ': 'L', 'Ņ': 'N', 
		'Š': 'S', 'Ū': 'u', 'Ž': 'Z', 
		'ā': 'a', 'č': 'c', 'ē': 'e', 'ģ': 'g', 'ī': 'i', 'ķ': 'k', 'ļ': 'l', 'ņ': 'n',
		'š': 's', 'ū': 'u', 'ž': 'z'
	};
	
	// Make custom replacements
	for (var k in opt.replacements) {
		s = s.replace(RegExp(k, 'g'), opt.replacements[k]);
	}
	
	// Transliterate characters to ASCII
	if (opt.transliterate) {
		for (var k in char_map) {
			s = s.replace(RegExp(k, 'g'), char_map[k]);
		}
	}
	
	// Replace non-alphanumeric characters with our delimiter
	var alnum = (typeof(XRegExp) === 'undefined') ? RegExp('[^a-z0-9]+', 'ig') : XRegExp('[^\\p{L}\\p{N}]+', 'ig');
	s = s.replace(alnum, opt.delimiter);
	
	// Remove duplicate delimiters
	s = s.replace(RegExp('[' + opt.delimiter + ']{2,}', 'g'), opt.delimiter);
	
	// Truncate slug to max. characters
	s = s.substring(0, opt.limit);
	
	// Remove delimiter from ends
	s = s.replace(RegExp('(^' + opt.delimiter + '|' + opt.delimiter + '$)', 'g'), '');
	
	return opt.lowercase ? s.toLowerCase() : s;
}
    
    
    
    
	$(document).ready(function(){
	
	    /* $(document).off("focusout", ".search_address").on("focusout", ".search_address", function(event){
	     
		var result_container = $(this).closest(".c_search_address").find('.result-list-address');
		result_container.html("").hide();
		 
	     });
	     */
	    
	     $(document).off("click", ".translatebrugis").on("click", ".translatebrugis", function(event){
	
	var el_search=$(this).closest(".c_translate").find(".search_address");
	    var name_encours=el_search.attr("name");
	    if(name_encours==="value"){
		name_encours=el_search.attr("field_sql")+"_bien";
		
	    }
	   
	     var lg_encours="";
	      var container_en_cours=el_search.closest(".c_search_address");
	      var form_commun=el_search.closest("form");
	     if(name_encours==="adresse_fr_bien"){
		 lg_encours="fr"; 
		 $("input[name='adresse_nl_bien']",form_commun).val("");
		var result_container =  $("input[name='adresse_nl_bien']",form_commun).closest(".c_search_address").find('.result-list-address');
		 
		result_container.html("").hide();
	     };
	     if(name_encours==="adresse_nl_bien"){
		 lg_encours="nl";  
		 $("input[name='adresse_fr_bien']",form_commun).val("");
		var result_container =  $("input[name='adresse_fr_bien']",form_commun).closest(".c_search_address").find('.result-list-address');
		result_container.html("").hide();
		 
	     }
	    
 	

 	var search = el_search.val();
 	search = $.trim(search);
 	var adresse = "https://geoservices.irisnet.be/localization/Rest/Localize/getaddresses?address="+search+"&language="+lg_encours+"&spatialReference=31370";
	
 	jQuery.ajax({ 
		type :"POST",
		url : adresse,
		dataType : "json",
		//data : data,

	    beforeSend: function(){
	        //submit.append(' <i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i>').fadeIn();
	    },

		success : function(data){
			console.log(data);
			var container = "";
			if(data['status']=="success"){
				$.each(data['result'], function( index, value ) {
				  if(  value.qualificationText["policeNumber"]==="Found"){
				    var street = value.address['street'];
					container+= '<li id="fav" class="list-group-item list-group-item-action list-li" brug_lg="'+lg_encours+'" brug_id="'+street.id+'" brug_num="'+value.address['number']+'" brug_street_name="'+street.name+'" brug_postcode="'+street.postCode+'" brug_municipality="'+street.municipality+'" >';
					container+= '<div class="value-list">';

					if(street.name != ""){container+= street.name+" ";}
					if(value.address['number']!=""){container+= value.address['number']+" ";}
					if(street.postCode != ""){container+= street.postCode+" ";}
					if(street.municipality != ""){container+= street.municipality;}

					
					container+= '</div></li>';
				    }
				});
				$(".result-list-address",container_en_cours).html(container).show();
			}else{
				$(".result-list-address",container_en_cours).html("").hide();
			}

			
		}
    });
    return false;
 });
	 $(document).off("keyup", ".search_address").on("keyup", ".search_address", function(event){
	     
	    var name_encours=$(this).attr("name");
	    if(name_encours==="value"){
		name_encours=$(this).attr("field_sql")+"_bien";
		
	    }
	   
	     var lg_encours="";
	      var container_en_cours=$(this).closest(".c_search_address");
	      var form_commun=$(this).closest("form");
	     var is_exist=$(this).closest("form").length;
	     if(is_exist===0){
		 form_commun=$(this).closest(".modal");
		
	     };   
	      
	     if(name_encours==="adresse_fr_bien"){
		 lg_encours="fr"; 
		 $("input[name='adresse_nl_bien']",form_commun).val("");
		var result_container =  $("input[name='adresse_nl_bien']",form_commun).closest(".c_search_address").find('.result-list-address');
		 
		result_container.html("").hide();
	     };
	     if(name_encours==="adresse_nl_bien"){
		 lg_encours="nl";  
		 $("input[name='adresse_fr_bien']",form_commun).val("");
		var result_container =  $("input[name='adresse_fr_bien']",form_commun).closest(".c_search_address").find('.result-list-address');
		result_container.html("").hide();
		 
	     }
	    
 	var TouchKeyPress = (window.Event) ? event.which : event.keyPress;

 	if (TouchKeyPress > 0 && (TouchKeyPress == 33 || TouchKeyPress == 34)){
 		return false;
 	}

 	var search = $(this).val();
 	search = $.trim(search);
 	var adresse = "https://geoservices.irisnet.be/localization/Rest/Localize/getaddresses?address="+search+"&language="+lg_encours+"&spatialReference=31370";

 	jQuery.ajax({ 
		type :"POST",
		url : adresse,
		dataType : "json",
		//data : data,

	    beforeSend: function(){
	        //submit.append(' <i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i>').fadeIn();
	    },

		success : function(data){
			console.log(data);
			var container = "";
			if(data['status']=="success"){
				$.each(data['result'], function( index, value ) {
				  if(  value.qualificationText["policeNumber"]==="Found"){
				    var street = value.address['street'];
					container+= '<li id="fav" class="list-group-item list-group-item-action list-li" brug_lg="'+lg_encours+'" brug_id="'+street.id+'" brug_num="'+value.address['number']+'" brug_street_name="'+street.name+'" brug_postcode="'+street.postCode+'" brug_municipality="'+street.municipality+'" >';
					container+= '<div class="value-list">';

					if(street.name != ""){container+= street.name+" ";}
					if(value.address['number']!=""){container+= value.address['number']+" ";}
					if(street.postCode != ""){container+= street.postCode+" ";}
					if(street.municipality != ""){container+= street.municipality;}

					
					container+= '</div></li>';
				    }
				});
				$(".result-list-address",container_en_cours).html(container).show();
			}else{
				$(".result-list-address",container_en_cours).html("").hide();
			}

			
		}
    });
 });
 
 $(document).off('click', '.list-li').on('click', '.list-li', function(e){
  
	
	var el=$(this);
	var form_commun=$(this).closest("form");
	var is_exist=$(this).closest("form").length;
	     if(is_exist===0){
		 form_commun=$(this).closest(".modal");
		
	     }; 
	///erooro si un est vide, vuillez introudire
	var input_encours=$(this).closest(".c_search_address").find(".search_address");
	var lg_encours=el.attr("brug_lg");
	var number_search=el.attr("brug_num");
	var rue_search=url_slug(el.attr("brug_street_name"));
	var postcode=el.attr("brug_postcode");
	var municipality=url_slug(el.attr("brug_municipality"));
	var brug_id=el.attr("brug_id");
	var result_container = el.closest('.result-list-address');
	result_container.html("").hide();
	var value_choice = $(this).find('.value-list').text();
	input_encours.val(value_choice);
	var adresse = "https://geoservices.irisnet.be/localization/Rest/Localize/getaddressesfields?json={'language':'','address':{'number':'"+number_search+"','street':{'name':'"+rue_search+"','postcode':'"+postcode+"','municipality':'"+municipality+"'}},'spatialReference':'31370'}";
		
	jQuery.ajax({ 
		    type :"POST",
		    url : adresse,
		    dataType : "json",
		    //data : data,

		beforeSend: function(){
		    //submit.append(' <i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i>').fadeIn();
		},

		    success : function(data){
			    console.log(data);
			    var container = "";
			    if(data['status']=="success"){
				    $.each(data['result'], function( index, value ) {

						var street = value.address['street'];

						//alert("street.postcode:"+street.postCode+" postcode:"+postcode);
					
					if(street.postCode==postcode)
					{
					    
					var is_trouve_pas=true;
					var input_a_remplir="adresse_nl_bien";
					
					   
					    
					    var rue=street.name;
					    var id_rue=street.id;
						

					    var address=rue+' '+value.address['number']+' '+street.postCode+' '+street.municipality;
					   
					   $("select[name='adresse_fr_cp']",form_commun).val(street.postCode);
					
					$("select[name='adresse_fr_cp']",form_commun).trigger("chosen:updated");
					       
						
						$(".search_address").each(function() {
						    
						    var field_sql_compare=$(this).val();
						 
						    
						    if(field_sql_compare === address){
							is_trouve_pas = false;
						    }
							
							if(field_sql_compare === ""){
							
							input_a_remplir=$(this).attr("name");
						    }
						    
						    
						});
						
						if(is_trouve_pas){
						    $("input[name='"+input_a_remplir+"']",form_commun).val(address);
						}
						//alert(rue);
					}
					   
				    });
				}
			    }
			    });
    });

	 $(document).off('click', '.list-li2').on('click', '.list-li2', function(e){
	 	
		var container_en_cours=$(this).closest(".c_search_address");
		var super_container=container_en_cours.closest("form");
		
	 	var value_choice = $(this).find('.value-list').text();
		//alert(value);
	 	var input = $('.search_address',container_en_cours);
		var field_sql=input.attr("field_sql");
		
		
		var value_old=input.val();
		
	 	var result_container = $(this).closest('.result-list-address');
	 	input.val(value_choice);
	 	result_container.html("").hide();
		
		var search = value_old;
		search=value_choice;
		search = $.trim(search);

	//alert(search);

		var adresse = "https://geoservices.irisnet.be/localization/Rest/Localize/getaddresses?address="+search+"&language=&spatialReference=31370";
		var id_find="";
		var number_find="";
		var street_find="";
		var rue_search="";
		var number_search="";
		jQuery.ajax({ 
		    type :"POST",
		    url : adresse,
		    dataType : "json",
		    //data : data,

		beforeSend: function(){
		    //submit.append(' <i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i>').fadeIn();
		},

		    success : function(data){
			    console.log(data);
			
			    var container = "";
			    if(data['status']=="success"){
				    $.each(data['result'], function( index, value ) {
					    

					    var street = value.address['street'];
					    
					    var rue=street.name;
					    var id_rue=street.id;
					    rue_search=url_slug(rue);
					      //rue_search=rue.replace(' ', '+').toLower;
					   //alert(rue_search);
					    number_search=value.address['number'];
					    if(value_choice.indexOf(rue) !== -1)
					    {
						number_find=value.address['number'];
						street_find=rue;
						//alert(id_find);
					    }
					   
						var adresse = "https://geoservices.irisnet.be/localization/Rest/Localize/getaddressesfields?json={'language':'','address':{'number':'"+number_search+"','street':{'name':'"+rue_search+"','postcode':''}},'spatialReference':'31370'}";
//alert(adresse);
		//alert(adresse);
		jQuery.ajax({ 
		    type :"POST",
		    url : adresse,
		    dataType : "json",
		    //data : data,

		beforeSend: function(){
		    //submit.append(' <i id="Loading" class="fa fa-refresh fa-spin fa-1x fa-fw"></i>').fadeIn();
		},

		    success : function(data){
			    console.log(data);
			    var container = "";
			    if(data['status']=="success"){
				    $.each(data['result'], function( index, value ) {
					    

					    var street = value.address['street'];
					    
					    var rue=street.name;
					    var id_rue=street.id;
					    
					    if(value_choice.indexOf(rue) == -1)
					    {
						
						$(".search_address").each(function() {
						    
						    var field_sql_compare=$(this).attr("field_sql");
						    if(field_sql_compare!==field_sql)
						    {
							var new_adress_traduc=value.address['number']+' '+rue+' '+street.postCode+' '+street.municipality;
							$(this).val(new_adress_traduc);
						    }	
						    
						    
						});
						
						//alert(rue);
					    }
					   
						
					      /* if(value.address['number']!=""){container+= value.address['number']+" ";}
					    if(street.name != ""){container+= street.name+" ";}
					    if(street.postCode != ""){container+= street.postCode+" ";}
					    if(street.municipality != ""){container+= street.municipality+" ";}


					    container+= '</div></li>';*/
				    });
				    
				    
				 
			    }else{
				    
			    }


		    }
		});
					      /* if(value.address['number']!=""){container+= value.address['number']+" ";}
					    if(street.name != ""){container+= street.name+" ";}
					    if(street.postCode != ""){container+= street.postCode+" ";}
					    if(street.municipality != ""){container+= street.municipality+" ";}


					    container+= '</div></li>';*/
				    });
				    
				    
				 
			    }else{
				    
			    }


		    }
		});
		
				
		
		e.preventDefault();
	 });

});
</script>



