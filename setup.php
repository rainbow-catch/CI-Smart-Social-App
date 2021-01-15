<?php
// Setup file 
// This file is used for creating the config/database files. Please delete it once setup.

$stage = 0;
if(isset($_GET['stage'])) {
	$stage = intval($_GET['stage']);
}

if($stage == 0) {
	include("application/views/setup/step1.php");
} elseif($stage == 2) {
	$config_status = is_writable("application/config/config.php");
	if(!$config_status) {
		"The file application/config/config.php is not writable!";
		exit();
	}


	$site_url = $_POST['site_url'];
	$mod_rewrite = intval($_POST['mod_rewrite']);
	$sessions = intval($_POST['sessions']);
	$sub_folder = $_POST['sub_folder'];

	// Open Sample Config File
	$file = file_get_contents("application/config/config.sample.php");

	// Replace URL
	$file = str_replace("##BASE_URL##", $site_url, $file);

	if($mod_rewrite) {
		$file = str_replace("##INDEX_PAGE##", "", $file);

		// Modify .htaccess file to include subfolder
		if(!empty($sub_folder)) {
			$htaccess_status = is_writable(".htaccess");
			if(!$htaccess_status) {
				"The file .htaccess is not writable!";
				exit();
			}

			$access = file_get_contents("sample.htaccess");
			$access = str_replace("##SUB_FOLDER##", "RewriteBase /" . $sub_folder, $access);
			file_put_contents(".htaccess", $access);
		} else {
			$htaccess_status = is_writable(".htaccess");
			if(!$htaccess_status) {
				"The file .htaccess is not writable!";
				exit();
			}

			$access = file_get_contents("sample.htaccess");
			$access = str_replace("##SUB_FOLDER##", "", $access);
			file_put_contents(".htaccess", $access);
		}
	} else {
		$file = str_replace("##INDEX_PAGE##", "index.php", $file);

		$htaccess_status = is_writable(".htaccess");
		if(!$htaccess_status) {
			"The file .htaccess is not writable!";
			exit();
		}

		$access = file_get_contents("sample.htaccess");
		$access = str_replace("##SUB_FOLDER##", "", $access);
		file_put_contents(".htaccess", $access);
	}

	if($sessions) {
		$file = str_replace("##SESSION_DRIVER##", "files", $file);
		$file = str_replace("##SESSION_PATH##", "null", $file);
	} else {
		$file = str_replace("##SESSION_DRIVER##", "database", $file);
		$file = str_replace("##SESSION_PATH##", "'ci_sessions'", $file);
	}

	$random = md5(rand(1,10000000)) . sha1(rand(1,10000000)) . rand(1,10000) . md5(time());

	$file = str_replace("##ENCRYPTION_KEY##", $random, $file);

	file_put_contents("application/config/config.php", $file);

	header("Location: setup.php?stage=3");
	exit();
} elseif($stage == 3) {
	include("application/views/setup/step2.php");
} elseif($stage == 4) {
	$database_status = is_writable("application/config/database.php");
	if(!$database_status) {
		"The file application/config/database.php is not writable!";
		exit();
	}

	$database_host = $_POST['database_host'];
	$database_user = $_POST['database_user'];
	$database_name = $_POST['database_name'];
	$database_password = $_POST['database_password'];
	$database_driver = $_POST['database_driver'];

	// test connection
	if(!$database_driver) {
		$link = mysqli_connect($database_host, $database_user, $database_password, $database_name);
		if(!$link) {
			echo"Failed to connect to the database. Check your provided database values.<br /><br />" . mysqli_connect_error();
			exit();
		}
	} else {
		$link = mysql_connect($database_host, $database_user, $database_password, true, 65536);
		if(!$link) {
			echo"Failed to connect to the database. Check your provided database values.<br /><br />" . mysql_error();
			exit();
		}
		// Select DB
		$db = mysql_select_db($database_name);
		if(!$db) {
			echo"Failed to connect to the database. Check your provided database values.<br /><br />" . mysql_error();
			exit();
		}
	}

	if($database_driver) {
		$database_driver_name = "mysql";
	} else {
		$database_driver_name = "mysqli";
	}

	// Write to config file
	// Open Sample Config File
	$file = file_get_contents("application/config/database.sample.php");
	$file = str_replace("##DATABASE_HOST##", $database_host, $file);
	$file = str_replace("##DATABASE_USER##", $database_user, $file);
	$file = str_replace("##DATABASE_PASSWORD##", $database_password, $file);
	$file = str_replace("##DATABASE_NAME##", $database_name, $file);
	$file = str_replace("##DATABASE_DRIVER##", $database_driver_name, $file);
	file_put_contents("application/config/database.php", $file);
	

	// Create database
	$database_sql = file_get_contents("setup_db.sql");
	if(!$database_driver) {
		if($link->multi_query($database_sql)) {

		} else {
			echo"Could not execute SQL file. Please import the setup_db.sql file to your database in PHPmyAdmin.<br /><br />Once done, please proceeed to the <a href='setup.php?stage=5'>Admin Setup page</a>.";
			echo "<hr>" . $link->error;
			exit();
		}
	} else {
		$query = '';
		$error = "";
		$sql = file("setup_db.sql");
		foreach ($sql as $line) {
			// check for comment
			if (substr($line, 0, 2) == '--' || $line == '')
			    continue;
				// Build query
				$query .= $line;

			// check for end	
			if (substr(trim($line), -1, 1) == ';') {
			    // Perform the query
			    mysql_query($query) or $error .= 'Error performing query \'<strong>' . $query . '\': ' . mysql_error() . '<br /><br />';
			    // Reset temp variable to empty
			    $query = '';
			}
		}

		if(!empty($error)) {
			echo"There were errors when trying to import the database files:" . $error . "<br /><br />
			You may have to import the setup_db.sql file to your database in PHPmyAdmin.<br /><br />
			Once done, please proceeed to the <a href='setup.php?stage=5'>Admin Setup page</a>.";
			exit();
		}
	}

	header("Location: setup.php?stage=5");
} elseif($stage == 5) {
	include("application/views/setup/step3.php");
} elseif($stage == 6) {
	// Check for database table
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];

	// Validate usernam
	if (!preg_match("/^[a-z0-9_]+$/i", $username)) {
		echo "Username must only contain letters, numbers and underscores!";
		exit();
	}

	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	     //Valid email!
		echo "Email is invalid!";
		exit();
	}

	if($password != $password2) {
		echo "Your passwords do not match!";
		exit();
	}

	// Encrypt password
	include("application/libraries/PasswordHash.php");

	$phpass = new PasswordHash(12, false);
    $password = $phpass->HashPassword($password);

    // Create user
    // Connect to the database
    DEFINE("BASEPATH", "LOL");
    DEFINE("ENVIRONMENT", "DEV");
    include("application/config/database.php");
    if($db['default']['dbdriver'] != "mysql" 
    	&& $db['default']['dbdriver'] != "mysqli") {
    	echo "The installer only supports mysql and mysqli drivers. Please make 
    sure the settings in application/config/database.php use one of either of 
    these drivers. Once installation is complete, you can switch back to your 
    other driver.";
    	exit();
    }

    $database_user = $db['default']['username'];
    $database_host = $db['default']['hostname'];
    $database_password = $db['default']['password'];
    $database_name = $db['default']['database'];


    if($db['default']['dbdriver'] == "mysql") {
    	$link = mysql_connect($database_host, $database_user, $database_password, true, 65536);
		if(!$link) {
			echo"Failed to connect to the database. Check your provided database values.<br /><br />" . mysql_error();
			exit();
		}
		// Select DB
		$db = mysql_select_db($database_name);
		if(!$db) {
			echo"Failed to connect to the database. Check your provided database values.<br /><br />" . mysql_error();
			exit();
		}

		$s = mysql_query("SELECT `install` FROM `site_settings`") or die(mysql_error());
		$r = mysql_fetch_array($s);
		if(isset($r['install']) && $r['install'] == 0) {
			echo "The application has already been installed. A new admin account cannot be recreated. If you need to recreate the admin account, try a fresh installation (drop the database).";
			exit();
		}

		$dir = dirname(__FILE__);
		$query = mysql_query("INSERT INTO `users`(`email`,`username`,`password`,`first_name`,`last_name`,`user_role`,`IP`,`joined`,`joined_date`) VALUES('" . $email . "', '". $username . "', '" . $password . "', 'Admin', 'User', '1', '" . $_SERVER['REMOTE_ADDR'] . "', '".time()."', '".date("n-Y")."')") or die(mysql_error());

		$query = mysql_query("UPDATE `site_settings` SET `install` = 0, `upload_path_relative` = 'uploads', `upload_path` = '" . $dir . "/uploads' WHERE `ID` = 1") or die(mysql_error());
    } else {
    	$link = mysqli_connect($database_host, $database_user, $database_password, $database_name);
		if(!$link) {
			echo"Failed to connect to the database. Check your provided database values.<br /><br />" . mysqli_connect_error();
			exit();
		}

		$s = $link->query("SELECT `install` FROM `site_settings`");
		$r = $s->fetch_assoc();
		if(isset($r['install']) && $r['install'] == 0) {
			echo "The application has already been installed. A new admin account cannot be recreated. If you need to recreate the admin account, try a fresh installation (drop the database).";
			exit();
		}

		$s = $link->query("INSERT INTO `users`(`email`,`username`,`password`,`first_name`,`last_name`,`user_role`,`IP`,`joined`,`joined_date`) VALUES('" . $email . "', '". $username . "', '" . $password . "', 'Admin', 'User', '1', '" . $_SERVER['REMOTE_ADDR'] . "', '".time()."', '".date("n-Y")."')");
		if(!$s) {
			echo"Failed to create user: <br /><br />" . $link->error;
			exit();
		}


		$dir = dirname(__FILE__);
		$s = $link->query("UPDATE `site_settings` SET `install` = 0, `upload_path_relative` = 'uploads', `upload_path` = '" . $dir . "/uploads' WHERE `ID` = 1");
		if(!$s) {
			echo"Failed to setup site settings: <br /><br />" . $link->error;
			exit();
		}
    }

    // Chmod the files to non writable
    @chmod("application/config/config.php", 0644);
    @chmod("application/config/database.php", 0644);
    @chmod(".htaccess", 0644);
    header("Location: setup.php?stage=7");
} elseif($stage == 7) {
	 DEFINE("BASEPATH", "LOL");
    DEFINE("ENVIRONMENT", "DEV");
    include("application/config/config.php");
include("application/views/setup/step4.php");
} elseif($stage == 99) {
	// Check writable files
	$config_status = is_writable("application/config/config.php");
	$database_status = is_writable("application/config/database.php");
	$htaccess_status = is_writable(".htaccess");

	echo json_encode(array(
		"config_status" => $config_status,
		"database_status" => $database_status,
		"htaccess_status" => $htaccess_status
		)
	);
	exit();
}


function isEnabled($func) {
    return is_callable($func) && false === stripos(ini_get('disable_functions'), $func);
}
?>