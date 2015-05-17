	<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Programs extends CI_Controller {
		private $db_table = "NewProgrammeInformation";

		public function index()
		{
			 
		}

		public function all_result(){
			header('Content-Type: application/json');
			$formula = $this->input->get("formula");
			if ($formula == "4c1") {
				$this->load->model("program");
			 $query = $this->db->query("SELECT PID from NewProgrammeInformation");
			 $result = $this->program->fourCPlusOne ($query);
			 echo json_encode($result->result());
			
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
