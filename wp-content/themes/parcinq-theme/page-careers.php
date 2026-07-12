<?php
/**
 * Template for the Careers page.
 *
 * Automatically applies to the page with the careers slug.
 *
 * @package Parcinq_Theme
 */

get_header();

$parcinq_contact_page = get_page_by_path( 'contact' );
$parcinq_contact_url  = $parcinq_contact_page instanceof WP_Post && 'publish' === $parcinq_contact_page->post_status
	? get_permalink( $parcinq_contact_page )
	: home_url( '/contact/' );

$parcinq_open_roles = array(
	array(
		'title' => __( 'Contributing Writer', 'parcinq-theme' ),
		'meta'  => __( 'Editorial · Rolling', 'parcinq-theme' ),
	),
	array(
		'title' => __( 'Social Media Lead', 'parcinq-theme' ),
		'meta'  => __( 'Social · Open', 'parcinq-theme' ),
	),
	array(
		'title' => __( 'Photographer (Freelance)', 'parcinq-theme' ),
		'meta'  => __( 'Visual · Rolling', 'parcinq-theme' ),
	),
	array(
		'title' => __( 'Graphic Designer', 'parcinq-theme' ),
		'meta'  => __( 'Design · Open', 'parcinq-theme' ),
	),
	array(
		'title' => __( 'Editorial Intern', 'parcinq-theme' ),
		'meta'  => __( 'Internship · Open', 'parcinq-theme' ),
	),
);
?>

<main id="primary" class="site-main careers-page">
	<section class="ed-hero careers-hero">
		<div class="wrap">
			<span class="bgword" aria-hidden="true"><?php echo esc_html__( 'JOIN', 'parcinq-theme' ); ?></span>
			<div class="inner reveal">
				<div class="ed-meta"><span><?php echo esc_html__( 'Join the Team', 'parcinq-theme' ); ?></span></div>
				<h1><?php echo esc_html__( 'Careers', 'parcinq-theme' ); ?></h1>
				<p class="stand"><?php echo esc_html__( 'We are a small team making something we care about. Come build it with us.', 'parcinq-theme' ); ?></p>
			</div>
		</div>
	</section>

	<div class="ruleheavy" aria-hidden="true"></div>

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'careers-entry' ); ?>>
			<div class="content careers-content reveal">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
	?>

	<section class="careers-roles wrap reveal" aria-labelledby="careers-roles-title">
		<div class="careers-roles-head">
			<h2 id="careers-roles-title"><?php echo esc_html__( 'Open Roles', 'parcinq-theme' ); ?></h2>
			<span><?php echo esc_html__( 'Updated Regularly', 'parcinq-theme' ); ?></span>
		</div>

		<ol class="careers-role-list">
			<?php foreach ( $parcinq_open_roles as $parcinq_index => $parcinq_role ) : ?>
				<li class="careers-role">
					<span class="careers-role-num"><?php echo esc_html( sprintf( '%02d', $parcinq_index + 1 ) ); ?></span>
					<h3><?php echo esc_html( $parcinq_role['title'] ); ?></h3>
					<span class="careers-role-meta"><?php echo esc_html( $parcinq_role['meta'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>

	<section class="cta-band careers-cta reveal">
		<h2><?php echo esc_html__( 'Work with Parcinq', 'parcinq-theme' ); ?></h2>
		<p><?php echo esc_html__( 'Send your résumé, portfolio and preferred role.', 'parcinq-theme' ); ?></p>
		<a class="btn" href="<?php echo esc_url( $parcinq_contact_url ); ?>"><?php echo esc_html__( 'Apply Now', 'parcinq-theme' ); ?></a>
	</section>
</main>

<?php
get_footer();