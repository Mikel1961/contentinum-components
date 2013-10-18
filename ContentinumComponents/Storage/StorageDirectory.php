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

use ContentinumComponents\Storage\AbstractStorage;
use ContentinumComponents\Storage\AbstractStorageEntity;
use ContentinumComponents\Storage\Exeption\ErrorLogicStorageException;
use ContentinumComponents\Storage\Exeption\InvalidValueStorageException;

/**
 * Directory manager(s)
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de  
 */
class StorageDirectory extends AbstractStorage
{
    const ERROR_STORAGE_ENTITY = 'Incorrect or missing entity to continue working';
    const DIR_ADD_SUCCESS = 'add_dir_success';
    const DIR_ADD_ERROR = 'add_dir_error';
    const DIR_RM_SUCCESS = 'rm_dir_success';
    const DIR_RM_ERROR = 'rm_dir_error';
    const DIR_COPY_SUCCESS = 'cp_dir_success';
    const DIR_COPY_ERROR = 'cp_dir_error';
    const DIR_RENAME_SUCCESS = 'rename_dir_success';
    const DIR_RENAME_ERROR = 'rename_dir_error';
    const DIR_MOVE_SUCCESS = 'move_dir_success';
    const DIR_MOVE_ERROR = 'move_dir_error';
    const DIR_ZIP_SUCCESS = 'zip_dir_success';
    const DIR_ZIP_ERROR = 'zip_dir_error';    

