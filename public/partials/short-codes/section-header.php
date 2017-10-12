<?php

	$a = shortcode_atts( array(
		'title' => 'Error - No Section Title Provided'
	), $atts );
?>

<div style="background-color: #3399FF; color: white; padding: 1em; margin-bottom: 1em; text-align: center;">
	<h3 style="color: #CCC;">Section Header: <?php echo $a['title']; ?></h2>
</div>