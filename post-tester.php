<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- Post Tester to see how a post will look on Ponychan. Written by RogerDodger. -->

<head>

<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<title>Post Tester</title>
<link rel="shortcut icon" href="http://www.ponychan.net/chan/favicon.php" />

<?php 

if(!isset($path)){
	$path = "/PostTester";
}

include 'assets/postjelly.php'; 
include 'assets/CParse.php';
include 'assets/formjelly.php';

?>
	


<link rel="stylesheet" type="text/css" href="<?php echo $path.'/assets/img_global.css'?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $path.'/assets/colgate.css'?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $path.'/assets/post-tester.css'?>" />

<script type="text/javascript" src="<?php echo $path.'/assets/pt-php.js'?>" charset="utf-8"></script>
<script type="text/javascript">
window.onload = function (){
	togboard('<?php echo isset($_POST["board"]) ? $_POST["board"] : 'fic'; ?>');
}
</script>

</head>

<body>
<?php 
function _mkboardlnk($title, $board) {
	echo '<a name="nav" href="#" class="navbarboard" onclick="return togboard(\''.$board.'\')" id="'.$board.'" title="'.$title.'">/'.$board.'/</a>';
}
?>
<div id="verytopbar" class="darkbar" style="position: fixed; left: 0px; top: 0px; ">
	<div class="navbar">
		<span class="navbarsection">
			<a class="navbarboard" href="#" onclick="return false" title="All boards">/all/</a>
			<span class="navbarsection">
				<?php _mkboardlnk("Site Issues", "meta"); ?>
				
				<?php _mkboardlnk("Twilight's Library", "arch"); ?>
				
			</span>
			<span class="navbarsection">
				<?php _mkboardlnk("Friendship is Magic", "pony"); ?>
				
				<?php _mkboardlnk("Pictures", "pic"); ?>
				
				<?php _mkboardlnk("Merchandise", "merch"); ?>
				
				<?php _mkboardlnk("Oatmeal", "oat"); ?>
				
			</span>
			<span class="navbarsection">
				<?php _mkboardlnk("Art", "art"); ?>
				
				<?php _mkboardlnk("Fanfics", "fic"); ?>
				
				<?php _mkboardlnk("Music/video", "media"); ?>
				
				<?php _mkboardlnk("Projects", "collab"); ?>
				
			</span>
			<span class="navbarsection">
				<?php _mkboardlnk("Roleplay", "rp"); ?>
				
				<?php _mkboardlnk("Roleplay Lounge", "ooc"); ?>
				
			</span>
			<span class="navbarsection">
				<?php _mkboardlnk("Phoenix", "phoenix"); ?>
				
				<?php _mkboardlnk("Music", "vinyl"); ?>
				
				<?php _mkboardlnk("Games", "g"); ?>
				
				<?php _mkboardlnk("Discussion", "dis"); ?>
				
				<?php _mkboardlnk("Chat", "chat"); ?>
				
			</span>
			<span class="navbarsection">
				<?php _mkboardlnk("Gala", "gala"); ?>
				
				<?php _mkboardlnk("World", "int"); ?>
				
			</span>
		</span>
	</div>
</div>

<div class="logo">Post Tester</div>

<hr />

