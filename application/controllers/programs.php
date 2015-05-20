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

		public function all(){
			$this->db->query("SELECT Program.PName AS PName, Student.PID, Student.GDChi, Student.GDEng, Student.GDMCore, 
	Student.GDMExt, Student.GDLB, Student.GDE1, Student.GDE2, Student.GDE3, Program.EnglishWeight AS EW, Program.ChineseWeight AS CW,
	Program.MathsWeight AS MW, Program.LSWeight AS LW
	FROM stugradedata AS Student
	INNER JOIN newprogrammeinformation AS Program
	On Student.pid = Program.pid");
		}
		
		
		
	}
