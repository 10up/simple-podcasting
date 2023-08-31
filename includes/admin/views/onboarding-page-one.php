<?php
/**
 * Body template for onboarding: Step - 1.
 *
 * @package tenup_podcasting
 */

?>
<form method="POST" action="">
	<div class="simple-podcasting__onboarding-body simple-podcasting__onboarding-body--step-1">
		<div id="simple-podcasting__page-title">
			<?php esc_html_e( 'Create your first podcast show', 'simple-podcasting' ); ?>
		</div>

		<!-- Podcast name -->
		<div class="simple-podcasting__setting">
			<label class="simple-podcasting__setting-label" for="simple-podcasting-podcast-name"><?php esc_html_e( 'Podcast name*', 'simple-podcasting' ); ?></label>
			<div class="simple-podcasting__setting-input"><input name="podcast-name" id="simple-podcasting-podcast-name" aria-describedby="simple-podcasting__podcast-name-description" required type="text" /></div>
			<div class="simple-podcasting__setting-description" id="simple-podcasting__podcast-name-description"><?php esc_html_e( 'What’s the title of your podcast show that listeners will see?', 'simple-podcasting' ); ?></div>
		</div>

		<!-- Podcast artist/author -->
		<div class="simple-podcasting__setting">
			<label class="simple-podcasting__setting-label" for="simple-podcasting-podcast-artist"><?php esc_html_e( 'Podcast Artist / Author name*', 'simple-podcasting' ); ?></label>
			<div class="simple-podcasting__setting-input"><input name="podcast-artist" id="simple-podcasting-podcast-artist" aria-describedby="simple-podcasting__podcast-artist-description" required type="text" /></div>
			<div class="simple-podcasting__setting-description" id="simple-podcasting__podcast-artist-description"><?php esc_html_e( 'Who’s the artist or author of your podcast show that listeners will see?', 'simple-podcasting' ); ?></div>
		</div>

		<!-- Podcast description -->
		<div class="simple-podcasting__setting">
			<label class="simple-podcasting__setting-label" for="simple-podcasting-podcast-description"><?php esc_html_e( 'Podcast summary*', 'simple-podcasting' ); ?></label>
			<div class="simple-podcasting__setting-input"><textarea name="podcast-description" id="simple-podcasting-podcast-description" aria-describedby="simple-podcasting__podcast-description-description" rows="5" required></textarea></div>
			<div class="simple-podcasting__setting-description" id="simple-podcasting__podcast-description-description"><?php esc_html_e( 'Briefly describe to your listeners what your podcast is about. (No HTML please)', 'simple-podcasting' ); ?></div>
		</div>

		<!-- Cover image -->
		<div class="simple-podcasting__setting">
			<input type="hidden" name="podcast-cover-image-id" id="simple-podcasting-podcast-cover-image-id" aria-describedby="simple-podcasting__cover-image-description" value="" required>
			<label class="simple-podcasting__setting-label" for="simple-podcasting-podcast-cover-image-id"><?php esc_html_e( 'Cover image*', 'simple-podcasting' ); ?></label>
			<div id="simple-podcasting__cover-image-preview"></div>
			<button type="button" class="simple-podcasting__btn simple-podcasting__btn--ghost" id="simple-podcasting__upload-cover-image"><?php esc_html_e( 'Select image', 'simple-podcasting' ); ?></button>
			<div class="simple-podcasting__setting-description" id="simple-podcasting__cover-image-description"><?php esc_html_e( 'Minimum size: 1400px x 1400 px — maximum size: 2048px x 2048px. Make sure the image is square so it will properly display within podcatcher apps.', 'simple-podcasting' ); ?></div>
		</div>

		<!-- Podcast category -->
		<div class="simple-podcasting__setting">
			<label class="simple-podcasting__setting-label" for="simple-podcasting-podcast-category"><?php esc_html_e( 'Podcast category*', 'simple-podcasting' ); ?></label>
			<select name="podcast-category" id="simple-podcasting-podcast-category" aria-describedby="simple-podcasting__podcast-category-description" required>
				<?php foreach ( \tenup_podcasting\get_podcasting_categories_options() as $option_value => $option_label ) : ?>
					<option value="<?php echo esc_attr( $option_value ); ?>"><?php echo esc_html( $option_label ); ?></option>
				<?php endforeach; ?>
			</select>
			<div class="simple-podcasting__setting-description" id="simple-podcasting__podcast-category-description"><?php esc_html_e( 'What’s the category the listeners will use to discover your podcast under when browsing  podcatchers?', 'simple-podcasting' ); ?></div>
		</div>

		<!-- Create button -->
		<button class="simple-podcasting__btn simple-podcasting__btn--black" id="simple-podcasting__create-podcast-button"><?php esc_html_e( 'Create', 'simple-podcasting' ); ?></button>
		<?php wp_nonce_field( 'simple-podcasting-create-show-action', 'simple-podcasting-create-show-nonce-field' ); ?>
	</div>
</form>
