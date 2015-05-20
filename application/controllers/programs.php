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
			header('Content-Type: application/json');
			$course_id = $this->input->get("course_id");
			$inject_sql ="";
			if (!is_null($course_id)) {
				$inject_sql = "where Student.PID = $course_id";
			}
			$this->output->set_content_type('application/json');
			$query = $this->db->query('SELECT Program.PName AS PName, Student.PID, Student.GDChi, Student.GDEng, Student.GDMCore, 
				Student.GDMExt, Student.GDLB, Student.GDE1, Student.GDE2, Student.GDE3, Program.EnglishWeight AS EW, Program.ChineseWeight AS CW,
				Program.MathsWeight AS MW, Program.LSWeight AS LW
				FROM StuGradeData AS Student
				INNER JOIN NewProgrammeInformation AS Program
				On Student.pid = Program.pid '.$inject_sql);
			foreach ($query->result() as $key => $row) {

				if ( $row->GDMCore < $row->GDMExt ){	        // If MathsExtension is higher, use MathsExtension as Maths Grade
					if ( $row->GDE1 >=  $row->GDE2 && $row->GDE1 >=  $row->GDE3) {
						$cal_4C1 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1, 2, '.', '');
					} else if  ( $row->GDE2 >=  $row->GDE1 && $row->GDE2 >=  $row->GDE3) {
						$cal_4C1 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE2, 2, '.', '');
					} else if ( $row->GDE3 >=  $row->GDE1 && $row->GDE3 >=  $row->GDE2) {
						$cal_4C1 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE3, 2, '.', '');
					}
				} else {
					if ( $row->GDE1 >=  $row->GDE2 && $row->GDE1 >=  $row->GDE3) {
						$cal_4C1 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1, 2, '.', '');
					} else if  ( $row->GDE2 >=  $row->GDE1 && $row->GDE2 >=  $row->GDE3) {
						$cal_4C1 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE2, 2, '.', '');
					} else if ( $row->GDE3 >=  $row->GDE1 && $row->GDE3 >=  $row->GDE2) {
						$cal_4C1 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE3, 2, '.', '');
					}
				}

				$row->cal_4C1 = $cal_4C1;
				$dec_4C1 = number_format( $cal_4C1 / ($row->EW + $row->CW +$row->MW + $row->LW + 1 ), 2, '.', '');
				$row->dec_4C1 = $dec_4C1;

				if ( $row->GDMCore < $row->GDMExt ){	
					if ( $row->GDE1 <=  $row->GDE2 && $row->GDE1 <=  $row->GDE3) {
						$cal_4C2 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE2 + $row->GDE3, 2, '.', '');
					} else if  ( $row->GDE2 <=  $row->GDE1 && $row->GDE2 <=  $row->GDE3) {
						$cal_4C2 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE3, 2, '.', '');
					} else if ( $row->GDE3 <=  $row->GDE1 && $row->GDE3 <=  $row->GDE2) {
						$cal_4C2 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
					}
				} else {
					if ( $row->GDE1 <=  $row->GDE2 && $row->GDE1 <=  $row->GDE3) {
						$cal_4C2 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE2 + $row->GDE3, 2, '.', '');
					} else if  ( $row->GDE2 <=  $row->GDE1 && $row->GDE2 <=  $row->GDE3) {
						$cal_4C2 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE3, 2, '.', '');
					} else if ( $row->GDE3 <=  $row->GDE1 && $row->GDE3 <=  $row->GDE2) {
						$cal_4C2 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
					}
				}
				$row->cal_4C2 = $cal_4C2;
				$dec_4C2 = number_format( $cal_4C2 / ($row->EW + $row->CW +$row->MW + $row->LW + 2 ), 2, '.', '');
				$row->dec_4C2 = $dec_4C2;

			}
			if ( $row->GDMCore < $row->GDMExt ) {

				if ( 	 $row->GDEng <= $row->GDChi && $row->GDEng <= $row->GDMExt && $row->GDEng <= $row->GDLB && $row->GDEng <= $row->GDE1 && $row->GDEng <= $row->GDE2 && $row->GDEng <= $row->GDE3) {                
					if ( $row->GDChi <= $row->GDMExt && $row->GDChi <= $row->GDLB && $row->GDChi <= $row->GDE1 && $row->GDChi <=$row->GDE2 && $row->GDChi <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->MW + $row->LW + 1 + 1+ 1), 2, '.', '');
					}			
					else if ($row->GDMExt <= $row->GDChi && $row->GDMExt <= $row->GDLB && $row->GDMExt <= $row->GDE1 && $row->GDMExt <= $row->GDE2 && 	$row->GDMExt <= $row->GDE3) {
						$cal_Best5 = number_format( $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->LW + 1 + 1+ 1), 2, '.', '');
					}
					else if ($row->GDLB <= $row->GDChi && $row->GDLB <= $row->GDMExt && $row->GDLB <= $row->GDE1 && $row->GDLB <= $row->GDE2 && $row->GDLB <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDChi * $row->CW + $row->GDMExt * $row->MW + $row->GDE1 + $row->GDE2+ $row->GDE3 , 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + 1 + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE1 <= $row->GDChi && $row->GDE1 <= $row->GDMExt && $row->GDE1 <= $row->GDLB && $row->GDE1 <= $row->GDE2 && $row->GDE1 <= $row->GDE3) {
						$cal_Best5 = number_format( $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE2 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE2 <= $row->GDChi && $row->GDE2 <= $row->GDMExt && $row->GDE2 <= $row->GDLB && $row->GDE2 <= $row->GDE1 && $row->GDE2 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE3 <= $row->GDChi && $row->GDE3 <= $row->GDMExt && $row->GDE3 <= $row->GDLB && $row->GDE3 <= $row->GDE1 && $row->GDE3 <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
				}
				else if ( $row->GDChi <= $row->GDEng && $row->GDChi <= $row->GDMExt && $row->GDChi <= $row->GDLB && $row->GDChi <= $row->GDE1 && $row->GDChi <= $row->GDE2 && $row->GDChi <= $row->GDE3) {
					if ( $row->GDEng <= $row->GDMExt && $row->GDEng <= $row->GDLB && $row->GDEng <= $row->GDE1 && $row->GDEng <=$row->GDE2 && $row->GDEng <= $row->GDE3) { 
						$cal_Best5 = number_format($row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->MW + $row->LW + 1 + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDMExt <= $row->GDEng && $row->GDMExt <= $row->GDLB && $row->GDMExt <= $row->GDE1 && $row->GDMExt <= $row->GDE2 && $row->GDMExt <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->LW + 1 + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDLB <= $row->GDEng && $row->GDLB <= $row->GDMExt && $row->GDLB <= $row->GDE1 && $row->GDLB <= $row->GDE2 && $row->GDLB <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDMExt * $row->MW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + 1 + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE1 <= $row->GDEng && $row->GDE1 <= $row->GDMExt && $row->GDE1 <= $row->GDLB && $row->GDE1 <= $row->GDE2 && $row->GDE1 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE2 <= $row->GDEng && $row->GDE2 <= $row->GDMExt && $row->GDE2 <= $row->GDLB && $row->GDE2 <= $row->GDE1 && $row->GDE2 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 +  $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}	
					else if ($row->GDE3 <= $row->GDEng && $row->GDE3 <= $row->GDMExt && $row->GDE3 <= $row->GDLB && $row->GDE3 <= $row->GDE1 && $row->GDE3 <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
				}
				else if ($row->GDMExt <= $row->GDEng && $row->GDMExt <= $row->GDChi && $row->GDMExt <= $row->GDLB && $row->GDMExt <= $row->GDE1 && $row->GDMExt <= $row->GDE2 && $row->GDMExt <= $row->GDE3) {
					if ($row->GDChi <= $row->GDEng && $row->GDChi <= $row->GDLB && $row->GDChi <= $row->GDE1 && $row->GDChi <= $row->GDE2 && $row->GDChi <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->LW + 1 + 1+ 1), 2, '.', '');
					}
					else if ($row->GDEng <= $row->GDChi && $row->GDEng <= $row->GDLB && 	$row->GDEng <= $row->GDE1 && $row->GDEng <= $row->GDE2 && 	$row->GDEng <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->LW + 1 + 1+ 1), 2, '.', '');
					}
					else if ($row->GDLB <= $row->GDChi && $row->GDLB <= $row->GDEng && $row->GDLB <= $row->GDE1 && $row->GDLB <= $row->GDE2 && $row->GDLB <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + 1 + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE1 <= $row->GDChi && $row->GDE1 <= $row->GDEng && $row->GDE1 <= $row->GDLB && $row->GDE1 <= $row->GDE2 && $row->GDE1 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE2 <= $row->GDChi && $row->GDE2 <= $row->GDEng && $row->GDE2 <= $row->GDLB && $row->GDE2 <= $row->GDE1 && $row->GDE2 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE3 <= $row->GDChi && $row->GDE3 <= $row->GDEng && $row->GDE3 <= $row->GDLB && $row->GDE3 <= $row->GDE1 && $row->GDE3 <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->LW + 1+ 1), 2, '.', '');
					}
				}
				else if ( $row->GDLB <= $row->GDEng && $row->GDLB <= $row->GDChi && $row->GDLB <= $row->GDMExt && $row->GDLB <= $row->GDE1 && $row->GDLB <= $row->GDE2 && $row->GDLB <= $row->GDE3) {
					if ( $row->GDChi <= $row->GDEng && $row->GDChi <= $row->GDMExt && $row->GDChi <= $row->GDE1 && $row->GDChi <=$row->GDE2 && $row->GDChi <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW  +$row->GDMExt * $row->MW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + 1 + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDEng <= $row->GDChi && $row->GDEng <= $row->GDMExt && $row->GDEng <= $row->GDE1 && $row->GDEng <= $row->GDE2 && $row->GDEng <= $row->GDE3) {
						$cal_Best5 = number_format( $row->GDChi * $row->CW + $row->GDMExt * $row->MW + $row->GDE1 + $row->GDE2+  $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + 1 + 1+ 1), 2, '.', '');

					}
					else if ( $row->GDMExt <= $row->GDChi && $row->GDMExt <= $row->GDEng && $row->GDMExt <= $row->GDE1 && $row->GDMExt <= $row->GDE2 && $row->GDMExt <= $row->GDE3) 	{ 
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + 1 + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDE1 <= $row->GDChi && $row->GDE1 <= $row->GDEng && $row->GDE1 <= $row->GDMExt && $row->GDE1 <= $row->GDE2 && $row->GDE1 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE2 <= $row->GDChi && $row->GDE2 <= $row->GDEng && $row->GDE2 <= $row->GDMExt && $row->GDE2 <= $row->GDE1 && $row->GDE2 <= $row->GDE3) 	{
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE3 <= $row->GDChi && $row->GDE3 <= $row->GDEng && $row->GDE3 <= $row->GDMExt && $row->GDE3 <= $row->GDE1 && $row->GDE3 <= $row->GDE2) 	{
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDMExt * $row->MW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + 1+ 1), 2, '.', '');
					}
				}
				else if ( $row->GDE1 <= $row->GDEng && $row->GDE1 <= $row->GDChi && $row->GDE1 <= $row->GDMExt && $row->GDE1 <= $row->GDLB && $row->GDE1 <= $row->GDE2 && $row->GDE1 <= $row->GDE3) {
					if ( $row->GDChi <= $row->GDEng && $row->GDChi <= $row->GDMExt && $row->GDChi <= $row->GDLB && 	$row->GDChi <=$row->GDE2 && $row->GDChi <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDEng <= $row->GDChi && $row->GDEng <= $row->GDMExt && $row->GDEng <= $row->GDLB && $row->GDEng <= $row->GDE2 && $row->GDEng <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDMExt <= $row->GDChi && $row->GDMExt <= $row->GDEng && $row->GDMExt <= $row->GDLB && $row->GDMExt <= $row->GDE2 && $row->GDMExt <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDLB <= $row->GDChi && $row->GDLB <= $row->GDEng && $row->GDLB <= $row->GDMExt && $row->GDLB <= $row->GDE2 && 	$row->GDLB <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW+ $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDE2 <= $row->GDChi && $row->GDE2 <= $row->GDEng && $row->GDE2 <= $row->GDMExt && $row->GDE2 <= $row->GDLB && $row->GDE2 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + $row->LW+ 1), 2, '.', '');
					}
					else if ($row->GDE3 <= $row->GDChi && $row->GDE3 <= $row->GDEng && $row->GDE3 <= $row->GDMExt && $row->GDE3 <= $row->GDLB && $row->GDE3 <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + $row->LW+ 1), 2, '.', '');
					}
				}
				else if ( $row->GDE2 <= $row->GDEng && $row->GDE2 <= $row->GDChi && $row->GDE2 <= $row->GDMExt && $row->GDE2 <= $row->GDLB && $row->GDE2 <= $row->GDE1 && $row->GDE2 <= $row->GDE3) {	
					if ( $row->GDChi <= $row->GDEng && $row->GDChi <= $row->GDMExt && $row->GDChi <= $row->GDLB && $row->GDChi <=$row->GDE1 && $row->GDChi <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + $row->LW+ 1 + 1), 2, '.', '');
					}
					else if ( $row->GDEng <= $row->GDChi && $row->GDEng <= $row->GDMExt && $row->GDEng <= $row->GDLB && $row->GDEng <= $row->GDE1 && $row->GDEng <= $row->GDE3) {
						$cal_Best5 = number_format( $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + $row->LW+ 1 +1), 2, '.', '');
					}
					else if (	$row->GDMExt <= $row->GDChi && $row->GDMExt <= $row->GDEng && $row->GDMExt <= $row->GDLB && $row->GDMExt <= $row->GDE1 && $row->GDMExt <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW  + $row->LW+ 1 +1), 2, '.', '');
					}
					else if ( $row->GDLB <= $row->GDChi && $row->GDLB <= $row->GDEng && $row->GDLB <= $row->GDMExt && $row->GDLB <= $row->GDE1 && $row->GDLB <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + 1 + 1), 2, '.', '');
					}
					else if ( $row->GDE1 <= $row->GDChi && $row->GDE1 <= $row->GDEng && $row->GDE1 <= $row->GDMExt && $row->GDE1 <= $row->GDLB && $row->GDE1 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + $row->LW+ 1), 2, '.', '');
					}
					else if ( $row->GDE3 <= $row->GDChi && $row->GDE3 <= $row->GDEng && $row->GDE3 <= $row->GDMExt && $row->GDE3 <= $row->GDLB && 	$row->GDE3 <= $row->GDE1) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + $row->LW+ 1), 2, '.', '');
					}
				}
				else if ( $row->GDE3 <= $row->GDEng && $row->GDE3 <= $row->GDChi && $row->GDE3 <= $row->GDMExt && $row->GDE3 <= $row->GDLB && $row->GDE3 <= $row->GDE1 && $row->GDE3 <= $row->GDE2) {	
					if ( $row->GDChi <= $row->GDEng && $row->GDChi <= $row->GDMExt && $row->GDChi <= $row->GDLB && $row->GDChi <=$row->GDE1 && 	$row->GDChi <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + $row->LW+ 1+1), 2, '.', '');
					}
					else if ( $row->GDEng <= $row->GDChi && $row->GDEng <= $row->GDMExt && $row->GDEng <= $row->GDLB && $row->GDEng <= $row->GDE1 && $row->GDEng <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + $row->LW+ 1 +1), 2, '.', '');
					}
					else if ($row->GDMExt <= $row->GDChi && $row->GDMExt <= $row->GDEng && $row->GDMExt <= $row->GDLB && $row->GDMExt <= $row->GDE1 && $row->GDMExt <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->LW+ 1 +1), 2, '.', '');
					}
					else if ( $row->GDLB <= $row->GDChi && 	$row->GDLB <= $row->GDEng && $row->GDLB <= $row->GDMExt && $row->GDLB <= $row->GDE1 && $row->GDLB <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + 1+ 1), 2, '.', '');

					}
					else if ( $row->GDE2 <= $row->GDChi && $row->GDE2 <= $row->GDEng && $row->GDE2 <= $row->GDMExt && $row->GDE2 <= $row->GDLB && $row->GDE2 <= $row->GDE1) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + $row->LW+ 1), 2, '.', '');
					}
					else if ( $row->GDE1 <= $row->GDChi && $row->GDE1 <= $row->GDEng && $row->GDE1 <= $row->GDMExt && $row->GDE1 <= $row->GDLB && $row->GDE1 <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMExt * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + $row->LW+ 1), 2, '.', '');
					}
				}




			} else if ($row->GDMCore >=  $row->GDMExt){

				if ( 	 $row->GDEng <= $row->GDChi && $row->GDEng <= $row->GDMCore && $row->GDEng <= $row->GDLB && $row->GDEng <= $row->GDE1 && $row->GDEng <= $row->GDE2 && $row->GDEng <= $row->GDE3) {                
					if ( $row->GDChi <= $row->GDMCore && $row->GDChi <= $row->GDLB && $row->GDChi <= $row->GDE1 && $row->GDChi <=$row->GDE2 && $row->GDChi <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->MW + $row->LW + 1 + 1+ 1), 2, '.', '');
					}			
					else if ($row->GDMCore <= $row->GDChi && $row->GDMCore <= $row->GDLB && $row->GDMCore <= $row->GDE1 && $row->GDMCore <= $row->GDE2 && 	$row->GDMCore <= $row->GDE3) {
						$cal_Best5 = number_format( $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->LW + 1 + 1+ 1), 2, '.', '');
					}
					else if ($row->GDLB <= $row->GDChi && $row->GDLB <= $row->GDMCore && $row->GDLB <= $row->GDE1 && $row->GDLB <= $row->GDE2 && $row->GDLB <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDChi * $row->CW + $row->GDMCore * $row->MW + $row->GDE1 + $row->GDE2+ $row->GDE3 , 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + 1 + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE1 <= $row->GDChi && $row->GDE1 <= $row->GDMCore && $row->GDE1 <= $row->GDLB && $row->GDE1 <= $row->GDE2 && $row->GDE1 <= $row->GDE3) {
						$cal_Best5 = number_format( $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE2 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE2 <= $row->GDChi && $row->GDE2 <= $row->GDMCore && $row->GDE2 <= $row->GDLB && $row->GDE2 <= $row->GDE1 && $row->GDE2 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE3 <= $row->GDChi && $row->GDE3 <= $row->GDMCore && $row->GDE3 <= $row->GDLB && $row->GDE3 <= $row->GDE1 && $row->GDE3 <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
				}
				else if ( $row->GDChi <= $row->GDEng && $row->GDChi <= $row->GDMCore && $row->GDChi <= $row->GDLB && $row->GDChi <= $row->GDE1 && $row->GDChi <= $row->GDE2 && $row->GDChi <= $row->GDE3) {
					if ( $row->GDEng <= $row->GDMCore && $row->GDEng <= $row->GDLB && $row->GDEng <= $row->GDE1 && $row->GDEng <=$row->GDE2 && $row->GDEng <= $row->GDE3) { 
						$cal_Best5 = number_format($row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->MW + $row->LW + 1 + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDMCore <= $row->GDEng && $row->GDMCore <= $row->GDLB && $row->GDMCore <= $row->GDE1 && $row->GDMCore <= $row->GDE2 && $row->GDMCore <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->LW + 1 + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDLB <= $row->GDEng && $row->GDLB <= $row->GDMCore && $row->GDLB <= $row->GDE1 && $row->GDLB <= $row->GDE2 && $row->GDLB <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDMCore * $row->MW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + 1 + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE1 <= $row->GDEng && $row->GDE1 <= $row->GDMCore && $row->GDE1 <= $row->GDLB && $row->GDE1 <= $row->GDE2 && $row->GDE1 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE2 <= $row->GDEng && $row->GDE2 <= $row->GDMCore && $row->GDE2 <= $row->GDLB && $row->GDE2 <= $row->GDE1 && $row->GDE2 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 +  $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}	
					else if ($row->GDE3 <= $row->GDEng && $row->GDE3 <= $row->GDMCore && $row->GDE3 <= $row->GDLB && $row->GDE3 <= $row->GDE1 && $row->GDE3 <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
				}
				else if ($row->GDMCore <= $row->GDEng && $row->GDMCore <= $row->GDChi && $row->GDMCore <= $row->GDLB && $row->GDMCore <= $row->GDE1 && $row->GDMCore <= $row->GDE2 && $row->GDMCore <= $row->GDE3) {
					if ($row->GDChi <= $row->GDEng && $row->GDChi <= $row->GDLB && $row->GDChi <= $row->GDE1 && $row->GDChi <= $row->GDE2 && $row->GDChi <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->LW + 1 + 1+ 1), 2, '.', '');
					}
					else if ($row->GDEng <= $row->GDChi && $row->GDEng <= $row->GDLB && 	$row->GDEng <= $row->GDE1 && $row->GDEng <= $row->GDE2 && 	$row->GDEng <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->LW + 1 + 1+ 1), 2, '.', '');
					}
					else if ($row->GDLB <= $row->GDChi && $row->GDLB <= $row->GDEng && $row->GDLB <= $row->GDE1 && $row->GDLB <= $row->GDE2 && $row->GDLB <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + 1 + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE1 <= $row->GDChi && $row->GDE1 <= $row->GDEng && $row->GDE1 <= $row->GDLB && $row->GDE1 <= $row->GDE2 && $row->GDE1 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE2 <= $row->GDChi && $row->GDE2 <= $row->GDEng && $row->GDE2 <= $row->GDLB && $row->GDE2 <= $row->GDE1 && $row->GDE2 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE3 <= $row->GDChi && $row->GDE3 <= $row->GDEng && $row->GDE3 <= $row->GDLB && $row->GDE3 <= $row->GDE1 && $row->GDE3 <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->LW + 1+ 1), 2, '.', '');
					}
				}
				else if ( $row->GDLB <= $row->GDEng && $row->GDLB <= $row->GDChi && $row->GDLB <= $row->GDMCore && $row->GDLB <= $row->GDE1 && $row->GDLB <= $row->GDE2 && $row->GDLB <= $row->GDE3) {
					if ( $row->GDChi <= $row->GDEng && $row->GDChi <= $row->GDMCore && $row->GDChi <= $row->GDE1 && $row->GDChi <=$row->GDE2 && $row->GDChi <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW  +$row->GDMCore * $row->MW + $row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + 1 + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDEng <= $row->GDChi && $row->GDEng <= $row->GDMCore && $row->GDEng <= $row->GDE1 && $row->GDEng <= $row->GDE2 && $row->GDEng <= $row->GDE3) {
						$cal_Best5 = number_format( $row->GDChi * $row->CW + $row->GDMCore * $row->MW + $row->GDE1 + $row->GDE2+  $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + 1 + 1+ 1), 2, '.', '');

					}
					else if ( $row->GDMCore <= $row->GDChi && $row->GDMCore <= $row->GDEng && $row->GDMCore <= $row->GDE1 && $row->GDMCore <= $row->GDE2 && $row->GDMCore <= $row->GDE3) 	{ 
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDE1 + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + 1 + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDE1 <= $row->GDChi && $row->GDE1 <= $row->GDEng && $row->GDE1 <= $row->GDMCore && $row->GDE1 <= $row->GDE2 && $row->GDE1 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE2 <= $row->GDChi && $row->GDE2 <= $row->GDEng && $row->GDE2 <= $row->GDMCore && $row->GDE2 <= $row->GDE1 && $row->GDE2 <= $row->GDE3) 	{
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + 1+ 1), 2, '.', '');
					}
					else if ($row->GDE3 <= $row->GDChi && $row->GDE3 <= $row->GDEng && $row->GDE3 <= $row->GDMCore && $row->GDE3 <= $row->GDE1 && $row->GDE3 <= $row->GDE2) 	{
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDMCore * $row->MW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + 1+ 1), 2, '.', '');
					}
				}
				else if ( $row->GDE1 <= $row->GDEng && $row->GDE1 <= $row->GDChi && $row->GDE1 <= $row->GDMCore && $row->GDE1 <= $row->GDLB && $row->GDE1 <= $row->GDE2 && $row->GDE1 <= $row->GDE3) {
					if ( $row->GDChi <= $row->GDEng && $row->GDChi <= $row->GDMCore && $row->GDChi <= $row->GDLB && 	$row->GDChi <=$row->GDE2 && $row->GDChi <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDEng <= $row->GDChi && $row->GDEng <= $row->GDMCore && $row->GDEng <= $row->GDLB && $row->GDEng <= $row->GDE2 && $row->GDEng <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDMCore <= $row->GDChi && $row->GDMCore <= $row->GDEng && $row->GDMCore <= $row->GDLB && $row->GDMCore <= $row->GDE2 && $row->GDMCore <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->LW + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDLB <= $row->GDChi && $row->GDLB <= $row->GDEng && $row->GDLB <= $row->GDMCore && $row->GDLB <= $row->GDE2 && 	$row->GDLB <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW+ $row->GDE2+ $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + 1+ 1), 2, '.', '');
					}
					else if ( $row->GDE2 <= $row->GDChi && $row->GDE2 <= $row->GDEng && $row->GDE2 <= $row->GDMCore && $row->GDE2 <= $row->GDLB && $row->GDE2 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + $row->LW+ 1), 2, '.', '');
					}
					else if ($row->GDE3 <= $row->GDChi && $row->GDE3 <= $row->GDEng && $row->GDE3 <= $row->GDMCore && $row->GDE3 <= $row->GDLB && $row->GDE3 <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + $row->LW+ 1), 2, '.', '');
					}
				}
				else if ( $row->GDE2 <= $row->GDEng && $row->GDE2 <= $row->GDChi && $row->GDE2 <= $row->GDMCore && $row->GDE2 <= $row->GDLB && $row->GDE2 <= $row->GDE1 && $row->GDE2 <= $row->GDE3) {	
					if ( $row->GDChi <= $row->GDEng && $row->GDChi <= $row->GDMCore && $row->GDChi <= $row->GDLB && $row->GDChi <=$row->GDE1 && $row->GDChi <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + $row->LW+ 1 + 1), 2, '.', '');
					}
					else if ( $row->GDEng <= $row->GDChi && $row->GDEng <= $row->GDMCore && $row->GDEng <= $row->GDLB && $row->GDEng <= $row->GDE1 && $row->GDEng <= $row->GDE3) {
						$cal_Best5 = number_format( $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + $row->LW+ 1 +1), 2, '.', '');
					}
					else if (	$row->GDMCore <= $row->GDChi && $row->GDMCore <= $row->GDEng && $row->GDMCore <= $row->GDLB && $row->GDMCore <= $row->GDE1 && $row->GDMCore <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW  + $row->LW+ 1 +1), 2, '.', '');
					}
					else if ( $row->GDLB <= $row->GDChi && $row->GDLB <= $row->GDEng && $row->GDLB <= $row->GDMCore && $row->GDLB <= $row->GDE1 && $row->GDLB <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDE1 + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + 1 + 1), 2, '.', '');
					}
					else if ( $row->GDE1 <= $row->GDChi && $row->GDE1 <= $row->GDEng && $row->GDE1 <= $row->GDMCore && $row->GDE1 <= $row->GDLB && $row->GDE1 <= $row->GDE3) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE3, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + $row->LW+ 1), 2, '.', '');
					}
					else if ( $row->GDE3 <= $row->GDChi && $row->GDE3 <= $row->GDEng && $row->GDE3 <= $row->GDMCore && $row->GDE3 <= $row->GDLB && 	$row->GDE3 <= $row->GDE1) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + $row->LW+ 1), 2, '.', '');
					}
				}
				else if ( $row->GDE3 <= $row->GDEng && $row->GDE3 <= $row->GDChi && $row->GDE3 <= $row->GDMCore && $row->GDE3 <= $row->GDLB && $row->GDE3 <= $row->GDE1 && $row->GDE3 <= $row->GDE2) {	
					if ( $row->GDChi <= $row->GDEng && $row->GDChi <= $row->GDMCore && $row->GDChi <= $row->GDLB && $row->GDChi <=$row->GDE1 && 	$row->GDChi <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->MW + $row->LW+ 1+1), 2, '.', '');
					}
					else if ( $row->GDEng <= $row->GDChi && $row->GDEng <= $row->GDMCore && $row->GDEng <= $row->GDLB && $row->GDEng <= $row->GDE1 && $row->GDEng <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->CW + $row->MW + $row->LW+ 1 +1), 2, '.', '');
					}
					else if ($row->GDMCore <= $row->GDChi && $row->GDMCore <= $row->GDEng && $row->GDMCore <= $row->GDLB && $row->GDMCore <= $row->GDE1 && $row->GDMCore <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->LW+ 1 +1), 2, '.', '');
					}
					else if ( $row->GDLB <= $row->GDChi && 	$row->GDLB <= $row->GDEng && $row->GDLB <= $row->GDMCore && $row->GDLB <= $row->GDE1 && $row->GDLB <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + 1+ 1), 2, '.', '');

					}
					else if ( $row->GDE2 <= $row->GDChi && $row->GDE2 <= $row->GDEng && $row->GDE2 <= $row->GDMCore && $row->GDE2 <= $row->GDLB && $row->GDE2 <= $row->GDE1) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + $row->LW+ 1), 2, '.', '');
					}
					else if ( $row->GDE1 <= $row->GDChi && $row->GDE1 <= $row->GDEng && $row->GDE1 <= $row->GDMCore && $row->GDE1 <= $row->GDLB && $row->GDE1 <= $row->GDE2) {
						$cal_Best5 = number_format($row->GDEng * $row->EW + $row->GDChi * $row->CW +$row->GDMCore * $row->MW + $row->GDLB * $row->LW + $row->GDE1 + $row->GDE2, 2, '.', '');
						$dec_Best5 = number_format($cal_Best5/($row->EW + $row->CW + $row->MW + $row->LW+ 1), 2, '.', '');
					}
				}
				$row->cal_Best5 = $cal_Best5;
				$row->dec_Best5 = $dec_Best5;

				$mathIndex = number_format( ($row->GDMExt - $row->GDMCore) * 0.3, 2, '.', '') ;


				if ( $row->GDE1 <= $row->GDE2 && $row->GDE1 <= $row->GDE3 ) {
					$elecIndex = number_format( ($row->GDMExt - $row->GDE1) * 0.2, 2, '.', '') ;	
					$worstElec = $row->GDE1;
					if ( $row->GDE2 <= $row->GDE3 ) {
						$secBestElec = $row->GDE2 ;
						$bestElec = $row->GDE3;
					} 
					else {
						$secBestElec = $row->GDE3 ;
						$bestElec = $row->GDE2;
					}


				}
				else if( $row->GDE2 <= $row->GDE1 && $row->GDE2 <= $row->GDE3 ) {
					$elecIndex = number_format( ($row->GDMExt - $row->GDE2) * 0.2, 2, '.', '') ;
					$worstElec = $row->GDE2;
					if ( $row->GDE1 <= $row->GDE3 ) {
						$secBestElec = $row->GDE1 ;
						$bestElec = $row->GDE3;
					} 
					else {
						$secBestElec = $row->GDE3 ;
						$bestElec = $row->GDE1;
					}
				}
				else if( $row->GDE3 <= $row->GDE1 && $row->GDE3 <= $row->GDE2 ) {
					$elecIndex = number_format( ($row->GDMExt - $row->GDE3) * 0.2, 2, '.', '') ;
					$worstElec = $row->GDE3;
					if ( $row->GDE1 <= $row->GDE2 ) {
						$secBestElec = $row->GDE1 ;
						$bestElec = $row->GDE2;
					} 
					else {
						$secBestElec = $row->GDE2 ;
						$bestElec = $row->GDE1;
					}


				}			
				if ( $mathIndex >= $elecIndex && $mathIndex > 0) {
					$cal_RSI = number_format( ($row->GDEng * 1.3 + $row->GDChi + $row->GDMCore * 1.3 + $row->GDLB + $bestElec +  $secBestElec * 0.6 +  $worstElec * 0.2 + $mathIndex) /32*5, 2, '.', '');

				} else if ( $elecIndex > $mathIndex && $elecIndex > 0) {
					$cal_RSI = number_format( ($row->GDEng * 1.3 + $row->GDChi + $row->GDMCore * 1.3 + $row->GDLB + $bestElec +  $secBestElec * 0.6 +  $worstElec * 0.2 + $elecIndex)/32*5, 2, '.', '');

				} else {
					$cal_RSI = number_format( ($row->GDEng * 1.3 + $row->GDChi + $row->GDMCore * 1.3 + $row->GDLB + $bestElec +  $secBestElec * 0.6 +  $worstElec * 0.2)/32*5, 2, '.', '');

				}

				$row->cal_RSI = $cal_RSI;

			}


			echo json_encode($query->result());
		}
		
		
		
	}
