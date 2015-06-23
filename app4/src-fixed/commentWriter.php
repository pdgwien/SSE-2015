<?php
		print "<html>";
		
		if (isset($_GET['comment'])) {
			$comment = $_GET['comment'] . "\n";
			print "Comment provided: $comment<br />";
			print "Storing comment..<br />";
			
			$comment .= file_get_contents('comments.txt');
			file_put_contents('comments.txt', $comment);
			
			print "Comment stored!";
		} else {
			print "No comment provided!";
		}
		print "</html>"
?>