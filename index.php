<?php

// read input data from POST
$request = json_decode(file_get_contents('php://input'), true);

// set file name
$log_file_name = "my-sms-status-callbacks-logs.log";

// check if log file already exists
if (!file_exists($log_file_name)) {
	//create a file in not exists
    $log_file_create = fopen($log_file_name, 'w');
    fclose($log_file_create);
    chmod($log_file_name, 0755);
}

// Check that this is a delivery receipt.
if (!isset($request['messageId'])) {
    //save message to a specific log file
    error_log("This is not a delivery receipt\n", 3, $log_file_name);
    return;
}

//Check if your message has been delivered correctly.
if ($request['status']['name'] == 'Delivered') {
    error_log("Your message to ".$request['to']." (message id ".$request['messageId'].") was DELIVERED.\n", 3, $log_file_name);
    error_log("The cost was ".$request['price']."\n", 3, $log_file_name);
} elseif ($request['status']['name'] == 'Failed') {
    error_log("Your message to ".$request['to']." (message id ".$request['messageId'].") was FAILED.\n", 3, $log_file_name);
    error_log("The cost was ".$request['price']."\n", 3, $log_file_name);
} else {
    error_log("Your message to ".$request['to']." has a status of: ".$request['status']['name']."\n", 3, $log_file_name);  
}
