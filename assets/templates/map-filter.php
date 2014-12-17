<div id="casasync_map_filter">
	<form method="POST">
		<ul>
			<?php $filter = json_decode($filter_config, true); ?>
			<?php $i= 1; ?>
			<?php foreach($filter as $key => $value): ?>
				<li>
					<label>
						<input type="hidden" name="map_filter" value="0" />
						<?php $shouldBeChecked = (!isset($_POST['map_filter']) && $i==1) ? ('checked="checked"') : (""); ?>
							<input name="map_filter" data-url="<?php echo $value["url"]; ?>" class="radio" type="radio" <?php echo $shouldBeChecked; ?>>
							<?php echo /*$value['icon']. ' ' . */$value['label']; ?>
					</label>
				</li>
				<?php $i++; ?>
			<?php endforeach; ?>
		</ul>
		<button type="submit">Filtern</button>
	</form>
</div>