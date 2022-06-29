<?php
session_start();

$sql = "-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.14 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table icc_endirecto.price_list
CREATE TABLE IF NOT EXISTS `price_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT '0',
  `name` varchar(50) DEFAULT '0',
  `type` varchar(50) DEFAULT '0',
  `place` varchar(50) DEFAULT '0',
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `season` varchar(50) DEFAULT '0',
  `plan` varchar(50) DEFAULT '0',
  `price_pax_double` int(11) DEFAULT '0',
  `price_simple` int(11) DEFAULT '0',
  `price_tripled` int(11) DEFAULT '0',
  `price_dinner` int(11) DEFAULT '0',
  `hab_doble` int(11) DEFAULT '0',
  `hab_simple` int(11) DEFAULT '0',
  `hab_tripled` int(11) DEFAULT '0',
  `offert` varchar(50) DEFAULT '0',
  `offert_validity` varchar(50) DEFAULT '0',
  `offert_from` date DEFAULT NULL,
  `offert_to` date DEFAULT NULL,
  `provider` varchar(50) DEFAULT '0',
  `kids_policy` varchar(50) DEFAULT '0',
  `room_vacancy` varchar(50) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table icc_endirecto.price_transport
CREATE TABLE IF NOT EXISTS `price_transport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL DEFAULT '',
  `from_place` varchar(50) NOT NULL DEFAULT '',
  `to_place` varchar(50) NOT NULL DEFAULT '',
  `vehicle_type` varchar(50) NOT NULL DEFAULT '',
  `vehicle_max_passenger` int(11) NOT NULL DEFAULT '0',
  `vehicle_price` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

ALTER TABLE `general_users`
ADD 	`fullname` varchar(50) DEFAULT NULL,
		`position` varchar(50) DEFAULT NULL,
  		`gender` varchar(50) NOT NULL DEFAULT 'male',
  		`avatar` varchar(50) NOT NULL DEFAULT 'avatar-male-1';
  		
-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;";

//require_once("./core/config.php");
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

class my_ZipArchive extends ZipArchive
{
    public function extractSubdirTo($destination, $subdir)
    {
        $errors = array();

        // Prepare dirs
        $destination = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $destination);
        $subdir = str_replace(array("/", "\\"), "/", $subdir);

        if (substr($destination, mb_strlen(DIRECTORY_SEPARATOR, "UTF-8") * -1) != DIRECTORY_SEPARATOR)
            $destination .= DIRECTORY_SEPARATOR;

        if (substr($subdir, -1) != "/")
            $subdir .= "/";

        // Extract files
        for ($i = 0; $i < $this->numFiles; $i++) {
            $filename = $this->getNameIndex($i);

            if (substr($filename, 0, mb_strlen($subdir, "UTF-8")) == $subdir) {
                $relativePath = substr($filename, mb_strlen($subdir, "UTF-8"));
                $relativePath = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $relativePath);

                if (mb_strlen($relativePath, "UTF-8") > 0) {
                    if (substr($filename, -1) == "/")  // Directory
                    {
                        // New dir
                        if (!is_dir($destination . $relativePath))
                            if (!@mkdir($destination . $relativePath, 0755, true))
                                $errors[$i] = $filename;
                    } else {
                        if (dirname($relativePath) != ".") {
                            if (!is_dir($destination . dirname($relativePath))) {
                                // New dir (for file)
                                @mkdir($destination . dirname($relativePath), 0755, true);
                            }
                        }

                        // New file
                        if (@file_put_contents($destination . $relativePath, $this->getFromIndex($i)) === false)
                            $errors[$i] = $filename;
                    }
                }
            }
        }

        return $errors;
    }
}
$update_file = "https://github.com/striderskynet/ICC/archive/refs/heads/master.zip";
/*
try {
    $conn = new mysqli($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['data']);
    $conn->multi_query($sql);
} catch (Exception $e) {
    $error = $e->getMessage();
    die($error);
}

echo "Updating database...<br>\n";
*/
$headers = get_headers($update_file, 1);
$clen = $headers['Content-Length'][1];
/*
$size = $clen;
switch ($clen) {
    case $clen < 1024:
        $size = $clen . ' B';
        break;
    case $clen < 1048576:
        $size = round($clen / 1024, 2) . ' KiB';
        break;
    case $clen < 1073741824:
        $size = round($clen / 1048576, 2) . ' MiB';
        break;
    case $clen < 1099511627776:
        $size = round($clen / 1073741824, 2) . ' GiB';
        break;
}*/

download($update_file, "./icc-master.zip");

//echo "Downloading file...<br>\n";
/*
rename("./core/config.php", "./core/config_old.php");

$zip = new my_ZipArchive();
if ($zip->open("./icc-master.zip") === TRUE) {
    $errors = $zip->extractSubdirTo("./", "ICC-master/");
    $zip->close();
    echo 'ok, errors: ' . count($errors);
} else {
    echo 'failed';
}

session_destroy();
*/