<div id="casasync_map_filter">
	<h3><?php echo $title; ?></h3>
	<ul>
		<?php
			$filter = json_decode($filter_config, true);
			$i= 1;
		?>
		<?php foreach($filter as $key => $value): ?>
			<li data-url="<?php echo $value["url"]; ?>" data-current="<?php echo ($i == 1) ? (1) : (0); ?>">
				<?php echo $value['label']; ?>
			</li>
			<?php $i++; ?>
		<?php endforeach; ?>
	</ul>
</div>