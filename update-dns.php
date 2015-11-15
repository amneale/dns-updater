<?php
/**
 * Simple script for update DNS record
 */

require_once 'vendor/autoload.php';

use DigitalOceanV2\Adapter\BuzzAdapter;
use DigitalOceanV2\DigitalOceanV2;

define('CONFIG_INI', 'config.ini');
define('DOMAIN_TYPE', 'A');

$config   = parse_ini_file(CONFIG_INI);
$serverIp = trim(file_get_contents('http://icanhazip.com/'));

$adapter      = new BuzzAdapter($config['access_token']);
$digitalOcean = new DigitalOceanV2($adapter);
$domainRecord = $digitalOcean->domainRecord();

$updated = false;
foreach ($domainRecord->getAll($config['domainName']) as $record) {
    if ($record->type == DOMAIN_TYPE && $record->name == $config['recordName']) {
        $domainRecord->updateData(
            $config['domainName'],
            $record->id,
            $serverIp
        );
        $updated = true;
        break;
    }
}

if (!$updated) {
    $domainRecord->create(
        $config['domainName'],
        DOMAIN_TYPE,
        $config['recordName'],
        $serverIp
    );
}