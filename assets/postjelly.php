<?php
	
/**
 * Calculate the different name and tripcode for the name field provided
 *
 * @param string $post_name Text entered in the Name field
 * @return array Name and tripcode
 */
function calculateNameAndTripcode($post_name) {

	if(preg_match("/(#)(.*)/", $post_name, $regs)){
		$cap = $regs[2];
		
		/* Not needed in this implementation
		$cap_full = '#' . $regs[2];

		// {{{ Special tripcode check
		
		$trips = unserialize(KU_TRIPS);
		if (count($trips) > 0) {
			if (isset($trips[$cap_full])) {
				$forcedtrip = $trips[$cap_full];
				return array(preg_replace("/(#)(.*)/", "", $post_name), $forcedtrip);
			}
		}
		
		// }}}
		*/

		if (function_exists('mb_convert_encoding')) {
			$recoded_cap = mb_convert_encoding($cap, 'SJIS', 'UTF-8');
			if ($recoded_cap != '') {
				$cap = $recoded_cap;
			}
		}

		if (strpos($post_name, '#') === false) {
			$cap_delimiter = '!';
		} elseif (strpos($post_name, '!') === false) {
			$cap_delimiter = '#';
		} else {
			$cap_delimiter = (strpos($post_name, '#') < strpos($post_name, '!')) ? '#' : '!';
		}

		if (preg_match("/(.*)(" . $cap_delimiter . ")(.*)/", $cap, $regs_secure)) {
			$cap = $regs_secure[1];
			$cap_secure = $regs_secure[3];
			$is_secure_trip = true;
		} else {
			$is_secure_trip = false;
		}

		$tripcode = '';
		if ($cap != '') {
			/* From Futabally */
			$cap = strtr($cap, "&amp;", "&");
			$cap = strtr($cap, "&#44;", ", ");
			$salt = substr($cap."H.", 1, 2);
			$salt = preg_replace("/[^\.-z]/", ".", $salt);
			$salt = strtr($salt, ":;<=>?@[\\]^_`", "ABCDEFGabcdef");
			$tripcode = substr(crypt($cap, $salt), -10);
		}

		if ($is_secure_trip) {
			if ($cap != '') {
				$tripcode .= '!';
			}

			$secure_tripcode = md5($cap_secure . KU_RANDOMSEED);
			if (function_exists('base64_encode')) {
				$secure_tripcode = base64_encode($secure_tripcode);
			}
			if (function_exists('str_rot13')) {
				$secure_tripcode = str_rot13($secure_tripcode);
			}

			$secure_tripcode = substr($secure_tripcode, 2, 10);

			$tripcode .= '!' . $secure_tripcode;
		}

		$name = preg_replace("/(" . $cap_delimiter . ")(.*)/", "", $post_name);


		return array($name, $tripcode);
	}

	return $post_name;
}

/**
 * Format a long message to be shortened if it exceeds the allowed length on a page
 *
 * @param string $message Post message
 * @return string The formatted message
 */
 
function formatLongMessage($message) {
	$output = '';
	if ((strlen($message) > KU_LINELENGTH || count(explode('<br />', $message)) > 12)) {
		$message_exploded = explode('<br />', $message);
		$message_shortened = '';
		for ($i = 0; $i <= 11; $i++) {
			if (isset($message_exploded[$i])) {
				$message_shortened .= $message_exploded[$i] . '<br />';
			}
		}
		if (strlen($message_shortened) > KU_LINELENGTH) {
			$message_shortened = substr($message_shortened, 0, KU_LINELENGTH);
		}
		$message_shortened = closeOpenTags($message_shortened);
		
		if (strrpos($message_shortened,"<") > strrpos($message_shortened,">")) {
			//We have a partially opened tag we need to get rid of.
			$message_shortened = substr($message_shortened, 0, strrpos($message_shortened,"<"));
		}
		
		$output = $message_shortened. 
		'<b>&#0133;</b><div class="abbrev"><a href="#" onclick="return false">Read more &gt;&gt;</a></div>'.
		"\n";
	} else {
		$output .= $message . "\n";
	}

	return $output;
}

/* Thanks milianw - php.net */
/**
 * Closes all HTML tags left open
 *
 * @param string $html HTML to be checked
 * @return string HTML with all tags closed
 */
function closeOpenTags($html){
	/* Put all opened tags into an array */
	preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU", $html, $result);
	$openedtags=$result[1];

	/* Put all closed tags into an array */
	preg_match_all("#</([a-z]+)>#iU", $html, $result);
	$closedtags=$result[1];
	$len_opened = count($openedtags);
	/* All tags are closed */
	if(count($closedtags) == $len_opened){
		return $html;
	}
	$openedtags = array_reverse($openedtags);
	/* Close tags */
	for($i=0;$i<$len_opened;$i++) {
		if ($openedtags[$i]!='br') {
			if (!in_array($openedtags[$i], $closedtags)){
				$html .= '</'.$openedtags[$i].'>';
			} else {
				unset($closedtags[array_search($openedtags[$i], $closedtags)]);
			}
		}
	}
	return $html;
}
