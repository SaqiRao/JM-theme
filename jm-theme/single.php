<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package JM-theme
 */

get_header();
?>
<div class="container">
	<div class="row">
		<div class="col-md-9">
	<main id="primary" class="site-main">
    
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'jm-theme' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'jm-theme' ) . '</span> <span class="nav-title">%title</span>',
				)
			);

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.


		// Fetch ratings from post meta
			$post_ratings = get_post_meta(get_the_ID(), 'user_ratings', true);
			?>
			<div class="rating-dispaly" style="margin-top: 20px; padding: 20px; background-color: #f9f9f9; border: 1px solid #ccc; align-items: center;">
            <?php
			if ($post_ratings && is_array($post_ratings)) {
				echo '<ul style="margin: 0; padding: 0; list-style: none;">';
				
				// Loop through each user's rating
				foreach ($post_ratings as $user_id => $rating) {
					$user_info = get_userdata($user_id);
					$user_name = $user_info ? $user_info->display_name : 'Unknown User';
					
					echo '<li style="margin-bottom: 10px;">';
					
					// Display the user's name
					echo '<strong>' . esc_html($user_name) . ':</strong> ';
					
					// Display stars based on the rating
					echo '<div class="star-rating" style="display: inline-block; margin-left: 5px;">';
					for ($i = 1; $i <= 5; $i++) {
						// Check if the current star should be filled or empty
						$star_class = $i <= $rating ? 'star-filled' : 'star-empty';
						$star_color = $i <= $rating ? 'gold' : '#ccc';
						
						// Display the star
						echo '<span class="' . esc_attr($star_class) . '" style="color: ' . $star_color . '; font-size: 18px;">&#9733;</span>';
					}
					echo '</div>';
					
					echo '</li>';
				}
				echo '</ul>';
			} else {
				echo '<p>No ratings for this post yet.</p>';
			}

		?>
		</div>
		 <?php // if (is_user_logged_in()) : ?> 
		
		<div id="post-rating" style="display: flex; flex-direction: column; gap: 15px; align-items: center; margin-top: 20px;">
		<label for="rating" style="font-size: 20px; font-weight: bold;">Rate this Post:</label>
		<div class="star-rating" style="display: flex; gap: 5px;">
		<?php for ($i = 1; $i <= 5; $i++) : ?>
		<span class="star" data-value="<?php echo $i; ?>" style="font-size: 30px; color: #ccc; cursor: pointer;">&#9733;</span>
		<?php endfor; ?>
		</div>
		<br>
		<button id="submit-rating" data-post-id="<?php echo get_the_ID(); ?>" style="padding: 10px 20px; font-size: 16px; border: none; background-color: #4CAF50; color: white; cursor: pointer; border-radius: 5px; transition: background-color 0.3s;">Submit Rating</button>
		</div>
     
         <div id="rating-result" style="font-size: 16px; color: green; font-weight: bold; align-items: center;"></div>


    <style>
        .star {
            font-size: 24px;
            cursor: pointer;
            color: gray;
        }

        .star:hover {
            color: gold;
        }
    </style>
<?php // else : ?>
    <p>Please log in to rate this post.</p>
<?php// endif; ?>


	</main>
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
