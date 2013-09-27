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
	const ERROR_READ_FILE = 'Can not open or read the file, missing filename!';
	const ERROR_METHOD_READ_FILE = 'No method to read file content define!';
	
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
	 * 
	 * @param unknown $path
	 * @param unknown $file
	 * @return unknown|boolean
	 */
	public function fetchFileContent($path = null,$file = null)
	{
		if (!$path){
			if (method_exists($this->getEntity(), 'getCurrentPath')){
				$path = $this->getEntity()->getCurrentPath();
			}
			if (!$path){
				throw new InvalidValueStorageException('No directory path given');
			} 
		}
		
		if (!$file){
			if (null == ($file = $this->getFile())){
				throw new InvalidValueStorageException('No file name given');
			}
		}
		
		if (false != ($content = @file_get_contents($this->getStorage()->setPath($path)->setCurrent($file)->getAdapter ()))){
			return $content;
		}
		return false;
	}
}