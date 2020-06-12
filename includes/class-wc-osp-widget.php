<?php

class OSP_WIDGET extends WP_Widget {

    function __construct() {

        $params = array(
            'description' => ' Test Country / Currency ',
            'name' => 'Openair Sport'
        );
        parent::__construct('osp_widget', '', $params);
    }

    function form($instance) {

        extract($instance);
    }

    function widget($args, $instance) {
        echo ' Country / Currency ';
    }


}

add_action('widgets_init', 'register_osp_widget');

function register_osp_widget() {
    register_widget('OSP_WIDGET');
}

