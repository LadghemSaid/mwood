<?php
namespace ElementsKit;

class Elementskit_Widget_Lottie_Handler extends Core\Handler_Widget {

    public function wp_init(){
        include self::get_dir() . 'json-handler.php';
    }

    static function get_name() {
        return 'elementskit-lottie';
    }

    static function get_title() {
        return esc_html__( 'Lottie', 'elementskit' );
    }

    static function get_icon() {
        return 'eicon-animation ekit-widget-icon';
    }

    static function get_categories() {
        return [ 'elementskit' ];
    }

    static function get_dir() {
        return \ElementsKit::widget_dir() . 'lottie/';
    }

    static function get_url() {
        return \ElementsKit::widget_url() . 'lottie/';
    }
}
