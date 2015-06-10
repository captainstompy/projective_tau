<?php

define('CARD_WIDTH', 825);
define('CARD_HEIGHT', 1125);
define('CARD_BACKGROUND_COLOUR', '#EDEDED');
define('OUTPUT', 'ptau_back.png');
define('LINE_WIDTH', 100);

$colours = [
    'rgb(239,91,136)',
    'rgb(126,206,210)',
    'rgb(195,153,107)'
];

$back = new Imagick();
$back->newImage(CARD_WIDTH, CARD_HEIGHT, CARD_BACKGROUND_COLOUR);

function drawVLine($back, $x, $c) {
  $draw = new ImagickDraw();
  $draw->setStrokeColor($c);
  $draw->setFillColor($c);
  $draw->setStrokeAlpha(1);
  $draw->setFillAlpha(1);
  $draw->rectangle($x, 0, $x + LINE_WIDTH, CARD_HEIGHT);
  $back->drawImage($draw);
}

function drawHLine($back, $y, $c) {
  $draw = new ImagickDraw();
  $draw->setStrokeColor($c);
  $draw->setFillColor($c);
  $draw->setStrokeAlpha(1);
  $draw->setFillAlpha(1);
  $draw->rectangle(0, $y, CARD_WIDTH, $y + LINE_WIDTH);
  $back->drawImage($draw);
}

drawHLine($back, 250, $colours[1]);

drawVLine($back, 100, $colours[0]);
drawVLine($back, 250, $colours[1]);
drawVLine($back, 400, $colours[2]);

drawHLine($back, 100, $colours[0]);
drawHLine($back, 400, $colours[2]);

$back->writeImage(OUTPUT);
