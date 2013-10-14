<?php

/**
 * contentinum - accessibility websites
 *
 * LICENSE
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category contentinum components
 * @package Storage
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Storage;

use ContentinumComponents\Storage\Exeption\ErrorLogicStorageException;
/**
 * Abstract class file and directory manager(s)
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
class StorageManager 
{
	const CONFIG_BASE_PATH = 'base';
	const CONFIG_DIRECTORY_PATH = 'path';
	const CONFIG_FTP = 'ftp';
	
	const UNLINK_FILE = 'Delete file failed';
	const COPY_FILE_ERROR = 'Error during copy file';
	const CREATE_DIR_LOOP = 'Infinite loop detected';
	const CREATE_DIR_EXISTS = 'Folder already exists';
	const CREATE_DIR_BASEDIR = 'Path not in open_basedir paths';
	const CREATE_DIR_ERROR = 'Could not create directory';
	const RM_DIR_ERROR = 'Could not remove directory';
	const FETCH_ALL_DIR_ERR = 'Directory not exists or not found';
	
	/**
	 * Images extension
	 *
	 * @var array
	 */
	protected $images = array (
			'JPG',
			'JEPG',
			'jpg',
			'jepg',
			'png',
			'gif' 
	);
	/**
	 * Enable ftp
	 *
	 * @var boolen
	 */
	protected $ftp = false;
	protected $server = null;
	protected $username = null;
	protected $password = null;
	
	/**
	 * Document root path
	 *
	 * @var string
	 */
	protected $documentRoot = null;
	
	/**
	 * Path to files resources
	 *
	 * @var string
	 */
	protected $path = null;
	
	/**
	 * Adjustment default key
	 *
	 * @var string
	 */
	protected $default = null;
	
	/**
	 * Current path
	 *
	 * @var string
	 */
	protected $current = null;
	
	/**
	 * Optionally force folder/file overwrites
	 *
	 * @var boolen
	 */
	protected $force = false;
	/**
	 * Directory permissions mode
	 *
	 * @var int
	 */
	protected $mode = 0755;
	/**
	 * Repository name (entity class name for directories)
	 * @var string
	 */
	protected $repositoryName = '';

	/**
	 * Construct
	 *
	 * @param array $config
	 */
	public function __construct ($config = array())
	{
		if (is_array($config)) {
			$this->setOptions($config);
		} else {
			$this->_setup();
		}
	}
	
	/**
	 * Set configuration
	 *
	 * @param array $config
	 */
	public function setOptions (array $config)
	{
		foreach ($config as $key => $value) {
			switch ($key) {
				case self::CONFIG_BASE_PATH:
					$this->setDocumentRoot($value);
					break;
				case self::CONFIG_DIRECTORY_PATH:
					$this->setPath($value);
					break;
				default:
					// ignore unrecognized configuration
					break;
			}
		}
	}

	/**
	 * @return the $_force
	 */
	public function getForce()
	{
		return $this->force;
	}
	
	/**
	 * @param boolen $force
	 * @return Contentinum_Model_Directory_Abstract
	 */
	public function setForce($force)
	{
		$this->force = $force;
		return $this;
	}
	
	/**
	 * @return the $mode
	 */
	public function getMode()
	{
		return $this->mode;
	}
	
	/**
	 * @param number $mode
	 * @return Contentinum_Model_Directory_Abstract
	 */
	public function setMode($mode)
	{
		$this->mode = $mode;
		return $this;
	}
	
	/**
	 *
	 * @param string $documentRoot
	 * @return Contentinum_Model_Directory_Abstract
	 */
	public function setDocumentRoot ($documentRoot)
	{
		$this->documentRoot = $documentRoot;
		return $this;
	}
	
	/**
	 *
	 * @return the $_documentRoot
	 */
	public function getDocumentRoot ()
	{
		if (null === $this->documentRoot) {
			$this->setDocumentRoot(CON_ROOT_PATH);
		}
		return $this->documentRoot;
	}
	
	/**
	 *
	 * @param string $_path
	 * @return Contentinum_Model_Directory_Abstract
	 */
	public function setPath ($path)
	{
		$this->path = $path;
		return $this;
	}
	
	/**
	 *
	 * @return the $_path
	 */
	public function getPath ()
	{
		return $this->path;
	}
	
	/**
	 *
	 * @return the $current
	 */
	public function getCurrent ()
	{
		if (null == $this->current) {
			return '';
		}
		return '/' . $this->current;
	}
	
	/**
	 *
	 * @param string $current
	 * @return Contentinum_Model_Directory_Abstract
	 */
	public function setCurrent ($current)
	{
		$this->current = (string) $current;
		return $this;
	}
	
	/**
	 * Enter description here .
	 *
	 *
	 * ..
	 */
	public function getAdapter ()
	{
		$adapter = null;
		if (false === $this->ftp) {
			$adapter = $this->getDocumentRoot() . $this->getPath() . $this->getCurrent();
		}
	
		return $adapter;
	}
	
	/**
	 * Return adjustment default key
	 *
	 * @return string
	 */
	public function getDefaultKey ()
	{
		return $this->default;
	}	
	
	/**
	 * Function to strip additional / or \ in a path name
	 *
	 * @param string $path to clean
	 * @param string $ds (optional)
	 * @return string cleaned path
	 */
	public function clean ($path, $cfg = null)
	{
		if (! $cfg) {
			$cfg = DS;
		}
		$path = trim($path);
		if (empty($path)) {
			$path = $this->getDocumentRoot();
		} else {
			// Remove double slashes and backslahses and convert all slashes and
			// backslashes to DS
			$path = preg_replace('#[/\\\\]+#', $cfg, $path);
		}
		return $path;
	}

	/**
	 * Delete a file
	 *
	 * @param mixed $file The file name or an array of file names
	 * @return boolean True on success
	 */
	public function unlinkFile ($file)
	{
		$file = $this->clean($file);
		// In case of restricted permissions we zap it one way or the other
		// as long as the owner is either the webserver or the ftp
		if (@unlink($file)) {
			return true;
		} else {
			throw new ErrorLogicStorageException(self::UNLINK_FILE);
		}
	}
	
	/**
	 * Remove files and directories recursively
	 * @param string $directory
	 * @param boolen $empty
	 * @throws ErrorLogicStorageException
	 * @return boolean
	 */
	public function rmDirectory($directory, $empty = false)
	{
		if (substr($directory, - 1) == DS) {
			$directory = substr($directory, 0, - 1);
		}
		if (! file_exists($directory) || ! is_dir($directory)) {
			return false;
		} elseif (is_readable($directory)) {
			$handle = opendir($directory);
			while (false !== ($item = readdir($handle))) {
				if ($item != '.' && $item != '..') {
					$path = $directory . DS . $item;
					if (is_dir($path)) {
						$this->rmDirectory($path);
					} else {
						$this->unlinkFile($path);
					}
				}
			}
			closedir($handle);
			if ($empty == false) {
				if (! rmdir($directory)) {
					throw new ErrorLogicStorageException(self::RM_DIR_ERROR);
				}
			}
		}
		return true;
	}	
	
	/**
	 * Copy files recursively
	 * @param string $source source path or file
	 * @param string $dest destination path with/or file
	 * @throws ErrorLogicStorageException
	 * @return boolean
	 */
	public function copyRecursive($source, $dest){
	
		// Simple copy for a file
		if (is_file($source)) {
		    if (@copy($source, $dest)){
		        return true;
		    } else {
		        throw new ErrorLogicStorageException(self::COPY_FILE_ERROR);
		    }
		}
	
		// Make destination directory
		if (!is_dir($dest)) {
			$this->create($dest);
		}
	
		// Loop through the folder
		$dir = dir($source);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}
	
			// Deep copy directories
			$this->copyRecursive($source.DS.$entry, $dest.DS.$entry);
		}
	
		// Clean up
		$dir->close();
	
		return true;
	}	
	
	/**
	 * Create a folder -- and all necessary parent folders
	 *
	 * @return boolean True if successful
	 */
	public function create ($path)
	{
		// Initialize variables
		$options = 0;
		static $nested = 0;
		// Check to make sure the path valid and clean
		$path = $this->clean($path);
		// Check if parent dir exists
		$parent = dirname($path);
		if (! is_dir($parent)) {
			// Prevent infinite loops!
			$nested ++;
			if (($nested > 20) || ($parent == $path)) {
				$nested --;
				throw new ErrorLogicStorageException(self::CREATE_DIR_LOOP);
			}
			try {
				self::create($parent);
			} catch (\Exception $e) {
				$msg = $e->getMessage();
				$nested --;
				throw new ErrorLogicStorageException($msg);
			}
			// OK, parent directory has been created
			$nested --;
		}
		// Check if dir already exists
		if (is_dir($path)) {
			throw new ErrorLogicStorageException(self::CREATE_DIR_EXISTS);
		}
		// Check for safe mode
		if (true === $this->ftp) { // Connect the FTP client
		} else {
			// We need to get and explode the open_basedir paths
			$obd = ini_get('open_basedir');
			// If open_basedir is set we need to get the open_basedir that the
			// path is in
			if ($obd != null) {
				if (CON_PATH_ISWIN) {
					$obdSeparator = ";";
				} else {
					$obdSeparator = ":";
				}
				// Create the array of open_basedir paths
				$obdArray = explode($obdSeparator, $obd);
				$inOBD = false;
				$obdpath = null;
				// Iterate through open_basedir paths looking for a match
				foreach ($obdArray as $test) {
					$test = $this->clean($test);
					if (strpos($path, $test) === 0) {
						$obdpath = $test;
						$inOBD = true;
						break;
					}
				}
				if ($inOBD == false) {
					// throw exception because the path to be created is not in
					// open_basedir
					throw new ErrorLogicStorageException(self::CREATE_DIR_BASEDIR);
				}
			}
			// First set umask
			$origmask = @ umask(0);
			// Create the path
			if (! $ret = @mkdir($path, $this->mode)) {
				@ umask($origmask);
				throw new ErrorLogicStorageException(self::CREATE_DIR_ERROR);
			}
			// Reset umask
			@ umask($origmask);
		}
		return $ret;
	}

	/**
	 * Get directory content
	 *
	 * @param string $dir
	 * @throws Contentinum_Model_Exception
	 */
	public function fetchAll ()
	{
		$path = $this->getDocumentRoot() . $this->path;
		if (false !== ($dir = $this->getCurrent())) {
			$path = $path . '' . $dir;
		}

		if (is_dir($path)) {
			$result = $row = array();
			$iterator = new \DirectoryIterator($path);
			foreach ($iterator as $element) {
				$row['filename'] = $key = $element->getFilename();
				$row['extension'] = $this->getExtension($key);
				if (true === $this->isImages($row['extension'])) {
					list ($width, $height, $type, $attr) = @getimagesize($element->getPathname());
					$row['width'] = $width;
					$row['height'] = $height;
				} else {
					$row['width'] = '';
					$row['height'] = '';
				}
				if ($element->isDir() && '.' != $element->getFilename() && '..' != $element->getFilename()) {
					$row['items'] = '1';
				} else {
					$row['items'] = '';
				}
				$row['path'] = $element->getPath();
				$row['pathname'] = $element->getPathname();
				$row['type'] = $element->getType();
				$row['size'] = $element->getSize();
				$row['time'] = $element->getMTime();
				$row['perms'] = $element->getPerms();
				$row['readable'] = $element->isReadable();
				$row['writable'] = $element->isWritable();
				$row['executable'] = $element->isExecutable();
				$result[$key] = $row;
				$row = array();
			}
			unset($iterator);
			asort($result);
			return $result;
		} else {
			throw new ErrorLogicStorageException(self::FETCH_ALL_DIR_ERR);
		}
	}
	
	/**
	 * Gets the extension of a file name
	 *
	 * @param string $file The file name
	 * @return string The file extension
	 */
	public function getExtension ($fileName)
	{
		return strtolower(substr($fileName, strrpos($fileName, '.') + 1));
	}
	
	/**
	 * Check is it image
	 *
	 * @param string $extension fiel extension
	 * @return boolen
	 */
	public function isImages ($extension)
	{
		if (in_array($extension, $this->images)) {
			return true;
		}
		return false;
	}	
	
	/**
	 * Set repository name and set current directory
	 * @param string $repositoryName
	 * @return \ContentinumComponents\Storage\StorageManager
	 */
	public function getRepository($repositoryName)
	{
		$this->repositoryName = $repositoryName;
		$entity = new $repositoryName();
		$this->setCurrent($entity->getCurrentPath());
		return $this;
	}
	
	/**
	 * Find all content (directories and files) in a specified directory
	 * @return multitype:unknown
	 */
	public function findAll()
	{
		$result = $this->fetchAll();
		$entries = array();
		$entityName = $this->repositoryName;
		foreach ($result as $row) {
			$entry = new $entityName();
			$entry->setOptions($row);
			$entries[] = $entry;
		}
		return $entries;		
		
	}
	
}