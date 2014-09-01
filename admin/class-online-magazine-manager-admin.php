<?php

class Online_Magazine_Manager_Admin {

    private $version;

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

    function init_rewrite_rules() {
        //temporary
        add_rewrite_rule('rubrics/?$','index.php?post_type=onlimag-article','top');
        add_rewrite_rule('rubrics/page/?([0-9]{1,})/?$','index.php?post_type=onlimag-article&paged=$matches[1]','top');
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
            'rewrite'            => array( 'slug' => 'issues' ),
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
            'rewrite'            => array( 'slug' => 'articles' ),
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
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'rubrics' ),
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

    function add_meta_box_issue_articles() {
        add_meta_box(
            'issue_articles_id',
            __( "Issue's articles", 'onlimag' ),
            array( $this, 'render_meta_box_issue_articles' ),
            'onlimag-issue'
        );
        wp_enqueue_script( 'jquery-ui-sortable' );
    }

    function render_meta_box_issue_articles( $post ) {
        global $ommp;

        wp_nonce_field( 'issue_articles_meta_box', 'issue_articles_meta_box_nonce' );

        $articles = $ommp->get_the_articles( array( 'magazine' => $post->ID) );

        usort( $articles->posts, array($ommp, 'issue_articles_order_compare') );

        echo '<ul id="sortable">';

        while ( $articles->have_posts() ) :

            $articles->the_post();

            global $post;

            echo '<li id="articles_orders_'.$post->ID.'" class="ui-state-default">'.$post->post_title.' - <a class="remove-article-issue" href="#'.$post->ID.'">remove</a></li>';

        endwhile;

        echo '</ul>';

        echo '<hr>';

        $articles_no_issues = $ommp->get_the_articles( array( 'magazine' => -1, 'post_status' => array('publish', 'draft')) );

        echo '<h4>Add article to the issue</h4>';

        echo '<select id="article-items">';

        echo '<option>Seleziona un articolo</option>';

        while ( $articles_no_issues->have_posts() ) :

            $articles_no_issues->the_post();

            global $post;

            echo '<option value="'.$post->ID.'">'.$post->post_title.'</option>';

        endwhile;

        echo '</select>';

        echo '<a id="add-article-issue" class="button button-primary">Add Article</a>';

        echo '<hr>';

        echo '<a id="save-issue-menu" class="button button-primary">Save Issue Menu</a>';

        ?>
        <script>
            jQuery(function() {
                jQuery( "#sortable" ).sortable();
                jQuery( "#sortable" ).disableSelection();

                jQuery( "#save-issue-menu").click( function() {

                    var data = 'action=update_issue_articles_orders&post_ID=' + jQuery('#post_ID').val() + '&' + jQuery( "#sortable" ).sortable( 'serialize' );

                    jQuery.post(ajaxurl, data, function(response) {
                        console.log( 'Got this from the server: ' + response );
                    });
                });

                jQuery( "#add-article-issue").click( function() {

                    var data = 'action=add_article_issue'
                        + '&post_ID=' + jQuery('#post_ID').val()
                        + '&article_order=' + ( jQuery( "#sortable li").length + 1 )
                        + '&new_article_ID=' + jQuery( "#article-items option:selected" ).val()
                        + '&new_article_title=' + jQuery( "#article-items option:selected" ).text();

                    jQuery.post(ajaxurl, data, function(response) {

                        if(response.status == 1){

                            new_article = '<li id="articles_orders_' + response.data.id + '" class="ui-state-default">' + response.data.title + '</li>';

                            jQuery("#sortable").append(new_article);
                            jQuery("#sortable").sortable('refresh');
                        }
                    },'json');
                });

                jQuery( ".remove-article-issue").click( function() {

                    var data = 'action=remove_article_issue'
                        + '&post_ID=' + jQuery('#post_ID').val()
                        + '&article_ID=' + jQuery( this ).attr('href').slice(1)

                    jQuery.post(ajaxurl, data, function(response) {

                        if(response.status == 1){
                            jQuery( '#articles_orders_' + response.data.article_ID).slideUp( 'normal', function() { $(this).remove(); } );
                        }
                    },'json');
                });

            });



        </script>
<?php

    }

    function update_ajax_issue_articles_orders() {
        global $table_prefix, $wpdb; // this is how you get access to the database

        $order = 0;

        foreach( $_POST['articles_orders'] as $article_ID ) {
            $order++;
            $wpdb->replace(
                $table_prefix . 'onlimag_magazine',
                array(
                    'magazine_id' => $_POST['post_ID'],
                    'article_id' => $article_ID,
                    'order' => $order,
                ),
                array(
                    '%d',
                    '%d',
                    '%d',
                )
            );
        }

        echo 1;

        die();

    }

    function add_ajax_article_issue() {
        global $table_prefix, $wpdb; // this is how you get access to the database

        $wpdb->replace(
            $table_prefix . 'onlimag_magazine',
            array(
                'magazine_id' => $_POST['post_ID'],
                'article_id' => $_POST['new_article_ID'],
                'order' => $_POST['article_order'],
            ),
            array(
                '%d',
                '%d',
                '%d',
            )
        );

        $res = array(
            'status' => 1,
            'data' => array(
                'id' => $_POST['new_article_ID'],
                'title' => $_POST['new_article_title'],
            ),
        );

        echo json_encode( $res );

        die();

    }

    function remove_ajax_article_issue() {
        global $table_prefix, $wpdb; // this is how you get access to the database

        $wpdb->delete(
            $table_prefix . 'onlimag_magazine',
            array(
                'magazine_id' => $_POST['post_ID'],
                'article_id' => $_POST['article_ID'],
            ),
            array(
                '%d',
                '%d',
            )
        );

        $res = array(
            'status' => 1,
            'data' => array(
                'post_ID' => $_POST['post_ID'],
                'article_ID' => $_POST['article_ID'],
            ),
        );

        echo json_encode( $res );

        die();

    }
}