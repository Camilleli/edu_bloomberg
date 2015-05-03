<?php
class program extends CI_Model {
            var $db_table ;
            var $PID;
            var $PName;
            var $InstID;
            var $EnglishWeight;
            var $ChineseWeight;
            var $MathsWeight;
            var $LSWeight;
            var $Elec1Weight;
            var $Elec2Weight;
            var $Elec3Weight;
			var $MathsExtWeight;
			var $AvgRSI;
			var $MinRSI;
			var $AvgBest5;
			var $MinBest5;
			var $Avg4C1;
			var $Min4C1;
			var $Avg4C2;
			var $Min4C2;
			

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
       
    }

    function from_db_construct($id)
    {	
		$this->db->SELECT("NewProgrammeInformation.PID, 
		(NewProgrammeInformation.EnglishWeight * AVG(StuGradeData.GDEng) +
		NewProgrammeInformation.ChineseWeight * AVG(StuGradeData.GDChi) +
		NewProgrammeInformation.MathsWeight * AVG(StuGradeData.GDMCore) +
		NewProgrammeInformation.LSWeight * AVG(StuGradeData.GDLB)+
		NewProgrammeInformation.Elec1Weight * AVG(StuGradeData.GDE1)+
		NewProgrammeInformation.Elec2Weight * AVG(StuGradeData.GDE2)+
		NewProgrammeInformation.Elec3Weight * AVG(StuGradeData.GDE3)-
		LEAST(NewProgrammeInformation.Elec1Weight * AVG(StuGradeData.GDE1), NewProgrammeInformation.Elec2Weight * AVG(StuGradeData.GDE2),NewProgrammeInformation.Elec3Weight * AVG(StuGradeData.GDE3) ))AS Avg4C2Result,
		
		(NewProgrammeInformation.EnglishWeight * MIN(StuGradeData.GDEng) +
		NewProgrammeInformation.ChineseWeight * MIN(StuGradeData.GDChi) +
		NewProgrammeInformation.MathsWeight * MIN(StuGradeData.GDMCore) +
		NewProgrammeInformation.LSWeight * MIN(StuGradeData.GDLB)+
		NewProgrammeInformation.Elec1Weight * MIN(StuGradeData.GDE1)+
		NewProgrammeInformation.Elec2Weight * MIN(StuGradeData.GDE2)+
		NewProgrammeInformation.Elec3Weight * MIN(StuGradeData.GDE3)-
		LEAST(NewProgrammeInformation.Elec1Weight * MIN(StuGradeData.GDE1), NewProgrammeInformation.Elec2Weight * MIN(StuGradeData.GDE2),NewProgrammeInformation.Elec3Weight * MIN(StuGradeData.GDE3)))AS MIN4C2Result,
		
		(NewProgrammeInformation.EnglishWeight * AVG(StuGradeData.GDEng) +
		NewProgrammeInformation.ChineseWeight * AVG(StuGradeData.GDChi) +
		NewProgrammeInformation.MathsWeight * AVG(StuGradeData.GDMCore) +
		NewProgrammeInformation.LSWeight * AVG(StuGradeData.GDLB)+
		GREATEST(NewProgrammeInformation.Elec1Weight * AVG(StuGradeData.GDE1), NewProgrammeInformation.Elec2Weight * AVG(StuGradeData.GDE2),NewProgrammeInformation.Elec3Weight * AVG(StuGradeData.GDE3) ))AS Avg4C1Result,
		
		(NewProgrammeInformation.EnglishWeight * MIN(StuGradeData.GDEng) +
		NewProgrammeInformation.ChineseWeight * MIN(StuGradeData.GDChi) +
		NewProgrammeInformation.MathsWeight * MIN(StuGradeData.GDMCore) +
		NewProgrammeInformation.LSWeight * MIN(StuGradeData.GDLB)+
		GREATEST(NewProgrammeInformation.Elec1Weight * MIN(StuGradeData.GDE1), NewProgrammeInformation.Elec2Weight * MIN(StuGradeData.GDE2),NewProgrammeInformation.Elec3Weight * MIN(StuGradeData.GDE3) ))AS MIN4C1Result ");
		
		$this->db->FROM("`NewProgrammeInformation`, `StuGradeData`");
		$this->db->WHERE("NewProgrammeInformation.PID = StuGradeData.PID");
		$this->db->GROUP_BY("NewProgrammeInformation.PID");
		
		$query = $this->db->get();
		
		if ( $query -> num_rows() > 0)
		{
			return $query;
		}
		
		
		
		
		/*
        $this->db_table = "NewProgrammeInformation";
        $query = $this->db->query('SELECT COUNT(*) FROM NewProgrammeInformation');
		$result = $query->result();
		$this->y = result;
		$query = $this->db->get_where("NewProgrammeInformation");
        $result  = $query->result();
		$this->x = 0;
		
		
        while ( x < y ) {
         $this->PID = $result[0]->PID;
		 $this->PName = $result[0]->PName;
         $this->InstID = $result[0]->InstID;
         $this->EnglishWeight= $result[0]->EnglishWeight;
         $this->ChineseWeight= $result[0]->ChineseWeight;
         $this->MathsWeight= $result[0]->MathsWeight;
         $this->LSWeight= $result[0]->LSWeight;
         $this->Elec1Weight= $result[0]->Elec1Weight;
         $this->Elec2Weight= $result[0]->Elec2Weight;
         $this->Elec3Weight= $result[0]->Elec3Weight;
		 $this->MathsExtWeight= $result[0]->MathsExtWeight;
		 $this->AvgRSI= $result[0]->AvgRSI;
		 $this->MinRSI= $result[0]->MinRSI;
		 $this->AvgBest5= $result[0]->AvgBest5;
		 $this->MinBest5= $result[0]->MinBest5;
		 $this->Avg4C1= $result[0]->Avg4C1;
		 $this->Min4C1= $result[0]->Min4C1;
		 $this->Avg4C2= $result[0]->Avg4C2;
		 $this->Min4C2= $result[0]->Min4C2;
		 $x = x + 1;
		 }  
		

        return $this;
		*/
    }
    /*
    function get_last_ten_entries()
    {
        $query = $this->db->get('entries', 10);
        return $query->result();
    }

    function insert_entry()
    {
        $this->title   = $_POST['title']; // please read the below note
        $this->content = $_POST['content'];
        $this->date    = time();

        $this->db->insert('entries', $this);
    }

    function update_entry()
    {
        $this->title   = $_POST['title'];
      $this->content = $_POST['content'];
        $this->date    = time();

      $this->db->update('entries', $this, array('id' => $_POST['id']));
    }
	*/
}
?>