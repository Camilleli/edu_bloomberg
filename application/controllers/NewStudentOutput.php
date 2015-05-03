<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewStudentOutput extends CI_Controller {
	private $db_table = "NewStudentOutput";
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
		if($this->input->get("SOID")){
			$query = $this->db->get_where($this->db_table, array('SOID' => $this->input->get("SOID")));
			echo json_encode($query->result());
		}else{
			echo "fail";
		}

	}
	
}
