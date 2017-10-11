<?php

	$a = shortcode_atts( array(
		'title' => 'Error - No Section Title Provided'
	), $atts );
?>

<div style="border: 1px solid grey; padding: 1em; margin-bottom: 1em; text-align: center;">
	<h2>Section Header: <?php echo $a['title']; ?></h2>
</div>