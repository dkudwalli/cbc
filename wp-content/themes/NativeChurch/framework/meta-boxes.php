<?php
if (!class_exists('Native_Church_Core_Features')) {
  return;
}

$home_page = get_option('page_on_front');
$featured_block = get_post_meta($home_page, 'imic_home_featured_blocks1', true);
$featured_block2 = get_post_meta($home_page, 'imic_home_featured_blocks2', true);
$featured_block3 = get_post_meta($home_page, 'imic_home_featured_blocks3', true);
$all_blocks = array($featured_block, $featured_block2, $featured_block3);
if ($featured_block != '') {
  update_post_meta($home_page, 'imic_home_row_featured_blocks', $all_blocks);
  update_post_meta($home_page, 'imic_home_featured_blocks1', '');
  update_post_meta($home_page, 'imic_home_featured_blocks2', '');
  update_post_meta($home_page, 'imic_home_featured_blocks3', '');
}
function prefix_register_meta_boxes($meta_boxes)
{
  $sermons_cats = apply_filters('nativechurch_get_terms', 'sermons-category');
  /* * ** Meta Box Functions **** */
  $prefix = 'imic_';
  global $meta_boxes;
  $imic_options = get_option('imic_options');
  $event_time_format = (isset($imic_options['event_admin_timezone'])) ? $imic_options['event_admin_timezone'] : 'HH:mm';
  $event_feature = (isset($imic_options['enable_event_feature'])) ? $imic_options['enable_event_feature'] : '1';
  $meta_boxes = array();
  /* Staff Meta Box
    ================================================== */
  $meta_boxes[] = array(
    'id' => 'staff_meta_box',
    'title' => esc_html__('Staff Member Meta', 'framework'),
    'pages' => array('staff'),
    'fields' => array(
      array(
        'name' => esc_html__('Facebook', 'framework'),
        'id' => $prefix . 'staff_member_facebook',
        'desc' => esc_html__("Enter staff member's Facebook URL.", 'framework'),
        'clone' => false,
        'type' => 'hidden',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Twitter', 'framework'),
        'id' => $prefix . 'staff_member_twitter',
        'desc' => esc_html__("Enter staff member's Twitter username.", 'framework'),
        'clone' => false,
        'type' => 'hidden',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Google+', 'framework'),
        'id' => $prefix . 'staff_member_google_plus',
        'desc' => esc_html__("Enter staff member's Google+ URL.", 'framework'),
        'type' => 'hidden',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Pinterest', 'framework'),
        'id' => $prefix . 'staff_member_pinterest',
        'desc' => esc_html__("Enter staff member's Pinterest URL.", 'framework'),
        'type' => 'hidden',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Email', 'framework'),
        'id' => $prefix . 'staff_member_email',
        'desc' => esc_html__("Enter staff member's Email.", 'framework'),
        'type' => 'text',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Phone Number', 'framework'),
        'id' => $prefix . 'staff_member_phone',
        'desc' => esc_html__("Enter staff member's phone number.", 'framework'),
        'type' => 'text',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Job Title', 'framework'),
        'id' => $prefix . 'staff_job_title',
        'desc' => esc_html__("Enter staff member's job title.", 'framework'),
        'type' => 'text',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Social Icon', 'framework'),
        'id' => $prefix . "social_icon_list",
        'desc' => esc_html__('Select social icons and enter social profile URL.', 'framework'),
        'type' => 'text_list',
        'clone' => true,
        'options' => array(
          '0' => esc_html__('Social', 'framework'),
          '1' => esc_html__('URL', 'framework'),
        ),
      ),
    ),
  );
  /* Causes Meta Box
    ================================================== */
  /*** Causes Details Meta box ***/
  $meta_boxes[] = array(
    'id' => 'cause_meta_box',
    'title' => esc_html__('Cause Details', 'framework'),
    'pages' => array('causes'),
    'fields' => array(
      array(
        'name' => esc_html__('Cause End Date', 'framework'),
        'id' => $prefix . 'cause_end_dt',
        'desc' => esc_html__("Choose date when this cause will end and stop accepting donations.", 'framework'),
        'type' => 'date',
        'js_options' => array(
          'dateFormat' => 'yy-mm-dd',
          'changeMonth' => true,
          'changeYear' => true,
          'showButtonPanel' => false,
        ),
      ),
      array(
        'name' => esc_html__('Cause Amount', 'framework'),
        'id' => $prefix . 'cause_amount',
        'desc' => esc_html__("Insert target amount required for this cause.", 'framework'),
        'type' => 'text',
      ),
      array(
        'name' => esc_html__('Cause Amount Received', 'framework'),
        'id' => $prefix . 'cause_amount_received',
        'desc' => esc_html__("Total amount received so far for this cause.", 'framework'),
        'type' => 'text',
      ),
      array(
        'name' => esc_html__('Custom donate button', 'framework'),
        'id' => $prefix . 'cause_custom_donation',
        'desc' => esc_html__("Enter URL for the donate button used in place of default donation form popup.", 'framework'),
        'type' => 'text',
      ),
      array(
        'name' => esc_html__('Open in a new window', 'framework'),
        'desc' => esc_html__("Check this option to open custom donate button URL in a new browser tab/window.", 'framework'),
        'id' => $prefix . 'cause_custom_donation_window',
        'type' => 'checkbox',
      ),
    ),
  );
  if ($event_feature == '1') {
    /* Event Meta Box
        ================================================== */
    /*** Event Details Meta box ***/
    $meta_boxes[] = array(
      'id' => 'event_meta_box',
      'title' => esc_html__('Event Details Meta Box', 'framework'),
      'pages' => array('event'),
      'fields' => array(
        array(
          'name' => esc_html__('Event Start Date', 'framework'),
          'id' => $prefix . 'event_start_dt',
          'desc' => esc_html__("Choose date when this event will start.", 'framework'),
          'type' => 'date',
          'js_options' => array(
            'dateFormat' => 'yy-mm-dd',
            'changeMonth' => true,
            'changeYear' => true,
            'showButtonPanel' => true,
          ),
        ),
        array(
          'name' => esc_html__(' Event End Date', 'framework'),
          'id' => $prefix . 'event_end_dt',
          'desc' => esc_html__("Choose date when this event will end.", 'framework'),
          'type' => 'date',
          'js_options' => array(
            'dateFormat' => 'yy-mm-dd',
            'changeMonth' => true,
            'changeYear' => true,
            'showButtonPanel' => false,
          ),
        ),
        array(
          'name' => esc_html__('All Day Event', 'framework'),
          'desc' => esc_html__("Check this option if this event will be happening for the day of the chosen start/end date. This will work for single day events only.", 'framework'),
          'id' => $prefix . 'event_all_day',
          'type' => 'checkbox',
        ),
        array(
          'name' => esc_html__('Event Start Time', 'framework'),
          'id' => $prefix . 'event_start_tm',
          'type' => 'time',
          // jQuery datetime picker options. See here http://trentrichardson.com/examples/timepicker/
          'js_options' => array(
            'stepMinute' => 1,
            'timeFormat' => $event_time_format, //HH:mm 24 Hour format
            //'timeFormat' => "hh:mm tt", //hh:mm tt 12 Hour format
            'showSecond' => false,
            'hourMax' => 23,
            'stepSecond' => 1,
          ),
          'visible' => array('imic_event_all_day', '!=', '1'),
        ),
        array(
          'name' => esc_html__('Event End Time', 'framework'),
          'id' => $prefix . 'event_end_tm',
          'type' => 'time',
          // jQuery datetime picker options. See here http://trentrichardson.com/examples/timepicker/
          'js_options' => array(
            'stepMinute' => 1,
            'timeFormat' => $event_time_format, //HH:mm 24 Hour format
            //'timeFormat' => "hh:mm tt", //hh:mm tt 12 Hour format
            'showSecond' => false,
            'hourMax' => 23,
            'stepSecond' => 1,
          ),
          'visible' => array('imic_event_all_day', '!=', '1'),
        ),
        array(
          'name' => esc_html__('Address', 'framework'),
          'id' => $prefix . "event_address",
          'desc' => __('Enter event\'s address.', 'framework'),
          'type' => 'textarea',
          'cols' => 20,
          'rows' => 3,
        ),
        array(
          'name' => esc_html__('Contact Number', 'framework'),
          'id' => $prefix . "event_contact",
          'desc' => esc_html__('Enter event\'s manager contact number. This is a static value which you might want to show on single event page.', 'framework'),
          'type' => 'text',
        ),
        array(
          'name' => esc_html__('Event Registration', 'framework'),
          'id' => $prefix . 'event_registration_status',
          'desc' => esc_html__("Select Enable to activate Event Registration.", 'framework'),
          'type' => 'select',
          'options' => array(
            '0' => esc_html__('Disable', 'framework'),
            '1' => esc_html__('Enable', 'framework'),
          ),
          'std' => 0,
        ),
        array(
          'name' => esc_html__('Event Registration Fee', 'framework'),
          'id' => $prefix . "event_registration_fee",
          'desc' => esc_html__('Enter event\'s registration fee(This field will work only when imithemes payment plugin is active and above option event registration is enabled.) For multiple type tickets use the other metabox "Event Tickets Type" below and leave this field empty.', 'framework'),
          'type' => 'text',
          'visible' => array('imic_event_registration_status', '=', '1'),
        ),
        array(
          'name' => esc_html__('Guest Registration', 'framework'),
          'id' => $prefix . 'event_registration_required',
          'desc' => esc_html__("Select enable to activate guest registration(When enabled it will not be mandatory for users to register on your website to be able to register for an event). Works only when above option event registration is enabled.", 'framework'),
          'type' => 'select',
          'options' => array(
            '0' => esc_html__('Disable', 'framework'),
            '1' => esc_html__('Enable', 'framework'),
          ),
          'std' => 0,
          'visible' => array('imic_event_registration_status', '=', '1'),
        ),
        array(
          'name' => esc_html__('Custom Registration Button URL', 'framework'),
          'id' => $prefix . 'custom_event_registration',
          'desc' => esc_html__("For example EventBrite event page URL of yours. This URL will be used for the registration button on single event page when the above option event registration is enabled", 'framework'),
          'type' => 'text',
          'visible' => array('imic_event_registration_status', '=', '1'),
        ),
        array(
          'name' => esc_html__('Open custom URL in new Tab/Window', 'framework'),
          'id' => $prefix . 'custom_event_registration_target',
          'type' => 'checkbox',
          // Value can be 0 or 1
          'std' => 1,
          'visible' => array('imic_event_registration_status', '=', '1'),
        ),
      ),
    );
    /*** Event Recurrence Meta box ***/
    $meta_boxes[] = array(
      'id' => 'event_recurring_box',
      'title' => esc_html__('Recurring Options', 'framework'),
      'pages' => array('event'),
      'fields' => array(
        //Frequency of Event
        array(
          'name' => esc_html__('Event Frequency', 'framework'),
          'id' => $prefix . 'event_frequency',
          'desc' => esc_html__("Select Frequency.", 'framework'),
          'type' => 'select',
          'options' => array(
            '0' => esc_html__('Not Required', 'framework'),
            '1' => esc_html__('Every Day', 'framework'),
            '2' => esc_html__('Every Second Day', 'framework'),
            '3' => esc_html__('Every Third Day', 'framework'),
            '4' => esc_html__('Every Fourth Day', 'framework'),
            '5' => esc_html__('Every Fifth Day', 'framework'),
            '6' => esc_html__('Every Sixth Day', 'framework'),
            '7' => esc_html__('Every Week', 'framework'),
            '30' => esc_html__('Every Month', 'framework'),
            '35' => esc_html__('More Options', 'framework'),
            '32' => esc_html__('Multiple Dates Options', 'framework'),
          ),
        ),
        array(
          'name' => esc_html__('Event Week Day', 'framework'),
          'id' => $prefix . 'event_week_day',
          'desc' => esc_html__("Select Week Day.", 'framework'),
          'type' => 'select',
          'options' => array(
            'sunday' => esc_html__('Sunday', 'framework'),
            'monday' => esc_html__('Monday', 'framework'),
            'tuesday' => esc_html__('Tuesday', 'framework'),
            'wednesday' => esc_html__('Wednesday', 'framework'),
            'thursday' => esc_html__('Thursday', 'framework'),
            'friday' => esc_html__('Friday', 'framework'),
            'saturday' => esc_html__('Saturday', 'framework'),
          ),
          'visible' => array('imic_event_frequency', '=', '35'),
        ),
        array(
          'name' => esc_html__('Day of Month', 'framework'),
          'id' => $prefix . 'event_day_month',
          'desc' => esc_html__("Select Day of Month.", 'framework'),
          'type' => 'select',
          'options' => array(
            'first' => esc_html__('First', 'framework'),
            'second' => esc_html__('Second', 'framework'),
            'third' => esc_html__('Third', 'framework'),
            'fourth' => esc_html__('Fourth', 'framework'),
            'last' => esc_html__('Last', 'framework'),
          ),
          'visible' => array('imic_event_frequency', '=', '35'),
        ),
        array(
          'name' => esc_html__('Event Multiple Recurring Date', 'framework'),
          'id' => $prefix . 'event_recurring_dt',
          'desc' => esc_html__("Insert multiple dates for recurring event, this will work only with single day event.", 'framework'),
          'type' => 'date',
          'clone' => true,
          'js_options' => array(
            'dateFormat' => 'yy-mm-dd',
            'changeMonth' => true,
            'changeYear' => true,
            'showButtonPanel' => true,
          ),
          'visible' => array('imic_event_frequency', '=', '32'),
        ),
        array(
          'name' => esc_html__('Number of times to repeat event', 'framework'),
          'id' => $prefix . 'event_frequency_count',
          'desc' => esc_html__("Enter the number of how many time this event should repeat. max number of times an event can repeat is 999, events which have different start and end date can not be set for recurrence. DOn not enter anything in this field if setting multiple recurring dates.", 'framework'),
          'type' => 'text',
          'visible' => array('imic_event_frequency', '!=', '0'),
        ),
        array(
          'name' => esc_html__('Do not change', 'framework'),
          'id' => $prefix . 'event_frequency_end',
          'desc' => esc_html__("If any changes done in this file, may your theme will not work like running now.", 'framework'),
          'type' => 'hidden',
        ),
      ),
    );
    /*** Total Persons Details Meta box ***/
    $meta_boxes[] = array(
      'id' => 'event_person_meta_box',
      'title' => esc_html__('Attendess & Contact Details', 'framework'),
      'pages' => array('event'),
      'fields' => array(
        //Attendees
        array(
          'name' => esc_html__('Attendees', 'framework'),
          'id' => $prefix . "event_attendees",
          'desc' => esc_html__('Enter number of attendees. This is a static value which you might want to show on single event page.', 'framework'),
          'type' => 'text',
        ),
        //Staff Members
        array(
          'name' => esc_html__('Staff Members', 'framework'),
          'id' => $prefix . "event_staff_members",
          'desc' => esc_html__('Enter number of staff members. This is a static value which you might want to show on single event page.', 'framework'),
          'type' => 'text',
        ),
        array(
          'name' => esc_html__('Email Address', 'framework'),
          'id' => $prefix . "event_email",
          'desc' => esc_html__('Enter Email for Event. This email address is where theme will send event registrants info and can be used by event page visitors to contact directly', 'framework'),
          'type' => 'text',
        ),
      ),
    );
    /*** Featured Event Meta box ***/
    $meta_boxes[] = array(
      'id' => 'featured_event_meta_box',
      'title' => esc_html__('Featured Event', 'framework'),
      'pages' => array('event'),
      'fields' => array(
        //Attendees
        array(
          'name' => esc_html__('Featured Event', 'framework'),
          'id' => $prefix . "event_featured",
          'desc' => esc_html__('Select for featured event. If this is set to Yes then this event will be available as an option to select for featured event Widget at Appearance > Widgets', 'framework'),
          'type' => 'select',
          'options' => array(
            '0' => esc_html__('No', 'framework'),
            '1' => esc_html__('Yes', 'framework'),
          ),
        ),
      ),
    );
  }
  /* Gallery Meta Box
    ================================================== */
  $meta_boxes[] = array(
    'id' => 'gallery_meta_box',
    'title' => esc_html__('Post Media', 'framework'),
    'pages' => array('gallery'),
    'fields' => array(
      array(
        'name' => esc_html__('Video URL', 'framework'),
        'id' => $prefix . 'gallery_video_url',
        'desc' => esc_html__("Enter the video URL.", 'framework'),
        'type' => 'url',
        'visible' => ['post_format', 'video'],
      ),
      array(
        'name' => esc_html__('Link', 'framework'),
        'id' => $prefix . 'gallery_link_url',
        'desc' => esc_html__("Enter the URL for link gallery type post.", 'framework'),
        'type' => 'url',
        'visible' => ['post_format', 'link'],
      ),
      array(
        'name' => esc_html__('Gallery Images', 'framework'),
        'id' => $prefix . 'gallery_images',
        'desc' => esc_html__("Choose/Upload gallery images.", 'framework'),
        'type' => 'image_advanced',
        'visible' => ['post_format', 'gallery'],
      ),
      array(
        'name' => esc_html__('Slider Images', 'framework'),
        'id' => $prefix . 'gallery_slider_image',
        'desc' => esc_html__("Enter slider images.", 'framework'),
        'type' => 'hidden',
        'visible' => ['post_format', 'fh'],
      ),
      array(
        'name' => esc_html__('Slider Pagination', 'framework'),
        'id' => $prefix . 'gallery_slider_pagination',
        'desc' => esc_html__("Enable to show pagination for slider.", 'framework'),
        'type' => 'select',
        'options' => array(
          'yes' => esc_html__('Enable', 'framework'),
          'no' => esc_html__('Disable', 'framework'),
        ),
        'visible' => ['post_format', 'gallery'],
      ),
      array(
        'name' => esc_html__('Slider Autoplay', 'framework'),
        'id' => $prefix . 'gallery_slider_auto_slide',
        'desc' => esc_html__("Select Yes to slide automatically.", 'framework'),
        'type' => 'select',
        'options' => array(
          'yes' => esc_html__('Yes', 'framework'),
          'no' => esc_html__('No', 'framework'),
        ),
        'visible' => ['post_format', 'gallery'],
      ),
      array(
        'name' => esc_html__('Auto slide interval', 'framework'),
        'id' => $prefix . 'gallery_slider_speed',
        'desc' => esc_html__("Default per slide interval is 5 seconds. You can change it to anything you like. 1000 is equals to 1 second.", 'framework'),
        'type' => 'text',
        'visible' => array('imic_gallery_slider_auto_slide', '!=', 'no'),
      ),
      array(
        'name' => esc_html__('Slider Direction Arrows', 'framework'),
        'id' => $prefix . 'gallery_slider_direction_arrows',
        'desc' => esc_html__("Select Yes to show slider direction arrows.", 'framework'),
        'type' => 'select',
        'options' => array(
          'yes' => esc_html__('Yes', 'framework'),
          'no' => esc_html__('No', 'framework'),
        ),
        'visible' => ['post_format', 'gallery'],
      ),
      array(
        'name' => esc_html__('Slider Effects', 'framework'),
        'id' => $prefix . 'gallery_slider_effects',
        'desc' => esc_html__("Select effect for slider.", 'framework'),
        'type' => 'select',
        'options' => array(
          'fade' => esc_html__('Fade', 'framework'),
          'slide' => esc_html__('Slide', 'framework'),
        ),
        'visible' => ['post_format', 'gallery'],
      ),
      array(
        'name' => esc_html__('Audio Display', 'framework'),
        'id' => $prefix . 'gallery_audio_display',
        'desc' => esc_html__("Select audio type.", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('By Soundcloud', 'framework'),
          '2' => esc_html__('By Upload', 'framework'),
        ),
        'visible' => ['post_format', 'audio'],
      ),
      array(
        'name' => esc_html__('SoundCloud Track', 'framework'),
        'id' => $prefix . 'gallery_audio',
        'desc' => esc_html__("Enter SoundCloud iframe code to show on post.", 'framework'),
        'type' => 'textarea',
        'std' => '',
        'visible' => array('imic_gallery_audio_display', '!=', '2'),
      ),
      array(
        'name' => esc_html__('Audio', 'framework'),
        'id' => $prefix . 'gallery_uploaded_audio',
        'desc' => esc_html__("Upload audio.", 'framework'),
        'type' => 'file_advanced',
        'max_file_uploads' => 1,
        'visible' => array('imic_gallery_audio_display', '!=', '1'),
      ),
    ),
  );
  //Page Design Meta Box
  $meta_boxes[] = array(
    'title'       => esc_html__('Page Design Options', 'framework'),
    'tabs'      => array(
      'layout'    => array(
        'label' => esc_html__('Layout', 'framework'),
      ),
      'header'    => array(
        'label' => esc_html__('Header', 'framework'),
      ),
      'page_header'  => array(
        'label' => esc_html__('Page Header', 'framework'),
      ),
      'page_title'  => array(
        'label' => esc_html__('Page Title', 'framework'),
      ),
      'page_content' => array(
        'label' => esc_html__('Content', 'framework'),
      ),
      'sidebar' => array(
        'label' => esc_html__('Sidebar', 'framework'),
      ),
      'social_share'    => array(
        'label' => esc_html__('Social Sharing', 'framework'),
      ),
    ),
    // Tab style: 'default', 'box' or 'left'. Optional
    'tab_style' => 'left',
    // Show meta box wrapper around tabs? true (default) or false. Optional
    'tab_wrapper' => true,
    'pages' => array('post', 'page', 'event', 'sermons', 'staff', 'product', 'causes'),
    'fields'    => array(
      array(
        'name' => esc_html__('Show no header', 'framework'),
        'id' => $prefix . 'strict_no_header',
        'desc' => esc_html__("This will dishonour site header chosen at Theme Options as well.", 'framework'),
        'type' => 'checkbox',
        'default' => 0,
        'tab'  => 'header',
      ),
      array(
        'name' => esc_html__('Topbar Show/Hide', 'framework'),
        'id' => $prefix . 'page_topbar_show',
        'type' => 'select',
        'tab'  => 'header',
        'options' => array(
          '' => esc_html__('Theme Default', 'framework'),
          1 => esc_html__('Show', 'framework'),
          0 => esc_html__('Hide', 'framework'),
        ),
      ),
      array(
        'name' => esc_html__('Main Header', 'framework'),
        'id' => $prefix . 'page_specific_header',
        'type' => 'select',
        'options' => array(
          '' => esc_html__('Theme Default', 'framework'),
          '1' => esc_html__('Style 1', 'framework'),
          '2' => esc_html__('Style 2', 'framework'),
          '3' => esc_html__('Style 3', 'framework'),
          '4' => esc_html__('Style 4', 'framework'),
          '5' => esc_html__('Style 5', 'framework'),
          '6' => esc_html__('Style 6', 'framework'),
        ),
        'tab'  => 'header',
        'hidden' => array('imic_strict_no_header', '=', '1'),
      ),
      array(
        'name' => esc_html__('Logo', 'framework'),
        'id' => $prefix . 'page_logo_image',
        'desc' => esc_html__("Choose an image to use it as this page's logo", 'framework'),
        'type' => 'image_advanced',
        'tab'  => 'header',
        'hidden' => array('imic_strict_no_header', '=', '1'),
        'max_file_uploads' => 1
      ),
      array(
        'name' => esc_html__('Retina Logo', 'framework'),
        'id' => $prefix . 'page_retina_logo_image',
        'desc' => esc_html__("Choose an image to use it as this page's retina logo", 'framework'),
        'type' => 'image_advanced',
        'tab'  => 'header',
        'hidden' => array('imic_strict_no_header', '=', '1'),
        'max_file_uploads' => 1
      ),
      array(
        'name' => esc_html__('Retina Logo Width', 'framework'),
        'desc' => esc_html__("Enter width of the standard logo (1x) version, do not enter the retina logo image width", 'framework'),
        'id' => $prefix . 'page_retina_logo_image_width',
        'type' => 'text',
        'tab'  => 'header',
        'hidden' => array('imic_strict_no_header', '=', '1'),
      ),
      array(
        'name' => esc_html__('Retina Logo Height', 'framework'),
        'desc' => esc_html__("Enter height of the standard logo (1x) version, do not enter the retina logo image height", 'framework'),
        'id' => $prefix . 'page_retina_logo_image_height',
        'type' => 'text',
        'tab'  => 'header',
        'hidden' => array('imic_strict_no_header', '=', '1'),
      ),
      array(
        'name' => esc_html__('Page layout', 'framework'),
        'id' => $prefix . 'page_layout',
        'desc' => esc_html__("Select layout for the page.", 'framework'),
        'type' => 'select',
        'tab'  => 'layout',
        'options' => array(
          '' => esc_html__('Theme Default', 'framework'),
          'full-width-page' => esc_html__('Full Width', 'framework'),
          'boxed' => esc_html__('Boxed', 'framework'),
        )
      ),
      array(
        'name' => esc_html__('Content Width', 'framework'),
        'desc' => esc_html__("Enter width of content in px or %", 'framework'),
        'id' => $prefix . 'content_width',
        'type' => 'text',
        'tab'  => 'page_content',
      ),
      array(
        'name' => esc_html__('Content Spacing Top', 'framework'),
        'desc' => esc_html__("Enter padding from top in the content area. Enter in px/%/rem", 'framework'),
        'id' => $prefix . 'content_padding_top',
        'type' => 'text',
        'tab'  => 'page_content',
      ),
      array(
        'name' => esc_html__('Content Spacing Bottom', 'framework'),
        'desc' => esc_html__("Enter padding from top in the content area. Enter in px/%/rem", 'framework'),
        'id' => $prefix . 'content_padding_bottom',
        'type' => 'text',
        'tab'  => 'page_content',
      ),
      array(
        'name' => esc_html__('Background Color', 'framework'),
        'id' => $prefix . 'pages_body_bg_color',
        'desc' => esc_html__("Choose background color for the outer area", 'framework'),
        'type' => 'color',
        'tab'  => 'layout',
        'visible' => array('page_layout', '=', 'boxed')
      ),
      array(
        'name' => esc_html__('Background Image', 'framework'),
        'id' => $prefix . 'pages_body_bg_image',
        'desc' => esc_html__("Choose background image for the outer area", 'framework'),
        'type' => 'image_advanced',
        'tab'  => 'layout',
        'max_file_uploads' => 1,
        'visible' => array('page_layout', '=', 'boxed')
      ),
      array(
        'name' => esc_html__('100% Background Image', 'framework'),
        'id' => $prefix . 'pages_body_bg_wide',
        'desc' => esc_html__("Choose to have the background image display at 100%.", 'framework'),
        'type' => 'select',
        'tab'  => 'layout',
        'options' => array(
          '1' => esc_html__('Yes', 'framework'),
          '0' => esc_html__('No', 'framework'),
        ),
        'std' => 0,
        'visible' => array('page_layout', '=', 'boxed')
      ),
      array(
        'name' => esc_html__('Background Repeat', 'framework'),
        'id' => $prefix . 'pages_body_bg_repeat',
        'desc' => esc_html__("Select how the background image repeats.", 'framework'),
        'type' => 'select',
        'tab'  => 'layout',
        'options' => array(
          'repeat' => esc_html__('Repeat', 'framework'),
          'repeat-x' => esc_html__('Repeat Horizontally', 'framework'),
          'repeat-y' => esc_html__('Repeat Vertically', 'framework'),
          'no-repeat' => esc_html__('No Repeat', 'framework'),
        ),
        'std' => 'repeat',
        'visible' => array('page_layout', '=', 'boxed')
      ),
      array(
        'name' => esc_html__('Background Color', 'framework'),
        'id' => $prefix . 'pages_content_bg_color',
        'desc' => esc_html__("Choose background color for the Content area", 'framework'),
        'type' => 'color',
        'tab'  => 'page_content',
      ),
      array(
        'name' => esc_html__('Background Image', 'framework'),
        'id' => $prefix . 'pages_content_bg_image',
        'desc' => esc_html__("Choose background image for the Content area", 'framework'),
        'type' => 'image_advanced',
        'tab'  => 'page_content',
        'max_file_uploads' => 1
      ),
      array(
        'name' => esc_html__('100% Background Image', 'framework'),
        'id' => $prefix . 'pages_content_bg_wide',
        'desc' => esc_html__("Choose to have the background image display at 100%.", 'framework'),
        'type' => 'select',
        'tab'  => 'page_content',
        'options' => array(
          1 => esc_html__('Yes', 'framework'),
          0 => esc_html__('No', 'framework'),
        ),
        'std' => 0,
      ),
      array(
        'name' => esc_html__('Background Repeat', 'framework'),
        'id' => $prefix . 'pages_content_bg_repeat',
        'desc' => esc_html__("Select how the background image repeats.", 'framework'),
        'type' => 'select',
        'tab'  => 'page_content',
        'options' => array(
          'repeat' => esc_html__('Repeat', 'framework'),
          'repeat-x' => esc_html__('Repeat Horizontally', 'framework'),
          'repeat-y' => esc_html__('Repeat Vertically', 'framework'),
          'no-repeat' => esc_html__('No Repeat', 'framework'),
        ),
        'std' => 'repeat',
      ),
      array(
        'name' => esc_html__('Page Header Show/Hide', 'framework'),
        'id' => $prefix . 'page_header_show',
        'type' => 'select',
        'tab'  => 'page_header',
        'options' => array(
          1 => esc_html__('Show', 'framework'),
          0 => esc_html__('Hide', 'framework'),
        ),
        'std' => 1,
      ),
      array(
        'name' => esc_html__('Header Type', 'framework'),
        'id' => $prefix . 'pages_Choose_slider_display',
        'desc' => esc_html__("Select header type", 'framework'),
        'type' => 'select',
        'tab'  => 'page_header',
        'options' => array(
          '0' => esc_html__('Image Banner', 'framework'),
          '1' => esc_html__('Flex Slider', 'framework'),
          '2' => esc_html__('Revolution Slider', 'framework'),
          '4' => esc_html__('Smart Slider', 'framework'),
          '3' => esc_html__('Color Banner', 'framework'),
        ),
      ),
      array(
        'name' => esc_html__('Banner Image', 'framework'),
        'id' => $prefix . 'header_image',
        'desc' => esc_html__("Upload banner image for header for this Page/Post.", 'framework'),
        'type' => 'image_advanced',
        'tab'  => 'page_header',
        'max_file_uploads' => 1,
        'hidden' => array('imic_pages_Choose_slider_display', '!=', '0'),
      ),
      array(
        'name' => esc_html__('Select Revolution Slider', 'framework'),
        'id' => $prefix . 'pages_select_revolution_from_list',
        'desc' => esc_html__("Select Revolution Slider from the list", 'framework'),
        'type' => 'select',
        'tab'  => 'page_header',
        'options' => RevSliderShortCode(),
        'hidden' => array('imic_pages_Choose_slider_display', '!=', '2'),
      ),
      array(
        'name' => esc_html__('Select Smart Slider', 'framework'),
        'id' => $prefix . 'pages_select_smart_from_list',
        'desc' => esc_html__("Select Smart Slider from the list", 'framework'),
        'type' => 'select',
        'tab'  => 'page_header',
        'options' => SmartSliderShortCode(),
        'hidden' => array('imic_pages_Choose_slider_display', '!=', '4'),
      ),
      array(
        'name' => esc_html__('Header height in px', 'framework'),
        'id' => $prefix . 'pages_slider_height',
        'desc' => esc_html__("Default height is 150px.", 'framework'),
        'type' => 'text',
        'tab'  => 'page_header',
        'default' => '150',
        'visible' => array('imic_pages_Choose_slider_display', 'in', ['0', '1', '3']),
      ),
      array(
        'name' => esc_html__('Slider Images', 'framework'),
        'id' => $prefix . 'pages_slider_image',
        'desc' => esc_html__("Upload/Choose slider images.", 'framework'),
        'type' => 'image_advanced',
        'hidden' => array('imic_pages_Choose_slider_display', '!=', '1'),
        'tab'  => 'page_header',
      ),
      array(
        'name' => esc_html__('Slider Autoplay', 'framework'),
        'id' => $prefix . 'pages_slider_auto_slide',
        'desc' => esc_html__("Select Yes to slide automatically.", 'framework'),
        'type' => 'select',
        'tab'  => 'page_header',
        'options' => array(
          'yes' => esc_html__('Yes', 'framework'),
          'no' => esc_html__('No', 'framework'),
        ),
        'hidden' => array('imic_pages_Choose_slider_display', '!=', '1'),
      ),
      array(
        'name' => esc_html__('Auto slide interval', 'framework'),
        'id' => $prefix . 'pages_slider_speed',
        'desc' => esc_html__("Default per slide interval is 5 seconds. You can change it to anything you like. 1000 is equals to 1 second.", 'framework'),
        'type' => 'text',
        'tab'  => 'page_header',
        'visible' => array('imic_pages_slider_auto_slide', '=', 'yes'),
      ),
      array(
        'name' => esc_html__('Slider Pagination', 'framework'),
        'id' => $prefix . 'pages_slider_pagination',
        'desc' => esc_html__("Select Enable to show pagination for slider.", 'framework'),
        'type' => 'select',
        'tab'  => 'page_header',
        'options' => array(
          'yes' => esc_html__('Enable', 'framework'),
          'no' => esc_html__('Disable', 'framework'),
        ),
        'hidden' => array('imic_pages_Choose_slider_display', '!=', '1'),
      ),
      array(
        'name' => esc_html__('Slider Direction Arrows', 'framework'),
        'id' => $prefix . 'pages_slider_direction_arrows',
        'desc' => esc_html__("Select Yes to show slider direction arrows.", 'framework'),
        'type' => 'select',
        'tab'  => 'page_header',
        'options' => array(
          'yes' => esc_html__('Yes', 'framework'),
          'no' => esc_html__('No', 'framework'),
        ),
        'hidden' => array('imic_pages_Choose_slider_display', '!=', '1'),
      ),
      array(
        'name' => esc_html__('Slider Effects', 'framework'),
        'id' => $prefix . 'pages_slider_effects',
        'desc' => esc_html__("Select effects for slider.", 'framework'),
        'type' => 'select',
        'tab'  => 'page_header',
        'options' => array(
          'fade' => esc_html__('Fade', 'framework'),
          'slide' => esc_html__('Slide', 'framework'),
        ),
        'hidden' => array('imic_pages_Choose_slider_display', '!=', '1'),
      ),
      array(
        'name' => esc_html__('Banner Color', 'framework'),
        'id' => $prefix . 'pages_banner_color',
        'type' => 'color',
        'tab'  => 'page_header',
        'hidden' => array('imic_pages_Choose_slider_display', '!=', '3'),
      ),
      array(
        'name' => esc_html__('Breadcrumb Show/Hide', 'framework'),
        'id' => $prefix . 'pages_breadcrumb_show',
        'type' => 'select',
        'tab'  => 'page_header',
        'options' => array(
          1 => esc_html__('Show', 'framework'),
          0 => esc_html__('Hide', 'framework'),
        ),
        'std' => 1,
      ),
      array(
        'name' => esc_html__('Page Title Show/Hide', 'framework'),
        'id' => $prefix . 'pages_title_show',
        'type' => 'select',
        'tab'  => 'page_title',
        'options' => array(
          1 => esc_html__('Show', 'framework'),
          0 => esc_html__('Hide', 'framework'),
        ),
        'std' => 1,
      ),
      array(
        'name' => esc_html__('Page Header Background Color', 'framework'),
        'id' => $prefix . 'pages_banner_bg_color',
        'desc' => esc_html__("Select background color for the page title bar.", 'framework'),
        'type' => 'color',
        'tab'  => 'page_title'
      ),
      // Custom title
      array(
        'name' => esc_html__('Custom Title', 'framework'),
        'id' => $prefix . 'post_page_custom_title',
        'desc' => esc_html__("Enter custom title for the page.", 'framework'),
        'type' => 'text',
        'tab'  => 'page_title'
      ),
      array(
        'name' => esc_html__('Page Title Text Color', 'framework'),
        'id' => $prefix . 'pages_banner_text_color',
        'desc' => esc_html__("Select banner text color.", 'framework'),
        'type' => 'color',
        'tab'  => 'page_title'
      ),
      array(
        'name' => esc_html__('Page Title Alignment', 'framework'),
        'id' => $prefix . 'pages_title_alignment',
        'desc' => esc_html__("Choose aligment of the page title.", 'framework'),
        'type' => 'select',
        'tab'  => 'page_title',
        'options' => array(
          '' => esc_html__('Theme Default', 'framework'),
          'left' => esc_html__('Left', 'framework'),
          'center' => esc_html__('Center', 'framework'),
          'right' => esc_html__('Right', 'framework'),
        ),
      ),
      array(
        'name' => esc_html__('Show social sharing buttons', 'framework'),
        'id' => $prefix . 'page_social_share',
        'type' => 'select',
        'tab'  => 'social_share',
        'options' => array(
          '1' => esc_html__('Show', 'framework'),
          '0' => esc_html__('Hide', 'framework'),
        ),
        'std' => 1,
      ),
      array(
        'name' => esc_html__('Select Sidebar from list', 'framework'),
        'id' => $prefix . 'select_sidebar_from_list',
        'desc' => esc_html__("Select Sidebar from list, if using page builder then please add sidebar from element only.", 'framework'),
        'type' => 'sidebar',
        'field_type'  => 'select_advanced',
        'tab'  => 'sidebar',
      ),
      array(
        'name' => esc_html__('Select Sidebar Position', 'framework'),
        'id' => $prefix . 'select_sidebar_position',
        'desc' => esc_html__("Select Sidebar Postion", 'framework'),
        'type' => 'radio',
        'options' => array(
          '1' => esc_html__('Right', 'framework'),
          '2' => esc_html__('Left', 'framework'),
        ),
        'default' => '1',
        'tab'  => 'sidebar',
      ),
    )
  );
  /* Post Meta Box
    ================================================== */
  $meta_boxes[] = array(
    'id' => 'post_meta_box',
    'title' => esc_html__('Custom Description', 'framework'),
    'pages' => array('post'),
    'fields' => array(
      // Custom Description
      array(
        'name' => esc_html__('Custom Description', 'framework'),
        'id' => $prefix . 'post_custom_description',
        'desc' => esc_html__("Enter custom description for the post that is shown on the right of page title.", 'framework'),
        'type' => 'textarea',
      ),
    ),
  );
  /* Sermon Meta Box
    ================================================== */
  $meta_boxes[] = array(
    'id' => 'sermons_meta_box',
    'title' => esc_html__('Sermon Media', 'framework'),
    'pages' => array('sermons'),
    'fields' => array(
      array(
        'type' => 'heading',
        'name' => esc_html__('Audio for Sermon', 'framework'),
        'id' => 'heading_id3',
        'desc' => esc_html__('Add audio to your sermons either by direct URL or Upload here', 'framework'),
      ),
      array(
        'name' => esc_html__('Upload Audio', 'framework'),
        'id' => $prefix . 'sermons_audio_upload',
        'desc' => esc_html__("Select your audio source. By URL will be requiring you to put a valid MP3 audio file URL.", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('By Upload', 'framework'),
          '2' => esc_html__('By URL', 'framework'),
        ),
      ),
      array(
        'name' => esc_html__('Audio', 'framework'),
        'id' => $prefix . 'sermons_audio',
        'desc' => esc_html__("Upload .mp3 file format.", 'framework'),
        'type' => 'file_advanced',
        'max_file_uploads' => 1,
        'hidden' => array('imic_sermons_audio_upload', '=', '2'),
      ),
      array(
        'name' => esc_html__('Audio URL', 'framework'),
        'id' => $prefix . 'sermons_url_audio',
        'desc' => esc_html__("Enter a valid mp3 audio file URL ending with .mp3 extension", 'framework'),
        'type' => 'url',
        'hidden' => array('imic_sermons_audio_upload', '=', '1'),

      ),
      array(
        'name' => esc_html__('No. of plays', 'framework'),
        'id' => 'imi_play_record',
        'desc' => esc_html__("Number of times this audio has been played.", 'framework'),
        'type' => 'text',
        'hidden' => array('imic_sermons_audio_upload', '=', '2'),
		 'admin_columns' => 'after title'

      ),
      array(
        'type' => 'heading',
        'name' => esc_html__('Video for Sermon', 'framework'),
        'id' => 'heading_id2',
      ),
      array(
        'name' => esc_html__('Sermon Video', 'framework'),
        'id' => $prefix . 'sermons_video_upload_option',
        'desc' => esc_html__("Select video source.", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('By Vimeo/Youtube URL', 'framework'),
          '2' => esc_html__('Custom Video', 'framework'),
        ),
      ),
      array(
        'name' => esc_html__('Sermon Video .mp4', 'framework'),
        'id' => $prefix . 'sermons_video_mp4',
        'desc' => esc_html__("This is mandatory for custom video. MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7", 'framework'),
        'type' => 'file_input',
        'max_file_uploads' => 1,
        'hidden' => array('imic_sermons_video_upload_option', '=', '1'),
      ),
      array(
        'name' => esc_html__('Sermon Video .webm', 'framework'),
        'id' => $prefix . 'sermons_video_webm',
        'desc' => esc_html__("WebM/VP8 for Firefox4, Opera, and Chrome", 'framework'),
        'type' => 'file_input',
        'max_file_uploads' => 1,
        'hidden' => array('imic_sermons_video_upload_option', '=', '1'),
      ),
      array(
        'name' => esc_html__('Sermon Video .ogv', 'framework'),
        'id' => $prefix . 'sermons_video_ogv',
        'desc' => esc_html__("Ogg/Vorbis for older Firefox and Opera versions", 'framework'),
        'type' => 'file_input',
        'max_file_uploads' => 1,
        'hidden' => array('imic_sermons_video_upload_option', '=', '1'),
      ),
      array(
        'name' => esc_html__('Sermon Video Poster Image', 'framework'),
        'id' => $prefix . 'sermons_video_poster',
        'desc' => esc_html__("An image which will appear as a poster prior to video start playing.", 'framework'),
        'type' => 'file_input',
        'max_file_uploads' => 1,
        'hidden' => array('imic_sermons_video_upload_option', '=', '1'),
      ),
      array(
        'name' => esc_html__('Sermon URL', 'framework'),
        'id' => $prefix . 'sermons_url',
        'desc' => esc_html__("Enter vimeo/youtube URL for sermon.", 'framework'),
        'type' => 'url',
        'hidden' => array('imic_sermons_video_upload_option', '=', '2'),
      ),
      array(
        'type' => 'heading',
        'name' => esc_html__('PDF for Sermon', 'framework'),
        'id' => 'heading_id1',
      ),
      //Pdf
      array(
        'name' => esc_html__('Upload PDF', 'framework'),
        'id' => $prefix . 'sermons_pdf_upload_option',
        'desc' => esc_html__("Select PDF file SOURCE.", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('By Upload', 'framework'),
          '2' => esc_html__('By URL', 'framework'),
        ),
      ),
      // Upload Pdf
      array(
        'name' => esc_html__('Upload PDF', 'framework'),
        'id' => $prefix . 'sermons_Pdf',
        'desc' => esc_html__("Upload PDF for sermon.", 'framework'),
        'type' => 'file_advanced',
        'max_file_uploads' => 1,
        'hidden' => array('imic_sermons_pdf_upload_option', '=', '2'),
      ),
      // Upload Pdf by url
      array(
        'name' => esc_html__('PDF URL', 'framework'),
        'id' => $prefix . 'sermons_pdf_by_url',
        'desc' => esc_html__("Enter PDF URL for sermon.", 'framework'),
        'type' => 'url',
        'hidden' => array('imic_sermons_pdf_upload_option', '=', '1'),

      ),
      array(
        'type' => 'heading',
        'name' => esc_html__('Additional Media Attachments', 'framework'),
        'desc' => esc_html__('These media items will be displayed on single sermon page', 'framework'),
        'id' => 'heading_id4',
      ),
      // ADDITIONAL VIMEO VIDEO
      array(
        'name' => esc_html__('Additional Vimeo Video', 'framework'),
        'id' => $prefix . 'sermons_add_vimeo_url',
        'desc' => esc_html__("Enter Vimeo video URL", 'framework'),
        'type' => 'url',

      ),
      // ADDITIONAL YOUTUBE VIDEO
      array(
        'name' => esc_html__('Additional Youtube Video', 'framework'),
        'id' => $prefix . 'sermons_add_youtube_url',
        'desc' => esc_html__("Enter Youtube video URL", 'framework'),
        'type' => 'url',

      ),
      // ADDITIONAL SOUNDCLOUD AUDIO
      array(
        'name' => esc_html__('Additional Soundcloud Audio', 'framework'),
        'id' => $prefix . 'sermons_add_soundcloud_url',
        'desc' => esc_html__("Enter Soundcloud audio URL", 'framework'),
        'type' => 'url',

      ),
      // ADDITIONAL VIDEO MP4
      array(
        'name' => esc_html__('Additional Sermon Video .mp4', 'framework'),
        'id' => $prefix . 'sermons_add_video_mp4',
        'desc' => esc_html__("This is mandatory for custom video. MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7", 'framework'),
        'type' => 'file_input',
        'max_file_uploads' => 1,
      ),
      // ADDITIONAL VIDEO WebM
      array(
        'name' => esc_html__('Additional Sermon Video .webm', 'framework'),
        'id' => $prefix . 'sermons_add_video_webm',
        'desc' => esc_html__("WebM/VP8 for Firefox4, Opera, and Chrome", 'framework'),
        'type' => 'file_input',
        'max_file_uploads' => 1,
      ),
      // ADDITIONAL VIDEO OGV
      array(
        'name' => esc_html__('Additional Sermon Video .ogv', 'framework'),
        'id' => $prefix . 'sermons_add_video_ogv',
        'desc' => esc_html__("Ogg/Vorbis for older Firefox and Opera versions", 'framework'),
        'type' => 'file_input',
        'max_file_uploads' => 1,
      ),
      // ADDITIONAL VIDEO POSTER
      array(
        'name' => esc_html__('Additional Sermon Video Poster Image', 'framework'),
        'id' => $prefix . 'sermons_add_video_poster',
        'desc' => esc_html__("An image which will appear as a poster prior to video play start.", 'framework'),
        'type' => 'file_input',
        'max_file_uploads' => 1,
      ),
    ),
  );
  /* Sermon Podcast Meta Box
    ================================================== */
  $meta_boxes[] = array(
    'id' => 'sermons_podcast',
    'title' => esc_html__('Sermon Podcast', 'framework'),
    'pages' => array('sermons'),
    'fields' => array(
      array(
        'name' => esc_html__('Sermon audio length', 'framework'),
        'id' => $prefix . 'sermon_duration',
        'desc' => esc_html__("Enter audio length in format hh:mm:ss", 'framework'),
        'type' => 'text',
      ),
      array(
        'name' => esc_html__('Sermon audio file size', 'framework'),
        'id' => $prefix . 'sermon_size',
        'desc' => esc_html__("Enter file size for the uploaded audio file.", 'framework'),
        'type' => 'text',
      ),
      array(
        'name' => esc_html__('Sermon short description', 'framework'),
        'id' => $prefix . 'sermons_podcast_description',
        'desc' => esc_html__("Enter short and sweet description for this sermon to show at podcast players.", 'framework'),
        'type' => 'textarea',
      ),
    ),
  );
  /* * **Contact Page Meta Box 1 *** */
  $meta_boxes[] = array(
    'id' => 'template-contact1',
    'title' => esc_html__('Contact Form', 'framework'),
    'pages' => array('page'),
    'show' => array(
      // With all conditions below, use this logical operator to combine them. Default is 'OR'. Case insensitive. Optional.
      'relation' => 'OR',
      // List of page templates (used for page only). Array. Optional.
      'template' => array('template-contact.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Email', 'framework'),
        'id' => $prefix . 'contact_email',
        'desc' => esc_html__("Enter email address to use in contact form where the forms submissions will be sent. By default admin email will be used.", 'framework'),
        'type' => 'text',
        'std' => get_option('admin_email'),
      ),
      array(
        'name' => esc_html__('Subject', 'framework'),
        'id' => $prefix . 'contact_subject',
        'desc' => esc_html__("Enter subject to use in contact page.", 'framework'),
        'type' => 'textarea',
      ),
    ),
  );
  /* * **Contact Page Meta Box 2 *** */
  $meta_boxes[] = array(
    'id' => 'template-contact2',
    'title' => esc_html__('Contact Details', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-contact.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Our Location Text', 'framework'),
        'id' => $prefix . 'our_location_text',
        'desc' => esc_html__("Enter the our location text to display on contact page.", 'framework'),
        'type' => 'text',
      ),
      array(
        'name' => esc_html__('Map Display', 'framework'),
        'id' => $prefix . 'contact_map_display',
        'desc' => esc_html__("Display Map?", 'framework'),
        'type' => 'select',
        'options' => array(
          'no' => esc_html__('No', 'framework'),
          'yes' => esc_html__('Yes', 'framework'),
        ),
      ),
      array(
        'name' => esc_html__('Map Box Code', 'framework'),
        'id' => $prefix . 'contact_map_box_code',
        'desc' => esc_html__("Enter the map iframe embed code to display on contact page. You can get your embed code from http://maps.google.com/", 'framework'),
        'type' => 'textarea',
        'sanitize_callback' => 'none'
      ),
    ),
  );
  /* * **Home Page Meta Box1 *** */
  $meta_boxes[] = array(
    'id' => 'template-home1',
    'title' => esc_html__('Home Page Header Slider', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'template' => array('template-home.php', 'template-home-pb.php', 'template-h-second.php', 'template-h-third.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'id' => $prefix . 'custom_homepage_message',
        'std' => __('<p style="background-color:red; color:#fff; padding:5px 20px">If you are setting this page as your front page at Settings > Reading then use this metabox options for the page header slider instead of page/post header options.</p>', 'framework'),
        'type' => 'custom_html',
      ),
      array(
        'name' => esc_html__('Choose slider', 'framework'),
        'id' => $prefix . 'Choose_slider_display',
        'desc' => esc_html__("Select slider type for your homepage.", 'framework'),
        'type' => 'select',
        'options' => array(
          '0' => esc_html__('Flex Slider', 'framework'),
          '1' => esc_html__('Revolution Slider', 'framework'),
          '2' => esc_html__('Smart Slider', 'framework'),
        ),
      ),
      array(
        'name' => esc_html__('Select Smart Slider', 'framework'),
        'id' => $prefix . 'select_smart_from_list',
        'desc' => esc_html__("Select Smart Slider from the list", 'framework'),
        'type' => 'select',
        'tab'  => 'page_header',
        'options' => SmartSliderShortCode(),
        'visible' => array('imic_Choose_slider_display', '=', '2'),
      ),
      array(
        'name' => esc_html__("Select Revolution Slider", "framework"),
        'id' => $prefix . 'select_revolution_from_list',
        'desc' => esc_html__("Select Revolution Slider from the list", 'framework'),
        'type' => 'select',
        'options' => RevSliderShortCode(),
        'visible' => array('imic_Choose_slider_display', '=', '1'),
      ),
      array(
        'name' => esc_html__('Slider Images', 'framework'),
        'id' => $prefix . 'slider_image',
        'desc' => esc_html__("Choose/Upload slider images.", 'framework'),
        'type' => 'image_advanced',
        'visible' => array('imic_Choose_slider_display', '=', '0'),
      ),
      array(
        'name' => esc_html__('Slider Autoplay', 'framework'),
        'id' => $prefix . 'slider_auto_slide',
        'desc' => esc_html__("Select Yes to slide automatically.", 'framework'),
        'type' => 'select',
        'options' => array(
          'yes' => esc_html__('Yes', 'framework'),
          'no' => esc_html__('No', 'framework'),
        ),
        'visible' => array('imic_Choose_slider_display', '=', '0'),
      ),
      array(
        'name' => esc_html__('Autoplay slide interval', 'framework'),
        'id' => $prefix . 'slider_speed',
        'desc' => esc_html__("Default per slide interval is 5000. You can change it to anything you like. 1000 is equals to 1 second.", 'framework'),
        'type' => 'text',
        'hidden' => array('imic_slider_auto_slide', '=', 'no'),
      ),
      array(
        'name' => esc_html__('Slider Pagination', 'framework'),
        'id' => $prefix . 'slider_pagination',
        'desc' => esc_html__("Enable to show pagination for slider.", 'framework'),
        'type' => 'select',
        'options' => array(
          'yes' => esc_html__('Enable', 'framework'),
          'no' => esc_html__('Disable', 'framework'),
        ),
        'std' => 'yes',
        'visible' => array('imic_Choose_slider_display', '=', '0'),
      ),
      array(
        'name' => esc_html__('Slider Direction Arrows', 'framework'),
        'id' => $prefix . 'slider_direction_arrows',
        'desc' => esc_html__("Select Yes to show slider direction arrows.", 'framework'),
        'type' => 'select',
        'options' => array(
          'yes' => esc_html__('Yes', 'framework'),
          'no' => esc_html__('No', 'framework'),
        ),
        'visible' => array('imic_Choose_slider_display', '=', '0'),
      ),
      array(
        'name' => esc_html__('Slider Effects', 'framework'),
        'id' => $prefix . 'slider_effects',
        'desc' => esc_html__("Select effect for slider.", 'framework'),
        'type' => 'select',
        'options' => array(
          'fade' => esc_html__('Fade', 'framework'),
          'slide' => esc_html__('Slide', 'framework'),
        ),
        'visible' => array('imic_Choose_slider_display', '=', '0'),
      ),
    ),
  );
  /* * **Home Second Meta Box1 *** */
  $meta_boxes[] = array(
    'id' => 'template-h-second-1',
    'title' => esc_html__('Categories Area', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-h-second.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Switch for categories area', 'framework'),
        'id' => $prefix . 'switch_categories_post',
        'desc' => esc_html__("Select enable or disable to show/hide categories posts area.", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('Enable', 'framework'),
          '2' => esc_html__('Disable', 'framework'),
        ),
        'std' => '1',
      ),
      array(
        'name' => esc_html__('Category show on home page', 'framework'),
        'id' => $prefix . 'category_to_show_on_home',
        'desc' => esc_html__("Choose Category to show  on Home page", 'framework'),
        'clone' => true,
        //'clone-group' => 'imic-clone-group',
        'type' => 'select',
        'options' => imic_get_cat_list(),
      ),
      array(
        'name' => esc_html__('Number of Post', 'framework'),
        'id' => $prefix . 'number_of_post_cat',
        'desc' => esc_html__("Enter number of post", 'framework'),
        'type' => 'text',
        'std' => '',
        'clone' => true,
      ),
    ),
  );
  /* * **Home Page Meta Box6 *** */
  $meta_boxes[] = array(
    'id' => 'template-home6',
    'title' => esc_html__('Select option for Area Under Slider', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-home.php', 'template-home-pb.php', 'template-h-third.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Switch for section under slider', 'framework'),
        'id' => $prefix . 'upcoming_area',
        'desc' => esc_html__("Select enable or disable to show/hide Event/Sermon/Contents under slider.", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('Enable', 'framework'),
          '2' => esc_html__('Disable', 'framework'),
        ),
        'std' => '1',
      ),
      array(
        'name' => esc_html__('Recent Event/Sermon', 'framework'),
        'id' => $prefix . 'latest_sermon_events_to_show_on',
        'desc' => esc_html__("Choose latest item to show under slider", 'framework'),
        'type' => 'select',
        'options' => array(
          'letest_event' => esc_html__('Latest event', 'framework'),
          'letest_sermon' => esc_html__('Latest Sermon', 'framework'),
          'text' => esc_html__('Custom message', 'framework'),
        ),
        'hidden' => array('imic_upcoming_area', '=', '2'),
      ),
      array(
        'name' => esc_html__('Custom Text Message', 'framework'),
        'id' => $prefix . 'custom_text_message',
        'desc' => esc_html__("Enter custom message, this field accept shortcodes as well.", 'framework'),
        'type' => 'textarea',
        'hidden' => array('imic_latest_sermon_events_to_show_on', '!=', 'text'),
      ),
      array(
        'name' => esc_html__('Event Category', 'framework'),
        'id' => $prefix . 'advanced_event_taxonomy',
        'desc' => esc_html__("Choose event category", 'framework'),
        'type' => 'taxonomy_advanced',
        'options' => array(
          'taxonomy' => 'event-category',
          'type' => 'select',
          'args' => array('orderby' => 'count', 'hide_empty' => true),
        ),
        'multiple' => true,
        'field_type' => 'select_advanced',
        'select_all_none' => true,
        'hidden' => array('imic_latest_sermon_events_to_show_on', '!=', 'letest_event'),
      ),
      array(
        'name' => esc_html__('Switch for Going on events', 'framework'),
        'id' => $prefix . 'going_on_events',
        'desc' => esc_html__("Select enable or disable to show/hide Going On Events under slider.", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('Disable', 'framework'),
          '2' => esc_html__('Enable', 'framework'),
        ),
        'std' => '1',
        'hidden' => array('imic_latest_sermon_events_to_show_on', '!=', 'letest_event'),
      ),
      array(
        'name' => esc_html__('Custom Going On Events Title', 'framework'),
        'id' => $prefix . 'custom_going_on_events_title',
        'desc' => esc_html__("Enter going on events block title.", 'framework'),
        'type' => 'text',
        'hidden' => array('imic_going_on_events', '!=', '2'),
      ),
      array(
        'name' => esc_html__('Sermons Category', 'framework'),
        'id' => $prefix . 'advanced_sermons_category',
        'desc' => esc_html__("Choose sermon category", 'framework'),
        'type' => 'taxonomy_advanced',
        'options' => array(
          'taxonomy' => 'sermons-category',
          'type' => 'select',
          'args' => array('orderby' => 'count', 'hide_empty' => true),
        ),
        'multiple' => true,
        'field_type' => 'select_advanced',
        'select_all_none' => true,
        'hidden' => array('imic_latest_sermon_events_to_show_on', '!=', 'letest_sermon'),
      ),
      array(
        'name' => esc_html__('All Events/Sermons Button URL', 'framework'),
        'id' => $prefix . 'all_event_sermon_url',
        'desc' => esc_html__("Enter URL for the button link to your all events/sermons page", 'framework'),
        'type' => 'text',
        'std' => '',
        'hidden' => array('imic_latest_sermon_events_to_show_on', '=', 'text'),
      ),
    ),
  );
  /* * **Home Page Meta Box4 *** */
  $meta_boxes[] = array(
    'id' => 'template-home4',
    'title' => esc_html__('Featured Blocks Area', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-home.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Switch for featured blocks', 'framework'),
        'id' => $prefix . 'imic_featured_blocks',
        'desc' => esc_html__("Select enable or disable to show/hide featured blocks.", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('Enable', 'framework'),
          '2' => esc_html__('Disable', 'framework'),
        ),
        'std' => '1',
      ),
      array(
        'name' => esc_html__('Featured Blocks to show on home page', 'framework'),
        'id' => $prefix . 'home_featured_blocks',
        'desc' => esc_html__("Enter the Posts/Pages comma separated ID to show featured blocks on Home page. example - 1,2,3", 'framework'),
        'type' => 'text',
        'std' => '',
        'hidden' => array('imic_imic_featured_blocks', '=', '2'),
      ),
      array(
        'name' => esc_html__('Title for featured blocks', 'framework'),
        'id' => $prefix . 'home_row_featured_blocks',
        'desc' => esc_html__("Enter the title for featured blocks. Add more as per the entered page IDs", 'framework'),
        'type' => 'text',
        'clone' => true,
        'std' => '',
      ),
      array(
        'name' => esc_html__('Title for first featured block', 'framework'),
        'id' => $prefix . 'home_featured_blocks1',
        'desc' => esc_html__("Enter the title for first featured block area", 'framework'),
        'type' => 'hidden',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Title for second featured block', 'framework'),
        'id' => $prefix . 'home_featured_blocks2',
        'desc' => esc_html__("Enter the title for second featured block area", 'framework'),
        'type' => 'hidden',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Title for third featured block', 'framework'),
        'id' => $prefix . 'home_featured_blocks3',
        'desc' => esc_html__("Enter the title for third featured block area", 'framework'),
        'type' => 'hidden',
        'std' => '',
      ),
    ),
  );
  /* * **Home Page Meta Box7 *** */
  $meta_boxes[] = array(
    'id' => 'template-home7',
    'title' => esc_html__('Upcoming Events Area', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-home.php', 'template-h-third.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Switch for upcoming events', 'framework'),
        'id' => $prefix . 'imic_upcoming_events',
        'desc' => esc_html__("Select enable or disable to show/hide upcoming events.", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('Enable', 'framework'),
          '2' => esc_html__('Disable', 'framework'),
        ),
        'std' => '1',
      ),
      array(
        'name' => esc_html__('Event Category', 'framework'),
        'id' => $prefix . 'upcoming_event_taxonomy',
        'desc' => esc_html__("Choose event category", 'framework'),
        'type' => 'taxonomy_advanced',
        'options' => array(
          'taxonomy' => 'event-category',
          'type' => 'select',
          'args' => array('orderby' => 'count', 'hide_empty' => true),
        ),
        'multiple' => true,
        'field_type' => 'select_advanced',
        'select_all_none' => true,
        'hidden' => array('imic_imic_upcoming_events', '=', '2'),
      ),
      array(
        'name' => esc_html__('Custom More Upcoming Events Title', 'framework'),
        'id' => $prefix . 'custom_upcoming_events_title',
        'desc' => esc_html__("Enter more upcoming events title.", 'framework'),
        'type' => 'text',
        'hidden' => array('imic_imic_upcoming_events', '=', '2'),
      ),
      array(
        'name' => esc_html__('Number of Events to show on home page', 'framework'),
        'id' => $prefix . 'events_to_show_on',
        'desc' => esc_html__("Enter the number of events to show on home page. Example: 3", 'framework'),
        'type' => 'text',
        'std' => '',
        'hidden' => array('imic_imic_upcoming_events', '=', '2'),
      ),
    ),
  );
  /* * **Home Page Meta Box5 *** */
  $meta_boxes[] = array(
    'id' => 'template-home5',
    'title' => esc_html__('Recent Posts Area', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-home.php', 'template-h-third.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Switch for recent post.', 'framework'),
        'id' => $prefix . 'imic_recent_posts',
        'desc' => esc_html__("Select enable or disable to show/hide recent posts.", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('Enable', 'framework'),
          '2' => esc_html__('Disable', 'framework'),
        ),
        'std' => '1',
      ),
      array(
        'name' => esc_html__('Post Category', 'framework'),
        'id' => $prefix . 'recent_post_taxonomy',
        'desc' => esc_html__("Choose post category", 'framework'),
        'type' => 'taxonomy_advanced',
        'options' => array(
          'taxonomy' => 'category',
          'type' => 'select',
          'args' => array('orderby' => 'count', 'hide_empty' => true),
        ),
        'std' => '',
        'multiple' => true,
        'hidden' => array('imic_imic_recent_posts', '=', '2'),
        'field_type' => 'select_advanced',
        'select_all_none' => true
      ),
      array(
        'name' => esc_html__('Custom Latest News Title', 'framework'),
        'id' => $prefix . 'custom_latest_news_title',
        'desc' => esc_html__("Enter custom latest news title.", 'framework'),
        'type' => 'text',
        'hidden' => array('imic_imic_recent_posts', '=', '2'),
      ),
      array(
        'name' => esc_html__('Number of Recent Posts to show on home page.', 'framework'),
        'id' => $prefix . 'posts_to_show_on',
        'desc' => esc_html__("Enter the number of recent posts to show on home page. Example: 3", 'framework'),
        'type' => 'text',
        'std' => '',
        'hidden' => array('imic_imic_recent_posts', '=', '2'),
      ),
      array(
        'name' => esc_html__('Show read more button', 'framework'),
        'id' => $prefix . 'recent_posts_rmbutton',
        'desc' => esc_html__("Show read more button for each recent post?", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('Yes', 'framework'),
          '2' => esc_html__('No', 'framework'),
        ),
        'std' => '2',
        'hidden' => array('imic_imic_recent_posts', '=', '2'),
      ),
      array(
        'name' => esc_html__('Custom read more button text', 'framework'),
        'id' => $prefix . 'recent_posts_rmbutton_text',
        'desc' => esc_html__("Enter button text for read more button", 'framework'),
        'type' => 'text',
        'std' => '',
        'hidden' => array('imic_recent_posts_rmbutton', '=', '2'),
      ),
    ),
  );
  /* * **Home Page Meta Box3 *** */
  $meta_boxes[] = array(
    'id' => 'template-home3',
    'title' => esc_html__('Recent Galleries Area', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-home.php', 'template-h-third.php', 'template-h-second.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Switch for gallery', 'framework'),
        'id' => $prefix . 'imic_galleries',
        'desc' => esc_html__("Select enable or disable to show/hide galleries.", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('Enable', 'framework'),
          '2' => esc_html__('Disable', 'framework'),
        ),
        'std' => '1',
      ),
      array(
        'name' => esc_html__('Gallery Categories', 'framework'),
        'id' => $prefix . 'home_gallery_taxonomy',
        'desc' => esc_html__("Choose gallery category", 'framework'),
        'type' => 'taxonomy_advanced',
        'options' => array(
          'taxonomy' => 'gallery-category',
          'type' => 'select',
          'args' => array('orderby' => 'count', 'hide_empty' => true),
        ),
        'std' => '',
        'multiple' => true,
        'hidden' => array('imic_imic_galleries', '=', '2'),
        'field_type' => 'select_advanced',
        'select_all_none' => true
      ),
      //Custom Gallery Title
      array(
        'name' => esc_html__('Custom Gallery Title', 'framework'),
        'id' => $prefix . 'custom_gallery_title',
        'desc' => esc_html__("Enter custom gallery title.", 'framework'),
        'type' => 'text',
        'hidden' => array('imic_imic_galleries', '=', '2'),
      ),
      array(
        'name' => esc_html__('Custom More Galleries Title', 'framework'),
        'id' => $prefix . 'custom_more_galleries_title',
        'desc' => esc_html__("Enter custom more galleries title.", 'framework'),
        'type' => 'text',
        'hidden' => array('imic_imic_galleries', '=', '2'),
      ),
      array(
        'name' => esc_html__('Custom More Galleries URL', 'framework'),
        'id' => $prefix . 'custom_more_galleries_url',
        'desc' => esc_html__("Enter custom more galleries URL.", 'framework'),
        'type' => 'url',
        'hidden' => array('imic_imic_galleries', '=', '2'),
      ),
      array(
        'name' => esc_html__('Number of Galleries to show on home page', 'framework'),
        'id' => $prefix . 'galleries_to_show_on',
        'desc' => esc_html__("Enter the number of gallery posts to show on home page. Example: 3", 'framework'),
        'type' => 'text',
        'std' => '',
        'hidden' => array('imic_imic_galleries', '=', '2'),
      ),
      array(
        'name' => esc_html__('Upload Background Image', 'framework'),
        'id' => $prefix . 'galleries_background_image',
        'desc' => esc_html__("Upload background image for the latest gallery section of home page.", 'framework'),
        'type' => 'image_advanced',
        'max_file_uploads' => 1,
        'hidden' => array('imic_imic_galleries', '=', '2'),
      ),
    ),
  );
  /* * **Home third Meta Box1 *** */
  $meta_boxes[] = array(
    'id' => 'template-h-third-1',
    'title' => esc_html__('Latest Sermon Albums', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-h-third.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Switch for Sermon Albums', 'framework'),
        'id' => $prefix . 'switch_sermon_album',
        'desc' => esc_html__("Select enable or disable to show/hide sermon albums posts area.", 'framework'),
        'type' => 'select',
        'options' => array(
          '1' => esc_html__('Enable', 'framework'),
          '2' => esc_html__('Disable', 'framework'),
        ),
        'std' => '1',
      ),
      array(
        'name' => esc_html__('Custom Latest Sermon Albums Title', 'framework'),
        'id' => $prefix . 'custom_albums_title',
        'desc' => esc_html__("Enter custom latest sermon albums title", 'framework'),
        'type' => 'text',
        'std' => '',
        'hidden' => array('imic_switch_sermon_album', '=', '2'),
      ),
      array(
        'name' => esc_html__('Number of Sermon Albums', 'framework'),
        'id' => $prefix . 'number_of_sermon_albums',
        'desc' => esc_html__("Enter number of sermon albums to show", 'framework'),
        'type' => 'text',
        'std' => '',
        'hidden' => array('imic_switch_sermon_album', '=', '2'),
      ),
      array(
        'name' => esc_html__('All Sermon Albums URL', 'framework'),
        'id' => $prefix . 'sermon_albums_url',
        'desc' => esc_html__("Enter sermon albums URL", 'framework'),
        'type' => 'text',
        'hidden' => array('imic_switch_sermon_album', '=', '2'),
      ),
    ),
  );
  /* * ** Gallery  Pagination Meta Box 1 *** */
  $meta_boxes[] = array(
    'id' => 'template-gallery-pagination1',
    'title' => esc_html__('Gallery Metabox', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-gallery-pagination.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Number of gallery items', 'framework'),
        'id' => $prefix . 'gallery_pagination_to_show_on',
        'desc' => esc_html__("Enter the number of gallery items to show on a page. For example: 6", 'framework'),
        'type' => 'text',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Gallery Columns Layout', 'framework'),
        'id' => $prefix . 'gallery_pagination_columns_layout',
        'desc' => esc_html__("Enter the number of columns for layout to show on gallery page. Example: 3", 'framework'),
        'type' => 'text',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Show gallery items title', 'framework'),
        'id' => $prefix . 'show_gallery_title',
        'desc' => esc_html__("Select enable if you need to show gallery posts title.", 'framework'),
        'type' => 'select',
        'options' => array(
          '0' => esc_html__('Disable', 'framework'),
          '1' => esc_html__('Enable', 'framework'),
        ),
        'std' => 0,
      ),
    ),
  );
  /* * ** Gallery Masonry Meta Box 1 *** */
  $meta_boxes[] = array(
    'id' => 'template-gallery-masonry1',
    'title' => esc_html__('Gallery Metabox', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-gallery-masonry.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Number of gallery items', 'framework'),
        'id' => $prefix . 'gallery_masonry_to_show_on',
        'desc' => esc_html__("Enter the number of gallery items to show on a page. For example: 3", 'framework'),
        'type' => 'text',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Show gallery items title', 'framework'),
        'id' => $prefix . 'show_gallery_title_masonry',
        'desc' => esc_html__("Select enable if you need to show gallery items title.", 'framework'),
        'type' => 'select',
        'options' => array(
          '0' => esc_html__('Disable', 'framework'),
          '1' => esc_html__('Enable', 'framework'),
        ),
        'std' => 0,
      ),
    ),
  );
  /* * ** Gallery  Filter Meta Box 1 *** */
  $meta_boxes[] = array(
    'id' => 'template-gallery-filter1',
    'title' => esc_html__('Gallery Metabox', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-gallery-filter.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Gallery Columns Layout', 'framework'),
        'id' => $prefix . 'gallery_filter_columns_layout',
        'desc' => esc_html__("Enter the number of columns for Layout to show on gallery filter page. Example: 3", 'framework'),
        'type' => 'select',
        'options' => array(
          2 => esc_html__('2 Columns', 'framework'),
          3 => esc_html__('3 Columns', 'framework'),
          4 => esc_html__('4 Columns', 'framework'),
          6 => esc_html__('6 Columns', 'framework'),
        ),
        'std' => 3,
      ),
      array(
        'name' => esc_html__('Show gallery items title', 'framework'),
        'id' => $prefix . 'show_gallery_title_filter',
        'desc' => esc_html__("Select enable if you need to show gallery items title.", 'framework'),
        'type' => 'select',
        'options' => array(
          '0' => esc_html__('Disable', 'framework'),
          '1' => esc_html__('Enable', 'framework'),
        ),
        'std' => 0,
      ),
    ),
  );
  /* * ** Gallery  Category Meta Box 1 *** */
  $meta_boxes[] = array(
    'id' => 'gallery-taxonomies',
    'title' => esc_html__('Gallery Categories', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-gallery-filter.php', 'template-gallery-masonry.php', 'template-gallery-pagination.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Gallery Category', 'framework'),
        'id' => $prefix . 'advanced_gallery_taxonomy',
        'desc' => esc_html__("Choose gallery category", 'framework'),
        'type' => 'taxonomy_advanced',
        'options' => array(
          'taxonomy' => 'gallery-category',
          'type' => 'select',
          'args' => array('orderby' => 'count', 'hide_empty' => true),
        ),
        'std' => '',
        'multiple' => true,
        'field_type' => 'select_advanced',
        'select_all_none' => true
      ),
    ),
  );
  /* * ** Event  Category Meta Box 1 *** */
  $meta_boxes[] = array(
    'id' => 'events-taxonomies',
    'title' => esc_html__('Events Categories', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-event-category.php', 'template-events_grid.php', 'template-events-timeline.php', 'template-events.php', 'template-events-classic.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Event Category', 'framework'),
        'id' => $prefix . 'advanced_event_list_taxonomy',
        'desc' => esc_html__("Choose event category", 'framework'),
        'type' => 'taxonomy_advanced',
        'options' => array(
          'taxonomy' => 'event-category',
          'type' => 'select',
          'args' => array('orderby' => 'count', 'hide_empty' => true),
        ),
        'std' => '',
        'multiple' => true,
        'field_type' => 'select_advanced',
        'select_all_none' => true
      ),
    ),
  );
  /* * ** Post  Category Meta Box 1 *** */
  $meta_boxes[] = array(
    'id' => 'post-taxonomies',
    'title' => esc_html__('Post Categories', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-blog-full-width.php', 'template-blog-masonry.php', 'template-blog-medium-thumbnails.php', 'template-blog-timeline.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Post Category', 'framework'),
        'id' => $prefix . 'advanced_post_taxonomy',
        'desc' => esc_html__("Choose post category", 'framework'),
        'type' => 'taxonomy_advanced',
        'options' => array(
          'taxonomy' => 'category',
          'type' => 'select',
          'args' => array('orderby' => 'count', 'hide_empty' => true),
        ),
        'std' => '',
        'multiple' => true,
        'field_type' => 'select_advanced',
        'select_all_none' => true
      ),
    ),
  );
  /* * ** Post  Category Meta Box 1 *** */
  $meta_boxes[] = array(
    'id' => 'post-taxonomies-blog',
    'title' => esc_html__('Blog Post Categories', 'framework'),
    'pages' => array('page'),
    'show_names' => true,
    'fields' => array(
      array(
        'id' => $prefix . 'custom_cats_blog_message',
        'std' => __('<p style="background-color:red; color:#fff; padding:5px 20px">If you are setting this page as your posts page at Settings > Reading then use this metabox options for the post categories.</p>', 'framework'),
        'type' => 'custom_html',
      ),
      array(
        'name' => esc_html__('Post Category', 'framework'),
        'id' => $prefix . 'advanced_blog_taxonomy',
        'desc' => esc_html__("Choose post category/categories", 'framework'),
        'type' => 'taxonomy_advanced',
        'options' => array(
          'taxonomy' => 'category',
          'type' => 'select',
          'args' => array('orderby' => 'count', 'hide_empty' => true),
        ),
        'std' => '',
        'multiple' => true,
        'field_type' => 'select_advanced',
        'select_all_none' => true
      ),
    ),
  );
  /* * ** Staff Page Meta Box 1 *** */
  $meta_boxes[] = array(
    'id' => 'template-staff1',
    'title' => esc_html__('Staff to show', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-staff.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Number of Staff to show', 'framework'),
        'id' => $prefix . 'staff_to_show_on',
        'desc' => esc_html__("Enter the number of staff posts to show on staff page. Example: 3", 'framework'),
        'type' => 'text',
        'std' => '',
      ),
      array(
        'name' => esc_html__('Select grid column', 'framework'),
        'id' => $prefix . 'staff_grid_column',
        'desc' => esc_html__("Select number of columns to show in a row.", 'framework'),
        'type' => 'select',
        'options' => array(
          '4' => esc_html__('Three', 'framework'),
          '6' => esc_html__('Two', 'framework'),
          '3' => esc_html__('Four', 'framework'),
        ),
      ),
      array(
        'name' => esc_html__('Staff Category', 'framework'),
        'id' => $prefix . 'advanced_staff_taxonomy',
        'desc' => esc_html__("Choose staff category", 'framework'),
        'type' => 'taxonomy_advanced',
        'options' => array(
          'taxonomy' => 'staff-category',
          'type' => 'select',
          'args' => array('orderby' => 'count', 'hide_empty' => true),
        ),
        'multiple' => true,
        'field_type' => 'select_advanced',
        'select_all_none' => true
      ),
      array(
        'name' => esc_html__('Select Orderby', 'framework'),
        'id' => $prefix . 'staff_select_orderby',
        'desc' => esc_html__("Select staff orderby.", 'framework'),
        'type' => 'select',
        'options' => array(
          'ID' => esc_html__('ID', 'framework'),
          'title' => esc_html__('Title', 'framework'),
          'date' => esc_html__('Date', 'framework'),
          'menu_order' => esc_html__('Menu Order', 'framework'),
        ),
      ),
      array(
        'name' => esc_html__('Select Order', 'framework'),
        'id' => $prefix . 'staff_select_order',
        'desc' => esc_html__("Select staff order.", 'framework'),
        'type' => 'select',
        'options' => array(
          'ASC' => esc_html__('Ascending', 'framework'),
          'DESC' => esc_html__('Descending', 'framework'),
        ),
      ),
      array(
        'name' => esc_html__('Length of Excerpt to show', 'framework'),
        'id' => $prefix . 'staff_excerpt_length',
        'desc' => esc_html__("Enter the number of words you would like to show from the staff posts content/excerpt. Enter 0 to completely hide the excerpt and read more button", 'framework'),
        'type' => 'text',
        'std' => '',
      ),
    ),
  );

  /* * ** Events Timeline Meta Box 1 *** */
  $meta_boxes[] = array(
    'id' => 'template-events-timeline',
    'title' => esc_html__('Event Timeline View', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-events-timeline.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('Event type', 'framework'),
        'id' => $prefix . 'events_timeline_view',
        'desc' => esc_html__("Select events to show in timeline", 'framework'),
        'type' => 'select',
        'options' => array(
          'future' => esc_html__('Future', 'framework'),
          'past' => esc_html__('Past', 'framework'),
        ),
      ),
      array(
        'name' => esc_html__('Events to show per page', 'framework'),
        'id' => $prefix . 'event_timeline_per_page',
        'desc' => esc_html__("Enter the number of events that you would like to show per page.", 'framework'),
        'type' => 'text',
        'std' => '',
      ),
    ),
  );
  /* * ** Blog Masonry Meta Box 1 *** */
  $meta_boxes[] = array(
    'id' => 'template-blog-masonry',
    'title' => esc_html__('Blog Masonry Metabox', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-blog-masonry.php'),
    ),
    'show_names' => true,
    'fields' => array(
      array(
        'name' => esc_html__('What should thumbnails do?', 'framework'),
        'id' => $prefix . 'blog_masonry_thumbnails',
        'desc' => esc_html__("Select what you like for your blog thumbnails - Open in lightbox or redirect to single post page.", 'framework'),
        'type' => 'select',
        'options' => array(
          '0' => esc_html__('Lightbox', 'framework'),
          '1' => esc_html__('Single Post', 'framework'),
        ),
        'std' => 0,
      ),
    ),
  );
  /* * ** Sermon Albums Template Meta Box 1 *** */
  $meta_boxes[] = array(
    'id' => 'template-sermons-albums1',
    'title' => esc_html__('Show Sermon Categories/Albums', 'framework'),
    'pages' => array('page'),
    'show' => array(
      'relation' => 'OR',
      'template' => array('template-sermons-albums.php'),
    ),
    'show_names' => true,
    'fields' => array(
      //Sort albums by
      array(
        'name' => esc_html__('Select Orderby', 'framework'),
        'id' => $prefix . 'albums_select_orderby',
        'desc' => esc_html__("Select how you want to sort albums by. Default is by count", 'framework'),
        'type' => 'select',
        'options' => array(
          'count' => esc_html__('Count', 'framework'),
          'ID' => esc_html__('ID', 'framework'),
          'name' => esc_html__('Name', 'framework'),
          'slug' => esc_html__('Slug', 'framework'),
          'include' => esc_html__('Custom order', 'framework'),
        ),
        'std' => 'count',
      ),
      array(
        'name' => esc_html__('Select Order', 'framework'),
        'id' => $prefix . 'albums_select_order',
        'desc' => esc_html__("Select the order of list. Default is by ASC", 'framework'),
        'type' => 'select',
        'options' => array(
          'ASC' => esc_html__('Ascending', 'framework'),
          'DESC' => esc_html__('Descending', 'framework'),
        ),
        'std' => 'ASC',
      ),
      array(
        'name' => esc_html__('Sermon Categories', 'framework'),
        'id' => $prefix . 'sermon_categories_custom_order',
        'desc' => esc_html__("Add categories to show them in given order.", 'framework'),
        'clone' => true,
        //'clone-group' => 'imic-clone-group',
        'type' => 'select',
        'options' => $sermons_cats,
      ),
    ),
  );
  return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'prefix_register_meta_boxes');
