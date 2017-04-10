<?php

$target = $this->config( 'admin/jsonadm/url/target' );
$cntl = $this->config( 'admin/jsonadm/url/controller', 'jsonadm' );
$action = $this->config( 'admin/jsonadm/url/action', 'get' );
$config = $this->config( 'admin/jsonadm/url/config', array() );

$resources = $attributes = array();
$site = $this->param( 'site', 'default' );

foreach( $this->get( 'resources', array() ) as $resource ) {
	$resources[$resource] = $this->url( $target, $cntl, $action, array( 'site' => $site, 'resource' => $resource, 'id' => '' ), array(), $config );
}

foreach( $this->get( 'attributes', array() ) as $attr ) {
	$attributes[$attr->getCode()] = $attr->toArray();
}

?>
{
	"meta": {
		"resources": <?php echo json_encode( $resources ); ?>,
		"attributes": <?php echo json_encode( $attributes ); ?>
	}
<?php if( isset( $this->errors ) ) : ?>
	,"errors": <?php echo $this->partial( $this->config( 'admin/jsonadm/partials/template-errors', 'partials/errors-standard.php' ), array( 'errors' => $this->errors ) ); ?>
<?php endif; ?>

}
