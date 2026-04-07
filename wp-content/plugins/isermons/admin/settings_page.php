<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit;
}

// Start Class
if (!class_exists('iSermons_Settings_Options')) {

  class iSermons_Settings_Options
  {

    /**
     * Start things up
     *
     * @since 1.0.0
     */
    public function __construct()
    {

      // We only need to register the admin panel on the back-end
      if (is_admin()) {
        add_action('admin_menu', array('iSermons_Settings_Options', 'add_admin_menu'));
        add_action('admin_init', array('iSermons_Settings_Options', 'register_settings'));
      }
    }

    public static function isermons_register_post_type()
    {
      isermons_register_post_type();
    }

    public static function get_isermons_options()
    {
      return get_option('isermons_options');
    }

    /**
     * Returns single theme option
     *
     * @since 1.0.0
     */
    public static function get_isermons_option($id)
    {
      $options = self::get_isermons_options();
      if (isset($options[$id])) {
        return $options[$id];
      }
    }

    /**
     * Add sub menu page
     *
     * @since 1.0.0
     */
    public static function add_admin_menu()
    {
      add_submenu_page(
        'edit.php?post_type=imi_isermons',
        esc_html__('Settings', 'isermons'),
        esc_html__('Settings', 'isermons'),
        'manage_options',
        'isermons_settings_options',
        array('iSermons_Settings_Options', 'create_admin_page')
      );
    }

    /**
     * Register a setting and its sanitization callback.
     * @since 1.0.0
     */
    public static function register_settings()
    {
      register_setting('isermons_options', 'isermons_options', array('iSermons_Settings_Options', 'sanitize'));
    }

    /**
     * Sanitization callback
     *
     * @since 1.0.0
     */
    public static function sanitize($options)
    {

      $options = (empty($options)) ? array() : $options;
      $sb = get_option('isermons_options');
      foreach ($sb as $key => $value) {
        if (array_key_exists($key, $options)) {
          if (is_array($options[$key])) {
            $options[$key] = $options[$key];
          } else {
            $options[$key] = $options[$key];
          }
        } else {
          if (is_array($value)) {
            $options[$key] = $value;
          } else {
            if ($key == "payment_confirmation_content" || $key == "pre_registration_content" || $key == "contact_organizer_fields" || $key == "ticket_booking_fields" || $key == 'isermons_sermons_podcast_cover') {
              $options[$key] = $value;
            } else {
              $options[$key] = sanitize_text_field($value);
            }
          }
        }
      }
      return $options;
    }

    /**
     * Settings page output
     *
     * @since 1.0.0
     */
    public static function create_admin_page()
    { ?>

      <div class="wrap">

        <h1><?php esc_html_e('Sermon Options', 'isermons'); ?></h1>
        <?php
              $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
              if (isset($_GET['tab'])) $active_tab = $_GET['tab'];
              ?>
        <h2 class="nav-tab-wrapper">

          <a href="edit.php?post_type=imi_isermons&page=isermons_settings_options&amp;tab=general" class="nav-tab <?php echo ($active_tab == 'general') ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'isermons'); ?></a>

          <a href="edit.php?post_type=imi_isermons&page=isermons_settings_options&amp;tab=isermons_details" class="nav-tab <?php echo ($active_tab == 'isermons_details') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Details Page', 'isermons'); ?></a>

          <a href="edit.php?post_type=imi_isermons&page=isermons_settings_options&amp;tab=taxonomy_page" class="nav-tab <?php echo ($active_tab == 'taxonomy_page') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Taxonomy Page', 'isermons'); ?></a>

          <a href="edit.php?post_type=imi_isermons&page=isermons_settings_options&amp;tab=isermons_permalink" class="nav-tab <?php echo ($active_tab == 'isermons_permalink') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Permalinks', 'isermons'); ?></a>

          <a href="edit.php?post_type=imi_isermons&page=isermons_settings_options&amp;tab=isermons_template" class="nav-tab <?php echo ($active_tab == 'isermons_template') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Templates', 'isermons'); ?></a>

          <a href="edit.php?post_type=imi_isermons&page=isermons_settings_options&amp;tab=isermons_podcast" class="nav-tab <?php echo ($active_tab == 'isermons_podcast') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Podcast', 'isermons'); ?></a>

          <a href="edit.php?post_type=imi_isermons&page=isermons_settings_options&amp;tab=isermons_retagger" class="nav-tab <?php echo ($active_tab == 'isermons_retagger') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Retagger', 'isermons'); ?></a>

          <a href="edit.php?post_type=imi_isermons&page=isermons_settings_options&amp;tab=import" class="nav-tab <?php echo ($active_tab == 'import') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Import Sermons', 'isermons'); ?></a>

          <a href="edit.php?post_type=imi_isermons&page=isermons_settings_options&amp;tab=db_update" class="nav-tab <?php echo ($active_tab == 'db_update') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('DB Update', 'isermons'); ?></a>



        </h2>
        <form method="post" action="options.php">

          <?php settings_fields('isermons_options'); ?>
          <?php if ($active_tab == 'general') { ?>
            <h3><?php _e('Sermons Basic Settings', 'isermons'); ?></h3>
            <div id="general-settings" class="ui-sortable meta-box-sortables">
              <div class="postbox">

                <div class="inside">
                  <table class="form-table wpex-custom-admin-login-table">

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Default color', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_default_color'); ?>
                        <input type="text" class="isermons_default_color" name="isermons_options[isermons_default_color]" value="<?php echo esc_attr($value); ?>">
                        <p class="description"><?php esc_html_e('Choose your desired color to be used for the highlighted parts of the full iSermons plugin.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon categories', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_taxonomy_categories'); ?>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('categories', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_categories][]" value="categories"><?php esc_html_e('Enable categories', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('hierarchical', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_categories][]" value="hierarchical"><?php esc_html_e('Hierarchical', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('filters', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_categories][]" value="filters"><?php esc_html_e('Hierarchical for filters', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('column', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_categories][]" value="column"><?php esc_html_e('Show admin column', 'isermons'); ?>

                        </label>
                        <p class="description"><?php esc_html_e('Select settings to use for sermon categories.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon series', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_taxonomy_series'); ?>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('series', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_series][]" value="series"><?php esc_html_e('Enable series', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('hierarchical', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_series][]" value="hierarchical"><?php esc_html_e('Hierarchical', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('filters', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_series][]" value="filters"><?php esc_html_e('Hierarchical for filters', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('column', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_series][]" value="column"><?php esc_html_e('Show admin column', 'isermons'); ?>

                        </label>
                        <p class="description"><?php esc_html_e('Select settings to use for sermon series.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon books', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_taxonomy_books'); ?>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('books', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_books][]" value="books"><?php esc_html_e('Enable books', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('hierarchical', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_books][]" value="hierarchical"><?php esc_html_e('Hierarchical', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('filters', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_books][]" value="filters"><?php esc_html_e('Hierarchical for filters', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('column', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_books][]" value="column"><?php esc_html_e('Show admin column', 'isermons'); ?>

                        </label>
                        <p class="description"><?php esc_html_e('Select settings to use for sermon books.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon topics', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_taxonomy_topics'); ?>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('topics', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_topics][]" value="topics"><?php esc_html_e('Enable topics', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('hierarchical', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_topics][]" value="hierarchical"><?php esc_html_e('Hierarchical', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('filters', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_topics][]" value="filters"><?php esc_html_e('Hierarchical for filters', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('column', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_topics][]" value="column"><?php esc_html_e('Show admin column', 'isermons'); ?>

                        </label>
                        <p class="description"><?php esc_html_e('Select settings to use for sermon topics.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon preachers', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_taxonomy_preachers'); ?>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('preachers', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_preachers][]" value="preachers"><?php esc_html_e('Enable preachers', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('hierarchical', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_preachers][]" value="hierarchical"><?php esc_html_e('Hierarchical', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('filters', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_preachers][]" value="filters"><?php esc_html_e('Hierarchical for filters', 'isermons'); ?>

                        </label>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('column', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_taxonomy_preachers][]" value="column"><?php esc_html_e('Show admin column', 'isermons'); ?>

                        </label>
                        <p class="description"><?php esc_html_e('Select settings to use for sermon preachers.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon archive', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_sermons_archive_switch'); ?>
                        <label><input type="radio" <?php echo (($value == 'on') ? 'checked' : ''); ?> class="" name="isermons_options[isermons_sermons_archive_switch]" value="on"><?php esc_html_e('Enable archive', 'isermons'); ?>

                        </label>
                        <label><input type="radio" <?php echo (($value == 'off') ? 'checked' : ''); ?> class="" name="isermons_options[isermons_sermons_archive_switch]" value="off"><?php esc_html_e('Disable archive', 'isermons'); ?>

                        </label>
                        <p class="description"><?php esc_html_e('Check enable if you would like to enable archive for sermons.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon taxonomy', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_sermons_taxonomy_template'); ?>
                        <label><input type="radio" <?php echo (($value == "on") ? 'checked' : ''); ?> class="" name="isermons_options[isermons_sermons_taxonomy_template]" value="on"><?php esc_html_e('Use default', 'isermons'); ?>

                        </label>
                        <label><input type="radio" <?php echo (($value == "off") ? 'checked' : ''); ?> class="" name="isermons_options[isermons_sermons_taxonomy_template]" value="off"><?php esc_html_e('Use Custom template', 'isermons'); ?>

                        </label>
                        <p class="description"><?php esc_html_e('Check default if you would like to use default taxonomy template for terms, you need to create isermons.php file for that.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Audio Download', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_enable_audio_download'); ?>
                        <label><input type="radio" <?php echo (($value == 'on') ? 'checked' : ''); ?> class="" name="isermons_options[isermons_enable_audio_download]" value="on"><?php esc_html_e('Enable', 'isermons'); ?>

                        </label>
                        <label><input type="radio" <?php echo (($value == 'off') ? 'checked' : ''); ?> class="" name="isermons_options[isermons_enable_audio_download]" value="off"><?php esc_html_e('Disable', 'isermons'); ?>

                        </label>
                        <p class="description"><?php esc_html_e('Enable to allow your website visitors to download sermons audio .mp3 files.', 'isermons'); ?></p>
                      </td>
                    </tr>
					  
                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Display Date', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_date_type'); ?>
                        <select name="isermons_options[isermons_date_type]">
                          <?php
                                  $options = array(
                                    '' => esc_html__('Preached', 'isermons'),
                                    'publish' => esc_html__('Published', 'isermons'),
                                  );
                                  foreach ($options as $id => $label) { ?>
                            <option value="<?php echo esc_attr($id); ?>" <?php selected($value, $id, true); ?>>
                              <?php echo strip_tags($label); ?>
                            </option>
                          <?php } ?>
                        </select>
                        <p class="description"><?php esc_html_e('Choose the date type displays on the sermons list, details page. Date Preached is a custom date field whereas Date Published is the date when the sermon was added/published on the website.', 'isermons'); ?></p>
                      </td>
                    </tr>

                  </table>
                </div>
              </div>
            </div>
          <?php } ?>
          <!--isermons Permalink Settings Tab-->
          <?php if ($active_tab == 'isermons_permalink') { ?>
            <h3><?php esc_html_e('Sermons Permalink Settings', 'isermons'); ?></h3>
            <h5><?php esc_html_e('Please update permalinks settings page if did any changes in below fields', 'isermons'); ?></h5>
            <div id="permalink-settings" class="ui-sortable meta-box-sortables">
              <div class="postbox">

                <div class="inside">
                  <table class="form-table wpex-custom-admin-login-table">

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon Posts', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_permalink'); ?>
                        <input type="text" name="isermons_options[isermons_sermons_permalink]" value="<?php echo esc_attr($value); ?>">
                        <p class="description"><?php esc_html_e('Use this field to change slug of sermon post type for front end.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon Category Permalink', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_category_permalink'); ?>
                        <input type="text" name="isermons_options[isermons_sermons_category_permalink]" value="<?php echo esc_attr($value); ?>">
                        <p class="description"><?php esc_html_e('Use this field to change slug of sermon categories taxonomy for front end.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon Series Permalink', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_series_permalink'); ?>
                        <input type="text" name="isermons_options[isermons_sermons_series_permalink]" value="<?php echo esc_attr($value); ?>">
                        <p class="description"><?php esc_html_e('Use this field to change slug of sermon series taxonomy for front end.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon Books Permalink', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_books_permalink'); ?>
                        <input type="text" name="isermons_options[isermons_sermons_books_permalink]" value="<?php echo esc_attr($value); ?>">
                        <p class="description"><?php esc_html_e('Use this field to change slug of sermon books taxonomy for front end.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon Topics Permalink', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_topics_permalink'); ?>
                        <input type="text" name="isermons_options[isermons_sermons_topics_permalink]" value="<?php echo esc_attr($value); ?>">
                        <p class="description"><?php esc_html_e('Use this field to change slug of sermon topics taxonomy for front end.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon Preachers Permalink', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_preachers_permalink'); ?>
                        <input type="text" name="isermons_options[isermons_sermons_preachers_permalink]" value="<?php echo esc_attr($value); ?>">
                        <p class="description"><?php esc_html_e('Use this field to change slug of sermon preachers taxonomy for front end.', 'isermons'); ?></p>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          <?php } ?>
          <!--isermons Details Settings Tab-->
          <?php if ($active_tab == 'isermons_details') { ?>
            <h3><?php esc_html_e('Sermon Details Page', 'isermons'); ?></h3>
            <div id="details-settings" class="ui-sortable meta-box-sortables">
              <div class="postbox">

                <div class="inside">
                  <h4><?php esc_html_e('These settings will only work when details page shortcodes are not using in sermon editor.', 'isermons'); ?></h4>
                  <table class="form-table wpex-custom-admin-login-table">
                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon meta data', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_details_meta'); ?>
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('categories', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_details_meta][]" value="categories"><?php esc_html_e('Categories', 'isermons'); ?>
                        </label><br />
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('series', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_details_meta][]" value="series"><?php esc_html_e('Series', 'isermons'); ?>
                        </label><br />
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('books', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_details_meta][]" value="books"><?php esc_html_e('Books', 'isermons'); ?>
                        </label><br />
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('topics', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_details_meta][]" value="topics"><?php esc_html_e('Topics', 'isermons'); ?>
                        </label><br />
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('preacher', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_details_meta][]" value="preacher"><?php esc_html_e('Preachers', 'isermons'); ?>
                        </label><br />
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('chapter', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_details_meta][]" value="chapter"><?php esc_html_e('Chapter', 'isermons'); ?>
                        </label><br />
                        <label><input type="checkbox" <?php echo ((is_array($value) && in_array('date', $value) ? 'checked' : '')); ?> class="" name="isermons_options[isermons_details_meta][]" value="date"><?php esc_html_e('Date', 'isermons'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('Select meta data for sermon details page.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Next/Prev Links', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_enable_np_links'); ?>
                        <label><input type="radio" <?php echo (($value == 'on') ? 'checked' : ''); ?> class="" name="isermons_options[isermons_enable_np_links]" value="on"><?php esc_html_e('Enable', 'isermons'); ?>

                        </label>
                        <label><input type="radio" <?php echo (($value == 'off') ? 'checked' : ''); ?> class="" name="isermons_options[isermons_enable_np_links]" value="off"><?php esc_html_e('Disable', 'isermons'); ?>

                        </label>
                        <p class="description"><?php esc_html_e('Enable to show links for the next and previous sermon posts on the single sermon pages.', 'isermons'); ?></p>
                      </td>
                    </tr>


                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Related sermons', 'isermons'); ?><br />
                        <?php $value = self::get_isermons_option('isermons_details_related'); ?>
                        <label><input type="radio" <?php echo (($value == 'related') ? 'checked' : ''); ?> class="" name="isermons_options[isermons_details_related]" value="related"><?php esc_html_e('Enable', 'isermons'); ?>
                        </label>
                        <label><input type="radio" <?php echo (($value == 'no') ? 'checked' : ''); ?> class="" name="isermons_options[isermons_details_related]" value="no"><?php esc_html_e('Disable', 'isermons'); ?>
                        </label>
                      </th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_sermons_related_taxonomy'); ?>
                        <?php wp_editor($value, 'isermons_sermons_related_taxonomy', array('textarea_rows' => 4, 'textarea_name' => 'isermons_options[isermons_sermons_related_taxonomy]')); ?>
                        <p class="description"><?php esc_html_e('Add shortcode for recent sermons.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Related terms', 'isermons'); ?><br />
                        <?php $value = self::get_isermons_option('isermons_details_recent'); ?>
                        <label><input type="radio" <?php echo (($value == 'recent') ? 'checked' : ''); ?> class="" name="isermons_options[isermons_details_recent]" value="recent"><?php esc_html_e('Enable', 'isermons'); ?>
                        </label>
                        <label><input type="radio" <?php echo (($value == 'no') ? 'checked' : ''); ?> class="" name="isermons_options[isermons_details_recent]" value="no"><?php esc_html_e('Disable', 'isermons'); ?>
                        </label>
                      </th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_terms_related_taxonomy'); ?>
                        <?php wp_editor($value, 'isermons_terms_related_taxonomy', array('textarea_rows' => 4, 'textarea_name' => 'isermons_options[isermons_terms_related_taxonomy]')); ?>
                        <p class="description"><?php esc_html_e('Add shortcode for recent terms.', 'isermons'); ?></p>
                      </td>
                    </tr>

                  </table>
                </div>
              </div>
            </div>

          <?php } ?>
          <!--Sermons Taxonomy Page Tab-->
          <?php if ($active_tab == 'taxonomy_page') { ?>
            <h3><?php esc_html_e('Taxonomy Page Settings', 'isermons'); ?></h3>
            <div id="template-settings" class="ui-sortable meta-box-sortables">
              <div class="postbox">

                <div class="inside">
                  <table class="form-table wpex-custom-admin-login-table">

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Order By', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_tax_orderby'); ?>
                        <select name="isermons_options[isermons_tax_orderby]">
                          <?php
                                  $options = array(
                                    'date' => esc_html__('Published Date', 'isermons'),
                                    'meta_value' => esc_html__('Preached Date', 'isermons'),
                                    'ID' => esc_html__('ID', 'isermons'),
                                    'title' => esc_html__('Title', 'isermons'),
                                    'name' => esc_html__('Name', 'isermons'),
                                  );
                                  foreach ($options as $id => $label) { ?>
                            	<option value="<?php echo esc_attr($id); ?>" <?php selected($value, $id, true); ?>>
                              <?php echo strip_tags($label); ?>
                            </option>
                          <?php } ?>
                        </select>
                        <p class="description"><?php esc_html_e('Order sermons by on sermon taxonomy page like on series, topics etc.', 'isermons'); ?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Order', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_tax_order'); ?>
                        <select name="isermons_options[isermons_tax_order]">
                          <?php
                                  $options = array(
                                    'DESC' => esc_html__('Descending', 'isermons'),
                                    'ASC' => esc_html__('Ascending', 'isermons'),
                                  );
                                  foreach ($options as $id => $label) { ?>
                            	<option value="<?php echo esc_attr($id); ?>" <?php selected($value, $id, true); ?>>
                              <?php echo strip_tags($label); ?>
                            </option>
                          <?php } ?>
                        </select>
                        <p class="description"><?php esc_html_e('Order of the sermons on sermon taxonomy page like on series, topics etc.', 'isermons'); ?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon button text(Video)', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_term_sermons_watch'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_term_sermons_watch]" placeholder="<?php esc_html_e('Watch Sermon', 'isermons'); ?>" value="<?php echo esc_attr($value); ?>">
                        <p class="description"><?php esc_html_e('Button text for sermons with video media.', 'isermons'); ?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon button text(Audio)', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_term_sermons_listen'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_term_sermons_listen]" placeholder="<?php esc_html_e('Listen Sermon', 'isermons'); ?>" value="<?php echo esc_attr($value); ?>">
                        <p class="description"><?php esc_html_e('Button text for sermons without video media.', 'isermons'); ?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermon button text(No Audio/Video)', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_term_sermons_details'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_term_sermons_details]" placeholder="<?php esc_html_e('View Sermon', 'isermons'); ?>" value="<?php echo esc_attr($value); ?>">
                        <p class="description"><?php esc_html_e('Button text for sermons without video/audio media.', 'isermons'); ?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermons per page', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_term_sermons_page'); ?>
                        <input type="number" size="50" name="isermons_options[isermons_term_sermons_page]" placeholder="<?php esc_html_e('Eg. 10', 'isermons'); ?>" value="<?php echo esc_attr($value); ?>">
                        <p class="description"><?php esc_html_e('Show sermons per page on the taxonomy page. Enter -1 to show all the sermons at a time.', 'isermons'); ?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Related Taxonomy Grid Columns', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_related_term_col'); ?>
                        <select name="isermons_options[isermons_related_term_col]">
                          <?php
                                  $options = array(
                                    '4' => esc_html__('4', 'isermons'),
                                    '3' => esc_html__('3', 'isermons'),
                                    '2' => esc_html__('2', 'isermons'),
                                    '1' => esc_html__('1', 'isermons'),
                                  );
                                  foreach ($options as $id => $label) { ?>
                            	<option value="<?php echo esc_attr($id); ?>" <?php selected($value, $id, true); ?>>
                              <?php echo strip_tags($label); ?>
                            </option>
                          <?php } ?>
                        </select>
                        <p class="description"><?php esc_html_e('select related taxonomy terms layout column to be used on the single taxonomy page like series, book...', 'isermons'); ?></p>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>


          <?php } ?>
          <!--Sermons Template Settings Tab-->
          <?php if ($active_tab == 'isermons_template') { ?>
            <h3><?php esc_html_e('Sermons Template Settings', 'isermons'); ?></h3>
            <div id="template-settings" class="ui-sortable meta-box-sortables">
              <div class="postbox">

                <div class="inside">
                  <table class="form-table wpex-custom-admin-login-table">

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sermons Archive', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_template'); ?>
                        <input type="text" class="isermons-admin-templates" name="" value="<?php echo get_the_title($value); ?>">
                        <input type="hidden" name="isermons_options[isermons_sermons_template]" value="<?php echo esc_attr($value); ?>">
                        <p class="description"><?php esc_html_e('Use this page as iSermons archive page. Start typing your page title and select.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <!--<tr valign="top">
							<th scope="row"><?php esc_html_e('Sermon terms template', 'isermons'); ?></th>
							<td>
								<?php $value = self::get_isermons_option('isermons_sermons_term_template'); ?>
								<input type="text" class="isermons-admin-templates" name="" value="<?php echo get_the_title($value); ?>">
								<input type="hidden" name="isermons_options[isermons_sermons_term_template]" value="<?php echo esc_attr($value); ?>">
								<p class="description"><?php esc_html_e('Set template to show terms layout according to theme.', 'isermons'); ?></p>
							</td>
						</tr>-->

                  </table>
                </div>
              </div>
            </div>


          <?php } ?>
          <!--Sermons Podcast Settings Tab-->
          <?php if ($active_tab == 'isermons_podcast') {
                  wp_enqueue_media();
                  ?>
            <h3><?php esc_html_e('Sermons Podcast Settings', 'isermons'); ?></h3>
            <div id="template-settings" class="ui-sortable meta-box-sortables">
              <div class="postbox">

                <div class="inside">
                  <table class="form-table wpex-custom-admin-login-table">
                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Podcast Title', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_title'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_sermons_podcast_title]" placeholder="<?php esc_html_e('e.g. isermons', 'isermons'); ?>" value="<?php echo esc_attr($value); ?>">
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Podcast Description', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_description'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_sermons_podcast_description]" placeholder="<?php esc_html_e('e.g. This is isermons sermon description', 'isermons'); ?>" value="<?php echo esc_attr($value); ?>">
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Website Link', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_web_link'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_sermons_podcast_web_link]" placeholder="<?php echo esc_url(site_url()); ?>" value="<?php echo esc_attr($value); ?>">
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Copyright', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_copyright'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_sermons_podcast_copyright]" placeholder="<?php esc_html_e('e.g. Copyright © isermons', 'isermons'); ?>" value="<?php echo esc_attr($value); ?>">
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Webmaster name', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_webmaster_name'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_sermons_podcast_webmaster_name]" placeholder="<?php esc_html_e('e.g. Primary speaker or church name', 'isermons'); ?>" value="<?php echo esc_attr($value); ?>">
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Webmaster email', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_webmaster_email'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_sermons_podcast_webmaster_email]" placeholder="<?php echo esc_attr(get_option('admin_email')); ?>" value="<?php echo esc_attr($value); ?>">
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Author', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_author'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_sermons_podcast_author]" placeholder="<?php esc_html_e('e.g. Primary speaker or church name', 'isermons'); ?>" value="<?php echo esc_attr($value); ?>">
                        <p><?php esc_html_e('This will display at the "Artist" in the iTunes Store.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Subtitle', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_subtitle'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_sermons_podcast_subtitle]" placeholder="<?php esc_html_e('e.g. Preaching and audio teaching from', 'isermons'); ?>" value="<?php echo esc_attr($value); ?>">
                        <p><?php esc_html_e('Your subtitle should briefly tell the listener what they can expect to hear.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Summary', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_summary'); ?>
                        <textarea size="50" rows="5" cols="50" name="isermons_options[isermons_sermons_podcast_summary]" placeholder="<?php esc_html_e('e.g. Preaching and audio teaching from', 'isermons'); ?>"><?php echo esc_attr($value); ?></textarea>
                        <p><?php esc_html_e('Keep your Podcast Summary short, sweet and informative. Be sure to include a brief statement about your mission and in what region your audio content originates.', 'isermons'); ?></p>

                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Owner name', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_owner_name'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_sermons_podcast_owner_name]" placeholder="<?php esc_html_e('e.g. isermons', 'isermons'); ?>" value="<?php echo esc_attr($value); ?>" />
                        <p><?php esc_html_e('This should typically be the name of your Church.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Owner email', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_owner_email'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_sermons_podcast_owner_email]" placeholder="<?php echo esc_attr(get_option('admin_email')); ?>" value="<?php echo esc_attr($value); ?>" />
                        <p><?php esc_html_e('Use an email address that you dont mind being made public. If someone wants to contact you regarding your Podcast this is the address they will use.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Cover image', 'isermons'); ?></th>
                      <td class="isermons-media-field-area">
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_cover'); ?>
                        <?php
                                echo '<div class="isermons-admin-row">';
                                echo '<div class="isermons-admin-column">';
                                echo '<input maxlength="" type="text" size="50" name="isermons_options[isermons_sermons_podcast_cover]" id="" value="' . $value . '" class="isermons_media_field"/>
                            	<input type="hidden" class="isermons-file-id" value="">', '';
                                echo '</div>';
                                echo '<div class="isermons-admin-column">';
                                echo '<input type="button" class="button isermons-add-file" value="' . esc_html__('Add', 'isermons') . '">';
                                echo '<input type="button" class="isermons-remove-file hidden" value="' . esc_html__('Remove', 'isermons') . '">';
                                echo '</div>';
                                echo '</div>';
                                ?>
                        <p><?php esc_html_e('This JPG will serve as the Podcast artwork in the iTunes Store. The image should be 1400px by 1400px', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Top category', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_top_category'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_sermons_podcast_top_category]" value="<?php echo esc_attr($value); ?>" />
                        <p><?php esc_html_e('Choose the appropriate top-level category for your Podcast listing in iTunes. ', 'isermons'); ?><a href="https://support.imithemes.com/knowledgebase/apple-podcast-category-sub-category/" target="_blank"><?php esc_html_e('Reference', 'isermons'); ?></a></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Sub category', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_sub_category'); ?>
                        <input type="text" size="50" name="isermons_options[isermons_sermons_podcast_sub_category]" value="<?php echo esc_attr($value); ?>" />
                        <p><?php esc_html_e('Choose the appropriate sub category for your Podcast listing in iTunes. ', 'isermons'); ?><a href="https://support.imithemes.com/knowledgebase/apple-podcast-category-sub-category/" target="_blank"><?php esc_html_e('Reference', 'isermons'); ?></a></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Feed URL', 'isermons'); ?></th>
                      <td>
                        <?php $value = self::get_isermons_option('isermons_sermons_podcast_feed_url');
						  if($value == ''){$value = home_url('/') . 'feed/?post_type=imi_isermons';}
						  ?>
                        <input type="text" readonly size="50" name="isermons_options[isermons_sermons_podcast_feed_url]" value="<?php echo esc_attr($value); ?>" />
                        <p><?php esc_html_e('This is your Feed URL to submit to iTunes', 'isermons'); ?></p>
                      </td>
                    </tr>

                  </table>
                </div>
              </div>
            </div>


          <?php } ?>
          <!--Sermons Retagger Settings Tab-->
          <?php if ($active_tab == 'isermons_retagger') {
                  ?>
            <h3><?php esc_html_e('Retagger Settings', 'isermons'); ?></h3>
            <div id="template-settings" class="ui-sortable meta-box-sortables">
              <div class="postbox">

                <div class="inside">
                  <table class="form-table wpex-custom-admin-login-table">
                    <tr valign="top">
                      <td colspan="4">
                        <p class="description"><?php esc_html_e('Below are the options for the bible passage you attach to your sermons. Reftagger automatically tags them, creating tooltips that appear when a reader hovers over them.', 'isermons'); ?></p>
                      </td>
                    </tr>

                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Retagger Popup', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_sermons_retagger_switch'); ?>
                        <label><input type="radio" <?php echo (($value == 'on') ? 'checked' : ''); ?> class="" name="isermons_options[isermons_sermons_retagger_switch]" value="on"><?php esc_html_e('Enable', 'isermons'); ?>

                        </label>
                        <label><input type="radio" <?php echo (($value == 'off') ? 'checked' : ''); ?> class="" name="isermons_options[isermons_sermons_retagger_switch]" value="off"><?php esc_html_e('Disable', 'isermons'); ?>

                        </label>
                        <p class="description"><?php esc_html_e('Select enable to show popup for the bible passage added to the sermons using the Retagger API.', 'isermons'); ?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Bible version', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_sermons_retagger_source'); ?>
                        <select name="isermons_options[isermons_sermons_retagger_source]">
                          <?php
                                  $options = array(
                                    'AMP' => esc_html__('Amplified Bible (AMP)', 'isermons'),
                                    'ASV' => esc_html__('American Standard Version (ASV)', 'isermons'),
                                    'DAR' => esc_html__('Darby', 'isermons'),
                                    'ESV' => esc_html__('English Standard Version (ESV)', 'isermons'),
                                    'GW' => esc_html__('God\'s Word (GW)', 'isermons'),
                                    'HCSB' => esc_html__('Holma Christian Standard Bible (HCSB)', 'isermons'),
                                    'KJV' => esc_html__('King James Version (KJV)', 'isermons'),
                                    'LEB' => esc_html__('Lexham English Bible (LEB)', 'isermons'),
                                    'MESSAGE' => esc_html__('Message Bible', 'isermons'),
                                    'NASB' => esc_html__('New American Standard Bible (NASB)', 'isermons'),
                                    'NCV' => esc_html__('New Century Version (NCV)', 'isermons'),
                                    'NIV' => esc_html__('New International Version (NIV)', 'isermons'),
                                    'NIRV' => esc_html__('New International Reader\'s Version (NIRV)', 'isermons'),
                                    'NKJV' => esc_html__('New King James Version (NKJV)', 'isermons'),
                                    'NLT' => esc_html__('New Living Translation (NLT)', 'isermons'),
                                    'DOUAYRHEIMS' => esc_html__('Douay-Rheims', 'isermons'),
                                    'YLT' => esc_html__('Young Literal Translation (YLT)', 'isermons'),
                                  );
                                  foreach ($options as $id => $label) { ?>
                            <option value="<?php echo esc_attr($id); ?>" <?php selected($value, $id, true); ?>>
                              <?php echo strip_tags($label); ?>
                            </option>
                          <?php } ?>
                        </select>
                        <p class="description"><?php esc_html_e('Choose Bible version of the Bible Passage retagger popups. Default is ESV.', 'isermons'); ?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <th scope="row"><?php esc_html_e('Popup Style', 'isermons'); ?></th>
                      <td colspan="3">
                        <?php $value = self::get_isermons_option('isermons_sermons_retagger_style'); ?>
                        <select name="isermons_options[isermons_sermons_retagger_style]">
                          <?php
                                  $options = array(
                                    '' => esc_html__('Light', 'isermons'),
                                    'dark' => esc_html__('Dark', 'isermons'),
                                  );
                                  foreach ($options as $id => $label) { ?>
                            <option value="<?php echo esc_attr($id); ?>" <?php selected($value, $id, true); ?>>
                              <?php echo strip_tags($label); ?>
                            </option>
                          <?php } ?>
                        </select>
                        <p class="description"><?php esc_html_e('Choose style for the retagger popups.', 'isermons'); ?></p>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>


          <?php } ?>
          <?php
                if ($active_tab != 'import' && $active_tab != 'db_update') {
                  submit_button(esc_html__('Save Changes', 'isermons'));
                } ?>
        </form>
        <?php if ($active_tab == 'import') { ?>
          <h3><?php esc_attr_e('Import Sermons', 'isermons'); ?></h3>
          <div id="payments-settings" class="ui-sortable meta-box-sortables">
            <div class="postbox">
              <div class="inside">
                <p class="description"><?php esc_html_e('This function has been deprecated since version 2.2 of the iSermons plugin. We recommend using ', 'isermons'); ?><a href="https://wordpress.org/plugins/wp-all-import/" target="_blank">WP All Import</a> <?php esc_html_e('plugin to import your posts from CSV/XML','isermons'); ?></p>
                <form class="isermons-admin-import-sermons" action="">
                  <input type="file" name="isermons-admin-import-file" class="isermons-admin-import-file">
                  <!--<input type="submit" value="Submit">-->
                </form>
                <div class="row">
                  <div class="column">
                    <?php echo '<h3>' . esc_html__('CSV Labels', 'isermons') . '</h3>'; ?>
                  </div>
                  <div class="column">
                    <?php echo '<h3>' . esc_html__('Import Field', 'isermons') . '</h3>'; ?>
                  </div>

                </div>
                <input type="button" class="button isermons-initiate-import" value="Import">
              </div>
            </div>
          </div>
        <?php } ?>
        <?php if ($active_tab == 'db_update') { ?>
          <h3><?php esc_attr_e('Database Update', 'isermons'); ?></h3>
          <div id="db-update-settings" class="ui-sortable meta-box-sortables">
            <div class="postbox">
              <div class="inside">
				<p class="description" style="color: red"><?php esc_html_e('USE THIS OPTION CAREFULLY. A FULL DATABASE BACKUP IS RECOMMENDED.', 'isermons'); ?></p>
				<p class="description"><?php esc_html_e('In the version 2.2 of the plugin we have introduced a new functionality to sort sermons by the custom Preached Date field. To use this function we must need to covert the date format in this field to a new more usable format. If you are planning to order sermons by preached date then this DB update is required to be completed beforehand. Please note this is only required if you are using an older version of the plugin and currently have sermons added to your website.', 'isermons'); ?></p>
                <form id="isermons_update_form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
					<?php wp_nonce_field('isermons_update_date_format', 'isermons_nonce'); ?>
					<input type="hidden" name="action" value="update_date_format">
					<p>
						<input type="submit" id="isermons_update_button" name="submit" class="button button-primary" value="Update Date Format">
					</p>
					<progress id="isermons_progress_bar" value="0" max="100" style="display: none; width: 100%;"></progress>
					<div id="isermons_message_box" style="display: none; margin-top: 10px;"></div>
				</form>
				<p class="description"><?php esc_html_e('Click on the button above to start the process. You will be redirected back to this page with success message once the process is complete.', 'isermons'); ?></p>
              </div>
            </div>
          </div>
        <?php } ?>
      </div><!-- .wrap -->
<?php }
  }
}
new iSermons_Settings_Options();
