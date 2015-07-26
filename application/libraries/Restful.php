<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Restful {
    private $CI;
    function __construct()
    {
        // Assign by reference with "&" so we don't create a copy
        $this->CI = &get_instance();
    }
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
      return $new_record_id;
  }
  public function update_db ($table_name, $post_data_format, $input_data , $uuid_field_name, $record_id)
    {

        $CI =   &get_instance(); 
        $sql_data;
        foreach ($post_data_format as $key => $value) {
          $sql_data[$value] = $input_data->{$value} ;
            // echo $input_data->{$value};
      }
      $CI->db->where($uuid_field_name, $record_id);
      $CI->db->update($table_name, $sql_data);
      return $sql_data{$uuid_field_name};
  }
  public function check_fb_token_valid($token){

        $this->CI->load->library("curl");
        $json = $this->CI->curl->simple_get("https://graph.facebook.com/me?access_token=".$token);
        if(strlen($json) == 0){
           return false;
        }else{
           return true;
        }
  }

  public function json_response($array){

      header('Content-Type: application/json');
      $result = array_merge( array("status"=>"success"), $array);
      echo json_encode($result);

  }
  
}

?>