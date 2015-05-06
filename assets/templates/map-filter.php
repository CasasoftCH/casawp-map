<div id="casasync_map_filter">
	<?php if ($title != ''): ?>
		<h3><?php echo $title; ?></h3>
	<?php endif; ?>
	<ul>
		<?php
			$filter = json_decode($filter_config, true);
			$i= 1;
		?>
		<?php foreach($filter as $key => $value): ?>
			<li data-url="<?php echo $value["url"]; ?>" data-current="<?php echo ($i == 1) ? (1) : (0); ?>">
				<label for="filtervalue<?php echo $i; ?>">
					<input type="checkbox" id="filtervalue<?php echo $i; ?>" name="filter" <?php echo ($i == 1) ? (' checked="checked"') : (''); ?>>
					 <i class="<?php echo $value["icon"]; ?>"></i> <?php echo $value['label']; ?>
				</label> 
			</li>
			<?php $i++; ?>
		<?php endforeach; ?>
	</ul>
</div>