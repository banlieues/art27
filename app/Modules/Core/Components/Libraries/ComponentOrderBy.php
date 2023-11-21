<?php

namespace Components\Libraries;

Class ComponentOrderBy 
{
        private $path="Components\Views\ComponentOrderBy/";

        public function orderTh($fields,$orderBy,$orderDirection,$request=NULL)
        {
            /* Params possible
            * $fields avec array de type [nom du champsql =>label]
            * Orderby= contient le nom du champ utilisé pour le classement
            * OrderDirection = ASC ou DESC
            */

            return view($this->path."OrderTh",
                    [
                        "fields"=>$fields,
                        "orderBy"=>$orderBy,
                        "orderDirection"=>$orderDirection
                    ]
            );
        }

        public function getOrderBy($orderBy, $request=null)
        {
            if($request && !is_null($request->getVar("orderBy")))
            {
                if(!empty($request->getVar("orderBy")))
                {
                    return $request->getVar("orderBy");
                }
                else
                {
                    return $orderBy;
                }
            }
            else
            {
                return $orderBy;  
            } 
        }

        public function getOrderDirection($orderDirection, $request=null)
        {
            if($request && !is_null($request->getVar("orderDirection")) )
            {
                if(!empty($request->getVar("orderDirection")))
                {
                    return $request->getVar("orderDirection");
                }
                else
                {
                    return $orderDirection;
                }
                
            }
            else
            {
                return $orderDirection;  
            } 

        }



}