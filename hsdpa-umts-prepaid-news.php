<?php
/*
Plugin Name: HSDPA UMTS Prepaid News
Plugin URI: http://wordpress.org/extend/plugins/hsdpa-umts-prepaid-news/
Description: Adds a customizeable widget which displays the latest HSDPA UMTS Prepaid News by http://www.hsdpa-umts-prepaid.de/
Version: 1.0
Author: Oliver Schmid
Author URI: http://www.hsdpa-umts-prepaid.de/
License: GPL3
*/

function HSDPAUMTSPrepaidNews()
{
  $options = get_option("widget_HSDPAUMTSPrepaidNews");
  if (!is_array($options)){
    $options = array(
      'title' => 'HSDPA UMTS Prepaid News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Objekt erzeugen 
  $rss = simplexml_load_file( 
  'http://news.google.de/news?pz=1&cf=all&ned=de&hl=de&q=umts&cf=all&output=rss'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximale Anzahl an News, wobei 0 (Null) alle anzeigt
  $max_news = $options['news'];
  // maximale Länge, auf die ein Titel, falls notwendig, gekürzt wird
  $max_length = $options['chars'];
  
  // RSS Elemente durchlaufen 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Titel in Zwischenvariable speichern
    $title = $i->title;
    // Länge des Titels ermitteln
    $length = strlen($title);
    // wenn der Titel länger als die vorher definierte Maximallänge ist,
    // wird er gekürzt und mit "..." bereichert, sonst wird er normal ausgegeben
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_HSDPAUMTSPrepaidNews($args)
{
  extract($args);
  
  $options = get_option("widget_HSDPAUMTSPrepaidNews");
  if (!is_array($options)){
    $options = array(
      'title' => 'HSDPA UMTS Prepaid News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  HSDPAUMTSPrepaidNews();
  echo $after_widget;
}

function HSDPAUMTSPrepaidNews_control()
{
  $options = get_option("widget_HSDPAUMTSPrepaidNews");
  if (!is_array($options)){
    $options = array(
      'title' => 'HSDPA UMTS Prepaid News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['HSDPAUMTSPrepaidNews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['HSDPAUMTSPrepaidNews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['HSDPAUMTSPrepaidNews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['HSDPAUMTSPrepaidNews-CharCount']);
    update_option("widget_HSDPAUMTSPrepaidNews", $options);
  }
?> 
  <p>
    <label for="HSDPAUMTSPrepaidNews-WidgetTitle">Widget Title: </label>
    <input type="text" id="HSDPAUMTSPrepaidNews-WidgetTitle" name="HSDPAUMTSPrepaidNews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="HSDPAUMTSPrepaidNews-NewsCount">Max. News: </label>
    <input type="text" id="HSDPAUMTSPrepaidNews-NewsCount" name="HSDPAUMTSPrepaidNews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="HSDPAUMTSPrepaidNews-CharCount">Max. Characters: </label>
    <input type="text" id="HSDPAUMTSPrepaidNews-CharCount" name="HSDPAUMTSPrepaidNews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="HSDPAUMTSPrepaidNews-Submit"  name="HSDPAUMTSPrepaidNews-Submit" value="1" />
  </p>
  
<?php
}

function HSDPAUMTSPrepaidNews_init()
{
  register_sidebar_widget(__('HSDPA UMTS Prepaid News'), 'widget_HSDPAUMTSPrepaidNews');    
  register_widget_control('HSDPA UMTS Prepaid News', 'HSDPAUMTSPrepaidNews_control', 300, 200);
}
add_action("plugins_loaded", "HSDPAUMTSPrepaidNews_init");
?>