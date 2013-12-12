<?php
/**
 * piwikTracker Addon 
 *
 * @author DECAF
 * @version $Id$
 */

class raphaelizer
{
  public $js;
  public $w;
  public $h;
  public $canvas_id;
  
  private $has_closing_tag;
  private $valid_attr = array('clip-rect', 'cx', 'cy', 'fill', 'fill-opacity', /* 'font', */ 'font-family', 'font-size', 'font-weight', 'height', 'opacity', 'path', 'r', 'rotation', 'rx', 'ry', 'scale', 'src', 'stroke', 'stroke-dash', 'stroke-linecap', 'stroke-linejoin', 'stroke-miterlimit', 'stroke-opacity', 'stroke-width', 'text-anchor', 'translation', 'width', 'x', 'y');
  // IE BUG - This sad excuse of a browser can't handle the 'font' attribute


  /**
   * (void)__construct((int)w,(int)h,(str)canvas_id)
   * 
   * Constructor for class raphaelizer
   * 
   * @author DECAF
   **/
  public function __construct($w, $h, $canvas_id='mycanvas')
  {
    $this->w  = $w;
    $this->h  = $h;
    $this->canvas_id = $canvas_id;
    $this->has_closing_tag = FALSE;
    $this->js = '<div id="'.$canvas_id.'" style="width: '.$this->w.'px; height: '.$this->h.'px;"></div>'."\n";
    $this->js .= '<script type="text/javascript" charset="utf-8">'."\n";
  }


  /**
   * (void)canvas((str)bgcolor)
   * 
   * prepares the raphael canvas
   * 
   * @author DECAF
   **/
  public function canvas($bgcolor = FALSE)
  {
    $this->js .= '  var '.$this->canvas_id.' = Raphael("'.$this->canvas_id.'", '.$this->w.', '.$this->h.');'."\n";
    if ($bgcolor) 
    {
      $this->rect(0,0,$this->w,$this->h,array('fill' => $bgcolor, 'stroke-width' => 0));
    }
  }


  /**
   * (void)rect((int)x,(int)y,(int)w,(int)h,(array)attr,(str)id)
   * 
   * draws a rectancle
   * 
   * @author DECAF
   **/
  public function rect($x, $y, $w, $h, $attr=array(), $id='')
  {
    if ($id)
    {
      $this->js .= '  var '.$id.' =';
    }
    $this->js .= '  '.$this->canvas_id.'.rect('.$x.','.$y.','.$w.','.$h.')';
    if (count($attr))
    {
      $this->addAttr($attr);
    }
    $this->js .= ';'."\n";
  }


  /**
   * (void)text((int)x,(int)y,(array)attr,(str)id)
   * 
   * draws text
   * 
   * @author DECAF
   **/
  public function text($x, $y, $text, $attr=array(), $id='')
  {
    if ($id)
    {
      $this->js .= '  var '.$id.' =';
    }
    $this->js .= '  '.$this->canvas_id.'.text('.$x.','.$y.',"'.$text.'")';
    if (count($attr))
    {
      $this->addAttr($attr);
    }
    $this->js .= ';'."\n";
  }


  /**
   * (void)text((str)src,(int)x,(int)y,(int)w,(int)h,(array)attr,(str)id)
   * 
   * draws an image on the canvas
   * 
   * @author DECAF
   **/
  public function image($src, $x, $y, $w, $h, $attr=array(), $id='')
  {
    if ($id)
    {
      $this->js .= '  var '.$id.' =';
    }
    $this->js .= '  '.$this->canvas_id.'.image("'.$src.'", '.$x.', '.$y.', '.$w.', '.$h.')';
    if (count($attr))
    {
      $this->addAttr($attr);
    }
    $this->js .= ';'."\n";
  }


  /**
   * (void)path((array)points,(array)attr,(str)id)
   * 
   * draws a path
   * the points need to be an array with array(x=>int,y=>int) as the single points
   * 
   * @author DECAF
   **/
  public function path($points, $attr=array(), $id='')
  {
    if ($id)
    {
      $this->js .= '  var '.$id.' =';
    }
    $path = $this->getSvgPath($points);
    $this->js .= '  '.$this->canvas_id.'.path("'.$path.'")';
    if (count($attr))
    {
      $this->addAttr($attr);
    }
    $this->js .= ';'."\n";
  }


  /**
   * (str)getSvgPath((array)points)
   * 
   * converts points to an SVG path
   * 
   * @return (str)svg_path
   * @author DECAF
   **/
  private function getSvgPath($points)
  {
    $i=0;
    $svg_path_str = '';
    foreach ($points as $path)
    {
      if(!$i) $cmd = 'M'; else $cmd = 'L';
      $svg_path_str .= $cmd.' '.($path['x']).' '.($path['y']).' ';
      $i++;
    }
    return trim($svg_path_str);
  }


  /**
   * (void)addEventListener((str)elem,(str)event,(str)action)
   * 
   * adds an event listener to an element
   * 
   * @author DECAF
   **/
  public function addEventListener($elem, $event, $action)
  {
    $this->js .= '  '.$elem.'.'.$event.'(function(event) { '.$action.' });';
  }


  /**
   * (void)addAttr((array)attr)
   * 
   * adds svg attributes
   * 
   * @author DECAF
   **/
  public function addAttr($attr) 
  {
    foreach ($attr as $key => $value) 
    {
      if (in_array($key,$this->valid_attr)) 
      {
        $this->js .= '.attr({"'.$key.'":"'.$value.'"})';
      }
    }
  }


  /**
   * (str)getJs()
   * 
   * returns the html code for an raphael widget
   * 
   * @return (str)this->js
   * @author DECAF
   **/
  public function getJs()
  {
    if (!$this->has_closing_tag) 
    {
      $this->setJsClosingTag();
    }
    return $this->js;
  }


  /**
   * (void)setJsClosingTag()
   * 
   * adds the closing script tag
   * 
   * @author DECAF
   **/
  public function setJsClosingTag()
  {
    $this->has_closing_tag = TRUE;
    // $this->js .= 'alert("raphaelizer js has been parsed.");'."\n";
    $this->js .= '</script>'."\n";
  }

} // end class