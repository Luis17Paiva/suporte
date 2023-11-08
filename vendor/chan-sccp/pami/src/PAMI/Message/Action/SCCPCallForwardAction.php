<?php
/**
 * SCCPCallforward action message.
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Action
 * @author     Diederik de Groot <ddegroot@users.sf.net>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @version    SVN: $Id$
 * @link       http://marcelog.github.com/PAMI/
 *
 * Copyright 2015 Diederik de Groot ddegroot@users.sf.net>, Marcelo Gornstein <marcelog@gmail.com>
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
namespace PAMI\Message\Action;

use PAMI\Exception\PAMIException;

/**
 * SCCP Set Callforward on a line/device action message.
 *
 * @author     Diederik de Groot <ddegroot@users.sf.net>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @link       http://marcelog.github.com/PAMI/
 */
class SCCPCallForwardAction extends ActionMessage
{
    /**
     * Constructor.
     *
     * @param string $lineName LineName
     * @param string $deviceId DeviceId
     * @param string $destination
     * @param string $type ['none', 'all', 'busy']
     *
     * @return void
     */
    public function __construct($lineName, $deviceId, $destination = null, $type = 'none')
    {
        parent::__construct('SCCPCallforward');
        
        $this->setKey('LineName', $lineName);
        $this->setKey('DeviceId', $deviceId);
        if (!in_array(strtolower($type), array('none', 'all', 'busy'))) {
            throw new PAMIException('Type has to be one of \'none\', \'all\', \'busy\'.');
        }

        if ($type != 'none') {
            if (isset($destination)) {
                $this->setKey('Type', $type);
                $this->setKey('Destination', $destination);
            } else {
                throw new PAMIException('You cannot have type:$type without a destination');
            }
        } else {
            $this->setKey('Type', $type);
        }
    }
}
