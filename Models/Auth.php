<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


class Auth
{
    protected $db;
    protected $today;
    protected $close_db;

    public function __construct()
    {
        try {
            $conn = new Connection();
            $this->db = $conn->openConnection();
            // $conn->closeConnection();
            $this->today = date('d-m-Y');
        } catch (\Throwable $th) {
            var_dump($th->getMessage());
        }
    }

    // get user details from the database
    public function get_user($email)
    {
        $get_user = $this->db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid,u.password, u.first_login FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title  WHERE u.email=:email ");
        $get_user->execute(array(":email" => $email));
        $count_user = $get_user->rowCount();
        $user = $get_user->fetch();
        return ($count_user > 0) ? $user : false;
    }

    // get user details from the database
    public function get_user_by_id($user_id)
    {
        $get_user = $this->db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid,u.password, u.first_login FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title  WHERE u.userid=:user_id");
        $get_user->execute(array(":user_id" => $user_id));
        $count_user = $get_user->rowCount();
        $user = $get_user->fetch();
        return ($count_user > 0) ? $user : false;
    }

    // login functionality
    public function login($email, $password)
    {
        $user = $this->get_user($email);
        $success = false;
        if ($user) {
            $success =  (password_verify($password, $user->password)) ? true : false;
        }
        return $success ? $user : false;
    }

    // send reset link to users email
    public function forgot_password($email)
    {
        $user = $this->get_user($email);
        $mail_response = false;
        if ($user) {
            $token = $this->generate_string(64);
            $create_reset_token = $this->db->prepare("INSERT INTO tbl_password_resets (`email`, `token`) VALUES (:email, :token)");
            $results = $create_reset_token->execute(array(":email" => $email, ":token" => $token));
            if ($results) {
                $notification_group_id = 1;
                $notification_type_id = 8;
                $priority = 1;
                $page_url = "http://localhost:8000/reset-password.php?token=$token";
                $mail_response = $this->send_mail($user->userid, $user->ttitle . " " . $user->fullname, $email, $priority, $notification_group_id, $notification_type_id, $page_url);
            }
        }
        return $mail_response;
    }

    // Verify token when reseting pasword
    public function verify_token($token)
    {
        $get_user = $this->db->prepare("SELECT * FROM tbl_password_resets WHERE token=:token ORDER BY created_at DESC LIMIT 1");
        $get_user->execute(array(":token" => $token));
        $count_user = $get_user->rowCount();
        $token_data = $get_user->fetch();
        $time_elapsed = false;
        if ($count_user > 0) {
            $time_elapsed = $this->calculate_time($token_data->created_at);
        }
        return $time_elapsed;
    }

