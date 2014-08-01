<?php

class Online_Magazine_Manager_Public {

    private $version;

    // this variable is used only internally in where filter to select article by magazine id
    // it is an ugly solution but not found a better way
    // don't consider it as an important element of the class just a service variable
    private $selectedMagazine;

    function __construct($version) {
        $this->version = $version;
    }

    public function register_query_vars( $qvars ) {
        $qvars[] = 'rubrics';
        $qvars[] = 'magazine';
        return $qvars;
    }

    public function get_selected_rubrics() {
        return get_query_var('rubrics');
    }

    public function get_selected_magazine() {
        return get_query_var('magazine');
    }

    public function get_the_rubrics( $args = array() ) {
        $taxonomies = array(
            'onlimag-rubric',
        );

        if (empty($args)) {
            $args = array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
            );
        }

        $rubrics = get_terms($taxonomies, $args);

        return $rubrics;

    }

    public function the_rubrics($args = array()) {

        $title = __('Rubrics', 'onlimag');
        $title_format = '<h4>%s</h4>';
        $container_format = '<ol class="list-unstyled">%s</ol>';
        $item_format = '<li><a href="%s">%s</a></li>';

        extract($args);

        $title_format .= '%s';
        $container_format .= '%s';
        $item_format .= '%s';

        $rubrics = $this->get_the_rubrics();
        if (!empty($rubrics)) {
            printf($title_format, $title, PHP_EOL);
            $items_output = '';
            foreach ($rubrics as $rubric) {
                $items_output .= sprintf($item_format, $rubric->slug, $rubric->name, PHP_EOL);
            }
            printf($container_format, $items_output, PHP_EOL);
        }
    }

    public function get_the_articles($args = array()) {
        $args_articoli = array(
            'post_type' => 'onlimag-article',
            'post_status' => 'published',
        );

        extract($args);

        if (isset($rubrics) && ! empty( $rubrics ) ) {
            $args_tax_query = array(
                array(
                    'taxonomy' => 'onlimag-rubric',
                    'field' => 'slug',
                    'terms' => $rubrics,
                )
            );
            $args_articoli['tax_query'] = $args_tax_query;
        }

        if (isset($paged)) {
            $args_articoli['paged'] = $paged;
        }

        add_filter('posts_join', array( $this, 'posts_join_filter_for_articles' ) );
        add_filter('posts_fields', array( $this, 'posts_fields_filter_for_articles' ) );
        add_filter('posts_groupby', array( $this, 'posts_groupby_filter_for_articles' ) );
        if ( isset( $magazine ) && is_int( (int) $magazine ) ) {
            $this->selectedMagazine = $magazine;
            add_filter('posts_where', array( $this, 'posts_where_filter_for_magazine' ) );
            add_filter('posts_join', array( $this, 'posts_join_filter_for_magazine' ) );
        }

        $articles = new WP_Query($args_articoli);

        remove_filter('posts_join', array( $this, 'posts_join_filter_for_articles' ) );
        remove_filter('posts_fields', array( $this, 'posts_fields_filter_for_articles' ) );
        remove_filter('posts_groupby', array( $this, 'posts_groupby_filter_for_articles' ) );
        if ( isset( $magazine ) && is_int( (int) $magazine ) ) {
            $this->selectedMagazine = null;
            remove_filter('posts_where', array( $this, 'posts_where_filter_for_magazine' ) );
            remove_filter('posts_join', array( $this, 'posts_join_filter_for_magazine' ) );
        }

        return $articles;
    }

    public function posts_join_filter_for_articles( $join ) {
        global $wpdb;
        $join .=
            "
              INNER JOIN $wpdb->term_relationships as term_relationships_others
                ON ($wpdb->posts.ID = term_relationships_others.object_id)
              INNER JOIN $wpdb->term_taxonomy
                ON (term_relationships_others.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
              INNER JOIN $wpdb->terms
                ON ($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
            ";
        return $join;
    }

    public function posts_join_filter_for_magazine( $join ) {
        global $table_prefix, $wpdb;
        $current_blog_id = get_current_blog_id();
        $join .=
            "
              INNER JOIN " . $table_prefix . "onlimag_magazine
                ON (" . $table_prefix . "onlimag_magazine.article_id = $wpdb->posts.ID)
            ";
        return $join;
    }

    public function posts_fields_filter_for_articles( $fields ) {
        global $wpdb;
        $fields .= ",  GROUP_CONCAT($wpdb->terms.name SEPARATOR ', ') as rubrics";
        return ($fields);
    }

    public function posts_groupby_filter_for_articles( $groupby ) {
        global $wpdb;
        $groupby = "{$wpdb->posts}.ID";
        return $groupby;
    }

    public function posts_where_filter_for_magazine( $where ) {
        global $table_prefix;
        $where .= " AND " . $table_prefix . "onlimag_magazine.magazine_id = " . $this->selectedMagazine;
        return $where;

    }

}