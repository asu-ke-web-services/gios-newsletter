<div style="border: 1px solid grey; padding: 1em; margin-bottom: 1em;">
	<?php
	$a = shortcode_atts ( array(
		'category_name' => '',
		'order_by' => 'title'
		), $atts);
	?>

	<h4>Category: <?php echo $a['category_name']; ?></h4>
	<p>This is a category section, which will contain all current posts from a particular category.</p>

	<p>The setting for the shortcode that made this section are:</p>
		<ul>
			<li>category_name: <b><?php echo $a['category_name'];?></b></li>
			<li>order_by: <b><?php echo $a['order_by'];?></b></li>
		</ul>
</div>