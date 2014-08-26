online-magazine
===============

The Online Magazine is a WordPress plugin that enable WordPress within the elements necessary to manage efficiently an online magazine. online magazine is composed by issues delivered periodically. Each issue contains article grouped by category/rubric

theme function
===============

the plugin initiate a global variable $ommp that can be used to use the utilities developed and exposed by the plugin into the theme you are developing

*sample usage:*
$global $ommp;
$ommp->get_the_rubrics();

**get_selected_rubrics()**

return the rubric selected by user. return an empty string if not rubric is selecet. it is a wrapper for get_query_var('rubrics')

**get_selected_magazine()**

return the magazine id selected by user. return an empty string if not rubric is selecet. it is a wrapper for get_query_var('magazine')

**get_the_rubrics()**

return the rubrics set in the system as an Array of term objects. see also wordpress function get_terms return value for more details of returning array

to change the number of rubrics in the result use
args = array(
    'number' => 5
)

**the_rubrics_widget( args )**

show the rubrics widget a list of all (or part) of the rubrics in the system so that user can select to view article of a single rubric.
format can be defined by user with the following parameters

args = array(
    'title' => 'Rubrics';
    'read_all_text' => 'Read all';
    'title_format' => '<h4>%s</h4>';
    'container_format' => '<ol class="list-unstyled">%s</ol>';
    'item_format' => '<li><a href="/rubrics/%s">%s</a></li>';
    'read_all_format' => '<a href="/rubrics">%s</a>';
    'item_number' => 5;
)


**get_the_issues( args)**

return the issues published as a WP_Query object. See also WordPress function WP_Query object to see how to use the result

to change the number of issues in the result use
args = array(
    'post_per_page' => 5
)

**the_issues_widget( args )**

show the rubrics widget a list of all (or part) of the rubrics in the system so that user can select to view article of a single rubric.
format can be defined by user with the following parameters

args = array(
    'title' => 'Issues',
    'read_all_text' => 'Read all',
    'title_format' => '<h4>%s</h4>',
    'container_format' => '<ol class="list-unstyled">%s</ol>',
    'item_format' => '<li><a href="/issues/%s">%s</a></li>',
    'read_all_format' => '<a href="/issues">%s</a>',
    'item_number' => 5,
)


**get_the_articles( args )**

Return articles published in the system. user can filter article by magazine, rubric and use pagination.
See also WordPress function WP_Query object to see how to use the result
function return WP_Query object.

*parameters available*

args = array(
    'magazine' => 58,
    'rubrics' => array('sport'),
    'paged' => 2,
    'post_per_page' => 5,
)

*filter article by issue (using issue ID - post_ID)*

args = array(
    'magazine' => 58,
)

*filter article by "sport" rubric*

args = array(
    'rubrics' => array('sport'),
)

*filter article by "sport" rubric, second page of results*

args = array(
    'rubrics' => array('sport'),
    'paged' => 2,
)
