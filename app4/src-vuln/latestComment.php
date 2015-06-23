<?php
		print "<html>";
		print "<div id=\"test\">";
		print "Latest comment: <br />";
		
		$comment = "";
		
		$handle = fopen("comments.txt", "r");
		if ($handle) {
			$comment = fgets($handle);
			
			print $comment;
			
			fclose($handle);
		} else {
			echo "Comment couldn't be read!";
		} 
		print "</div>";
		print "</html>"
?>