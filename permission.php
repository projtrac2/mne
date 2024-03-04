<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


session_start();
(!isset($_SESSION['MM_Username'])) ? header("location: index.php") : "";


include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

$user_name = $_SESSION['MM_Username'];
$designation_id = $_SESSION['designation'];
$department_id = $_SESSION['ministry'];
$section_id = $_SESSION['sector'];
$directorate_id = $_SESSION['directorate'];
$floc = $_SESSION['avatar'];
$fullname = $_SESSION['fullname'];

$designation_id = 2;
function get_current_url()
{
    $current_page_url = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $url_components = parse_url($current_page_url);

    if (isset($url_components['query'])) { // getting the parameters
        parse_str($url_components['query'], $params);
    }

    $paths = explode("/", $url_components['path']); // getting the filename
    return $paths[2];
}

function page_sector($page_id) // validating pages that are department specific
{
    global $db, $designation_id, $department_id, $section_id, $directorate_id;
    $sql = $db->prepare("SELECT * FROM tbl_page_sectors  WHERE page_id=:page_id ");
    $sql->execute(array(":page_id" => $page_id));
    $rows = $sql->fetch();
    $total_rows = $sql->rowCount();
    $result = true;
    if ($total_rows > 0) {
        $page_department = $rows['department_id'];
        $page_sector = $rows['sector_id'];
        $page_directorate = $rows['directorate_id'];
        $result = false;
        if ($designation_id == 1) {
            $result = true;
        } else if ($designation_id == 5) {
            if ($page_department == $department_id) {
                $result = true;
            }
        } else if ($designation_id == 6) {
            if ($page_department == $department_id) {
                if ($page_sector == $section_id) {
                    $result = true;
                }
            }
        } else if ($designation_id >= 7) {
            if ($page_department == $department_id) {
                if ($page_sector  == $section_id) {
                    if ($page_directorate == $directorate_id) {
                        $result = true;
                    }
                }
            }
        }
    }
    return $result;
}

function get_page_actions() // for getting the permission to access individual page
{
    global $db, $designation_id;
    $path = get_current_url();
    $path = "dashboard";
    $stmt = $db->prepare("SELECT * FROM tbl_pages p INNER JOIN tbl_page_designations d ON p.id = d.page_id WHERE url=:url and d.designation_id=:designation_id");
    $stmt->execute(array(":designation_id" => $designation_id, ":url" => $path));
    $row_count = $stmt->rowCount();
    $row_stmt = $stmt->fetch();

    $permissions = [];
    if ($row_count > 0) { // ensure that the user can access the page
        $page_id = $row_stmt['page_id'];
        $sector_validation = page_sector($page_id); // ensure that the user can access if it is department specific
        if ($sector_validation) {
            $sql = $db->prepare("SELECT s.name FROM tbl_page_permissions p INNER JOIN tbl_permissions s ON p.page_id = s.id INNER JOIN tbl_designation_permissions d ON d.permission_id = s.id WHERE p.page_id=:page_id AND d.designation_id=:designation_id");
            $sql->execute(array(":page_id" => $page_id, ":designation_id" => $designation_id));
            $count = $sql->rowCount();

            if ($count > 0) {
                while ($row = $sql->fetch()) { // store the page actions for the page in an array
                    $permissions[] = $row['name'];
                }
            }
        }
    }
    return $permissions;
}
?>

<?php
$page_permissions = get_page_actions(); // for getting the permission to access individual page
// if user has no permissions in the array he/she cannot access the page
// if (!empty($page_permissions)) {
?>
<style>
    .sidemenu a:link {
        text-decoration: none;
    }

    .sidemenu .active {
        background-color: #b63b4d;
    }
</style>
<div class="menu">
    <div class="span2 sidemenu">
        <ul id="accordion" class="accordion">
            <?php
            // Access to sidebar detaills
            $sql = $db->prepare("SELECT * FROM tbl_pages WHERE parent=0");
            $sql->execute();
            while ($row = $sql->fetch()) {
                $parent_id = $row['id'];
                $icon = $row['icon'];
                $parent_name = $row['name'];
                $parent_validation = page_sector($parent_id);
                if ($parent_validation) {
                    $stmt = $db->prepare("SELECT * FROM tbl_pages p INNER JOIN tbl_page_designations d ON p.id = d.page_id WHERE parent=:parent and d.designation_id=:designation_id");
                    $stmt->execute(array(":parent" => $parent_id, ":designation_id" => $designation_id));
                    $row_count = $stmt->rowCount();
                    if ($row_count > 0) {
            ?>
                        <li class="<?php echo ($parent_id == $Id) ? "open" : ""; ?>">
                            <div class="link">
                                <?= $icon ?> <?= $parent_name ?>
                                <i class="fa fa-chevron-down" style="color:white"></i>
                            </div>
                            <ul class="submenu" style="<?php echo $parent_id == $Id ? "display: block;" : ""; ?>">
                                <?php
                                while ($child = $stmt->fetch()) {
                                    $child_id = $child['id'];
                                    $child_name = $child['name'];
                                    $child_url = $child['url'];
                                    $child_validation = page_sector($parent_id);
                                    if ($child_validation) {
                                ?>
                                        <li class="<?php echo $child_id == $subId ? 'active' : ''; ?>">
                                            <a href="<?= $child_url ?>.php">&nbsp; <?= $child_name ?></a>
                                        </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        <?php
                    }
                        ?>
                        </li>
                <?php
                }
            }
                ?>
                <li>
                    <div class="link">
                        <!-- <i class="fa fa-folder-open-o" style="color:white"></i> -->
                    </div>
                </li>
        </ul>
    </div>
</div>
<?php
// } else {
?>
<!-- <h4>403 ERROR Forbidden to access this page </h4> -->
<?php
// }
?>