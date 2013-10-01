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
 * Abstract class file and directory manager(s)
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
abstract class AbstractStorage extends AbstractManager
{
	
	const ERROR_STORAGE_PATH = 'No directory path given';
	const ERROR_STORAGE_MANAGER = 'There is no storage manager initiated';
	const ERROR_FILE = 'No file name given';
	/**
	 * Logger
	 * @var object
	 */
	protected $logger = false;	
	
	/**
	 * Set a logger
	 * @param object $logger
	 */
	public function setLogger($logger)
	{
		$this->logger = $logger;
	}
	
	/**
	 * Returns Zend logger
	 * @return object
	 */
	public function getLogger()
	{
		return $this->logger;
	}

	/**
	 * Check if logger available
	 * @return boolean
	 */
	public function hasLogger()
	{
		if (false === $this->logger){
			return false;
		} else {
			return true;
		}
	}	
	
	/**
	 * @see \ContentinumComponents\Storage\AbstractManager::getEntity()
	 */
	public function getEntity() 
	{	
		return $this->entity;
	}

	/**
	 * @see \ContentinumComponents\Storage\AbstractManager::getEntityName()
	 */
	public function getEntityName() 
	{
		return $this->entityName;
	}


	/**
	 * 
	 * @see \ContentinumComponents\Storage\AbstractManager::getStorage()
	 * @return StorageManager
	 */
	public function getStorage($storage = null, $charset = 'UTF8') 
	{
		if ($storage) {
			$this->setStorage ( $storage, $charset );
		}
		
		if (! $this->storage instanceof StorageManager ) {
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_STORAGE_MANAGER);
			}			
			throw new InvalidValueStorageException( self::ERROR_STORAGE_MANAGER );
		}
		
		return $this->storage;
		
	}

	/**
	 * @see \ContentinumComponents\Storage\AbstractManager::setEntity()
	 */
	public function setEntity($entity) 
	{
		$this->entity = $entity;
		return $this;
	}

	/**
	 * @see \ContentinumComponents\Storage\AbstractManager::setEntityName()
	 */
	public function setEntityName($name = null) 
	{
		if (!$name && $this->entity){
			$name = $this->entity->getEntityName();
		}
		$this->entityName = $name;
		return $this;
	}

	/**
	 * @see \ContentinumComponents\Storage\AbstractManager::setStorage()
	 */
	public function setStorage($storage, $charset = 'UTF8') 
	{
	    $this->storage = $storage;
		
		if (! $this->storage instanceof StorageManager) {
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_STORAGE_MANAGER);
			}			
			throw new InvalidValueStorageException ( self::ERROR_STORAGE_MANAGER );
		}
		
		return $this;
	}
	
}