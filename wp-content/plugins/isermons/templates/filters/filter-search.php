<!--
This template is to use for showing filters of keyword search, date and order of sermons.
**
This template can be overridden by copying it to yourtheme/templates/filters/filter-search.php.
-->
<input name="tabs" type="radio" value="search" id="isermons-tabs-tab1" <?php echo (($params['default']=='search')?'checked':''); ?> class="isermons-tabs-input">
	<label for="isermons-tabs-tab1" class="isermons-tabs-label"><?php esc_html_e('Search &amp; Sort', 'isermons'); ?></label>
	<div class="isermons-tabs-panel">
		<div class="isermons-filters-sorting clearfix">
			<ul>
                <?php
                $saved_data = get_option('isermons_sermons_data_saved');
				if (is_array($params['search'])) {
					if(in_array('keyword', $params['search']))
					{
					?>
					<li>
						<div class="isermons-filter-search clearfix">
							<input class="" value="<?php echo wp_kses($params['key'], isermons_allowed_html()); ?>" type="text" placeholder="<?php esc_html_e('Search Sermons', 'isermons'); ?>">
							<button class="isermons-filter-sermons-search isermons-btn isermons-btn-primary" data-attr="ss"><i class="isermons-icon-magnifier"></i></button>
						</div>
					</li>
					<?php
					}
				
					if(in_array('year', $params['search']) && !empty($saved_data))
					{
					?>
					<li>
						<select class="isermons-filter-sermons" data-attr="years">
							<option value=""><?php esc_html_e('All Years', 'isermons'); ?></option>
							<?php
							$years = array();
							foreach($saved_data as $key=>$value)
							{
								$years[] = $value['year'];
							}
							$years = array_unique($years);
							
							usort($years,function($a,$b){return strlen($b) <=> strlen($a);});
							rsort($years);
							foreach($years as $values)
							{
								$selected = ($params['year']==$values)?'selected':'';
								echo '<option '.esc_attr($selected).' value="'.esc_attr($values).'">'.esc_attr($values).'</option>';
							}
							?>
						</select>
					</li>
					<?php
					}
					if(in_array('order', $params['search']))
					{
					?>
					<li>
						<select class="isermons-filter-sermons" data-attr="order">
							<option <?php echo (($params['order']=='DESC')?'selected':''); ?> value="DESC"><?php esc_html_e('Newest to Oldest', 'isermons'); ?></option>
							<option <?php echo (($params['order']=='ASC')?'selected':''); ?> value="ASC"><?php esc_html_e('Oldest to Newest', 'isermons'); ?></option>
						</select>
					</li>
					<?php
					}
				}
                ?>
			</ul>
		</div>
	</div>