<?php

function sortsort($a, $b) {
	$order = array(
		'none'=>0,
		'1'=>1,
		'2'=>2,
		'3'=>3
	);

	$split_a = explode("_", end(explode("/",$a)));
	$split_a[5] = str_replace(".png", "", $split_a[5]);
	$split_b = explode("_", end(explode("/",$b)));
	$split_b[5] = str_replace(".png", "", $split_b[5]);

	// $split_a and $split_b should look like this: array('line',num,'circle',num,'triangle',num)
	if ($order[$split_a[5]] < $order[$split_b[5]]) {
		return -1;
	} else if ($order[$split_b[5]] > $order[$split_a[5]]) {
		return 1;
	}

	if ($order[$split_a[3]] < $order[$split_b[3]]) {
		return -1;
	} else if ($order[$split_b[3]] > $order[$split_a[3]]) {
		return 1;
	}

	if ($order[$split_a[1]] < $order[$split_b[1]]) {
		return -1;
	} else {
		return 1;
	}
}

$image_small = new Imagick();
$image_small->newImage(5040, 120, 'none');

$image_big = new Imagick();
$image_big->newImage(10080, 240, 'none');

$cards = array();
foreach(glob("cards/*.png") as $filename) {
	$cards[] = $filename;
}
// TODO - seems inverting will be just fine... usort($cards, "sortsort");

$cards = array_reverse($cards);

foreach($cards as $key => $card) {
	$card_image = new Imagick($card);

	$card_image->scaleImage(160,240);
// TODO - scaling won't be the same ratio, probably
	$image_big->compositeImage($card_image, Imagick::COMPOSITE_DEFAULT, $key*160, 0);

	$card_image->scaleImage(80,120);
	$image_small->compositeImage($card_image, Imagick::COMPOSITE_DEFAULT, $key*80, 0);
}

$image_big->writeImage("taugame_site_big.png");
$image_small->writeImage("taugame_site_small.png");
// TODO - jpgs
$image_big->setImageFormat('jpg');
$image_big->writeImage("taugame_site_big.jpg");
$image_small->setImageFormat('jpg');
$image_small->writeImage("taugame_site_small.jpg");
