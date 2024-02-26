<?php
    // Use sdk for API configuration and POST rest calls.
    require("services/CustumApi.php");

    // Recovery of an instance of the main class of the sdk: API + configuration of the data of the developer.
    //$custumAPI = CustumAPI::getInstance(app_key, app_secret,app_id)
    $custumApi = CustumAPI::getInstance( "B5kaGoFf5GtmXqCjAHAQu5SsQFeURWp6","si33xcM1LpG1LVYWteDNU81l30TpZNhH","05a90502-1c9e-49b1-8fbd-72d34863f55f");

    // Developer authentication and recovery of the access token or update of it.
    $acessTk = $custumApi->oauthAuthenticate();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $phoneNumber = $_POST["phoneNumber"];
        $message = $_POST["message"];

        // Display the submitted data
        echo "PhoneNumber: " . $phoneNumber . "<br>";
        echo "Message: " . $message . "<br>";
    }

    // Balance request 
    $resBalance = $custumApi->requestGetSmsBalance();
    echo "\n \n \n";
    var_dump($resBalance);

    // get sms list.
    $smsList = $custumApi->requestGetSmsList();
    echo "\n \n \n";
    var_dump($smsList);

    // Send sms request to a list of mobile phone.
    $sendSms = $custumApi->requestSimpleSms([$phoneNumber], $message);
    echo "\n \n \n";
    var_dump($sendSms);
?>
