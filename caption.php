<?php
    global $post;
    $args = array(
        'numberposts' => -1,
        'post_type' => 'attachment',
        'status' => 'publish',
        'post_mime_type' => 'image',
        'post_parent' => $post -> ID
    );

    $images = &get_children( $args );

    if( isset( $images[ get_post_thumbnail_id( $post -> ID ) ] ) ){
        $description = $images[ get_post_thumbnail_id( $post -> ID ) ] -> post_excerpt;
    }else{
        $args = array(
            'numberposts' => -1,
            'post_type' => 'attachment',
            'status' => 'publish',
            'post_mime_type' => 'image',
            'post_parent' => 0
        );

        $images = &get_children($args);

        if( isset( $images[  get_post_thumbnail_id( $post -> ID ) ] ) ){
            $description = $images[ get_post_thumbnail_id( $post -> ID ) ] -> post_excerpt;
        }else{
            $description = '';
        }
    }

    if( strlen( $description ) ){
        echo $description;
    }
?>