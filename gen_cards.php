<?php

define('CARD_WIDTH', 825);
define('CARD_HEIGHT', 1125);
define('CARD_BACKGROUND_COLOUR', '#FFFFFF');
define('CARD_DEBUG', false);
define('CARD_WRITE_LOCATION', "cards/");
define('LINE_WIDTH', 100);

// TODO - better colours
/*
define('COLOUR_ONE', '#000000');
define('COLOUR_TWO', '#FF0000');
define('COLOUR_THREE', '#00FF00');
*/
define('COLOUR_ONE', 'rgb(239,91,136)');
define('COLOUR_TWO', 'rgb(126,206,210)');
define('COLOUR_THREE', 'rgb(195,153,107)');

$debug = true;

$full_width = 825;
$full_height = 1125;
$bleedetc = 36;

class Card {
	private $card;

	private $lines;
	private $circle;
	private $triangles;
	function Card($lines, $circle, $triangles) {
		$this->card = new Imagick();
		$this->card->newImage(CARD_WIDTH, CARD_HEIGHT, CARD_BACKGROUND_COLOUR);

		$this->lines = $lines;
		$this->circle = $circle;
		$this->triangles = $triangles;
	}

	public function genCard() {
		$draw = new ImagickDraw();
		$draw->setStrokeOpacity(1);

		if ($this->lines) {
			$draw->setFillColor($this->lines->getColour());
			$line_points = $this->lines->getCoordinates();
			$draw->rectangle($line_points['start']['x'], $line_points['start']['y'], $line_points['end']['x'], $line_points['end']['y']);
			$draw->rectangle(CARD_WIDTH - $line_points['start']['x'], $line_points['start']['y'], CARD_WIDTH - $line_points['end']['x'], $line_points['end']['y']);
		}

		if ($this->circle) {
			$draw->setFillColor($this->circle->getColour());
			$circle_points = $this->circle->getCoordinates();
			$draw->circle($circle_points['origin']['x'], $circle_points['origin']['y'], $circle_points['final']['x'], $circle_points['final']['y']);
		}

		if ($this->triangles) {
			$draw->setFillColor($this->triangles->getColour());
			$triangle_points = $this->triangles->getCoordinates();
			$draw->polygon($triangle_points);

			foreach($triangle_points as $key => $point) {
				$triangle_points[$key]['y'] = CARD_HEIGHT - $point['y'];
			}
			$draw->polygon($triangle_points);
		}

		if (CARD_DEBUG) {
			$x = 0;
			$y = 0;
			while($x < CARD_WIDTH) {
				$draw->setStrokeColor("#BBBBBB");
				$draw->line($x, 0, $x, CARD_HEIGHT);

				$x += 10;
			}
		}

		$this->card->drawImage($draw);
		$this->card->writeImage(CARD_WRITE_LOCATION.$this->getName().".png");
	}

	private function getName() {
		$line_colour = $this->lines ? $this->colourToName($this->lines->getColour()) : 'none';
		$circle_colour = $this->circle ? $this->colourToName($this->circle->getColour()) : 'none';
		$triangle_colour = $this->triangles ? $this->colourToName($this->triangles->getColour()) : 'none';
		return "line_".$line_colour."_circle_".$circle_colour."_triangle_".$triangle_colour;
	}

	private function colourToName($colour) {
		switch($colour) {
			case COLOUR_ONE:
				return '1';
			case COLOUR_TWO:
				return '2';
			case COLOUR_THREE:
				return '3';
			default:
				return '0';
		}
	}
}

class Shape {
	private static $shape_buffer = 30;
	private static $shape_border_reference;
	// TODO - maybe make the others extend this and have it by default
	public static function getShapeBuffer() {
		return Shape::$shape_buffer;
	}
	public static function getBorderReference() {
		if (!isset(Shape::$shape_border_reference)) {
			 Shape::$shape_border_reference = Lines::getBorderReference() + Shape::$shape_buffer;
		}
		return Shape::$shape_border_reference;
	}
}

class Lines {
	private $colour;
	private static $line_border_reference;

	public static function getBorderReference() {
		if (!isset(Lines::$line_border_reference)) {
			 Lines::$line_border_reference = CARD_WIDTH / 3;
		}
		return Lines::$line_border_reference;
	}

	function Lines($colour) {
		$this->colour = $colour;
	}

	public function getColour() {
		return $this->colour;
	}

	public function getCoordinates() {
		return array(
			'start'=> array('x'=>Lines::getBorderReference() - LINE_WIDTH, 'y'=>0),
			'end'  => array('x'=>Lines::getBorderReference(), 'y'=>CARD_HEIGHT)
		);
	}
}