    // calculate time and return in minutes
    private function calculate_time($created_at)
    {
        $date1 = strtotime($created_at);
        $date2 = strtotime(date("Y-m-d h:i:s"));
        $diff = abs($date2 - $date1);

        $years = floor($diff / (365 * 60 * 60 * 24));

        $months = floor(($diff - $years * 365 * 60 * 60 * 24)
            / (30 * 60 * 60 * 24));

        $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
            $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

        $hours = floor(($diff - $years * 365 * 60 * 60 * 24
            - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24)
            / (60 * 60));

        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24
            - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
            - $hours * 60 * 60) / 60);

        return ((60 - $minutes) > 0) ? true : false;
    }

    private function send_mail($user_id, $fullname, $email, $priority, $notification_type_id, $page_url, $otp)
    {
        $mail = new Email();
        $notification_group_id = 1;
        $token = $mail->get_auth_token($fullname, $email, '', $otp);
        $notification = $mail->get_notifications($priority, $notification_group_id);
        $notification_id = $notification->id;
        return $mail->get_template($token, $user_id, $notification_type_id, $notification_group_id, $notification_id, $page_url);
    }

    // Reset password
    public function reset_password($email, $token, $password)
    {
        $user = $this->get_user($email);
        $mail_response = false;
        if ($user) {
            $stored_token_verify = $this->verify_token($token);
            if ($stored_token_verify) {
                $sql = $this->db->prepare("UPDATE users SET  `password`=:password, last_update_password_date=:today WHERE email=:email");
                $results = $sql->execute(array(":password" => password_hash($password, PASSWORD_DEFAULT), ":today" => date('Y-m-d'), ":email" => $email));
                if ($results) {
                    $mail_response =  $this->send_mail($user->userid, $user->fullname, $email, 1, 22, "index.php", '');
                }
            }
        }
        return $mail_response;
    }

    public function suspicious_activity($email)
    {
        $user = $this->get_user($email);
        $mail_response = false;
        if ($user) {
            $sql = $this->db->prepare("UPDATE tbl_projteam2 SET  `disabled`=1 WHERE email=:email");
            $results = $sql->execute(array(":email" => $email));
            if ($results) {
                $mail_response = $this->send_mail($user->userid, $user->fullname, $email, 1, 10, "index.php", '');
            }
        }
        return $mail_response;
    }

    // for new users and those who would like to change their passwords
    public function change_password($userid, $password)
    {
        $user = $this->get_user_by_id($userid);
        $response = false;
        if ($user) {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = $this->db->prepare("UPDATE users SET password=:password, last_update_password_date=:today, first_login=0 WHERE userid=:userid");
            $response = $sql->execute(array(":password" => $password_hashed, ":today" => date('Y-m-d'), ":userid" => $userid));
            if ($response) {
                $response = $this->send_mail($user->userid, $user->fullname, $user->email, 1, 22, "index.php", '');
            }
        }
        return $response ? $user : false;
    }

    public function store_login_history($user_id)
    {
        $token = $this->generate_string(64);
        $create_reset_token = $this->db->prepare("INSERT INTO tbl_login_history (`user_id` ) VALUES (:user_id)");
        $results = $create_reset_token->execute(array(":user_id" => $user_id));
        return $results;
    }

    private function generate_string($str_length)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $str_length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }


    // send mail to contractor and block if attempts reached limit
    public function otp($email)
    {
        $user = $this->get_user($email);
        $mail_response = false;
        if ($user) {
            // generate otp
            $otp = rand(100000, 999999);
            date_default_timezone_set('Africa/Nairobi');
            $expires_at = date('Y-m-d H:i:s', strtotime('+2 minute'));
            // store this details in db
            $opt_stmt = $this->db->prepare('UPDATE users SET otp=:otp, expires_at=:expires_at WHERE email=:email');
            $otp_result = $opt_stmt->execute([":otp" => $otp, ":expires_at" => $expires_at, ":email" => $email]);

            if ($otp_result) {
                $mail_response = $this->send_mail($user->userid, $user->fullname, $user->email, 3, 27, '', $otp);
            }
        }
        return $mail_response;
    }

    /**
     * checks if the opt sent has expired or not
     * @param ContractorEmail
     * @return std class
     */
    public function checkIfOptExpired($email, $otp_code)
    {
        $sql = $this->db->prepare("SELECT * FROM users WHERE email=:email");
        $sql->execute(array(":email" => $email));
        $record = $sql->fetch(PDO::FETCH_OBJ);
        $otp_expired_at = $record->expires_at;
        $now = date('Y-m-d H:i:s');
        if ($now > $otp_expired_at) {
            // regenerate otp and send
            $this->otp($email);
            $_SESSION["errorMessage"] = "Otp has expired check mail for new one!";
            return false;
        } else {
            // check if its true
            $otp_sved = $record->otp;
            if ($otp_code === $otp_sved) {
                // remove otp
                $opt_stmt = $this->db->prepare('UPDATE users SET otp=:otp, expires_at=:expires_at WHERE email=:email');
                $otp_result = $opt_stmt->execute([":otp" => null, ":expires_at" => null, ":email" => $email]);
                if ($otp_result) {
                    unset($_SESSION["errorMessage"]);
                    return true;
                }
            } else {
                $_SESSION["errorMessage"] = "Wrong otp code entered.";
                return false;
            }
        }
    }
}
