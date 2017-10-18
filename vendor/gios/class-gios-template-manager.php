<?php

/**
 *
 * TemplateManager
 *
 * A simple class to load Handlebar templates inside our Newsletter plugin. Here's
 * the deal: I know that I could abstract this out further - with an interface that
 * defines how a template manager should work, and multiple classes for different loading
 * methods, etc. For now, I'm just looking for a way to keep this repetitive logic out of
 * __every single callback function__ WordPress is going to make us write to render our templates.
 *
 * With that in mind, this class is all tangled up with LightnCandy (our Handlebars
 * rendering engine) and the WordPress Transients API.
 */

namespace TemplateManager;

use LightnCandy\LightnCandy;

class TemplateManager {

	private $templatePath;

	/**
	 * Our constructor
	 */
	function __construct( $templatePath ) {

		$this->templatePath = $templatePath;
	}

	/**
	 * For retrieving a compiled template. Will compile a template, and save it
	 * to the Transient table if no existing compiled template is available.
	 *
	 */
	public function getTemplate( String $templateName = NULL) {

		// for now, only look in the file system
		if( $template = $this->loadFromFile( $templateName ) )  {
			return $template;
		}else{
			wp_die('Unable to find the template: ' . $templateName );
		}

		/**
		 * Check the Transient table(s) first, to see if we've already saved
		 * a compiled version of this template.
		 */
		$template = json_decode( $this->loadFromTransient( $templateName ), true );


		if( false === $template ) {
		/**
		 * We did NOT find anything in the database. Check the file system for a
		 * file and load the contents. These would be *uncompiled* templates.
		 */

			$template = $this->loadFromFile( $templateName );

			// we got a template from the file system. Might as well save it for later.
			if( false !== $template) {
				$this->saveToTransient( $templateName, $template, 0 );
			}else{
				wp_die( '<p>Unable to find a template called: ' . $templateName );
			}
		}

		return $template;
	}

	/**
	 * Attempts to load a template saved in the WordPress transient table. This is just
	 * a thin wrapper around WordPress's existing get_transient() method, in case
	 * we ever need to do more work before/after getting the template.
	 *
	 * Returns: the template text if any value is found for $key, or FALSE
	 */
	private function loadFromTransient( String $key = NULL ) {

		if ( ! $key || empty( $key ) ) {
			return false;
		}else{
			return get_transient( $key );
		}
	}

	/**
	 * saves a template to the database through the Transient API.
	 */
	private function saveToTransient( $key = NULL, $value = NULL, $expiration = NULL ) {

		return set_transient( $key, $value, $expiration );
	}

	/**
	 * loads a template from the file system. Returns the contents of the file, or FALSE
	 * if the file does not exist, or is empty. Note: this use's PHP's idea of 'empty', so
	 * a file with only a zero, or an empty string, will be considered empty.
	 */
	private function loadFromFile( String $templateName = NULL ) {

		if (! $templateName || empty( $templateName ) ) {
			return false;
		}else{

			$fullPath = $this->templatePath . $templateName . '.php';

			if ( file_exists( $fullPath ) ) {
				$template = file_get_contents( $fullPath );
				if( ! empty( $template ) ) {
					return $template;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	}

	/**
	 * compiles an uncompiled template using LightnCandy
	 */
	private function compileTemplate ( $templateName ) {
		return LightnCandy::compile( $templateName );
	}
}