class Circle {
	private $colour;
	private static $centerx;
	private static $centery;

	public static function getCenterX() {
		if (!isset(Circle::$centerx)) {
			 Circle::$centerx = CARD_WIDTH / 2;
		}
		return Circle::$centerx;
	}
	public static function getCenterY() {
		if (!isset(Circle::$centery)) {
			 Circle::$centery = CARD_HEIGHT / 2;
		}
		return Circle::$centery;
	}

	function Circle($colour) {
		$this->colour = $colour;
	}

	public function getColour() {
		return $this->colour;
	}

	public function getCoordinates() {
		return array(
			'origin'=> array('x'=>Circle::getCenterX(), 'y'=>Circle::getCenterY()),
			'final' => array('x'=>Circle::getCenterX() + (Circle::getCenterX() - (Lines::getBorderReference() + Shape::getShapeBuffer())), 'y'=>Circle::getCenterY())
		);
	}
}

class Triangles {
	private $colour;
	function Triangles($colour) {
		$this->colour = $colour;
	}

	public function getColour() {
		return $this->colour;
	}

	public function getCoordinates() {
		$triangle_line_length = CARD_WIDTH - 2*(Lines::getBorderReference() + Shape::getShapeBuffer());
		$triangle_height = 1.73 / 2 * $triangle_line_length;
		$triangle_center_y = CARD_HEIGHT / 4;
		return array(
			array('x'=>Circle::getCenterX(), 'y'=>$triangle_center_y - $triangle_height / 2),
			array('x'=>CARD_WIDTH - Shape::getBorderReference(), 'y'=>$triangle_center_y + $triangle_height / 2),
			array('x'=>Shape::getBorderReference(), 'y'=>$triangle_center_y + $triangle_height / 2)
		);
	}
}

$lines = array(
	new Lines(COLOUR_ONE),
	new Lines(COLOUR_TWO),
	new Lines(COLOUR_THREE),
	'none'
);
$circles = array(
	new Circle(COLOUR_ONE),
	new Circle(COLOUR_TWO),
	new Circle(COLOUR_THREE),
	'none'
);
$triangles = array(
	new Triangles(COLOUR_ONE),
	new Triangles(COLOUR_TWO),
	new Triangles(COLOUR_THREE),
	'none'
);

foreach($lines as $line) {
	if ($line == "none") $line = null;

	foreach($circles as $circle) {
		if ($circle == "none") $circle = null;

		foreach($triangles as $triangle) {
			if ($triangle == "none") $triangle = null;

			if (is_null($triangle) && is_null($circle) && is_null($line)) continue;

			$card = new Card($line, $circle, $triangle);
			$card->genCard();
		}
	}
}

die();

$image = new Imagick();
$image->newImage($full_width, $full_height, '#FFFFFF');

$draw = new ImagickDraw();
$draw->setStrokeOpacity(1);

$colour = "#000000"; // TODO - this will change based on many things when generating the full deck

$draw->setFillColor($colour);

$width = $full_width - $bleedetc;

$line_width = 100;

$shape_buffer = 30;

$line_border_reference = $width / 3;

$draw->rectangle($line_border_reference - $line_width, 0, $line_border_reference, $full_height);
$draw->rectangle($full_width - $line_border_reference, 0, $full_width - $line_border_reference + $line_width, $full_height);

$centerx = $full_width / 2;
$centery = $full_height / 2;

$shape_border_reference = $line_border_reference + $shape_buffer;

$draw->circle($centerx, $centery, $centerx + ($centerx - $shape_border_reference), $centery);

$triangle_line_length = $full_width - 2*$shape_border_reference;
$triangle_height = 1.73 / 2 * $triangle_line_length; // TODO - ceil it. also, 1.73 is the approx of root 3.
$triangle_center_y = $full_height / 4;

$triangle_points = array(
	array('x'=>$centerx, 'y'=> $triangle_center_y - $triangle_height / 2), //top point
	array('x'=>$full_width - $shape_border_reference, 'y'=> $triangle_center_y + $triangle_height / 2),
	array('x'=>$shape_border_reference, 'y'=> $triangle_center_y + $triangle_height / 2)
);

$draw->polygon($triangle_points);

foreach($triangle_points as $key => $point) {
	$triangle_points[$key]['y'] = $full_height - $point['y'];
}
$draw->polygon($triangle_points);

// draw a grid on top
// TODO - for testing!!!
if ($debug) {
	$x = 0;
	$y = 0;
	while($x < $full_width) {
		$draw->setStrokeColor("#BBBBBB");
		$draw->line($x, 0, $x, $full_height);

		$x += 10;
	}
}

$image->drawImage($draw);
$image->writeImage("test.png");
