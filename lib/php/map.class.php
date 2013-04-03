<?php
    class map {
        function load_map( $id = null , $lat = null , $lng = null , $clat = null , $clng = null , $zoom = null , $map_title = null , $map_description = null , $map_phone1 = null , $map_phone2 = null, $map_fax = null, $map_email = null , $message = null ){
            if( (int) $id > 0 ){
                $ajax = false;
            }else{
                $id                 = isset($_GET['id']) && $_GET['id'] > 0 ? $_GET['id'] : exit;
                $lat                = isset($_GET['lat']) ? $_GET['lat'] : MAP_LAT;
                $lng                = isset($_GET['lng'])  ? $_GET['lng'] : MAP_LNG;
                $clat               = isset($_GET['clat']) ? $_GET['clat'] : MAP_CLAT;
                $clng               = isset($_GET['clng']) ? $_GET['clng'] : MAP_CLNG;
                $zoom               = isset($_GET['zoom']) ? $_GET['zoom'] : MAP_ZOOM;
                $map_title          = isset($_GET['title']) && strlen($_GET['title']) > 0 ? $_GET['map_title'] : '';
                $map_description    = isset($_GET['map_description']) && strlen($_GET['map_description']) > 0 ? $_GET['map_description'] : '';
                $map_phone1         = isset($_GET['map_phone1']) && strlen($_GET['map_phone1']) > 0 ? $_GET['map_phone1'] : '';
                $map_phone2         = isset($_GET['map_phone2']) && strlen($_GET['map_phone2']) > 0 ? $_GET['map_phone2'] : '';
                $map_fax            = isset($_GET['map_fax']) && strlen($_GET['map_fax']) > 0 ? $_GET['map_fax'] : '';
                $map_email          = isset($_GET['map_email']) && strlen($_GET['map_email']) > 0 ? $_GET['map_email'] :  get_the_author_meta( 'user_email' , get_current_user_id() );
                $message            = isset($_GET['message']) && strlen($_GET['message']) > 0 ? $_GET['message'] : '';
                $hidde_contact      = isset($_GET['hidde_contact']) && strlen($_GET['hidde_contact']) > 0 ? $_GET['hidde_contact'] : '';

                $ajax = true;
            }

?>
            <script type="text/javascript">
                var f_phone1    = '<?php echo '<strong>' . __('Phone 1 ' , 'cosmotheme') . '</strong>: '; ?>';
                var f_phone2    = '<?php echo '<strong>' . __('Phone 2 ' , 'cosmotheme') . '</strong>: '; ?>';
                var f_fax       = '<?php echo '<strong>' . __('Fax ' , 'cosmotheme') . '</strong>: '; ?>';
                var f_email     = '<?php echo '<strong>' . __('Email ' , 'cosmotheme') . '</strong>: '; ?>';
                var id          = <?php echo $id; ?>;
                var lat         = <?php echo $lat; ?>;
                var clat        = <?php echo $clat; ?>;
                var lng         = <?php echo $lng; ?>;
                var clng        = <?php echo $clng; ?>;
                var title       = '<?php echo $map_title; ?>';
                var desc        = '<?php echo $map_description; ?>';
                var phone1      = '<?php echo $map_phone1; ?>';
                var phone2      = '<?php echo $map_phone2; ?>';
                var fax         = '<?php echo $map_fax; ?>';
                var email       = '<?php echo $map_email; ?>';
                var h_title     = '<?php echo '<h3>' . $map_title . '</h3>'; ?>';
                var h_desc      = '<?php echo '<p>' . $map_description . '</p>'; ?>';
                var h_phone1    = '<?php echo '<small>'; ?>' + f_phone1 + '<?php echo $map_phone1 . '</small><br />'; ?>';
                var h_phone2    = '<?php echo '<small>'; ?>' + f_phone2 + '<?php echo $map_phone2 . '</small><br />'; ?>';
                var h_fax       = '<?php echo '<small>'; ?>' + f_fax + '<?php echo $map_fax . '</small><br />'; ?>';
                var h_email     = '<?php echo '<small>'; ?>' + f_email + '<?php echo $map_email . '</small><br />'; ?>';
                var info        =  h_title + h_desc + h_phone1 + h_phone2 + h_fax + h_email;
                var message     = '<?php echo $message; ?>';
                var zoom        = <?php echo $zoom; ?>;
                <?php
                    $siteurl = get_option('siteurl');
                    if( !empty($siteurl) ){
                        $siteurl = rtrim( $siteurl , '/') . '/wp-admin/admin-ajax.php' ;
                    }else{
                        $siteurl = home_url('/wp-admin/admin-ajax.php');
                    }
                ?>

                var ajaxurl = "<?php echo $siteurl; ?>";
            </script>
            <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.min.js" type="text/javascript"></script>
            <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=<?php echo options::get_value( 'social' , 'google_map' ); ?>" type="text/javascript"></script>
            <script src="<?php echo get_template_directory_uri() ?>/js/google.map.js" type="text/javascript"></script>
            <div id="map_canvas"></div>
<?php
            if( $ajax ){
                exit();
            }
        }

        function set_map_meta(){
            if( isset($_GET['id']) && $_GET['id'] > 0 ){
                $id = $_GET['id'];
            }else{
                 exit();
            }

            if( isset($_GET['lat']) ){
                $lat = $_GET['lat'];
            }else{
                $lat  = get_post_meta( $id , 'map_lat');
                $lat  = !empty( $lat ) ? $lat[0] : MAP_LAT;
            }

            if( isset($_GET['lng']) ){
                $lng  = $_GET['lng'];
            }else{
                $lng  = get_post_meta( $id , 'map_lng');
                $lng  = !empty( $lng ) ? $lng[0] : MAP_LNG;
            }

            if( isset($_GET['clat']) ){
                $clat = $_GET['clat'];
            }else{
                $clat = get_post_meta( $id , 'map_center_lat');
                $clat = !empty( $clat ) ? $clat[0] : MAP_CLAT;
            }

            if( isset($_GET['clng']) ){
                $clng = $_GET['clng'];
            }else{
                $clng = get_post_meta( $id , 'map_center_lng');
                $clng = !empty( $clng ) ? $clng[0] : MAP_CLNG;
            }

            if( isset($_GET['zoom']) ){
                $zoom   = $_GET['zoom'];
            }else{
                $zoom = get_post_meta( $id , 'map_zoom');
                $zoom = !empty( $zoom ) ? $zoom[0] : MAP_ZOOM;
            }

            if( isset($_GET['map_title']) && strlen( $_GET['map_title'] ) ){
                $map_title = $_GET['map_title'];
            }else{
                $map_title = get_post_meta( $id , 'map_title');
                $map_title  = !empty( $map_title ) ? $map_title[0] : '';
            }

            if( isset($_GET['map_description']) && strlen( $_GET['map_description'] ) ){
                $map_description = $_GET['map_description'];
            }else{
                $map_description = get_post_meta( $id , 'map_description');
                $map_description  = !empty( $map_description ) ? $map_description[0] : '';
            }

            if( isset($_GET['map_phone1']) && strlen( $_GET['map_phone1'] ) ){
                $map_phone1 = $_GET['map_phone1'];
            }else{
                $map_phone1 = get_post_meta( $id , 'map_phone1');
                $map_phone1  = !empty( $map_phone1 ) ? $map_phone1[0] : '';
            }

            if( isset($_GET['map_phone2']) && strlen( $_GET['map_phone2'] ) ){
                $map_phone2 = $_GET['map_phone2'];
            }else{
                $map_phone2 = get_post_meta( $id , 'map_phone2');
                $map_phone2  = !empty( $map_phone2 ) ? $map_phone2[0] : '';
            }

            if( isset($_GET['map_fax']) && strlen( $_GET['map_fax'] ) ){
                $map_fax = $_GET['map_fax'];
            }else{
                $map_fax = get_post_meta( $id , 'map_fax');
                $map_fax  = !empty( $map_fax ) ? $map_fax[0] : '';
            }

            if( isset($_GET['map_email']) && strlen( $_GET['map_email'] ) ){
                $map_email = $_GET['map_email'];
            }else{
                $map_email = get_post_meta( $id , 'map_email');
                $map_email  = !empty( $map_email ) ? $map_email[0] : get_the_author_meta( 'user_email' , get_current_user_id());
            }

            if( isset($_GET['message']) && strlen( $_GET['message'] ) ){
                $message = $_GET['message'];
            }else{
                $message = get_post_meta( $id , 'message');
                $message  = !empty( $message ) ? $message[0] : '';
            }

            if( isset($_GET['hidde_contact']) && strlen( $_GET['hidde_contact'] ) ){
                $hidde_contact = $_GET['hidde_contact'];
            }else{
                $hidde_contact = get_post_meta( $id , 'hidde_contact');
                $hidde_contact = !empty( $hidde_contact ) ? $hidde_contact[0] : '';
            }

            update_post_meta( $id , 'map_lat' , $lat );
            update_post_meta( $id , 'map_lng' , $lng );
            update_post_meta( $id , 'map_center_lat' , $clat );
            update_post_meta( $id , 'map_center_lng' , $clng );
            update_post_meta( $id , 'map_zoom' , $zoom );
            update_post_meta( $id , 'map_title', $map_title );
            update_post_meta( $id , 'map_description' , $map_description );
            update_post_meta( $id , 'map_phone1' , $map_phone1 );
            update_post_meta( $id , 'map_phone2' , $map_phone2 );
            update_post_meta( $id , 'map_fax' , $map_fax);
            update_post_meta( $id , 'map_email' , $map_email);
            update_post_meta( $id , 'message' , $message );
            update_post_meta( $id , '$hidde_contact' , $hidde_contact );

?>

            <?php $type = 'hidden'; ?>
            <input type="<?php echo $type; ?>" id="map_lat"   value="<?php echo $lat; ?>">
            <input type="<?php echo $type; ?>" id="map_lng"   value="<?php echo $lng; ?>">
            <input type="<?php echo $type; ?>" id="map_clat"  value="<?php echo $clat; ?>">
            <input type="<?php echo $type; ?>" id="map_clng"  value="<?php echo $clng; ?>">
            <input type="<?php echo $type; ?>" id="map_zoom"  value="<?php echo $zoom; ?>">
<?php
            exit();
        }

        function get_contact_map( ){
            $id     = isset( $_GET['id'] ) && (int)$_GET['id'] > 0 ? (int) $_GET['id'] : exit;
            $type   = isset( $_GET['type'] ) && strlen( $_GET['type'] ) > 0 ? $_GET['type'] : exit;
            
            $post   = get_post( $id );


?>

            <link href="<?php echo get_template_directory_uri() ?>/lib/css/shcode/contact.css" type="text/css" rel="stylesheet"  />

            <form action="" method="post" style="">
                <fieldset>
                    <?php

                        $map_title          = get_post_meta( $post -> ID , 'map_title');
                        $map_description    = get_post_meta( $post -> ID , 'map_description');
                        $map_phone1         = get_post_meta( $post -> ID , 'map_phone1');
                        $map_phone2         = get_post_meta( $post -> ID , 'map_phone2');
                        $map_fax            = get_post_meta( $post -> ID , 'map_fax');
                        $map_email          = get_post_meta( $post -> ID , 'map_email');
                        $message            = get_post_meta( $post -> ID , 'message');
                        $hidde_contact      = get_post_meta( $post -> ID , 'hidde_contact');

                        $map_title          = !empty( $map_title ) ? $map_title[0] : '';
                        $map_description    = !empty( $map_description ) ? $map_description[0] : '';
                        $map_phone1         = !empty( $map_phone1 ) ? $map_phone1[0] : '';
                        $map_phone2         = !empty( $map_phone2 ) ? $map_phone2[0] : '';
                        $map_fax            = !empty( $map_fax ) ? $map_fax[0] : '';
                        $map_email          = !empty( $map_email ) ? $map_email[0] : get_the_author_meta( 'user_email' , get_current_user_id());
                        $message            = !empty( $message ) ? $message[0] : '';
                        $hidde_contact      = !empty( $hidde_contact ) ? $hidde_contact[0] : '';

                        $type               = 'hidden';

                        if( strlen( options::get_value( 'social' , 'google_map' ) ) ){

                        $lat                = get_post_meta( $post -> ID , 'map_lat');
                        $lng                = get_post_meta( $post -> ID , 'map_lng');
                        $clat               = get_post_meta( $post -> ID , 'map_center_lat');
                        $clng               = get_post_meta( $post -> ID , 'map_center_lng');
                        $zoom               = get_post_meta( $post -> ID , 'map_zoom');


                        $lat                = !empty( $lat ) ? $lat[0] : MAP_LAT;
                        $lng                = !empty( $lng ) ? $lng[0] : MAP_LNG;
                        $clat               = !empty( $clat ) ? $clat[0] : MAP_CLAT;
                        $clng               = !empty( $clng ) ? $clng[0] : MAP_CLNG;
                        $zoom               = !empty( $zoom ) ? $zoom[0] : MAP_ZOOM;

                    ?>
                        <div id="map_meta">
                            <input type="<?php echo $type; ?>" id="map_lat"   value="<?php echo $lat; ?>">
                            <input type="<?php echo $type; ?>" id="map_lng"   value="<?php echo $lng; ?>">
                            <input type="<?php echo $type; ?>" id="map_clat"  value="<?php echo $clat; ?>">
                            <input type="<?php echo $type; ?>" id="map_clng"  value="<?php echo $clng; ?>">
                            <input type="<?php echo $type; ?>" id="map_zoom"  value="<?php echo $zoom; ?>">
                        </div>
                    <?php
                        }else{
                    ?>
                        <div id="map_meta">
                            <input type="<?php echo $type; ?>" id="map_lat"   value="">
                            <input type="<?php echo $type; ?>" id="map_lng"   value="">
                            <input type="<?php echo $type; ?>" id="map_clat"  value="">
                            <input type="<?php echo $type; ?>" id="map_clng"  value="">
                            <input type="<?php echo $type; ?>" id="map_zoom"  value="">
                        </div>
                    <?php
                        }
                    ?>

                    <p>
                        <label for="map_title">
                            <?php _e( 'Title Info' , 'cosmotheme' ); ?><br />
                            <input type="text" id="map_title" onkeyup="javascript:addTitleMap( this.value)" value="<?php echo $map_title; ?>">
                        </label>
                    </p>

                    <p>
                        <label for="map_description_info">
                            <?php _e( 'Description Info ' , 'cosmotheme' ); ?><br />
                            <textarea id="map_description" onkeyup="javascript:addDescriptionMap( this.value )"><?php echo $map_description; ?></textarea>
                        </label>
                    </p>

                    <p>
                        <label for="map_phone1">
                            <?php _e( 'Contact phone 1' , 'cosmotheme' ); ?><br />
                            <input type="text" id="map_phone1" onkeyup="javascript:addPhone1Map( this.value )" value="<?php echo $map_phone1; ?>">
                        </label>
                    </p>
                    <p>
                        <label for="map_phone2">
                            <?php _e( 'Contact phone 2' , 'cosmotheme' ); ?><br />
                            <input type="text" id="map_phone2" onkeyup="javascript:addPhone2Map( this.value )" value="<?php echo $map_phone2; ?>">
                        </label>
                    </p>
                    <p>
                        <label for="map_fax">
                            <?php _e( 'Fax' , 'cosmotheme' ); ?><br />
                            <input type="text" id="map_fax" onkeyup="javascript:addFaxMap( this.value )" value="<?php echo $map_fax; ?>">
                        </label>
                    </p>
                    <p>
                        <label for="map_email">
                            <?php _e( 'Contact email' , 'cosmotheme' ); ?><br />
                            <input id="map_email" type="text" onkeyup="javascript:addEmailMap( this.value )" value="">
                        </label>
                    </p>

                    <p>
                        <label for="message"> <?php _e( 'Aditional Info' , 'cosmotheme' ); ?><br />
                            <textarea id="message" ></textarea>
                        </label>
                    </p>
                    <p>
                        <label for="hidde_contatc">
                            <?php
                                $c = '';
                                if( $hidde_contact == 'yes' ){
                                    $c = 'checked="checked"';
                                }
                            ?>
                            <input type="checkbox" id="hidde_contact" value="yes" <?php echo $c; ?>/>&nbsp;&nbsp<?php _e( 'Hide Contact form' , 'cosmotheme' ); ?>
                        </label>
						
						
                    </p>
					<p>
						<label for="hidde_map">
                            <input type="checkbox" id="hidde_map" value="yes" onclick="if(this.checked){document.getElementById( 'contact_container' ).style.visibility='visible';}else{document.getElementById( 'contact_container' ).style.visibility='hidden';}"/>&nbsp;&nbsp<?php _e( 'Hide Map' , 'cosmotheme' ); ?>
                        </label>
					</p>
                </fieldset>
                <?php if( strlen( options::get_value( 'social' , 'google_map' ) ) ){ ?>
                    <div id="load_map" style="">
                        <?php self::load_map( $id , $lat , $lng , $clat , $clng , $zoom , $map_title , $map_description , $map_phone1 , $map_phone2 , $map_fax , $map_email , $message , $hidde_contact ); ?>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
            </form>
<?php
            exit();
        }
    }
?>