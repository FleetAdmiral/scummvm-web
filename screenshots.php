<?php
// set this for position of this file relative
$file_root = ".";

// load libraries
require($file_root."/include/"."incl.php");
require($file_root."/include/"."scr-categories.php");

function display_single_shot($cat1, $cat2, $cat3) {
}

html_page_header('ScummVM :: Screenshots');

html_content_begin('Screenshots');

$view = $HTTP_GET_VARS['view'];
$offset = $HTTP_GET_VARS['offset'];
$cat1 = $HTTP_GET_VARS['cat1'];
$cat2 = $HTTP_GET_VARS['cat2'];

?>
<script>
function openWin(fileToOpen,nameOfWindow,width,height) {
	myWindow = window.open("",nameOfWindow,"menubar=no,scrollbars=no,status=no,width="+width+",height="+height);
	myWindow.document.open();
	myWindow.document.write('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">\n')
	myWindow.document.write('<html><head><title>ScreenShot Viewer</title><link rel="stylesheet" href="styles.css" type="text/css"></head>')
	myWindow.document.write('<body><a href="javascript:self.close();"><img src="'+ fileToOpen +'"></a></body></html>');
	myWindow.document.close();
}
</script>
<?php

if ($view == "") {
?>
  <div class="par-item">
    <div class="par-head">
       Screenshots
    </div>
    <div class="par-intro">
<?php


  // Random screenshot
  $randImg = rand(0, $screenshots_count - 1);
?>
<br>
<table border=0 width="100%">
<tr><td align="left" valign="center">

    <div class="navigation">
       Navigation

       <div class="nav-dots">
	  &nbsp;
       </div>

<?php  display_categories_list(); ?>
    </div>

</td><td style="width:300px; vertical-align:center; align:left">

<table border=0 align="left" cellspacing=0>
<tr><td width=280 height=37 colspan=4><img src="images/rs-top.png" /></td></tr>
<tr><td width=17 height=10 colspan=2><img src="images/rs-top-left.png" /></td><td rowspan=2 width=256 height=192>
<?php
  echo "<a href=\"javascript:openWin('./screenshots/{$screenshots[$randImg]}','scummvm',640,483);\"\n";
  echo "  onMouseOver=\"window.status='Click to View Full Size Image';return true;\"\n";
  echo "  onMouseOut=\"window.status='';return true;\">";
  echo '<img align=right src="'.screenshot_thumb_from_full($screenshots[$randImg]).'"/';
  echo ' title="Click to view Full Size"></a>';
?>
</td><td style="background:#a82709;" width=7 height=192 rowspan=2></td></tr>
<tr><td width=10 height=182></td><td style="background:#a82709;" width=7></td></tr>
<tr><td width=280 height=21 colspan=4><img src="images/rs-bottom.png" /></td></tr>
</table>

</td></tr>
</table>

    <p>&nbsp;</p>
    </div>
    <div class="par-content">

<?php    

  display_categories();

?>
    </div>
  </div>
<?php
}
if ($view != -1 && view != "") {
  display_single_shot($cat1, $cat2, $view);
}
if ($view == -1) {
  // generate list of all shots to show
  $showlist = array();

  foreach ($categories as $i) {
    if ($cat1 == $i->_catnum || $cat1 == -1) {
      foreach ($i->_list as $j) {
        if ($cat2 == $j['catnum'] || $cat2 == -1) {
          for ($k = 0; $k < $scrcatnums[$i->_catnum][$j['catnum']]; $k++) {
            array_push($showlist, $i->_catnum."_".$j['catnum']."_{$k}");
	  }
        }
      }
    }
  }

  $num = 0;
  $where = 0;
  
  echo html_frame_start("Screenshot Gallery","540",2,0,"color0")."<tr>";

  while (list($c,$image) = each($showlist)) {
    // do not show images less than current pos
    if ($offset > $c)
      continue;

    // display image
    echo html_frame_td(
	'<table cellpadding="0" cellspacing="0"><tr><td align="center">'.
	"<a href=\"javascript:openWin('./screenshots/bigscummvm_$image.png','scummvm',640,483);\">".
	'<img src="'.screenshot_thumb_path($image).'" '.
	'width="'.$thumb_w.'" height="'.$thumb_h.'" alt="Screenshot '.$c.'"></a>'.
	'</td></tr><tr><td align="center">'.
	screenshot_caption($image).
	'</td></tr></table>',
	'align=center class="color0" style="padding-top: 10px; font-style: italic;"'
    );

    // count number of images displayed.
    $num++;

    // end row at 2
    if (($num % 2 == 0) && ($num != 1)) {
      echo "</tr><tr>\n";
    }
  }

  echo "</tr>";

  echo html_frame_end();
 }

html_content_end();
html_page_footer();

?>