<div class="postarea">
	<form action="post-tester.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="board" id="formboard" value="<?php echo isset($_POST["board"])? $_POST["board"] : 'fic'; ?>" />
	<table class="postform">
		<tr>
			<td class="postblock">Name</td>
			<td><input type="text" name="name" size="28" maxlength="75" value="<?php echo isset($_POST["name"])? htmlspecialchars($_POST["name"]): ''; ?>" /></td>
		</tr>

		<tr>
			<td class="postblock">E-mail</td>
			<td><input type="text" name="email" size="28" maxlength="75" value="<?php echo isset($_POST["email"])? htmlspecialchars($_POST["email"]): ''; ?>" /></td>
		</tr>

		<tr>
			<td class="postblock">Subject</td>
			<td>
				<input type="text" name="title" size="35" maxlength="75" value="<?php echo isset($_POST["title"])? htmlspecialchars($_POST["title"]): ''; ?>" />
				<input type="submit" value="Update Post" />
			</td>
		</tr>
		
		<tr>
			<td class="postblock">Message</td>
			<td><textarea name="content" cols="48" rows="6"><?php echo isset($_POST["content"]) ? htmlspecialchars($_POST["content"]) : ''; ?></textarea></td>
		</tr>
		
		<tr>
			<td class="postblock">Tags</td>
			<td><input type="text" name="tags" size="35" maxlength="255" value="<?php echo isset($_POST["tags"])? htmlspecialchars($_POST["tags"]): ''; ?>" /></td>
		</tr>
		
		<tr>
			<td class="postblock">File</td>
			<input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
			<td><input type="file" name="image" /></td>
		</tr>
		
		<tr>
			<td class="postblock">Settings</td>
			<td>
				<input type="checkbox" name="noparse" <?php if(isset($_POST["noparse"])) {echo 'checked="yes"';} ?> /> HTML &nbsp; 
				<input type="checkbox" name="trunc" <?php if(isset($_POST["trunc"])) {echo 'checked="yes"';} ?> /> Truncate &nbsp;
				<input type="checkbox" name="small-thumb" <?php if(isset($_POST["small-thumb"])) {echo 'checked="yes"';} ?> /> Small thumbnail &nbsp;
			</td>
		</tr>
	</table>
	
	</form>
</div>
<hr />

<?php if(!$help || 
		isset($_FILES["image"]) && $_FILES["image"]["error"] > 0 && $_FILES["image"]["error"] != 4): ?>
<?php if(!$help): ?>
<p style="text-align:center;">Your post is <?php echo $postlen ?> characters long.</p>

<?php endif; ?>

<?php if(isset($_FILES["image"]) && $_FILES["image"]["error"] > 0 && $_FILES["image"]["error"] != 4): ?>
<p style="text-align:center;"><?php
	switch ($_FILES["image"]["error"]){
		case 1:
		case 2:
			echo 'Error: Image too large. (Max filesize 4mb)';
			break;
		case 3:
			echo 'Error: Image damaged/lost in transfer.';
			break;
		case 6:
			echo 'Error: Temp folder missing for some reason. Contact the admin.';
			break;
		case 7:
			echo 'Error: Can’t write to disk. Contact the admin.';
			break;
		case 8:
			echo 'Error: An extension blocked the upload. Contact the admin.';
			break;
		case 9:
			echo 'Error: File is not an image.';
			break;
	}
?></p>
<?php endif; ?>
<hr />
<?php endif; ?>

<div class="thread">

<?php if ($image["exists"]): ?>
<span class="filesize">
	File <a href="<?php echo $path.'/assets/images/'.$image["id"].'"'; ?>><?php echo $image["id"]; ?></a> - 
		(<?php echo $image["size"] ?>, 
		<span class="dimensions"><?php echo $image["width"].'x'.$image["height"] ?></span>, 
		<?php echo $image["name"]; ?>)
</span>
<br />
<a href="#" onclick="return false"><img <?php echo "src=\"$path/assets/images/{$image["id"]}\" height=\"{$image["h"]}\" width=\"{$image["w"]}\""?> class="thumb" /></a>
<?php endif; ?>

<label>
	<input type="checkbox" />
	<?php echo isset($title) ? '<span class="filetitle">'.$title.'</span>' : ''; ?>
	<span class="postername"><?php echo $name; ?></span><?php echo isset($trip) ? '<span class="postertrip">!'.$trip.'</span>' : ''; ?>
	<span class="posttime"><?php echo date('D, d M Y H:i:s \G\M\T'); ?></span>
</label>

<span class="reflink">
	<a href="#">No.&nbsp;</a><a href="#">NNNNN</a>
</span>

<br />

<blockquote>
	<div id="content"><?php if($help===true) {include 'assets/help.php';} else {echo $content;} ?></div>
</blockquote>

<div class="postfooter">
	<a href="#" onclick="return false">Reply</a> &#8226; <a href="#" onclick="return false">Report</a>
</div>

</div>

<hr />

<br />

<div class="footer" style="clear:both">
	Written by <a href="http://www.fimfiction.net/user/RogerDodger/" target="_blank">RogerDodger</a> (<a href="https://github.com/RogerDodger/PostTester" target="_blank">view source</a>)
	<br />
	Emulating <a href="http://www.kusabax.org/" target="_blank" rel="nofollow">Kusaba X</a>
</div>

</body>
