<?php
$organizer = eventer_get_terms_front('eventer-organizer',  $event_id, array('organizer_phone', 'organizer_email', 'organizer_website', 'organizer_image'));
$organizer = isset($organizer[0]) ? $organizer[0] : [];
$nimgcont = '';
if (!$organizer) return;
?>
<div class="eventer-organizer-block eventer-cfloat">
  <?php if ($organizer['metas']['organizer_image'] != '') {
    $image_src = wp_get_attachment_image_src($organizer['metas']['organizer_image']);
    $image = isset($image_src[0]) ? $image_src[0] : '';
	$nimgcont = 'eventer-organizer-info-wi'; ?>
    <div class="eventer-organizer-image">
      <span style="background-image:url(<?php echo $image; ?>)"></span>
      <a rel="emodal:open" title="Contact" href="#eventer-contact-form" class="eventer-btn eventer-btn-plain et_smooth_scroll_disabled"><?php esc_attr_e('Contact', 'eventer'); ?></a>
    </div>
  <?php } ?>
  <div class="eventer-organizer-info <?php echo esc_attr($nimgcont); ?>">
    <span><?php esc_attr_e('Organized By', ' eventer'); ?></span>
    <strong><?php echo esc_attr($organizer['name']); ?></strong>
    <ul>
      <?php if ($organizer['metas']['organizer_phone'] != '') { ?>
        <li><i class="eventer-icon-phone"></i> <a href="tel:<?php echo esc_attr($organizer['metas']['organizer_phone']); ?>"><?php echo esc_attr($organizer['metas']['organizer_phone']); ?></a></li>
      <?php }
      if ($organizer['metas']['organizer_email'] != '') { ?>
        <li><i class="eventer-icon-envelope"></i> <a href="mailto:<?php echo $organizer['metas']['organizer_email']; ?>"><?php echo $organizer['metas']['organizer_email']; ?></a></li>
      <?php }
      if ($organizer['metas']['organizer_website'] != '') { ?>
        <li><i class="eventer-icon-globe"></i> <a href="<?php echo $organizer['metas']['organizer_website']; ?>"><?php echo $organizer['metas']['organizer_website']; ?></a></li>
      <?php } ?>
    </ul>
	<a rel="emodal:open" title="Contact" href="#eventer-contact-form" class="eventer-btn eventer-btn-plain et_smooth_scroll_disabled"><?php esc_attr_e('Contact', 'eventer'); ?></a>
  </div>
</div>