<?php

/**
 * Simple to use php5 class for image manipulation
 * 
 * @author Arjan Topolovec
 * @version 1.2
 * @license LGPL
 */

/*
 * changelog:
 * 1.4fm
 * - extended for new filters based on PHP4
 * - mirrorX/Y, grayscale, negative, sharpener
 * 
 * 1.3:
 * - use image resources with open and use them as arguments (example: watermark image)
 * 
 * 1.2:
 * - as parameter to resize function you can set max width or max height
 * - add simple strings to images
 * - merge two images
 * 
 * 1.1:
 * - added support for editing jpeg and gif images
 * - images can now be saved in jpeg and gif (based on file extension)
 * - added image resizing based on image ratio
 */

class Image{
	
	private function __construct(){}
	
	/**
	 * Open given image for editing
	 * Passed $image string must be a vailid image on disk
	 *
	 * @param string $image
	 * @static 
	 * @return object
	 */
	public static function open($image){
		if(is_string($image) && file_exists($image)){
			return new CreateImage($image);
		}
		
	}
	
}

class CreateImage{
	
	/**
	 * name of opened image
	 *
	 * @var string
	 */
	public $image_name;
	
	/**
	 * Opened image resource
	 *
	 * @var resource
	 */
	private $image;
	
	/**
	 * Info of opened image
	 * (width, height, bits, mime)
	 *
	 * @var array
	 */
	private $image_info;
	
	public function __construct($image){
		
		//get image info
		$get_size = getimagesize($image);
		$this->image_info = array(
			'width' => $get_size[0],
			'height' => $get_size[1],
			'bits' => $get_size['bits'],
			'mime' => $get_size['mime']
		);
		
		$this->image_name = $image;
		$this->image = $this->imcreate($image);
		
	}
	
	public function __destruct(){
		
		if(isset($this->image)){
			imagedestroy($this->image);
		}
		
	}
	
	/**
	 * Resize opened image to given $width and $height
	 *
	 * @param int $width
	 * @param int $height
	 * @return object
	 */
	public function resize($width = 0, $height = 0, $filter = array()){
		
		//check if only one lenght given
		if(is_int($width) && !$height){
			$height = (int)($width/($this->image_info['width']/$this->image_info['height']));
		} else if(is_int($height) && !$width){
			$width = (int)($height/($this->image_info['height']/$this->image_info['width']));
		}
		
		//check for filters
		/*
		if($filter['max_height'] && $height > $filter['max_height']){
			$height = $filter['max_height'];
			$width = (int)($height/($this->image_info['height']/$this->image_info['width']));
		} else if($filter['max_width'] && $width > $filter['max_width']){
			$width = $filter['max_width'];
			$height = (int)($width/($this->image_info['width']/$this->image_info['height']));
		}
		*/
		
		if(is_int($width) && is_int($height)){
			$image_old = $this->image;
			$this->image = imagecreatetruecolor($width,$height);
			
			imagecopyresampled($this->image, $image_old, 0, 0, 0, 0, $width, $height, $this->image_info['width'], $this->image_info['height']);
			imagedestroy($image_old);
			
			$this->image_info['width'] = $width;
			$this->image_info['height'] = $height;
			
			return $this;
			
		}
		
	}
	
	/**
	 * Add given watermark picture to opened image
	 *
	 * @param string $file
	 * @param string $position default is bottomright
	 * @return object
	 */
	public function watermark($file, $position = "bottomright"){
		
		$watermark = $this->imcreate($file, false);
		
		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		
		switch($position){
			case 'topleft':
				$watermark_pos_x = 0;
				$watermark_pos_y = 0;
				break;
			case 'topright':
				$watermark_pos_x = $this->image_info['width']-$watermark_width;
				$watermark_pos_y = 0;
				break;
			case 'bottomleft':
				$watermark_pos_x = 0;
				$watermark_pos_y = $this->image_info['height']-$watermark_height;
				break;
			case 'bottomright':
				$watermark_pos_x = $this->image_info['width']-$watermark_width;
				$watermark_pos_y = $this->image_info['height']-$watermark_height;
				break;
		}
		
		imagecopy($this->image, $watermark, $watermark_pos_x, $watermark_pos_y, 0, 0, 120, 40);
		
		imagedestroy($watermark);
		
		return $this;
	
	}
	
