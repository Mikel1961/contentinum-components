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
	
	const METHOD_COPY = 'copy';
	const METHOD_MOVE = 'move';
	const METHOD_RENAME = 'rename';
	const METHOD_DELETE = 'delete';
	const METHOD_UNZIP = 'unzip';
	
	const RENAME_FILE = 'Failed to rename the file or directory';
	const RENAME_FILE_EXISTS = 'A file or directory with this name already exists';
	const UNLINK_FILE = 'Delete file failed';
	const COPY_FILE_ERROR = 'Error during copy file';
	const CREATE_DIR_LOOP = 'Infinite loop detected';
	const CREATE_DIR_EXISTS = 'Folder already exists';
	const CREATE_DIR_BASEDIR = 'Path not in open_basedir paths';
	const CREATE_DIR_ERROR = 'Could not create directory';
	const RM_DIR_ERROR = 'Could not remove directory';
	const ZIP_MODUL_ERROR = 'Zip PHP module is not installed on this server';
	const ZIP_CREATE_ERROR = 'Archive could not be created';
	const UNZIP_FILE_ERROR = 'Unable to unzip this file';
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
	 * Store any copy, move or delete
	 * @var array
	 */
	protected $logAction = array();
	
	/**
	 * Don't list this directories
	 * @var array
	 */
	protected $disabledDirectories = array('.', '..', '_alternate');

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
	 * @return the $logAction
	 */
	public function getLogAction($method = null) 
	{
		if (null === $method){
	       return $this->logAction;
		}
		
		if (isset($this->logAction[$method])){
		    return $this->logAction[$method];
		}
		return null;
	}
	
	/**
	 * @param multitype: $logAction
	 */
	public function addLogAction($method, $logAction)
	{
		$this->logAction[$method][] = $logAction;
	}	

	/**
	 * @param multitype: $logAction
	 */
	public function setLogAction($logAction) 
	{
		$this->logAction = $logAction;
	}

	/**
	 * @return the $disabledDirectories
	 */
	public function getDisabledDirectories() 
	{
		return $this->disabledDirectories;
	}

	/**
	 * @param multitype: $disabledDirectories
	 */
	public function setDisabledDirectories($disabledDirectories) 
	{
		$this->disabledDirectories = $disabledDirectories;
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
	public function delete ($file)
	{
		$file = $this->clean($file);
		// In case of restricted permissions we zap it one way or the other
		// as long as the owner is either the webserver or the ftp
		if (@unlink($file)) {
		    $this->addLogAction(self::METHOD_DELETE, array('source' =>  $file));
			return true;
		} else {
			throw new ErrorLogicStorageException(self::UNLINK_FILE);
		}
	}

	/**
	 * Rename file or folder
	 * @param string $source file or folder name
	 * @param string $destination new file or folder name
	 * @throws ErrorLogicStorageException
	 * @return boolean
	 */
    public function rename($source, $destination, $method = self::METHOD_RENAME)
    {
        if (! file_exists($destination)) {
            if (@rename($source, $destination)) {
                $this->addLogAction($method, array('source' => $source, 'dest' => $destination));
                return true;
            } else {
                throw new ErrorLogicStorageException(self::RENAME_FILE);
            }
        } else {
            throw new ErrorLogicStorageException(self::RENAME_FILE_EXISTS);
        }
    }	
    
    /**
     * 
     * @param unknown $files
     * @param unknown $source
     * @param unknown $destination
     */
    public function move($files, $source, $destination){
    
    	// batch move
    	foreach($files as $file){
    	    if (isset($file['value'])){
    	        $file = $file['value'];
    	    }
    		if (!file_exists($destination.DS.$file)){
    			if(strpos($destination.DS.$file, $source.DS.$file.DS) !== false){
    				continue;
    			}
    			$this->rename($source.DS.$file, $destination.DS.$file, self::METHOD_MOVE);

    		}
    	}
    	return;
    }    
	
	/**
	 * Remove files and directories recursively
	 * @param string $directory
	 * @param boolen $empty
	 * @throws ErrorLogicStorageException
	 * @return boolean
	 */
	public function remove($directory, $empty = false)
	{
		if (substr($directory, - 1) == DS) {
			$directory = substr($directory, 0, - 1);
		}
		if (! file_exists($directory) || ! is_dir($directory)) {
			throw new ErrorLogicStorageException(self::RM_DIR_ERROR);
		} elseif (is_readable($directory)) {
			$handle = opendir($directory);
			while (false !== ($item = readdir($handle))) {
				if ($item != '.' && $item != '..') {
					$path = $directory . DS . $item;
					if (is_dir($path)) {
						$this->remove($path);
					} else {
						$this->delete($path);
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
	public function copy($source, $dest){
	
		// Simple copy for a file
		if (is_file($source)) {
		    if (@copy($source, $dest)){
		        $this->addLogAction(self::METHOD_COPY, array('source' => $source, 'dest' => $dest));
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
			$this->copy($source.DS.$entry, $dest.DS.$entry);
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
	 * Build and save a zip archive
	 * @param array $items array with name of files and/or directories
	 * @param string $destination destination path and archive file name
	 * @param string $paht path to save the archive file
	 * @throws ErrorLogicStorageException
	 */
    public function zip($items, $destination, $path)
    {
        if (! extension_loaded('zip')) {
            throw new ErrorLogicStorageException(self::ZIP_MODUL_ERROR);
        }
        
        if (substr($destination, - 4, 4) != '.zip') {
            $destination = $destination . '.zip';
        }
        
        $zip = new \ZipArchive();
        
        if (! $zip->open($destination, \ZipArchive::CREATE)) {
            throw new ErrorLogicStorageException(self::ZIP_CREATE_ERROR);
        }
        
        $startdir = str_replace('\\', '/', $path);
        
        foreach ($items as $source) {
            if (isset($source['value'])){
                $source = $source['value'];
            }
            
            $source = $path . DS . $source;
            
            $source = str_replace('\\', '/', $source);
            
            if (is_dir($source) === true) {
                $subdir = str_replace($startdir . '/', '', $source) . '/';
                $zip->addEmptyDir($subdir);
                
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);
                
                foreach ($files as $file) {
                    
                    $file = str_replace('\\', '/', $file);
                    
                    // Ignore "." and ".." folders
                    if (in_array(substr($file, strrpos($file, '/') + 1), array(
                        '.',
                        '..'
                    ))) {
                        continue;
                    }
                    if (is_dir($file) === true) {
                        $zip->addEmptyDir($subdir . str_replace($source . '/', '', $file . '/'));
                    } else 
                        if (is_file($file) === true) {
                            $zip->addFile($file, $subdir . str_replace($source . '/', '', $file));
                        }
                }
            } else 
                if (is_file($source) === true) {
                    $zip->addFile($source, basename($source));
                }
        }

        $zip->close();
        
        return;
    }
    
    /**
     * Unzip archive
     * @param string $archive name of zip archive
     * @param string $path directory path to zip archive
     * @throws ErrorLogicStorageException
     */
    public function unzip($archive, $path)
    {
        if (! extension_loaded('zip')) {
            throw new ErrorLogicStorageException(self::ZIP_MODUL_ERROR);
        }
        
        $archive = $path . DS . $archive;
        
        $zip = new \ZipArchive();
        if ($zip->open($archive) === true) {
            
            $entries = array();
            for ($idx = 0; $idx < $zip->numFiles; $idx ++) {
                $zname = $zip->getNameIndex($idx);
                if ($zname == pathinfo($archive, PATHINFO_BASENAME)){
                    continue;
                }
                $entries[] = $zname;
            }
            
            $zip->extractTo($path . DS, $entries);
            $this->addLogAction(self::METHOD_UNZIP  , array('path' => $path, 'entries' => $entries));
            $zip->close();
        } else {
            throw new ErrorLogicStorageException(self::UNZIP_FILE_ERROR);
        }
        
        return;
    }

    /**
     *
     * @param unknown $path
     * @param string $skip_files
     * @param string $link_prefix
     * @return string
     */
    public function getDirectoryTree($path, $skip_files = false, $link_prefix = '')
    {
        $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
        
        $dom = new \DomDocument("1.0");
        $list = $dom->createElement("ul");
        $dom->appendChild($list);
        $node = $list;
        
        $depth = 0;
        $homepath = $path;
        $reps = $this->getDocumentRoot();
        
        foreach ($objects as $name => $object) {
            
            $name = $object->getFilename();
            if ( ! in_array($name, $this->disabledDirectories)) {
                
                $type = $object->getType();
                $skip = false;
                
                if (false === $skip_files) {
                    $skip = false;
                }
                
                if (true === $skip_files) {
                    if ('dir' === $type) {
                        $skip = false;
                    } else {
                        $skip = true;
                    }
                }
                
                if (false === $skip) {
                    
                    $path = str_replace('\\', '/', $object->getPathname());
                    $isDir = is_dir($path);
                    $link = str_replace($reps, '', $path);
                    
                    if ($objects->getDepth() == $depth) {
                        // the depth hasnt changed so just add another li
                        $li = $dom->createElement('li');
                        $la = $dom->createElement('a', $object->getFilename());
                        $href = $dom->createAttribute('href');
                        $href->value = '#';
                        $class = $dom->createAttribute('class');
                        $class->value = 'setlink';
                        $dl = $dom->createAttribute('data-link');
                        $dl->value = $link;
                        $la->appendChild($href);
                        $la->appendChild($class);
                        $la->appendChild($dl);
                        $li->appendChild($la);
                        $node->appendChild($li);
                    } elseif ($objects->getDepth() > $depth) {
                        // the depth increased, the last li is a non-empty folder
                        $li = $node->lastChild;
                        $ul = $dom->createElement('ul');
                        $li->appendChild($ul);
                        $liul = $dom->createElement('li');
                        $liula = $dom->createElement('a', $object->getFilename());
                        $href = $dom->createAttribute('href');
                        $href->value = '#';
                        $class = $dom->createAttribute('class');
                        $class->value = 'setlink';
                        $dl = $dom->createAttribute('data-link');
                        $dl->value = $link;
                        $liula->appendChild($href);
                        $liula->appendChild($class);
                        $liula->appendChild($dl);
                        $liul->appendChild($liula);
                        $ul->appendChild($liul);
                        $node = $ul;
                    } else {
                        // the depth decreased, going up $difference directories
                        $difference = $depth - $objects->getDepth();
                        for ($i = 0; $i < $difference; $difference --) {
                            $node = $node->parentNode->parentNode;
                        }
                        $li = $dom->createElement('li');
                        $la = $dom->createElement('a', $object->getFilename());
                        $href = $dom->createAttribute('href');
                        $href->value = '#';
                        $class = $dom->createAttribute('class');
                        $class->value = 'setlink';
                        $dl = $dom->createAttribute('data-link');
                        $dl->value = $link;
                        $la->appendChild($href);
                        $la->appendChild($class);
                        $la->appendChild($dl);
                        $li->appendChild($la);
                        $node->appendChild($li);
                    }
                    $depth = $objects->getDepth();
                }
            }
        }
        
        $html = '<ul><li><a class="setlink" data-link="' . str_replace($reps, '', $homepath) . '" href="#">home</a>';
        $html .= $dom->saveHtml() . '</li></ul>';
        return $html;
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
					$count = new \RecursiveDirectoryIterator($element->getPathname());
					$row['childs'] = $count->hasChildren();
					$row['counts'] = $this->countItems($count);
					
				} else {
					$row['items'] = '';
				}
				$row['path'] = $element->getPath();
				$row['pathname'] = $element->getPathname();
				$row['type'] = $element->getType();
				$row['mimetype'] = mime_content_type($element->getPathname());
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
	 * Properties
	 * @param string $path file path to resource
	 * @param string $item file or dir item
	 * @return Ambigous <mixed, multitype:unknown string number boolean >
	 */
	public function getProperties($path, $item)
	{
	    $prop = array();
	    $path = $path . DS . $item;
	    if (file_exists($path)  &&  is_file($path)){
	        $prop = pathinfo($path);
	        $prop['mimetype'] = mime_content_type($path);
	        $prop['type'] = filetype($path);
	        $prop['size'] = filesize($path);
	        $prop['time'] = filemtime($path);
	        $prop['perms'] = fileperms($path);
	        $prop['readable'] = is_readable($path);
	        $prop['writable'] = is_writable($path);
	        $prop['executable'] = is_executable($path);	
	        if (true === $this->isImages($prop['extension'])) {
	        	list ($width, $height, $type, $attr) = @getimagesize($path);
	        	$prop['width'] = $width;
	        	$prop['height'] = $height;
	        }	             
	    }
	    return $prop;
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

	/**
	 * RecursiveDirectoryIterator count items
	 * @param RecursiveDirectoryIterator $recursiveDirectoryIterator
	 */
    protected function countItems($recursiveDirectoryIterator)
    {
        $counts = array();
        $counts['all'] = 0;
        $counts['directorys'] = 0;  
        $counts['links'] = 0;
        $counts['files'] = 0;
        $counts['size'] = 0;
        
        foreach ($recursiveDirectoryIterator as $element) 
        { 
            /* @var $element SplFileInfo */
            switch ($element->getType())
            {
                case 'file':
                    $counts['files'] ++;
                    $counts['size'] += $element->getSize();
                    break;
                case 'link':           
                    $counts['links'] ++;
                    break;
                case 'dir':   
                    if ( ! in_array($element->getFilename() , $this->disabledDirectories) ){
                        $counts['directorys']++;
                    }
                    break;
            }
            if ( ! in_array($element->getFilename() , $this->disabledDirectories) ){
                $counts['all'] ++;
            }
        }
        
        return $counts;
    }
	
}