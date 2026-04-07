<!--
This template is to use for showing sermons term in grid view.
**
This template can be overridden by copying it to yourtheme/templates/taxonomy/style1-grid.php.
-->
<?php
echo '<div class="isermons isermons-series-grid isermons-series-grid-classic isermons-grid isermons-grid-col'.esc_attr($params['columns']).'">
        <ul class="equah equah1 isermons-grid-layout">';
if(!empty($params['terms']))
{
    $counter = 1;
    foreach($params['terms'] as $param)
    {
        $image_url = '';
        $term_original_url = get_term_link($param['id'], $params['taxonomy']);
        $term_template_url = isermons_generate_endpoint_url('sermons', $param['slug'], get_permalink());
        $term_new_url = ($params['term_url']=='on')?$term_original_url:$term_template_url;
        $current_series = (isset($param['current']) && $param['current']=='1')?esc_html__('Current Series', 'isermons'):'';
        echo    '<li class="isermons-grid-item isermons-series-grid-item">';
        if(isset($param['metas'][$params['taxonomy'].'_image']) && $param['metas'][$params['taxonomy'].'_image']!='')
        {
            $image = wp_get_attachment_image_src($param['metas'][$params['taxonomy'].'_image'], $params['image']);
            $image_url = $image[0];
        }
            echo '<div class="isermons-media">
						<a href="'.esc_url($term_new_url).'" class="isermons-media-box equah-item1">';
                        if($image_url)
                        {
                            echo '<img src="'.esc_url($image_url).'" alt="Image" class="isermons-term-image">';
                        } else {
							echo '<div class="isermons-default-placeholder"></div>';
						}
                        echo '
						</a>
						<a target="_blank" href="'.esc_url(site_url('feed?post_type=imi_isermons&'.$params['taxonomy'].'='.$param['slug'])).'" class="isermons-feed-link isermons-tip-left isermons-tip-rounded" tooltip-label="Subscribe RSS Feed">
							<i class="isermons-icon-feed"></i>
						</a>
					</div>';
        if($current_series)
        {
            echo    '<div class="isermons-inline-title"><span>'.esc_attr($current_series).'</span></div>';
        }
        $sermons_string = ($param['count']>1)?esc_html__('Sermons', 'isermons'):esc_html__('Sermon', 'isermons');
		echo    '<div class="isermons-grid-item-in equah-item">
						<h4><a href="'.esc_url($term_new_url).'">'.esc_attr($param['name']).'</a></h4>';
						echo ($param['metas']['description']!='')?'<div class="isermons-excerpt"><p>'.wp_trim_words($param['metas']['description'], $params['words']).'</p></div>':'';
						echo '<div class="isermons-meta-data">'.$param['count'].' '.esc_attr($sermons_string).'</div>
					</div>
                </li>';
        if($counter++>$params['count']) break;
    }
}
echo '</ul>
</div>';
?>
