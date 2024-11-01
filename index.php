<?php
/*
Plugin Name: Webcam Gallery for WP
Plugin URI: http://
Description: Web Cam Gallery for WP is a plugin that allows you to create gallery with images from webcam
Version: 1.0.0
Author: Wallaceer
Author URI: http://imseo.it/wallaceer
*/

/*
Copyright (C) 2012 Wallaceer, http://imseo.it/wallaceer (wallaceer@imseo.it)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

require_once dirname( __FILE__ ) . '/functions.php';
wpcamTextLn();
    
    /**
     * load configuration's section
     */
    if ( is_admin() ):
            require_once dirname( __FILE__ ) . '/admin.php';
    else:

        add_action( 'wp_enqueue_scripts', 'wpcam_enqueue_styles' );

        
        add_action( 'wp_enqueue_scripts', 'wpcam_enqueue_js' );

    endif;


?>