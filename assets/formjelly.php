<?php
/* formjelly.php 
 * @define $name, $trip, $title, $content, $postlen
 * $name contains html data for e-mail if defined
 * $trip derived from anything following # in name
 * @define $image(
 *   [id]     => id,
 *   [name]   => filename,
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

if(isset($_POST["name"]) &&  $_POST["name"]!==''){
	$name = ($_POST["name"]);
	$name = htmlspecialchars($name);
	$return = calculateNameAndTripcode($name);
	if(is_array($return)){
		$name = $return[0];
		$trip = $return[1];
	} else {
		$name = $return;
	}
} else {
	$name = 'Anonymous';
}

if(isset($_POST["email"]) && $_POST["email"]!='' && $_POST["email"]!='noko'){
	$email = preg_replace('/#noko$/', '', htmlspecialchars($_POST["email"]));
	$name = '<a href="mailto:'.$email.'">'.$name.'</a>';
}

if(isset($_POST["title"]) && $_POST["title"]!=''){
	$title = htmlspecialchars($_POST["title"]);
}

$parse = new Parse;

if(isset($_POST["content"])){
	$content = ($_POST["content"]);
	if(!isset($_POST["noparse"])){
		$content = $parse->ParsePost($content, isset($_POST["board"])?$_POST["board"]:'fic');
	}
	$postlen = strlen($content);
	if(isset($_POST["trunc"])){
		$content = formatLongMessage($content);
	}
} else {
	$content = '';
}

if(isset($_FILES["image"])
		&& is_uploaded_file($_FILES["image"]["tmp_name"]) 
		&& $_FILES["image"]["error"] == UPLOAD_ERR_OK){
	if(preg_match('/^image/', $_FILES["image"]["type"])){

		$image["exists"] = true;
		$imgdir = __DIR__.'/images';
		if(!is_dir($imgdir)) {
			mkdir($imgdir);
		}

		// get id and increment index by 1
		$image["id"] = (
				is_string(@file_get_contents($imgdir.'/index'))?
				file_get_contents($imgdir.'/index'): 0 
			) + 1;
		file_put_contents($imgdir.'/index', $image["id"]);

		//get uploaded file's extension and append to id
		$ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
		$image["id"] .= ".$ext";

		//get dimensions of file
		$get = getimagesize($_FILES["image"]["tmp_name"]);
		$image["width"]  = $get[0];
		$image["w"]      = $get[0];
		$image["height"] = $get[1];
		$image["h"]      = $get[1];

		// get dimensions of thumb
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

		//move from tmp to images
		move_uploaded_file($_FILES["image"]["tmp_name"], $imgdir.'/'.$image["id"]);

		//set name
		$image["name"] = $_FILES["image"]["name"];

		//set filesize to human-readable string
		$image["size"] = $_FILES["image"]["size"];
		$image["size"] = $image["size"] > 1024*1024 ? 
			round($image["size"]/1024/1024, 2)."MB" :(
			$image["size"] > 1024 ?
				round($image["size"]/1024, 2)."KB" :
				$image["size"]."B");	
	} else {
		$image["exists"] = false;
		$_FILES["image"]["error"] = 9;
	}
} else {
	$image["exists"] = false;
}
