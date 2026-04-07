<!--
This template is to use for showing sermons terms in grid view.
**
This template can be overridden by copying it to yourtheme/templates/taxonomy/style2-grid.php.
-->
<?php
echo '<div class="isermons isermons-series-grid isermons-series-grid-modern isermons-grid isermons-grid-col'.esc_attr($params['columns']).'">
			<ul class="equah isermons-grid-layout">';
if(!empty($params['terms']))
{
    $counter = 1;
    foreach($params['terms'] as $param)
    {
        $term_original_url = get_term_link($param['id'], $params['taxonomy']);
        $term_template_url = isermons_generate_endpoint_url('sermons', $param['slug'], get_permalink());
        $term_new_url = ($params['term_url']=='on')?$term_original_url:$term_template_url;
        $image_url = '';
        echo    '<li class="isermons-grid-item isermons-series-grid-item">';
        if(isset($param['metas'][$params['taxonomy'].'_image']) && $param['metas'][$params['taxonomy'].'_image']!='')
        {
            $image = wp_get_attachment_image_src($param['metas'][$params['taxonomy'].'_image'], $params['image']);
            $image_url = $image[0];
        }
			echo     '<div class="isermons-media">
						<a href="'.esc_url($term_new_url).'" class="isermons-media-box equah-item">';
                        if($image_url)
                        {
                            echo '<img src="'.esc_url($image_url).'" alt="Image" class="isermons-term-image">';
                        } else {
							echo '<div class="isermons-default-placeholder"></div>';
						}
                        echo '
                            <span class="isermons-series-overlay">
								<span><i class="isermons-icon-control-play"></i></span>
								<span><h4 class="series-title">'.esc_attr($param['name']).'</h4></span>
								<span><span class="isermons-fbtn">'.esc_html__('Watch Series', 'isermons').'</span></span>
							</span>
						</a>
						<a target="_blank" href="'.esc_url(site_url('feed?post_type=imi_isermons&'.$params['taxonomy'].'='.$param['slug'])).'" class="isermons-feed-link isermons-tip-left isermons-tip-rounded" tooltip-label="'.esc_html__('Subscribe RSS Feed', 'isermons').'">
							<i class="isermons-icon-feed"></i>
						</a>
					</div>
                </li>';
        if($counter++==$params['count']) break;
    }
}
echo '</ul>
</div>';
?>