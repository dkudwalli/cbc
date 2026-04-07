<?php
get_header();
$options = get_option('imic_options');
?>
<!-- start page section -->
<section class="page-section">
    <div class="container">
        <div class="row main-content-row">
            <!-- start post -->
            <article class="col-md-12">
                <section class="page-content">
                    <?php
                    while (have_posts()) : the_post();
                        the_content();
                    endwhile;
                    ?>
                </section>
                <?php 
				if (isset($options['switch_sharing']) && $options['switch_sharing'] == 1) {
					if($options['share_post_types']['2'] == '1'){
						imic_share_buttons();
					}
				}
				// Comments
				if (isset($options['disable_page_comments']) && $options['disable_page_comments'] == 0) {
            		echo '';
				} else {
					comments_template('', true);
				}?>
            </article>
            <!-- end post -->
        </div>
    </div>
</section>
<?php get_footer(); ?>