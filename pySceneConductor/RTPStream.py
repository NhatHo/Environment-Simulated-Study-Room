__author__ = 'Noah'

import subprocess, os

class RTPStream:

    __process = None

    def RTPStream(self, streamingResource, address, port, sdpLocation):
        if(os.path.isfile(sdpLocation)):
            cmd = ['vlc', '-vvv', 'input_stream',
               '--sout\\\'#rtp{dst='+address
                +',port='+str(port)
                +',sdp='+sdpLocation+'}\'']
        else:
            raise Exception("could not reach file to stream")

        self.__process = subprocess.Popen(cmd, bufsize=0, stdin=streamingResource)

    def close(self):
        self.__process.terminate()

    def kill(self):
        self.__process.kill()