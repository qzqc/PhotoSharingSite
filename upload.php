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
    <div class="alert alert-warning">
      <strong>Select 1 file at once!</strong>
    </div>
<div class="col-12">
<center>
    <form action="upload.php" method="post" multipart="" enctype="multipart/form-data">
        <input type="file" class="dropify" data-allowed-file-extensions="jpg JPG png bmp tiff" name="img[]"/>
        <br>
        <button type="submit" class="btn btn-primary btn-lg btn-block">Upload</button>
    </form>
</center>
</div>

<?php
/*Checking if data exists*/
if (isset($_FILES['img'])) {
	$img = $_FILES['img'];}

/*Checking if $img array not empty*/
if (!empty($img)) {

	/*New array*/
	$img_desc = reArrayFiles($img);

	/*Do it foreach img (multiple img uploads does not works)*/
	foreach ($img_desc as $val) {

		/*Set photo file name*/
		$newname = date('Ymd') . '_' . random_int(10000, 99999) . '_' . $val['name'];
		move_uploaded_file($val['tmp_name'], './photos/uploads/' . $newname);

		/*Catching errors*/
		try {
			generateThumbnail('photos/uploads/' . $newname, 400, 300, 65);
		} catch (ImagickException $e) {
			echo '<center>' . $e->getMessage() . '</center>';
		} catch (Exception $e) {
			echo '<center>' . $e->getMessage() . '</center>';
		}
	}

	/*Creating html view with message*/
	echo '<center><h3> Files successfully uploaded! </h3></center>';

	/*OR creating JavaScript success message*/
	// echo '<script language="javascript">';
	// echo 'alert("file successfully uploaded")';
	// echo '</script>';

}

/*Creating new array function*/
function reArrayFiles($file) {
	$file_ary = array();
	$file_count = count($file['name']);
	$file_key = array_keys($file);

	for ($i = 0; $i < $file_count; $i++) {
		foreach ($file_key as $val) {
			$file_ary[$i][$val] = $file[$val][$i];
		}
	}
	return $file_ary;
}

/*Generating thumbnail for photo*/
function generateThumbnail($img, $width, $height, $quality = 90) {
	if (is_file($img)) {
		$imagick = new Imagick(realpath($img));
		$imagick->setImageFormat('jpeg');
		$imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
		$imagick->setImageCompressionQuality($quality);
		$imagick->thumbnailImage($width, $height, false, false);
		$filename_no_ext = explode('.', $img);
		$filename_no_ext = str_replace('photos/uploads/', '', $filename_no_ext[0]);
		if (file_put_contents('photos/uploads/thumbnails/' . $filename_no_ext . '.jpg', $imagick) === false) {
			throw new Exception("Could not put contents.");
		}
		return true;
	} else {
		throw new Exception("No valid image provided with {$img}.");
	}
}
?>
</div>
</body>

    <!-- ============================================================== -->
    <!-- JS and CSS -->
    <!-- ============================================================== -->
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/dropify/dropify.min.css">
    <script src="assets/jquery/jquery.min.js"></script>
    <script src="assets/bootstrap/bootstrap.min.js"></script>
    <script src="assets/dropify/dropify.min.js"></script>

    <script>
    $(document).ready(function() {
        // Basic
        $('.dropify').dropify({
            error: {
                'imageFormat': 'The image format is not allowed ({{ value }} only).'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        drEvent.on('dropify.error.imageFormat', function(event, element){
            alert('The image format is not allowed!');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
    </script>