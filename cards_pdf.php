<?php

mkdir("cards_cmyk");

function rewrite($old, $new) {
  $back_img = new Imagick($old);
  $new_back_img = new Imagick();
  $new_back_img->newImage(825, 1125, '#FFFFFF');
  $new_back_img->compositeImage($back_img, Imagick::COMPOSITE_DEFAULT, 0, 0);
  $new_back_img->transformImageColorspace(Imagick::COLORSPACE_CMYK);
  $new_back_img->writeImage($new);
}

rewrite("ptau_back_drivethru.png", "ptau_back_drivethru_rewritten.png");
rewrite("ptau_cover_drivethru.png", "ptau_cover_drivethru_rewritten.png");
rewrite("rules1_with_text.png", "rules1_with_text_rewritten.png");
rewrite("rules2_with_text.png", "rules2_with_text_rewritten.png");
rewrite("rules3_with_text.png", "rules3_with_text_rewritten.png");

foreach(glob("cards_drivethru/*.png") as $filename) {
	$img = new Imagick($filename);
	$img->transformImageColorspace(Imagick::COLORSPACE_CMYK);
	preg_match('/\/[\w]*.png/',$filename,$newname);
	$img->writeImage("cards_cmyk".$newname[0]);
}

$card_images = array();
foreach(glob("cards_cmyk/*.png") as $filename) {
	$card_images[] = $filename;
}
// TODO: reorder the card images.

// Add the back image interleaved with the front images.
$images = array();
$images[] = "ptau_cover_drivethru_rewritten.png";
$images[] = "rules1_with_text_rewritten.png";
$images[] = "rules2_with_text_rewritten.png";
$images[] = "rules3_with_text_rewritten.png";
foreach(array_reverse($card_images) as $card_image) {
	$images[] = "ptau_back_drivethru_rewritten.png";
	$images[] = $card_image;
}

$img = new Imagick();
$img->setResolution(300, 300);
$img->readImages($images);
$img->setImageUnits(Imagick::RESOLUTION_PIXELSPERINCH);
$img->setImageFormat("pdf");
$img->writeImages('card_test.pdf', true);
