<?php
class Program extends CI_Model {
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
    function table_for_all(){
   
    }
    function fourCPlusOne()
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
	}
		
}
?>