<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RTPStream
 *
 * @author Noah
 */
class RTPStream {

    protected $inputPipe;
    
    protected $multicastAddress;
    protected $multicastPort;
    protected $sdpLocation;
    
    protected $process;
    protected $pipes;

    public function __construct(
            $streamingResource
            ,$address = '127.0.0.1'
            ,$port = 3010
            ,$sdpLocation = "/tmp/www/sdp1.sdp") {
        
        $this->inputPipe=$streamingResource;
        $this->multicastAddress = $address;
        $this->multicastPort = $port;
        $this->sdpLocation = $sdpLocation;
        
        $this->stream();
    }
    
//    public function __destruct() {
//        $this->kill();
//    }
    
    protected function stream() {
        $cmd = 'vlc -vvv input_stream --sout\'#rtp{dst='
                .$this->multicastAddress
                .',port='.strval($this->multicastPort)
                .',sdp='.$this->sdpLocation + '}\'';

        //`ffmpeg $resource - | vlc -vvv input_stream --sout '#rtp{dst=239.0.0.1,port=50004,sdp=file://~/htdocs/streaminfo.sdp}'`;

        //TODO: test this method of piping transcoded data
        $this->process = proc_open($cmd, array(
            '0' => $this->inputPipe,
            '1' => array('pipe', 'w'),
            '2' => array('file', '/tmp/rtpstream_error', 'a')
                ), $this->pipes);
    }
    
    protected function closePipes() {
        fclose($this->pipes[1]);
    }

    public function close() {
        $this->closePipes();
        proc_terminate($this->process);
    }
    
    protected function kill() {
        $this->closePipes();
        proc_terminate($this->process, 9);
    }
}
