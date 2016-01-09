<?php

define('CARD_WIDTH', 825);
define('CARD_HEIGHT', 1125);
define('CARD_BACKGROUND_COLOUR', '#E2E2E2');
define('OUTPUT', 'ptau_back_drivethru.png');
define('LINE_WIDTH', 100);

$colours = [
    'rgb(239,91,136)',
    'rgb(126,206,210)',
    'rgb(195,153,107)'
];

$back = new Imagick();
$back->newImage(CARD_WIDTH, CARD_HEIGHT, CARD_BACKGROUND_COLOUR);
#$back->setImageColorspace(imagick::COLORSPACE_CMYK);

function drawRect($back, $c, $x, $y, $w, $h) {
  $iterator = $back->getPixelIterator();
  foreach ($iterator as $row=>$pixels) {
    foreach ( $pixels as $col=>$pixel ) {
      if ($col >= $x && $col < $w && $row >= $y && $row < $h) {
      $pixel->setColor($c);
      }
    }
    $iterator->syncIterator();
  }
}

function drawVLine($back, $x, $c) {
  drawRect($back, $c, $x, 0, $x + LINE_WIDTH, CARD_HEIGHT);
}

function drawHLine($back, $y, $c) {
  drawRect($back, $c, 0, $y, CARD_WIDTH, $y + LINE_WIDTH);
}

drawHLine($back, 250, $colours[1]);

drawVLine($back, 100, $colours[0]);
drawVLine($back, 250, $colours[1]);
drawVLine($back, 400, $colours[2]);

drawHLine($back, 100, $colours[0]);
drawHLine($back, 400, $colours[2]);

$back->writeImage(OUTPUT);
