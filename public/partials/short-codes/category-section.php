<?php

/**
 * Comments here
 *
 */

use LightnCandy\LightnCandy;

// first, normalize our shortcode attributes, providing defaults for anything missing
$a = shortcode_atts( array(
	'category_name' => '',
	'order_by' => 'title'),
	 $atts
);

// create an array of query parameters
$args = array( 'category_name' => $a['category_name'] );

// sort posts based on the 'order_by' attribute. The 'shortcode_atts()' method above will sort by title
// if no 'order_by' attribute is present, so we can rely on there being something there
switch ( $a['order_by'] ) {
	case 'date':
		$args['orderby'] = 'date';
		$args['order'] = 'ASC';
		break;
	case 'title':
		// sorting by title is the default. Falling through.
	default:
		$args['orderby'] = 'title';
		$args['order'] = 'ASC';
		break;
}


// create a new query to get posts in our category, sorted the way we want
$category_posts = new WP_Query( $args );

// the famous WordPress Loop!

if( $category_posts->have_posts() ) {

		/**
		 * Before we actually begin looping, we'll grab our template, compile it, and the prep it for
		 * using our data. That way we don't incur this overhead on every post in a category
		 *
		 * Note: to avoid having LightnCandy translate HTML tags into entities, we are passing the
		 * NOESCAPE flag when we compile the template.
		 */
		$template = $this->templateManager->getTemplate( 'one-post' );
		$compiled = LightnCandy::compile( $template, array(
			'flags' => LightnCandy::FLAG_NOESCAPE
		));
		$renderer = LightnCandy::prepare( $compiled );


		while( $category_posts->have_posts() ) {
			/**
			 * Now we're actually looping through posts. For each post, make a data array from the
			 * items we want to display.
			 *
			 * Note: in order to supress actual output, we are using some alternate 'get_' methods
			 * here. The 'get_the_content()' method may be troublesome, as it DOES NOT apply any
			 * WordPress filters to the content. If you're not seeing expected behavior in post
			 * content, this may be why.
			 */
    		$category_posts->the_post(); 
	    	$data = array(
					'title' => the_title( '', '', false ),
					'content' => get_the_content(),
					'date' => get_the_date(),
					'time' => get_the_time()
				);

	    	return $renderer( $data );

	}

}else{

      echo '<p>Oops, there are no posts for this category.</p>';

}