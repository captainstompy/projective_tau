<?php

define('CARD_WIDTH', 825);
define('CARD_HEIGHT', 1125);
define('CARD_BACKGROUND_COLOUR', 'none');
define('OUTPUT_SAFE', 'ptau_safe.png');
define('OUTPUT_BLEED', 'ptau_bleed.png');

$back = new Imagick();
$back->newImage(CARD_WIDTH, CARD_HEIGHT, CARD_BACKGROUND_COLOUR);

$draw = new ImagickDraw();
$draw->setStrokeColor('#000000');
$draw->setStrokeAlpha(1);
$draw->setFillAlpha(0);
$draw->rectangle(36, 36, CARD_WIDTH - 36, CARD_HEIGHT - 36);
$back->drawImage($draw);

$back->writeImage(OUTPUT_SAFE);

$back_bleed = new Imagick();
$back_bleed->newImage(CARD_WIDTH, CARD_HEIGHT, CARD_BACKGROUND_COLOUR);

$draw_bleed = new ImagickDraw();
$draw_bleed->setStrokeColor('#000000');
$draw_bleed->setStrokeAlpha(1);
$draw_bleed->setFillAlpha(0);
$draw_bleed->rectangle(18, 18, CARD_WIDTH - 18, CARD_HEIGHT - 18);
$back_bleed->drawImage($draw_bleed);

$back_bleed->writeImage(OUTPUT_BLEED);
