<?php

    if ( isset($argv[1]) )
    {
        define('ENVIRONMENT', 'cron');
    }
    else
    {
        define('ENVIRONMENT', 'development');
    }

    define('VERSION', '0.5.1');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */

if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
			error_reporting(E_ALL);
            $start_process =  microtime(true);
		break;

		case 'testing':
        case 'cron':
            if ( ! isset($argv[1]) )
            {
                exit('Cron name error.');
            }

            define('CRON_NAME', $argv[1]);
		case 'production':
			error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}

/*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 *
 */
$system_path = '../system';

/*
 *---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 *
 */
$application_folder = '../application';

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

	// Set the current directory correctly for CLI requests
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}

	if (realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path).'/';
	}

	// ensure there's a trailing slash
	$system_path = rtrim($system_path, '/').'/';

	// Is the system path correct?
	if ( ! is_dir($system_path))
	{
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// The PHP file extension
	// this global constant is deprecated.
	define('EXT', '.php');

	// Path to the system folder
	define('BASEPATH', str_replace("\\", "/", $system_path));

	// Path to the front controller (this file)
	define('FCPATH', str_replace("\\", "/", str_replace(SELF, '', __FILE__) ) );

	// Name of the "system folder"
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));



	// The path to the "application" folder
	if (is_dir($application_folder))
	{
		define('APPPATH', $application_folder.'/');
	}
	else
	{
		if ( ! is_dir(BASEPATH.$application_folder.'/'))
		{
			exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define('APPPATH', BASEPATH.$application_folder.'/');
	}
/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */


    require_once BASEPATH.'engine.php';

    if (ENVIRONMENT == 'development')
    {
        $finish_process = microtime(true) - $start_process;
        echo '<h5 style="position:fixed; bottom:0; color:rgb(255, 255, 255);z-index:2000;">Generate: '.number_format($finish_process, 5, '.', ' ').' second;</h5>';
    }

/* End of file index.php */
/* Location: ./index.php */