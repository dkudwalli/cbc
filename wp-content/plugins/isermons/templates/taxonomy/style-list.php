<!--
This template is to use for showing sermons terms in list view.
**
This template can be overridden by copying it to yourtheme/templates/taxonomy/style-list.php.
-->
<?php
echo '<div class="isermons isermons-series-list isermons-list">
		<ul class="isermons-tax-list">';
if(!empty($params['terms']))
{
    $counter = 1;
    foreach($params['terms'] as $param)
    {
        $image_url = $nIpClass = '';
        $term_original_url = get_term_link($param['id'], $params['taxonomy']);
        $term_template_url = isermons_generate_endpoint_url('sermons', $param['slug'], get_permalink());
        $term_new_url = ($params['term_url']=='on')?$term_original_url:$term_template_url;
        echo    '<li class="isermons-list-item isermons-series-list-item">';
        if(isset($param['metas'][$params['taxonomy'].'_image']) && $param['metas'][$params['taxonomy'].'_image']!='')
        {
            $image = wp_get_attachment_image_src($param['metas'][$params['taxonomy'].'_image'], $params['image']);
            $image_url = $image[0];
        }
		if(!$image_url){
			$nIpClass = 'isermons-ph-term';
		}
        $sermons_string = ($param['count']>1)?esc_html__('Sermons', 'isermons'):esc_html__('Sermon', 'isermons');
					echo '<div>
					<div class="isermons-media '.$nIpClass.'">
						<a href="'.esc_url($term_new_url).'" class="isermons-media-box">';
                        if($image_url)
                        {
                            echo '<img src="'.esc_url($image_url).'" alt="Image" class="isermons-term-image">';
                        } else {
							echo '<div class="isermons-default-placeholder"></div>';
						}
                        echo '
						</a>
						<a target="_blank" href="'.esc_url(site_url('feed?post_type=imi_isermons&'.$params['taxonomy'].'='.$param['slug'])).'" class="isermons-feed-link isermons-tip-left isermons-tip-rounded" tooltip-label="'.esc_html__('Subscribe RSS Feed', 'isermons').'">
							<i class="isermons-icon-feed"></i>
						</a>
					</div>
					</div>
					<div>
					<div class="isermons-list-item-in">
						<h4 class="series-title"><a href="'.esc_url($term_new_url).'">'.esc_attr($param['name']).'</a></h4>
						<div class="isermons-meta-data">'.$param['count'].' '.esc_attr($sermons_string).'</div>';
						echo ($param['metas']['description']!='')?'<div class="isermons-excerpt"><p>'.wp_trim_words($param['metas']['description'], $params['words']).'</p></div>':'';
						echo '<a href="'.esc_url($term_new_url).'" class="isermons-btn isermons-btn-primary isermons-btn-play">'.esc_html__('Watch Series', 'isermons').'</a>
					</div>
					</div>
				</li>';
        if($counter++>$params['count']) break;
    }
}
echo '</ul>
</div>';
?>
