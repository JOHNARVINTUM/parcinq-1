<?php
/**
 * Template for the Advertise page.
 *
 * Automatically applies to the page with the advertise slug.
 *
 * @package Parcinq_Theme
 */

get_header();

$parcinq_contact_page = get_page_by_path( 'contact' );
$parcinq_contact_url  = $parcinq_contact_page instanceof WP_Post && 'publish' === $parcinq_contact_page->post_status
	? get_permalink( $parcinq_contact_page )
	: home_url( '/contact/' );

$parcinq_advertise_offers = array(
	array(
		'label' => __( 'Print', 'parcinq-theme' ),
		'title' => __( 'Advertorial', 'parcinq-theme' ),
		'copy'  => __( 'A full-page, designed feature in the PARCINQ voice, in our printed issue.', 'parcinq-theme' ),
		'price' => __( 'From ₱40,000', 'parcinq-theme' ),
	),
	array(
		'label' => __( 'Digital', 'parcinq-theme' ),
		'title' => __( 'Branded Feature', 'parcinq-theme' ),
		'copy'  => __( 'A custom story on the site plus distribution across our social channels.', 'parcinq-theme' ),
		'price' => __( 'On request', 'parcinq-theme' ),
	),
	array(
		'label' => __( 'Social', 'parcinq-theme' ),
		'title' => __( 'Campaign', 'parcinq-theme' ),
		'copy'  => __( 'Concepted content for TikTok and Instagram, produced with our team.', 'parcinq-theme' ),
		'price' => __( 'On request', 'parcinq-theme' ),
	),
	array(
		'label' => __( 'Live', 'parcinq-theme' ),
		'title' => __( 'Event Partnership', 'parcinq-theme' ),
		'copy'  => __( 'Co-created events, activations and cover unveilings with full coverage.', 'parcinq-theme' ),
		'price' => __( 'On request', 'parcinq-theme' ),
	),
	array(
		'label' => __( 'Collab', 'parcinq-theme' ),
		'title' => __( 'Editorial Collaboration', 'parcinq-theme' ),
		'copy'  => __( 'A shoot or franchise built around your brand and our point of view.', 'parcinq-theme' ),
		'price' => __( 'On request', 'parcinq-theme' ),
	),
	array(
		'label' => __( 'Bespoke', 'parcinq-theme' ),
		'title' => __( 'Something Else', 'parcinq-theme' ),
		'copy'  => __( 'Tell us what you are trying to do and we will build the right package.', 'parcinq-theme' ),
		'price' => __( 'Let us talk', 'parcinq-theme' ),
	),
);
?>

<main id="primary" class="site-main advertise-page">
	<section class="ed-hero advertise-hero">
		<div class="wrap">
			<span class="bgword" aria-hidden="true"><?php echo esc_html__( 'PARTNER', 'parcinq-theme' ); ?></span>
			<div class="inner">
				<div class="ed-meta"><span><?php echo esc_html__( 'Work With Us', 'parcinq-theme' ); ?></span></div>
				<h1><?php echo esc_html__( 'Advertise', 'parcinq-theme' ); ?></h1>
				<p class="stand"><?php echo esc_html__( 'Reach a young, design-literate audience that actually cares about culture.', 'parcinq-theme' ); ?></p>
				<div class="ruleheavy" aria-hidden="true"></div>
			</div>
		</div>
	</section>

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'advertise-entry' ); ?>>
			<div class="content advertise-content reveal">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
	?>

	<section class="offers advertise-offers reveal" aria-label="<?php echo esc_attr__( 'Advertising and partnership options', 'parcinq-theme' ); ?>">
		<?php foreach ( $parcinq_advertise_offers as $parcinq_offer ) : ?>
			<article class="offer">
				<span class="k"><?php echo esc_html( $parcinq_offer['label'] ); ?></span>
				<h2><?php echo esc_html( $parcinq_offer['title'] ); ?></h2>
				<p><?php echo esc_html( $parcinq_offer['copy'] ); ?></p>
				<div class="price"><?php echo esc_html( $parcinq_offer['price'] ); ?></div>
			</article>
		<?php endforeach; ?>
	</section>

	<section class="cta-band advertise-cta reveal">
		<h2><?php echo esc_html__( 'Request the media kit', 'parcinq-theme' ); ?></h2>
		<p><?php echo esc_html__( 'Send us a note and we will share rates, reach and recent partnership work.', 'parcinq-theme' ); ?></p>
		<a class="btn" href="<?php echo esc_url( $parcinq_contact_url ); ?>"><?php echo esc_html__( 'Contact Partnerships', 'parcinq-theme' ); ?></a>
	</section>
</main>

<?php
get_footer();
