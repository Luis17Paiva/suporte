#!/usr/bin/env php
<?php
declare(ticks=1);
if ($argc != 6) {
    echo "Use: $argv[0] <host> <port> <user> <pass> <deviceid>\n";
    exit(254);
}

// Setup include path.
ini_set(
    'include_path',
    implode(
        PATH_SEPARATOR,
        array(
            implode(DIRECTORY_SEPARATOR, array('..', '..', '..', 'src', 'mg')),
            '../../../vendor/php/log4php', '../../../vendor/php',
            ini_get('include_path'),
        )
    )
);

require(implode(DIRECTORY_SEPARATOR, array(
    __DIR__,
    'vendor',
    'autoload.php'
)));
use PAMI\Client\Impl\ClientImpl;
use PAMI\Listener\IEventListener;
use PAMI\Message\Event\EventMessage;
use PAMI\Message\Action\SCCPShowDevicesAction;
use PAMI\Message\Action\SCCPShowDeviceAction;
use PAMI\Message\Event\SuccessfulAuthEvent;
use PAMI\Message\Event\TableStart;
use PAMI\Message\Event\TableEnd;
use PAMI\Message\Event\SCCPShowDeviceEvent;
use PAMI\Message\Event\SCCPDeviceEntryEvent;
use PAMI\Message\Event\SCCPDeviceButtonEntryEvent;

error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    $options = array(
        'log4php.properties' => realpath(__DIR__) . DIRECTORY_SEPARATOR . 'log4php.properties',
        'host' => $argv[1],
        'port' => $argv[2],
        'username' => $argv[3],
        'secret' => $argv[4],
        'connect_timeout' => 2,
        'read_timeout' => 2,
    );
    $pami = new ClientImpl($options);
    $pami->open();

    $response = $pami->send(new SCCPShowDeviceAction($argv[5]));
    print("Device:" . $argv[5]. ":\n");
    $linebuttons = $response->getLineButtons();
    foreach($linebuttons as $entry) {
        print(" - LineButton:\n");
        if ($entry instanceof PAMI\Message\Event\SCCPDeviceLineEntryEvent) {
            print("   - Name:" . $entry->getName() . "\n");
            print("   - Label:" . $entry->getLabel() . "\n");
            print("   - Id:" . $entry->getId() . "\n");
            print("   - Suffix:" . $entry->getSuffix() . "\n");
            print("   - CfwdType:" . $entry->getCfwdType() . "\n");
            print("   - CfwdNumber:" . $entry->getCfwdNumber() . "\n");
        }
    }
    
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}
?>
