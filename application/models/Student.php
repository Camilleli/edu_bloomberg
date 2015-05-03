<?php
class Student extends CI_Model {
            var $db_table ;
            var $FbId;
            var $StuID;
            var $StuName;
            var $StuGender;
            var $StuEmail;
            var $FbToken;
            var $FbProfileIcon;
            var $Active;
            var $Birthday;
            var $FirstLoginDate;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
       
    }

    function from_db_construct($id)
    {
         $this->db_table = "Students";
        $query = $this->db->get_where($this->db_table,array('FbId' => $id));
        $result  = $query->result();
        
        $this->FbId = $result[0]->FbId;
        $this->StuID = $result[0]->StuID;
        $this->StuName = $result[0]->StuName;
        $this->StuGender = $result[0]->StuGender;
        $this->StuEmail = $result[0]->StuEmail;
        $this->FbToken = $result[0]->FbToken;
        $this->FbProfileIcon = $result[0]->FbProfileIcon;
        $this->Active = $result[0]->Active;
        $this->Birthday = $result[0]->Birthday;
        $this->FirstLoginDate = $result[0]->FirstLoginDate;
        return $this;
    }
    
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

}
?>