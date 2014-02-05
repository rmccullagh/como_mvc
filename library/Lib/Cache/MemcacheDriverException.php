<?php

namespace Lib\Cache;

use \Exception;

class MemcacheDriverException extends Exception {
    public function getXdebugMessage() {
        if($this->xdebug_message) {
            return 
            '<table>' .
                $this->xdebug_message .
            '</table>';
        }
    }
}

