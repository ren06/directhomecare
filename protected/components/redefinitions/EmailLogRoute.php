<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileLogRoute
 *
 * @author Renaud
 */
class EmailLogRoute extends CFileLogRoute {

    protected function formatLogMessage($message, $level, $category, $time) {
 
        $messageNoStack = strtok($message, "\n");
        
        return @date('Y/m/d H:i:s',$time)." [$level] [$category] $messageNoStack\n";
       
    }

}

?>