	/**
	 * Crop opened image
	 *
	 * @param int $top_x
	 * @param int $top_y
	 * @param int $bottom_x
	 * @param int $bottom_y
	 * @return object
	 */
	public function crop($top_x, $top_y, $bottom_x, $bottom_y){
		
		$image_old = $this->image;
		$this->image = imagecreatetruecolor($bottom_x-$top_x, $bottom_y-$top_y);
		
		imagecopy($this->image, $image_old, 0, 0, $top_x, $top_y, $this->image_info['width'], $this->image_info['height']);
		imagedestroy($image_old);
		
		$this->image_info['width'] = $bottom_x-$top_x;
		$this->image_info['height'] = $bottom_y-$top_y;
		
		return $this;
		
	}
	
	/**
	 * Rotate opened image for given degree angle
	 *
	 * @param int $degree Valid values -90,90,180
	 * @return object
	 */
	public function rotate($degree){
		
		if($degree != (-90 || 90 || 180)){ return new Image();}
		
		$this->image = imagerotate($this->image, $degree, 0);
		
		if($degree == 90 || $degree == -90){
			$width = $this->image_info['width'];
			$height = $this->image_info['height'];
			$this->image_info['width'] = $height;
			$this->image_info['height'] = $width;
		}
		
		return $this;
		
	}
	
	/**
	 * Apply filter to image
	 * 
	 * @param int $filter filter for function imagefilter http://si2.php.net/manual/en/function.imagefilter.php
	 */
	public function filter($filter, $arg1 = "", $arg2 = -1, $arg3 = -1, $arg4 = -1){
		
		imagefilter($this->image, $filter);
		
		return $this;
		
	}
	
