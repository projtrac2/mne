<?php

function message_alerts($msg, $type, $url)
{
    $results = "";
    if ($type == 1) {
        $results =
            '<script type="text/javascript">
                swal({
                    title: "Success!",
                    text: "'.$msg.'",
                    type: "Success",
                    timer: 5000,
                    icon:"success",
                    showConfirmButton: false 
                });
            </script>';
    } else if ($type = 2) {
        $results =
            '<script type="text/javascript">
                swal({
                    title: "Error!!!",
                    text: "' . $msg . ' ",
                    type: "Error",
                    timer: 5000,
                    icon:"error",
                    showConfirmButton: false 
                });
            </script>';
    } else if ($type == 3) {
        $results =
            '<script type=\"text/javascript\">
                swal({
                    title: "Success!",
                    text: "' . $msg . '",
                    type: "Success",
                    timer: 5000,
                    icon:"success",
                    showConfirmButton: false 
                });
                setTimeout(function(){
                    window.location.href = ' . $url . ';
                }, 5000);
            </script>';
    }

    return $results;
}
