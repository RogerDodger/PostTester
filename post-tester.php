<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- Post Tester to see how a post will look on Ponychan -->

<head>

<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Post Tester</title>
<link rel="shortcut icon" href="http://www.ponychan.net/chan/favicon.php">

<?php 
include 'pt-php_files/postjelly.php'; 
include 'pt-php_files/CParse.php';
include 'pt-php_files/formjelly.php';
?>

<link rel="stylesheet" type="text/css" href="pt-php_files/img_global.css">
<link rel="stylesheet" type="text/css" href="pt-php_files/colgate.css">
<link rel="stylesheet" type="text/css" href="pt-php_files/post-tester.css">

<script type="text/javascript" src="pt-php_files/pt-php.js" charset="utf-8"></script>
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
	<input type="hidden" name="board" id="formboard" value="<?php echo isset($board)? $board : 'fic'; ?>">
	<table class="postform">
		<tr>
			<td class="postblock">Name</td>
			<td><input type="text" name="name" size="28" maxlength="75" value="<?php echo isset($_POST["name"])? htmlspecialchars($_POST["name"]): ''; ?>"></td>
		</tr>

		<tr>
			<td class="postblock">E-mail</td>
			<td><input type="text" name="email" size="28" maxlength="75" value="<?php echo isset($_POST["email"])? htmlspecialchars($_POST["email"]): ''; ?>"></td>
		</tr>

		<tr>
			<td class="postblock">Subject</td>
			<td>
				<input type="text" name="title" size="35" maxlength="75" value="<?php echo isset($_POST["title"])? htmlspecialchars($_POST["title"]): ''; ?>">
				<input type="submit" value="Update Post">
			</td>
		</tr>
		
		<tr>
			<td class="postblock">Message</td>
			<td><textarea name="content" cols="48" rows="6"><?php echo isset($_POST["content"]) ? htmlspecialchars($_POST["content"]) : ''; ?></textarea></td>
		</tr>

		<tr>
			<td class="postblock">File</td>
			<td><input type="text" name="imgsrc" size="35" value="<?php echo isset($_POST["imgsrc"])? htmlspecialchars($_POST["imgsrc"]): ''; ?>"></td>
		</tr>
		
		<tr>
			<td class="postblock">Settings</td>
			<td>
				<input type="checkbox" name="noparse" <?php if(isset($_POST["noparse"])) {echo 'checked="yes"';} ?>> HTML &nbsp; 
				<input type="checkbox" name="trunc" <?php if(isset($_POST["trunc"])) {echo 'checked="yes"';} ?>> Truncate &nbsp;
				<input type="checkbox" name="small-thumb" <?php if(isset($_POST["small-thumb"])) {echo 'checked="yes"';} ?>> Small thumbnail &nbsp;
			</td>
		</tr>
	</table>
	
	</form>
</div>
<hr />

<?php if (isset($postlen) && !preg_match('/^\\s*$/', $content)): ?>
<p style="text-align:center;">Your post is <?php echo $postlen ?> characters long.</p>

<hr />
<?php endif ?>

<div class="thread" id="threadNNNNN">

<?php if ($image["exists"]): ?>
<span class="filesize">
	File <a href="#" onclick="return false"><?php echo '133'; 
	foreach(range(0,8) as $dump){ 
		echo rand(0,9);
	} 
	echo '.jpg'; 
	?></a> - (<?php echo $image["size"] ?>, <span class="dimensions"><?php echo $image["width"].'x'.$image["height"] ?></span>, <?php echo $image["title"]; ?>)
</span>
<br/ >
<a href="#" onclick="return false"><img <?php echo "src=\"{$image["src"]}\" height=\"{$image["h"]}\" width=\"{$image["w"]}\""?> class="thumb"></a>
<?php endif; ?>

<a name="NNNNN" style="position: relative; top: -48px; "><span style="position: relative; top: 48px; "></span></a>
<label>
	<input type="checkbox">
	<?php echo isset($title) ? '<span class="filetitle">'.$title.'</span>' : ''; ?>
	<span class="postername"><?php echo $name; ?></span><?php 
	echo isset($trip) ? '<span class="postertrip">!'.$trip.'</span>' : ''; 
	?>
	<span class="posttime"><?php echo date('D, d M Y H:i:s \G\M\T'); ?></span>
</label>

<span class="reflink">
	<a href="#">No.&nbsp;</a><a href="#">NNNNN</a>
</span>

<br>

<blockquote>
	<div id="content"><?php if(preg_match('/^\\s*$/', $content)) {include 'pt-php_files/help.php';} else {echo $content;} ?></div>
</blockquote>

<div class="postfooter">
	<a href="#" onclick="return false">Reply</a> &#8226; <a href="#" onclick="return false">Watch</a> &#8226; <a href="#" onclick="return false">Report</a>
</div>

<a href="#" onclick="return false">Unspoiler all text</a> &#8226; <a href="#" onclick="return false">Expand all images</a> &#8226; <a href="#" onclick="return false">Reveal spoilers</a>

<span style="float:right">
	[<a href="#" onclick="return false">Return</a>]
	[<a href="#" onclick="return false">Entire Thread</a>]
	[<a href="#" onclick="return false">Last 50 posts</a>]
	[<a href="#" onclick="return false">First 100 posts</a>]
</span>

</div>

<hr />

<br />

<div class="footer" style="clear:both">
	Powered by <a href="http://www.kusabax.org/" target="_top" rel="nofollow">Kusaba X</a>
</div>

</body>
