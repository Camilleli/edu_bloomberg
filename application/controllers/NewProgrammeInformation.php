<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewProgrammeInformation extends CI_Controller {
	private $db_table = "NewProgrammeInformation";
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
		$query = $this->db->get($this->db_table);
		echo json_encode($query->result());
	}
	#for get the staude info by StuID
	public function get(){
		if($this->input->get("PID")){
			$query = $this->db->get_where($this->db_table, array('PID' => $this->input->get("PID")));
			echo json_encode($query->result());
		}else{
			echo "fail";
		}

	}
	#for new register user
	
}