    /**
     * Fetch all content from this directory
     * @param AbstractStorageEntity $entity
     * @param string $cd current folder
     * @throws InvalidValueStorageException
     * @return multitype:array:AbstractStorageEntity
     */
    public function fetchAll(AbstractStorageEntity $entity = null, $cd = null)
    {
        if (null === $entity) {
            $entity = $this->getEntity();
        } 
        
        if (! $entity instanceof AbstractStorageEntity) {
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_STORAGE_ENTITY);
			}			
			throw new InvalidValueStorageException( self::ERROR_STORAGE_ENTITY );
        }
        
        $entityName = $entity->getEntityName();
        
        $resultSet = $this->getStorage()
            ->setCurrent($entity->getCurrentPath() . DS . $cd)
            ->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new $entityName();
            $entry->setOptions($row);
            $entries[] = $entry;
        }
        return $entries;
    }
   
    /**
     * Create a new directory
     * @param string $directory name of the new drecitory
     * @param AbstractStorageEntity $entity
     * @param string $cd current folder within to create the new directory
     * @throws InvalidValueStorageException
     * @throws ErrorLogicStorageException
     * @return string
     */
    public function makeDirectory($directory, AbstractStorageEntity $entity = null, $cd = null)
    {
        if (null === $entity) {
            $entity = $this->getEntity();
        } 
        
        if (! $entity instanceof AbstractStorageEntity) {
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_STORAGE_ENTITY);
			}			
			throw new InvalidValueStorageException( self::ERROR_STORAGE_ENTITY );
        }
        
        $path = $this->getStorage()->getDocumentRoot();
        $path .= DS . $entity->getCurrentPath();
        if ($cd) {
            $path .= DS . $cd;
        }
        try {
            $this->getStorage()->create($path . DS . $directory);
            if (true == ($log = $this->getLogger())) {
                $log->info(self::DIR_ADD_SUCCESS . ' ' . $directory . ' in ' . $path);
            }
            return self::DIR_ADD_SUCCESS;
        } catch (\Exception $e) {
            if (true == ($log = $this->getLogger())) {
                $log->err(self::DIR_ADD_ERROR . ' ' . $directory . ': ' . $e->getMessage());
            }
            throw new ErrorLogicStorageException(self::DIR_ADD_ERROR);
        }
    }
    
    /**
     * Remove files and directories recursively
     * @param array $items files, folders to delete
     * @param AbstractStorageEntity $entity
     * @param string $cd current folder source items
     * @throws InvalidValueStorageException
     * @throws ErrorLogicStorageException
     * @return string
     */
    public function removeDirectory(array $items, AbstractStorageEntity $entity = null, $cd = null)
    {
        if (null === $entity) {
            $entity = $this->getEntity();
        } 
        
        if (! $entity instanceof AbstractStorageEntity) {
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_STORAGE_ENTITY);
			}			
			throw new InvalidValueStorageException( self::ERROR_STORAGE_ENTITY );
        }
        
        $path = $this->getStorage()->getDocumentRoot();
        $path .= DS . $entity->getCurrentPath();
        if ($cd) {
            $path .= DS . $cd;
        }
        try {
            foreach ($items as $item) {
                if (isset($item['value'])){
                    $item = $item['value'];
                }
                if (is_file($path . DS . $item)) {
                    $this->getStorage()->delete($path . DS . $item);
                } else {
                    $this->getStorage()->remove($path . DS . $item);
                }
                if (true == ($log = $this->getLogger())) {
                    $log->info(self::DIR_RM_SUCCESS . ' ' . $item . ' in ' . $path);
                }
            }
            return self::DIR_RM_SUCCESS;
        } catch (\Exception $e) {
            if (true == ($log = $this->getLogger())) {
                $log->err(self::DIR_RM_ERROR . ' ' . $item . ': ' . $e->getMessage());
            }
            throw new ErrorLogicStorageException(self::DIR_RM_ERROR);
        }
    }
    
    /**
     * Rename folders or files
     * @param string $name source name
     * @param string $rename new file or folder name
     * @param AbstractStorageEntity $entity
     * @param string $cd current folder source item
     * @throws InvalidValueStorageException
     * @throws ErrorLogicStorageException
     * @return string
     */
    public function renameDirectory($name, $rename, AbstractStorageEntity $entity = null, $cd = null)
    {
        if (null === $entity) {
            $entity = $this->getEntity();
        } 
        
        if (! $entity instanceof AbstractStorageEntity) {
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_STORAGE_ENTITY);
			}			
			throw new InvalidValueStorageException( self::ERROR_STORAGE_ENTITY );
        }
        
        $this->getStorage()->setPath(DS . $entity->getCurrentPath());
        if ($cd) {
            $this->getStorage()->setCurrent($cd);
        }
        $source = $this->getStorage()->getAdapter();
        $destination = $this->getStorage()->getAdapter();
        try {
            $this->getStorage()->rename($source . DS . $name, $destination . DS . $rename);
            
            if (true == ($log = $this->getLogger())) {
                $log->info(self::DIR_RENAME_SUCCESS . ' ' . $name . ' to ' . $rename);
            }
            return self::DIR_RENAME_SUCCESS;
        } catch (\Exception $e) {
            if (true == ($log = $this->getLogger())) {
                $log->err(self::DIR_RENMAE_ERROR . ' ' . $name . ': ' . $e->getMessage());
            }
            throw new ErrorLogicStorageException(self::DIR_RENAME_ERROR);
        }
    }
    
    /**
     * Move folder and/or files
     * @param array $items files, folders to move
     * @param string $destination destination path
     * @param AbstractStorageEntity $entity AbstractStorageEntity
     * @param string $cd current folder source item
     * @throws InvalidValueStorageException
     * @throws ErrorLogicStorageException
     * @return string
     */
    public function moveDirectory(array $items, $destination, AbstractStorageEntity $entity = null, $cd = null)
    {
        if (null === $entity) {
            $entity = $this->getEntity();
        } 
        
        if (! $entity instanceof AbstractStorageEntity) {
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_STORAGE_ENTITY);
			}			
			throw new InvalidValueStorageException( self::ERROR_STORAGE_ENTITY );
        }
        
        $destination = $this->getStorage()->getDocumentRoot() . DS . $destination;
        
        $this->getStorage()->setPath(DS . $entity->getCurrentPath());
        if ($cd) {
            $this->getStorage()->setCurrent($cd);
        }
        $source = $this->getStorage()->getAdapter();
        
        try {
            $this->getStorage()->move($items, $source, $destination);
            if (true == ($log = $this->getLogger())) {
                $log->info(self::DIR_MOVE_SUCCESS);
            }
            return self::DIR_MOVE_SUCCESS;
        } catch (\Exception $e) {
            if (true == ($log = $this->getLogger())) {
                $log->err(self::DIR_MOVE_ERROR . ': ' . $e->getMessage());
            }
            throw new ErrorLogicStorageException(self::DIR_MOVE_ERROR);
        }
    }
    
    /**
     * Copy files and directories recursively
     * 
     * @param array $items source files or folder
     * @param string $destination destination folder
     * @param AbstractStorageEntity $entity
     * @param string $cd current folder source item
     * @throws InvalidValueStorageException
     * @throws ErrorLogicStorageException
     * @return string
     */
    public function copyDirectory(array $items, $destination, AbstractStorageEntity $entity = null, $cd = null)
    {
        if (null === $entity) {
            $entity = $this->getEntity();
        } 
        
        if (! $entity instanceof AbstractStorageEntity) {
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_STORAGE_ENTITY);
			}			
			throw new InvalidValueStorageException( self::ERROR_STORAGE_ENTITY );
        }
        
        $destination = $this->getStorage()->getDocumentRoot() . DS . $destination;
        
        $this->getStorage()->setPath(DS . $entity->getCurrentPath());
        if ($cd) {
            $this->getStorage()->setCurrent($cd);
        }
        $source = $this->getStorage()->getAdapter();
        
        try {
            foreach ($items as $item) {
                $this->getStorage()->copy($source . DS . $item['value'], $destination . DS . $item['value']);
                if (true == ($log = $this->getLogger())) {
                    $log->info(self::DIR_COPY_SUCCESS . ' ' . $item['value'] . ' from ' . $source . ' to ' . $destination);
                }
            }
            return self::DIR_COPY_SUCCESS;
        } catch (\Exception $e) {
            if (true == ($log = $this->getLogger())) {
                $log->err(self::DIR_COPY_ERROR . ': ' . $e->getMessage());
            }
            throw new ErrorLogicStorageException(self::DIR_COPY_ERROR);
        }
    }
    
    /**
     * Zip files and directories recursively
     * @param array $items files, folders to delete
     * @param string $destination name of zip archive
     * @param AbstractStorageEntity $entity
     * @param string $cd current folder source items
     * @throws InvalidValueStorageException
     * @throws ErrorLogicStorageException
     * @return string
     */
    public function zipDirectory(array $items, $destination, AbstractStorageEntity $entity = null, $cd = null)
    {
    	if (null === $entity) {
    		$entity = $this->getEntity();
    	}
    
    	if (! $entity instanceof AbstractStorageEntity) {
    		if (true == ($log = $this->getLogger())){
    			$log->err(self::ERROR_STORAGE_ENTITY);
    		}
    		throw new InvalidValueStorageException( self::ERROR_STORAGE_ENTITY );
    	}
    
    	$path = $this->getStorage()->getDocumentRoot();
    	$path .= DS . $entity->getCurrentPath();
    	if ($cd) {
    		$path .= DS . $cd;
    	}
    	try {
    	    $this->getStorage()->zip($items, $destination, $path);
			if (true == ($log = $this->getLogger())) {
				$log->info(self::DIR_ZIP_SUCCESS . ' ' . $path);
			}
    		return self::DIR_ZIP_SUCCESS;
    	} catch (\Exception $e) {
    		if (true == ($log = $this->getLogger())) {
    			$log->err(self::DIR_ZIP_ERROR . ': ' . $e->getMessage());
    		}
    		throw new ErrorLogicStorageException(self::DIR_ZIP_ERROR);
    	}
    } 

    
    /**
     * Unzip a item
     * @param string $file name zip archive file
     * @param AbstractStorageEntity $entity
     * @param string $cd current folder within to create the new directory
     * @throws InvalidValueStorageException
     * @throws ErrorLogicStorageException
     * @return string
     */
    public function unzipDirectory($file, AbstractStorageEntity $entity = null, $cd = null)
    {
    	if (null === $entity) {
    		$entity = $this->getEntity();
    	}
    
    	if (! $entity instanceof AbstractStorageEntity) {
    		if (true == ($log = $this->getLogger())){
    			$log->err(self::ERROR_STORAGE_ENTITY);
    		}
    		throw new InvalidValueStorageException( self::ERROR_STORAGE_ENTITY );
    	}
    
    	$path = $this->getStorage()->getDocumentRoot();
    	$path .= DS . $entity->getCurrentPath();
    	if ($cd) {
    		$path .= DS . $cd;
    	}
    	try {
    		$this->getStorage()->unzip($file, $path);
    		if (true == ($log = $this->getLogger())) {
    			$log->info(self::DIR_UNZIP_SUCCESS . ' ' . $file . ' in ' . $path);
    		}
    		return self::DIR_UNZIP_SUCCESS;
    	} catch (\Exception $e) {
    		if (true == ($log = $this->getLogger())) {
    			$log->err(self::DIR_UNZIP_ERROR . ' ' . $file . ': ' . $e->getMessage());
    		}
    		throw new ErrorLogicStorageException(self::DIR_UNZIP_ERROR);
    	}
    }    
    

    /**
     * Is directory, warpper method for is_dir
     * 
     * @param string $directory folder name
     * @param AbstractStorageEntity $entity
     * @param string $cd current folder source item
     * @throws InvalidValueStorageException
     * @return boolean
     */
    public function isDirectory($directory, AbstractStorageEntity $entity = null, $cd = null)
    {
        if (null === $entity) {
            $entity = $this->getEntity();
        } 
        
        if (! $entity instanceof AbstractStorageEntity) {
			if (true == ($log = $this->getLogger())){
				$log->err(self::ERROR_STORAGE_ENTITY);
			}			
			throw new InvalidValueStorageException( self::ERROR_STORAGE_ENTITY );
        }
        
        $path = $this->getStorage()->getDocumentRoot();
        $path .= DS . $entity->getCurrentPath();
        if ($cd) {
            $path .= DS . $cd;
        }
        if (@is_dir($path . DS . $directory)) {
            return true;
        } else {
            return false;
        }
    }
}