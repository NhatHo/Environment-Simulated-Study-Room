<?php

/**
 * Description of RTPStream
 *
 * @author Noah
 */
class RTPStream{

    protected $inputPipe;
    
    protected $multicastAddress;
    protected $multicastPort;
    protected $sdpLocation;
    
    protected $process;
    protected $pipes;
    protected $file;

    public function __construct(
            $streamingResource,
            $address = '239.0.0.1',
            $port = 5004,
            $sdpLocation = "/tmp/www/sdp1.sdp") {
        
        $this->inputPipe=trim($streamingResource);
        $this->multicastAddress = $address;
        $this->multicastPort = $port;
        $this->sdpLocation = $sdpLocation;
        $this->stream();
    }
    
//    public function __destruct() {
//        $this->kill();
//    }
    
    protected function stream() {
	$cmd = "vlc -vvv '".$this->inputPipe."' --sout '#rtp{dst=".$this->multicastAddress.",port=".strval($this->multicastPort).",sdp=file:".$this->sdpLocation."}'";
	error_log ("Command: ".$cmd);
	error_log ("Expecte: vlc -vvv - --sout '#rtp{dst=239.0.0.1,port=50004,sdp=file://~/htdocs/streaminfo.sdp}'");
        //`ffmpeg $resource - | vlc -vvv input_stream --sout '#rtp{dst=239.0.0.1,port=50004,sdp=file://~/htdocs/streaminfo.sdp}'`;
	error_log ("The location is: ".$this->sdpLocation);
	error_log ("Multicast address: " . $this->multicastAddress . " Port: ".strval($this->multicastPort));
        //TODO: test this method of piping transcoded data
        $this->process = proc_open($cmd, array(
            //'0' => $this->inputPipe,
            '0' => array('pipe', 'r'),
            '1' => array('pipe', 'w'),
            '2' => array('file', '/tmp/rtpstream_error', 'a')
                ), $this->pipes);
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


    protected function closePipes() {
        fclose($this->pipes[0]);
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
