<?php
/**
 * piwikTracker Addon 
 *
 * @author DECAF
 * @version $Id$
 */

require_once('raphaelizer.class.php');

class raphaelizerPiwikStats extends raphaelizer
{

  private $data;
  private $stats;
  private $header_date;
  private $header_type;
  private $nb_columns;
  private $max;
  private $color_background       = '#eff9f9';
  private $color_background_alt   = '#dfe9e9';
  private $color_visits           = '#3c9ed0';
  private $color_uniq_visitors    = '#14568a';
  private $color_actions          = '#5ab8ef';
  private $color_text             = '#000';
  private $show                   = array('nb_uniq_visitors', 'nb_visits', 'nb_actions');
  private $segment_width;
  private $bar_width;
  private $offset_x;
  private $offset_y;
  private $canvas_h;


  /**
   * (void)__construct((str)id,(int)w,(array)options,(object)I18N)
   * 
   * Constructor for class raphaelizerPiwikStats
   * 
   * @author DECAF
   **/
  public function __construct($id='stats_canvas', $width = 745, $options=array(), $I18N)
  {
    // TODO: width & height needs to be setable
    foreach($options as $key => $opt)
    {
      if ($this->$key) 
      {
        $this->$key = $opt;
      }
    }
    
    $this->canvas_h = 240 + (count($this->show) * 20);
    parent::__construct($width, $this->canvas_h, $id);
    $this->stats = array();
    $this->I18N = $I18N;
  }


  /**
   * (void)setStats((array)stats)
   * 
   * set stats and calls setData
   * 
   * @author DECAF
   **/
  public function setStats($stats = array())
  {
    $this->stats = $stats;
    $this->setData();
  }


  /**
   * (void)canvas((str)bgcolor)
   * 
   * prepares the widget
   * 
   * @author DECAF
   **/
  public function canvas($bgcolor=FALSE)
  {
    parent::canvas($bgcolor);
    // draw bg stripes
    $stripe_height = 50;
    for ($i=0;$i<4;$i++)
    {
      $bgcolor = ($i%2) ? $this->color_background : $this->color_background_alt;
      $this->rect(10,($i*$stripe_height + 10),($this->w - 20),$stripe_height,array('fill' => $bgcolor, 'stroke-width' => 0));
    }
    $this->path(
      array(
        0 => array('x' => 10, 'y' => 211),
        1 => array('x' => $this->w-5, 'y' => 211)
      ), 
      array(
        'stroke-width'  => '1', 
        'stroke'        => '#ccc'
      )
    );

    $this->segment_width    = round(($this->w - 120) / $this->nb_columns);
    $this->bar_width        = round(($this->segment_width / (count($this->show)) - (3/count($this->show))) );
    $this->offset_x         = 100;
    $this->offset_y         = 210;

    $this->drawBranding();
    $this->drawScale();
    $this->drawCaptions();
    $this->drawLegend();
    $this->drawStatBars();
  }


  /**
   * (void)drawBranding()
   * 
   * draws decaf logo on the canvas
   * 
   * @author DECAF
   **/
  public function drawBranding()
  {
    $this->text(($this->w - 65), ($this->h - 15),'Addon by', array(
      'text-anchor' => 'end',
      'font'        => 'Helvetica, Verdana, Arial, sans-serif',
      'font-size'   => '9',
      'fill'        => '#bbb'
    ));
    $this->image('/files/addons/decaf_piwik_tracker/logo.decaf.gif',($this->w - 60),($this->h - 23),50,12, array('opacity' => '0.2'), 'logo_decaf');
    $this->addEventListener('logo_decaf', 'mouseover', 'this.attr({"opacity": 1, "cursor": "pointer"});');
    $this->addEventListener('logo_decaf', 'click', 'window.open("http://decaf.de/");');
    $this->addEventListener('logo_decaf', 'mouseout', 'this.attr({"opacity": 0.2});');
  }


  /**
   * (void)drawLegend()
   * 
   * draws the legend on the canvas
   * 
   * @author DECAF
   **/
  public function drawLegend()
  {
    $x = 10;
    $y = 238;
    foreach ($this->show as $type) 
    {
      switch ($type) 
      {
        case 'nb_uniq_visitors':
          $color = $this->color_uniq_visitors;
          break;
        case 'nb_visits':
          $color = $this->color_visits;
          break;
        case 'nb_actions':
          $color = $this->color_actions;
          break;
      }
      $this->rect($x,$y,13,13,array(
        'fill'          => $color,
        'stroke-width'  => '0'
      ));
      // $x += 16;
      
      $this->text($x+16,$y+7,$this->I18N->msg($type),array(
        'fill'          => $color,
        'font'          => 'Helvetica, Arial, sans-serif',
        'font-size'     => '12',
        'font-weight'   => 'bold',
        'text-anchor'   => 'start', 
      ));
      $y += 20;
    }
  }


