<?php
class FirecakeHelper extends Helper
{
	/**
	 * Firecake Helper
	 * ---------------
	 * This will repeat messages from the error.log as well as variables, form data, 
	 * validation errors, session data, and other useful info into the Firebug console 
	 * window.  Other than the normal log usage, you don't have to tell it to trace anything,
	 * it'll get the info automagically.
	 *
	 * Installation:
	 * -------------
	 * (1.) Install Firebug, http://www.getfirebug.com, reopen browser, turn Firebug on.
	 * 
	 * (2.) Turn debug on in /app/config/core.php  ie: define('DEBUG', 2);
	 * 
	 * (3.) Enable helpers in /app/app_controller.php 
	 * var $helpers = array('Html', 'Javascript', 'Firecake');
	 * 
	 * (4.) Add $firecake->view($this); between the <HEAD> tags of your layout file(s). 
	 * ie: /app/views/layouts/default.thtml
	 * ie: <HEAD><?php $firecake->view($this); ?></HEAD><BODY>Welcome to my site! Yay!...
	 * 
	 * You can add a second parameter to this if you just want the log.
	 * ie: $firecake->view($this,1);
	 * 
	 * (5.) Now just save this file as app/views/helpers/firecake.php
	 * 
	 * (6.) Tweak it.  In the view function, comment out data you don't want,
	 * like if you're not using sessions, comment out $script .= "\n".$this->getSession();
	 * by placing // in front of it.  Maybe you can think of a whole other category to add.
	 * If you want to avoid the alphabetical order Firebug uses, try numbering the keys, like
	 * ['Session'] becomes ['1. Sessions'].
	 * 
	 * (7.) Now quit playing with it and go build an app already!
	 * 
	 * Credits:
	 * --------
	 * Discussion and ideas for this started at:
	 * http://groups.google.com/group/cake-php/browse_thread/thread/5e041ccdc9d60131/
	 * 4987cb9652108bb9?#4987cb9652108bb9
	 * 
	 * In that thread, NOSLOW posted an awesome helper that outputed the variables and
	 * info at the bottom of the page.  It works great, but I was itchy to make use of the
	 * new Firebug plugin for Firefox!  So I modified it to output into Firebug instead of
	 * at the bottom of the page.  Looking for more ideas, I also used cakeinfo.php,
	 * a script released under MIT license by Masashi Shinbara.  I basically just mashed
	 * everything together, so you got all kinds of useful information in [+] tree order
	 * to sift through.
	 * 
	 * The php2js() function was modified only slightly to work with cake and the original
	 * can be found at http://paws.de/blog/2006/12/25/export-php-variables-to-javascript/
	 * 
	 */


	var $helpers = array('Html','Javascript','Session');

	function __construct() {
		$this->conf = Configure::getInstance();
		$this->version = $this->conf->version();
	}

	function view($var, $mode=null)
	{
		//if all you want to do is trace stuff in the error log, try this!
		//$mode = 1;


		if (empty($mode))
		{

			//define javascript array
			$script = "\nvar fbout = new Array();";

			//comment out the ones you don't need.
			//the first 4 or 5 are suggested mostly.
			//the others are general info that doesn't change much, but
			//might be good for familarization with the way cake works.

			$script .= "\n".$this->getSessions();
			$script .= "\n".$this->getPageData($var);
			$script .= "\n".$this->getValidationErrors($var);
			$script .= "\n".$this->getVars($var);
			$script .= "\n".$this->getLogs();
			
			//$script .= "\n"."fbout['Version'] = '".$this->version."';";
			//$script .= "\n".$this->getConstants();
			//$script .= "\n".$this->getPaths();
			//$script .= "\n".$this->getModels();
			//$script .= "\n".$this->getControllers();
			//$script .= "\n".$this->getPhp();
			//$script .= "\n".$this->getModules();


			//now echo it out and call the Firebug console.
			echo $this->Javascript->codeBlock($script." \nconsole.dir(fbout);\n");
		} else {
			$array = array();
			$array = $this->getLogs($mode);

			echo '<script type="text/javascript">';

			foreach($array as $b) {
				if ($b != "--[]--") {
					echo "\n".'console.info("'.$b.'");';
				}
			}
			echo '</script>';


		}

	}


