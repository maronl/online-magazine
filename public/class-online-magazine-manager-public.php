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

    public function fix_archive_query_with_rubrics_filter( $query ) {
        if( ! $query->is_main_query() ) {
            $this->remove_filters_for_articles_query();
            return $query;
        }

        if (
        ( is_tax() && $query->is_main_query() && isset( $query->query_vars['onlimag-rubric'] ) )
        ||
        ( is_archive() && $query->is_main_query() && $query->query_vars['post_type'] == 'onlimag-article' )
        ) {
            $this->add_filters_for_articles_query();
        }
        if  ( is_single() && $query->is_main_query() && $query->query_vars['post_type'] == 'onlimag-article' ) {
            $this->add_filters_for_articles_query();
        }
        return $query;
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

        if ( ! isset( $args['orderby'] ) ) {
            $args['orderby'] = 'name';
        }

        if ( ! isset( $args['order'] ) ) {
            $args['order'] = 'ASC';
        }

        if ( ! isset( $args['hide_empty'] ) ) {
            $args['hide_empty'] = false;
        }

        $rubrics = get_terms($taxonomies, $args);

        return $rubrics;

    }

    public function the_rubrics_widget($args = array()) {

        $title = __('Rubrics', 'onlimag');
        $read_all_text = __('Read all', 'onlimag');
        $title_format = '<h4>%s</h4>';
        $container_format = '<ul class="list-unstyled">%s</ul>';
        $item_format = '<li><a href="/rubrics/%s">%s</a></li>';
        $read_all_format = '<a href="/rubrics">%s</a>';
        $item_number = 5;

        // allow user set custom title, read_all_text, title_format ....
        extract($args);

        $title_format .= '%s';
        $container_format .= '%s';
        $item_format .= '%s';
        $read_all_format .= '%s';

        $rubrics = $this->get_the_rubrics(array('number' => $item_number));
        if (!empty($rubrics)) {
            if( !empty($title) )
                printf($title_format, $title, PHP_EOL);
            $items_output = '';
            foreach ($rubrics as $rubric) {
                $items_output .= sprintf($item_format, $rubric->slug, $rubric->name, PHP_EOL);
            }
            printf($container_format, $items_output, PHP_EOL);
            if( !empty($read_all_text) )
                printf($read_all_format, $read_all_text, PHP_EOL);
        }
    }

    public function get_the_issue( $issue_id = null ) {

        $args['post_status'] = 'publish';
        $args['post_type'] = 'onlimag-issue';
        $args['p'] = $issue_id;

        $issue = new WP_Query($args);

        return $issue->post;

    }

    public function get_the_published_issues( $args = array() ) {

        if ( ! isset( $args['orderby'] ) ) {
            $args['orderby'] = 'date';
        }

        if ( ! isset( $args['order'] ) ) {
            $args['order'] = 'DESC';
        }

        if ( ! isset( $args['posts_per_page'] ) ) {
            $args['posts_per_page'] = 5;
        }

        $args['post_status'] = 'publish';
        $args['post_type'] = 'onlimag-issue';

        $issues = new WP_Query($args);

        return $issues;

    }

    public function get_the_next_issue() {

        $args = array();
        $args['orderby'] = 'date';
        $args['order'] = 'ASC';
        $args['posts_per_page'] = 1;
        $args['post_status'] = 'future';
        $args['post_type'] = 'onlimag-issue';

        $issues = new WP_Query($args);

        return $issues->post;

    }

    public function the_issues_widget($args = array()) {

        $title = __('Issues', 'onlimag');
        $read_all_text = __('Read all', 'onlimag');
        $title_format = '<h4>%s</h4>';
        $container_format = '<ul class="list-unstyled">%s</ul>';
        $item_format = '<li><a href="/issues/%s">%s</a></li>';
        $read_all_format = '<a href="/issues">%s</a>';
        $item_number = 5;

        // allow user set custom title, read_all_text, title_format ....
        extract($args);

        $title_format .= '%s';
        $container_format .= '%s';
        $item_format .= '%s';
        $read_all_format .= '%s';

        $issues = $this->get_the_published_issues(array('posts_per_page' => $item_number));
        $issues = $issues->posts;
        if (!empty($issues)) {
            if( !empty($title) )
                printf($title_format, $title, PHP_EOL);
            $items_output = '';
            foreach ($issues as $issue) {
                $items_output .= sprintf($item_format, $issue->post_name, $issue->post_title, PHP_EOL);
            }
            printf($container_format, $items_output, PHP_EOL);
            if( !empty($read_all_text) )
                printf($read_all_format, $read_all_text, PHP_EOL);
        }
    }

    /*
     * function useful to compare order of articles when they are assigned to an issue
     * this function is valid only when you compare article inside an issue
     */
    function issue_articles_order_compare($a, $b){
        return $a->article_order - $b->article_order;
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
              LEFT JOIN " . $table_prefix . "related_posts
                ON (" . $table_prefix . "related_posts.related_post_id = $wpdb->posts.ID)
              LEFT JOIN " . $table_prefix . "posts as related_post_details
                ON (" . $table_prefix . "related_posts.linking_post_id = related_post_details.ID)
            ";
        return $join;
    }

    public function posts_fields_filter_for_articles( $fields ) {
        global $table_prefix, $wpdb;
        $fields .= ", related_post_details.ID as issue_ID, related_post_details.post_title as issue_title, related_post_details.post_name as issue_slug, GROUP_CONCAT($wpdb->terms.name SEPARATOR ', ') as rubrics";
        return ($fields);
    }

    public function posts_groupby_filter_for_articles( $groupby ) {
        global $wpdb;
        $groupby = "{$wpdb->posts}.ID";
        return $groupby;
    }

    public function add_main_filters_for_articles_query() {
        add_filter('posts_join', array( $this, 'posts_join_filter_for_articles' ) );
        add_filter('posts_fields', array( $this, 'posts_fields_filter_for_articles' ) );
        add_filter('posts_groupby', array( $this, 'posts_groupby_filter_for_articles' ) );
        add_filter('posts_join', array( $this, 'posts_join_filter_for_magazine' ) );
    }

    public function add_filters_for_articles_query( $magazine_id = null ) {
        $this->add_main_filters_for_articles_query();
    }

    public function remove_filters_for_articles_query( $magazine_id = null ) {
        remove_filter('posts_join', array( $this, 'posts_join_filter_for_articles' ) );
        remove_filter('posts_fields', array( $this, 'posts_fields_filter_for_articles' ) );
        remove_filter('posts_groupby', array( $this, 'posts_groupby_filter_for_articles' ) );
        remove_filter('posts_join', array( $this, 'posts_join_filter_for_magazine' ) );
    }

    public function posts_join_filter_for_rubrics_related_post( $join ) {
        global $table_prefix, $wpdb;
        $join .=
            "
              INNER JOIN $wpdb->term_relationships as term_relationships_others
                ON (" . $table_prefix . "related_posts.related_post_id = term_relationships_others.object_id)
              INNER JOIN $wpdb->term_taxonomy
                ON (term_relationships_others.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
              INNER JOIN $wpdb->terms
                ON ($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
            ";
        return $join;
    }

    public function posts_fields_filter_for_rubrics_related_posts( $fields ) {
        global $table_prefix, $wpdb;
        $fields .= ", GROUP_CONCAT($wpdb->terms.name SEPARATOR ', ') as rubrics";
        return ($fields);
    }

    public function posts_groupby_filter_for_rubrics_related_posts( $groupby ) {
        global $table_prefix, $wpdb;
        $groupby =  $table_prefix . "related_posts.ID";
        return $groupby;
    }

}