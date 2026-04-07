<?php

/*** Widget code for Featured Event ***/
class native_core_featured_event extends WP_Widget
{
  // constructor
  public function __construct()
  {
    $widget_ops = array('description' => __("Display Featured Event.", 'imithemes'));
    parent::__construct(false, $name = __('(N) Featured Event', 'imithemes'), $widget_ops);
  }
  // widget form creation
  public function form($instance)
  {
    // Check values
    if ($instance) {
      $title = esc_attr($instance['title']);
      $event = esc_attr($instance['event']);
    } else {
      $title = '';
      $event = '';
    }
    ?>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'imithemes'); ?></label>
      <input class="feTitle_<?php echo esc_attr($title); ?>" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    </p>

    <p>
      <label for="<?php echo esc_attr($this->get_field_id('event')); ?>"><?php _e('Featured Event', 'imithemes'); ?></label>
      <select class="feEvent" id="<?php echo esc_attr($this->get_field_id('event')); ?>" name="<?php echo esc_attr($this->get_field_name('event')); ?>">
        <option value=""><?php _e('Select Event', 'imithemes'); ?></option>
        <?php
            $today = date_i18n('Y-m-d');
            $featuredEvents = query_posts(
              array(
                'post_type' => 'event',
                'meta_key' => 'imic_event_start_dt',
                'meta_query' => array(
                  array(
                    'key' => 'imic_event_featured',
                    'value' => '1',
                    'compare' => '='
                  ),
                ),
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'posts_per_page' => -1
              )
            );
            if (!empty($featuredEvents)) {
              foreach ($featuredEvents as $fevent) {
                $name = $fevent->post_title;
                $id = $fevent->ID;
                $activePost = ($id == $event) ? 'selected' : '';
                echo '<option value="' . $id . '" ' . $activePost . '>' . $name . '</option>';
              }
            }
            ?>
      </select>
    </p>
    <?php
      }
      // update widget
      public function update($new_instance, $old_instance)
      {
        $instance = $old_instance;
        // Fields
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['event'] = strip_tags($new_instance['event']);
        return $instance;
      }
      // display widget
      public function widget($args, $instance)
      {
        extract($args);
        // these are the widget options
        $title = apply_filters('widget_title', $instance['title']);
        $event = apply_filters('widget_event', $instance['event']);

        $Heading = (!empty($title)) ? $title : __('Featured Events', 'imithemes');
        echo '' . $args['before_widget'];
        echo '' . $args['before_title'];
        echo apply_filters('widget_title', $Heading, $instance, $this->id_base);
        echo '' . $args['after_title'];
        $right_time = $showing_featured = '';
        $next_events = imic_recur_events('future', '', '', '', '');
        ksort($next_events);
        if ($event) {
          foreach ($next_events as $key => $value) {
            if ($value == $event) {
              $right_time = $key;
              $showing_featured = 1;
              break;
            }
          }
        }

        foreach ($next_events as $key => $value) {
          if ($showing_featured != 1 && get_post_meta($value, 'imic_event_featured', true) == '1') {
            $right_time = $key;
            $event = $value;
            break;
          }
        }
        if ($right_time != '') {
          //Featured Event Image
          $featuredEvent = get_post($event);
          $date_converted = date_i18n('Y-m-d', $right_time);
          $custom_event_url = imic_query_arg($date_converted, $event);
          $fitimeClass = '';
          if (has_post_thumbnail($event)) :
            echo '<div class="format-standard"><a href="' . $custom_event_url . '" class="media-box">' . get_the_post_thumbnail($event, 'full', array('class' => "featured-event-image")) . '</a></div>';
            $fitimeClass  = 'featured-event-time-floating';
          endif;
          ?>
      <div class="featured-event-container">
        <label class="label label-danger"><?php _e('Upcoming', 'framework'); ?></label>
        <div class="featured-event-time <?php echo esc_attr($fitimeClass); ?>">
          <span class="date"><?php echo date_i18n('d', $right_time); ?></span>
          <span class="month"><?php echo date_i18n('M, Y', $right_time); ?></span>
        </div>
        <h4 class="featured-event-title"><a href="<?php echo esc_url($custom_event_url); ?>"><?php echo get_the_title($event); ?></a></h4>
        <p><?php echo wp_trim_words($featuredEvent->post_content, 20) ?></p>
      </div>
    <?php } else { ?>
      <div class="featured-event-container">
        <h4 class="featured-event-title"><?php _e('No More Future Events', 'imithemes'); ?></h4>

      </div>
<?php }
    echo '' . $args['after_widget'];
  }
}
// register widget
add_action('widgets_init', function () {
  register_widget('native_core_featured_event');
});
