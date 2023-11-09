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
            $sidebar_sql = $db->prepare("SELECT p.id, p.url,p.name,p.icon FROM tbl_pages p INNER JOIN tbl_page_designations d ON p.id = d.page_id WHERE parent=0 and d.designation_id=:designation_id ORDER BY p.priority ASC");
            $sidebar_sql->execute(array(":designation_id" => $designation_id));
            while ($row = $sidebar_sql->fetch()) {
                $parent_id = $row['id'];
                $sidebar_icon = $row['icon'];
                $parent_name = $row['name'];
                $parent_validation = page_sector($parent_id);
                if ($parent_validation) {
                    $stmt = $db->prepare("SELECT p.id, p.url,p.name FROM tbl_pages p INNER JOIN tbl_page_designations d ON p.id = d.page_id WHERE parent=:parent and d.designation_id=:designation_id ORDER BY p.priority ASC");
                    $stmt->execute(array(":parent" => $parent_id, ":designation_id" => $designation_id));
                    $row_count = $stmt->rowCount();
                    if ($row_count > 0) {
            ?>
                        <li class="<?php echo ($parent_id == $Id) ? "open" : ""; ?>">
                            <div class="link">
                                <?= $sidebar_icon ?> <?= $parent_name ?>
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
                                            <a href="<?= $child_url ?>.php">&nbsp; <?= $child_name  ?></a>
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