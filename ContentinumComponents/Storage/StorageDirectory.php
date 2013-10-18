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
use ContentinumComponents\Storage\Exeption\ErrorLogicStorageException;
/**
 * Directory manager(s)
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
class StorageDirectory extends AbstractStorage
{
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

	/**
	 * Fetch all content from this directory
	 *
	 * @param string $class name model class of directory api
	 * @return array entries
	 */
	public function fetchAll ($entityName = null, $cd = null)
	{
	    if (null === $entityName){
	    	$entity = $this->getEntity();
	    	$entityName = $entity->getEntityName();
	    } else {
	    	$entity = new $entityName();
	    }
	    
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
	 * Create a new folder
	 * @param string $newDir name of new folder
	 * @param string $entityName base path directory (adatpter)
	 * @param string $cd curent directory
	 * @throws ErrorLogicStorageException
	 * @return string
	 */
	public function makeDirectory($newDir, $entityName = null, $cd = null)
	{
		if (null === $entityName){
			$entity = $this->getEntity();
			$entityName = $entity->getEntityName();
		} else {
			$entity = new $entityName();
		}	
		$path = $this->getStorage()->getDocumentRoot();	
		$path .= DS . $entity->getCurrentPath();
		if ($cd){
			$path .= DS . $cd;
		}
		try {
			$this->getStorage()->create($path. DS . $newDir);
			if (true == ($log = $this->getLogger())){
				$log->info(self::DIR_ADD_SUCCESS . ' in ' .$path);
			}	
			return self::DIR_ADD_SUCCESS;
		} catch (\Exception $e){
			if (true == ($log = $this->getLogger())){
				$log->err(self::DIR_ADD_ERROR. ': ' . $e->getMessage());
			}	
			throw new ErrorLogicStorageException(self::DIR_ADD_ERROR);		
		}
		
	}

    /**
     * Remove files and directories recursively
     * 
     * @param string $rmDir            
     * @param string $entityName base path directory (adatpter)            
     * @param string $cd            
     * @throws ErrorLogicStorageException
     */
    public function removeDirectory($rmItem, $entityName = null, $cd = null)
    {
        if (null === $entityName) {
            $entity = $this->getEntity();
            $entityName = $entity->getEntityName();
        } else {
            $entity = new $entityName();
        }
        $path = $this->getStorage()->getDocumentRoot();
        $path .= DS . $entity->getCurrentPath();
        if ($cd) {
            $path .= DS . $cd;
        }
        try {
            if ( is_file($path . DS . $rmItem)  ){
                $this->getStorage()->delete($path . DS . $rmItem);
            } else {
                $this->getStorage()->remove($path . DS . $rmItem);
            }
            if (true == ($log = $this->getLogger())) {
                $log->info(self::DIR_RM_SUCCESS);
            }
            return self::DIR_RM_SUCCESS;
        } catch (\Exception $e) {
            if (true == ($log = $this->getLogger())) {
                $log->err(self::DIR_RM_ERROR . ': ' . $e->getMessage());
            }
            throw new ErrorLogicStorageException(self::DIR_RM_ERROR);
	    }	        
	           
	}
	
	/**
	 * 
	 * @param unknown $sourceName
	 * @param unknown $destName
	 * @throws ErrorLogicStorageException
	 * @return string
	 */
	public function renameDirectory($sourceName, $destName,$entityName = null, $cd = null)
	{
	    if (null === $entityName) {
	    	$entity = $this->getEntity();
	    	$entityName = $entity->getEntityName();
	    } else {
	    	$entity = new $entityName();
	    }	    
	    $this->getStorage()->setPath(DS.$entity->getCurrentPath());
	    if ($cd){
	       $this->getStorage()->setCurrent($cd);
	    }
	    $source = $this->getStorage()->getAdapter ();
	    $dest = $this->getStorage()->getAdapter ();
	    try {
	    	$this->getStorage()->rename($source.DS.$sourceName,$dest.DS.$destName);
	    	 
	    	if (true == ($log = $this->getLogger())) {
	    		$log->info(self::DIR_RENAME_SUCCESS);
	    	}
	    	return self::DIR_RENAME_SUCCESS;
	    } catch (\Exception $e) {
	    	if (true == ($log = $this->getLogger())) {
	    		$log->err(self::DIR_RENMAE_ERROR . ': ' . $e->getMessage());
	    	}
	    	throw new ErrorLogicStorageException(self::DIR_RENAME_ERROR);
	    }	    
	}
	
	/**
	 * 
	 * @param unknown $files
	 * @param unknown $sourceName
	 * @param unknown $destName
	 * @throws ErrorLogicStorageException
	 * @return string
	 */
	public function moveDirectory($files,$source, $destination, $entityName = null, $cd = null)
	{
	    if (null === $entityName) {
	    	$entity = $this->getEntity();
	    	$entityName = $entity->getEntityName();
	    } else {
	    	$entity = new $entityName();
	    }
	    		
	    $destination = $this->getStorage()->getDocumentRoot() . DS . $destination;
	    
	    $this->getStorage()->setPath( DS . $entity->getCurrentPath());
	    if ($cd){
	    	$this->getStorage()->setCurrent($cd);
	    }	    
	    $source = $this->getStorage()->getAdapter ();
	    
		try {
			$this->getStorage()->move($files, $source, $destination );
			 
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
	 * @param array $source source files or folder
	 * @param string $dest destination folder
	 * @throws ErrorLogicStorageException
	 * @return string
	 */
	public function copyDirectory(array $source, $destination, $entityName = null, $cd = null)
	{
		if (null === $entityName) {
			$entity = $this->getEntity();
			$entityName = $entity->getEntityName();
		} else {
			$entity = new $entityName();
		}
		$destination = $this->getStorage()->getDocumentRoot() . DS . $destination;
		 
		$this->getStorage()->setPath( DS . $entity->getCurrentPath());
		if ($cd){
			$this->getStorage()->setCurrent($cd);
		}
		 
		$sourceDir= $this->getStorage()->getAdapter ();
	
		try {
			foreach ($source as $item){
				$this->getStorage()->copy($sourceDir.DS.$item['value'],$destination.DS.$item['value']);
			}
			if (true == ($log = $this->getLogger())) {
				$log->info(self::DIR_COPY_SUCCESS);
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
	 * Is directory, warpper method for is_dir
	 * @param string $dir directory
	 * @param string $entityName base path directory (adatpter) 
	 * @param string $cd current path
	 * @return boolean
	 */
	public function isDirectory($dir, $entityName = null, $cd = null)
	{
		if (null === $entityName){
			$entity = $this->getEntity();
			$entityName = $entity->getEntityName();
		} else {
			$entity = new $entityName();
		}
		$path = $this->getStorage()->getDocumentRoot();
		$path .= DS . $entity->getCurrentPath();
		if ($cd){
			$path .= DS . $cd;
		}
		if (@is_dir($path.DS.$dir)){
		    return true;
		} else {
		    return false;
		}
	}
}