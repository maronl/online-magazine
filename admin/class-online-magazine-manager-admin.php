<?php

class Online_Magazine_Manager_Admin {

    private $version;

    private $options;

    function __construct($version)
    {
        $this->version = $version;
    }

    function register_admin_menu(){
        add_menu_page( __( 'Online Magazine', 'online-magazine' ), __( 'Magazine', 'online-magazine' ), 'edit_others_posts', 'online-magazine', array( $this, 'render_magazine_overview_page'), '', 21 );
        add_submenu_page( 'online-magazine', __( 'Issues', 'online-magazine' ), __( 'Issues', 'online-magazine' ), 'edit_posts', 'edit.php?post_type=onlimag-issue', '' );
        add_submenu_page( 'online-magazine', __( 'Articles', 'online-magazine' ), __( 'Articles', 'online-magazine' ), 'edit_others_posts', 'edit.php?post_type=onlimag-article', '' );
        add_submenu_page( 'online-magazine', __( 'Rubrics', 'online-magazine' ), __( 'Rubrics', 'online-magazine' ), 'edit_posts', 'edit-tags.php?taxonomy=onlimag-rubric&post_type=onlimag-article', '' );
    }


    function render_magazine_overview_page() {
        require_once plugin_dir_path( __FILE__ ) . 'partials/online-magazine-overview.php';
    }

    function register_issue_post_type() {

        $labels = array(
            'name'               => __( 'Issues', 'online-magazine' ),
            'singular_name'      => __( 'Issue', 'online-magazine' ),
            'menu_name'          => __( 'Issues', 'admin menu', 'online-magazine' ),
            'name_admin_bar'     => __( 'Issue', 'add new on admin bar', 'online-magazine' ),
            'add_new'            => __( 'Add New Issue', 'online-magazine' ),
            'add_new_item'       => __( 'Add New Issue', 'online-magazine' ),
            'new_item'           => __( 'New Issue', 'online-magazine' ),
            'edit_item'          => __( 'Edit Issue', 'online-magazine' ),
            'view_item'          => __( 'View Issue', 'online-magazine' ),
            'all_items'          => __( 'All Issues', 'online-magazine' ),
            'search_items'       => __( 'Search Issues', 'online-magazine' ),
            'parent_item_colon'  => __( 'Parent Issues:', 'online-magazine' ),
            'not_found'          => __( 'No issues found.', 'online-magazine' ),
            'not_found_in_trash' => __( 'No issues found in Trash.', 'online-magazine' )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'issue' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'map_meta_cap'       => true,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' )
        );

        register_post_type( 'onlimag-issue', $args );
    }

    function register_issue_article_post_type() {

        $labels = array(
            'name'               => __( 'Articles', 'online-magazine' ),
            'singular_name'      => __( 'Article', 'online-magazine' ),
            'menu_name'          => __( 'Articles', 'admin menu', 'online-magazine' ),
            'name_admin_bar'     => __( 'Article', 'add new on admin bar', 'online-magazine' ),
            'add_new'            => __( 'Add New Article', 'online-magazine' ),
            'add_new_item'       => __( 'Add New Article', 'online-magazine' ),
            'new_item'           => __( 'New Article', 'online-magazine' ),
            'edit_item'          => __( 'Edit Article', 'online-magazine' ),
            'view_item'          => __( 'View Article', 'online-magazine' ),
            'all_items'          => __( 'All Articles', 'online-magazine' ),
            'search_items'       => __( 'Search Articles', 'online-magazine' ),
            'parent_item_colon'  => __( 'Parent Articles:', 'online-magazine' ),
            'not_found'          => __( 'No articles found.', 'online-magazine' ),
            'not_found_in_trash' => __( 'No articles found in Trash.', 'online-magazine' )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'article' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'map_meta_cap'       => true,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
        );

        register_post_type( 'onlimag-article', $args );

        $article_issue_category_labels = array(
            'name' => __( 'Rubrics', 'online-magazine' ),
            'singular_name' => __( 'Rubric', 'online-magazine' ),
            'search_items' =>  __( 'Search Rubric', 'online-magazine' ),
            'all_items' => __( 'All Rubrics', 'online-magazine' ),
            'parent_item' => __( 'Parent Rubric', 'online-magazine' ),
            'parent_item_colon' => __( 'Parent Rubric', 'online-magazine' ),
            'edit_item' => __( 'Edit Rubric', 'online-magazine' ),
            'update_item' => __( 'Update Rubric', 'online-magazine' ),
            'add_new_item' => __( 'Add New Rubric', 'online-magazine' ),
            'new_item_name' => __( 'New Rubric', 'online-magazine' ),
            'menu_name' => __( 'Rubric', 'online-magazine' ),
        );

        $article_issue_category_args = array(
            'hierarchical' => true,
            'labels' => $article_issue_category_labels,
            'show_ui' => false,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'article-rubric' ),
            'show_in_nav_menus' => true,
        );

        register_taxonomy('onlimag-rubric', array('onlimag-article'), $article_issue_category_args);

        $default_article_issue_category_cats = array('ambiente', 'arte', 'filosofia', 'storia', 'sport');

        foreach($default_article_issue_category_cats as $cat){

            if(!term_exists($cat, 'onlimag-rubric')) wp_insert_term($cat, 'onlimag-rubric');

        }
        
    }

    function taxonomy_submenu_correction( $parent_file ) {
        global $current_screen;
        $taxonomy = $current_screen->taxonomy;
        if ($taxonomy == 'onlimag-rubric'){
            $parent_file = 'online-magazine';
            global $submenu_file;
            $submenu_file = 'edit-tags.php?taxonomy=onlimag-rubric&post_type=onlimag-article';
        }
        return $parent_file;

    }

}