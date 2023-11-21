<?php
function sql_orderByDirection($orderBy,$orderDirection)
{
    $orderByExplode=explode(",",$orderBy);
    $orderFusion=[];

    if(!empty($orderByExplode))
    {
        foreach($orderByExplode as $order)
        {
            if(!empty(trim($order)))
            {
                array_push($orderFusion,trim($order)." ".trim($orderDirection));
            }
            
        }

        if(!empty($orderFusion))
        {
            return implode(",",$orderFusion);
        }
        else
        {
            return $orderBy.' '.$orderDirection;
        }
        
    }
    

    
}

function contruct_label_sql($label)
{
    if(strpos($label, ',') !== false)
    {

        //nom,prenom
        $l="CONCAT( ";
        $l.=str_replace(","," ,' ',",$label);

        $l.=") ";

        return $l;
    }
    else
    {
        return $label;
    }
}

