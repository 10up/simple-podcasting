<?php
/**
 * Body template for onboarding: Step - 2.
 *
 * @package tenup_podcasting
 */

use tenup_podcasting\admin\Onboarding;

?>
<div class="simple-podcasting__onboarding-body simple-podcasting__onboarding-body--step-2">
	<div class="simple-podcasting__panel simple-podcasting__panel--left">
		<div id="simple-podcasting__page-title">
			<?php esc_html_e( 'Well done!', 'simple-podcasting' ); ?>
		</div>

		<p>
			<?php
			printf(
				/* translators: %s podcast term edit page. */
				__( 'You can always edit show details <a href="%s">here</a>.', 'simple-podcasting' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
				esc_url( admin_url( 'edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true' ) )
			);
			?>
		</p>
		<p><?php esc_html_e( 'Now let’s create your show’s first episode:', 'simple-podcasting' ); ?></p>
		<ol>
			<li><?php esc_html_e( 'Create a new post', 'simple-podcasting' ); ?></li>
			<li><?php esc_html_e( 'Assign the post to a show', 'simple-podcasting' ); ?></li>
			<li><?php esc_html_e( 'Insert a podcast block with an audio file into the content', 'simple-podcasting' ); ?></li>
		</ol>
		<p><?php esc_html_e( 'You can then submit the feed URL to podcatchers. The feed will automatically update each time you add a new episode.', 'simple-podcasting' ); ?></p>
		<p><?php esc_html_e( "Let's get started!", 'simple-podcasting' ); ?></p>

		<div class="simple-podcasting__step-2-controls">
			<a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>" class="simple-podcasting__btn simple-podcasting__btn--black" id="simple-podcasting__create-a-new-post-button"><?php esc_html_e( 'Create a new Post', 'simple-podcasting' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true' ) ); ?>"><?php esc_html_e( 'Create another Show', 'simple-podcasting' ); ?></a>
		</div>
	</div>

	<div class="simple-podcasting__panel simple-podcasting__panel--right">
		<div class="simple-podcasting__podcast-block-preview">
			<img
				srcset="<?php echo esc_url( PODCASTING_URL . 'dist/assets/images/podcast-block-preview.png' ); ?>, <?php echo esc_url( PODCASTING_URL . 'dist/assets/images/podcast-block-preview@2x.png' ); ?> 2x"
				src="<?php echo esc_url( PODCASTING_URL . 'dist/assets/images/podcast-block-preview.png' ); ?>"
			/>
		</div>
	</div>
	<?php update_option( 'simple_podcasting_onboarding', Onboarding::STATUS_COMPLETED ); ?>
</div>
