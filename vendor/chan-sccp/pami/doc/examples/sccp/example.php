#!/usr/bin/env php
<?php
declare(ticks=1);
/**
 * PAMI basic use example.
 *
 * PHP Version 5
 *
 * @category Pami
 * @author   Marcelo Gornstein <marcelog@gmail.com>
 * @license  http://www.noneyet.ar/ Apache License 2.0
 * @version  SVN: $Id$
 * @link     http://www.noneyet.ar/
 *
 * Copyright 2011 Marcelo Gornstein <marcelog@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */
if ($argc != 5) {
    echo "Use: $argv[0] <host> <port> <user> <pass>\n";
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

////////////////////////////////////////////////////////////////////////////////
// Mandatory stuff to bootstrap.
////////////////////////////////////////////////////////////////////////////////
require(implode(DIRECTORY_SEPARATOR, array(
    __DIR__,
    '..',
    '..',
    '..',
    'vendor',
    'autoload.php'
)));
use PAMI\Client\Impl\ClientImpl;
use PAMI\Listener\IEventListener;
use PAMI\Message\Event\EventMessage;
use PAMI\Message\Action\GetConfigJSONAction;
use PAMI\Message\Action\ListCommandsAction;
use PAMI\Message\Action\SCCPShowDevicesAction;
use PAMI\Message\Action\SCCPShowGlobalsAction;
use PAMI\Message\Action\SCCPShowLinesAction;
use PAMI\Message\Action\SCCPShowDeviceAction;
use PAMI\Message\Action\SCCPShowLineAction;
use PAMI\Message\Action\SCCPShowChannelsAction;
use PAMI\Message\Action\SCCPShowSessionsAction;
use PAMI\Message\Action\SCCPConfigMetaDataAction;
use PAMI\Message\Event\SuccessfulAuthEvent;
use PAMI\Message\Event\SCCPGlobalSettingsEvent;
use PAMI\Message\Event\TableStart;
use PAMI\Message\Event\TableEnd;
use PAMI\Message\Event\SCCPLineEntryEvent;
use PAMI\Message\Event\SCCPShowDeviceEvent;
use PAMI\Message\Event\SCCPDeviceEntryEvent;
use PAMI\Message\Event\SCCPDeviceButtonEntryEvent;
use PAMI\Message\Event\SCCPDeviceFeatureEntryEvent;
use PAMI\Message\Event\SCCPDeviceSpeeddialEntryEvent;
use PAMI\Message\Event\SCCPDeviceLineEntryEvent;
use PAMI\Message\Event\SCCPShowLinesCompleteEvent;
use PAMI\Message\Event\SCCPShowLineCompleteEvent;
use PAMI\Message\Event\SCCPShowGlobalsCompleteEvent;
use PAMI\Message\Event\SCCPShowDevicesCompleteEvent;
use PAMI\Message\Event\SCCPShowDeviceCompleteEvent;
use PAMI\Message\Event\SCCPShowChannelsCompleteEvent;
use PAMI\Message\Event\SCCPShowSessionsCompleteEvent;

class EventListener implements IEventListener
{
    public function handle(EventMessage $event)
    {
        //var_dump($event);
    }
}
////////////////////////////////////////////////////////////////////////////////
// Code STARTS.
////////////////////////////////////////////////////////////////////////////////
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
    $pami->registerEventListener(new EventListener());
    $pami->open();

    // Retrieve json info from chan_sip
    $response = $pami->send(new GetConfigJSONAction("sip.conf"));
    var_dump($response->getJSON());

    // Retrieve config meta data json info from chan_sccp
    $response = $pami->send(new SCCPConfigMetaDataAction());
    print_r($response->getJSON());

    // Retrieve config meta data json info from chan_sccp for a general section
    $response = $pami->send(new SCCPConfigMetaDataAction("general"));
    print_r($response->getJSON());

    // Retrieve config meta data json info from chan_sccp for a device section
    $response = $pami->send(new SCCPConfigMetaDataAction("device"));
    print_r($response->getJSON());

    // Retrieve SCCPShowGlobals returning big response section
    $response = $pami->send(new SCCPShowGlobalsAction());
    if ($response->isSuccess()) {
        print "op\n";
        print "KeepAlive: " . $response->getKey("KeepAlive") . "\n";
        print "ConfigFile: " . $response->getKey("ConfigFile") . "\n";
        print "CodecsPreference: ";
        print_r($response->getKey("CodecsPreference"));
        print "\n";
        if ($response->isList()) {
            print "ok\n";
            $events = $response->getEvents();
            foreach ($events as $event) {
                if ($event->getName() == "SCCPGlobalSettings") {
                    print "KeepAlive: " . $event->getKeepAlive() . "\n";
                    print "ConfigFile: " . $event->getConfigFile() . "\n";
                    print "CodecsPreference: ";
                    print_r($event->getCodecsPreference());
                    print "\n";
                }
            }
        }
    }

    // Retrieve SCCPShowDevices returning multiple device sections
    $response = $pami->send(new SCCPShowDevicesAction());
    if ($response->isList()) {
        $events = $response->getEvents();
        foreach ($events as $event) {
            if ($event->getName() == "SCCPDeviceEntry") {
                print_r($event);
            }
        }
    }

    // Retrieve SCCPShowLines returning multiple line sections
    $response = $pami->send(new SCCPShowLinesAction());
    if ($response->isList()) {
        $events = $response->getEvents();
        foreach ($events as $event) {
            if ($event->getName() == "SCCPLineEntry") {
                print_r($event);
            }
        }
    }

    // Retrieve SCCPShowDevice returning multiple sections
    $response = $pami->send(new SCCPShowDeviceAction("SEP0023043403F9"));
    if ($response->isList()) {
        $events = $response->getEvents();
        print ("DeviceName" . $response->getDeviceName() . "\n");
        print ("\nDenyPermit:\n");
        print_r($response->getDenyPermit());
        print ("\nCaps:\n");
        print_r($response->getCapabilities());
        print ("\nPrefs:\n");
        print_r($response->getCodecsPreference());
    }
    $tableNames = $response->getTableNames();
    foreach ($tableNames as $tableName) {
        print "Table: $tableName\n";
        $method = 'get' . $tableName;
        $table = $response->$method();
        foreach ($table as $entry) {
            print_r($entry);
        }
    }

    // Retrieve SCCPShowLine returning multiple sections
    var_dump($pami->send(new SCCPShowLineAction("98031")));

    $time = time();
    while ((time() - $time) < $options['connect_timeout']) {
        usleep(10000);                  // wait 10 ms
        $pami->process();               // poll pami to see if anything happened
    }
    $pami->close(); // send logoff and close the connection.
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}
////////////////////////////////////////////////////////////////////////////////
// Code ENDS.
////////////////////////////////////////////////////////////////////////////////
