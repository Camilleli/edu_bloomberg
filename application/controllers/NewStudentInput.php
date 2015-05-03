<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewStudentInput extends CI_Controller {
	private $db_table = "NewStudentInput";
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

   
	public function index()
	{
		// $query = $this->db->get('StuGradeData');
		// print_r($query->result());
	}
	public function all()
	{
		$this->output->set_content_type('application/json');
		$query = $this->db->get($this->db_table);
		echo json_encode($query->result());
	}
	#for get the staude info by StuID
	public function get(){
		$this->output->set_content_type('application/json');
		if($this->input->get("SIID")){
			$query = $this->db->get_where($this->db_table, array('SIID' => $this->input->get("SIID")));
			echo json_encode($query->result());
		}else{
			echo "fail";
		}

	}
	#for new register user
	public function post(){
		$input_data = json_decode($this->input->post("json-data"));
		$data_format = 	[
		"SIID",
		"InputDate",
		"Fbid",
		"Student_Name",
		"Student_Email",
		"English_Grade",
		"Chinese_Grade",
		"Maths_Grade",
		"LS_Grade",
		"Elective_1",
		"Elective_1_Grade",
		"Elective_2",
		"Elective_2_Grade",
		"Elective_3",
		"Elective_3_Grade",
		"Maths_Extension",
		"Maths_Extension_Grade",
		"Preference_1",
		"Preference_2",
		"Preference_3",
		"Preference_4",
		"Preference_5",
			];
		
		$this->load->library("restful");
		$this->restful->insert_db ($this->db_table, $data_format , $input_data ,null);
	
		// print_r($input_data->StuName);
	}
}
