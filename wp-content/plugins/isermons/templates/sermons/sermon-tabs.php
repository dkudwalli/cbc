<!--
This template is to use for showing tabs on sermon details page.
**
This template can be overridden by copying it to yourtheme/templates/sermons/sermon-tabs.php.
-->
<?php
if($params['audio']!='' || $params['video']!='' || $params['bulletin']!='' || $params['notes']!='')
{
?>
<div class="isermons-single-content">
	<div class="isermons isermons-tabs isermons-filter-tabs">
        <?php
		$active_d_class = $active_class = '';
		if($params['video']==''){
			$active_class = 'checked="checked"';
		} elseif($params['video']=='' && $params['audio']=='') {
			$active_d_class = 'checked="checked"';
		}
        if($params['video']!='')
        {
            echo '<input name="tabs" type="radio" id="isermons-tabs-tab1" checked="checked" class="isermons-tabs-input">
            <label for="isermons-tabs-tab1" class="isermons-tabs-label">'.esc_html__('Video', 'isermons').'</label>
            <div class="isermons-tabs-panel">';
                echo do_action('isermons_add_video_section', '', get_the_ID(), array());
            echo '</div>';
        }
        if($params['audio']!='')
        {
            echo '<input name="tabs" type="radio" id="isermons-tabs-tab2" '.$active_class.' class="isermons-tabs-input">
            <label for="isermons-tabs-tab2" class="isermons-tabs-label">'.esc_html__('Audio', 'isermons').'</label>
            <div class="isermons-tabs-panel">';
                echo do_action('isermons_add_audio_section', '', get_the_ID(), array());
            echo '</div>	';
        }
        if($params['audio']!='' || $params['bulletin']!='' || $params['notes']!='')
        {
        ?>
        
				
		<input name="tabs" type="radio" id="isermons-tabs-tab3" <?php echo esc_attr($active_d_class); ?> class="isermons-tabs-input">
		<label for="isermons-tabs-tab3" class="isermons-tabs-label"><?php esc_html_e('Downloads', 'isermons'); ?></label>
		<div class="isermons-tabs-panel">
            <form action="<?php echo esc_url(admin_url( 'admin-ajax.php' )); ?>" method="post" class="">
                    <input type="hidden" name="action" value="isermons_download_files">
                    <input type="hidden" class="isermons-download-sermon" name="sermon" value="<?php echo esc_attr(get_the_ID()); ?>">
                    <input type="hidden" class="isermons-download-file" name="file" value="">
                    <input type="hidden" name="captcha" value="<?php echo wp_create_nonce('isermons-files-download'); ?>">
                </form>
			<ul class="isermons-single-downloads">
                <?php
                if($params['audio']!='')
                {
                    echo '
                    <li><a data-val="audio" class="isermons-download isermons-btn isermons-btn-primary isermons-btn-download">'.esc_html__('Download Audio', 'isermons').'</a></li>
                    ';
                }
                if($params['bulletin']!='')
                {
                    echo '
                    <li><a data-val="bulletin" class="isermons-download isermons-btn isermons-btn-primary isermons-btn-download">'.esc_html__('Download Bulletin', 'isermons').'</a></li>
                    ';
                }
                if($params['notes']!='')
                {
                    echo '
                    <li><a data-val="notes" class="isermons-download isermons-btn isermons-btn-primary isermons-btn-download">'.esc_html__('Download Notes', 'isermons').'</a></li>
                    ';
                }
                ?>
			</ul>
		</div>
        <?php
        }
        if(isermons_get_settings('isermons_details_custom_tab1_switch')=='yes')
        {
            echo '<input name="tabs" type="radio" id="isermons-tabs-tab4" class="isermons-tabs-input">
            <label for="isermons-tabs-tab4" class="isermons-tabs-label">'.esc_html__('Tab1', 'isermons').'</label>
            <div class="isermons-tabs-panel">';
                echo isermons_get_settings('isermons_details_custom_tab1');
            echo '</div>	';
        }
        if(isermons_get_settings('isermons_details_custom_tab2_switch')=='yes')
        {
            echo '<input name="tabs" type="radio" id="isermons-tabs-tab5" class="isermons-tabs-input">
            <label for="isermons-tabs-tab5" class="isermons-tabs-label">'.esc_html__('Tab2', 'isermons').'</label>
            <div class="isermons-tabs-panel">';
                echo isermons_get_settings('isermons_details_custom_tab2');
            echo '</div>	';
        }
        ?>
	</div>		
	<div class="isermons-single-cont">
		<?php
        $metas = isermons_get_settings('isermons_details_meta');
        $metas = (empty($metas))?array('date', 'preacher', 'series', 'books', 'topics', 'categories', 'chapter'):$metas;
        do_action('isermons_meta_data_display', '', get_the_ID(), $metas);
        ?>
        <div class="isermons-spacer-30"></div>
	</div>
</div>
<?php if(isermons_get_settings('isermons_enable_np_links')=='on'){ ?>
	<div class="isermons-np-links">
		<div class="isermons-row">
			<div class="isermons-col5">
				<div class="isermons-np-link"><?php echo previous_post_link('%link','<i class="isermons-icon-arrow-left"></i> %title'); ?>&nbsp;</div>
			</div>
			<div class="isermons-col5">
				<div class="isermons-np-link isermons-np-link-next">&nbsp;<?php echo next_post_link('%link','%title <i class="isermons-icon-arrow-right"></i>'); ?></div>
			</div>
		</div>
	</div>
<?php } ?>
<?php
}
isermons_append_template_with_arguments('templates/sermons/retagger', 'script', []);
?>