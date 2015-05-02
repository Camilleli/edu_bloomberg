<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Students extends CI_Controller {

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
		$query = $this->db->get('Students');
		print_r($query->result());
	}
	public function all()
	{
		$query = $this->db->get('Students');
		echo json_encode($query->result());
	}
	#for get the staude info by StuID
	public function get(){
		if($this->input->get("FbToken")){
			$query = $this->db->get_where('Students', array('FbToken' => $this->input->get("FbToken")), 1, 1);
			echo json_encode($query->result());
		}elseif ($this->input->get("StuID")) {
			$query = $this->db->get_where('Students', array('StuID' => $this->input->get("StuID")), 1, 1);
			echo json_encode($query->result());
		}elseif ($this->input->get("FbId")) {
			$query = $this->db->get_where('Students', array('FbId' => $this->input->get("FbId")), 1, 1);
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
		$query = $this->db->get('Students');

		$new_record_id = max(array_map(function($new_record) { 
			return $new_record->StuID;
		}, $query->result()));
		$data = array(
			'StuID' => ++$new_record_id,
			'StuName' => $input_data->StuName ,
			'StuGender' => $input_data->StuGender ,
			'StuEmail' =>$input_data->StuEmail,
			'StuGrade' => $input_data->StuGrade,
			'FbId' => $input_data->FbId,
			'FbToken' => $input_data->FbToken,
			'FbProfileIcon' => $input_data->FbProfileIcon,
			'Active' => $input_data->Active,
			'Birthday' => $input_data->Birthday,
			'FirstLoginDate'=> $input_data->FirstLoginDate
			);

		$this->db->insert('Students', $data); 
		echo "success";
		// print_r($input_data->StuName);
	}
}
