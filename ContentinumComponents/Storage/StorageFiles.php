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

use ContentinumComponents\Storage\Exeption\InvalidValueStorageException;
/**
 * File manager(s)
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
class StorageFiles extends AbstractStorage
{
	const ERROR_FILE_EXIST = 'The file does not exist or it was a false name or place specified';
	const ERROR_READ_FILE = 'Can not open and read the file';
	const ERROR_WRITE_FILE = 'Can not open and write the file';
	const ERROR_METHOD_READ_FILE = 'No method to read file content define';
	const ERROR_DELETE_FILE = 'Can not delete this file';
	const ERROR_COPY_FILE = 'File has been deleted';
	const SUCCESS_COPY_FILE = 'File was copied';
	const SUCCESS_READ_FILE = 'File content is displayed';
	const SUCCESS_WRITE_FILE = 'File content has changed';
	const SUCCESS_EMPTY_FILE = 'File has been emptied';
	const SUCCESS_DELETE_FILE = 'File has been deleted';

	
	/**
	 * Filename
	 *
	 * @var unknown_type
	 */
	protected $_file;
	
	/**
	 * Get filename
	 *
	 * @return the $_file
	 */
	public function getFile ()
	{
		return $this->_file;
	}
	
	/**
	 * Set Filename
	 *
	 * @param string $_file filename or filename and path to file
	 * @return Contentinum_Model_File
	 */
	public function setFile ($file)
	{
		$this->_file = $file;
		return $this;
	}	
	
	/**
	 * Warpper method for file_get_contents
	 * @param string $path
	 * @param string $file
	 * @throws InvalidValueStorageException
	 * @return unknown|boolean
	 */
	public function fetchFileContent($path = null,$file = null)
	{
		$path = $this->issetPath($path);
		$file = $this->issetFile($file);
		$dir = $this->getStorage()->setPath($path)->setCurrent($file)->getAdapter ();
		
		if ( ! is_file($dir) ){
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_FILE_EXIST);
			}
			throw new InvalidValueStorageException(self::ERROR_FILE_EXIST);
		}
		
		if (false !== ($content = @file_get_contents($dir))){
			if (true == ($log = $this->getLogger())){
				$log->info(self::SUCCESS_READ_FILE . ': '. $dir);
			}			
			return $content;
		} else {
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_READ_FILE);
			}			
			throw new InvalidValueStorageException(self::ERROR_READ_FILE);
		}
	}
	
	/**
	 * Warpper method for file_put_contents
	 * @param string $data file content data
	 * @param string $path
	 * @param string $file
	 * @throws InvalidValueStorageException
	 * @return boolean
	 */
	public function setFileContent($data, $path = null,$file = null)
	{
		$path = $this->issetPath($path);
		$file = $this->issetFile($file);
		$dir = $this->getStorage()->setPath($path)->setCurrent($file)->getAdapter ();
		
		if ( ! is_file($dir) ){
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_FILE_EXIST . ': ' . $dir);
			}			
			throw new InvalidValueStorageException(self::ERROR_FILE_EXIST);
		}
		
		if ( false === @file_put_contents($dir, $data) ){
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_WRITE_FILE . ': ' . $dir);
			}
			throw new InvalidValueStorageException(self::ERROR_READ_FILE);
		} else {
			if (true == ($log = $this->getLogger())){
				$log->info(self::SUCCESS_WRITE_FILE . ': '. $dir);
			}			
			return self::SUCCESS_WRITE_FILE;
		}
		
	}
	
	/**
	 * Copy a file
	 * @param string $dest destination path and file
	 * @param string $path source path
	 * @param string $file source file
	 * @throws InvalidValueStorageException
	 * @return boolean
	 */
	public function copyFile($dest, $path = null,$file = null)
	{
		$path = $this->issetPath($path);
		$file = $this->issetFile($file);
		$dir = $this->getStorage()->setPath($path)->setCurrent($file)->getAdapter ();		

		if ( ! is_file($dir) ){
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_FILE_EXIST);
			}
			throw new InvalidValueStorageException(self::ERROR_FILE_EXIST);
		}

		if (! copy($dir, $dest)){
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_COPY_FILE .': '. $dir . ' to ' . $dest );
			}
			throw new InvalidValueStorageException(self::ERROR_READ_FILE);			
		} else {
			if (true == ($log = $this->getLogger())){
				$log->info(self::SUCCESS_COPY_FILE . ': '. $dir . ' to ' . $dest);
			}			
			return self::SUCCESS_COPY_FILE;
		}
	}
	
	/**
	 * Warpper method for unlink
	 * @param string $data file content data
	 * @param string $path
	 * @param string $file
	 * @throws InvalidValueStorageException
	 * @return boolean
	 */
	public function deleteFile($path = null,$file = null)
	{
		$path = $this->issetPath($path);
		$file = $this->issetFile($file);
		$dir = $this->getStorage()->setPath($path)->setCurrent($file)->getAdapter ();
	
		if ( ! is_file($dir) ){
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_FILE_EXIST);
			}
			throw new InvalidValueStorageException(self::ERROR_FILE_EXIST);
		}
		
		try {
		    $this->getStorage()->unlinkFile($dir);
		    if (true === ($log = $this->getLogger())){
		    	$log->info(self::SUCCESS_DELETE_FILE . ': ' . $dir);
		    }
		    return self::SUCCESS_DELETE_FILE;		    
		} catch (\Exception $e){
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_DELETE_FILE . ': ' . $dir );
			}
			throw new InvalidValueStorageException(self::ERROR_READ_FILE);
		} 
	
	}	
	
	/**
	 * Check if a path has been specified, or if it can be determined
	 * @param string $path
	 * @throws InvalidValueStorageException
	 * @return string
	 */
	protected function issetPath($path = null)
	{
		if (!$path){
			if (method_exists($this->getEntity(), 'getCurrentPath')){
				$path = $this->getEntity()->getCurrentPath();
			}
			if (!$path){
				throw new InvalidValueStorageException('No directory path given');
			}
		}
		return $path;		
	}
	
	/**
	 * check if a file name has been specified, or if it can be determined
	 * @param string $file
	 * @throws InvalidValueStorageException
	 * @return string
	 */
	protected function issetFile($file = null)
	{
		if (!$file){
			if (null == ($file = $this->getFile())){
				throw new InvalidValueStorageException('No file name given');
			}
		}
		return $file;		
	}
	
}