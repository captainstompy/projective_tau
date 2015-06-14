<?php

define('CARD_WIDTH', 825);
define('CARD_HEIGHT', 1125);
define('CARD_BACKGROUND_COLOUR', '#FFFFFF');
define('OUTPUT_BASE', 'rules');
define('LINE_WIDTH', 100);
define('DEBUG', false);

$colours = [
    'rgb(239,91,136)',
    'rgb(126,206,210)',
    'rgb(195,153,107)'
];

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

function drawNumber($back, $i) {
  $number = new Imagick();
  $number->newImage(CARD_WIDTH, CARD_HEIGHT, 'none');
  $draw = new ImagickDraw();
  $draw->setFont("Kefa-Bold");
  $draw->setFontSize(100);
  $number->annotateImage($draw, 300, 300, 0, $i);
  $number->trimImage(0);
  $x = 100 + LINE_WIDTH / 2 - $number->getImageWidth() / 2;
  $safe_buffer = 36;
  $edge_buffer = 20;
  $y = CARD_HEIGHT - $number->getImageHeight() - $safe_buffer - $edge_buffer;
  $back->compositeImage($number, Imagick::COMPOSITE_DEFAULT, $x, $y);
}

function drawCard($i, $j, $t, $colours) {
  $back = new Imagick();
  $back->newImage(CARD_WIDTH, CARD_HEIGHT, CARD_BACKGROUND_COLOUR);  
  drawHLine($back, 100, $colours[$j - 1]);
  drawVLine($back, 100, $colours[$i - 1]);
  drawNumber($back, $t);

  if (DEBUG) {
    $draw = new ImagickDraw();
    $draw->setStrokeColor('#000000');
    $draw->setStrokeAlpha(1);
    $draw->setFillAlpha(0);
    $draw->rectangle(36, 36, CARD_WIDTH - 36, CARD_HEIGHT - 36);
    $back->drawImage($draw);
  }

  $back->writeImage(OUTPUT_BASE . $t . ".png");
}

drawCard(1, 2, '1', $colours);
drawCard(2, 3, '2', $colours);
drawCard(3, 1, '3', $colours);
drawCard(2, 1, '4', $colours);
drawCard(1, 3, '5', $colours);
drawCard(3, 2, '6', $colours);
