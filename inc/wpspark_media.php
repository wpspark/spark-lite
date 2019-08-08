<?php 
class WPSpark_Media{
    private static $instance;

    public static function init(){
        if(null === self::$instance ){
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct(){
        add_action( 'rest_api_init', array($this, 'wpspark_rest_fields') );
    }
    /**
     * create spark media field in wp rest api
     */
    public function wpspark_rest_fields(){
        register_rest_field( 'post',  'spark_media', array(
            'get_callback'    => array($this, 'wpspark_add_spark_media'), 
            'update_callback' => null,
            'schema'          => null,
        ));
        register_rest_field( 'post',  'spark_user', array(
            'get_callback'    => array($this, 'wpspark_add_spark_user'), 
            'update_callback' => null,
            'schema'          => null,
        ));
    }
    public function wpspark_add_spark_media($object, $field_name, $request){
        $featured_img_array = wp_get_attachment_image_src(
            $object['featured_media'], 
            'full',  
            false
        );
        
        if($featured_img_array){
            return $featured_img_array[0];
        }else{
            return null;
        }
    }
    public function wpspark_add_spark_user($object, $field_name, $request){
        $user_data = get_the_author_meta( 'nicename', $object['author'] );
        $user_name = get_the_author_meta( 'nicename', $object['author'] );
        $user_slug = get_the_author_meta( 'nicename', $object['author'] );
        $user_avatar_url = get_avatar_url( $object['author'] );
        $user_avatar = (object) array('wordpress_96' => $user_avatar_url);
        $user_all = (object) array('avatar_urls' => $user_avatar)   ;
        $all_data = (object) array('name' => $user_name, 'slug' => $user_name, 'avatar_urls' => $user_avatar);
        if($user_data){
            return $all_data;
        }else{
            return null;
        }
    }
}
