<?php

namespace Shuangz\Option;

trait OptionFormater{

	
	/**
	 * Unserialize value only if it was serialized.
	 *
	 * @since 2.0.0
	 *
	 * @param string $original Maybe unserialized original, if is needed.
	 * @return mixed Unserialized data can be any type.
	 */
	protected function maybe_unserialize( $original )
	{
	    if ( $this->is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
	        return @unserialize( $original );
	    return $original;
	}

	/**
	 * Check value to find if it was serialized.
	 *
	 * If $data is not an string, then returned value will always be false.
	 * Serialized data is always a string.
	 *
	 * @since 2.0.5
	 *
	 * @param mixed $data Value to check to see if was serialized.
	 * @param bool $strict Optional. Whether to be strict about the end of the string. Defaults true.
	 * @return bool False if not serialized and true if it was.
	 */
	protected function is_serialized( $data, $strict = true ) {
	    // if it isn't a string, it isn't serialized
	    if ( ! is_string( $data ) ) {
	        return false;
	    }
	    $data = trim( $data );
	    if ( 'N;' == $data ) {
	        return true;
	    }
	    if ( strlen( $data ) < 4 ) {
	        return false;
	    }
	    if ( ':' !== $data[1] ) {
	        return false;
	    }
	    if ( $strict ) {
	        $lastc = substr( $data, -1 );
	        if ( ';' !== $lastc && '}' !== $lastc ) {
	            return false;
	        }
	    } else {
	        $semicolon = strpos( $data, ';' );
	        $brace     = strpos( $data, '}' );
	        // Either ; or } must exist.
	        if ( false === $semicolon && false === $brace )
	            return false;
	        // But neither must be in the first X characters.
	        if ( false !== $semicolon && $semicolon < 3 )
	            return false;
	        if ( false !== $brace && $brace < 4 )
	            return false;
	    }
	    $token = $data[0];
	    switch ( $token ) {
	        case 's' :
	            if ( $strict ) {
	                if ( '"' !== substr( $data, -2, 1 ) ) {
	                    return false;
	                }
	            } elseif ( false === strpos( $data, '"' ) ) {
	                return false;
	            }
	            // or else fall through
	        case 'a' :
	        case 'O' :
	            return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
	        case 'b' :
	        case 'i' :
	        case 'd' :
	            $end = $strict ? '$' : '';
	            return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
	    }
	    return false;
	}

	/**
	 * Check whether serialized data is of string type.
	 *
	 * @since 2.0.5
	 *
	 * @param mixed $data Serialized data
	 * @return bool False if not a serialized string, true if it is.
	 */
	protected function is_serialized_string( $data ) {
	    // if it isn't a string, it isn't a serialized string
	    if ( ! is_string( $data ) ) {
	        return false;
	    }
	    $data = trim( $data );
	    if ( strlen( $data ) < 4 ) {
	        return false;
	    } elseif ( ':' !== $data[1] ) {
	        return false;
	    } elseif ( ';' !== substr( $data, -1 ) ) {
	        return false;
	    } elseif ( $data[0] !== 's' ) {
	        return false;
	    } elseif ( '"' !== substr( $data, -2, 1 ) ) {
	        return false;
	    } else {
	        return true;
	    }
	}

	/**
	 * Serialize data, if needed.
	 *
	 * @since 2.0.5
	 *
	 * @param mixed $data Data that might be serialized.
	 * @return mixed A scalar data
	 */
	protected function maybe_serialize( $data ) {
	    if ( is_array( $data ) || is_object( $data ) )
	        return serialize( $data );

	    // Double serialization is required for backward compatibility.
	    // See http://core.trac.wordpress.org/ticket/12930
	    if ( $this->is_serialized( $data, false ) )
	        return serialize( $data );

	    return $data;
	}

	
}
