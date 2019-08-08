<?php
/**
 * Plugin Name: WPSpark Lite
 * Plugin URI: https://wpspark.io/
 * Author: WPSpark
 * Author URI: https://wpspark.io/
 * Description: Provide simplified REST API for WPSpark Themes
 * Version:1.0
 * License: GPLv2 or Later
 * Text Domain: spark-lite
 *  */

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
    die;
}

/**
 * define the core root file
 */
define('WPSPARK_CORE_ROOT', untrailingslashit(plugin_dir_path(__FILE__)));



/**
 * require all files from inc directory
 */
$files = glob(WPSPARK_CORE_ROOT. '/inc/*.php');
foreach($files as $file){
    require $file;
}

function wpspark_core_load(){
    WPSpark_Sitedata::init();
    WPSpark_Media::init();
}
add_action('plugins_loaded', 'wpspark_core_load');

/**
 * Flush rewrite rules on
 * plugin activation/deactivation
 */
function wpspark_core_flush()
{
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'wpspark_core_flush');
register_deactivation_hook(__FILE__, 'wpspark_core_flush');


add_action(
    'rest_api_init',
    function () {
        if (!function_exists('use_block_editor_for_post_type')) {
            require ABSPATH . 'wp-admin/includes/post.php';
        }
        // Surface all Gutenberg blocks in the WordPress REST API
        $post_types = get_post_types_by_support(['editor']);
        foreach ($post_types as $post_type) {
            if (use_block_editor_for_post_type($post_type)) {
                register_rest_field(
                    $post_type,
                    'blocks',
                    [
                        'get_callback' => function (array $post) {
                            return parse_blocks($post['content']['raw']);
                        },
                    ]
        );
            }
        }
    }
);
