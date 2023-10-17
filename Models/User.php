<?php

class User
{
    protected $db;

    public function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->openConnection();
        $this->today = date('d-m-Y');
    }

    public function get_user($user_id)
    {
        $get_user = $this->db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid=:user_id");
        $get_user->execute(array(":user_id" => $user_id));
        $count_user = $get_user->rowCount();
        $user = $get_user->fetch();
        return ($count_user > 0) ? $user : false;
    }
}
