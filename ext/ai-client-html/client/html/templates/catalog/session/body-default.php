<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

?>
<?php $this->block()->start( 'catalog/session' ); ?>
<section class="aimeos catalog-session">
<?php if( isset( $this->sessionErrorList ) ) : ?>
	<ul class="error-list">
<?php foreach( (array) $this->sessionErrorList as $errmsg ) : ?>
		<li class="error-item"><?php echo $enc->html( $errmsg ); ?></li>
<?php endforeach; ?>
	</ul>
<?php endif; ?>
<?php echo $this->get( 'sessionBody' ); ?>
</section>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'catalog/session' ); ?>
