<?php

require_once(dirname(dirname(dirname(__FILE__)))."/public_html/app/util/Logger.php");

Logger::setPrefix(dirname(dirname(dirname(__FILE__)))) ;

class Deployer {
	var $username ;
	var $password ;
	var $hostname ;
	var $connection ;
    /**
	 * Debug flag
	 *
	 * Specifies whether to display error messages.
	 *
	 * @var	bool
	 */
	public $debug = FALSE;
    /**
	 * Passive mode flag
	 *
	 * @var	bool
	 */
	public $passive = TRUE;
	
	public function __construct() {
		$this->hostname = "www.storien.com" ;
	}
    
	public function getCredentials() {
		$handle = fopen ("php://stdin","r");
		echo "username :" ;
		$this->username = fgets($handle);
		$this->username = trim($this->username) ;
		echo "password :" ;
		$this->password = fgets($handle);
		$this->password = trim($this->password);
		Logger::log(__FILE__,__LINE__,$this->username) ;
		Logger::log(__FILE__,__LINE__,$this->password) ;
	}
    
    /**
     * deploy
     * @param string $locpath   Path to source with trailing slash
     * @param string $rempath   Path to destination - include the base folder with trailing slash
     */
	public function deploy($locpath,$rempath) {
		$this->getCredentials() ;
		$this->loginToServer() ;
		echo ftp_pwd($this->connection);
        
        // Set passive mode if needed
		if ($this->passive === TRUE)
		{
			ftp_pasv($this->connection, TRUE);
		}
        
        $this->mirror($locpath, $rempath);
//		$contents = ftp_nlist($this->connection, ".");
//		foreach($contents as $content) {
//			echo $content . PHP_EOL ;
//		} 

	} 
    /**
	 * Read a directory and recreate it remotely
	 *
	 * This function recursively reads a folder and everything it contains
	 * (including sub-folders) and creates a mirror via FTP based on it.
	 * Whatever the directory structure of the original file path will be
	 * recreated on the server.
	 *
	 * @param	string	$locpath	Path to source with trailing slash
	 * @param	string	$rempath	Path to destination - include the base folder with trailing slash
	 * @return	bool
	 */
	public function mirror($locpath, $rempath)
	{
		// Open the local file path
		if ($fp = @opendir($locpath))
		{
			// Attempt to open the remote file path and try to create it, if it doesn't exist
//			if ( ! $this->changedir($rempath, TRUE) && ( ! $this->mkdir($rempath) OR ! $this->changedir($rempath)))
//			{
//				return FALSE;
//			}

			// Recursively read the local directory
			while (FALSE !== ($file = readdir($fp)))
			{
				if (is_dir($locpath.$file) && $file[0] !== '.')
				{
					$this->mirror($locpath.$file.'/', $rempath.$file.'/');
				}
				elseif ($file[0] !== '.')
				{
					// Get the file extension so we can se the upload type
					$ext = $this->_getext($file);
					$mode = $this->_settype($ext);
                    var_dump($ext);
                    var_dump($mode);
					$this->upload($locpath.$file, $rempath.$file, $mode);
				}
			}

			return TRUE;
		}

		return FALSE;
	}
    
    /**
	 * Extract the file extension
	 *
	 * @param	string	$filename
	 * @return	string
	 */
	protected function _getext($filename)
	{
		return (($dot = strrpos($filename, '.')) === FALSE)
			? 'txt'
			: substr($filename, $dot + 1);
	}
    
    /**
	 * Set the upload type
	 *
	 * @param	string	$ext	Filename extension
	 * @return	string
	 */
	protected function _settype($ext)
	{
		return in_array($ext, array('txt', 'text', 'php', 'phps', 'php4', 'js', 'css', 'htm', 'html', 'phtml', 'shtml', 'log', 'xml'), TRUE)
			? 'ascii'
			: 'binary';
	}
    
