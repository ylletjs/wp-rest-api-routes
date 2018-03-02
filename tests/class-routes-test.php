<?php

use Yllet\Api\Routes;

class Routes_Test extends \WP_UnitTestCase {
	public function setUp() {
		parent::setUp();
		$this->class = Routes::instance();
	}

	public function tearDown() {
		parent::tearDown();
		unset( $this->class );
	}

	public function test_get_routes() {
		$routes = $this->class->get_routes();

		$this->assertTrue( is_array( $routes ) );
		$this->assertEmpty( $routes );

		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$wp_rewrite->flush_rules();

		$routes = $this->class->get_routes();

		$this->assertNotEmpty( $routes );
	}
}
