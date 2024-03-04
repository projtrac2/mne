<?php
class Auth
{
    protected $db;
    protected $today;
    protected $close_db;

    public function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->openConnection();
        // $conn->closeConnection();
        $this->today = date('d-m-Y');
    }

    // get user details from the database
    public function get_user($email)
    {
        $get_user = $this->db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.email=:email ");
        $get_user->execute(array(":email" => $email));
        $count_user = $get_user->rowCount();
        $user = $get_user->fetch();
        if ($count_user > 0) {
            return $user;
        } else {
            return false;
        }
    }

    // get user details from the database
    public function get_user_by_id($user_id)
    {
        $get_user = $this->db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid=:user_id");
        $get_user->execute(array(":user_id" => $user_id));
        $count_user = $get_user->rowCount();
        $user = $get_user->fetch();
        if ($count_user > 0) {
            return $user;
        } else {
            return false;
        }
    }

    // login functionality
    public function login($email, $password)
    {
        $user = $this->get_user($email);
        if ($user) {
            $hashed_password = $user->password;
            if (password_verify($password, $hashed_password)) {
                // $this->store_login_history($user->ptid);
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // send reset link to users email
    public function forgot_password($email)
    {
        $user = $this->get_user($email);
        if ($user) {
            $token = $this->generate_string(64);
            $create_reset_token = $this->db->prepare("INSERT INTO tbl_password_resets (`email`, `token`) VALUES (:email, :token)");
            $results = $create_reset_token->execute(array(":email" => $email, ":token" => $token));
            if ($results) {
                $mail = new Email();
                $data = array(
                    "sitename" => "Projtrac",
                    "firstname" => $user->fullname,
                    "contact" => $user->phone,
                    "password" => "password",
                    "recipient" => $email,
                );

                $template = $mail->email_template(1, $data);
                $data = array(
                    "subject" => "Forgot Password",
                    "title" => "Forgot Password",
                    "receipient" => $email,
                    "receipient_name" => $user->fullname,
                    "template" => $template,
                    "page_url" => "reset-password.php?token=" . $token,
                    "attachment" => ""
                );

                $mail_response = $mail->send_mail($data);
                if ($mail_response) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    // Verify token when reseting pasword
    public function verify_token($token)
    {
        $get_user = $this->db->prepare("SELECT * FROM tbl_password_resets WHERE token=:token ORDER BY created_at DESC LIMIT 1");
        $get_user->execute(array(":token" => $token));
        $count_user = $get_user->rowCount();
        $token_data = $get_user->fetch();
        if ($count_user > 0) {
            $time_elapsed = $this->calculate_time($token_data->created_at);
            if ($time_elapsed) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
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

    // Reset password
    public function reset_password($email, $token, $password)
    {
        $user = $this->get_user($email);
        if ($user) {
            $stored_token_verify = $this->verify_token($token);
            if ($stored_token_verify) {
                $sql = $this->db->prepare("UPDATE users SET  `password`=:password WHERE email=:email");
                $results = $sql->execute(array(":password" => password_hash($password, PASSWORD_DEFAULT), ":email" => $email));
                if ($results) {
                    $mail = new Email();
                    $data = array(
                        "sitename" => "Projtrac",
                        "firstname" => $user->fullname,
                        "contact" => $user->phone,
                        "password" => "password",
                        "recipient" => $email,
                    );

                    $template = $mail->email_template(1, $data);
                    $data = array(
                        "subject" => "Sucessfully Reset Password",
                        "title" => "Reset Password",
                        "receipient" => $email,
                        "receipient_name" => $user->fullname,
                        "template" => $template,
                        "page_url" => "index.php",
                        "attachment" => ""
                    );

                    $mail_response = $mail->send_mail($data);
                    if ($mail_response) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // for new users and those who would like to change their passwords
    public function change_password($userid, $password)
    {
        $user = $this->get_user_by_id($userid);
        if ($user) {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = $this->db->prepare("UPDATE users SET password=:password, first_login=0 WHERE userid=:userid");
            $results = $sql->execute(array(":password" => $password_hashed, ":userid" => $userid));
            if ($results) {
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function store_login_history($user_id)
    {
        $token = $this->generate_string(64);
        $create_reset_token = $this->db->prepare("INSERT INTO tbl_login_history (`user_id` ) VALUES (:user_id)");
        $results = $create_reset_token->execute(array(":user_id" => $user_id));
        if ($results) {
            return true;
        } else {
            return false;
        }
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
}
