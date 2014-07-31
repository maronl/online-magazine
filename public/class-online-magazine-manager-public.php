<?php

class Online_Magazine_Manager_Public {

    private $version;

    function __construct($version) {
        $this->version = $version;
    }

    static function get_the_rubrics( $args = array() ) {
        $taxonomies = array(
            'onlimag-rubric',
        );

        if( empty( $args ) ) {
            $args = array(
                'orderby'           => 'name',
                'order'             => 'ASC',
                'hide_empty'        => false,
            );
        }

        $rubrics = get_terms( $taxonomies, $args );

        return $rubrics;

    }

    static function the_rubrics( $args ) {

        $title_format = '<h4>%s</h4>';
        $container_format = '<ol class="list-unstyled">%s</ol>';
        $item_format = '<li><a href="%s">%s</a></li>';

        $title_format .= '%s';
        $container_format .= '%s';
        $item_format .= '%s';

        $rubrics = $this->get_the_rubrics();
        if( ! empty( $rubrics ) ) {
            printf( $title_format, __( 'Rubrics', 'onlimag' ), PHP_EOL );
            $items_output = '';
            foreach ( $rubrics as $rubric ) {
                $items_output .= printf( $item_format, $rubric->slug, $rubric->name, PHP_EOL );
            }
            printf( $container_format, $items_output, PHP_EOL );
        }
    }
}