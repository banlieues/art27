<?php

if(! function_exists('clone_object')) :
    function clone_object($data)
    {
        if(is_object($data)) :
            $data = clone $data;
            foreach($data as $k=>$v) :
                $data->$k = clone_object($v);
            endforeach;
        elseif(is_array($data)) :
            foreach($data as $k=>$v) :
                $data[$k] = clone_object($v);
            endforeach;
        endif;

        return $data;
    }
endif;

if(! function_exists('object_key_exists')) :
    function object_key_exists($key, $object)
    {
        return array_key_exists($key, (array) $object);
    }
endif;

if(! function_exists('implode_array_of_objects')) :
    function implode_array_of_objects($delimiter, $array, $keyname)
    {
        // $array_to_keep = [];
        // foreach($array as $object):
        //     if(!empty($object->$keyname)) $array_to_keep[] = $object->$keyname;
        // endforeach;
        // $result = implode($delimiter, $array_to_keep);

        $arrayKey = array_column($array, $keyname);
        $result = implode($delimiter, $arrayKey);

        return $result;
    }
endif;

if(! function_exists('object_keys')) :
    function object_keys($object)
    {
        $array = (array) $object;

        return array_keys($array);
    }
endif;

if(! function_exists('object_merge')) :
    function object_merge($object_1, $object_2)
    {
        $array_1 = (array) $object_1;
        $array_2 = (array) $object_2;

        return (object) array_merge($array_1, $array_2);
    }
endif;

if(! function_exists('object_filter')) :
    function object_filter($object)
    {
        return (object) array_filter((array) $object);
    }
endif;

if (! function_exists('array_of_objects_diff')) :
    function array_of_objects_diff($array_of_objects_1, $array_of_objects_2)
    {
        $array_of_objects = [];
        foreach($array_of_objects_1 as $object_1):
            $k = 0;
            foreach($array_of_objects_2 as $object_2):
                if($object_1 == $object_2) $k=1;
            endforeach;
            if($k == 0) $array_of_objects[] = $object_1;
        endforeach;
        foreach($array_of_objects_2 as $object_2):
            $k = 0;
            foreach($array_of_objects_1 as $object_1):
                if($object_2 == $object_1) $k=1;
            endforeach;
            if($k == 0) $array_of_objects[] = $object_2;
        endforeach;

        $array_of_objects = array_values(array_unique($array_of_objects, SORT_REGULAR));

        return $array_of_objects;
    }
endif;

if (! function_exists('array_of_objects_unique')) :
    function array_of_objects_unique($array_of_objects)
    {
        $array_of_object1 = $array_of_objects;
        $array_of_object2 = $array_of_objects;
        $i = 0;
        foreach($array_of_object1 as $object1) :
            $j = 0;
            foreach($array_of_object2 as $object2) :
                if($j<$i && $object1==$object2) :
                    unset($array_of_objects[$j]);
                endif;
                $j++;
            endforeach;
            $i++;
        endforeach;

        return array_values(array_filter($array_of_objects));
    }
endif;

if(! function_exists('object_convert')) :
    function object_convert($elem)
    {
        if(!empty($elem) && is_array($elem)):
            if(isAssociativeArray((array) $elem)) :
                $data = (object) [];
                foreach($elem as $key=>$child) :
                    $data->$key = object_convert($child);
                endforeach;
                $elem = $data;
            else :
                $data = [];
                foreach($elem as $child) :
                    $data[] = object_convert($child);
                endforeach;
                $elem = $data;
            endif;
        endif;

        return $elem;
    }

    function isAssociativeArray(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
endif;