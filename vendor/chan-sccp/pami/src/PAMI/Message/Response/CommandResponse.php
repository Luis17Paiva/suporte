<?php
/**
 * A generic SCCP response message from ami.
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Response
 * @author     Diederik de Groot <ddegroot@talon.nl>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @version    SVN: $Id$
 * @link       http://marcelog.github.com/PAMI/
 *
 * Copyright 2019 Diederik de Groot <ddegroot@talon.nl>
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
use PAMI\Message\Event\EventMessage;
use PAMI\Exception\PAMIException;

/**
 * A generic SCCP response message from ami.
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Response
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @link       http://marcelog.github.com/PAMI/
 */
class CommandResponse extends Response
{
    protected $output = array();
    /**
     * Returns true if this response contains the Message: command output follow
     *
     * @return boolean
     */
    public function isCommandFinished()
    {
        return stristr($this->getMessage(), 'command output follows') !== false;
    }

    /**
     * Returns Command output result
     *
     * @return string
     */
    public function getCommandOutput()
    {
        return (string) implode(self::EOL, $this->getKey('Output'));
    }

    /**
     * Returns Command output result array
     *
     * @return array of string
     */
    public function getCommandOutputArray()
    {
        return $this->getKey('Output');
    }

    /**
     * Override Message.php setSanitizedKey implementation to parse Output strings generated by CommandAction
     *
     * @return array of string
     */
    protected function setSanitizedKey($key, $value)
    {
        $key = strtolower((string)$key);
        if ($key === 'actionid') {
            $this->keys[$key] = (string)$this->sanitizeInput($value);
        } elseif ($key === 'output') {
            array_push($this->output, (string)$this->sanitizeInput($value));
            $this->keys[$key] = $this->output;
        } else {
            $this->keys[$key] = $this->sanitizeInput($value);
        }
    }
    
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
        $this->completed = $this->isCommandFinished();
    }
}