<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */

$enc = $this->encoder();

?>
<?php $this->block()->start( 'account/profile' ); ?>
<section class="aimeos account-profile">

	<?php if( ( $errors = $this->get( 'profileErrorList', array() ) ) !== array() ) : ?>

	<ul class="error-list">

		<?php foreach( $errors as $error ) : ?>

		<li class="error-item"><?php echo $enc->html( $error ); ?></li>

		<?php endforeach; ?>

	</ul>

	<?php endif; ?>

	<?php echo $this->get( 'profileBody' ); ?>

</section>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'account/profile' ); ?>
