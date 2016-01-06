<?php

define('CARD_WIDTH', 825);
define('CARD_HEIGHT', 1125);
//define('CARD_BACKGROUND_COLOUR', '#FFEBCD');
define('CARD_BACKGROUND_COLOUR', '#EDEDED');
//define('CARD_BACKGROUND_COLOUR', '#938F86');
define('OUTPUT', 'bqtau_back.png');

mt_srand(7);

$colours = [
    '#DF004F',
    '#FFBA00',
    '#1826B0',
    '#74E600'
];

$back = new Imagick();
$back->newImage(CARD_WIDTH, CARD_HEIGHT, CARD_BACKGROUND_COLOUR);

$circles = array();

function drawC($back, $x, $y, $r, $c, $o) {
  $draw = new ImagickDraw();
  $draw->setStrokeColor($c);
  $draw->setFillColor($c);
  $draw->setStrokeAlpha(0);
  $draw->setFillAlpha($o);
  $draw->circle($x, $y, $x + $r, $y);
  $back->drawImage($draw);
}

$j = 0;
define('MIN_DIST', 100);
for ($i = 0; $i < 1000; $i++) {
  $j++;
  if ($j > 100000) {
    break;
  }
  $c = $colours[$i % 4];
  $opacity = mt_rand(3000, 8000) / 10000;
  //$opacity = 0.9;
  $x = mt_rand(0, CARD_WIDTH);
  $y = mt_rand(0, CARD_HEIGHT);
  $r = mt_rand(80, 180);
  foreach ($circles as $circle) {
    $dist = sqrt(pow($x - $circle['x'], 2) + pow($y - $circle['y'], 2));
    if (($circle['c'] == $c && $dist < ($r + $circle['r'])) ||
        $dist < MIN_DIST) {
      echo "Skipping!\n";
      $i--;
      continue 2;
    }
  }
  echo "Not skipped!\n";
  $circles[] = array('x' => $x, 'y' => $y, 'r' => $r, 'c' => $c);

  drawC($back, $x, $y, $r, $c, $opacity);
}

$back->writeImage(OUTPUT);
