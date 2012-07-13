<style type="text/css" rel="stylesheet">
h3{
	text-align: left; 
	font: bold 1.1em serif; 
	display: inline-block;
	border-bottom: 1px dashed;
}
a.h{
	display: inline-block;
	color: #000;
}
span.code{
	white-space: pre !important;
	font-family: monospace !important;
}
</style>
<?php 
function _mktitle ($name) {
	return '<div style="margin-top: 1.5em;"><a class="h" href="#" onclick="return togdisp(\'t-'.strtolower($name).'\')"><h3>'.$name.'</h3></a></div>';
}
?>

<?php echo _mktitle ("Preface"); ?>
<div id="t-preface">
This is the help/debriefer for the post tester. It will be erased once you add content to your post. If you wish to get this text back, refresh the page or remove all the content in your post (i.e., make the “Message” field empty).
<br /><br />
This script is intended to emulate Ponychan’s poster for testing purposes. Most post idioms are implemented, sans the ones that would require access to protected server files (e.g., database queries, secure trips).
<br /><br />
Headers henceforth contain information pertinent to the respective input field. Clicking a header will collapse or expand its contents. (Requires Javascript.)
</div>

<?php echo _mktitle ("Name"); ?>
<div id="t-name">
The name field is your username. Defaults to “Anonymous”. A tripcode can be generated with the format <span class="code">&lt;name&gt;#&lt;trip&gt;</span>. Secure trips will be generated, but the output will likely be different than on Ponychan due to it using a secret salt.
</div>

<?php echo _mktitle ("E-mail"); ?>
<div id="t-e-mail">
The e-mail field will wrap your username in a mailto targeted at your e-mail address. Defaults to null.
</div>

<?php echo _mktitle ("Subject"); ?>
<div id="t-subject">
The subject field is the post’s title. Defaults to null.
</div>

<?php echo _mktitle ("Message"); ?>
<div id="t-message">
The message field contains the post’s content. Defaults to this help page. If defined, the number of characters in the post as Kusaba reads it will be given. Remember that Ponychan limits posts to 8,192 characters on most boards, and 40,000 on /fic/.
<br />
<br />
Posts will be parsed as plaintext with BBCode mark-up unless alternate behaviour is set (See Settings). Available mark-up:
<ul>
	<li>[b] <b>Bold</b> [/b]</li>
	<li>[i] <i>Italic</i> [/i]</li>
	<li>[u] <u>Underline</u> [/u]</li>
	<li>[s] <strike>Strikethrough</strike> [/s]</li>
	<li>[?] <!-- filler of 33 characters --><span class="spoiler" onmouseover="this.className='spoiler spoiler-hover'" onmouseout="this.className='spoiler'">Spoiler</span> [/?]</li>
	<li>[spoiler] <span class="spoiler" onmouseover="this.className='spoiler spoiler-hover'" onmouseout="this.className='spoiler'">Spoiler</span> [/spoiler]</li>
	<li>[rcv] <span class="royalluna">Royal Canterlot Voice</span> [/rcv]</li>
	<li>[aa] <span style="font-family: Mona, 'MS PGothic' !important;">Mona</span> [/aa]</li>
	<li>[code] <span style="white-space: pre !important;font-family: monospace !important;">Code</span> [/code]</li>
</ul>
Note that [aa] and [code] will create new blocks. (Essentially this means that the text will be forced onto a new line.)
<br />
<br />
Any text preceded by <u>http://</u>, <u>https://</u>, or <u>ftp://</u> will parse as a hyperlink. The link will continue “eating” characters until it meets one of following characters (where <span class="code">\s</span> is any whitespace character):
<ul><li><span class="code">\s()[&lt;|</span></li></ul>
If you want to break a link with a character not in the above list, you’ll need to put a zero-width non joiner (See <a href="http://en.wikipedia.org/wiki/Space_(punctuation)#Spaces_in_Unicode">[1]</a>) behind it. (This is useful when you want punctuation after a link. Default behaviour would put the punctuation in the link, possibly breaking it.)
<br />
<br />
Inter-board links use the following syntax:
<ul>
	<li><a href="#" onclick="return false">&gt;&gt;1234</a> &ndash; Link to a post on the current (or “active”) board. The current board may be changed by clicking on the headers. (Requires javascript. Defaults to /fic/.)
	<li><a href="#" onclick="return false">&gt;&gt;/pony/1234</a> &ndash; Link to a post on another board.
	<li><a href="#" onclick="return false">&gt;&gt;&gt;/pony/</a> &ndash; Link to another board page.
</ul>
Note that since I can’t query Ponychan’s database, links to posts in threads will be broken. I also cannot test the validity of a link, so while links to non-existent posts would not parse on Ponychan, they will here.
</div>

<?php echo _mktitle("Tags"); ?>
<div id="t-tags">
The tag field allows you to put tags in your post. They’re basically just boxes with the text prepended by a <span class="code">#</span> character. Tags are separated by commas. If you want to use a literal comma, insert two commas (e.g., to get the tag “#Me, Bob, and Alice”, input <span class="code">Me,, Bob,, and Alice</span>).
</div>

<?php echo _mktitle("File"); ?>
<div id="t-file">
The file field allows you to upload an image for your post. Max filesize of 4mb. Defaults to null.
</div>

<?php echo _mktitle("Settings") ?>
<div id="t-settings">
The settings checkboxes change posting behaviour.
<ul>
	<li><b>HTML: </b>Will dump the “Message” field straight in the post without parsing. Be careful: if you don’t close your tags, you might mess the page up.</li>
	<li><b>Truncate: </b>Will truncate posts of length larger than KU_LINELENGTH (set to 1800, same as Ponychan) or posts with 12 or more <span class="code">&lt;br /&gt;</span>s. This mimics how Ponychan truncates posts to be displayed in the board overviews.</li>
	<li><b>Small thumbnail: </b>Will resize the image to the size of post-thumbs, max 125x125, instead of the size of op-thumbs, max 200x800.</li>
</ul>
</div>
