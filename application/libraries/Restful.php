<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Restful {


    public function insert_db ($table_name, $post_data_format, $input_data , $uuid_field_name)
    {
        $CI =   &get_instance(); 
        $sql_data;
        $query =$CI->db->get($table_name);
        $new_record_id;
        if (!is_null($uuid_field_name) ) {
            # code...
            
            if (count($query->result())==0) {
                $new_record_id =0;
            }else{
                $new_record_id = max(array_map(function($new_record) use($uuid_field_name){ 
                    return $new_record->{$uuid_field_name};
                }, $query->result())) ;
            }
            $input_data->{$uuid_field_name} = ++$new_record_id;
        }
        
        foreach ($post_data_format as $key => $value) {
          $sql_data[$value] = $input_data->{$value} ;
            // echo $input_data->{$value};
      }
      $CI->db->insert($table_name, $sql_data);
      echo "success";
  }
}

?>