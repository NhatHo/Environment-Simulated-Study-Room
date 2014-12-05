<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SlideshowStream
 *
 * @author Noah Kipin
 */
class SlideshowStream {
    
    protected $process;
    protected $pipes;

    /**
     * 
     * @param type $path the path to the photo directory. Specify photos with 
     *      glob syntax.
     * @param int $displayDuration seconds to display each frame. (Default = 5)
     *      (min 1; max 3600)
     */
    public function __construct($path, $displayDuration = 5) {
        if($displayDuration > 3600 || $displayDuration < 1){
            $displayDuration = 5;
        }
        $framerate = '1/'.sprintf("%u",floor($displayDuration));
        
        $this->setupSlideshow($path, $framerate);
    }
    
//    public function __destruct() {
//        $this->kill();
//    }
    
    /**
     * 
     * @param type $path
     * @param type $framerate
     */
    protected function setupSlideshow($path, $framerate) {
        $cmd = 'ffmpeg -loop 1 -framerate '.$framerate.'-pattern_type glob -i \''
                .trim($path).'\' -c:v libx264 -';
        
        $this->process = proc_open($cmd, array(
            '0' => array('pipe', 'r'),
            '1' => array('pipe', 'w'),
            '2' => array('file', '/tmp/slideshow_error', 'a')
                ), $pipes);
        
        $this->pipes = $pipes;
    }
    
    /**
     * returns the pid of the running process or False otherwise
     * @return int
     */
    public function getPid() {
        $status = proc_get_status($this->process);
        if($status) {
            if($status['stopped'] == FALSE || $status['running'] == TRUE) {
                return $status['pid'];
            }
            else{
                return FALSE;
            }
        }
        else {
            return FALSE;
        }
    }
    
    /**
     * @return resource file descriptor for the pipe of media output
     */
    public function getOutputPipe() {
        return $this->pipes[1];
    }
    
    /**
     * 
     */
    protected function closePipes() {
        fclose($this->pipes[0]);
        fclose($this->pipes[1]);
    }
    
    /**
     * 
     */
    public function close() {
        $this->closePipes();
        proc_terminate($this->process);
    }

    /**
     * 
     */
    protected function kill() {
        $this->closePipes();
        proc_terminate($this->process, 9);
    }
}
