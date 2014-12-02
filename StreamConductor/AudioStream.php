<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AudioStream
 *
 * @author Noah
 */
class AudioStream extends MediaStream{
    
    protected $process;
    protected $pipes;

    public function __construct($path) {
        $this->getAudio($path);
    }
    
//    public function __destruct() {
//        $this->kill();
//    }

    protected function getAudio($path) {
        if (is_resource($this->process)) {
            throw new Exception('Transcoder already running.');
        }
        $pipes = NULL;

        $tmp_cmd = 'ffmpeg -i ' + $path + ' -';

        $this->process = proc_open($tmp_cmd, array(
            '0' => array('pipe', 'r'),
            '1' => array('pipe', 'w'),
            '2' => array('file', '/tmp/audio_error', 'a')
                ), $pipes);
        
        $this->pipes = $pipes;
    }
    
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

    public function close() {
        $this->closePipes();
        proc_terminate($this->process);
    }
    
    protected function closePipes() {
        fclose($this->pipes[0]);
        fclose($this->pipes[1]);
    }
    
    protected function kill(){
        $this->closePipes();
        proc_terminate($this->process, 9);
    }

    public function getOutputPipe(){
        return $this->pipes[1];
    }
    
    public function status(){
        return proc_get_status($this->process);
    }
}
