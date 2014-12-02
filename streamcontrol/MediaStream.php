<?php

/**
 *
 * @author Noah Kipin
 */
interface MediaStream {
    
    public function getOutputPipe();
    
    public function close();
}
