<?php
/**
 * Form per aggiornamento opzioni plugin
 */
function wpcamUpdateOptions(){
    ?>
    <div class="wrap">
        <div class="icon32" id=""><img src="<?php echo plugins_url( 'media/img/wpcamgallery32.png', __FILE__ ) ?>"></div>
        <h2><?php echo wpcam_title ?> :: <?php echo wpcam_config ?></h2>
         <form action="options.php" method="post">
             <?php settings_fields('wpcam_options_group'); ?>
            <table class="form-table">
            <tbody>
                <tr valign="top">
                    <td><label for="directory">Directory</label></td>
                    <td>/wp-content/uploads/<input type="text" name="wpcam_dir" id="wpcam_dir" value="<?php echo get_option('wpcam_dir'); ?>" size="50" /></td>
                    <td rowspan="3" style="vertical-align:top;padding:0 5px 0 5px;border:1px dotted #bbbbbb;background-color:#fff;text-align:center;">
                        <img src="<?php echo plugins_url( 'media/img/imseolab-b.png', __FILE__ ) ?>" width="150" />
                        <p><a href="http://blog.imseo.it/" target="_blank">http://blog.imseo.it/</a></p>
                    </td>
                </tr>
                <tr valign="top">
                    <td><label for="filename"><?php echo wpcam_file_name_example?></label></td>
                    <td>
                        <input type="text" name="wpcam_file_name_example" id="wpcam_file_name_example" value="<?php echo get_option('wpcam_file_name_example'); ?>" size="50" />
                        <p><?php echo wpcam_file_name_example_ex?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <td><label for="filedate"><?php echo filedate?></label></td>
                    <td>
                    <?php $sel = get_option('wpcam_file_name_type'); ?>
                        <select name="wpcam_file_name_type">
                            <option value="1" <?php ($sel == 1) ? print ' selected="selected"' : print '';?>><?php echo si?></option>
                            <option value="0" <?php ($sel == 0) ? print ' selected="selected"' : print '';?>>No</option>
                        </select>
                        <p><?php echo wpcam_file_name_type?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <td><label for="filename"><?php echo wpcam_reload_time?></label></td>
                    <td>
                        <input type="text" name="wpcam_reload_time" id="wpcam_reload_time" value="<?php echo get_option('wpcam_reload_time'); ?>" size="50" />
                        <p><?php echo wpcam_reload_time?></p>
                    </td>
                </tr>
            </tbody>
            </table>

            <p class="submit"><input type="submit" name="salva" id="salva" value="<?php echo SAVE ?>" class="button button-primary" /></p>
        </form>
        <hr style="size:1px; color:#eee;" />
        <?php   #include_once dirname( __FILE__ ) .   '/help/help-'.getLanguage().'.php'; ?>
    </div>
    <?php
}

/**
 * Attivazione voce di menu
 */
function wpCamGalleryMenu(){
    //add_menu_page(TITOLO PAGINA, TITOLO DEL MENU, LIVELLO DI ACCESSO, SLUG, FUNZIONE CHE RICHIAMA LA VOCE, URL ICONA OPZIONALE);
    add_menu_page('WP Cam Gallery', 'WPCam Gallery', 'administrator', 'WPCam', 'wpcamUpdateOptions', plugins_url( 'media/img/wpcamgallery.png', __FILE__ ) );
}
add_action('admin_menu', 'wpCamGalleryMenu');


function wpcamActivateSetDefaultOptions()
{
    add_option('wpcam_dir', 'directory', '', 'yes');
    add_option('wpcam_file_name_example', '192.168.1.64_01_20140531133455_TIMING.jpg', '', 'yes');
    add_option('wpcam_file_name_type', '0', '', 'yes');
    add_option('wpcam_reload_time', '5000', '', 'yes');
}
 
register_activation_hook( __FILE__, 'wpcamActivateSetDefaultOptions');


/**
 * Registrazione opzioni plugin
 */
function wpcamRegisterOptionsGroup()
{
    register_setting('wpcam_options_group', 'wpcam_dir');
    register_setting('wpcam_options_group', 'wpcam_file_name_example');
    register_setting('wpcam_options_group', 'wpcam_file_name_type');
    register_setting('wpcam_options_group', 'wpcam_reload_time');
    wpcamCreateDir();
}
 
add_action('admin_init', 'wpcamRegisterOptionsGroup');


/**
 * Create dir if not exist
 */
function wpcamCreateDir(){
    $wpcam_dir = get_option('wpcam_dir');
    $upload_dir = wp_upload_dir();
    if (!file_exists($upload_dir['basedir'].'/'.$wpcam_dir)) {
        mkdir($upload_dir['basedir'].'/'.$wpcam_dir, 0755, true);
    }
}






?>