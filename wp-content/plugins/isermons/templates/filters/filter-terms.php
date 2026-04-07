<!--
This template is to use for showing filters of all terms list to search sermons.
**
This template can be overridden by copying it to yourtheme/templates/filters/filter-terms.php.
-->
<input name="tabs" value="terms" <?php echo (($params['default']=='terms')?'checked':''); ?> type="radio" id="isermons-tabs-tab2" class="isermons-tabs-input">
<label for="isermons-tabs-tab2" class="isermons-tabs-label"><?php esc_html_e('Filters', 'isermons'); ?></label>
<div class="isermons-tabs-panel">
	<div class="isermons-filters-sorting clearfix">
		<ul>
            <?php
            foreach($params['terms'] as $taxonomy)
            {
                $terms = isermons_get_terms_data('imi_isermons-'.$taxonomy, '', array(), 'name', '', $params['objects']);
                $taxonomy_settings = isermons_get_settings('isermons_taxonomy_'.$taxonomy);
				if(is_array($taxonomy_settings)){
                	$data_child = (in_array('filters', $taxonomy_settings))?'isermons-filter-terms':'';
				}
                $all_selected = array($params[$taxonomy]);
                if(!empty($terms))
                {
                    $parents = $children = $children1 = $children2 = array();
                    if($params[$taxonomy]!='' && $data_child!='')
                    {
                        $parents = get_ancestors($params[$taxonomy], 'imi_isermons-'.$taxonomy);
                        $all_selected = array_merge($parents, $all_selected);
                        if(count($parents)<2)
                        {
                          
                        }
                    }
                    
                    
                    echo '<li>';
                    echo '<select data-attr="'.esc_attr('imi_isermons-'.$taxonomy).'" class="'.esc_attr($data_child).' isermons-filter-sermons">';
                    echo '<option value="">'.esc_html__('All ', 'isermons').$taxonomy.'</option>';
                    $depth = '';
                    foreach($terms as $term)
                    {
                        $selected = (in_array($term['id'], $all_selected))?'selected':'';
                        $children1 = ($selected=='selected' && empty($children1))?$term['children']:$children1;
                        $depth = ($selected=='selected' && $depth=='')?$term['depth']:'';
                        echo '<option '.esc_attr($selected).' data-depth="'.esc_attr($taxonomy.'-'.$term['depth']).'" data-child="'.esc_attr(json_encode($term['children'])).'" value="'.$term['id'].'">'.$term['name'].'</option>';
                    }
                    echo '</select>';
                    echo '</li>';
                    if(!empty($children1))
                    {
                        echo '<li class="'.$taxonomy.'-'.$depth.'">';
                        $depth = '';
                        echo '<select data-attr="'.esc_attr('imi_isermons-'.$taxonomy).'" class="'.esc_attr($data_child).' isermons-filter-sermons">';
                        echo '<option value="">'.esc_html__('Select', 'isermons').'</option>';
                        foreach($children1 as $child1)
                        {
                            $selected = (in_array($child1['id'], $all_selected))?'selected':'';
                            $children2 = ($selected=='selected' && empty($children2))?$child1['children']:$children2;
                            $depth = ($selected=='selected' && $depth=='')?$term['depth']:'';
                            echo '<option '.esc_attr($selected).' data-depth="'.esc_attr($taxonomy.'-'.$child1['depth']).'" data-child="'.esc_attr(json_encode($child1['children'])).'" value="'.$child1['id'].'">'.$child1['name'].'</option>';
                        }
                        echo '</select>';
                        echo '</li>';
                    }
                    if(!empty($children2))
                    {
                        echo '<li class="'.$taxonomy.'-'.$depth.'">';
                        echo '<select data-attr="'.esc_attr('imi_isermons-'.$taxonomy).'" class="'.esc_attr($data_child).' isermons-filter-sermons">';
                        echo '<option value="">'.esc_html__('Select', 'isermons').'</option>';
                        foreach($children2 as $child2)
                        {
                            $selected = (in_array($child2['id'], $all_selected))?'selected':'';
                            echo '<option '.esc_attr($selected).' data-depth="'.esc_attr($taxonomy.'-'.$child2['depth']).'" data-child="" value="'.$child2['id'].'">'.$child2['name'].'</option>';
                        }
                        echo '</select>';
                        echo '</li>';
                    }
                }
            }
            ?>
		</ul>
	</div>
</div>