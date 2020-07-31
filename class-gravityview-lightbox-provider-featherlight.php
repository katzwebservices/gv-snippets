<?php
/**
 * Integrate with the Featherlight lightbox and gallery scripts
 * @see https://github.com/noelboss/featherlight/
 * @since TODO
 */

/**
 * Register the Featherlight lightbox
 *
 * @internal
 */
class GravityView_Lightbox_Provider_Featherlight extends GravityView_Lightbox_Provider {

	public static $slug = 'featherlight';

	public static $script_slug = 'gravityview-featherlight';

	public static $style_slug = 'gravityview-featherlight';

	/**
	 * @inheritDoc
	 * @see https://github.com/noelboss/featherlight/#configuration
	 */
	protected function default_settings() {

		$defaults = array(
			'type' => 'image',
			'previousIcon' => '&#9664;',
			'nextIcon' => '&#9654;',
			'galleryFadeIn' => 0,
			'galleryFadeOut' => 0,
		);

		return $defaults;
	}

	/**
	 * @inheritDoc
	 */
	public function output_footer() {

		$settings = self::get_settings();

		$settings = json_encode( $settings );

		?>
		<script>
			jQuery( '.gravityview-featherlight' ).featherlight(<?php echo $settings; ?>);
			jQuery( '.gravityview-featherlight-gallery' ).featherlightGallery(<?php echo $settings; ?>);
		</script>
		<?php
	}

	/**
	 * @inheritDoc
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( self::$script_slug, plugins_url( 'assets/featherlight.min.js', __FILE__ ), array( 'jquery' ), GV_PLUGIN_VERSION );
		wp_enqueue_script( self::$script_slug . '-gallery', plugins_url( 'assets/featherlight.gallery.min.js', __FILE__ ), array( 'jquery', self::$script_slug ), GV_PLUGIN_VERSION );
	}

	/**
	 * @inheritDoc
	 */
	public function enqueue_styles() {
		wp_enqueue_style( self::$style_slug, plugins_url( 'assets/featherlight.min.css', __FILE__ ), array(), GV_PLUGIN_VERSION );
		wp_enqueue_style( self::$style_slug . '-gallery', plugins_url( 'assets/featherlight.gallery.min.css', __FILE__ ), array( self::$style_slug ), GV_PLUGIN_VERSION );
	}

	/**
	 * @inheritDoc
	 */
	public function allowed_atts( $atts = array() ) {

		$atts['data-featherlight']                        = null;
		$atts['data-featherlight-gallery']                = null;
		$atts['data-featherlight-ajax']                   = null;
		$atts['data-featherlight-type']                   = null;
		$atts['data-featherlight-close-on-esc']           = null;
		$atts['data-featherlight-filter']                 = null;
		$atts['data-featherlight-iframe-width']           = null;
		$atts['data-featherlight-iframe-height']          = null;
		$atts['data-featherlight-iframe-style']           = null;
		$atts['data-featherlight-iframe-frameborder']     = null;
		$atts['data-featherlight-iframe-allow']           = null;
		$atts['data-featherlight-iframe-allowfullscreen'] = null;

		return $atts;
	}

	/**
	 * @inheritDoc
	 */
	public function fileupload_link_atts( $link_atts, $field_compat = array(), $context = null ) {

		if ( ! $context->view->settings->get( 'lightbox', false ) ) {
			return $link_atts;
		}

		// Featherlight doesn't like the `rel` used by thickbox
		unset( $link_atts['rel'] );

		$link_atts['class'] = \GV\Utils::get( $link_atts, 'class' );

		if ( $context && ! empty( $context->field->field ) && $context->field->field->multipleFiles ) {
			$link_atts['class'] .= ' gravityview-featherlight-gallery';
		} else {
			$link_atts['class'] .= ' gravityview-featherlight';
		}

		$link_atts['class'] = gravityview_sanitize_html_class( $link_atts['class'] );

		return $link_atts;
	}

}

GravityView_Lightbox::register( 'GravityView_Lightbox_Provider_Featherlight' );