  /**
   * (void)drawCaptions()
   * 
   * draws the captions on the canvas
   * 
   * @author DECAF
   **/
  public function drawCaptions()
  {
    $i=0;
    
    $this->text(80, 225, $this->I18N->msg('piwik_api_date'),array(
      'font'        => 'Helvetica, Arial, sans-serif',
      'font-size'   => '11',
      'font-weight' => 'bold',
      'text-anchor' => 'end'
    ));
    foreach($this->header_date as $date)
    {
      $this->text($this->offset_x + round($this->segment_width/2) + ($this->segment_width * $i), 225, $this->convertPiwikDate($date),array(
        'font'        => 'Helvetica, Arial, sans-serif',
        'font-size'   => '11',
        'font-weight' => 'bold'
      ));
      $i++;
    }
  }


  /**
   * (void)drawScale()
   * 
   * draws the scale on the canvas
   * 
   * @author DECAF
   **/
  public function drawScale()
  {
    $nb_actions_step      = $this->max['nb_actions'] / 4;
    $nb_visits_step       = $this->max['nb_visits'] / 4;
    $nb_uniq_visitors_step = $this->max['nb_uniq_visitors'] / 4;
    for ($i = 4; $i > 0; $i--)
    {
      $y = 236;
      if (in_array('nb_actions', $this->show))
      {
        $this->text(80,228 - (50 * $i), $nb_actions_step * $i,
          array(
            'text-anchor' => 'end', 
            'fill'        => $this->color_actions, 
            'font-weight' => 'bold',
            'font-size'   => '12'
          ));
          $y = 243;
      }
      if (in_array('nb_visits', $this->show)) 
      {
        $this->text(80,$y - (50 * $i), $nb_visits_step * $i,
          array(
            'text-anchor' => 'end', 
            'fill'        => (in_array('nb_uniq_visitors',$this->show)) ? $this->color_uniq_visitors : $this->color_visits, 
            'font-weight' => 'bold',
            'font-size'   => '12'
          ));
      } else {
        if (in_array('nb_uniq_visitors', $this->show)) 
        {
          $this->text(80,$y - (50 * $i),$nb_uniq_visitors_step * $i,
            array(
              'text-anchor' => 'end', 
              'fill'        => $this->color_uniq_visitors, 
              'font-weight' => 'bold',
              'font-size'   => '12'
            ));
        }
      }
        
    }
  }


  /**
   * (void)drawStatBars()
   * 
   * draws the bars for the statistics on the canvas
   * 
   * @author DECAF
   **/
  public function drawStatBars()
  {
    if (!$this->nb_columns) 
    {
      return;
    }

    $actions_ratio = 200 / $this->max['nb_actions'];
    $visits_ratio  = 200 / $this->max['nb_visits'];
    if (in_array('nb_visits', $this->show)) 
    {
      $uniq_visitors_ratio  = 200 / $this->max['nb_visits']; // visits & unique visitors share the same ratio
    } 
    else
    {
      $uniq_visitors_ratio  = 200 / $this->max['nb_uniq_visitors'];
    }
    $h=0;
    for( $i=0; $i < $this->nb_columns; $i++ )
    {
      $j=0;
      $x = ($this->offset_x + ($this->segment_width * $i));
      $y = $this->offset_y - $h;
      // draw divider
      $this->path(array(
        0 => array('x' => ($x - 1), 'y' => 10),
        1 => array('x' => ($x - 1), 'y' => 210),
      ),array('stroke-width' => '1', 'stroke' => $this->color_background));
      
      foreach($this->show as $type)
      {
        switch ($type)
        {
          case 'nb_actions':
            $h      = round($this->data[$i][$type] * $actions_ratio);
            $color  = $this->color_actions;
            break;
          case 'nb_visits':
            $h      = round($this->data[$i][$type] * $visits_ratio);
            $color  = $this->color_visits;
            break;
          case 'nb_uniq_visitors':
            $h      = round($this->data[$i][$type] * $uniq_visitors_ratio);
            $color  = $this->color_uniq_visitors;
            break;
        }
        $x = ($this->offset_x + ($this->segment_width * $i)) + round($this->bar_width * $j);
        $y = $this->offset_y - $h;

        $this->rect($x,$y,$this->bar_width,$h,array('fill' => '#eff9f9', 'stroke-width' => '0'));
        $elem = 'bar_'.$i.'_'.$j;
        $this->rect($x+1,$y,$this->bar_width-1,$h,array('fill' => $color, 'stroke-width' => '0'), $elem);
        if ($h > 12)
        {
          $this->text($x + $this->bar_width - 4 , $y+9, $this->data[$i][$type],array(
            'fill'        => '#fff',
            'font'        => 'Helvetica, Arial, sans-serif',
            'font-size'   => '9',
            'text-anchor' => 'end'
          ));
        }
        else 
        {
          $this->text($x + $this->bar_width - 4, $y-5, $this->data[$i][$type],array(
            'fill'        => $color,
            'font'        => 'Helvetica, Arial, sans-serif',
            'font-size'   => '9',
            'text-anchor' => 'end'
          ));
        }
        $j++;
      }
      
    }
    
  }


