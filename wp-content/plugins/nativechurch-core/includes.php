<?php
class Native_Church_Core_Features
{
  public function __construct()
  {
    if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
      add_filter('mce_external_plugins', array($this, 'imic_add_tinymce_plugin'));
      add_filter('mce_buttons', array($this, 'imic_register_shortcode_button'));
    }
  }

  public function imic_register_shortcode_button($button)
  {
    array_push($button, 'separator', 'imicframework_shortcodes');
    return $button;
  }

  public function imic_add_tinymce_plugin($plugins)
  {
    $plugins['imicframework_shortcodes'] = NATIVECHURCH_CORE__PLUGIN_URL . 'shortcodes/imic-shortcodes/tinymce.editor.plugin.js';
    return $plugins;
  }
}
function nativechurch_core_initialize_features()
{
  new Native_Church_Core_Features;
}
add_action('init', 'nativechurch_core_initialize_features');
/* Stop Woocommerce setup redirect */
if (class_exists('Woocommerce')) {
  if (!function_exists('remove_class_filters')) {
    function remove_class_filters($tag, $class, $method)
    {
      $filters = $GLOBALS['wp_filter'][$tag];
      if (empty($filters)) {
        return;
      }
      foreach ($filters as $priority => $filter) {
        foreach ($filter as $identifier => $function) {
          if (is_array($function)) {

            if (is_array($function['function']) || is_string($function['function'])) {

              if (is_a($function['function'][0], $class) and $method === $function['function'][1]) {

                remove_filter(
                  $tag,
                  array($function['function'][0], $method),
                  $priority
                );
              }
            }
          }
        }
      }
    }
  }

  add_action('admin_init', 'disable_shop_redirect', 0);
  function disable_shop_redirect()
  {
    remove_class_filters(
      'admin_init',
      'WC_Admin',
      'admin_redirects'
    );
  }
}
// Stop Elementor redirect after activation
add_action('admin_init', function () {
  if (did_action('elementor/loaded')) {
    remove_action('admin_init', [\Elementor\Plugin::$instance->admin, 'maybe_redirect_to_getting_started']);
  }
}, 1);

