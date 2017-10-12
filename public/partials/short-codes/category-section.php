<?php

/**
 * Comments here
 *
 */

// first, normalize our shortcode attributes, providing defaults for anything missing
$a = shortcode_atts( array(
	'category_name' => '',
	'order_by' => 'title'),
	 $atts
);

// create an array of query parameters
$args = array( 'category_name' => $a['category_name'] );

// sort posts based on the 'order_by' attribute. The 'shortcode_atts()' method above will provide
// a default of title if no 'order_by' attribute is present, so we can rely on there being something there
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
?>


<?php if( $category_posts->have_posts() ) : ?>

  <?php while( $category_posts->have_posts() ) : ?>

     <?php $category_posts->the_post(); ?>

	<div style="border: 1px solid #EEE; padding: 1em; margin-bottom: 1em;">
		<h2><?php the_title() ?></h2>
		<div class='post-content'><?php the_content() ?></div>
		<p><em><?php the_date(); ?> at <?php the_time(); ?></em></p>
	</div>

	<?php endwhile; ?>

<?php else: ?>

      <p>Oops, there are no posts for this category.</p>

<?php endif; ?>