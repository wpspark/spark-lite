<?php
class WPSpark_Sitedata{

    private static $instance;


    public static function init(){
        if(null === self::$instance ){
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct(){
        add_action( 'rest_api_init', array($this, 'wpspark_site_data_route') );
    }

    /**
     * Register your routes here
     * inside this functions
     */
    public function wpspark_site_data_route(){
        /**
         * return site favicon and logo
         * while you head this endpoint
         */
        register_rest_route('spark', '/sitedata', array(
            'methods' => 'get',
            'callback' => array($this, 'wpspark_pull_site_meta_data')
        ));
        
    }

    public function wpspark_pull_site_meta_data(){

        $logo_url = $this->custom_logo_url(get_custom_logo());
        
        $site_data = [
            'favicon' => get_site_icon_url(),
            'logo' => $logo_url,
        ];
        return new WP_REST_Response($site_data, 200);

    }

    public function custom_logo_url ( $html ) {

        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $html = wp_get_attachment_url($custom_logo_id);
        return $html;    
    }

    
    

/**
 * below brace is the end of this class
 */
}

?>