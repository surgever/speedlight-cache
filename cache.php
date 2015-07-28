<?php 
/* Add to htaccess:
# BEGIN SpeedLight Cache 
RewriteEngine on
#Options All -Indexes
RewriteBase /
RewriteRule ^([/]?)$ cache.php?sec=home [L]
# END SpeedLight Cache
 */
 
/*** begin_caching.php ***/ 
// Settings
$cachedir = './';   // cache directory
$cachetime = 3600; // cache duration: 24h(86400) or 10min (600) or anything else
$cacheext = 'cache.php';   // extension
$cachepage = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$cachefile = $cachedir.'z-home-'.$cacheext;
// calculate cache file age
if(file_exists($cachefile)) {
  $cachelast = filemtime($cachefile);
} else {
  $cachelast = 0;
}
clearstatcache();

// show cache file if is fresh
if (time() - $cachetime <$cachelast) {
  @readfile($cachefile);
  exit();
}

// otherwise, let's cache the output
ob_start();  
include('index.php');
// generate new cache file
$fp = @fopen($cachefile, 'w');
// save minified buffer and close
@fwrite($fp, preg_replace(array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s'), array('>','<','\\1'),ob_get_contents())."\n<!--SpeedLight Cache ".date('c')."-->");
@fclose($fp);
ob_end_flush();

?>