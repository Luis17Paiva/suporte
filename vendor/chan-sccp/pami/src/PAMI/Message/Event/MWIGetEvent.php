<?php
/**
 * MWIGet Event
 *
 *
 * Note: Auto Generated using xsltproc
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Event
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @author     Diederik de Groot <ddegroot@gmail.com>
 * @license    http://github.com/chan-sccp/PAMI/LICENSE
 * @version    SVN: $Id$
 * @link       http://marcelog.github.com/PAMI/
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
namespace PAMI\Message\Event;

use PAMI\Message\Event\EventMessage;

/**
 * MWIGet Event
 *
 *
 * Note: Auto Generated using xsltproc
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Event
 * @author     Diederik de Groot <ddegroot@gmail.com>
 * @license    http://chan-sccp.github.com/PAMI/ Apache License 2.0
 * @link       http://github.com/chan-sccp/PAMI/
 */
class MWIGetEvent extends EventMessage
{
    /**
     * get Mailbox
     *
     * Returns:Specific mailbox ID.
     *
     * @return string
     */
    public function getMailbox()
    {
        return $this->getKey('Mailbox');
    }

    /**
     * get OldMessages
     *
     * Returns:The number of old messages in the mailbox.
     *
     * @return string
     */
    public function getOldMessages()
    {
        return $this->getKey('OldMessages');
    }

    /**
     * get NewMessages
     *
     * Returns:The number of new messages in the mailbox.
     *
     * @return string
     */
    public function getNewMessages()
    {
        return $this->getKey('NewMessages');
    }
}
