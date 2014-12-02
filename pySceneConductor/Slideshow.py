__author__ = 'Noah'
import MediaStream
import math, pipes, subprocess

class Slideshow(MediaStream):

    __process = None

    def Slideshow(self, path, displayDuration = 5):

        if(displayDuration > 3600 | displayDuration < 0.2):
            displayDuration = 5

        if(displayDuration == 1):
            framerate = "1"
        elif(displayDuration < 1):
            framerate = str(math.floor(float(1)/displayDuration))
        elif(displayDuration > 1):
            framerate = str(math.floor(displayDuration))

        self.__setupSlideshow(path, framerate)

    def __setupSlideshow(self, path, framerate):

        cmd = ['ffmpeg', '-framerate', framerate, '-i', path, '-c:v',
               'lib264', '-r', '30', '-pix_fmt yuv420p', '-']

        self.__process = subprocess.Popen(cmd, bufsize=0, stdout=subprocess.PIPE)

    def getOutputPipe(self):
        if(isinstance(self.__process, subprocess.Popen)):
            return self.__process.stdout
        else:
            return None

    def __closePipes(self):
        pass

    def close(self):
        return self.__process.terminate()

    def kill(self):
        return self.__process.kill()