add_action('init', 'imic_save_event');
if (!function_exists('imic_save_event')) {
  function imic_save_event()
  {
    //date_default_timezone_set('Antarctica/Troll');
    $query_string = $_SERVER['QUERY_STRING'];
    parse_str($query_string, $parsed_query);
    if (isset($parsed_query['action']) && isset($parsed_query['id']) && isset($parsed_query['key']) && $parsed_query['key'] == 'imic_save_event') {
      $id = $parsed_query['id'];
      $edate = $parsed_query['edate'];
      $action = $parsed_query['action'];
      $custom_post = get_post($id);
      $title = $custom_post->post_title;
      $contentraw = $custom_post->post_content;
      $content = wp_trim_words($contentraw, 50, '...');
      $imic_event_address = get_post_meta($id, 'imic_event_address', true);
      $eventStartTime = get_post_meta($id, 'imic_event_start_tm', true);
      $eventStartDate = get_post_meta($id, 'imic_event_start_dt', true);
      $eventEndTime = get_post_meta($id, 'imic_event_end_tm', true);
      $eventEndDate = get_post_meta($id, 'imic_event_end_dt', true);
      $random_name = substr(rand() . rand() . rand() . rand(), 0, 20);
      $user_tz = get_option('timezone_string');

      $schedule_date_start = $edate . ' ' . date_i18n('H:i', strtotime($eventStartTime));
      //$schedule_date_start->setTimeZone(new DateTimeZone('UTC'));
      $triggerOn_start = $schedule_date_start;
      $schedule_date_end = $edate . ' ' . date_i18n('H:i', strtotime($eventEndTime));
      //$schedule_date_end->setTimeZone(new DateTimeZone('UTC'));
      $triggerOn_end = $schedule_date_end;
      switch ($action) {
        case 'gcalendar':
          $google_save_url = 'https://www.google.com/calendar/render?action=TEMPLATE';
          $google_save_url .= '&dates=' . date_i18n("Ymd\THis", strtotime("$triggerOn_start"));
          $google_save_url .= '/' . date_i18n("Ymd\THis", strtotime("$triggerOn_end"));
          $google_save_url .= '&location=' . urlencode($imic_event_address);
          $google_save_url .= '&text=' . urlencode($title);
          //$google_save_url .= '&ctz=Antarctica/Troll';
          $google_save_url .= '&details=' . urlencode($content);
          wp_redirect($google_save_url);
          exit;
          break;
        case 'icalendar':
          ob_start();
          header("Content-Type: text/calendar; charset=utf-8");
          header("Content-Disposition: inline; filename=addto_calendar_" . $random_name . ".ics");
          $title = addslashes($title);
          $title = str_replace(array(",", ":", ";"), array("\,", "\:", "\;"), $title);
          $content = addslashes($content);
          $content = str_replace(array(",", ":", ";"), array("\,", "\:", "\;"), $content);
          $content = preg_replace('/\s+/', ' ', $content);
          $imic_event_address = addslashes($imic_event_address);
          $imic_event_address = str_replace(array(",", ":", ";"), array("\,", "\:", "\;"), $imic_event_address);
          echo "BEGIN:VCALENDAR\n";
          echo "VERSION:2.0\n";
          echo "PRODID:Imitheme.com \n";
          echo "BEGIN:VEVENT\n";
          echo "UID:" . date_i18n('Ymd') . 'T' . date_i18n('His') . rand() . "\n";
          echo "DTSTAMP;TZID=UTC:" . date_i18n('Ymd') . 'T' . date_i18n('His') . "\n";
          echo "DTSTART;TZID=UTC:" . date_i18n("Ymd\THis", strtotime("$triggerOn_start")) . "\n";
          echo "DTEND;TZID=UTC:" . date_i18n("Ymd\THis", strtotime("$triggerOn_end")) . "\n";
          echo "SUMMARY:$title\n";
          echo "LOCATION:$imic_event_address\n";
          echo "DESCRIPTION:$content\n";
          echo "END:VEVENT\n";
          echo "END:VCALENDAR\n";
          ob_flush();
          exit;
          break;
        case 'outlook':
          ob_start();
          header("Content-Type: text/calendar; charset=utf-8");
          header("Content-Disposition: inline; filename=addto_calendar_" . $random_name . ".ics");
          echo "BEGIN:VCALENDAR\n";
          echo "VERSION:2.0\n";
          echo "PRODID:Imitheme.com\n";
          echo "BEGIN:VEVENT\n";
          echo "UID:" . date_i18n('Ymd') . 'T' . date_i18n('His') . "-" . rand() . "\n";
          echo "DTSTAMP:" . date_i18n('Ymd') . 'T' . date_i18n('His') . "\n";
          echo "DTSTART:" . date_i18n("Ymd\THis", strtotime("$triggerOn_start")) . "\n";
          echo "DTEND:" . date_i18n("Ymd\THis", strtotime("$triggerOn_end")) . "\n";
          echo "SUMMARY:$title\n";
          echo "LOCATION:$imic_event_address\n";
          echo "DESCRIPTION: $content\n";
          echo "END:VEVENT\n";
          echo "END:VCALENDAR\n";
          ob_flush();
          exit;
          break;
        case 'outlooklive':
          $outlooklive_url = 'https://bay03.calendar.live.com/calendar/calendar.aspx?rru=addevent';
          $outlooklive_url .= '&summary=' . urlencode($title);
          $outlooklive_url .= '&location=' . urlencode($imic_event_address);
          $outlooklive_url .= '&description=' . urlencode($content);
          $outlooklive_url .= '&dtstart=' . date_i18n("Ymd\THis", strtotime("$eventStartDate $eventStartTime"));
          $outlooklive_url .= '&dtend=' . date_i18n("Ymd\THis", strtotime("$eventEndDate $eventEndTime"));
          wp_redirect($outlooklive_url);
          exit;
          break;
        case 'yahoo':
          $yahoo_url = 'https://calendar.yahoo.com/?view=d&v=60&type=20';
          $yahoo_url .= '&title=' . urlencode($title);
          $yahoo_url .= '&in_loc=' . urlencode($imic_event_address);
          $yahoo_url .= '&desc=' . urlencode($content);
          $yahoo_url .= '&st=' . date_i18n("Ymd\THis", strtotime("$triggerOn_start"));
          $yahoo_url .= '&et=' . date_i18n("Ymd\THis", strtotime("$triggerOn_end"));
          wp_redirect($yahoo_url);
          exit;
          break;
      }
    }
  }
}

