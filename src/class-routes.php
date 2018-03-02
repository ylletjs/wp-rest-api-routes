<?php

namespace Yllet\Api;

class Routes {

	/**
	 * Routes instance.
	 *
	 * @var \Yllet\Api\Routes
	 */
	protected static $instance;

	/**
	 * Get routes instance.
	 *
	 * @return \Yllet\Api\Routes
	 */
	public static function instance() {
		if ( ! isset( static::$instance ) ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	/**
	 * Routes construct.
	 */
	protected function __construct() {
		add_action( 'rest_api_init', [$this, 'register_rest_routes'] );
	}

	/**
	 * Register rest routes.
	 */
	public function register_rest_routes() {
		register_rest_route( 'wp/v2', '/routes', [
			'methods'  => 'GET',
			'callback' => [$this, 'get_routes'],
		] );
	}

	/**
	 * Get routes.
	 *
	 * @return array
	 */
	public function get_routes() {
		global $wp_rewrite;
		$routes = [];

		// Get rewrite rules.
		$rewrite_rules = get_option( 'rewrite_rules' );
		$rewrite_rules = empty( $rewrite_rules ) ? [] : $rewrite_rules;

		$rewrite_rules_by_source = [
			'post'     => $wp_rewrite->generate_rewrite_rules( $wp_rewrite->permalink_structure, EP_PERMALINK ),
			'date'     => $wp_rewrite->generate_rewrite_rules( $wp_rewrite->get_date_permastruct(), EP_DATE ),
			'root'     => $wp_rewrite->generate_rewrite_rules( $wp_rewrite->root . '/', EP_ROOT ),
			'comments' => $wp_rewrite->generate_rewrite_rules( $wp_rewrite->root . $wp_rewrite->comments_base, EP_COMMENTS, true, true, true, false ),
			'search'   => $wp_rewrite->generate_rewrite_rules( $wp_rewrite->get_search_permastruct(), EP_SEARCH ),
			'author'   => $wp_rewrite->generate_rewrite_rules( $wp_rewrite->get_author_permastruct(), EP_AUTHORS ),
			'page'     => $wp_rewrite->page_rewrite_rules(),
		];

		// Add extra permastructs.
		foreach ( $wp_rewrite->extra_permastructs as $permastructname => $permastruct ) {
			if ( is_array( $permastruct ) ) {
				$rewrite_rules_by_source[$permastructname] = $wp_rewrite->generate_rewrite_rules( $permastruct['struct'], $permastruct['ep_mask'], $permastruct['paged'], $permastruct['feed'], $permastruct['forcomments'], $permastruct['walk_dirs'], $permastruct['endpoints'] );
			} else {
				$rewrite_rules_by_source[$permastructname] = $wp_rewrite->generate_rewrite_rules( $permastruct, EP_NONE );
			}
		}

		// Apply core filters.
		foreach ( $rewrite_rules_by_source as $source => $rules ) {
			$rewrite_rules_by_source[$source] = apply_filters( $source . '_rewrite_rules', $rules );

			if ( 'post_tag' === $source ) {
				$rewrite_rules_by_source[$source] = apply_filters( 'tag_rewrite_rules', $rules );
			}
		}

		// Add all rewrites rules to the routes array.
		foreach ( $rewrite_rules as $rule => $rewrite ) {
			$routes[$rule]['path'] = $rule;

			// Append type to route if we can figure it out.
			foreach ( $rewrite_rules_by_source as $source => $rules ) {
				if ( array_key_exists( $rule, $rules ) ) {
					$routes[$rule]['type'] = $source;
				}
			}
		}

		// Add rewrite rules that maybe are missing.
		$maybe_missing = $wp_rewrite->rewrite_rules();
		foreach ( $maybe_missing as $rule => $rewrite ) {
			if ( array_key_exists( $rule, $routes ) ) {
				continue;
			}

			$routes[$rule] = [
				'rewrite' => $rewrite,
				'type'    => 'missing',
			];
		}

		return array_values( $routes );
	}
}
