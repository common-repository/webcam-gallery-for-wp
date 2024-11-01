<?php

function wpcamGetLanguage(){
    return get_bloginfo( 'language' );
}

function wpcamTextLn(){
	//$bloginfo = get_bloginfo( 'language' );
	include_once __DIR__ . '/languages/'.wpcamGetLanguage().'.php';
}

function wpcam_enqueue_styles() {
    wp_enqueue_style( 'wpcam-style', plugins_url( 'css/wpcam.css', __FILE__ ), array(), '2014.09.19', 'screen' );
    wp_enqueue_style( 'wpcam-scroll-butt-style', plugins_url( 'css/scrollable-buttons.css', __FILE__ ), array(), '2014.09.19', 'screen' );
    wp_enqueue_style( 'wpcam-scroll-hor-style', plugins_url( 'css/scrollable-horizontal.css', __FILE__ ), array(), '2014.09.19', 'screen' );
}

function wpcam_enqueue_js(){
    wp_enqueue_script('wpcam-jquery-tools-js', plugins_url( 'script/jquery.tools.min.js', __FILE__ ));
    wp_enqueue_script('wpcamjs', plugins_url( 'script/wpcam.js', __FILE__ ));

}

function wpcam(){
    
    $upload_dir = wp_upload_dir();
    $wpcam_dir = get_option('wpcam_dir');
    $wpcam_replace = get_option('wpcam_file_name_example');
    $wpcam_filename_date = get_option('wpcam_file_name_type');
    $wpcam_reload_time = get_option('wpcam_reload_time');
    
    $images = wpcamImagesGet($wpcam_dir);
    /*
    print '<pre>';
    print_r($images);
    print '</pre>';
    */
    $numImg = count($images);
    $div = $numImg/4;
    
    if($numImg > 0):
	$_img = '  
	<div id="wpcamGallery">    
	<div id="image_wrap">
	<!-- Initially the image is a simple 1x1 pixel transparent GIF -->
	<a class="thickbox" rel="gimage" title="image-civita-di-bagnoregio" target="_blank"><img src="'.plugins_url( 'media/img/blank.gif', __FILE__ ).'" width="600" height="375" style="border:1px solid #fff;" /></a>
	  </div>
	  
	  <div style="margin:0 auto; width: 634px; height:120px;">
	  <!-- "previous page" action -->
	  <a class="prev browse left"></a>
	  
	      <div class="scrollable" id="scrollable">
	  
	    <!-- root element for the items -->
	    <div class="items">

	    
	  ';
      
	  foreach($images as $imgs_id=>$imgs):
	  $class = (ceil($imgs_id%4) == 0) ? 'active' : '';
	      $_img .= (ceil($imgs_id%4) == 0) ? '<div>' : '';
	      $_img .= '<div id="divimg"><img src="'.$upload_dir['baseurl'].'/'.$wpcam_dir.'/'.$imgs.'" />';
	      
	      if($wpcam_filename_date == 1):
	           $newfilename = wpcamSetFileNameFromDate($imgs, $wpcam_replace);
	           $_img .= '<span style="position:relative;font-size: 10px;text-align:center;">'.$newfilename.'</span>';
	      else:
	           $_img .= '<span style="position:relative;font-size: 10px;">'.$imgs.'</span>';
	      endif;
	      $_img .= '</div>';
	      if( ceil(($imgs_id+1)%4) == 0):
		  $_img .= '</div>';
	      endif;
	  endforeach;
	  
	  $_img .= '</div>';
	  
	  
	      
	  $_img .= '
	    </div>
	 
	  </div>
	  
	  <!-- "next page" action -->
	  <a class="next browse right"></a>
	  </div>
	      </div>
	      <script>
	      setTimeout(function(){
               window.location.reload();
            }, '.$wpcam_reload_time.'); 
                
               
	    
	      </script>
	  ';
	  
	  return $_img;
    else:
	   #echo 'No Image Found!';
    endif;
    
}


/**
 * @abstract init form for check ticket history into content
 * 
 * @param type $content
 * @return type
 */
function wpcamInit($content){
    $wpcam = wpcam();
    return preg_replace("/{wpcam}/", $wpcam, $content);
}
add_filter('the_content', 'wpcamInit');

/**
 * Get a formatted php version
 * @return number
 */
function phpV(){
    return (int) substr(phpversion(), 2, 1);
}


/**
 * Get images
 * @param unknown $wpcam_dir
 * @return multitype:
 */
function wpcamImagesGet($wpcam_dir){
    
    $upload_dir = wp_upload_dir();
    
    $images = array();
    
    if (file_exists($upload_dir['basedir'].'/'.$wpcam_dir.'/')) {
        $dir = $upload_dir['basedir'].'/'.$wpcam_dir.'/';

    	if (is_dir($dir)) {
    	    
    	    if ($dh = opendir($dir)) {
   
    	       if(phpV() >= 4){
    	           $images = array_diff(scandir($dir, SCANDIR_SORT_DESCENDING), array('..', '.'));
    	       }    
    	       else{
    	           $images_ = array_diff(scandir($dir), array('..', '.'));
    	           foreach ($images_ as $im){
    	               array_unshift($images, $im);
    	           }
    	       }
    		closedir($dh);
    	    }
    	}
    }
   # print_r($images);
    return $images;
}



function wpcamSetFileNameFromDate($filename, $replace)
{
    #echo $filename.' - ';
    $rep = explode(",", $replace);
    foreach($rep as $_rep):
        $filename = preg_replace("/$_rep/", "", $filename);
    endforeach;
    #echo $filename.'<br />';
    preg_match("/-/", $filename, $match);
    if (count($match) > 0) :
        $d = explode("-", $filename);
        if (count($d[0]) == 4) :
            $_y = $d[0];
            $_m = $d[1];
            if (strlen($d[2]) == 2) :
                $_d = $d[2];
             else :
                $_d_ = explode(" ", $d[2]);
                $_d = $_d_[0];
                $_t = $_d_[1];
            endif;
            
            $newFileName = $_d . '/' . $_m . '/' . $_y . ' ' . $_t;
         

        else :
            
            $newFileName = $filename;
        

        endif;
     else :
        $_y = substr($filename, 0, 4);
        $_m = substr($filename, 4, 2);
        $_d = substr($filename, 6, 2);
        $_h = substr($filename, 8, 2);
        $_i = substr($filename, 10, 2);
        $_s = substr($filename, 12, 2);
        
        $newFileName = $_d . '/' . $_m . '/' . $_y . ' ' . $_h . ':' . $_i . ':' . $_s;
    

    endif;
    
    return $newFileName;
}