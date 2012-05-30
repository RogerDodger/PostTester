<?php
/*
* This file is part of kusaba.
*
* kusaba is free software; you can redistribute it and/or modify it under the
* terms of the GNU General Public License as published by the Free Software
* Foundation; either version 2 of the License, or (at your option) any later
* version.
*
* kusaba is distributed in the hope that it will be useful, but WITHOUT ANY
* WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
* A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License along with
* kusaba; if not, write to the Free Software Foundation, Inc.,
* 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
* +------------------------------------------------------------------------------+
* Parse class
* +------------------------------------------------------------------------------+
* A post's message text will be passed, which will then be formatted and cleaned
* before being returned.
* +------------------------------------------------------------------------------+
*/
class Parse {
	var $boardtype;
	var $parentid;
	var $id;
	var $boardid;
	
	function MakeClickable($txt) {
		/* Make http:// urls in posts clickable */
		$txt = preg_replace('#(http://|https://|ftp://)([^(\s<|\[)]*)#', '<a href="\\1\\2">\\1\\2</a>', $txt);
		
		return $txt;
	} 
	
	function BBCode($string){
		$patterns = array(
			'`\[b\](.+?)\[/b\]`is', 
			'`\[i\](.+?)\[/i\]`is', 
			'`\[u\](.+?)\[/u\]`is', 
			'`\[s\](.+?)\[/s\]`is', 
			'`\[aa\](.+?)\[/aa\]`is', 
			'`\[(?:\?|spoiler)\](.+?)\[/(?:\?|spoiler)\]`is', 
			);
		$replaces =  array(
			'<b>\\1</b>', 
			'<i>\\1</i>', 
			'<span style="border-bottom: 1px solid">\\1</span>', 
			'<strike>\\1</strike>', 
			'<div style="font-family: Mona,\'MS PGothic\' !important;">\\1</div>', 
			'<!-- filler of 33 characters. --><span class="spoiler" onmouseover="this.className=\'spoiler spoiler-hover\'" onmouseout="this.className=\'spoiler\'">\\1</span>', 
			);
		$string = preg_replace($patterns, $replaces , $string);
		$string = preg_replace_callback('`\[rcv\](.+?)\[/rcv\]`is', array(&$this, 'royalluna'), $string);
		$string = preg_replace_callback('`\[code\](.+?)\[/code\]`is', array(&$this, 'code_callback'), $string);
		
		return $string;
	}
	
	function royalluna($matches) {
		$thou = $matches[1];
		$thou = strtoupper($thou);
		$thou = preg_replace('/\bI\b/', 'WE', $thou);
		$thou = preg_replace('/\bYOU\b/', 'THOU', $thou);
		$thou = preg_replace('/\bYOUR\b/', 'THINE', $thou);
		return '<span class="royalluna">'.$thou.'</span>';
	}
	
	function code_callback($matches) {
		$return = '<div style="white-space: pre !important;font-family: monospace !important;">'
		. str_replace('<br />', '', $matches[1]) .
		'</div>';
		
		return $return;
	}
	
	function ColoredQuote($buffer) {
		/* Add a \n to keep regular expressions happy */
		if (substr($buffer, -1, 1)!="\n") {
			$buffer .= "\n";
		}
		
		/* The css for imageboards use 'unkfunc' (???) as the class for quotes */
		$class = 'unkfunc';
		$linechar = "\n";
		
		$buffer = preg_replace('/^(&gt;[^>](.*))\n/m', '<span class="'.$class.'">\\1</span>' . $linechar, $buffer);
		
		return $buffer;
	}
	
	/**
	 * modified to be database independent
	 */
	function ClickableQuote($buffer, $board) {
		
		/* Add html for links to posts in the board the post was made */
		$buffer = preg_replace('/&gt;&gt;([0-9]+)/', '<a href="http://www.ponychan.net/chan/'.$board.'/res/$1.html#$1" onclick="return highlight(\'$1, true\');" class="ref|'.$board.'|$1|$1">&gt;&gt;$1</a>', $buffer);
		
		/* Add html for links to posts made in a different board */
		$buffer = preg_replace_callback('/&gt;&gt;\/([a-z]+)\/([0-9]+)/', array(&$this, 'InterboardQuoteCheck'), $buffer);
		
		/* Add html for links to different boards */
		$buffer = preg_replace_callback('/&gt;&gt;&gt;\/([a-z]+)\//', array(&$this, 'InterboardLink'), $buffer);
		
		return $buffer;
	}
	
