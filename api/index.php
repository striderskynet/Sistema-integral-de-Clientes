<?php
    session_start();
    
    @require_once ('../core/config.php');

    $api_directory = _LOCAL . "api/";
   
    

    //debug(3, "Connecting to mysql database: " . str_replace("\n", "", var_export($database, true)));
    $db = @new db($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['data']);
   
    //Login session
    if (array_keys($_GET)[0] == "users"  && array_keys($_GET)[1] == "verify")
        require_once ($api_directory . "modules/users.php");

        /*
    if ( !isset($_SESSION['USER_ROLE']) )
        die("{Error:\":\"User not logged in, there is no client mode set}");*/

    switch(array_keys($_GET)[0]){
            case "users":
                require_once ($api_directory . "modules/users.php");
                break;
            case "clients":
                require_once ($api_directory . "modules/clients.php");
                break;
            case "vouchers":
                require_once ($api_directory . "modules/vouchers.php");
                break;
            case "prices":
                require_once ($api_directory . "modules/prices.php");
                break;
            case "query":
                require_once ($api_directory . "modules/query.php");
                break;
    }

    $db->close();