	/**
	 * Apply PHP4-Filters to image
	 *
	 * @param string $filter
	 * @param int strength for some filters (1-x)
	 * @return object
	 */
	public function filterphp4($filter, $arg1 = ""){
		
		$image_old = $this->image;
			$imInfo = $this->imsize($image_old);
		$this->image = imagecreatetruecolor($imInfo['width'], $imInfo['height']);
		
		if ($arg1 != ""):
			$arg1 = abs($arg1);
		endif;
		
		switch($filter):
			case 'mirrorx':		imagecopyresampled($this->image, $image_old, 0, 0, $imInfo['width'], 0, $imInfo['width'], $imInfo['height'], -$imInfo['width'], $imInfo['height']); 
								imagedestroy($image_old);
								break;
								
			case 'mirrory':		imagecopyresampled($this->image, $image_old, 0, 0, 0, $imInfo['height'], $imInfo['width'], $imInfo['height'], $imInfo['width'], -$imInfo['height']); 
								imagedestroy($image_old);
								break;
								
			case 'grayscale':	for ($y=0; $y < $imInfo['height']; $y++):
									for ($x=0; $x < $imInfo['width']; $x++):
										$rgb = imagecolorat($image_old, $x, $y); 
										$r = ($rgb >> 16) & 255; 
										$g = ($rgb >> 8) & 255; 
										$b = $rgb & 255;
										$grey = (int)(($r+$g+$b)/3);
											$newrgb = imagecolorallocate($this->image, $grey, $grey, $grey);
											
										@imagesetpixel($this->image, $x, $y, $newrgb);
									endfor;
								endfor;
								break;

			case 'negative':	for ($y=0; $y < $imInfo['height']; $y++):
									for ($x=0; $x < $imInfo['width']; $x++):
										$rgb = imagecolorat($image_old, $x, $y); 
										$neg = ($rgb ^ 0xffffff);
										
										@imagesetpixel($this->image, $x, $y, $neg);
									endfor;
								endfor;
								break;

			case 'pixelate':	for ($y=0; $y < $imInfo['height']; $y+=$arg1):
									for ($x=0; $x < $imInfo['width']; $x+=$arg1):
										$rgb = imagecolorat($image_old, $x, $y); 
										
										//complex
										$newr = 0; $newg = 0; $newb = 0;
										$colours = array();

										for ($k = $x; $k < $x+$arg1; ++$k):
											for ($l = $y; $l < $y+$arg1; ++$l):
												// if we are outside the valid bounds of the image, use a safe colour
												if ($k < 0): 					$colours[] = $rgb; continue; 	endif;
												if ($k >= $imInfo['width']): 	$colours[] = $rgb; continue; 	endif;
												if ($l < 0): 					$colours[] = $rgb; continue; 	endif;
												if ($l >= $imInfo['height']): 	$colours[] = $rgb; continue; 	endif;
							
												// if not outside the image bounds, get the colour at this pixel
												$colours[] = imagecolorat($image_old, $k, $l);
											endfor;
										endfor;

										// cycle through all the colours we can use for sampling
										foreach($colours as $colour):
											$newr += ($colour >> 16) & 0xFF;
											$newg += ($colour >> 8) & 0xFF;
											$newb += $colour & 0xFF;
										endforeach;
											// now divide the master numbers by the number of valid samples to get an average
											$numelements = count($colours);
											$newr /= $numelements;
											$newg /= $numelements;
											$newb /= $numelements;
										$newrgb = imagecolorallocate($image_old, $newr, $newg, $newb);
										
										//imagefilledrectangle($this->image, $x, $y, $x+$arg1-1, $y+$arg1-1, $rgb);
										@imagefilledrectangle($this->image, $x, $y, $x+$arg1-1, $y+$arg1-1, $newrgb);
									endfor;
								endfor;
								break;

			case 'scatter':		for ($y=0; $y < $imInfo['height']; $y++):
									for ($x=0; $x < $imInfo['width']; $x++):
										$distx = rand(-$arg1, $arg1);
										$disty = rand(-$arg1, $arg1);
										
										if ($x + $distx >= $imInfo['width']) continue;
										if ($x + $distx < 0) continue;
										if ($y + $disty >= $imInfo['height']) continue;
										if ($y + $disty < 0) continue;
										
										$rgb = imagecolorat($image_old, $x, $y); 
										$newrgb = imagecolorat($image_old, $x + $distx, $y + $disty);
										
										@imagesetpixel($this->image, $x, $y, $newrgb);
										@imagesetpixel($this->image, $x + $distx, $y + $disty, $rgb);
									endfor;
								endfor;
								break;
								
			case 'sharpening':	$radius = 0.5; $threshold = 3; $strength = 50;	//PS-Standards
								$matrix = array(	array(1, 1, 0, 0, 0, 0, 50),
													array(0, 1, 1, 0, 1, 0, 33.33333),
													array(1, 0, 0, 1, 0, 1, 25),
													array(0, 0, 1, 0, 1, 0, 33.33333),
													array(1, 0, 0, 0, 0, 0, 25),
													array(0, 0, 0, 1, 0, 1, 20),
													array(0, 1, 0, 0, 0, 0, 16.666667),
													array(0, 0, 0, 0, 0, 0, 50)
												);	//ur, ul, or, l, r, o, u, mm
			
								//Werte optimieren
								if (!empty($arg1)):
									$strength = $arg1 * 10;
								endif;
								$strength *= 0.016;
								$radius *= 2;
									$radius = abs(round($radius));
									
								//temp. Kopien des Originals erstellen - für Farbwertaufbereitung
								$image_tmp1 = imagecreatetruecolor($imInfo['width'], $imInfo['height']);
								$image_tmp2 = imagecreatetruecolor($imInfo['width'], $imInfo['height']);
									imagecopy($image_tmp1, $image_old, 0, 0, 0, 0, $imInfo['width'], $imInfo['height']);
									imagecopy($image_tmp2, $image_old, 0, 0, 0, 0, $imInfo['width'], $imInfo['height']);
								$image_blur1 = imagecreatetruecolor($imInfo['width'], $imInfo['height']);
								$image_blur2 = imagecreatetruecolor($imInfo['width'], $imInfo['height']);
								
								//Radius auf TMP-Bilder anwenden - Bildverschiebung in alle Richtungen
								for ($m=0; $m < $radius; $m++):
									imagecopy($image_blur1, $image_old, 0, 0, 1, 1, $imInfo['width']-1, $imInfo['height']-1); 					//ol
										foreach ($matrix as $line):										
											imagecopymerge($image_blur1, $image_old, $line[0], $line[1], $line[2], $line[3], $imInfo['width']-$line[4], $imInfo['height']-$line[5], str_replace(",", ".", $line[6]));
										endforeach;
									imagecopy($image_tmp1, $image_blur1, 0, 0, 0, 0, $imInfo['width'], $imInfo['height']);
									
									//Vergleichsbild für Helligkeit
									imagecopy($image_blur2, $image_old, 0, 0, 0, 0, $imInfo['width'], $imInfo['height']);
										foreach ($matrix as $line):										
											imagecopymerge($image_blur2, $image_old, 0, 0, 0, 0, $imInfo['width'], $imInfo['height'], str_replace(",", ".", $line[6]));
										endforeach;
									imagecopy($image_tmp2, $image_blur2, 0, 0, 0, 0, $imInfo['width'], $imInfo['height']);
								endfor;
								
								//Bilder vergleichen und neue Farbwerte berechnen
								for ($y=0; $y < $imInfo['height']; $y++):
									for ($x=0; $x < $imInfo['width']; $x++):
										$rgb = imagecolorat($image_tmp2, $x, $y); 
										$rgbblur = imagecolorat($image_tmp1, $x, $y); 
										
										$r = ($rgb >> 16) & 0xFF;
										$g = ($rgb >> 8) & 0xFF;
										$b = $rgb & 0xFF;
										$rblur = ($rgbblur >> 16) & 0xFF;
										$gblur = ($rgbblur >> 8) & 0xFF;
										$bblur = $rgbblur & 0xFF;
										
										//Vergleichen
										if (abs($r - $rblur) >= $threshold):
											$r = max(0, min(255, ($strength * ($r - $rblur)) + $r));
										endif;										
										if (abs($g - $gblur) >= $threshold):
											$g = max(0, min(255, ($strength * ($g - $gblur)) + $g));
										endif;										
										if (abs($b - $bblur) >= $threshold):
											$b = max(0, min(255, ($strength * ($b - $bblur)) + $b));
										endif;

										$newrgb = imagecolorallocate($this->image, $r, $g, $b);
										
										@imagesetpixel($this->image, $x, $y, $newrgb);
									endfor;
								endfor;
								break;

		endswitch;
		
		return $this;
	
	}
	
