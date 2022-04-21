<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    protected $host;
    protected $smtp_auth;
    protected $username;
    protected $password;
    protected $smtp_secure;
    protected $port;
    protected $db;
    protected $sender;
    protected $url;
    protected $org;
    protected $org_email;

    public function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->openConnection();
        $settings = $this->email_settings();
        $this->host = $settings->host;
        $this->smtp_auth = $settings->SMTPAuth;
        $this->username = $settings->username;
        $this->password = $settings->password;
        $this->smtp_secure = $settings->SMTPSecure;
        $this->port = $settings->port;
        $this->sender = $settings->username;

        $company_settings = $this->company_settings();
        if ($company_settings) {
            $this->url = $company_settings->main_url;
            $this->org = $company_settings->company_name;
            $this->org_email = $company_settings->email_address;
        }
    }

    private function email_settings()
    {
        $query_settings = $this->db->prepare("SELECT * FROM tbl_email_settings");
        $query_settings->execute();
        $settings = $query_settings->fetch();
        $row_count = $query_settings->rowCount();
        return ($row_count > 0) ? $settings : false;
    }

    private function company_settings()
    {
        $query_url =  $this->db->prepare("SELECT * FROM tbl_company_settings");
        $query_url->execute();
        $row_url = $query_url->fetch();
        $count = $query_url->rowCount();
        return ($count > 0) ? $row_url : false;
    }

    public function email_template($type, $data)
    {
        $query_email_templates =  $this->db->prepare("SELECT * FROM `tbl_email_templates` WHERE type=:type");
        $query_email_templates->execute(array(":type" => $type));
        $row_email_templates = $query_email_templates->fetch();
        $count = $query_email_templates->rowCount();
        if ($count > 0) {
            $token = array(
                'SITE_NAME' => $data['sitename'],
                'FIRST_NAME' => $data['firstname'],
                'MOBILE_NUMBER' => $data['contact'],
                'EMAIL' => $data['recipient'],
                'PASSWORD' => $data['password']
            );
            $pattern = '[%s]';
            foreach ($token as $key => $val) {
                $varMap[sprintf($pattern, $key)] = $val;
            }
            $template = strtr($row_email_templates->content, $varMap); 
            return $template;
        } else {
            return false;
        }
    }

    public function send_mail($data)
    {
        $content = $data['template'];
        $title = $data['title']; 
        $detailslink = '<a href="' . $this->url. $data['page_url'] . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">'.$title.'</a>';
        include 'templates/email-template.php';
        $mail = new PHPMailer;
        // $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host       = $this->host;
        $mail->SMTPAuth   = $this->smtp_auth;
        $mail->Username   = $this->username;
        $mail->Password   = $this->password;
        $mail->SMTPSecure = $this->smtp_secure;
        $mail->Port       = $this->port; 
        $mail->setFrom($this->sender, $this->org);
        $mail->addAddress($data['receipient'], $data['receipient_name']);
        (isset($data['attachment']) && !empty($data['attachment'])) ? $mail->addStringAttachment($data['attachment'],"myattachment.pdf") : "";
        $mail->isHTML(True);
        $mail->Subject = $data['subject'];
        $mail->Body    = $body;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        return $mail->send();
    }

    public function sendMail($data)
    {
        $content = $data['template'];
        $title = $data['title']; 
        $detailslink = '<a href="' . $this->url. $data['page_url'] . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">'.$title.'</a>';
        include '../../templates/email-template.php';
        $mail = new PHPMailer;
        // $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host       = $this->host;
        $mail->SMTPAuth   = $this->smtp_auth;
        $mail->Username   = $this->username;
        $mail->Password   = $this->password;
        $mail->SMTPSecure = $this->smtp_secure;
        $mail->Port       = $this->port; 
        $mail->setFrom($this->sender, $this->org);
        $mail->addAddress($data['receipient'], $data['receipient_name']);
        (isset($data['attachment']) && !empty($data['attachment'])) ? $mail->addStringAttachment($data['attachment'],"myattachment.pdf") : "";
        $mail->isHTML(True);
        $mail->Subject = $data['subject'];
        $mail->Body    = $body;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        return $mail->send();
    }
}