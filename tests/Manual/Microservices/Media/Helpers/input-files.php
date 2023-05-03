<?php
	include_once __DIR__ . '/../../../../../vendor/autoload.php';

	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Media\Helpers\Upload;

	if (!empty($_FILES)) {
		echo '<pre>';

		// var_dump(Helper::ConvertMultidimentionArrayToSingleDimention($_FILES));

		$upload = new Upload($_FILES);
		$result = $upload->upload();

		var_dump($result);
		echo '</pre>';
	}

/**
array(4) {
  ["single_file_one_level"]=>
  array(5) {
    ["name"]=>
    string(12) "wolf-002.jpg"
    ["type"]=>
    string(10) "image/jpeg"
    ["tmp_name"]=>
    string(25) "C:\wamp64\tmp\phpD567.tmp"
    ["error"]=>
    int(0)
    ["size"]=>
    int(86709)
  }
  ["multiple_files_one_level"]=>
  array(5) {
    ["name"]=>
    array(3) {
      [0]=>
      string(18) "1280x720 - 004.jpg"
      [1]=>
      string(18) "1280x720 - 003.jpg"
      [2]=>
      string(18) "1280x720 - 002.jpg"
    }
    ["type"]=>
    array(3) {
      [0]=>
      string(10) "image/jpeg"
      [1]=>
      string(10) "image/jpeg"
      [2]=>
      string(10) "image/jpeg"
    }
    ["tmp_name"]=>
    array(3) {
      [0]=>
      string(25) "C:\wamp64\tmp\phpD568.tmp"
      [1]=>
      string(25) "C:\wamp64\tmp\phpD569.tmp"
      [2]=>
      string(25) "C:\wamp64\tmp\phpD56A.tmp"
    }
    ["error"]=>
    array(3) {
      [0]=>
      int(0)
      [1]=>
      int(0)
      [2]=>
      int(0)
    }
    ["size"]=>
    array(3) {
      [0]=>
      int(155522)
      [1]=>
      int(46673)
      [2]=>
      int(50366)
    }
  }
  ["single_file_multi_level"]=>
  array(5) {
    ["name"]=>
    array(3) {
      ["file_1"]=>
      string(12) "wolf-002.jpg"
      ["file_2"]=>
      string(17) "500x500 - 002.jpg"
      ["file_3"]=>
      array(1) {
        ["subfile_1"]=>
        string(18) "1280x720 - 002.jpg"
      }
    }
    ["type"]=>
    array(3) {
      ["file_1"]=>
      string(10) "image/jpeg"
      ["file_2"]=>
      string(10) "image/jpeg"
      ["file_3"]=>
      array(1) {
        ["subfile_1"]=>
        string(10) "image/jpeg"
      }
    }
    ["tmp_name"]=>
    array(3) {
      ["file_1"]=>
      string(25) "C:\wamp64\tmp\phpD56B.tmp"
      ["file_2"]=>
      string(25) "C:\wamp64\tmp\phpD56C.tmp"
      ["file_3"]=>
      array(1) {
        ["subfile_1"]=>
        string(25) "C:\wamp64\tmp\phpD56D.tmp"
      }
    }
    ["error"]=>
    array(3) {
      ["file_1"]=>
      int(0)
      ["file_2"]=>
      int(0)
      ["file_3"]=>
      array(1) {
        ["subfile_1"]=>
        int(0)
      }
    }
    ["size"]=>
    array(3) {
      ["file_1"]=>
      int(86709)
      ["file_2"]=>
      int(30876)
      ["file_3"]=>
      array(1) {
        ["subfile_1"]=>
        int(50366)
      }
    }
  }
  ["multiple_files_multi_level"]=>
  array(5) {
    ["name"]=>
    array(2) {
      ["file_1"]=>
      array(3) {
        [0]=>
        string(18) "1280x720 - 003.jpg"
        [1]=>
        string(18) "1280x720 - 002.jpg"
        ["subfile_1"]=>
        array(2) {
          [0]=>
          string(17) "900x400 - 001.jpg"
          [1]=>
          string(17) "500x500 - 001.jpg"
        }
      }
      ["file_2"]=>
      array(3) {
        [0]=>
        string(18) "1280x720 - 004.jpg"
        [1]=>
        string(18) "1280x720 - 003.jpg"
        ["subfile_2"]=>
        array(2) {
          [0]=>
          string(12) "wolf-001.jpg"
          [1]=>
          string(11) "corndog.jpg"
        }
      }
    }
    ["type"]=>
    array(2) {
      ["file_1"]=>
      array(3) {
        [0]=>
        string(10) "image/jpeg"
        [1]=>
        string(10) "image/jpeg"
        ["subfile_1"]=>
        array(2) {
          [0]=>
          string(10) "image/jpeg"
          [1]=>
          string(10) "image/jpeg"
        }
      }
      ["file_2"]=>
      array(3) {
        [0]=>
        string(10) "image/jpeg"
        [1]=>
        string(10) "image/jpeg"
        ["subfile_2"]=>
        array(2) {
          [0]=>
          string(10) "image/jpeg"
          [1]=>
          string(10) "image/jpeg"
        }
      }
    }
    ["tmp_name"]=>
    array(2) {
      ["file_1"]=>
      array(3) {
        [0]=>
        string(25) "C:\wamp64\tmp\phpD56E.tmp"
        [1]=>
        string(25) "C:\wamp64\tmp\phpD56F.tmp"
        ["subfile_1"]=>
        array(2) {
          [0]=>
          string(25) "C:\wamp64\tmp\phpD570.tmp"
          [1]=>
          string(25) "C:\wamp64\tmp\phpD571.tmp"
        }
      }
      ["file_2"]=>
      array(3) {
        [0]=>
        string(25) "C:\wamp64\tmp\phpD582.tmp"
        [1]=>
        string(25) "C:\wamp64\tmp\phpD583.tmp"
        ["subfile_2"]=>
        array(2) {
          [0]=>
          string(25) "C:\wamp64\tmp\phpD584.tmp"
          [1]=>
          string(25) "C:\wamp64\tmp\phpD585.tmp"
        }
      }
    }
    ["error"]=>
    array(2) {
      ["file_1"]=>
      array(3) {
        [0]=>
        int(0)
        [1]=>
        int(0)
        ["subfile_1"]=>
        array(2) {
          [0]=>
          int(0)
          [1]=>
          int(0)
        }
      }
      ["file_2"]=>
      array(3) {
        [0]=>
        int(0)
        [1]=>
        int(0)
        ["subfile_2"]=>
        array(2) {
          [0]=>
          int(0)
          [1]=>
          int(0)
        }
      }
    }
    ["size"]=>
    array(2) {
      ["file_1"]=>
      array(3) {
        [0]=>
        int(46673)
        [1]=>
        int(50366)
        ["subfile_1"]=>
        array(2) {
          [0]=>
          int(35375)
          [1]=>
          int(32051)
        }
      }
      ["file_2"]=>
      array(3) {
        [0]=>
        int(155522)
        [1]=>
        int(46673)
        ["subfile_2"]=>
        array(2) {
          [0]=>
          int(41567)
          [1]=>
          int(124844)
        }
      }
    }
  }
}
*/
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
				<label>Single File One Level</label>
				<input type='file' name='single_file_one_level' />
			</div>

			<div class="bloc">
				<label>Multiple Files One Level</label>
				<input type='file' name='multiple_files_one_level[]' multiple />
			</div>

			<div class="bloc">
				<label>Single File Multi Level</label>
				<input type='file' name='single_file_multi_level[file_1]' />
				<input type='file' name='single_file_multi_level[file_2]' />
				<input type='file' name='single_file_multi_level[file_3][subfile_1]' />
			</div>

			<div class="bloc">
				<label>Multiple Files Multi Level</label>
				<input type='file' name='multiple_files_multi_level[file_1][]' multiple />
				<input type='file' name='multiple_files_multi_level[file_1][subfile_1][]' multiple />
				<input type='file' name='multiple_files_multi_level[file_2][]' multiple />
				<input type='file' name='multiple_files_multi_level[file_2][subfile_2][]' multiple />
			</div>

			<button>Submit</button>
		</form>
	</body>
</html>
