<?php

//telnet library: https://github.com/ngharo/Random-PHP-Classes/blob/master/Telnet.class.php

/**TODO: Separate this class's functions into separate transcoding and streaming
 * portions. This way the vlm management can potentially be done via telnet,
 * and this class could handle an array with the stream resources defined ex:
 *  array(
 *      array('slideshow', 'path/to/files/*', 'proj1'),
 *      array('audio', 'path/to/audio.mp3'),
 *      array('video', 'path/to/video.mkv', 'proj2'),
 *      array('video', 'path/to/video2.avi', 'proj3')
 *  );
 * 
 * this would be accepted as an argument when creating the manager, or through a
 * public function like "beginScene(resources)"
 */


/**
 * VLMstream
 * This class manages the creation and termination of a RTP multicast stream
 * from a specified resource.
 * 
 * @author Noah Kipin
 */
class sceneStreamer {

    protected $multicastAddress = "";
    protected $multicastPort = int;
    protected $sdpLocation = "";
    protected $transcoderResource = NULL;
    protected $streamResource = NULL;
    protected $activeResource = NULL;
    protected $pipes;

    /**
     * VLMstream(address, port, sdpPath)
     * This constructor sets the multicast address to stream to, and port for the
     * RTP multicast stream, and the location to store the sdp file for this 
     * stream.
     * 
     * address - the multicast address to stream to. Default is 239.0.0.1 (RFC 2365)
     * port - the rtp port to stream from. Default is 50004. Port pairs are required 
     *     for RTP. For each pair the even port carries data, and the odd port 
     *     carries RTCP. Using port 50004 means that the next port assigned for a 
     *     stream should be 50006 or above.
     * sdpPath - path to a location to store the stream connection information. This 
     *     sdp file is provided to the client system to allow it to start streaming 
     *     from the created RTP stream.
     */
    public function __construct($address = "239.0.0.1", $port = 50004, $sdpPath = "file://~/htdocs/streaminfo.sdp") {
        $this->multicastAddress = $address;
        $this->multicastPort = $port;
        $this->sdpLocation = $sdpPath;
    }

    /**
     * rtpStream(videosource)
     * Create a multicast stream from the specified resource. The information
     * required to play the stream is stored in an sdp file.
     */
    protected function rtpStream($resource) {
        if (is_resource($this->streamResource)) {
            throw new Exception('Stream already active, cannot create new stream.');
        }

        $tmp_cmd = 'vlc -vvv input_stream --sout\'#rtp{dst=';
        $tmp_cmd += $this->multicastAddress;
        $tmp_cmd += ',port=' + strval($this->multicastPort);
        $tmp_cmd += ',sdp=' + $this->sdpLocation + '}\'';

        //`ffmpeg $resource - | vlc -vvv input_stream --sout '#rtp{dst=239.0.0.1,port=50004,sdp=file://~/htdocs/streaminfo.sdp}'`;

        //TODO: test this method of piping transcoded data
        $this->streamResource = proc_open($tmp_cmd, array(
            '0' => transcodeVideo($resource),
            '1' => array('pipe', 'w'),
            '2' => array('file', '/tmp/stream_error', 'a')
                ), $this->pipes);
    }

    /**
     * createStreamingTranscoder(resourceToPlay)
     * This function transcodes the video given if necessary using ffmpeg. It
     * will create a H.264 stream to a pipe and returns
     * @param Path $resource path to resource to transcode
     * @return FileDescriptor Pipe which contains the streaming video data
     */
    protected function transcodeVideo($resource) {
        if (is_resource($this->transcoderResource)) {
            throw new Exception('Transcoder already running.');
        }

        $pipes = NULL;

        $tmp_cmd = 'ffmpeg -i ' + $resource + ' -';

        $this->transcoderResource = proc_open($tmp_cmd, array(
            '0' => array('pipe', 'r'),
            '1' => array('pipe', 'w'),
            '2' => array('file', '/tmp/transcode_error', 'a')
                ), $pipes);

        return $pipes[1];
    }

    /**
     * slideshow (resource, numImages, displayTime)
     * This function creates a multicast stream which will display a slideshow of
     * the images specified. The resource string must use following format:
     * for images named: img01.jpg, img02.jpg ---> img%02d.jpg
     *                   mypictures123.png, mypictures234.png --> mypictures%03d.png
     * 
     * resource - the path to the image resources (ex: /home/usr/images/img%02d.jpg)
     * numImages - the number of images (1-999)
     * displayTime is the number of seconds to display each image in seconds (1-60s)
     */
    public function slideshow($resource, $images, $displayTime) {
        if (is_resource($this->transcoderResource)) {
            throw new Exception('Transcoder already active. '
            . 'Cannot create new transcoder');
        }
        //Check if displaytime is in spec
        if ($displayTime > 60 || $displayTime < 1) {
            $displayTime = 5;
        }
        //Check if # of images is in spec
        if ($images < 1 || $images > 999) {
            throw new Exception('Invalid number of images.');
        }

        $tmp_cmd = 'ffmpeg -framerate 1/' + strval($displayTime) + ' -i ' + $resource +
                ' -c:v libx264 -r 30 -pix_fmt yuv420p - | vlc -vvv input_stream --sout\'#rtp{dst=' +
                $this->multicastAddress + ',port=' + strval($this->multicastPort) + ',sdp=' + $this->sdpLocation + '}\'';

        //`ffmpeg -framerate 1/$displayTime -i $resource -c:v libx264 -r 30 -pix_fmt yuv420p - | vlc -vvv input_stream --sout#rtp{dst=239.0.0.1,port=50004,sdp=file://~/htdocs/streaminfo.sdp}'`;   

        $this->activeResource = proc_open($tmp_cmd, array(0), $this->pipes);
    }

    public function audioStream($resource) {
        if ($this->activeResource != NULL) {
            throw new Exception('Stream already active, cannot create new stream.');
        }
        $tmp_cmd = "";
        $this->activeResource = proc_open($tmp_cmd, array(0), $this->pipes);
    }

    public function killStream() {
        if ($this->activeResource == NULL) {
            return;
        }
        if (proc_terminate($this->activeResource)) {
            $this->activeResource = NULL;
        } else {
            throw new Exception('Streaming resource failed to terminate.');
        }
    }

}
