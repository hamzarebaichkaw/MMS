<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

/// Payment e-mail legal information
$string = $this->translate( 'client',  'All orders are subject to our terms and conditions.' );

?>
<?php $this->block()->start( 'email/common/html/legal' ); ?>
<p class="email-common-legal content-block">
<?php echo nl2br( $enc->html( $string, $enc::TRUST ) ); ?>
<?php echo $this->get( 'legalBody' ); ?>
</p>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'email/common/html/legal' ); ?>
