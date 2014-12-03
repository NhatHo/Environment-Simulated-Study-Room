<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            echo "Creating Description\n";
            $audioURL = 'https://archive.org/download/forbidden_dragon_herbert_west_reanimator_01_lovecraft/forbidden_dragon_herbert_west_reanimator_01_lovecraft.mp3';
            $videoURL = 'https://archive.org/download/electricsheep-flock-244-37500-9/00244%3D37509%3D33572%3D32771.mp4';
            //$sliedeshowFilePath = '';
            $scneDisc = array([
                'audio' => array(["10.0.0.21",3010,$audioURL]),
                'video' => array([
                    array(["10.0.0.23",3010,'video',$video1URL]),
                    //array(["10.0.0.22",3010,'slideshow',$slideshowFilePath])
                ])
            ]);
            
            echo "Instantiating The Conductor \n";
            $conductor = new SceneConductor($scneDisc); 
            sleep(1);
            echo "Playing media\n";
            $conductor->play();
            echo "Sleep 60";
            sleep(60);
            echo "Stopping";
            $conductor->stop();
        ?>
    </body>
</html>
