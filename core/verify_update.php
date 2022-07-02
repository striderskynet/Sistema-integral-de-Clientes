<?php
require_once("config.php");

print_r($_POST);
//die();
function download($file_source, $file_target)
{
    $rh = fopen($file_source, 'rb');
    $wh = fopen($file_target, 'w+b');
    if (!$rh || !$wh) {
        return false;
    }

    while (!feof($rh)) {
        if (fwrite($wh, fread($rh, 4096)) === FALSE) {
            return false;
        }
        echo fstat($wh)['size'] . "\r";
        flush();
    }

    fclose($rh);
    fclose($wh);

    return true;
}

function retrieve_remote_file_size($url)
{
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, TRUE);

    $data = curl_exec($ch);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

    curl_close($ch);
    return $size;
}

function cURLcheckBasicFunctions()
{
    if (
        !function_exists("curl_init") &&
        !function_exists("curl_setopt") &&
        !function_exists("curl_exec") &&
        !function_exists("curl_close")
    ) return false;
    else return true;
}

/*
 * Returns string status information.
 * Can be changed to int or bool return types.
 */
function cURLdownload($url, $file)
{
    if (!cURLcheckBasicFunctions()) return "UNAVAILABLE: cURL Basic Functions";
    $ch = curl_init();
    if ($ch) {

        $fp = fopen($file, "w");
        if ($fp) {
            if (!curl_setopt($ch, CURLOPT_URL, $url)) {
                fclose($fp); // to match fopen()
                curl_close($ch); // to match curl_init()
                return "FAIL: curl_setopt(CURLOPT_URL)";
            }

            curl_setopt($ch, CURLOPT_USERAGENT, '"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.11) Gecko/20071204 Ubuntu/7.10 (gutsy) Firefox/2.0.0.11');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_REFERER, 'http://domain.com/');
            if (!curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false)) return "FAIL: curl_setopt(CURLOPT_FOLLOWLOCATION)";
            if (!curl_setopt($ch, CURLOPT_FILE, $fp)) return "FAIL: curl_setopt(CURLOPT_FILE)";
            if (!curl_setopt($ch, CURLOPT_HEADER, true)) return "FAIL: curl_setopt(CURLOPT_HEADER)";
            if (!curl_setopt($ch, CURLOPT_RETURNTRANSFER, true)) return "FAIL: curl_setopt(CURLOPT_RETURNTRANSFER)";
            if (!curl_setopt($ch, CURLOPT_FORBID_REUSE, false)) return "FAIL: curl_setopt(CURLOPT_FORBID_REUSE)";
            curl_setopt($ch, CURLOPT_USERAGENT, '"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.11) Gecko/20071204 Ubuntu/7.10 (gutsy) Firefox/2.0.0.11');
            // if( !curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true) ) return "FAIL: curl_setopt(CURLOPT_FOLLOWLOCATION)";
            // if( !curl_setopt($ch, CURLOPT_FILE, $fp) ) return "FAIL: curl_setopt(CURLOPT_FILE)";
            // if( !curl_setopt($ch, CURLOPT_HEADER, 0) ) return "FAIL: curl_setopt(CURLOPT_HEADER)";
            if (!curl_exec($ch)) return "FAIL: curl_exec()";
            curl_close($ch);
            fclose($fp);
            return "SUCCESS: $file [$url]";
        } else return "FAIL: fopen()";
    } else return "FAIL: curl_init()";
}


if (@isset($_POST['data'])) {
    $data = $_POST['data'];

    $data['dir'] = "updates" . DS . $data['tag_name'] . DS;

    if (!is_dir("updates")) {
        mkdir("updates", 0777);
    }

    if (!is_dir($data['dir'])) {
        mkdir($data['dir'], 0777);
    }

    $file_path = $data['dir'] . $data['tag_name'] . ".zip";


    //echo  cURLdownload($data['zipball_url'], $file_path);
    /* if (file_put_contents($file_path, file_get_contents($data['zipball_url']))) {
        echo "File downloaded successfully";
    } else {
        echo "File downloading failed.";
    }*/
    //echo retrieve_remote_file_size();
    //download($data['zipball_url'], $file_path);


    $opts = array('http' => array('header' => "    Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36\r\n"));
    //Basically adding headers to the request
    $context = stream_context_create($opts);


    file_put_contents($file_path, file_get_contents($data['zipball_url'], false, $context));


    print_r($_POST);
}
