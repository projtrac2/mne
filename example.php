
<?php

// $task_start_date = '2023-04-01';
// $task_end_date = '2024-05-01';
// $start_year = 2022;
// $duration = 4;

// $start_date  = $half = false;
// for ($i = 0; $i < $duration; $i++) {
//     $end_year = $start_year + 1;
//     $project_start_date = $start_year . '-07-01';
//     $project_end_date = $end_year . '-06-30';

//     $first_half_project_start_date = $start_year . '-07-01';
//     $first_half_project_end_date = $start_year . '-12-31';

//     $second_half_project_start_date = $end_year . '-01-01';
//     $second_half_project_end_date = $end_year . '-06-30';


//     if ($task_start_date >= $project_start_date && $task_start_date <= $project_end_date) {
//         $start_date = true;
//     }


//     if ($start_date) {
//         if ($task_end_date >= $project_start_date &&  $task_end_date <= $project_end_date) {
//             if ($task_start_date >= $first_half_project_start_date && $task_start_date <= $first_half_project_end_date) {
//                 echo  "Start date " . $first_half_project_start_date . " is not before end year" . $first_half_project_end_date;
//             }

//             if ($task_end_date >= $second_half_project_start_date && $task_end_date <= $second_half_project_end_date) {
//                 echo  "Start date " . $second_half_project_start_date . " is not before " . $second_half_project_end_date;
//             }
//             return;
//         } else {
//             if ($task_start_date >= $first_half_project_start_date && $task_start_date <= $first_half_project_end_date) {
//                 echo "Start date " . $first_half_project_start_date . " is not before end year" . $first_half_project_end_date;
//                 $half = true;
//             }else if ($task_start_date >= $second_half_project_start_date && $task_start_date <= $second_half_project_end_date) {
//                 echo "Start date " . $second_half_project_start_date . " is not before " . $second_half_project_end_date;
//                 $half = true;
//             }

//             if($half){
//                 echo "Start date " . $first_half_project_start_date . " is not before end year" . $first_half_project_end_date;
//                 echo  "Start date " . $second_half_project_start_date . " is not before " . $second_half_project_end_date;
//             }
//         }
//     }
//     $start_year++;
// }

$task_start_date = '2023-04-01';
$task_end_date = '2024-05-01';
$start_year = 2022;
$duration = 4;

$start_date  = false;
$financial_years = [];
for ($i = 0; $i < $duration; $i++) {
    $end_year = $start_year + 1;
    $project_start_date = $start_year . '-07-01';
    $project_end_date = $end_year . '-06-30';
    if ($task_start_date >= $project_start_date && $task_start_date <= $project_end_date) {
        $start_date = true;
    }

    if ($start_date) {
        if ($task_end_date >= $project_start_date &&  $task_end_date <= $project_end_date) {
            // echo  "Start date " . $project_start_date . " is not before " . $project_end_date;
            $financial_years[] = $start_year;
            break;
            // return;
        } else {
            // echo  "Start date " . $project_start_date . " is not before " . $project_end_date;
            $financial_years[] = $start_year;
        }
    }
    $start_year++;
}

$total_financial_years = count($financial_years);

//     $first_half_project_start_date = $start_year . '-07-01';
//     $first_half_project_end_date = $start_year . '-12-31';

//     $second_half_project_start_date = $end_year . '-01-01';
//     $second_half_project_end_date = $end_year . '-06-30';

for ($i = 0; $i < $total_financial_years; $i++) {
    $financial_year = $financial_years[$i];
    $end_financial_year = $financial_year + 1;
    $first_half_project_start_date = $financial_year . '-07-01';
    $first_half_project_end_date = $financial_year . '-12-31';

    $second_half_project_start_date = $end_financial_year . '-01-01';
    $second_half_project_end_date = $end_financial_year . '-06-30';


    if ($total_financial_years - $i == 1) {
        if ($task_start_date >= $first_half_project_start_date && $task_start_date <= $first_half_project_end_date) {
            echo  "Start date " . $first_half_project_start_date . " is not before end year" . $first_half_project_end_date;
        }

        if ($task_end_date >= $second_half_project_start_date && $task_end_date <= $second_half_project_end_date) {
            echo  "Start date " . $second_half_project_start_date . " is not before " . $second_half_project_end_date;
        }
    } else {
        // echo  "Start date " . $project_start_date . " is not before " . $project_end_date;
        $financial_years[] = $start_year;
    }
    echo $financial_year;
}
