<?php

function fct1_image_print( $img_id_src, $size = '', $crop = false, $alt = '', $itemprop = '' ) {
    $img = fct1_image_src( $img_id_src, $size, $crop );
    if ( !$img ) { return; }
    
    ?><img src="<?php echo $img[0] ?>" width="<?php echo $img[1] ?>" height="<?php echo $img[2] ?>" alt="<?php echo $alt ?>" loading="lazy" <?php echo $itemprop ? 'itemprop="'.$itemprop.'" ' : '' ?>/><?php
}

function fct1_image( $img_id_src, $size = '', $crop = false, $alt = '', $itemprop = '' ) {
    ob_start();
    fct1_image_print( $img_id_src, $size, $crop, $alt, $itemprop );
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

function fct1_image_src( $img_id_src, $size = '', $crop = false ) { // src starts from after ..uploads/

    if ( is_numeric( $img_id_src ) ) {
        $img_id_src = explode( '/wp-content/uploads/', wp_get_attachment_image_src( $img_id_src, 'full' )[0] )[1];
    }

    if ( is_string( $img_id_src ) ) {

        $return = function($src, $src_size = []) use (&$path, &$url) {
            
            if ( $new_size = !empty( $src_size ) ? $src_size : getimagesize( $path . $src ) ) {
                return [
                    $url . $src,
                    $new_size[0],
                    $new_size[1]
                ];
            }
            return [ $url . $src ];
        };

        list( 'path' => $path, 'url' => $url ) = wp_get_upload_dir();
        $src = '/' . trim( $img_id_src, '/' );


        if ( !is_file( $path . $src ) ) { return; } // no source file
        // can unlink the thumbnails if no source file, or keep it separately
        $size = $size ? $size : 'full';
        if ( !is_array( $size ) || !is_int( $size[0] ) || !is_int( $size[1] ) ) { return $return( $src ); }

        if ( $src_size = getimagesize( $path . $src ) ) { // make only smaller variants
            if ( $src_size[0] <= $size[0] && $src_size[1] <= $size[1] ) { return $return( $src ); }
        }
        unset( $src_size );
        
        $path_split = pathinfo( $src );
        $desired_src =  $path_split['dirname'] .
                        ( $path_split['dirname'] == '/' ? '' : '/' ) .
                        $path_split['filename'] .
                        '-' . $size[0] . 'x' . $size[1] . 'c' . // c is for custom - to clear the files when needed
                        '.' . $path_split['extension']
                    ;

        if ( is_file( $path . $desired_src ) ) {
            return $return( $desired_src );
        }

        // create new image
        $edit_img = wp_get_image_editor( $path . $src );
        if ( is_wp_error( $edit_img ) ) { return $return( $src ); }

        $edit_img->resize( $size[0], $size[1], $crop );
        $edit_img->save( $path . $desired_src );

        return $return( $desired_src );
        
    }

}