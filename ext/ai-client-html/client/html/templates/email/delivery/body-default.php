<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

?>
<?php $this->block()->start( 'email/delivery' ); ?>
<?php echo $this->get( 'deliveryBody' ); ?>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'email/delivery' ); ?>