	/**
	 * Show opened image
	 *
	 */
	public function show(){
		header("Content-type: image/png");
		imagepng($this->image);
		imagedestroy($this->image);
	}
	
	/**
	 * Save opened image
	 *
	 * @param string $filename name to save on hard drive
	 * @param int $quality compress ratio, from 100 (best) to 0 (worst), default 100
	 */
	public function save($filename, $quality = 100){
		$this->imsave($this->image, $filename, $quality);
		imagedestroy($this->image);
	}
	
	/**
	 * Create image resource based on mime type of the image
	 * 
	 * @param string $image bath to image
	 * @param bool $main_picture false if not main image like watermark (dafault true)
	 * @return resource
	 */
	private function imcreate($image, $main_picture = true){
		
		if(is_string($image)){
			$image_mime;
			
			if($main_picture == false){
				$info = getimagesize($image);
				$image_mime = $info['mime'];
			} else {
				$image_mime = $this->image_info['mime'];
			}
			
			if($image_mime == ('image/gif')){
				return imagecreatefromgif($image);
			} else if($image_mime == ('image/png')){
				return imagecreatefrompng($image);
			} else if($image_mime == ('image/jpeg')){
				return imagecreatefromjpeg($image);
			}
		} else if(is_resource($image) && get_resource_type($image) == "gd"){
			return $image;
		} else if(is_object($image)){
			return $image->getResource();
		}
		
	}
	
	/**
	 * Save image in format chosen by extension
	 * 
	 * @param resource $image
	 * @param string $filename
	 * @param int $quality
	 */
	private function imsave($image, $filename = NULL, $quality = NULL){
		
		$info = pathinfo($filename);
		$image_extension = $info['extension'];
		
		if($image_extension == ("jpeg" || "jpg")){
			imagejpeg($image, $filename, $quality);
		} else if($image_extension == ("png")){
			imagepng($image, $filename, 0);
		} else if($image_extension == ("gif")){
			imagejpeg($image, $filename);
		}
		
	}
	
	/**
	 * Write simple strings to image
	 * 
	 * @param string $text text to write on image
	 * @param int $x x-position of the text
	 * @param int $y y-position of the text
	 * @param int $size text size
	 * @param array $color text color
	 */
	public function text($text, $x=0, $y=0, $size = 5, $color = array(255, 255, 255)){
		
		imagestring($this->image, $size, $x, $y, $text, imagecolorallocate($this->image, $color[0], $color[1], $color[2]));
		
		return $this;
		
	}
	
	/**
	 * Merge two images
	 * 
	 * @param mixed $image
	 * @param int $x
	 * @param int $y
	 * @param int $opacity values from 0 to 100 
	 */
	public function merge($image, $x=0, $y=0, $opacity=100){
		
		$image_merge = $this->imcreate($image, false);
		
		$imInfo = $this->imsize($image_merge);
			
		imagecopymerge($this->image, $image_merge, $x, $y, 0, 0, $imInfo['width'], $imInfo['height'], $opacity);
		
		return $this;
		
	}
	
	/**
	 * Get image info
	 * 
	 * @param mixed $image
	 */
	private function imsize($image){
		
		if(is_string($image)){
			$get_info = getimagesize($image);
			$info = array('width' => $get_info[0], 'height' => $get_info[1]);
		} else if(is_resource($image)){
			if(get_resource_type($image) == 'gd'){
				$info = array('width' => imagesx($image), 'height' => imagesy($image));
			}
		}
		
		return $info;
		
	}
	
	/**
	 * Get image resource
	 */
	public function getResource(){
		
		return $this->image;
		
	}
	
}

?>