__author__ = 'Noah'

class AbstractMediaStream():

    def getOutputPipe(self):
        raise NotImplementedError("Implementation Required.")

    def close(self):
        raise NotImplementedError("Implementation Required.")