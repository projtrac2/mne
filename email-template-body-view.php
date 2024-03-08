<?php
require('includes/permission.php');



try {
    $template = '<p>Email template not found</p>';
    if ($_GET['si'] == 6) {
        # code...
        $query_templates = $db->prepare("SELECT * FROM tbl_email_templates WHERE id=:id");
        $query_templates->execute([':id' => $_GET['si']]);
        $row_email_templates = $query_templates->fetch();
        $title = 'title';
        $content = 'message goes here';
        $link = '#';
        $count = $query_templates->rowCount();
        $details_link =  '<a href="' . $link . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Click Here</a>';
        if ($count > 0) {
            $token = array('TITLE' => $title, 'MESSAGE' => $content, 'LINK' => $details_link,);
            $pattern = '[%s]';
            foreach ($token as $key => $val) {
                $varMap[sprintf($pattern, $key)] = $val;
            }
            $template = strtr($row_email_templates['content'], $varMap);
        } else {
            $template = "<p>Email template not found</p>";
        }
    }

    if ($_GET['si'] != 6) {
        // content
        $selected_templates = $db->prepare("SELECT * FROM tbl_email_templates WHERE id=:id");
        $selected_templates->execute([':id' => $_GET['si']]);
        $selected_templates_result = $selected_templates->fetch();
        $content = $selected_templates_result['content'];

        // template
        $title = 'Template';
        $query_templates = $db->prepare("SELECT * FROM tbl_email_templates WHERE id=:id");
        $query_templates->execute([':id' => 6]);
        $row_email_templates = $query_templates->fetch();
        $count = $query_templates->rowCount();
        $details_link =  '<a href="#" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Click Here</a>';
        if ($count > 0) {
            $token = array('TITLE' => $title, 'MESSAGE' => $content, 'LINK' => $details_link,);
            $pattern = '[%s]';
            foreach ($token as $key => $val) {
                $varMap[sprintf($pattern, $key)] = $val;
            }
            $template = strtr($row_email_templates['content'], $varMap);
        } else {
            $template = "<p>Email template not found</p>";
        }
    }
} catch (\Throwable $th) {
}
?>
<style>
    .my-btn:hover {
        cursor: pointer;
    }
</style>
<div style="background: #2196f3; display:flex; flex-direction:row-reverse; padding:10px;">
    <a href="/email_templates.php">
        <button class="my-btn" style=" padding-left: 16px; padding-right: 16px; padding-top:10px; padding-bottom: 10px; background:#ff9600; color:white; border:none; border-radius: 5px;">back</button>
    </a>
</div>
<?php echo $template ?>