	/**
	 * Parse the log file, add line marker when necessary, etc.  If $mode isn't empty
	 * then it will return a normal php array instead of getting turned into a Javascript
	 * array that Firebug can understand.
	 */
	function getLogs($mode=null) {
		$logMarker = "--[]--";  //just an obscure string to help seperate old and new logs.
		$logFile = LOGS."error.log"; //might need to change this depending on server.
		$logRecent = false; //true if you just want the latest "this time" logs, false for all.

		if (file_exists($logFile)) {
			//it exists, lets put it into an array with newest items at top.
			$output = array_reverse(file($logFile));

			if ($logRecent) {
				//$key = how many log entrys since the last log marker was inserted
				$key = array_search($logMarker . "\n", $output);

				//trim array
				if ($key) {
					$output = array_slice($output, 0, $key);
					$output = array_reverse($output);
				}

				//add a new marker. edit directly to avoid timestamp.
				$fd = fopen($logFile, "a");
				if (($fd) && (!empty($fd)) && ($key != 0)) {
					//if it opened and the last line isn't already a marker.
					fwrite($fd, $logMarker . "\n");
					fclose($fd);
				} else {
					$output = null;
				}
			}

			//return results depending on mode and if there is data.
			if ($output) {
				if (empty($mode)) {
					return "fbout['Logs'] = ".$this->_php2js($output).";";
				} else {
					return str_replace("\n","",$output);
				}
			} else {
				if (empty($mode)) {
					return "fbout['Logs'] = 'No new logs';";
				} else {
					return $array = array("No new logs");
				}
			}
		}
	}


	/**
	 * This will parse files will Constants can be found and return them to us
	 * in a nice array.
	 */
	function getConstants() {
		$a = file(ROOT . DS . APP_DIR . DS . 'config' . DS . 'core.php');
		//$b = file(ROOT . DS . APP_DIR . DS . 'webroot' . DS . 'index.php');
		$b = file(WWW_ROOT . 'index.php'); 
		$contents = array_merge($a,$b);

		$array = array();
		foreach ($contents as $line) {
			if (preg_match("/define\('([^']+)'/", $line , $m)) {
				$name = $m[1];

				if (defined($name)) {
					$array[$name] = constant($name);
				}
			}
		}
		return "fbout['Constants'] = ".$this->_php2js($array).";";
	}


	/**
	 * Get paths.
	 */
	function getPaths() {
		$array = array();

		$paths = get_object_vars($this->conf);
		foreach ($paths as $k => $v) {
			if (preg_match("/Path/", $k)) {
				if (count($v) == 1) {
					$array[$k] = $v[0];
				} else {
					$array[$k] = $v;
				}
			}
		}
		return "fbout['Paths'] = ".$this->_php2js($array).";";
	}

	/**
	 * Get each controller and some info about them put into an array.
	 */
	function getControllers() {
		$array = array();

		$paths = $this->_getFileListDirs($this->conf->controllerPaths);
		foreach ($paths as $path) {
			if (is_file($path) && preg_match("/^(.+)_controller\.php$/", basename($path), $m)) {
				$ctrlName = Inflector::camelize($m[1]);
				loadController($ctrlName);
				$class = $ctrlName . 'Controller';
				$obj = new $class();

				$v = $this->_getClassDiffValues(get_class_vars('Controller'), get_object_vars($obj));
				$v['view'] = $this->_getViewList($ctrlName);

				$array[$ctrlName] = $v;
			}
		}

		return "fbout['Controllers'] = ".$this->_php2js($array).";";
	}

	/**
	 * Get info on the sessions, each key and value, etc.
	 */
	function getSessions() {
		return "fbout['Sessions'] = ".$this->_php2js($this->Session->read()).";";
	}

	/**
	 * Get info about each Model.
	 */
	function getModels() {
		$array = array();

		$paths = $this->_getFileListDirs($this->conf->modelPaths);
		foreach ($paths as $path) {
			if (is_file($path) && preg_match("/^(.+)\.php$/", basename($path), $m)) {
				$modelName = Inflector::camelize($m[1]);
				loadModel($modelName);

				$v = $this->_getClassDiffValues(get_class_vars('Model'), get_class_vars($modelName));

				$array[$modelName] = $v;
			}
		}

		return "fbout['Models'] = ".$this->_php2js($array).";";
	}


	/**
	 * Get page data, generally form submissions.
	 */
	function getPageData($var) {
		if (isset($var->data)) {
			return "fbout['Data'] = ".$this->_php2js($var->data).";";
		} else {
			return "fbout['Data'] = 'No Data Submitted';";
		}
	}


