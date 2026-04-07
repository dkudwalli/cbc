<?php
function isermons_video_popup($view = '', $id = '', $metas = array())
{
    if($id=='') return;
    $video_url = get_post_meta($id, 'isermons_video_url', true);
    $details = isermons_extract_video_info($video_url);
    if(!isset($details['type']) || $details['type']=='') return;
    if(!is_singular())
    {
        $view .=
        '<div class="isermons-modal-static isermons-modal-fw isermons-modal-av" id="isermons-video-modal'.esc_attr($id).'">
                <div class="isermons-modal-body"><a href="#" class="isermons-modal-close">x</a>';
	}
		if($details['type']=='self')
		{
			$view .= '<video poster="" playsinline controls class="plyr-player">
					<source src="'.$video_url.'" type="video/'.$details['ext'].'">
				</video>';
		}
		else
		{
			$view .= '<div class="plyr-player" data-plyr-provider="'.esc_attr($details['type']).'" data-plyr-embed-id="'.esc_attr($details['id']).'"></div>';
		}
	if(!is_singular())
    {
        $view .= '
                </div>
        </div>';
	}
    echo wp_kses($view, isermons_allowed_html());
}
add_action('isermons_add_video_section', 'isermons_video_popup', 10, 3);

function isermons_audio_popup($view = '', $id = '', $metas = array())
{
    if($id=='') return;
    $audio_url = get_post_meta($id, 'isermons_audio_file', true);
    if(!$audio_url) return;
    if(!is_singular())
    {
        $view .=
        '<div class="isermons-modal-static isermons-modal-fw isermons-modal-av" id="isermons-audio-modal'.esc_attr($id).'">
                <div class="isermons-modal-body"><a href="#" class="isermons-modal-close">x</a>';
	}
                $view .= '
				<audio class="plyr-player" controls>
					<source src="'.esc_url($audio_url).'" type="audio/mp3">
				</audio>';
    
    if(!is_singular())
    {
		$view .= '</div>
		</div>';
	}
	
    echo wp_kses($view, isermons_allowed_html());
}
add_action('isermons_add_audio_section', 'isermons_audio_popup', 10, 3);

function isermons_meta_data_view($meta_list = '', $id = '', $metas = array())
{
    if(is_array($metas) && array_diff(array('preacher', 'date', 'series', 'chapter', 'books', 'topics', 'categories'), $metas)==0) return;
    $preachers = isermons_get_terms_data('imi_isermons-preachers', $id, array(), 'name', '1');
    $series = isermons_get_terms_data('imi_isermons-series', $id, array(), 'name', '1');
    $books = isermons_get_terms_data('imi_isermons-books', $id, array(), 'name', '1');
    $topics = isermons_get_terms_data('imi_isermons-topics', $id, array(), 'name', '1');
    $category = isermons_get_terms_data('imi_isermons-categories', $id, array(), 'name', '1');
    $chapter = get_post_meta(get_the_ID(), 'isermons_bible_passage', true);
	$date_type = isermons_get_settings('isermons_date_type');
    $date = get_post_meta($id, 'isermons_date_preached', true);
    $date = (!empty($date))?$date:get_the_date('Y-m-d', $id);
	if($date_type == 'publish'){
		$date = get_the_date('Y-m-d', $id);
	}
    $meta_list .= '
    <div class="isermons-meta-data">';
        $meta_list .= (in_array('date', $metas) && !empty($date))?'<div class="isermons-meta isermons-meta-date"><i class="isermons-icon-calendar"></i> '.esc_attr(date_i18n(get_option('date_format'), strtotime($date))).'</div>':'';
		$meta_list .= (in_array('preacher', $metas) && $preachers!='')?'<div class="isermons-meta isermons-meta-preacher">'.esc_html__('By', 'isermons').' '.$preachers.'</div>':'';
        $meta_list .= (in_array('series', $metas) && !empty($series))?'<div class="isermons-meta isermons-meta-series"><i class="isermons-icon-folder-alt"></i> '.$series.'</div>':'';
        $meta_list .= (in_array('books', $metas) && !empty($books))?'<div class="isermons-meta isermons-meta-books"><i class="isermons-icon-notebook"></i> '.$books.'</div>':'';
        $meta_list .= (in_array('topics', $metas) && !empty($topics))?'<div class="isermons-meta isermons-meta-topics"><i class="isermons-icon-docs"></i> '.$topics.'</div>':'';
        $meta_list .= (in_array('categories', $metas) && !empty($category))?'<div class="isermons-meta isermons-meta-categories"><i class="isermons-icon-folder-alt"></i> '.$category.'</div>':'';
        $meta_list .= (in_array('chapter', $metas) && !empty($chapter))?'<div class="isermons-meta isermons-meta-passage isermons-bible-passage"><i class="isermons-icon-book-open"></i> <abbr>'.esc_attr($chapter).'</abbr></div>':'';

    $meta_list .= '
	</div>
    ';
    echo wp_kses($meta_list, isermons_allowed_html());
}
add_action('isermons_meta_data_display', 'isermons_meta_data_view', 10, 3);

