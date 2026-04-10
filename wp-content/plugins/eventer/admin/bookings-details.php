<?php
add_action('admin_menu', function () {
  add_submenu_page(
    null,
    esc_html__('Eventer Booking Info', 'eventer'),
    esc_html__('Eventer Booking Info', 'textdomain'),
    'manage_options',
    'eventer-booking-info',
    'eventer_booking_details'
  );
});

function eventer_booking_details()
{
  echo '<style>
    body {font-family: Arial;}
    
    /* Style the tab */
    .tab {
      overflow: hidden;
      border: 1px solid #ccc;
      background-color: #f1f1f1;
    }
    
    /* Style the buttons inside the tab */
    .tab button {
      background-color: inherit;
      float: left;
      border: none;
      outline: none;
      cursor: pointer;
      padding: 14px 16px;
      transition: 0.3s;
      font-size: 17px;
    }
    
    /* Change background color of buttons on hover */
    .tab button:hover {
      background-color: #ddd;
    }
    
    /* Create an active/current tablink class */
    .tab button.active {
      background-color: #ccc;
    }
    
    /* Style the tab content */
    .tabcontent {
      display: none;
      padding: 6px 12px;
      border: 1px solid #ccc;
      border-top: none;
    }
    
    /* Style the close button */
    .topright {
      float: right;
      cursor: pointer;
      font-size: 28px;
    }
    
    .topright:hover {color: red;}
    </style>';
  $registrant = (isset($_REQUEST['registrant'])) ? absint($_REQUEST['registrant']) : 0;
  if ($registrant) {
    eventer_render_booking_details_page($registrant);
  } else {
    echo "No details found here";
  }
}

function eventer_booking_user_details_update()
{
  eventer_handle_booking_user_details_update();
}
add_action('wp_ajax_eventer_booking_user_details_update', 'eventer_booking_user_details_update');

function eventer_booking_user_settings_update()
{
  eventer_handle_booking_user_settings_update();
}
add_action('wp_ajax_eventer_booking_user_settings_update', 'eventer_booking_user_settings_update');
