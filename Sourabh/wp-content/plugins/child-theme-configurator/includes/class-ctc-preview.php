<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

    class ChildThemeConfiguratorPreview {
        /**
         * Replaces core function to start preview theme output buffer.
         */
        static function preview_theme() {
            // are we previewing?
            if ( ! isset( $_GET[ 'template' ] ) || !wp_verify_nonce( $_GET['preview_ctc'] ) )
                return;
            // can user preview?
            if ( !current_user_can( 'switch_themes' ) )
                return;
            // hide admin bar in preview
            if ( isset( $_GET[ 'preview_iframe' ] ) )
                show_admin_bar( false );
            // sanitize template param
            $_GET[ 'template' ] = preg_replace( '|[^a-z0-9_./-]|i', '', $_GET[ 'template' ] );
            // check for manipulations
            if ( validate_file( $_GET[ 'template' ] ) )
                return;
            // replace future get_template calls with preview template
            add_filter( 'template', 'ChildThemeConfiguratorPreview::preview_theme_template_filter' );
        
            if ( isset( $_GET[ 'stylesheet' ] ) ):
                // sanitize stylesheet param
                $_GET['stylesheet'] = preg_replace( '|[^a-z0-9_./-]|i', '', $_GET['stylesheet'] );
                // check for manipulations
                if ( validate_file( $_GET['stylesheet'] ) )
                    return;
                // replace future get_stylesheet calls with preview stylesheet
                add_filter( 'stylesheet', 'ChildThemeConfiguratorPreview::preview_theme_stylesheet_filter' );
            endif;
            // swap out theme mods with preview theme mods
            add_filter( 'pre_option_theme_mods_' . get_option( 'stylesheet' ), 
                'ChildThemeConfiguratorPreview::preview_mods' );
            // impossibly high priority to test for stylesheets loaded after wp_head()
            add_action( 'wp_print_styles', 'ChildThemeConfiguratorPreview::test_css', 999999 );
            // pass the wp_styles queue back to use for stylesheet handle verification
            add_action( 'wp_footer', 'ChildThemeConfiguratorPreview::parse_stylesheet' );

        }
        
        /**
         * Retrieves child theme mods for preview
         */        
        static function preview_mods() { 
            if ( ! isset( $_GET[ 'stylesheet' ] ) || get_option( 'stylesheet' ) == $_GET[ 'stylesheet' ] ) return false;
            return get_option( 'theme_mods_' . preg_replace('|[^a-z0-9_./-]|i', '', $_GET['stylesheet']) );
        }
        
        /**
         * Function to modify the current template when previewing a theme
         *
         * @return string
         */
        static function preview_theme_template_filter() {
            return ( isset($_GET['template']) && current_user_can( 'switch_themes' ) ) ? $_GET['template'] : '';
        }
        
        /**
         * Function to modify the current stylesheet when previewing a theme
         *
         * @return string
         */
        static function preview_theme_stylesheet_filter() {
            return ( isset( $_GET['stylesheet'] ) && current_user_can( 'switch_themes' ) ) ? $_GET['stylesheet'] : '';
        }
        
        // enqueue dummy stylesheet with extremely high priority to test wp_head()
        static function test_css() {
            wp_enqueue_style( 'ctc-test', get_stylesheet_directory_uri() . '/ctc-test.css' );
        }
        
        static function parse_stylesheet() {
            echo '<script>/*<![CDATA[' . LF;
            global $wp_styles, $wp_filter;
            $queue = implode( "\n", $wp_styles->queue );
            echo 'BEGIN WP QUEUE' . LF . $queue . LF . 'END WP QUEUE' . LF;
            if ( is_child_theme() ):
                // check for signals that indicate specific settings
                $file = get_stylesheet_directory() . '/style.css';
                if ( file_exists( $file ) && ( $styles = @file_get_contents( $file ) ) ):
                    // is this child theme a standalone ( framework ) theme?
                    if ( defined( 'CHLD_THM_CFG_IGNORE_PARENT' ) ):
                        echo 'CHLD_THM_CFG_IGNORE_PARENT' . LF;
                    endif;
                    // has this child theme been configured by CTC? ( If it has the timestamp, it is one of ours. )
                    if ( preg_match( '#\nUpdated: \d\d\d\d\-\d\d\-\d\d \d\d:\d\d:\d\d\n#s', $styles ) ):
                        echo 'IS_CTC_THEME' . LF;
                    endif;
                    // is this child theme using the @import method?
                    if ( preg_match( '#\@import\s+url\(.+?\/' . preg_quote( get_template() ) . '\/style\.css.*?\);#s', $styles ) ):
                        echo 'HAS_CTC_IMPORT' . LF;
                    endif;
                endif;
            else:
                // Check if the parent style.css file is used at all. If not we can skip the parent stylesheet handling altogether.
                $file = get_template_directory() . '/style.css';
                if ( file_exists( $file ) && ( $styles = @file_get_contents( $file ) ) ):
                    $styles = preg_replace( '#\/\*.*?\*\/#s', '', $styles );
                    if ( !preg_match( '#\n\s*([\[\.\#\:\w][\w\-\s\(\)\[\]\'\^\*\.\#\+:,"=>]+?)\s*\{(.*?)\}#s', $styles ) ):
                        echo 'NO_CTC_STYLES' . LF;
                    endif;
                endif;
            endif;
            /**
             * Use the filter api to determine the parent stylesheet enqueue priority
             * because some themes do not use the standard 10 for various reasons.
             * We need to match this priority so that the stylesheets load in the correct order.
             */
            echo 'BEGIN CTC IRREGULAR' . LF;
            // Iterate through all the added hook priorities
            foreach ( $wp_filter[ 'wp_enqueue_scripts' ] as $priority => $arr ):
                // If this is a non-standard priority hook, determine which handles are being enqueued.
                // These will then be compared to the primary handle ( style.css ) 
                // to determine the enqueue priority to use for the parent stylesheet. 
                if ( $priority != 10 ):
                    // iterate through each hook in this priority group
                    foreach ( $arr as $funcarr ):
                        // clear the queue
                        $wp_styles->queue = array();
                        // now call the hooked function to populate the queue
                        if ( !is_null($funcarr['function']) )
                            call_user_func_array( $funcarr[ 'function' ], array() );
                    endforeach;
                    // report the priority, and any handles that were added
                    echo $priority . ',' . implode( ",", $wp_styles->queue ) . LF;
                endif;
            endforeach;
            echo 'END CTC IRREGULAR' . LF;
            echo '*/]]></script>' . LF;
        }
    }
    
    // replace core preview function with CTCP function for quick preview
    remove_action( 'setup_theme', 'preview_theme' );
    add_action( 'setup_theme', 'ChildThemeConfiguratorPreview::preview_theme' );
    