	function InterboardQuoteCheck($match) {
		global $boards;
		if (in_array($match[1], $boards)) {
			return '<a href="http://www.ponychan.net/chan/'.$match[1].'/res/'.$match[2].'.html#'.$match[2].'">'."&gt;&gt;/{$match[1]}/{$match[2]}</a>";
		} else {
			return $match[0];
		}
	}
	
	function InterboardLink($match) {
		global $boards;
		if (in_array($match[1], $boards)) {
			return '<a href="http://www.ponychan.net/chan/'.$match[1].'">'."&gt;&gt;&gt;/{$match[1]}/</a>";
		} else {
			return $match[0];
		}
	}
	
	/* unneeded
	function Wordfilter($buffer, $board) {
		global $tc_db;
		
		$query = "SELECT * FROM `".KU_DBPREFIX."wordfilter`";
		$results = $tc_db->GetAll($query);
		foreach($results AS $line) {
			$array_boards = explode('|', $line['boards']);
			if (in_array($board, $array_boards)) {
				$replace_word = $line['word'];
				$replace_replacedby = $line['replacedby'];
				
				$buffer = ($line['regex'] == 1) ? preg_replace($replace_word, $replace_replacedby, $buffer) : str_ireplace($replace_word, $replace_replacedby, $buffer);
			}
		}
		
		return $buffer;
	}
	*/
	
	function CheckNotEmpty($buffer) {
		$buffer_temp = str_replace("\n", "", $buffer);
		$buffer_temp = str_replace("<br>", "", $buffer_temp);
		$buffer_temp = str_replace("<br/>", "", $buffer_temp);
		$buffer_temp = str_replace("<br />", "", $buffer_temp);

		$buffer_temp = str_replace(" ", "", $buffer_temp);
		
		if ($buffer_temp=="") {
			return "";
		} else {
			return $buffer;
		}
	}
	
	function CutWord($txt, $where) {
		$txt_split_primary = preg_split('/\n/', $txt);
		$txt_processed = '';
		$usemb = (function_exists('mb_substr') && function_exists('mb_strlen')) ? true : false;
		
		foreach ($txt_split_primary as $txt_split) {
			$txt_split_secondary = preg_split('/ /', $txt_split);
			
			foreach ($txt_split_secondary as $txt_segment) {
				$segment_length = ($usemb) ? mb_strlen($txt_segment) : strlen($txt_segment);
				while ($segment_length > $where) {
					if ($usemb) {
						$txt_processed .= mb_substr($txt_segment, 0, $where) . "\n";
						$txt_segment = mb_substr($txt_segment, $where);
						
						$segment_length = mb_strlen($txt_segment);
					} else {
						$txt_processed .= substr($txt_segment, 0, $where) . "\n";
						$txt_segment = substr($txt_segment, $where);
						
						$segment_length = strlen($txt_segment);
					}
				}
				
				$txt_processed .= $txt_segment . ' ';
			}
			
			$txt_processed = ($usemb) ? mb_substr($txt_processed, 0, -1) : substr($txt_processed, 0, -1);
			$txt_processed .= "\n";
		}
		
		return $txt_processed;
	}
	
	function ParsePost($message, $board) {
		
		$message = trim($message);
		$message = $this->CutWord($message, (KU_LINELENGTH / 15));
		$message = htmlspecialchars($message, ENT_QUOTES);
		// if (KU_MAKELINKS) {
			$message = $this->MakeClickable($message);
		// }
		$message = $this->ClickableQuote($message, $board);
		$message = $this->ColoredQuote($message);
		/*if (KU_MARKDOWN) {
			require KU_ROOTDIR . 'lib/markdown/markdown.php';
			$message = Markdown($message);
		}*/
		$message = str_replace("\n", '<br />', $message);
		$message = $this->BBCode($message);
		// $message = $this->Wordfilter($message, $board);
		$message = $this->CheckNotEmpty($message);
		
		return $message;
	}
}
?>