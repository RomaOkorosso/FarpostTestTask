<?php
$allowed_process_time = 0;
$min_uptime_percents = 0.0;
$response_count = 0;
$response_with_errors = 0;
$is_test = false;
const MIN_RESPONSE_CODE = 500;
const MAX_RESPONSE_CODE = 600;
if (!empty($argv)) {
    for ($i = 1; $i < count($argv); $i++) {
        if ($argv[$i] === "-u") {
            $min_uptime_percents = floatval($argv[$i + 1]);
            // echo "-u = ", $allowed_process_time, " type:", gettype($allowed_process_time), "\n";
        }
        if ($argv[$i] === "-t") {
            $allowed_process_time = intval($argv[$i + 1]);
            // echo "-t = ", $min_uptime_percents, " type:", gettype($min_uptime_percents), "\n";
        }
    }
}

class Analyzer
{

    static function analyze($min_uptime_percents, $allowed_process_time, $is_test = false,
                                   $filename = "access.log"): string
    {
        $to_return = "";
        global $response_count, $response_with_errors;
        if ($file = fopen($filename, "r")) {
            $entrance_log_time = "";
            while (!feof($file)) {
                $line = fgets($file);
                if (empty($line)) {
                    return "-1";
                }
                $data = explode(" ", $line);
                $response_code = intval($data[8]);
                $process_time = floatval($data[10]);
                $response_count++;

                if (empty($entrance_log_time)) {
                    $entrance_log_time = str_replace("[", "", $data[3]);
                }

                if (($response_code >= MIN_RESPONSE_CODE && $response_code < MAX_RESPONSE_CODE) ||
                    ($process_time > $allowed_process_time)) {

                    $response_with_errors++;
                    $uptime_with_errs_percents = 100 - ($response_with_errors / $response_count) * 100;

                    if ($uptime_with_errs_percents < $min_uptime_percents) {
                        $result = strval($entrance_log_time . " " . str_replace("[", "", $data[3]) . " " .
                            $uptime_with_errs_percents . "\n");
                        if (!$is_test) {
                            echo $result;
                        } else {
                            $to_return .= $result;
                        }
                    }
                    $response_count = 0;
                    $response_with_errors = 0;
                    $entrance_log_time = "";
                }
            }
            fclose($file);
        }
        return $to_return;
    }
}

Analyzer::analyze($min_uptime_percents, $allowed_process_time, false, "test1.log");
// echo Analyzer::analyze($min_uptime_percents, $allowed_process_time, true, "access.log");
