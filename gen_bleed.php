<?php

define('CARD_WIDTH', 825);
define('CARD_HEIGHT', 1125);
define('CARD_BACKGROUND_COLOUR', 'none');
define('OUTPUT', 'ptau_safe.png');

$back = new Imagick();
$back->newImage(CARD_WIDTH, CARD_HEIGHT, CARD_BACKGROUND_COLOUR);

$draw = new ImagickDraw();
$draw->setStrokeColor('#FF0000');
$draw->setStrokeAlpha(1);
$draw->setFillAlpha(0);
$draw->rectangle(18, 18, CARD_WIDTH - 18, CARD_HEIGHT - 18);
$back->drawImage($draw);

$back->writeImage(OUTPUT);
