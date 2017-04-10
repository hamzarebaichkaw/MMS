<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014-2016
 */

$propertyItems = $this->get( 'propertyItems', array() );
$enc = $this->encoder();

?>
<?php $this->block()->start( 'catalog/detail/additional/property' ); ?>
<div class="additional-box">
<?php if( count( $propertyItems ) > 0 ) : ?>
	<h2 class="header properties"><?php echo $enc->html( $this->translate( 'client', 'Properties' ), $enc::TRUST ); ?></h2>
	<div class="content properties">
		<table class="properties">
			<tbody>
<?php foreach( $propertyItems as $propertyItem ) : ?>
				<tr class="item">
					<td class="name"><?php echo $enc->html( $this->translate( 'client/code', $propertyItem->getType() ), $enc::TRUST ); ?></td>
					<td class="value"><?php echo $enc->html( $propertyItem->getValue() ); ?></td>
				</tr>
<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>
<?php echo $this->get( 'propertyBody' ); ?>
</div>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'catalog/detail/additional/property' ); ?>
