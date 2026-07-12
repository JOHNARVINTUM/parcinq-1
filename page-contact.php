<?php
/**
 * Contact page template.
 *
 * Automatically applies to the page with slug "contact".
 *
 * @package Parcinq_Theme
 */

get_header();

$parcinq_contacts = array(
	array(
		'label' => __( 'General Inquiries', 'parcinq-theme' ),
		'email' => 'hello@parcinq.com',
	),
	array(
		'label' => __( 'Editorial', 'parcinq-theme' ),
		'email' => 'editorial@parcinq.com',
	),
	array(
		'label' => __( 'Advertising and Partnerships', 'parcinq-theme' ),
		'email' => 'partnerships@parcinq.com',
	),
	array(
		'label' => __( 'Careers', 'parcinq-theme' ),
		'email' => 'careers@parcinq.com',
	),
);
?>

<main id="primary" class="site-main contact-page">
	<section class="ed-hero contact-hero">
		<div class="wrap">
			<span class="bgword"><?php echo esc_html__( 'CONTACT', 'parcinq-theme' ); ?></span>
			<div class="inner reveal">
				<div class="ed-meta"><span><?php echo esc_html__( 'Get in Touch', 'parcinq-theme' ); ?></span></div>
				<h1><?php echo esc_html__( 'Contact', 'parcinq-theme' ); ?></h1>
				<p class="stand"><?php echo esc_html__( 'Press, partnerships, pitches or a simple hello. Reach us here.', 'parcinq-theme' ); ?></p>
			</div>
		</div>
	</section>

	<div class="ruleheavy"></div>

	<section class="contact-grid contact-page-grid reveal" aria-label="<?php echo esc_attr__( 'Contact information', 'parcinq-theme' ); ?>">
		<div class="contact-content">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'contact-entry' ); ?> >
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>
		</div>

		<div class="contact-options">
			<?php foreach ( $parcinq_contacts as $parcinq_contact ) : ?>
				<div class="contact-detail">
					<div class="k"><?php echo esc_html( $parcinq_contact['label'] ); ?></div>
					<a href="<?php echo esc_url( 'mailto:' . antispambot( $parcinq_contact['email'] ) ); ?>"><?php echo esc_html( antispambot( $parcinq_contact['email'] ) ); ?></a>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
</main>

<?php
get_footer();