<?php
/**
 * A sccp show line response message from ami.
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Event
 * @author     Diederik de Groot <ddegroot@users.sf.net>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @version    SVN: $Id$
 * @link       http://marcelog.github.com/PAMI/
 *
 * Copyright 2015 Diederik de Groot <ddegroot@users.sf.net>, Marcelo Gornstein <marcelog@gmail.com>
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
namespace PAMI\Message\Response;

use PAMI\Message\Response\Response;
use PAMI\Message\Event\SCCPVariablesEntryEvent;
use PAMI\Message\Event\SCCPMailboxEntryEvent;

/**
 * A sccp show line response message from ami.
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Event
 * @author     Diederik de Groot <ddegroot@users.sf.net>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @link       http://marcelog.github.com/PAMI/
 */
class SCCPShowLineResponse extends ComplexResponse
{

    /**
     * Constructor.
     *
     * @param string $rawContent Literal message as received from ami.
     *
     * @return void
     */
    public function __construct($rawContent)
    {
        parent::__construct($rawContent);
    }
    
    private function getEventKey($keyname)
    {
        return $this->events[0]->getKey($keyname);
    }

    private function getEventBoolKey($keyname)
    {
        return $this->events[0]->getBoolKey($keyname);
    }
    
    /**
     * Returns key: 'Name'.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getEventKey('Name');
    }

    /**
     * Returns key: 'Description'.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getEventKey('Description');
    }

    /**
     * Returns key: 'Label'.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->getEventKey('Label');
    }

    /**
     * Returns key: 'ID'.
     *
     * @return integer
     */
    public function getID()
    {
        return intval($this->getEventKey('ID'));
    }

    /**
     * Returns key: 'Pin'.
     *
     * @return integer
     */
    public function getPin()
    {
        return intval($this->getEventKey('Pin'));
    }

    /**
     * Returns key: 'VoiceMailNumber'.
     *
     * @return string
     */
    public function getVoiceMailNumber()
    {
        return $this->getEventKey('VoiceMailNumber');
    }

    /**
     * Returns key: 'TransferToVoicemail'.
     *
     * @return string
     */
    public function getTransferToVoicemail()
    {
        return $this->getEventKey('TransferToVoicemail');
    }

    /**
     * Returns key: 'MeetMeEnabled'.
     *
     * @return boolean
     */
    public function getMeetMeEnabled()
    {
        return $this->getEventBoolKey('MeetMeEnabled');
    }

    /**
     * Returns key: 'MeetMeNumber'.
     *
     * @return string
     */
    public function getMeetMeNumber()
    {
        return $this->getEventKey('MeetMeNumber');
    }

    /**
     * Returns key: 'MeetMeOptions'.
     *
     * @return string
     */
    public function getMeetMeOptions()
    {
        return $this->getEventKey('MeetMeOptions');
    }

    /**
     * Returns key: 'Context'.
     *
     * @return string
     */
    public function getContext()
    {
        return $this->getEventKey('Context');
    }

    /**
     * Returns key: 'Language'.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->getEventKey('Language');
    }

    /**
     * Returns key: 'AccountCode'.
     *
     * @return string
     */
    public function getAccountCode()
    {
        return $this->getEventKey('AccountCode');
    }

    /**
     * Returns key: 'Musicclass'.
     *
     * @return string
     */
    public function getMusicclass()
    {
        return $this->getEventKey('Musicclass');
    }

    /**
     * Returns key: 'AmaFlags'.
     *
     * @return integer
     */
    public function getAmaFlags()
    {
        return intval($this->getEventKey('AmaFlags'));
    }

    /**
     * Returns key: 'CallGroup'.
     *
     * @return int[]
     */
    public function getCallGroup()
    {
        return array_map('intval', explode(",", $this->getEventKey('Callgroup')));
    }

    /**
     * Returns key: 'PickupGroup'.
     *
     * @return int[]
     */
    public function getPickupGroup()
    {
        return array_map('intval', explode(",", $this->getEventKey('Pickupgroup')));
    }

    /**
     * Returns key: 'NamedCallGroup'.
     *
     * @return string[]
     */
    public function getNamedCallGroup()
    {
        return explode(",", $this->getEventKey('NamedCallGroup'));
    }

    /**
     * Returns key: 'NamedPickupGroup'.
     *
     * @return string[]
     */
    public function getNamedPickupGroup()
    {
        return explode(",", $this->getEventKey('NamedPickupGroup'));
    }

    /**
     * Returns key: 'ParkingLot'.
     *
     * @return string
     */
    public function getParkingLot()
    {
        return $this->getEventKey('ParkingLot');
    }

    /**
     * Returns key: 'CallerIDName'.
     *
     * @return string
     */
    public function getCallerIDName()
    {
        return $this->getEventKey('CallerIDName');
    }

