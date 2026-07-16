<?php
/**
 * Contact page template.
 *
 * Automatically applies to the page with slug "contact".
 *
 * @package Parcinq_Theme
 */

$parcinq_contact_status = '';
$parcinq_contact_errors = array();
$parcinq_contact_values = array(
	'name'    => '',
	'email'   => '',
	'subject' => '',
	'message' => '',
);

if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['parcinq_contact_submit'] ) ) {
	$parcinq_contact_values['name']    = isset( $_POST['parcinq_contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['parcinq_contact_name'] ) ) : '';
	$parcinq_contact_values['email']   = isset( $_POST['parcinq_contact_email'] ) ? sanitize_email( wp_unslash( $_POST['parcinq_contact_email'] ) ) : '';
	$parcinq_contact_values['subject'] = isset( $_POST['parcinq_contact_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['parcinq_contact_subject'] ) ) : '';
	$parcinq_contact_values['message'] = isset( $_POST['parcinq_contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['parcinq_contact_message'] ) ) : '';
	$parcinq_contact_trap              = isset( $_POST['parcinq_contact_company'] ) ? sanitize_text_field( wp_unslash( $_POST['parcinq_contact_company'] ) ) : '';

	if ( ! isset( $_POST['parcinq_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['parcinq_contact_nonce'] ) ), 'parcinq_contact_form' ) ) {
		$parcinq_contact_errors[] = __( 'The form expired. Please refresh the page and try again.', 'parcinq-theme' );
	}

	if ( '' !== $parcinq_contact_trap ) {
		$parcinq_contact_errors[] = __( 'The message could not be sent.', 'parcinq-theme' );
	}

	if ( '' === $parcinq_contact_values['name'] ) {
		$parcinq_contact_errors[] = __( 'Please enter your name.', 'parcinq-theme' );
	}

	if ( '' === $parcinq_contact_values['email'] || ! is_email( $parcinq_contact_values['email'] ) ) {
		$parcinq_contact_errors[] = __( 'Please enter a valid email address.', 'parcinq-theme' );
	}

	if ( '' === $parcinq_contact_values['message'] ) {
		$parcinq_contact_errors[] = __( 'Please enter a message.', 'parcinq-theme' );
	}

	if ( empty( $parcinq_contact_errors ) ) {
		$parcinq_contact_to    = 'connectwithus@parcinq.com';
		$parcinq_mail_subject = '' !== $parcinq_contact_values['subject'] ? $parcinq_contact_values['subject'] : __( 'Parcinq contact form message', 'parcinq-theme' );
		$parcinq_mail_subject = sprintf(
			/* translators: %s: Contact form subject. */
			__( '[PARCINQ] %s', 'parcinq-theme' ),
			$parcinq_mail_subject
		);
		$parcinq_mail_body    = sprintf(
			"Name: %1\$s\nEmail: %2\$s\nSubject: %3\$s\n\nMessage:\n%4\$s",
			$parcinq_contact_values['name'],
			$parcinq_contact_values['email'],
			$parcinq_contact_values['subject'],
			$parcinq_contact_values['message']
		);
		$parcinq_mail_headers = array(
			'Content-Type: text/plain; charset=UTF-8',
			sprintf( 'Reply-To: %1$s <%2$s>', $parcinq_contact_values['name'], $parcinq_contact_values['email'] ),
		);
		$parcinq_message_sent = wp_mail( $parcinq_contact_to, $parcinq_mail_subject, $parcinq_mail_body, $parcinq_mail_headers );

		if ( $parcinq_message_sent ) {
			$parcinq_contact_status = 'success';
			$parcinq_contact_values = array(
				'name'    => '',
				'email'   => '',
				'subject' => '',
				'message' => '',
			);
		} else {
			$parcinq_contact_status  = 'error';
			$parcinq_contact_errors[] = __( 'The message could not be sent. Please try again later.', 'parcinq-theme' );
		}
	}
}

get_header();

$parcinq_contact_socials = array(
	array(
		'label' => __( 'Instagram', 'parcinq-theme' ),
		'url'   => 'https://instagram.com/parcinqmagazine',
	),
	array(
		'label' => __( 'TikTok', 'parcinq-theme' ),
		'url'   => 'https://www.tiktok.com/@parcinqmagazine',
	),
	array(
		'label' => __( 'YouTube', 'parcinq-theme' ),
		'url'   => 'https://www.youtube.com/@ParcinqMagazine',
	),
	array(
		'label' => __( 'Facebook', 'parcinq-theme' ),
		'url'   => 'https://facebook.com/parcinqmagazine',
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
				<div class="ruleheavy"></div>
			</div>
		</div>
	</section>

	<section class="contact-grid reveal" aria-label="<?php echo esc_attr__( 'Contact Parcinq', 'parcinq-theme' ); ?>">
		<div class="contact-form-column">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'contact-entry' ); ?>>
					<?php if ( 'success' === $parcinq_contact_status ) : ?>
						<div class="form-success" role="status">
							<?php echo esc_html__( 'Thank you. Your message has been sent.', 'parcinq-theme' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $parcinq_contact_errors ) ) : ?>
						<div class="form-message" role="alert">
							<?php foreach ( $parcinq_contact_errors as $parcinq_contact_error ) : ?>
								<p><?php echo esc_html( $parcinq_contact_error ); ?></p>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<form class="form contact-prototype-form" method="post" action="<?php echo esc_url( get_permalink() ); ?>">
						<?php wp_nonce_field( 'parcinq_contact_form', 'parcinq_contact_nonce' ); ?>

						<label for="parcinq-contact-name"><?php echo esc_html__( 'Name', 'parcinq-theme' ); ?> <span class="required" aria-hidden="true">*</span></label>
						<input id="parcinq-contact-name" type="text" name="parcinq_contact_name" autocomplete="name" value="<?php echo esc_attr( $parcinq_contact_values['name'] ); ?>" required>

						<label for="parcinq-contact-email"><?php echo esc_html__( 'Email', 'parcinq-theme' ); ?> <span class="required" aria-hidden="true">*</span></label>
						<input id="parcinq-contact-email" type="email" name="parcinq_contact_email" autocomplete="email" value="<?php echo esc_attr( $parcinq_contact_values['email'] ); ?>" required>

						<label for="parcinq-contact-subject"><?php echo esc_html__( 'Subject', 'parcinq-theme' ); ?></label>
						<input id="parcinq-contact-subject" type="text" name="parcinq_contact_subject" value="<?php echo esc_attr( $parcinq_contact_values['subject'] ); ?>">

						<label for="parcinq-contact-message"><?php echo esc_html__( 'Message', 'parcinq-theme' ); ?> <span class="required" aria-hidden="true">*</span></label>
						<textarea id="parcinq-contact-message" name="parcinq_contact_message" required><?php echo esc_textarea( $parcinq_contact_values['message'] ); ?></textarea>

						<label class="contact-hp" for="parcinq-contact-company"><?php echo esc_html__( 'Company', 'parcinq-theme' ); ?></label>
						<input class="contact-hp" id="parcinq-contact-company" type="text" name="parcinq_contact_company" tabindex="-1" autocomplete="off">

						<button type="submit" name="parcinq_contact_submit" value="1"><?php echo esc_html__( 'Send Message', 'parcinq-theme' ); ?></button>
					</form>
				</article>
			<?php endwhile; ?>
		</div>

		<aside class="contact-options" aria-label="<?php echo esc_attr__( 'Contact details', 'parcinq-theme' ); ?>">
			<div class="contact-detail">
				<div class="k"><?php echo esc_html__( 'General', 'parcinq-theme' ); ?></div>
				<a href="mailto:<?php echo esc_attr( antispambot( 'connectwithus@parcinq.com' ) ); ?>"><?php echo esc_html( antispambot( 'connectwithus@parcinq.com' ) ); ?></a>
			</div>
			<div class="contact-detail">
				<div class="k"><?php echo esc_html__( 'Partnerships', 'parcinq-theme' ); ?></div>
				<a href="mailto:<?php echo esc_attr( antispambot( 'partnerships@parcinq.com' ) ); ?>"><?php echo esc_html( antispambot( 'partnerships@parcinq.com' ) ); ?></a>
			</div>
			<div class="contact-detail">
				<div class="k"><?php echo esc_html__( 'Office', 'parcinq-theme' ); ?></div>
				<p><?php echo esc_html__( 'Mandaluyong, Metro Manila, Philippines', 'parcinq-theme' ); ?></p>
			</div>
			<div class="contact-detail contact-follow">
				<div class="k"><?php echo esc_html__( 'Follow', 'parcinq-theme' ); ?></div>
				<p>
					<?php foreach ( $parcinq_contact_socials as $parcinq_index => $parcinq_social ) : ?>
						<?php if ( $parcinq_index > 0 ) : ?>
							<span aria-hidden="true">&middot;</span>
						<?php endif; ?>
						<a href="<?php echo esc_url( $parcinq_social['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $parcinq_social['label'] ); ?></a>
					<?php endforeach; ?>
				</p>
			</div>
		</aside>
	</section>
</main>

<?php
get_footer();
