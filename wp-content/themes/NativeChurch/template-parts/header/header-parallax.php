<?php
echo '<style type="text/css">' . "\n";
echo '.body ol.breadcrumb{padding-top:' . $args['breadpad'] . 'px;}';
echo "</style>" . "\n";
?>
<div class="nav-backed-header parallax" style="
<?php
if (isset($args['color'])) {
  ?>
background-color:<?php echo esc_attr($args['color']); ?>;
<?php
} else {
  ?>
background-image:url(<?php echo esc_attr($args['url']); ?>);
  <?php
  }
  ?>
">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <ol class="breadcrumb">
          <?php
          if (function_exists('bcn_display_list')) {
            bcn_display_list();
          }
          ?>
        </ol>
      </div>
    </div>
  </div>
</div>