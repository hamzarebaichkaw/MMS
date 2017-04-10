<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$logoContent = false;
$enc = $this->encoder();

/// E-mail HTML title
$title = $this->translate( 'client', 'E-mail notification' );

/** client/html/email/logo
 * Path to the logo image displayed in HTML e-mails
 *
 * The path can either be an absolute local path or an URL to a file on a
 * remote server. If the file is stored on a remote server, "allow_url_fopen"
 * must be enabled. See {@link http://php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen php.ini allow_url_fopen}
 * documentation for details.
 *
 * @param string Absolute file system path or remote URL to the logo image
 * @since 2014.03
 * @category User
 * @see client/html/email/from-email
 */
if( ( $logo = $this->config( 'client/html/email/logo', 'client/html/themes/elegance/images/aimeos.png' ) ) != '' )
{
	$logoFilename = basename( $logo );

	if( file_exists( $logo ) !== false )
	{
		$logoContent = file_get_contents( $logo );
		$finfo = new finfo( FILEINFO_MIME_TYPE );
		$logoMimetype = $finfo->file( $logo );
	}
}

$path = $this->config( 'client/html/common/template/baseurl', 'client/html/themes/elegance' );
$filename = $path . DIRECTORY_SEPARATOR . 'common.css';
$content = '';

if( file_exists( $filename ) !== false ) {
	$content = file_get_contents( $filename );
}

?>
<?php $this->block()->start( 'email/common/html' ); ?>
<html>
	<head>
		<title><?php echo $enc->html( $title, $enc::TRUST ); ?></title>
		<meta name="application-name" content="Aimeos" />
	</head>
	<body>
		<style type="text/css">
html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li,
fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup,
menu, nav, output, ruby, section, summary, time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section {
	display: block;
}

<?php echo $content; ?>

.aimeos .content-block {
	margin: 1.5em 2%;
	width: 96%;
}
.aimeos .logo {
	margin: 1.5em 2%;
	margin-top: 0;
	float: right;
}
.aimeos .common-summary {
	clear: both;
}
		</style>
		<div class="aimeos">
<?php if( $logoContent !== false ) : ?>
			<img class="logo" src="<?php echo $this->mail()->embedAttachment( $logoContent, $logoMimetype, $logoFilename ); ?>" />
<?php endif; ?>
<?php echo $this->get( 'htmlBody' ); ?>
		</div>
	</body>
</html>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'email/common/html' ); ?>
