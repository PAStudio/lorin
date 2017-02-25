<?php
/**
 * The frontpage template for displaying posts
 *
 * @package Cryout Creations
 * @subpackage Nirvana
 * @since Nirvana 1.1
 */

$nirvanas = nirvana_get_theme_options();
foreach ($nirvanas as $key => $value) { ${"$key"} = $value; }
?>

		<section id="container" class="one-column <?php //echo nirvana_get_layout_class(); ?>">

			<div id="content" role="main">

			<?php //cryout_before_content_hook();

			$nirvana_old_posts_per_page = get_option( 'posts_per_page' );

			if ( have_posts() ) :

				/* Start the Loop */
				update_option( 'posts_per_page', $nirvanas['nirvana_frontpostscount']);

				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
				$the_query1 = new WP_Query( array('cat'=>get_cat_ID('影片'),'posts_per_page'=>1,'paged'=> $paged) );
        $the_query2 = new WP_Query( array('cat'=>5,'posts_per_page'=>1,'paged'=> $paged) );
        $wp_query = new WP_query();
        $wp_query->posts = array_merge($the_query1->posts,$the_query2->posts);
        $wp_query->post_count = count($wp_query->posts);

				while ( $wp_query->have_posts() ) : $wp_query->the_post();

 		            global $more; $more=0;
					get_template_part( 'content/content', get_post_format() );

				endwhile;

				if($nirvana_pagination=="Enable") nirvana_pagination($the_query->max_num_pages); else nirvana_content_nav( 'nav-below' );

			else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'No Posts to Display', 'nirvana' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php printf(
							__( 'You currently have no published posts. To hide this message either <a href="%s">add some posts</a> or disable displaying posts on the Presentation Page in <a href="%s">theme settings</a>.', 'nirvana' ),
							esc_url( admin_url()."post-new.php"),
							esc_url( admin_url()."themes.php?page=nirvana-page") ); ?>
						</p>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php
			endif;
			update_option( 'posts_per_page', $nirvana_old_posts_per_page);
			//cryout_after_content_hook();
			?>

			</div><!-- #content -->
		<?php //nirvana_get_sidebar(); ?>
		</section><!-- #container -->