    /**
	 * Change directory
	 *
	 * The second parameter lets us momentarily turn off debugging so that
	 * this function can be used to test for the existence of a folder
	 * without throwing an error. There's no FTP equivalent to is_dir()
	 * so we do it by trying to change to a particular directory.
	 * Internally, this parameter is only used by the "mirror" function below.
	 *
	 * @param	string	$path
	 * @param	bool	$suppress_debug
	 * @return	bool
	 */
    public function changedir($path, $suppress_debug = FALSE)
	{
		$result = @ftp_chdir($this->connection, $path);

		if ($result === FALSE)
		{
			if ($this->debug === TRUE && $suppress_debug === FALSE)
			{
                Logger::log(__FILE__,__LINE__,'ftp_unable_to_changedir') ;
			}

			return FALSE;
		}

		return TRUE;
	}
    
    /**
	 * Create a directory
	 *
	 * @param	string	$path
	 * @param	int	$permissions
	 * @return	bool
	 */
	public function mkdir($path, $permissions = NULL)
	{
        var_dump($path);
		if ($path === '')
		{
			return FALSE;
		}

		$result = @ftp_mkdir($this->connection, $path);
        var_dump($result);
        die;
		if ($result === FALSE)
		{
			if ($this->debug === TRUE)
			{
                Logger::log(__FILE__,__LINE__,'ftp_unable_to_mkdir') ;
			}

			return FALSE;
		}

		// Set file permissions if needed
		if ($permissions !== NULL)
		{
			$this->chmod($path, (int) $permissions);
		}

		return TRUE;
	}
    
    /**
	 * Set file permissions
	 *
	 * @param	string	$path	File path
	 * @param	int	$perm	Permissions
	 * @return	bool
	 */
	public function chmod($path, $perm)
	{
		if (@ftp_chmod($this->connection, $perm, $path) === FALSE)
		{
			if ($this->debug === TRUE)
			{
                Logger::log(__FILE__,__LINE__,'ftp_unable_to_chmod') ;
			}

			return FALSE;
		}

		return TRUE;
	}
	public function loginToServer() {
		$this->connection = ftp_connect($this->hostname);
		// login with username and password
		$login_result = ftp_login($this->connection, 
						$this->username, $this->password);
		
		// check connection
		if ((!$this->connection) || (!$login_result)) {
			echo "FTP connection has failed!" . PHP_EOL ;
			echo "Attempted to connect to ". $this->hostname  . PHP_EOL ;
			exit;
		} else {
			echo "Connected to " . $this->hostname . PHP_EOL ;
		}		
	}
	
    /**
	 * Upload a file to the server
	 *
	 * @param	string	$locpath
	 * @param	string	$rempath
	 * @param	string	$mode
	 * @param	int	$permissions
	 * @return	bool
	 */
	public function upload($locpath, $rempath, $mode = 'auto', $permissions = NULL)
	{
        var_dump($locpath);
		if ( ! file_exists($locpath))
		{
            Logger::log(__FILE__,__LINE__,'ftp_no_source_file') ;
			return FALSE;
		}

		// Set the mode if not specified
		if ($mode === 'auto')
		{
			// Get the file extension so we can set the upload type
			$ext = $this->_getext($locpath);
			$mode = $this->_settype($ext);
		}

		$mode = ($mode === 'ascii') ? FTP_ASCII : FTP_BINARY;
        var_dump($rempath);
        
		$result = @ftp_put($this->connection, $rempath, $locpath, $mode);
        var_dump($result);
        
		if ($result === FALSE)
		{
			if ($this->debug === TRUE)
			{
                Logger::log(__FILE__,__LINE__,'ftp_unable_to_upload') ;
			}

			return FALSE;
		}

		// Set file permissions if needed
		if ($permissions !== NULL)
		{
			$this->chmod($rempath, (int) $permissions);
		}

		return TRUE;
	}
}


$deployer = new Deployer() ;
/**
     * deploy
     * @param string $locpath   Path to source with trailing slash
     * @param string $rempath   Path to destination - include the base folder with trailing slash
     */
$deployer->deploy('../../protected/template/','protected/template/') ;
$deployer->deploy('../../protected/content/','protected/content/') ;
$deployer->deploy('../../public_html/','public_html/') ;
