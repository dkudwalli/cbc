<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Callback function to show fields for sermons custom post type meta box

function isermons_show_meta_box($post, $metabox) {
    $meta_box = apply_filters('isermons_get_details_metafields', array());
    global $isermons_allowed_tags;

    // Use nonce for verification
    echo '<input type="hidden" name="isermons_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	foreach($metabox['args'] as $field)
    {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		$field_type_class = (isset($field['type']) && $field['type']=='file')?'isermons-media-field-area':'';

		echo '<div class="isermons-admin-row"><div class="isermons-admin-column"><label class="isermons-field-title">'.$field['name'].'</label><span class="field-description">'.$field['desc'].'</span></div>';
		echo '<div class="isermons-admin-column '.esc_attr($field_type_class).'">';
			
		switch ($field['type']) {
			case 'text':
				echo '<input maxlength="'. $field['limit']. '" class="'.esc_attr($field['class']).'" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" />', '';
				break;
			case 'file':
				echo '<div class="isermons-admin-row">';
				echo '<div class="isermons-admin-column">';
				echo '<input maxlength="'. $field['limit']. '" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" class="isermons_media_field"/><input type="hidden" class="isermons-file-id" value="">', '';
				echo '</div>';
				echo '<div class="isermons-admin-column">';
				echo '<input type="button" class="button isermons-add-file" value="'.esc_html__('Add File', 'isermons').'">';
				echo '<input type="button" class="button isermons-remove-file hidden" value="'.esc_html__('Remove', 'isermons').'">';
				echo '</div>';
				echo '</div>';
				break;
			case 'textarea':
				echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4">', $meta ? $meta : $field['std'], '</textarea>', '';
				break;
			case 'select':
				$multiple = (isset($field['multiple']))?$field['multiple']:'';
				$select_multi = ($multiple)?'multiple':'';
				$select_start = ($multiple)?'<select '.$select_multi.' name="'.$field['id'].'[]" id="'.$field['id'].'">':'<select '.$select_multi.' name="'.$field['id'].'" id="'.$field['id'].'">';
				echo wp_kses($select_start, $isermons_allowed_tags);
				foreach ($field['options'] as $key=>$value) {
					if(is_array($meta))
					{
						echo '<option ', in_array($key, $meta)? ' selected="selected"' : '', ' value="'.$key.'">', $value, '</option>';
					}
					else
					{
						echo '<option ', $meta == $key ? ' selected="selected"' : '', ' value="'.$key.'">', $value, '</option>';
					}
				}
				echo '</select>', '';
				break;
			case 'radio':
				foreach ($field['options'] as $option) {
					echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
				}
				break;
			case 'checkbox':
				echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />','';
				break;
    	}
		echo '</div>';
		echo '</div>';
	}
}

function isermons_create_details_metabox($meta_box = array())
{
    $prefix = 'isermons_';
    $meta_box[] = array(
        'id' => 'isermons_sermon_details',
        'title' => esc_html__('Sermon Details', 'isermons'),
        'page' => 'imi_isermons',
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => esc_html__('Date Preached', 'isermons'),
                'desc' => esc_html__('This is optional.', 'isermons'),
                'id' => $prefix . 'date_preached',
                'class' => 'isermons_admin_date_picker',
                'limit' => '',
                'type' => 'text',
                'std' => ''
            ),
        )
    );
    
    $meta_box[] = array(
        'id' => 'isermons_downloadable_files',
        'title' => esc_html__('Sermon Media', 'isermons'),
        'page' => 'imi_isermons',
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => esc_html__('Audio', 'isermons'),
                'desc' => esc_html__('Add/Upload or enter an URL for audio file which will be used for the audio player as well as for the sermon audio download.', 'isermons'),
                'id' => $prefix . 'audio_file',
                'class' => '',
                'limit' => '',
                'type' => 'file',
                'std' => ''
            ),
            array(
                'name' => esc_html__('Bulletin', 'isermons'),
                'desc' => esc_html__('Upload bulletin PDF file or enter an URL. This file will be available for download for the sermon.', 'isermons'),
                'id' => $prefix . 'bulletin_file',
                'class' => '',
                'limit' => '',
                'type' => 'file',
                'std' => ''
            ),
            array(
                'name' => esc_html__('Sermon Notes', 'isermons'),
                'desc' => esc_html__('Upload bulletin PDF file or enter an URL. This file will be available for download for the sermon.', 'isermons'),
                'id' => $prefix . 'notes_file',
                'class' => '',
                'limit' => '',
                'type' => 'file',
                'std' => ''
            ),
            array(
                'name' => esc_html__('Video', 'isermons'),
                'desc' => esc_html__('Add URL of video.', 'isermons'),
                'id' => $prefix . 'video_url',
                'class' => '',
                'limit' => '',
                'type' => 'file',
                'std' => ''
            ),
            array(
                'name' => esc_html__('Bible Passage', 'isermons'),
                'desc' => esc_html__('Enter bible passage with full book name, Example: John 3:16-18', 'isermons'),
                'id' => $prefix . 'bible_passage',
                'class' => '',
                'limit' => '',
                'type' => 'text',
                'std' => ''
            ),
        )
    );
    
    $meta_box[] = array(
        'id' => 'isermons_podcast_fields',
        'title' => esc_html__('Podcast Details', 'isermons'),
        'page' => 'imi_isermons',
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => esc_html__('Audio length', 'isermons'),
                'desc' => esc_html__('Enter leangth of the uploaded/selected audio file in format hh:mm:ss', 'isermons'),
                'id' => $prefix . 'audio_length',
                'class' => '',
                'limit' => '',
                'type' => 'text',
                'std' => ''
            ),
            array(
                'name' => esc_html__('Audio file size', 'isermons'),
                'desc' => esc_html__('Enter file size for the uploaded/selected audio file in MB. Example: 2MB', 'isermons'),
                'id' => $prefix . 'audio_size',
                'class' => '',
                'limit' => '',
                'type' => 'text',
                'std' => ''
            ),
            array(
                'name' => esc_html__('Description', 'isermons'),
                'desc' => esc_html__('Enter short description for sermon which will be used for the sermon podcast.', 'isermons'),
                'id' => $prefix . 'sermon_description',
                'class' => '',
                'limit' => '',
                'type' => 'textarea',
                'std' => ''
            ),
        )
    );
    
    
    return $meta_box;
}
add_filter('isermons_get_details_metafields', 'isermons_create_details_metabox', 10, 1);
add_action('add_meta_boxes_imi_isermons', 'isermons_add_meta_box');

