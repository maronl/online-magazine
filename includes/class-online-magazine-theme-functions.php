<?php

class Online_Magazine_Theme_Functions {

    function __construct() { }

    public static function  define_theme_functions() {

	   if( ! function_exists( 'om_issue_number' ) ) {
            function om_issue_number( $title ) {
                $matches = array();
                preg_match("/^(\\D*)(\\d+)(\\D*)(\\d*)(.*)$/",$title,$matches);
                return $matches[2];
            }
        }

        if( ! function_exists( 'om_issue_year' ) ) {
            function om_issue_year( $title ) {
                $matches = array();
                preg_match("/^(\\D*)(\\d+)(\\D*)(\\d*)(.*)$/",$title,$matches);
                return $matches[4];
            }
        }

    }
} 