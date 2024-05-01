<?php
include '../controller.php';
try {
    if (isset($_GET['get_company_coordinates'])) {
        $query_company =  $db->prepare("SELECT * FROM tbl_company_settings");
        $query_company->execute();
        $row_company = $query_company->fetch();
        $total_company = $query_company->rowCount();
        $success = false;
        $company_latitude = $company_longitude = '';
        if ($total_company > 0) {
            $success = true;
            $company_latitude = $row_company['latitude'];
            $company_longitude = $row_company['longitude'];
        }

        echo json_encode(array('success' => $success, 'latitude' => $company_latitude, 'longitude' => $company_longitude));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
