Environment-Simulated-Study-Room
================================

Dr. Alan Steele came to our class on behalf of Carleton University's Library
proposing a term project to create a projector system for a room in the
Library's Dicovery Centre. This room will feature three projectors mounted on
the ceiling pointed to three different walls and has speakers that support
7.1 digital surround sound. The idea for the system was to play synchronized
video/pictures along the three walls and audio, in order to simulate an outdoor
study environment.

This project is a joint effort between the Systems Engineering department and
the Philosophy department at Carleton University. We design and implement the
system, and the Philosophy students will find the contents that the system
will offer to the library.

The Engineering team consisted of:

- Haifa Aljasser
- Nhat Ho
- Itaf Joudeh
- Noah Kipin
- Brandon To

![The Room](http://i.imgur.com/t8h0bvQ.png)


Design
======

The design of the system consists of three parts:

- The Scene Manager Subsystem
- The Content Distribution Subsystem
- The IR Control Subsystem

The Scene Manager Subsystem should provide the user with a GUI to interact with
the system. This system will give the user the ability to play/pause/stop/skip
and adjust the volume of the scenes. This subsystem will provide an interface
for the administrator to do all that a user can, along with upload/edit/delete
scenes, and configure system settings. We have decided that a web interface
would be best for this task, as it could be easily deployed and also accessed
on the library's tablet. This will also save work if the system ever changes
platforms. This system will be the only part that the end user will see.

The Content Distribution Subsystem should provide the other systems the ability
to play video on the projectors, and audio on the speakers. This system should
handle the synchronozation of the video output of the three projectors, along
with the synchronization of the audio with the video. The content distribution
system will control three Raspberry Pi's connected to the three projectors, and
one Raspberry Pi connected to the speaker. The Raspberry Pi's will stream video
directly from the centralized server.

The IR Control Subsystem should provide the user with the ability to control
the scenes through a physical infrared remote control. This subsystem should
accept infrared signals from the remote and map it to a subset of the features
that the Scene Manager Subsystem's web interface provides. This include
allowing the user to play/pause/stop/skip and adjust the volume of scenes.

The following image is the class diagram of the initial design of the system:

![Class Diagram](http://i.imgur.com/zjqDCEB.png)

The following image is the use case diagram of system:

![Use Case Diagram](http://i.imgur.com/eP44e3m.png)


Implementation
==============