/* -------------------------------------------------------------------------------------
Add New Custom Menu Option
@since NativeChurch 1.6
------------------------------------------------------------------------------------- */
if (!class_exists('IMIC_Custom_Nav')) {
  class IMIC_Custom_Nav
  {
    public function add_nav_menu_meta_boxes()
    {
      add_meta_box(
        'mega_nav_link',
        esc_html__('Mega Menu', 'imithemes'),
        array($this, 'nav_menu_link'),
        'nav-menus',
        'side',
        'low'
      );
    }
    public function nav_menu_link()
    {
      global $_nav_menu_placeholder, $nav_menu_selected_id;
      $_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;
      ?>
        <div id="posttype-wl-login" class="posttypediv">
          <div id="tabs-panel-wishlist-login" class="tabs-panel tabs-panel-active">
            <ul id="wishlist-login-checklist" class="categorychecklist form-no-clear">
              <li>
                <label class="menu-item-title">
                  <input type="checkbox" class="menu-item-object-id" name="menu-item[<?php echo esc_attr($_nav_menu_placeholder); ?>][menu-item-object-id]" value="<?php echo esc_attr($_nav_menu_placeholder); ?>"> <?php _e('Create Column', 'imithemes'); ?>
                </label>
                <input type="hidden" class="menu-item-db-id" name="menu-item[<?php echo esc_attr($_nav_menu_placeholder); ?>][menu-item-db-id]" value="0">
                <input type="hidden" class="menu-item-object" name="menu-item[<?php echo esc_attr($_nav_menu_placeholder); ?>][menu-item-object]" value="page">
                <input type="hidden" class="menu-item-parent-id" name="menu-item[<?php echo esc_attr($_nav_menu_placeholder); ?>][menu-item-parent-id]" value="0">
                <input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr($_nav_menu_placeholder); ?>][menu-item-type]" value="">
                <input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr($_nav_menu_placeholder); ?>][menu-item-title]" value="<?php _e('Column', 'imithemes'); ?>">
                <input type="hidden" class="menu-item-classes" name="menu-item[<?php echo esc_attr($_nav_menu_placeholder); ?>][menu-item-classes]" value="custom_mega_menu">
              </li>
            </ul>
          </div>
          <p class="button-controls">
            <span class="add-to-menu">
              <input type="submit" class="button-secondary submit-add-to-menu right" value="<?php _e('Add to Menu', 'imithemes'); ?>" name="add-post-type-menu-item" id="submit-posttype-wl-login">
              <span class="spinner"></span>
            </span>
          </p>
        </div>
      <?php
          }
        }
      }
      $custom_nav = new IMIC_Custom_Nav;
      add_action('admin_init', array($custom_nav, 'add_nav_menu_meta_boxes'));

      /* -------------------------------------------------------------------------------------
Custom admin menu items
@since NativeChurch 1.0
------------------------------------------------------------------------------------- */
      if (!function_exists('imic_admin_bar_menu')) {
        function imic_admin_bar_menu()
        {
          global $wp_admin_bar;
          if (current_user_can('manage_options')) {
            $theme_customizer = array(
              'id' => '2',
              'title' => esc_html__('Color Customizer', 'imithemes'),
              'href' => admin_url('/customize.php'),
              'meta' => array('target' => 'blank'),
            );
            $wp_admin_bar->add_menu($theme_customizer);
          }
        }
        add_action('admin_bar_menu', 'imic_admin_bar_menu', 99);
      }

      if (!function_exists('imic_audio_soundcloud')) {
        function imic_audio_soundcloud($url, $width = "100%", $height = 250)
        {
          $getValues = file_get_contents('http://soundcloud.com/oembed?format=js&url=' . $url . '&iframe=true');
          $decodeiFrame = substr($getValues, 1, -2);
          $jsonObj = json_decode($decodeiFrame);
          return str_replace('height="200"', 'height="250"', $jsonObj->html);
        }
      }

      //Add Sermons Filter Shortcode
      if (!function_exists('imic_sermons_filter_shortcode')) {
        function imic_sermons_filter_shortcode($atts, $content = null)
        {
          extract(shortcode_atts(array(
            'categories' => '',
            'tags' => '',
            'speakers' => ''
          ), $atts));
          $output = '';
          $output .= '<form class="sermon-filter-search searchandfilter" method="get" action="' . esc_url(home_url()) . '">
		<div>
		<ul>';
          $get_sermon_categories = get_terms('sermons-category');
          if (!empty($get_sermon_categories)) {
            $output .= '<li>
			<select id="sermons-category" class="postform nativechurch_sermon_filters" name="sermons-category">
			<option selected value="" data-objects="">' . esc_html__('Sermons Categories', 'imithemes') . '</option>';
            foreach ($get_sermon_categories as $category) {
              $objects = json_encode(get_objects_in_term($category->term_id, 'sermons-category'));
              $selected = ($categories == $category->slug) ? 'selected' : '';
              $output .= "<option class='terms-search' " . $selected . " value='" . $category->slug . "' data-objects='" . $objects . "'>" . $category->name . " (" . $category->count . ")</option>";
            }
            $output .= '</select>
			</li>';
          }
          $get_sermon_tags = get_terms('sermons-tag');
          if (!empty($get_sermon_tags)) {
            $output .= '<li>
			<select id="sermons-tag" class="postform nativechurch_sermon_filters" name="sermons-tag">
			<option selected value="" data-objects="">' . esc_html__('Sermons Tag', 'imithemes') . '</option>';
            foreach ($get_sermon_tags as $tag) {
              $objects = json_encode(get_objects_in_term($tag->term_id, 'sermons-tag'));
              $selected = ($tags == $tag->slug) ? 'selected' : '';
              $output .= "<option class='terms-search' " . $selected . " value='" . $tag->slug . "' data-objects='" . $objects . "'>" . $tag->name . " (" . $tag->count . ")</option>";
            }
            $output .= '</select>
			</li>';
          }
          $get_sermon_speakers = get_terms('sermons-speakers');
          if (!empty($get_sermon_speakers)) {
            $output .= '<li>
			<select id="sermons-speakers" class="postform nativechurch_sermon_filters" name="sermons-speakers">
			<option selected value="" data-objects="">' . esc_html__('Sermons Speakers', 'imithemes') . '</option>';
            foreach ($get_sermon_speakers as $speaker) {
              $objects = json_encode(get_objects_in_term($speaker->term_id, 'sermons-speakers'));
              $selected = ($speakers == $speaker->slug) ? 'selected' : '';
              $output .= "<option class='terms-search' " . $selected . " value='" . $speaker->slug . "' data-objects='" . $objects . "'>" . $speaker->name . " (" . $speaker->count . ")</option>";
            }
            $output .= '</select>
			</li>';
          }
          $output .= '<li>
		<input class="btn btn-default" type="submit" value="' . esc_html__('Filter', 'imithemes') . '">
		</li>
		</ul>
		</div>
		</form>';
          return $output;
        }
        add_shortcode('imic-searchandfilter', 'imic_sermons_filter_shortcode');
      }
      // Get all post types
      if (!function_exists('imic_get_all_types')) {
        add_action('wp_loaded', 'imic_get_all_types');
        function imic_get_all_types()
        {
          $args = array(
            'public'   => true,
          );
          $output = 'names'; // names or objects, note names is the default
          return $post_types = get_post_types($args, $output);
        }
      }

      /* SIDEBAR SHORTCODES
  =================================================*/
      function nativechurch_sidebar($atts, $content = null)
      {
        extract(shortcode_atts(array(
          "id" => "",
          "column" => 4
        ), $atts));
        ob_start();
        dynamic_sidebar($id);
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
      }
      add_shortcode('sidebar_megamenu', 'nativechurch_sidebar');

      $options = get_option('imic_options');
      $event_feature = (isset($options['enable_event_feature'])) ? $options['enable_event_feature'] : '1';
      $sermon_feature = (isset($options['enable_sermon_feature'])) ? $options['enable_sermon_feature'] : '1';
      $staff_feature = (isset($options['enable_staff_feature'])) ? $options['enable_staff_feature'] : '1';
      $gallery_feature = (isset($options['enable_gallery_feature'])) ? $options['enable_gallery_feature'] : '1';

      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'shortcodes/shortcodes.php';
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'shortcodes/imic-shortcodes/interface.php';
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'meta-boxes/meta-box/meta-box.php';
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'meta-boxes/meta-box-show-hide/meta-box-show-hide.php';
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'meta-boxes/meta-box-conditional-logic/meta-box-conditional-logic.php';
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'meta-boxes/meta-box-group/meta-box-group.php';
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'meta-boxes/meta-box-tabs/meta-box-tabs.php';
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'meta-boxes/mb-admin-columns/mb-admin-columns.php';
      if (is_admin()) {
        include_once NATIVECHURCH_CORE__PLUGIN_PATH . 'imi-admin/admin.php';
      }
      //Widgets
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'widgets/latest_gallery.php';
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'widgets/selected_post.php';
      ($sermon_feature == '1') ? require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'widgets/recent_sermons.php' : '';
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'widgets/Advertisement.php';
	  add_action('after_setup_theme', function() {
			// Ensure the function exists
      		$event_feature = (isset($options['enable_event_feature'])) ? $options['enable_event_feature'] : '1';
			if (function_exists('imic_recur_events')) {
      			($event_feature == '1') ? require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'widgets/featured_event.php' : '';
      			($event_feature == '1') ? require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'widgets/upcoming_events.php' : '';
      			require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'widgets/custom_category.php';
			} else {
				
			}
		});
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'widgets/recent_post.php';
      ($sermon_feature == '1') ? require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'widgets/sermon_speakers.php' : '';
      //Podcast
      add_action('init', 'nativechurch_podcasting');
      if (!function_exists('nativechurch_podcasting')) {
        function nativechurch_podcasting()
        {
          $sermon_feature = (isset($options['enable_sermon_feature'])) ? $options['enable_sermon_feature'] : '1';
          ($sermon_feature == '1') ? require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'podcasting/podcast-functions.php' : '';
        }
      }
      //Meta Boxes
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'meta-boxes/extra_category_field.php';
      require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'meta-boxes/term_color_picker.php';
      //Custom Post Types
      ($sermon_feature == '1') ? require_once NATIVECHURCH_CORE__PLUGIN_PATH . '/custom-post-types/sermon-type.php' : '';
      ($event_feature == '1') ? require_once NATIVECHURCH_CORE__PLUGIN_PATH . '/custom-post-types/event-type.php' : '';
      ($staff_feature == '1') ? require_once NATIVECHURCH_CORE__PLUGIN_PATH . '/custom-post-types/staff-type.php' : '';
      ($gallery_feature == '1') ? require_once NATIVECHURCH_CORE__PLUGIN_PATH . '/custom-post-types/gallery-type.php' : '';
      ($event_feature == '1') ? require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'meta-boxes/tickets_clone_fields.php' : '';
      function nativechurch_core_shortcode_scripts($hook)
      {
        wp_enqueue_style('imic_font_awesome_thickbox', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), '5.0', 'all');
        wp_enqueue_style('imic_shortcode_thickbox', plugin_dir_url(__FILE__) . 'shortcodes/imic-shortcodes/shortcodes-style.css', array('thickbox'), '5.0', 'all');
        wp_enqueue_style('imic_shortcode_base_thickbox', plugin_dir_url(__FILE__) . 'shortcodes/imic-shortcodes/base.css', array('thickbox'), '5.0', 'all');
        wp_enqueue_style('thickbox');

        wp_enqueue_script('thickbox');
      }
      add_action('admin_enqueue_scripts', 'nativechurch_core_shortcode_scripts');

      //SiteOrigin Premium Widgets
      function siteorigin_p_init()
      {
        if (class_exists('SiteOrigin_Panels')) :
          require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'siteorigin-premium/siteorigin-premium.php';
        endif;
      }
      add_action('plugins_loaded', 'siteorigin_p_init');

      // Settings Page
      class NativeThemeSettings
      {
        private $native_theme_settings_options;

        public function __construct()
        {
          add_action('admin_menu', array($this, 'native_theme_settings_add_plugin_page'));
          add_action('admin_init', array($this, 'native_theme_settings_page_init'));
        }

        public function native_theme_settings_add_plugin_page()
        {
          add_options_page(
            'Native Theme Settings', // page_title
            'Native Theme Settings', // menu_title
            'manage_options', // capability
            'native-theme-settings', // menu_slug
            array($this, 'native_theme_settings_create_admin_page') // function
          );
        }

        public function native_theme_settings_create_admin_page()
        {
          $this->native_theme_settings_options = get_option('native_theme_settings_option_name'); ?>

      <div class="wrap">
        <h2>Native Theme Settings</h2>
        <p></p>
        <?php settings_errors(); ?>

        <form method="post" action="options.php">
          <?php
              settings_fields('native_theme_settings_option_group');
              do_settings_sections('native-theme-settings-admin');
              submit_button();
              ?>
        </form>
      </div>
    <?php }

      public function native_theme_settings_page_init()
      {
        register_setting(
          'native_theme_settings_option_group', // option_group
          'native_theme_settings_option_name', // option_name
          array($this, 'native_theme_settings_sanitize') // sanitize_callback
        );

        add_settings_section(
          'native_theme_settings_setting_section', // id
          'Settings', // title
          array($this, 'native_theme_settings_section_info'), // callback
          'native-theme-settings-admin' // page
        );

        add_settings_field(
          'elementor_demo_active_0', // id
          'Elementor Demo active', // title
          array($this, 'elementor_demo_active_0_callback'), // callback
          'native-theme-settings-admin', // page
          'native_theme_settings_setting_section' // section
        );
      }

      public function native_theme_settings_sanitize($input)
      {
        $sanitary_values = array();
        if (isset($input['elementor_demo_active_0'])) {
          $sanitary_values['elementor_demo_active_0'] = $input['elementor_demo_active_0'];
        }

        return $sanitary_values;
      }

      public function native_theme_settings_section_info()
      { }

      public function elementor_demo_active_0_callback()
      {
        printf(
          '<input type="checkbox" name="native_theme_settings_option_name[elementor_demo_active_0]" id="elementor_demo_active_0" value="elementor_demo_active_0" %s> <label for="elementor_demo_active_0">This option is for the Elementor demos, If enabled then the regular theme options of the Classic demo will not work.</label>',
          (isset($this->native_theme_settings_options['elementor_demo_active_0']) && $this->native_theme_settings_options['elementor_demo_active_0'] === 'elementor_demo_active_0') ? 'checked' : ''
        );
      }
    }
    if (is_admin())
      $native_theme_settings = new NativeThemeSettings();

    /* 
 * Retrieve this value with:
 * $native_theme_settings_options = get_option( 'native_theme_settings_option_name' ); // Array of All Options
 * $elementor_demo_active_0 = $native_theme_settings_options['elementor_demo_active_0']; // Elementor Demo active
 */
