<?php
    include_once __DIR__ . '/../../../../../vendor/autoload.php';

    use DigitalSplash\Helpers\Helper;
use DigitalSplash\Media\Helpers\Media;
use DigitalSplash\Media\Helpers\Upload;

    if (!empty($_FILES)) {
        echo '<pre>';
        
		
		$upload = new Upload($_FILES, [] , __DIR__ . "/../../../../_CommonFiles/Media/UploadFolder");
		$result = $upload->upload();
		var_dump($result);
      

        echo '</pre>';
    }
?>

<html>
    <head>
        <style>
            .bloc {
                display: block;
                margin-bottom: 25px;
            }

            label,
            input {
                display: block;
                margin-bottom: 5px;
            }

            label {
                font-weight: 600;
            }
        </style>
    </head>
    <body>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="bloc">
                <label for="file_1">File 1</label>
                <input type="file" name="file_1[]" id="file_1" multiple>
            </div>
			<button>Submit</button>
        </form>
    </body>
</html>
