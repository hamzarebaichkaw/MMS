<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

?>
<?php $this->block()->start( 'catalog/detail/additional' ); ?>
<div class="catalog-detail-additional">
<?php echo $this->get( 'additionalBody' ); ?>
</div>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'catalog/detail/additional' ); ?>
