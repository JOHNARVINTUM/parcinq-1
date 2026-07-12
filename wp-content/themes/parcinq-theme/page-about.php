<?php
/**
 * About page template.
 *
 * Automatically applies to the page with slug "about".
 *
 * @package Parcinq_Theme
 */

get_header();

$parcinq_contact_page = get_page_by_path( 'contact' );
$parcinq_contact_url  = $parcinq_contact_page instanceof WP_Post && 'publish' === $parcinq_contact_page->post_status ? get_permalink( $parcinq_contact_page ) : home_url( '/contact/' );
?>

<main id="primary" class="site-main about-page">
	<section class="ed-hero about-hero">
		<div class="wrap">
			<span class="bgword"><?php echo esc_html__( 'ABOUT', 'parcinq-theme' ); ?></span>
			<div class="inner reveal">
				<div class="ed-meta"><span><?php echo esc_html__( 'Who We Are', 'parcinq-theme' ); ?></span></div>
				<h1><?php echo esc_html__( 'About PARCINQ', 'parcinq-theme' ); ?></h1>
				<p class="stand"><?php echo esc_html__( 'An Asian pop culture publication, built in Manila.', 'parcinq-theme' ); ?></p>
			</div>
		</div>
	</section>

	<div class="ruleheavy"></div>

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'about-entry' ); ?>>
			<div class="content about-content reveal">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>

	<section class="stats about-stats reveal" aria-label="<?php echo esc_attr__( 'PARCINQ statistics', 'parcinq-theme' ); ?>">
		<article class="stat">
			<div class="n"><?php echo esc_html__( '2020', 'parcinq-theme' ); ?></div>
			<div class="l"><?php echo esc_html__( 'Founded', 'parcinq-theme' ); ?></div>
		</article>
		<article class="stat">
			<div class="n"><?php echo esc_html__( '50+', 'parcinq-theme' ); ?></div>
			<div class="l"><?php echo esc_html__( 'Magazine Covers', 'parcinq-theme' ); ?></div>
		</article>
		<article class="stat">
			<div class="n"><?php echo esc_html__( '100+', 'parcinq-theme' ); ?></div>
			<div class="l"><?php echo esc_html__( 'Featured Artists', 'parcinq-theme' ); ?></div>
		</article>
		<article class="stat">
			<div class="n"><?php echo esc_html__( 'Asia', 'parcinq-theme' ); ?></div>
			<div class="l"><?php echo esc_html__( 'Stories Across the Region', 'parcinq-theme' ); ?></div>
		</article>
	</section>

	<section class="cta-band about-cta reveal">
		<h2><?php echo esc_html__( 'Want to work with us?', 'parcinq-theme' ); ?></h2>
		<p><?php echo esc_html__( 'For partnerships, press and collaborations, we would love to hear from you.', 'parcinq-theme' ); ?></p>
		<a class="btn" href="<?php echo esc_url( $parcinq_contact_url ); ?>"><?php echo esc_html__( 'Get in Touch', 'parcinq-theme' ); ?></a>
	</section>
</main>

<?php
get_footer();