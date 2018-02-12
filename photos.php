<!doctype html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Photo Sharing</title>
</head>

<body>

<div class="container">
    <nav class="navbar navbar-light" style="background-color: #ADD8E6; background-size: 100%;">
        <a href="photos.php" class="btn btn-secondary btn-lg btn-block">Photos</a>
        <a href="upload.php" class="btn btn-secondary btn-lg btn-block">Upload</a>
    </nav>
	<div class="row">

	<?php
/*Scan directory for files*/
$photoDirArray = scandir('photos', 1);

/*Remoiving first folders from array*/
//unset($photoDirArray[0], $photoDirArray[1]);

/*Remoiving last folders from array*/
array_pop($photoDirArray);
array_pop($photoDirArray);

/*Foreach photos folder*/
foreach ($photoDirArray as $dir) {

	/*Scan directory for files*/
	$dataArray = scandir('photos/' . $dir, 1);

	/*Removing 'thumbnails' folder from array search*/
	for ($i = 0; $i < count($dataArray); $i++) {
		if ($dataArray[$i] == 'thumbnails') {unset($dataArray[$i]);}
	}
	/*Remoiving first folders from array*/
	//unset($dataArray[0], $dataArray[1]);

	/*Remoiving last folders from array*/
	array_pop($dataArray);
	array_pop($dataArray);

	/*Loading photos to html page view*/
	foreach ($dataArray as $img) {

		/*Set img location*/
		$imgLocation = 'photos/' . $dir . '/' . $img;

		/*Removing file resolution ec .jpg .gif etc..*/
		$imgThumb = explode('.', $img);
		array_pop($imgThumb);
		$imgThumb = implode($imgThumb);

		/*Set photo thumbnail name*/
		$imgThumbFile = $imgThumb . '.jpg';

		/*Set thumpnail location*/
		$imgThumbLocation = 'photos/' . $dir . '/thumbnails/' . $imgThumbFile;

		/*Check if thumbnail exists, if no -> set another resolution .jpg .gif etc..*/
		if (!file_exists($imgThumbLocation)) {
			$imgThumbFile = $imgThumb . '.JPG'; //from .jpg to .JPG for example
			$imgThumbLocation = 'photos/' . $dir . '/thumbnails/' . $imgThumbFile;
		}

		/*Creating bootstrap html view with thumbnails and photo links*/
		echo '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">';
		echo '<a target="_blank" href="' . $imgLocation . '"><img src="' . $imgThumbLocation . '" class="img-thumbnail"></a>';
		echo '</div>';
	}
}

?>

	</div>
	<nav class="navbar navbar-light" style="background-color: #ADD8E6; background-size: 100%;">
        <a href="photos.php" class="btn btn-secondary btn-lg btn-block">Photos</a>
        <a href="upload.php" class="btn btn-secondary btn-lg btn-block">Upload</a>
    </nav>
</div>
</body>
	<!-- ============================================================== -->
    <!-- JS and CSS -->
    <!-- ============================================================== -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <script src="assets/jquery/jquery.min.js"></script>
    <script src="assets/bootstrap/bootstrap.min.js"></script>