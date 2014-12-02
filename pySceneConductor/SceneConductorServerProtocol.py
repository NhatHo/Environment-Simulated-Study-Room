__author__ = 'Noah Kipin'

import asyncio, json

class SceneConductorServerProtocol(asyncio.Protocol):

    def data_received(self, data):
        new_scene = json.load(data)
        pass
