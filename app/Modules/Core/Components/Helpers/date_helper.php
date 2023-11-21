<?php
/* functions 
    convert_date_fr_to_en: convert date fr to en
    convert_date_en_to_fr: convert date en to fr
    convert_date_en_to_fr_with_h: convert date fr to en with hour, minute and second
    date_now_sql: date en now
    annee_now: year now
    calcul_age: calculate age from annee now, format years and month in option
    calul_age_from: calculate from a date, format years and month in option
*/

if ( ! function_exists('month_label_from_number'))
{
    function month_label_from_number($i)
    {
        $fmt = new \IntlDateFormatter('fr_FR', null, null);
        $fmt->setPattern('MMMM');
        
        return ucfirst($fmt->format(\DateTime::createFromFormat('!m', $i))); 
    }
}

if ( ! function_exists('month_list_object'))
{
    function month_list_object($id='id', $label='label')
    {
        $datas = [];
        for($i=1; $i<=12; $i++) :
            $data = (object) [];
            $data->$id = $i;
            $data->$label = month_label_from_number($i);
            $datas[] = $data;
        endfor;

        return $datas;
    }
}

if ( ! function_exists('convert_date_fr_to_en'))
{
    function convert_date_fr_to_en($date)
    {
        if(is_valid_date_sql($date))
        {
            return $date;
        }
        if(!empty($date)):
            $tab = explode('/', $date);
            if(isset($tab[2])&&isset($tab[1])&&isset($tab[0])):
                $date_en = $tab[2].'-'.$tab[1].'-'.$tab[0];
                return $date_en;
            else:
                return NULL;
            endif;
        else:
            return NULL;
        endif;
    }
}

if ( ! function_exists('convert_date_en_to_fr'))
{
    function convert_date_en_to_fr($date)
    {
        if($date=="0000-00-00"): return NULL; endif;




        if(strpos($date, "-")===FALSE || strlen($date)!=10):
                if(!empty($date)):
                    return $date;
                else:
                    return NULL;
                endif;
	    else:
	        if(!empty($date)):
		        $d = new datetime($date);
		        $date_fr = $d->format('d/m/Y');
		        return $date_fr;  
	        else:
		        return NULL;
	        endif;
	    endif;
    }
}


function is_valid_date_sql($date,$format = 'Y-m-d')
{
    $dt = DateTime::createFromFormat($format, $date);
    return $dt && $dt->format($format) === $date;
}

