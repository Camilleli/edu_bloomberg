<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Students extends CI_Controller {
	private $db_table = "Students";
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
		 $this->load->model("student");
		 $model = $this->student->from_db_construct ("123");
		 print_r($model->FbId);
	}
	public function all()
	{
		$query = $this->db->get($this->db_table);
		echo json_encode($query->result());
	}
	#for get the staude info by StuID
	public function get(){
		if($this->input->get("FbToken")){
			$query = $this->db->get_where($this->db_table, array('FbToken' => $this->input->get("FbToken")));
			echo json_encode($query->result());
		}elseif ($this->input->get("StuID")) {

			$query = $this->db->get_where($this->db_table, array('StuID' => $this->input->get("StuID")));
			echo json_encode($query->result());
		}elseif ($this->input->get("FbId")) {
			$query = $this->db->get_where($this->db_table, array('FbId' => $this->input->get("FbId")));
			echo json_encode($query->result());
		}else{
			echo "fail";
		}

	}
	#for new register user
	public function post(){
		#post to uri sample
		// json-data :
				//  {
		  //   "StuName": "testname",
		  //   "StuGender": "m",
		  //   "StuEmail": "test@gmail.com",
		  //   "StuGrade": "6",
		  //   "FbId": "123",
		  //   "FbToken": "123",
		  //   "FbProfileIcon": "http://icons.iconarchive.com/icons/mazenl77/I-like-buttons-3a/512/Cute-Ball-Go-icon.png",
		  //   "Active": "0",
		  //   "Birthday": "2015-05-02",
		  //   "FirstLoginDate": "2015-05-02"
		  // }
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
}
