<?php


namespace CtElementorTemplate;

class AssetResolver {
	/**
	 * @var array
	 */
	private $manifest = [];

	public function resolveAll( $entry, $in_footer = false, $variables = [] ) {
		$key = 'ct_et';

		$is_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		if ( $manifest = $this->getManifest() ) {
			// $path = self::leading_slash_it($path);

			if ( isset( $manifest[ $entry ] ) ) {
				$map = $manifest[ $entry ];

				$firstJsKey = null;
				$lastJsKey = null;
				$lastCssKey = null;

				foreach ( $map as $name => $file ) {
					if ( preg_match( '/\.js$/i', $name ) ) {
						$jsKey = $key . '-' . $entry . '-' . $name;
						\wp_enqueue_script(
							$jsKey,
							$this->resolve( $entry, $name ),
							$lastJsKey ? [ 'jquery', $lastJsKey ] : [ 'jquery' ],
							$is_debug ? time() : CT_ET_VERSION,
							$in_footer
						);

						$lastJsKey = $jsKey;
						if ( ! $firstJsKey ) {
							$firstJsKey = $jsKey;
						}
					} else if ( preg_match( '/\.css$/i', $name ) ) {
						$cssKey = $key . '-' . $entry . '-' . $name;

						\wp_enqueue_style(
							$cssKey,
							$this->resolve( $entry, $name ),
							$lastCssKey ? [ $lastCssKey ] : [],
							$is_debug ? time() : CT_ET_VERSION
						);

						$lastCssKey = $cssKey;
					}
				}

				if ( $variables && count( $variables ) && $firstJsKey ) {
					foreach ( $variables as $name => $data ) {
						\wp_localize_script( $firstJsKey, $name, (array) $data );
					}
				}
			}
		}

		return false;
	}

	/**
	 * @param $path
	 *
	 * @return string
	 */
	public function resolve( $entry, $path ) {
		if ( $manifest = $this->getManifest() ) {
			// $path = self::leading_slash_it($path);

			if ( isset( $manifest[ $entry ] ) ) {
				$map = $manifest[ $entry ];

				if ( isset( $map[ $path ] ) ) {
					return CT_ET_URL . '/build/' . self::leading_slash_it( $map[ $path ] );
				}
			}
		}

		return false;
	}


	/**
	 * @return array|mixed|object
	 */
	private function getManifest() {
        if ( ! $this->manifest ) {
			$manifest = ADMIN_PATH . '/elementor-templates/build/asset-manifest.json';

			global $wp_filesystem;
    	$file = $wp_filesystem->get_contents( $manifest );

			if (
				$file &&
				is_array( $map = json_decode( $file, true ) )
			) {
				$this->manifest = $map;
			}
		}

		return $this->manifest;
	}


	/**
	 * @param $string
	 *
	 * @return string
	 */
	private static function leading_slash_it( $string ) {
		return '/' . ltrim( $string, '/\\' );
	}
}
