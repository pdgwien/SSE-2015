<?php
require_once("../controller/CardController.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Validate your credit card</title>
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" type="text/css" href="css/esse_shop.css" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
</head>

<body>
	<div class="container">
	<div class="header">
	</div>
	<p>
		<?php echo validate(); ?>
	</p>
	<p><a href="search.htm">Go back...</a></p>
</div>
</body>

</html>