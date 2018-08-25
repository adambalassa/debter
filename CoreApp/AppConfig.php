<?php

	/**
	* Letsnet - AppConfig
	*
	* @author Letsnet <info@letsnet.hu>
	* @version 0.1
	* @category CoreApp Class
	* @uses CoreApp namespace
	*
	*/

	namespace CoreApp;

		class AppConfig {

			private static $appconfig_file_before = "App/config/";
			private static $appconfig_file_after = ".json";

			/**
			* The function returns to JSON object by default
			* @param bool $bool -> set this true to change the return type to PHP array
			*/

			public static function appConfigFile($configfile, $bool) {

				$appconfigfile = self::$appconfig_file_before . $configfile . self::$appconfig_file_after;

				if($bool) {
					return(json_decode(file_get_contents($appconfigfile), TRUE));
				}
				else {
					return(json_decode(file_get_contents($appconfigfile)));
				}
			}

			/**
			* Get data from the configuration json file by the arrowString structure
			* @param string $arrowString contains the object data (object=>object)
		    */

			public static function getData($configfile, $arrowString) {

				$config = self::appConfigFile($configfile, FALSE);

				$a = self::arrowString($arrowString);
				$c_a = count($a);

				for($i = 0; $i < $c_a; $i++) {
					$config = $config->{$a[$i]};
				}

				return($config);

			}

			public static function arrowString($string) {
				$array = explode("=>", $string);
				return $array;
			}

			/* end AppConfig CLASS */
		}