function isermons_meta_media_actions($actions = '', $id = '', $metas = array())
{
    if(array_diff(array('video', 'audio', 'download'), $metas)==0) return;
    $audio_url = get_post_meta($id, 'isermons_audio_file', true);
    $video_url = get_post_meta($id, 'isermons_video_url', true);
    $bulletin = get_post_meta($id, 'isermons_bulletin_file', true);
    $notes = get_post_meta($id, 'isermons_notes_file', true);
	$enable_audio_download = isermons_get_settings('isermons_enable_audio_download');
    $actions .= '
    <div class="isermons-sermon-actions">
		<ul>';
        if(in_array('video', $metas) && $video_url!='')
        {
            $actions .= '
            <li class="isermons-pl-video isermons-pl-va"><a href="#isermons-video-modal'.esc_attr($id).'" class="isermons-tip-top-left isermons-tip-rounded" tooltip-label="'.esc_html__('Play Video', 'isermons').'"><i class="isermons-icon-social-youtube"></i></a></li>
            ';
        }
		if(in_array('audio', $metas) && $audio_url!='')
        {
            $actions .= '
            <li class="isermons-pl-audio isermons-pl-va"><a href="#isermons-audio-modal'.esc_attr($id).'" class="isermons-tip-top-left isermons-tip-rounded" tooltip-label="'.esc_html__('Play Audio', 'isermons').'"><i class="isermons-icon-microphone"></i></a></li>
            ';
        }
		if(in_array('download', $metas) && ($audio_url!='' || $bulletin!='' || $notes!=''))
        {
            $actions .= '
            <li class="isermons-dl-files">
                <form action="'.esc_url(admin_url( 'admin-ajax.php' )).'" method="post" class="">
                    <input type="hidden" name="action" value="isermons_download_files">
                    <input type="hidden" class="isermons-download-sermon" name="sermon" value="'.esc_attr($id).'">
                    <input type="hidden" class="isermons-download-file" name="file" value="">
                    <input type="hidden" name="captcha" value="'.wp_create_nonce('isermons-files-download').'">
                </form>
				<a href="#" class="isermons-tip-top-left isermons-tip-rounded" tooltip-label="'.esc_html__('Downloads', 'isermons').'"><i class="isermons-icon-cloud-download"></i></a>
				<ul class="isermons-download-files">';
                if($audio_url!='' && $enable_audio_download == 'on')
                {
                    $actions .= '
                    <li><a data-val="audio" class="isermons-download">'.esc_html__('Download Audio', 'isermons').'</a></li>
                    ';
                }
                if($bulletin!='')
                {
                    $actions .= '
                    <li><a data-val="bulletin" class="isermons-download">'.esc_html__('Download Bulletin', 'isermons').'</a></li>
                    ';
                }
                if($notes!='')
                {
                    $actions .= '
                    <li><a data-val="notes" class="isermons-download">'.esc_html__('Download Notes', 'isermons').'</a></li>
                    ';
                }
                $actions .= '
				</ul>
			</li>
            ';
        }
		$actions .= '	
		</ul>
	</div>
    ';
    echo wp_kses($actions, isermons_allowed_html());
}
add_action('isermons_add_meta_and_media_actions', 'isermons_meta_media_actions', 10, 3);