<?php
	function test_sqlinject($input) {
	
		/* This function returns a string with whitespace stripped
		 from the beginning and end of str.
		 Without the second parameter, trim() will strip these characters:

		    " " (ASCII 32 (0x20)), an ordinary space.
		    "\t" (ASCII 9 (0x09)), a tab.
		    "\n" (ASCII 10 (0x0A)), a new line (line feed).
		    "\r" (ASCII 13 (0x0D)), a carriage return.
		    "\0" (ASCII 0 (0x00)), the NUL-byte.
		    "\x0B" (ASCII 11 (0x0B)), a vertical tab.	*/
	
		$input = trim($input);

		//Returns a string with backslashes stripped off

		$input = stripslashes($input);

		//Convert special characters to HTML entities

		$input = htmlspecialchars($input);

		return $input;
	}
?>
