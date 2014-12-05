<?php
require_once 'RemoteControl.php';
require_once 'SlideshowStream.php';
require_once 'VideoTranscoder.php';
require_once 'RTPStream.php';
require_once 'AudioStream.php';
require_once '../php/phpLib/config.php';

/**
 * Description of SceneConductor
 *
 * @author Noah
 */
define ("MEM_KEY",3010);
define ("PRC_VAR_KEY", 3011);
define ("SDP_URL","http://10.0.0.2/Environment-Simulated-Study-Room/data/sdps/"); //CONFIGURE ME !!
define ("SDP_LOC", ABSOLUTEPATH); //CONFIGURE ME !!
    

class SceneConductor {
    
    protected $processObjects; // array of process objects
    protected $remotes; // array of remote controls established
    protected $countRtpStreams; // number of RTP streams opened.

    protected $shm; // shared memory identifier
    protected $processes; // array of process ids
    
    protected $sceneDisc; // description of scene to be played.

    /**
     * 
     * @param type $sceneDisc an array which describes the locations to stream
     *      to and which resources to stream.
     */
    public function __construct($sceneDisc) {
        $this->sceneDisc = $sceneDisc;
        $this->processObjects = array();
        $this->remotes = array();
        //connect to persistent mem, and retrieve the process list
        $this->shm = shm_attach(MEM_KEY);
        $this->processes = shm_get_var($this->shm, PRC_VAR_KEY);
    }
    
    public function __destruct() {
        //detatch from persistent memory
        shm_detach($this->shm);
    }
    
    public function play() {
        
        //kill previous processes
        $this->killProcesses();
        
        if(key_exists('audio', $this->sceneDisc)) {
            $this->audio($this->sceneDisc['audio']);
        }
        
        if(key_exists('video', $this->sceneDisc)) {
            foreach ($this->sceneDisc['video'] as $component) {
                error_log($component);
                switch ($component[2]){ //switch based on type of stream
                    case 'slideshow':
                        $this->slideshow($component);
                    case 'video':
                        $this->video($component);
                }
            }
        }
    }
    
    /**
     * this method scans through the 
     * @return boolean
     */
    protected function killProcesses() {
        if(count($this->processes) > 0) {
            foreach ($this->processes as $pid) {
                // send SIGKILL to processes
                posix_kill($pid, 9);
            }
            $this->processes = array();
        }
        return TRUE;
    }

    protected function slideshow($component) {
        $vidstream = new SlideshowStream($component[3]);
	if(!is_resource($vidstream)) {
            error_log("did not get pipe from ffmpeg");
        }
        array_push($this->processes, $vidstream->getPid());
        array_push($this->processObjects, $vidstream);
        
        $sdpfilename = sprintf("sdp%u.sdp", $this->countRtpStreams);
        
        $rtpstream = new RTPStream(
                $vidstream->getOutputPipe(),
                '239.0.0.1',
                50004+2*$this->countRtpStreams,
                SDPLOCATION.$sdpfilename);
        
        array_push($this->processes, $rtpstream->getPid());
        shm_put_var($this->shm, PRC_VAR_KEY, $this->processes); //update shared mem
        
        array_push($this->processObjects, $rtpstream);
        $this->countRtpStreams += 1;
        
        $url = SDP_URL.$sdpfilename;
        
        $remote = new RemoteControl($component[0], $component[1]);
        $remote->queue($url);
        $remote->play();
        array_push($this->remotes, $remote);
    }

    protected function video($component) {
        //$vidstream = new VideoTranscoder($component[3]);
        //array_push($this->processes, $vidstream->getPid());
        //array_push($this->processObjects, $vidstream);
        
        $sdpfilename = sprintf("sdp%u.sdp", $this->countRtpStreams);
        
        $rtpstream = new RTPStream(
                //$vidstream->getOutputPipe(),
                $component[3],
                '239.0.0.1',
                50004+2*$this->countRtpStreams,
                SDPLOCATION.$sdpfilename);
        
        array_push($this->processes, $rtpstream->getPid());
        shm_put_var($this->shm, PRC_VAR_KEY, $this->processes); //update shared mem
        
        array_push($this->processObjects, $rtpstream);
        $this->countRtpStreams += 1;
        
        $url = SDP_URL.$sdpfilename;
        
        $remote = new RemoteControl($component[0], $component[1]);
        $remote->queue($url);
        $remote->play();
        array_push($this->remotes, $remote);
    }
    
    protected function audio($component) {
        
        $sdpfilename = sprintf("sdp%u.sdp", $this->countRtpStreams);
        
        $rtpstream = new RTPStream(
                $component[2],
                '239.0.0.1',
                50004+2*$this->countRtpStreams,
                SDPLOCATION.$sdpfilename
        );
        
        array_push($this->processes, $rtpstream->getPid());
        shm_put_var($this->shm, PRC_VAR_KEY, $this->processes); //update shared mem
        
        array_push($this->processObjects, $rtpstream);
        $this->countRtpStreams += 1;
        
        $url = SDP_URL.$sdpfilename;
        
        $remote = new RemoteControl($component[0], $component[1]);
        $remote->queue($url);
        $remote->play();
        array_push($this->remotes, $remote);
    }

    public function stop() {
        if(count($this->remotes > 0)) {
            foreach ($this->remotes as $remote) {
                $remote->stop();
            }
        }
        if(count($this->processObjects) > 0){
            foreach($this->processObjects as $proc) {
                //gracefully end streams
                $proc->close();
            }
        }
        else {
            // if the process objects are not available then kill the process list
            $this->killProcesses();
        }
    }
}
