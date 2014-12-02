<?php

/**
 * Description of RemoteControl
 * 
 * This class will control VLC via the remote control interface.
 * The VLC client will need to be configured to accept connections on a specific
 * port, and this configuration will need to be provided to the remoteControl 
 * object when it is created. The remoteControl class will establish a 
 * persistent connection with the host, and can command it to start, stop, and 
 * pause playback as well as increase, decrase and mute the audio.
 * 
 * To run VLC with the RC interface use the --rc-host "yourhost:port"
 * ex: $> vlc -I rc --rc-host localhost:3010
 *
 * @author Noah Kipin
 */
class RemoteControl {

    protected $connection;
    
    /**
     * Constructor for remoteControl. The remote host and port for the VLC
     * RC interface must be specified.
     * 
     * @param string $host This is the address of the VLC RC host
     * @param int $port This is the port of the VLC RC host
     */
    public function __construct($host, $port) {
        $this->connection = $this->connect($host, $port);
    }
    
    /**
     * cleanup connection.
     */
    public function __destruct() {
        fclose($this->connection);
    }
    
    /**
     * Connects to the VLC RC interface.
     * @return resource
     * @throws Exception if it is unable to connect to the
     */
    protected function connect($host, $port){
        $errno = 0;
        $errstr = "";
        $c = fsockopen($host,  $port, 
                $errno, $errstr, 10);
        if (!$c){
            throw new Exception($errstr, $errno);
        } else {
            stream_set_blocking($c, 0); //set non-blocking
        }
        return $c;
    }
    
    /**
     * Sends the given command to VLC
     * @param string $cmd
     */
    protected function sendCommand($cmd) {
        fwrite($this->connection, $cmd."\n");
        fflush($this->connection);
    }
    
    /**
     * queue's some media to be played.
     * @param path $resource the path to the resource/media to play
     */
    public function queue($resource) {
        $this->sendCommand("clear");
        $this->sendCommand("loop on");
        $this->sendCommand("enqueue ".$resource);
    }
    
    /**
     * Play playlist
     */
    public function play() {
        $this->sendCommand("play");
    }
    
    /**
     * Stop playlist
     */
    public function stop(){
        $this->sendCommand("stop");
    }
    
    /**
     * Pause playback
     */
    public function pause(){
        $this->sendCommand("pause");
    }
    
    /**
     * Set volume to 0
     */
    public function mute(){
        $this->sendCommand("volume 0");
    }
    
    /**
     * Additively increase volume
     */
    public function increaseVol(){
        $this->sendCommand("volup");
    }
    
    /**
     * Subtract volume
     */
    public function decreaseVol() {
        $this->sendCommand("voldown");
        
    }
    
    /**
     * Set volume between 0 and 256 based on percentage.
     * @param int $percent The percent volume to set
     */
    public function setVol($percent) {
        $vol = 256 * $percent;
        $this->sendCommand("volume $vol");
    }
    
    /**
     * Fullscreen the application
     */
    public function fullscreenOn() {
        $this->sendCommand("F on");
    }
    
    /**
     * Remove fullscreen
     */
    public function fullscreenOff() {
        $this->sendCommand("F off");
    }
    
    /**
     * Requests the status of the remote VLC client.
     * WARNING: this is a blocking function
     * @return string The status text from the server
     */
    public function status() {
        //set blocking
        stream_set_blocking($this->connection, 1);
        //Seek buffer to EOF
        fseek($this->connection, 0, SEEK_END);
        //request the status
        $this->sendCommand("status");
        //get the status
        $status = fgets($this->connection);
        //set non-blocking
        stream_set_blocking($this->connection, 0);
        //return the reult
        return $status;
    }
}