  /**
   * (void)setData()
   * 
   * set the data so raphael can use it, set max for the different types
   * 
   * @author DECAF
   **/
  public function setData()
  {
    $i=0;
    $max = array(
      'total'             => 1,     // we don't wanna divide by zero
      'nb_actions'        => 1,
      'nb_uniq_visitors'  => 1,
      'nb_visits'         => 1
    );
    $this->header_date  = array();
    $this->header_type  = array();
    $this->data         = array();

    foreach($this->stats as $date => $value)
    {
      $this->header_date[$i] = $date;
      if (count($this->show) > 1) {
        foreach($value as $k => $v) 
        {
          if (in_array($k, $this->show)) 
          {
            $this->header_type[$k] = $k;
            $this->data[$i][$k] = $v;
          }
          if ($v > $max['total'])
          {
            $max['total'] = $v;
          }
          if ($v > $max[$k])
          {
            $max[$k] = $v;
          }
          
        }
      } else {
        if (is_numeric($value))
        {
          $v=$value;
        } else {
          $v=FALSE;
        }
        $k = $this->show[0];
        $this->header_type[$k] = $k;
        $this->data[$i][$k] = $v;
        if ($v > $max['total'])
        {
          $max['total'] = $v;
        }
        if ($v > $max[$k])
        {
          $max[$k] = $v;
        }
      }

      $i++;
    }
    $this->max        = $this->normalizeMax($max);
    $this->nb_columns = count($this->header_date);
  }


  /**
    * (array)normalizeMax((array)max)
    * 
    * normalize the maximums
    * 
    * @author DECAF
    **/
  private function normalizeMax($max)
  {
    foreach ($max as &$m)
    {
      $len = strlen($m)-1;
      $first = substr($m,0,1);
      $first += 1;
      if ($m <= 10) 
      {
        $divider = 4;
      }
      else 
      {
        $divider = 2;
      }
      while (floor(($first / $divider)) != ($first / $divider))
      {
        $first += 1;
      }
      $m = $first;
      for($i=0;$i < $len;$i++)
      {
        $m .= '0';
      }
    }
    return $max;
  }


  /**
   * (array)getData()
   * 
   * getter for data
   **/
  public function getData()
  {
    return $this->data;
  }


  /**
   * (array)getMax()
   * 
   * getter for max
   **/
  public function getMax()
  {
    return $this->max;
  }


  /**
   * (int)getNbColumns()
   * 
   * getter for nb_columns
   **/
  public function getNbColumns()
  {
    return $this->nb_columns;
  }


  /**
   * (array)getHeaderDate()
   * 
   * getter for header_date
   **/
   public function getHeaderDate()
  {
    return $this->header_date;
  }


  /**
   * (array)getHeaderType()
   * 
   * getter for header_type
   **/  
   public function getHeaderType()
  {
    return $this->header_type;
  }


  /**
   * (str)convertPiwikDate((str)str)
   * 
   * converts the timestring delivered by Pikwik to something more readable
   * 
   * @author DECAF
   **/
  private function convertPiwikDate($str)
  {
    $retval = $str;
    $date = date_parse($str);
    if (strpos($str,'to')) // probably a week
    {
      $retval = date($this->I18N->msg('date_format_week'), mktime(0,0,0,$date['month'], $date['day'], $date['year']));
    } else {
      if (!$date['error_count'] && strlen($str) == 10) // this is a day
      {
        $retval = date($this->I18N->msg('date_format_day'), mktime(0,0,0,$date['month'], $date['day'], $date['year']));
      }
      if (!$date['error_count'] && strlen($str) == 7) // this is a month
      {
        $retval = date($this->I18N->msg('date_format_month'), mktime(0,0,0,$date['month'], 1, $date['year']));
      }
      
    }
    return $retval;
  }

} // end class