<!--
This template is used to add bible verse retagger script
-->
<?php
$options = get_option('isermons_options');
$retagger_switch = $options['isermons_sermons_retagger_switch'];
$retagger_source = $options['isermons_sermons_retagger_source'];
$retagger_style = $options['isermons_sermons_retagger_style'];

// Add bible verse retagger script
if($retagger_switch == 'on'){
if($retagger_source != ''){
	$source = $retagger_source;
} else {
	$source = 'ESV';
}
if($retagger_style != ''){
	$style = $retagger_style;
} else {
	$style = '';
}
?>
	<script>
		var refTagger = {
			settings: {
				bibleVersion: "<?php echo esc_attr($source); ?>",
				tooltipStyle: "<?php echo esc_attr($style); ?>",
			}
		};
		(function(d, t) {
			var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
			g.src = "https://api.reftagger.com/v2/RefTagger.js";
			s.parentNode.insertBefore(g, s);
		}(document, "script"));
	</script>
<?php } ?>