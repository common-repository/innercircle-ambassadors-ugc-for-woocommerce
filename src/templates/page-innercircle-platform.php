<?php
/*
 * Template Name: Innercircle
 * Description: A Page Template with a darker design.
 */
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php
		wp_body_open();
		?>
        <div id="ic-loader-wrapper">
            <div id="ic-loader"></div>
        </div>
        <div id="ic-platform-container"></div>
        <?php wp_footer(); ?>
    </body>
</html>