	/**
	 * If a form was submitted, this will let you know how well it validated.  A key
	 * equal to "1" is bad, not listing the key means the data was good for that field.
	 */
	function getValidationErrors($var) {
		if (isset($var->validationErrors)) {
			return "fbout['Validation'] = ".$this->_php2js($var->validationErrors).";";
		} else {
			return "fbout['Validation'] = 'No Validation Errors';";
		}
	}

	/**
	 * Get variables in the view, some you set, some cake takes care of.
	 */
	function getVars($var) {
		$array = array('here' => $var->here,'pageTitle' => $var->pageTitle,'layout' => $var->layout);

		if (isset($var->viewVars)) {
			return "fbout['Variables'] = ".$this->_php2js(array_merge($var->viewVars,$array)).";";
		} else {
			return "fbout['Variables'] = 'No Data Set For View';";
		}

	}

	/**
	 * Get some info about your installation.
	 */
	function getPhp() {

		$array = array();
		$array['VERSION'] = phpversion();
		$array['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
		$array['SERVER_PORT'] = $_SERVER['SERVER_PORT'];
		$array['SCRIPT_FILENAME'] = $_SERVER['SCRIPT_FILENAME'];
		$array['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'];

		return "fbout['PHP Info'] = ".$this->_php2js($array).";";
	}

	/**
	 * Make sure you got mod_rewrite installed and other stuff.
	 */
	function getModules() {
		return "fbout['Apache Modules'] = ".$this->_php2js(apache_get_modules()).";";
	}


	/**
	 * The rest are all utility functions that aid the ones above, such as
	 * array parsing and grabbing a list of files from a directory.
	 */
	function _getFileListDirs($dirPaths) {
		$array = array();

		foreach ($dirPaths as $dirPath) {
			$array += $this->_getFileList($dirPath);
		}

		return $array;
	}

	function _getFileList($dirPath) {
		$array = array();

		if (!file_exists($dirPath) || !is_dir($dirPath)) {
			return $array;
		}
		$d = dir($dirPath);

		while ($file = $d->read()) {
			if ($file == '.' || $file == '..') {
				continue;
			}

			if (substr($dirPath, -1) != DS) {
				$dirPath .= DS;
			}
			$path = $dirPath . $file;
			if (is_dir($path)) {
				$array += $this->_getFileList($path);
			}

			$array[] = $path;
		}

		return $array;
	}
	function _getClassDiffValues(&$baseVars, &$classVars) {
		$array = array();

		foreach ($classVars as $name => $var) {
			if (@$baseVars[$name] !== $classVars[$name]) {
				$array[$name] = $this->_arrayToString($var);
			}
		}

		return $array;
	}

	function _arrayToString(&$value) {
		$str = '';

		if (is_array($value)) {
			foreach ($value as $k => $v) {
				if (!empty($str)) {
					$str .= '","';
				}
				if (is_array($v)) {
					$str .= $k;
				} else {
					$str .= (is_numeric($k) ? $v : $k);
				}
			}
		} else {
			$str =  $value;
		}

		return $str;
	}

	function _getViewList($ctrlName) {
		$array = array();

		foreach ($this->conf->viewPaths as $dirPath) {
			$dirPath .= Inflector::underscore($ctrlName);

			$array[] = sprintf('[%s]', $dirPath);
			$offset = strlen($dirPath) + 1; // add DS character

			$paths = $this->_getFileList($dirPath);
			foreach ($paths as $path) {
				if (is_file($path) && preg_match("/(.ctp|.thtml)$/", $path)) {
					$array[] = substr($path, $offset);
				}
			}
		}

		return $array;
	}


	/**
	 * converts PHP array into a string that Javascript will be able to put into an array.
	 */
	function _php2js($dta) {
		if(is_object($dta)) {
			$dta = get_object_vars($dta);
		}
		if(is_array($dta)) {
			foreach($dta AS $k=>$d)
			$dta[$k] = $this->_php2js($k).":".str_replace("\n","",$this->_php2js($d));
			return '{'.implode(',',$dta).'}';
		} elseif(is_numeric($dta)) {
			return $dta;
		}elseif(is_string($dta)) {
			$dta = str_replace('\\','/',$dta);
			return '"'.str_replace('"','\"',$dta).'"';
		} else {
			return 'null';
		}
	}
}
?>