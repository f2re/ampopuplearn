<?php
/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin.
 *
 * @package AmpopupLearn\Includes
 */
// print_r("sdfsdf");
spl_autoload_register( 'ampopuplearn_namespace_autoload' );
 
/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin by looking at the $class_name parameter being passed as an argument.
 *
 * The argument should be in the form: TutsPlus_Namespace_Demo\Namespace. The
 * function will then break the fully-qualified class name into its pieces and
 * will then build a file to the path based on the namespace.
 *
 * The namespaces in this plugin map to the paths in the directory structure.
 *
 * @param string $class_name The fully-qualified name of the class to load.
 */
function ampopuplearn_namespace_autoload( $class ) {
 
	$namespace = 'AmpopupLearn\\';
	$path      = 'src';

	// Bail if the class is not in our namespace.
	if ( 0 !== strpos( $class, $namespace ) ) {
		return;
	}

	// Remove the namespace.
	$class = str_replace( $namespace, '', $class );

	// Build the filename.
	$file = realpath( __DIR__ . "/{$path}" );
	$file = $file . DIRECTORY_SEPARATOR . str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';
	// echo $file.' ';
	// If the file exists for the class name, load it.
	if ( file_exists( $file ) ) {
		// echo $file.' ';
		include( $file );
	}
}