<?php
/**
 * Simple script for update DNS record
 */

require_once 'vendor/autoload.php';

use DigitalOceanV2\Adapter\BuzzAdapter;
use DigitalOceanV2\DigitalOceanV2;

define('CONFIG_INI', 'config.ini');
define('DOMAIN_TYPE', 'A');


try {
    $config = parse_ini_file(CONFIG_INI, true);
    $serverIp = file_get_contents('http://www.icanhazip.com/');

    if ($serverIp === false) {
        throw new Exception('Failed to retrieve server IP');
    }
    $serverIp = trim($serverIp);

    $adapter      = new BuzzAdapter($config['access_token']);
    $digitalOcean = new DigitalOceanV2($adapter);
    $domainRecord = $digitalOcean->domainRecord();

    $domains = (array) $config['domain'];
    $records = $config['record'];

    foreach ($domains as $domain) {
        $records = is_array($records) ? $records[$domain] : (array) $records;
        foreach ($records as $recordName) {
            $updated = false;
            foreach ($domainRecord->getAll($domain) as $record) {
                if ($record->type == DOMAIN_TYPE && $record->name == $recordName) {
                    $domainRecord->updateData(
                        $domain,
                        $record->id,
                        $serverIp
                    );
                    $updated = true;
                    break;
                }
            }

            if (!$updated) {
                $domainRecord->create(
                    $domain,
                    DOMAIN_TYPE,
                    $recordName,
                    $serverIp
                );
            }
        }
    }

} catch (Exception $e) {
    error_log($e->getMessage());
}