if ( ! function_exists('convert_date_en_to_fr_with_h'))
{
    function convert_date_en_to_fr_with_h($date, $with_h=TRUE, $with_s=true)
    {
        if($date=="0000-00-00 00:00:00"): return NULL; endif;
        if(is_null($date)): return NULL; endif;
        
	    if(!empty($date)):
            if(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',$date))
                $is_convert=TRUE;

            if(preg_match('/\d{4}-\d{2}-\d{2}/',$date))
                $is_convert=TRUE;  
                  
               if(isset($is_convert))
               { 
                    $d = new datetime($date);
                    if($with_h):
                        if($with_s==true):
                            $date_fr = str_replace(" ","&nbsp;", $d->format('d/m/Y à H:i:s'));
                        else:
                            $date_fr = str_replace(" ","&nbsp;", $d->format('d/m/Y à H:i'));    
                        endif;
                    else:
                        $date_fr = str_replace(" ","&nbsp;", $d->format('d/m/Y'));    
                    endif;

                    if($with_h!==true) $date_fr = str_replace("à", $with_h, $date_fr);

                    return $date_fr;  
                }
                else
                {
                    return $date;
                }
	    else:
	        return NULL;
	    endif;
    }

}

function convert_date_en_withouth($date)
{
    if($date=="0000-00-00 00:00:00"): return NULL; endif;

    

    if(!empty($date)):
        if(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',$date))
            $is_convert=TRUE;

        if(preg_match('/\d{4}-\d{2}-\d{2}/',$date))
            $is_convert=TRUE;  
              
           if(isset($is_convert))
           { 
                $d = new datetime($date);
               
                    $date = $d->format('Y/m/d');    
               


                return $date;  
            }
            else
            {
                return $date;
            }
    else:
        return NULL;
    endif;
}

if ( ! function_exists('date_only_h_m'))
{
    function date_only_h_m($date)
    {
        if($date=="0000-00-00 00:00:00"): return NULL; endif;

        

	    if(!empty($date)):
            if(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',$date))
                $is_convert=TRUE;

            if(preg_match('/\d{4}-\d{2}-\d{2}/',$date))
                $is_convert=TRUE;  
                  
               if(isset($is_convert))
               { 
                    $d = new datetime($date);
                    $date_fr = $d->format('H:i');


                    return $date_fr;  
                }
                else
                {
                    return $date;
                }
	    else:
	        return NULL;
	    endif;
    }

}


if ( ! function_exists('date_now_sql'))
{
	function date_now_sql()
	{
        $datestring = "%Y-%m-%d";
        $time = time();

        return mdate($datestring, $time);
	}
}



if ( ! function_exists('annee_now_sql'))
{
	function annee_now_sql()
	{
        $datestring = "%Y";
        $time = time();
        return mdate($datestring, $time);
	}
}


if ( ! function_exists('calcul_age'))
{
    function calcul_age($naiss, $with_month=FALSE)
    {
        if($naiss=="0000-00-00"||is_null($naiss)): return NULL; endif;
        $datetime1 = new DateTime();
        $datetime2 = new DateTime($naiss);
        $age = $datetime1->diff($datetime2);
        if($age->format('%y')==0&&$age->format('%m')==0): return NULL; endif;
        if($with_month):
            return  $age->format('%y'). " ans "." ".$age->format('%m'). " mois";
        else:
            return  $age->format('%y'). " ans";
        endif;
    }
}

if ( ! function_exists('calcul_age_from'))
{
    function calcul_age_from($naiss,$creation,$with_month=FALSE)
    {
        if(empty($naiss)||empty($creation)||$naiss=="0000-00-00"||$creation=="0000-00-00") return "---";
        $datetime1 = new DateTime($creation);
        $datetime2 = new DateTime($naiss);
        $age = $datetime1->diff($datetime2);
        if($age->format('%y')==0&&$age->format('%m')==0): return "----"; endif;
        if($with_month):
            return  $age->format('%y'). " ans "." ".$age->format('%m'). " mois";
        else:
            return  $age->format('%y'). " ans ";
        endif;
        
    }
}

function adjust_gmt($datetime) 
{
    date_default_timezone_set('Europe/Brussels');
    $ts = strtotime($datetime . " GMT");
    return strftime("%Y-%m-%d %H:%M:%S", $ts);
}
    
function adjust_gmt_calendar($datetime) 
{
    date_default_timezone_set('Europe/Brussels');
    $ts = strtotime($datetime . " GMT");
    return strftime("%Y-%m-%dT%H:%M:%S", $ts);
}

function date_log($date_insert,$date_modification,$user_create,$user_modification)
{
    
    $view=NULL;

    $date_insert=convert_date_en_to_fr_with_h($date_insert);
    $date_modification=convert_date_en_to_fr_with_h($date_modification);

    if(!empty($date_insert))
    {

        $view.='<div class="text-align-left my-2">';
            $view.='<small>';
                $view.='<i>';
                    $view.='<strong>Date de création: </strong>';
                    $view.=$date_insert;
                    $view.=" <strong>par</strong> ";
                    $view.=$user_create;
                    if(!empty($date_modification))
                    {
                        $view.=' | ';
                        $view.='<strong>Dernière Mise à jour: </strong>';
                        $view.=$date_modification;
                        $view.=" <strong>par</strong> ";
                        $view.=$user_modification;
                    }
                
                $view.='</i>';
            $view.='</small>';
        $view.='</div>';
    }
   
    return $view;
    
}
    