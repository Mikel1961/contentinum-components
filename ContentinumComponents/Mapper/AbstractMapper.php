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
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category contentinum components
 * @package Mapper
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 5.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Mapper;

use Doctrine\ORM\EntityManager;
use ContentinumComponents\Entity\AbstractEntity;
use ContentinumComponents\Storage\AbstractManager;
use ContentinumComponents\Mapper\Exeption\InvalidValueMapperException;

/**
 * Abstract class storage manager for Doctrine
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
abstract class AbstractMapper extends AbstractManager 
{
	const UNSET_ALL = 'all';
	const UNSET_ENT = 'ent';
	const UNSET_ENT_NAME = 'name';
	
	/**
	 * Logger
	 * @var \Zend\Log
	 */
	protected $logger = false;
	
	/**
	 *
	 * @see \Contentinum\Storage\AbstractManager::getEntity()
	 */
	public function getEntity() 
	{
		return $this->entity;
	}
	
	/**
	 *
	 * @see \Contentinum\Storage\AbstractManager::getEntityName()
	 */
	public function getEntityName() 
	{
		if (null === $this->entityName) {
			$this->setEntityName ( null );
		}
		return $this->entityName;
	}
	
	/**
	 *
	 * @see \Contentinum\Storage\AbstractManager::getStorage()
	 */
	public function getStorage($storage = null, $charset = 'UTF8') 
	{
		if ($storage) {
			$this->setStorage ( $storage, $charset );
		}
		
		if (! $this->storage instanceof EntityManager) {
			throw new InvalidValueMapperException ( 'There is no Doctrine EntityManager initiated !' );
		}
		
		return $this->storage;
	}
	
	/**
	 *
	 * @see \Contentinum\Storage\AbstractManager::setEntity()
	 */
	public function setEntity($entity) 
	{
		$this->entity = $entity;
	}
	
	/**
	 *
	 * @see \Contentinum\Storage\AbstractManager::setEntityName()
	 */
	public function setEntityName($name = null) 
	{
		if (null === $name) {
			$name = $this->getEntity ();
		}
		
		if (is_string ( $name )) {
			$this->entityName = $name;
		} elseif ( is_object($name) && method_exists ( $name, 'getEntityName' )) {
			$this->entityName = $name->getEntityName ();
		} else {
			throw new InvalidValueMapperException ( 'Incorrect parameters given, to set the entity name' );
		}
	}
	
	/**
	 *
	 * @see \Contentinum\Storage\AbstractManager::setStorage()
	 */
	public function setStorage($storage, $charset = 'UTF8') 
	{
		$this->storage = $storage;
		
		if (! $this->storage instanceof EntityManager) {
			throw new InvalidValueMapperException ( 'There is no Doctrine EntityManager initiated !' );
		}
		
		if (false !== $charset) {
			$this->storage->getConnection ()->exec ( 'SET NAMES "' . $charset . '"' );
		}
	}
	
	/**
	 * Returns Zend logger
	 * @return \Zend\Log | false
	 */
	public function getLog()
	{
		return $this->logger;
	}
	
	/**
	 * Unset a entity
	 * 
	 * @param string $param        	
	 */
	public function unsetEntity($param = self::UNSET_ALL) 
	{
		switch ($param) {
			case self::UNSET_ALL :
				$this->entity = null;
				$this->entityName = null;
				break;
			case self::UNSET_ENT :
				$this->entity = null;
				break;
			case self::UNSET_ENT_NAME :
				$this->entityName = null;
				break;
			default :
				break;
		}
	}
}