// Add meta box
function isermons_add_meta_box() {
    $meta_box = apply_filters('isermons_get_details_metafields', array());
    foreach($meta_box as $mbox)
    {
        add_meta_box($mbox['id'], $mbox['title'], 'isermons_show_meta_box', $mbox['page'], $mbox['context'], $mbox['priority'], $mbox['fields']);
    }
    
}

add_action('save_post', 'isermons_save_meta_data');

// Save data from meta box
function isermons_save_meta_data($post_id) {
    $meta_box = apply_filters('isermons_get_details_metafields', array());
    // verify nonce
    if (!isset($_POST['isermons_meta_box_nonce'])||!wp_verify_nonce($_POST['isermons_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    foreach($meta_box as $meta)
    {
        foreach ($meta['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = (isset($_POST[$field['id']]))?$_POST[$field['id']]:'';

        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
    }
    
}
$taxonomies = array('imi_isermons-categories', 'imi_isermons-series', 'imi_isermons-topics', 'imi_isermons-books', 'imi_isermons-preachers');
if(!function_exists('isermons_term_image_field')):

if (isset($_REQUEST['taxonomy'])):

foreach($taxonomies as $taxonomy)
{
    add_action($taxonomy . '_add_form_fields', 'isermons_term_image_field', 10, 2);
    add_action($taxonomy . '_edit_form_fields', 'isermons_term_image_field', 10, 2);
}
function isermons_term_image_field($tag) {
    $taxonomy = $_REQUEST['taxonomy'];
    if (is_object($tag)) {
        $term_id = $tag->term_id; // Get the ID of the term we're editing
        $term_meta = get_term_meta( $term_id, $taxonomy.'_image', true);
		$image_src = wp_get_attachment_image_src($term_meta);
		$image = $image_src[0];
    } else {
        $term_meta = '';
		$image = '';
    }
       ?>
    <table class="form-table">
        <tbody>
            <tr class="form-field form-required">
                <th scope="row"><label for="image"><?php esc_html_e('Term Image', 'isermons') ?></label></th>
                   <td><?php
                       echo '<div><img id="'.$taxonomy.'_term_image" class="isermons-term-image-src" src ="' . esc_url($image) . '" width ="150px" height ="150px"/></div>';
                       echo '<input id="'.$taxonomy.'_upload_image" type="button" class="button button-primary isermons-term-upload-image" value="'.esc_html__('Upload Image', 'isermons').'" /> ';
                      if(isset($tag->term_id)){
                       echo '<input id="'.$taxonomy.'_image_remove" type="button" class="button button-primary isermons-term-remove-image" value="'.esc_html__('Remove Image', 'isermons').'" />';
                      }
                       ?>
                   <p class="description"><?php esc_html_e('Upload term image.', 'isermons'); ?></p>
                   </td>
                 </tr><input type="hidden" class="isermons-term-image-id" id="<?php echo esc_attr($taxonomy); ?>_image_id" name="<?php echo esc_attr($taxonomy); ?>_id_save" value="<?php echo esc_attr($term_meta); ?>" />
           </tbody>
       </table>              
   <?php
} endif;
if(!function_exists('isermons_category_save_image_custom_fields')):
foreach($taxonomies as $taxonomy)
{
    add_action('created_' . $taxonomy, 'isermons_category_save_image_custom_fields');
    add_action('edited_' . $taxonomy, 'isermons_category_save_image_custom_fields', 10, 1);
}

function isermons_category_save_image_custom_fields($term_id) {
    $taxonomy = $_REQUEST['taxonomy'];
       if (isset($_POST[$taxonomy.'_id_save'])) {
           $venue_image = $_POST[$taxonomy.'_id_save'];
           update_term_meta( $term_id, $taxonomy.'_image', $venue_image);
         }
       }
       endif;
endif;
