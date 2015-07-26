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
		//start of using new table
		public function new_table($course_id){
			$year = 2014;
			$query =[];
			if (!is_null($course_id)) {
				$query = $this->db->get_where("2014Data_source",array("Jcode"=>'JS'.$course_id));
			}else{
				$query = $this->db->get("2014Data_source");
			}
			
			$code_query = $this->db->get("NewProgrammeInformation");
			foreach ($query->result() as $row)
			{
				//pid
				$pid = str_replace("JS","",$row->Jcode);
			    $row->PID = $pid;
			    unset($row->Jcode);
			    //PName
			    $code_name_key = array_search($pid, array_column($code_query->result(),"PID"));
			    $row->PName = $code_query->result()[$code_name_key]->PName;
			    //GDChi
			    $row->GDChi = $row->CHI;
			    unset($row->CHI);
			    //GDEng
			    $row->GDEng = $row->ENG;
			    unset($row->ENG);
			    //mcore
			    $row->GDMCore = $row->Math;
			    unset($row->Math);
			    $row->GDMExt = $row->M1nM2;
			    //gdlb
			    $row->GDLB = $row->LS;
			    unset($row->LS);
			    //GD1,2,3
			    $all_course_array = [
			    	$row->PHY,
			    	$row->CHEM,
			    	$row->BIO,
			    	$row->SCI_INT,
			    	$row->SCI_COM,
			    	$row->ICT,
			    	$row->CLIT,
			    	$row->ELIT,
			    	$row->CHIST,
			    	$row->HIST,
			    	$row->GEOG,
			    	$row->ECON,
			    	$row->BAFS,
			    	$row->VA,
			    	$row->THS,
			    	$row->ERS,
			    	$row->MUSIC,
			    	$row->PE,
			    	$row->HMSC,
			    	$row->DAT,
			    	$row->TL,
			    	$row->JAP,
			    	$row->GER,
			    	$row->OTH_CATC,
			    	$row->APL,
			    	$row->UNDEF
			    ];
			    list($row->GDE1,$row->GDE2,$row->GDE3) = $this->cal_GDE($all_course_array);
			    	unset($row->PHY);
			    	unset($row->CHEM);
			    	unset($row->BIO);
			    	unset($row->SCI_INT);
			    	unset($row->SCI_COM);
			    	unset($row->ICT);
			    	unset($row->CLIT);
			    	unset($row->ELIT);
			    	unset($row->CHIST);
			    	unset($row->HIST);
			    	unset($row->GEOG);
			    	unset($row->ECON);
			    	unset($row->BAFS);
			    	unset($row->VA);
			    	unset($row->THS);
			    	unset($row->ERS);
			    	unset($row->MUSIC);
			    	unset($row->PE);
			    	unset($row->HMSC);
			    	unset($row->DAT);
			    	unset($row->TL);
			    	unset($row->JAP);
			    	unset($row->GER);
			    	unset($row->OTH_CATC);
			    	unset($row->APL);
			    	unset($row->UNDEF);
			    //for ew cw mw
			    $weighting = $this->db->query('SELECT  Program.EnglishWeight AS EW, Program.ChineseWeight AS CW,
				Program.MathsWeight AS MW, Program.LSWeight AS LW
				From NewProgrammeInformation AS Program
				Where PID='.$pid)->result()[0];
				$row->EW = $weighting->EW;
				$row->CW = $weighting->CW;
				$row->MW = $weighting->MW;
				$row->LW = $weighting->LW;
			   	//Year
			    	$row->Year = "2014";
			    //origin 4c 
			    $this->four_c($row);
			    //cal_4c1
			    $row->cal_4C1 = $row->cal_4C_1X;
			    unset($row->cal_4C_1X);
			    //dev_4c1
			    $row->dec_4C1 = strval(round($row->cal_4C1/5,2));
			    //cal_4c2
			    //cal_best5
			    $row->cal_Best5 = $row->Best5;
			    unset($row->Best5);
			    $row->dev_Best5 = strval($row->cal_Best5 / 5);
			    //RSI;
			    $this->rsi($row);
			    //
			    unset($row->REMARK);
			    unset($row->cal_2C_3X);
			    unset($row->Band);
			    unset($row->M1);
			    unset($row->M2);
			    unset($row->M1nM2);



			   
			}
			return $query->result();
		}
		//end of useing new table
		private function cal_GDE($array_of_all_course){
			for ($i=0; $i < count($array_of_all_course); $i++) { 
				$array_of_all_course[$i] = intval($array_of_all_course[$i]);
			}
			rsort($array_of_all_course);
			return [strval($array_of_all_course[0]),strval($array_of_all_course[1]),strval($array_of_all_course[2])];
		}
		public function chartjson(){
			header('Content-Type: application/json');
			$course_id = $this->input->get("course_id");
			$year = $this->input->get("year");
			$inject_sql ="where Year!='2014'";
			if (!is_null($course_id)) {
				$inject_sql = $inject_sql."AND Student.PID = $course_id";
			}
			if (!is_null($year) && $year!="2014") {
				$inject_sql = $inject_sql." AND Student.Year=$year";
			}

			$json_array = $this->json_code($inject_sql)->result();

			if(is_null($year) || $year == '2014'){
				$json_array = array_merge($json_array,$this->new_table($course_id));
			}
			echo json_encode($json_array);
		}
		public function chart(){
			$course_id = $this->input->get("course_id");
			$year = $this->input->get("year");
			$inject_sql ="";
			if (!is_null($course_id)) {
				$inject_sql = "where Student.PID = $course_id";
			}
			if (!is_null($year)) {
				$inject_sql = $inject_sql." AND Student.Year=$year";
			}
			$data['json'] = json_encode($this->json_code($inject_sql)->result());
			$this->load->view("chart",$data);
		}


		public function all(){
			header('Content-Type: application/json');
			$course_id = $this->input->get("course_id");
			
			$inject_sql ="";
			if (!is_null($course_id)) {
				$inject_sql = "where Student.PID = $course_id";
			}
			
			$this->output->set_content_type('application/json');
			$query = $this->json_code($inject_sql);


			echo json_encode($query->result());
		}
		private function json_code($inject_sql){
			$query = $this->db->query('SELECT Program.PName AS PName, Student.PID, Student.GDChi, Student.GDEng, Student.GDMCore, 
				Student.GDMExt, Student.GDLB, Student.GDE1, Student.GDE2, Student.GDE3, Program.EnglishWeight AS EW, Program.ChineseWeight AS CW,
				Program.MathsWeight AS MW, Student.Year, Program.LSWeight AS LW
				FROM StuGradeData AS Student  
				INNER JOIN NewProgrammeInformation AS Program
				On Student.pid = Program.pid '.$inject_sql);
			foreach ($query->result() as $key => $row) {

				$this->four_c($row);
				if ( $row->GDMCore < $row->GDMExt ) {
					$this->mathCoreSmallerThanEXT($row);
				}else if ($row->GDMCore >=  $row->GDMExt){

					$this->mathCorebiggerThanEXT($row);
					$this->rsi($row);

				}
			}
			return $query;
		}
		private function rsi($row){
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
		private function four_c($row){
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
		private function mathCoreSmallerThanEXT($row){
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
			$row->cal_Best5 = $cal_Best5;
			$row->dec_Best5 = $dec_Best5;

		}
		private function mathCorebiggerThanEXT($row){
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
		}
	}
