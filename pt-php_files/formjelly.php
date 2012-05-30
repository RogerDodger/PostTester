<?php
/* formjelly.php 
 * @define $name, $trip, $title, $content, $postlen
 * $name contains html data for e-mail if defined
 * $trip derived from anything following # in name
 * @define $image(
 *   [src]    => src,
 *   [title]  => title (src stripped of path),
 *   [width]  => width (true),
 *   [height] => height (true),
 *   [size]   => filesize (human-readable string)
 *   [w]      => width (resized),
 *   [h]      => height (resized),
 *   [exists] => bool
 * )
 *
 */


date_default_timezone_set("UTC");
define ("KU_LINELENGTH", 1800);
define ("KU_RANDOMSEED", ''); 
$boards = array('meta', 'arch', 
                'pony', 'pic', 'merch', 'oat', 
				'arch', 'fic', 'media', 'collab', 
				'rp', 'ooc',
				'phoenix', 'vinyl', 'g', 'dis', 'chat',
				'gala', 'int'
);

if(isset($_POST["name"])){
	$name = $_POST["name"] == ''? 'Anonymous': ($_POST["name"]);
	$return = calculateNameAndTripcode($name);
	if(is_array($return)){
		$name = $return[0];
		$trip = $return[1];
	} else {
		$name = $return;
	}
	$name = htmlspecialchars($name);
} else {
	$name = 'Anonymous';
}

if(isset($_POST["email"])){
	if($_POST["email"]!='' && $_POST["email"]!='noko'){
		$email = preg_replace('/#noko$/', '', htmlspecialchars($_POST["email"]));
		$name = '<a href="mailto:'.$email.'">'.$name.'</a>';
	}
}

if(isset($_POST["title"])){
	if($_POST["title"]!='') {
		$title = htmlspecialchars($_POST["title"]);
	}
}

$parse = new Parse;

if(isset($_POST["board"])){
	$board = $_POST["board"];
} else {
	$board = 'fic';
}

if(isset($_POST["content"])){
	$content = ($_POST["content"]);
	if(!isset($_POST["noparse"])){
		$content = $parse->ParsePost($content, $board);
	}
	$postlen = strlen($content);
	if(isset($_POST["trunc"])){
		$content = formatLongMessage($content);
	}
} else {
	$content = '';
}

if(isset($_POST["imgsrc"])){
	
	/* sanity check on requests */
	if(preg_match('#^http://#', $_POST["imgsrc"])){
		$header = @get_headers($_POST["imgsrc"], 1);
	}
	
	if((isset($header) && $header["Content-Length"] < 1024*1024*4)) {
	
		$get = @getimagesize($_POST["imgsrc"]);
		if($get!==false){
			$image = array(
				"exists" => true,
				"src"    => $_POST["imgsrc"],
				"title"  => preg_replace('/.+\\//', '', $_POST["imgsrc"]),
				"width"  => $get[0],
				"height" => $get[1],
				"w"      => $get[0],
				"h"      => $get[1],
			);
			
			/* get filesize */
			$image["size"] = $header["Content-Length"];
			
			if($image["size"] > 1024*1024) {
				$image["size"] = round($image["size"]/1024/1024, 2)."MB";
			} elseif ($image["size"] > 1024) {
				$image["size"] = round($image["size"]/1024, 2)."KB";
			} else {
				$image["size"] .= "B";
			}
			
			/* get dimensions of thumb */
			if(isset($_POST["small-thumb"])){
				$maxh = 125;
				$maxw = 125;
			} else {
				$maxh = 800;
				$maxw = 200;
			}
			
			if ($image["w"] > $maxw || $image["h"] > $maxh) {
				echo $ratio = $image["w"]*($maxh/$maxw) > $image["h"] ?	$maxw/$image["w"] : $maxh/$image["h"];
				echo $image["w"] = floor($image["w"]*$ratio);
				echo $image["h"] = floor($image["h"]*$ratio);
			}
			
		} 
		else {			
			$image["exists"] = false;
		}
	} 
	else {
		$image["exists"] = false;
	}
} 
else {
	$image["exists"] = false;
}