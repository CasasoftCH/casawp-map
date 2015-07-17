<?php $converter = new CasaSync\Conversion(); ?>
<?php $default_query = array(
	"casasync_availability_s" => array(),
	"casasync_salestype_s" => array(),
	"casasync_category_s" => array(),
	"casasync_location_s" => array()
); ?>
<?php $query = array_merge($default_query, $_GET); ?>
<pre>
</pre>

<div id="casasync_map_filter">
	<?php if ($title != ''): ?>
		<h3><?php echo $title; ?></h3>
	<?php endif; ?>
		<?php
			$filters = json_decode($filter_config, true);
			$i = 1;
		?>
		<form method="GET">	
			
			<?php foreach ($filters as $filter): ?>
				<?php if ($filter['visible'] == 1): ?>
					<div class="taxonomy_wrapper <?= ($filter['taxonomy']) ? " " . $filter['taxonomy'] : "" ; ?>">
						<h3><?php echo $filter['label'] ?></h3>
						<?php 
							$params = array(
								$filter['taxonomy'].($filter['inclusive'] ? "" : "_not" ) => array_map('trim', explode(',', $filter['filter_terms']))
							);

								
							$inc_terms = array_map('trim', explode(',', $filter['filter_terms']));
							
								
							

							$url = "/casasync/ajax?".http_build_query($params);
							switch ($filter["taxonomy"]) {
								case 'casasync_salestype':
									$terms = get_terms( $filter['taxonomy'], array(
									    'orderby'           => 'name', 
									    'order'             => 'ASC',
									    'hide_empty'        => true, 
									    'exclude'           => array(), 
									    'exclude_tree'      => array(), 
									    'include'           => array(),
									    'number'            => '', 
									    'fields'            => 'all', 
									    'slug'              => '',
									    'parent'            => '',
									    'hierarchical'      => true, 
									    'child_of'          => 0,
									    'childless'         => true,
									    'get'               => '', 
									    'name__like'        => '',
									    'description__like' => '',
									    'pad_counts'        => false, 
									    'offset'            => '', 
									    'search'            => '', 
									    'cache_domain'      => 'core'
									) );
									foreach ($terms as $term) {
										if ($term->name == 'buy') {
										    $label = __('Buy', 'casasync');
										} elseif ($term->name == 'rent') {
										    $label = __('Rent', 'casasync');
										} else {
										    $label = $term->name;
										}

										if (!in_array($term->name, $inc_terms) && !in_array($label, $inc_terms)) {
											$input = new casasoft\casasyncmap\Input('checkbox', $filter['taxonomy']."_s[]", $term->name, array(
												'label' => $label,
												'checked' => (in_array($term->name, $query[$filter['taxonomy']."_s"]) ? true : false)
											));

											echo $input;
										}
									}
								break;
								case 'casasync_category': 
									$terms = get_terms( $filter['taxonomy'], array(
									    'orderby'           => 'name', 
									    'order'             => 'ASC',
									    'hide_empty'        => true, 
									    'exclude'           => array(), 
									    'exclude_tree'      => array(), 
									    'include'           => array(),
									    'number'            => '', 
									    'fields'            => 'all', 
									    'slug'              => '',
									    'parent'            => '',
									    'hierarchical'      => true, 
									    'child_of'          => 0,
									    'childless'         => true,
									    'get'               => '', 
									    'name__like'        => '',
									    'description__like' => '',
									    'pad_counts'        => false, 
									    'offset'            => '', 
									    'search'            => '', 
									    'cache_domain'      => 'core'
									) );
									foreach ($terms as $term) {
										if ($converter->casasync_convert_categoryKeyToLabel($term->name)) {

											$label = $converter->casasync_convert_categoryKeyToLabel($term->name);

											
											if (!in_array($term->name, $inc_terms) && !in_array($label, $inc_terms)) {
												$input = new casasoft\casasyncmap\Input('checkbox', $filter['taxonomy']."_s[]", $term->name, array(
													'label' => $label,
													'checked' => (in_array($term->name, $query[$filter['taxonomy']."_s"]) ? true : false)
													));
												echo $input;
											}
										}
									}
								break;
								case 'casasync_availability': 
									$terms = get_terms( $filter['taxonomy'], array(
									    'orderby'           => 'name', 
									    'order'             => 'ASC',
									    'hide_empty'        => true, 
									    'exclude'           => array(), 
									    'exclude_tree'      => array(), 
									    'include'           => array(),
									    'number'            => '', 
									    'fields'            => 'all', 
									    'slug'              => '',
									    'parent'            => '',
									    'hierarchical'      => true, 
									    'child_of'          => 0,
									    'childless'         => true,
									    'get'               => '', 
									    'name__like'        => '',
									    'description__like' => '',
									    'pad_counts'        => false, 
									    'offset'            => '', 
									    'search'            => '', 
									    'cache_domain'      => 'core'
									) );
									foreach ($terms as $term) {
										if ($converter->casasync_convert_availabilityKeyToLabel($term->name)) {
											$label = $converter->casasync_convert_availabilityKeyToLabel($term->name);

											
											if (!in_array($term->name, $inc_terms) && !in_array($label, $inc_terms)) {
												$input = new casasoft\casasyncmap\Input('checkbox', $filter['taxonomy']."_s[]", $term->name, array(
													'label' => $label,
													'checked' => (in_array($term->name, $query[$filter['taxonomy']."_s"]) ? true : false)
												));
												echo $input;
											}
										}
									}
									//echo get_post_meta( $term->term_id, 'availability_label', $single = true );
								break;
								case 'casasync_location':

									$terms = get_terms( $filter['taxonomy'], array(
									    'orderby'           => 'name', 
									    'order'             => 'ASC',
									    'hide_empty'        => true, 
									    'exclude'           => array(), 
									    'exclude_tree'      => array(), 
									    'include'           => array(),
									    'number'            => '', 
									    'fields'            => 'all', 
									    'slug'              => '',
									    'parent'            => '',
									    'hierarchical'      => true, 
									    'child_of'          => 0,
									    'childless'         => false,
									    'get'               => '', 
									    'name__like'        => '',
									    'description__like' => '',
									    'pad_counts'        => false, 
									    'offset'            => '', 
									    'search'            => '', 
									    'cache_domain'      => 'core'
									));

									foreach ($terms as $term) {

										$label = __($term->name , 'casasync');

										

										if (!in_array($term->name, $inc_terms) && !in_array($label, $inc_terms)) {
											$input = new casasoft\casasyncmap\Input('checkbox', $filter['taxonomy']."_s[]", $term->slug, array(
												'label' => $label,
												'checked' => (in_array($term->name, $query[$filter['taxonomy']."_s"]) ? true : false)
											));
											echo $input;
										}

										
									}
									break;
								default:
									$terms = get_terms( $filter['taxonomy'], array(
									    'orderby'           => 'name', 
									    'order'             => 'ASC',
									    'hide_empty'        => true, 
									    'exclude'           => array(), 
									    'exclude_tree'      => array(), 
									    'include'           => array(),
									    'number'            => '', 
									    'fields'            => 'all', 
									    'slug'              => '',
									    'parent'            => '',
									    'hierarchical'      => true, 
									    'child_of'          => 0,
									    'childless'         => true,
									    'get'               => '', 
									    'name__like'        => '',
									    'description__like' => '',
									    'pad_counts'        => false, 
									    'offset'            => '', 
									    'search'            => '', 
									    'cache_domain'      => 'core'
									));

									foreach ($terms as $term) {


										$label = __($term->name , 'casasync');

										
										
										if (!in_array($term->name, $inc_terms) && !in_array($label, $inc_terms)) {
											$input = new casasoft\casasyncmap\Input('checkbox', $filter['taxonomy']."_s[]", $term->name, array(
												'label' => $label,
												'checked' => (in_array($term->name, $query[$filter['taxonomy']."_s"]) ? true : false)
											));
											echo $input;
										}

										//echo "<input type='checkbox' id='filtervalue$i' name='filter'" . ($i == 1) ? (' checked="checked"') : ('') . ">";
									}
								break;
							}
						?>
					</div>
				<?php endif; ?>

			<?php endforeach; ?>
		</form>
		<?php /* foreach($filter as $key => $value): ?>
			<li data-current="<?php echo ($i == 1) ? (1) : (0); ?>">
				<label for="filtervalue<?php echo $i; ?>">
					<input type="checkbox" id="filtervalue<?php echo $i; ?>" name="filter" <?php echo ($i == 1) ? (' checked="checked"') : (''); ?>>
					<?php if ($value["icon"]): ?>
						<i class="<?php echo $value["icon"]; ?>"></i> 
					<?php endif ?>

					<?php echo $value['label']; ?>
				</label> 
			</li>
			<?php $i++; ?>
		<?php endforeach; */ ?>
</div>