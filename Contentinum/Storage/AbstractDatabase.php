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
 * @package Storage
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace Contentinum\Storage;

use Doctrine\ORM\EntityManager;
use Contentinum\Entity\AbstractEntity;
use Contentinum\Storage\Exeption\InvalidValueException;
use Contentinum\Storage\Exeption\NoEntityException;

/**
 * Abstract class storage manager for Doctrine
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
class AbstractDatabase extends AbstractManager
{

    /**
     *
     * @see \Contentinum\Storage\AbstractManager::getEntityName()
     */
    public function getEntityName()
    {
        return $this->_entityName;
    }

    /**
     *
     * @see \Contentinum\Storage\AbstractManager::setEntityName()
     */
    public function setEntityName($name)
    {
        if (is_string($name)) {
            $this->_entityName = $name;
        } elseif ($name instanceof AbstractEntity && method_exists($name, 'getEntityName')) {
            $this->_entityName = $name->getEntityName();
        } else {
            throw new InvalidValueException('Incorrect parameters given, to set the entity name');
        }
    }

    /**
     *
     * @see \Contentinum\Storage\AbstractManager::getEntity()
     */
    public function getEntity()
    {
        return $this->_entity;
    }

    /**
     *
     * @see \Contentinum\Storage\AbstractManager::setEntity()
     */
    public function setEntity($entity)
    {
        if ($entity instanceof AbstractEntity) {
            $this->_entity = $entity;
        } else {
            throw new NoEntityException('No entity was given');
        }
    }
    
    /**
     * Unset all entity parameters
     */
    public function unsetEntityParams()
    {
    	$this->_entity = null;
    	$this->_entityName = null;
    }

    /**
     * Set entity and entity name
     * 
     * @param AbstractEntity $entity
     *            AbstractEntity
     */
    public function setEntityParams($entity)
    {
        if (null === $this->_entity){
    		$this->setEntity($entity);
        }
        
        if (null === $this->_entityName){
        	$this->setEntityName($entity);
        }
    }

    /**
     *
     * @see \Contentinum\Storage\AbstractManager::getStorage()
     */
    public function getStorage($storage = null, $charset = 'UTF8')
    {
        if ($storage) {
            $this->setStorage($storage, $charset);
        }
        
        if (! $this->_storage instanceof EntityManager) {
            throw new InvalidValueException('There is no Doctrine EntityManager initiated !');
        }
        
        return $this->_storage;
    }

    /**
     *
     * @see \Contentinum\Storage\AbstractManager::setStorage()
     */
    public function setStorage($storage, $charset = 'UTF8')
    {
        $this->_storage = $storage;
        
        if (! $this->_storage instanceof EntityManager) {
            throw new InvalidValueException('There is no Doctrine EntityManager initiated !');
        }
        
        if (false !== $charset) {
            $this->_storage->getConnection()->exec('SET NAMES "' . $charset . '"');
        }
    }
}