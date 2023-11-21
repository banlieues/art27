<?php

if(! function_exists('database_decode')) :
    function database_decode($data)
    {
        if(is_array($data) && isAssociativeArray($data)) :
            $data = (object) $data;
            foreach($data as $key=>$value) :
                $data->$key = database_decode($value);
            endforeach;
        elseif(is_array($data)) :
            $i=0;
            foreach($data as $value) :
                $data[$i] = database_decode($value);
                $i++;
            endforeach;
        elseif(is_object($data)) :
            foreach($data as $key=>$value) :
                $data->$key = database_decode($value);
            endforeach;
        elseif(is_json($data)) :
            $data = database_decode(json_decode($data));
        endif;

        return $data;
    }
endif;

if (! function_exists('get_nullable_fields')) :
    function get_nullable_fields($file)
    {
        $fields = json_decode(file_get_contents($file));
        $nullable_fields = [];
        foreach($fields as $key=>$metadata) :
            if(isset($metadata->default) && $metadata->default == 'null' && $metadata->type != 'date') $nullable_fields[] = $key;
        endforeach;

        return $nullable_fields;
    }
endif;

if (! function_exists('database_encode')) :
    function database_encode($table, $data, $nullable_fields=null)
    {
        if(is_array($data)) $data = (object) $data;

        // $is_array = 0;
        // if(is_array($data)) :
        //     $is_array = 1;
        //     $data = (object) $data;
        // endif;

        $db = db_connect();

        $fields = $db->getFieldNames($table);
        $pk_label = get_primary_key($table);

        $post = (object) [];
        foreach($data as $key=>$value) :
            if($key==$pk_label && is_primary_key_auto_increment($table)) continue;
            if(in_array($key, $fields)) :
                // debug($key);
                if(is_array($value)) :
                    if(empty($value)) $post->$key = null;
                    else $post->$key = json_encode($value, JSON_NUMERIC_CHECK);
                elseif(is_object($value)) :
                    if(empty((array) $value)) $post->$key = null;
                    else $post->$key = json_encode($value);
                elseif(is_string($value)) :
                    if(isset($value)) :
                        if(trim($value) == '') :
                            $post->$key = null;
                        else :
                            $post->$key = trim($value);
                        endif;
                    else :
                        $post->$key = null;
                    endif;
                elseif(is_numeric($value)) :
                    $post->$key = $value;
                else :
                    if(!empty($value)) :
                        debug('database_encode -> case unrecognized');
                        debug($key);
                        dd($value);
                        $post->$key = $value;
                    else :
                        $post->$key = null;
                    endif;
                endif;
            // elseif(empty($data->$field) && !empty($nullable_fields) && in_array($field, $nullable_fields)) :
            //     $post->$field = null;
            // else :
            //     // exclude to post
            endif;
        endforeach;

        return (array) $post;

        // if($is_array == 1) $post = (array) $post;

        // return $post;
    }
endif;

if (! function_exists('filter_post_by_table_fields')) :
    function filter_post_by_table_fields($table, $post)
    {
        $is_array = 0;
        if(is_array($post)) $is_array = 1;
        if($is_array==1) $post = (object) $post;

        $CI = get_instance();
        $fields = $CI->db->field_data($table);
        $data = (object) [];
        foreach($fields as $field) :
            if($field->primary_key!=1 && isset($post->{$field->name})) $data->{$field->name} = $post->{$field->name};
        endforeach;

        if($is_array==1) $data = (array) $data;

        return $data;
    }
endif;

if (! function_exists('get_primary_key')) :
    function get_primary_key($table)
    {
        $db = db_connect();
        $fields = $db->getFieldData($table);

        foreach($fields as $field) if($field->primary_key==1) return $field->name;
    }
endif;

if (! function_exists('is_primary_key_auto_increment')) :
    function is_primary_key_auto_increment($table)
    {
        $db = db_connect();
        $fields = $db->getFieldData($table);

        foreach($fields as $field) :
            if($field->primary_key==1 && $field->type=='int') return true;
            else return false;
        endforeach;
    }
endif;
