<?php 

class Crontab {
    // In this class, array instead of string would be the standard input / output format.
    // Legacy way to add a job:
    // $output = shell_exec('(crontab -l; echo "'.$job.'") | crontab -');
    
    static private function stringToArray($jobs = '') {
        $array = explode("\r\n", trim($jobs)); // trim() gets rid of the last \r\n
        foreach ($array as $key => $item) {
            if ($item == '') {
                unset($array[$key]);
            }
        }
        return $array;
    }
    
    // method to convert array to string 
    static private function arrayToString($jobs = array()) {
        $string = implode("\r\n", $jobs);
        return $string;
    }
    
    // function to get all existing cron jobs in crontab
    static public function getJobs() {
        $output = shell_exec('crontab -l');
        return self::stringToArray($output);
    }
    
    // method to save the cronjob to crontab
    static public function saveJobs($jobs = array()) {
        $output = shell_exec('echo "'.self::arrayToString($jobs).'" | crontab -');
        return $output;	
    }
    
    // method to check if a cronjob already exist in crontab
    static public function doesJobExist($job = '') {
        $jobs = self::getJobs();
        if (in_array($job, $jobs)) {
            return true;
        } else {
            return false;
        }
    }
    
    // method to add a new cronjob to crontab
    static public function addJob($job = '') {
        if (self::doesJobExist($job)) {
            return false;
        } else {
            $jobs = self::getJobs();
            $jobs[] = $job;
            return self::saveJobs($jobs);
        }
    }
    
    // method to remove a scheduled cronjob
    static public function removeJob($job = '') {
        if (self::doesJobExist($job)) {
            $jobs = self::getJobs();
            unset($jobs[array_search($job, $jobs)]);
            return self::saveJobs($jobs);
        } else {
            return false;
        }
    }
    
}

$data  = new Crontab();

var_dump($data->getJobs());
// Crontab::getJobs();
    // Adding cron job:
// Crontab::addJob('*/1 * * * * php /home/evans/Desktop/Projtrac System/Schedular/script.php'); //runr s every minute 
    // Removing cron job:
// Crontab::removeJob('*/1 * * * * php /home/evans/Desktop/Projtrac/Schedular/script.php'); 
?>