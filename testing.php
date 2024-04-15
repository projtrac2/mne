<?php

function exception_handler(Throwable $exception) {
    echo "Uncaught exception: " , $exception->getMessage(), "\n";
  }
  
  set_exception_handler('exception_handler');
    function get_page_actions() // for getting the permission to access individual page 
    {
        try {
            //code...
            global $db;
            $designation_id = 1;
            $path = "dashboard";
            $stmt = $db->prepare("SELECT * FROM tbl_pages p INNER JOIN tbl_page_designations d ON p.id = d.page_id WHERE url=:url and d.designation_id=:designation_id");
            $stmt->execute(array(":designation_id" => $designation_id));
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
            print_r('peter');
            return $permissions;
        } catch (PDOException $th) {
            customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
        }
    }

    try {
        get_page_actions();
    } catch (\PDOException $th) {
        customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
    }

