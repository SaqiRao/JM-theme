<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package JM-theme
 */

get_header();
?>
<div class="container">
	<div class="row">
		<div class="col-md-9">
		<main id="primary" class="site-main" role="main">

		<div id="post-container">
    <?php
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 6, // Initial posts to show
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
    ?>
            <div class="post">
                <h2><?php the_title(); ?></h2>
                <p><?php the_excerpt(); ?></p>
            </div>
    <?php
        endwhile;
        wp_reset_postdata();
    endif;
    ?>
</div>
<button id="load-more" data-page="1" data-url="<?php echo admin_url('admin-ajax.php'); ?>">Load More</button>


</main>
<!-- #main -->
	</div>
	<div class="col-md-3">
		
<?php
get_sidebar();
?>
</div>
</div>
</div>
<?php
get_footer();
