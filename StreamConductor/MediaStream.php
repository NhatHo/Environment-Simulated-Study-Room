<?php

/**
 *
 * @author Noah Kipin
 */
interface MediaStream {
    
    public function getPid();
    
    public function getOutputPipe();
    
    public function close();
}
