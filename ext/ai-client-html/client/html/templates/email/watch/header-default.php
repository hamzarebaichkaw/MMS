<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

/// Product notification e-mail subject
$this->mail()->setSubject( $this->translate( 'client', 'Your watched products' ) );

?>
<?php echo $this->get( 'watchHeader' ); ?>
