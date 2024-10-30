<?php
/*
 * Plugin Name: Image Modal
 * Description: Allows users to click on any image within post or page content and enlarge them in a modal.
 * Version: 1.0
 * Author: GPTMade
 * Author URI:        https://gptmade.com
 * License:           GPL v2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
*/

/*
Image Modal is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Image Modal is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Image Modal. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

function add_image_modal( $content ) {
    $post_id = get_the_ID();
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($content);
    libxml_clear_errors();
    $imgs = $dom->getElementsByTagName('img');
    foreach ($imgs as $img) {
        $imgUrl = $img->getattribute('src');
        $imgUrl = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $imgUrl);
        $imgId = attachment_url_to_postid( $imgUrl );
        if( !empty($imgId) ) {
            $largeImgUrl = wp_get_attachment_image_src( $imgId, 'full' );
            $img->setattribute( 'data-large_image', $largeImgUrl[0] );
        } else {
            $attachments = get_attached_media( 'image', $post_id );
            foreach ($attachments as $attachment) {
                $attachment_image_url = wp_get_attachment_url($attachment->ID);
                $attachment_image_url = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_image_url);
                if( $attachment_image_url == $imgUrl ) {
                    $img->setattribute( 'data-large_image', $attachment->guid );
                }
            }
        }
    }



    $content = $dom->saveHTML();
    return $content;
}


add_filter( 'the_content', 'add_image_modal' );

function activate_image_modal_plugin() {
    add_filter('the_content', 'add_image_modal');
}
register_activation_hook( __FILE__, 'activate_image_modal_plugin' );


function image_modal_enqueue_scripts() {
    wp_enqueue_script( 'image-modal', plugin_dir_url( __FILE__ ) . 'image-modal.js', array(), '1.0', true );
    wp_enqueue_style( 'image-modal-css', plugin_dir_url( __FILE__ ) . 'image-modal.css', array(), '1.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'image_modal_enqueue_scripts' );
?>
