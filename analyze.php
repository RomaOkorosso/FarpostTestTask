<?php
$allowed_process_time = 0;
$min_uptime_percents = 0.0;
$response_count = 0;
$is_test = false;
const MIN_RESPONSE_CODE = 500;
const MAX_RESPONSE_CODE = 600;

function unset_all_values()
{
    $vars = array_keys(get_defined_vars());
    for ($i = 0; $i < sizeOf($vars); $i++) {
        echo $vars[$i] . "\n";
        unset($$vars[$i]);
    }
    unset($vars, $i);
}

if (!empty($argv)) {
    for ($i = 1; $i < count($argv); $i++) {
        if ($argv[$i] === "-u") {
            $min_uptime_percents = floatval($argv[$i + 1]);
        }
        if ($argv[$i] === "-t") {
            $allowed_process_time = intval($argv[$i + 1]);
        }
    }
}

class Analyzer
{

    static function analyze($min_uptime_percents, $allowed_process_time, $is_test = false, $filename = "access.log"):
    string
    {
        $start_time = "";
        $prev_time = "";
        $curr_time = "";
        $to_return = "";
        $response_with_errors = 0;
        $response_count = 0;
        if ($file = fopen($filename, "r")) {
            $uptime_with_errs_percents = 100.0;
            while (!feof($file)) {
                $line = fgets($file);
                if (empty($line)) {
                    if ($uptime_with_errs_percents < $min_uptime_percents) {
                        $result = strval($start_time . " " . $curr_time . " " . $uptime_with_errs_percents . "\n");
                        if (!$is_test) {
                            echo $result;
                        } else {
                            $to_return .= $result;
                        }
                    }
                    $response_with_errors = 0;
                    $response_count = 0;
                    $uptime_with_errs_percents = 100;
                    return $to_return;
                }
                $data = explode(" ", $line);
                $response_code = intval($data[8]);
                $process_time = floatval($data[10]);

                $response_count++;
                if (empty($start_time)) {
                    $start_time = str_replace("[", "", $data[3]);
                }
                if (empty($prev_time)) {
                    $prev_time = str_replace("[", "", $data[3]);
                } else {
                    $prev_time = $curr_time;
                }
                $curr_time = str_replace("[", "", $data[3]);

                if (($response_code >= MIN_RESPONSE_CODE && $response_code < MAX_RESPONSE_CODE) || ($process_time > $allowed_process_time)) {
                    $response_with_errors++;
                    $uptime_with_errs_percents = 100 - ($response_with_errors / $response_count) * 100;

                } else if ($uptime_with_errs_percents < $min_uptime_percents) {
                    $result = strval($start_time . " " . $prev_time . " " . $uptime_with_errs_percents . "\n");
                    if (!$is_test) {
                        echo $result;
                    } else {
                        $to_return .= $result;
                    }
                    $uptime_with_errs_percents = 100.0;
                    $response_count = 0;
                    $response_with_errors = 0;
                    $start_time = "";
                    $prev_time = "";
                    $curr_time = "";
                }
            }
            fclose($file);
        }
        $response_with_errors = 0;
        $response_count = 0;
        $uptime_with_errs_percents = 100;
        return $to_return;
    }
}

