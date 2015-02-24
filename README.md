Environment-Simulated-Study-Room
================================
(last updated: February 23, 2015)

Dr. Alan Steele came to our team on behalf of Carleton University's Library
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

The following image describes the orientation of the projectors in the room:

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
handle the synchronization of the video output of the three projectors, along
with the synchronization of the audio with the video. The content distribution
system will control three Raspberry Pi's connected to the three projectors, and
one Raspberry Pi connected to the speaker. The Raspberry Pi's will stream video
directly from the centralized server. Communication with the Raspberry Pi's
will be through RTP.

The IR Control Subsystem should provide the user with the ability to control
the scenes through a physical infrared remote control. This subsystem should
accept infrared signals from the remote and map it to a subset of the features
that the Scene Manager Subsystem's web interface provides. This include
allowing the user to play/pause/stop/skip and adjust the volume of scenes. The
IR control system will make use of a Raspberry Pi's GPIO pins by connecting an
IR sensor to it. The Subsystem will make use of the Raspberry Pi to listen to
the IR signals at the frequency of the remote control and send the data to the
web service provided by the Scene Manager Subsystem. Communication with the web
server will be through HTTP.

Since the IR System is not a part of the website, it was created in it's own
repository and can be found here:
https://github.com/brandonto/Environment-Simulated-Study-Room-IR-System

The following image is the class diagram of the initial design of the system:

![Class Diagram](http://i.imgur.com/zjqDCEB.png)

The following image is the use case diagram of system:

![Use Case Diagram](http://i.imgur.com/eP44e3m.png)


Implementation
==============

For the Scene Manager Subsystem, we decided to use the following technologies:

- HTML5, CSS (Bootstrap framework), and Javascript (JQuery framework) for the
front end of the website
- PHP for the backend of the website, due to simplicity and fast deployment time
- MySQL for the database, since it plays nicely with PHP
- Plupload library was also used

For the Content Distribution Subsystem, we decided on:

- VLC media player for the video playback
- PHP for interfacing with VLC and providing easy access for the Scene Manager
Subsystem

For the IR Control Subsystem, we decided on:

- LIRC for the infrared library
- C for the easy access to the hardware and execution speed


Integration
===========

Due to our team working closely, integration was not much of an issue. Both the
Scene Manager Subsystem and the Content Distribution Subsystem were created
using PHP as a backend. This means that integration was simply a matter of
calling into the API decided prior to development. The IR Control System was also
easily integrated since we decided to communicate through HTTP. This means that
PHP handles most of the server side processing of the packets for us and gives us
easy access to the data received through PHP's POST method. This leaves us with
only the client side construction of the HTTP request to implement and send over
the network. Certain areas had to be patched up for everything to run smoothly,
but the combined effort took less than a day.


Testing
=======

Testing of the Scene Manager Subsystem was automated through the use of the
Selenium web driver and Java's unit testing framework. Manual testing was also
done. Through implementing the system, testing was done extensively as well. We
have all of our tests cases documented in our project report.

Testing of the Content Distribution System was mostly done manually.

Testing of the IR Control System could not be automated due to somebody having
to physically press the IR remote in order for us to receive the signal. Due to
the requirements of extended execution time and the time constraints that we had,
this aspect could not be tested fully as well.


Difficulties
============

Some difficulties that arose during the implementation are documented here:



Conclusion
==========

Overall, we think this project was a success. Given the timespan (3 months), we
did not meet all of the requirements fully. We only provided support for video,
but we believe adding images will be trivial. We did not have time to create a
dedicated Android/IOS application to interface with our website. However, on
mobile, our websites were very responsive due to JQuery mobile and Bootstrap.
We also severely overestimated the capabilities of the Raspberry Pi's. Since
we did not get hardware acceleration working on the Raspberry Pi's, the video
could not run in full screen, otherwise the processor would crash. The solution
would be to migrate to more powerful computers for streaming the video.

This project is currently on hold due to our other classes. We may decide to
come back to finish up the implementation during the summer. We would say that
this project is currently sitting between 80-90% complete.