    /**
     * Returns key: 'CallerIDNumber'.
     *
     * @return string
     */
    public function getCallerIDNumber()
    {
        return $this->getEventKey('CallerIDNumber');
    }

    /**
     * Returns key: 'IncomingCallsLimit'.
     *
     * @return integer
     */
    public function getIncomingCallsLimit()
    {
        return intval($this->getEventKey('IncomingCallsLimit'));
    }

    /**
     * Returns key: 'ActiveChannelCount'.
     *
     * @return integer
     */
    public function getActiveChannelCount()
    {
        return intval($this->getEventKey('ActiveChannelCount'));
    }

    /**
     * Returns key: 'SecDialtoneDigits'.
     *
     * @return integer
     */
    public function getSecDialtoneDigits()
    {
        return intval($this->getEventKey('SecDialtoneDigits'));
    }

    /**
     * Returns key: 'SecDialtone'.
     *
     * @return integer
     */
    public function getSecDialtone()
    {
        /* can be either integer or hex -> convert hex to int */
        return intval($this->getEventKey('SecDialtone'), 0);
    }

    /**
     * Returns key: 'EchoCancellation'.
     *
     * @return boolean
     */
    public function getEchoCancellation()
    {
        return $this->getEventBoolKey('EchoCancellation');
    }

    /**
     * Returns key: 'SilenceSuppression'.
     *
     * @return boolean
     */
    public function getSilenceSuppression()
    {
        return $this->getEventBoolKey('SilenceSuppression');
    }

    /**
     * Returns key: 'CanTransfer'.
     *
     * @return boolean
     */
    public function getCanTransfer()
    {
        return $this->getEventBoolKey('CanTransfer');
    }

    /**
     * Returns key: 'DNDAction'.
     *
     * @return string
     */
    public function getDNDAction()
    {
        return $this->getEventKey('DNDAction');
    }

    /**
     * Returns key: 'IsRealtimeLine'.
     *
     * @return boolean
     */
    public function getIsRealtimeLine()
    {
        return $this->getEventBoolKey('IsRealtimeLine');
    }

    /**
     * Returns key: 'PendingDelete'.
     *
     * @return boolean
     */
    public function getPendingDelete()
    {
        return $this->getEventBoolKey('PendingDelete');
    }

    /**
     * Returns key: 'PendingUpdate'.
     *
     * @return boolean
     */
    public function getPendingUpdate()
    {
        return $this->getEventBoolKey('PendingUpdate');
    }

    /**
     * Returns key: 'RegistrationExtension'.
     *
     * @return string
     */
    public function getRegistrationExtension()
    {
        return $this->getEventKey('RegistrationExtension');
    }

    /**
     * Returns key: 'RegistrationContext'.
     *
     * @return string
     */
    public function getRegistrationContext()
    {
        return $this->getEventKey('RegistrationContext');
    }

    /**
     * Returns key: 'AdhocNumberAssigned'.
     *
     * @return boolean
     */
    public function getAdhocNumberAssigned()
    {
        return $this->getEventBoolKey('AdhocNumberAssigned');
    }

    /**
     * Returns key: 'MessageWaitingNew'.
     *
     * @return integer
     */
    public function getMessageWaitingNew()
    {
        return intval($this->getEventKey('MessageWaitingNew'));
    }

    /**
     * Returns key: 'MessageWaitingOld'.
     *
     * @return integer
     */
    public function getMessageWaitingOld()
    {
        return intval($this->getEventKey('MessageWaitingOld'));
    }

    /**
     * Returns events[] related to AttachedDevices from the tables['AttachedDevices']
     *
     * @return events[]
     * @return PAMI\Message\Event\SCCPAttachedDeviceEntryEvent[]
     */
    public function getAttachedDevices()
    {
        $res = array();
        if ($this->hasTable() && array_key_exists('AttachedDevices', $this->tables)) {
            $res = $this->tables['AttachedDevices']['Entries'];
        }
        return $res;
    }

    /**
     * Returns an array of SCCPSCCPMailboxEntryEvent's
     *
     * The returned array contains PAMI\Message\Event\SCCPMailboxEntryEvent objects
     *
     * @return PAMI\Message\Event\SCCPMailboxEntryEvent[]
     */
    public function getMailboxes()
    {
        $res = array();
        if ($this->hasTable() && array_key_exists('Mailboxes', $this->tables)) {
            $res = $this->tables['Mailboxes']['Entries'];
        }
        return $res;
    }

    /**
     * Returns an array of SCCPVariableEntryEvent's
     *
     * The returned array contains PAMI\Message\Event\SCCPVariableEntryEvent objects
     *
     * @return PAMI\Message\Event\SCCPVariableEntryEvent[]
     */
    public function getVariables()
    {
        $res = array();
        if ($this->hasTable() && array_key_exists('Variables', $this->tables)) {
            $res = $this->tables['Variables']['Entries'];
        }
        return $res;
    }
}
