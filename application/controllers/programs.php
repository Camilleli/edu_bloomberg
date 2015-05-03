<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class programs extends CI_Controller {
	private $db_table = "NewProgrammeInformation";

	public function index()
	{
		 $this->load->model("program");
		 $query = $this->db->query("SELECT PID from NewProgrammeInformation");
		 $model = $this->program->from_db_construct ($query);
		 
		 
		 $this->output->set_content_type('application/json');

		 echo json_encode($model->result());
		 
		 
		 
		 
		 
		 //print_r($model->result());
	}
	public function all()
	{	
		$query = $this->db->get($this->db_table);
		echo json_encode($query->result());
	}
	public function get(){
		if($this->input->get("PID")){
			$query = $this->db->get_where($this->db_table, array('PID' => $this->input->get("PID")));
			echo json_encode($query->result());
		} else {
			echo "fail";
		}
	}
	
	
	/*
	#for new register user
	public function post(){

		$input_data = json_decode($this->input->post("json-data"));
		
		$data_format = [
			'FbId',
			'StuID',
			'StuName',
			'StuGender',
			'StuEmail',
			'FbId',
			'FbToken',
			'FbProfileIcon',
			'Active',
			'Birthday',
			'FirstLoginDate',
			];

		$this->load->library("restful");

		$this->restful->insert_db ($this->db_table, $data_format , $input_data ,"StuID");
		// print_r($input_data->StuName);
	}
	*/
}
