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
namespace Contentinum\Mapper;

use Contentinum\Mapper\Worker;
use Contentinum\Entity\Exeption\IsPublishEntityException;
use Contentinum\Mapper\Exeption\MissEntityMapperException;
/**
 * Basis warpper class for insert and update database operations
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
class Process extends Worker
{

	/**
	 * @see \Contentinum\Mapper\Worker::save()
	 */
	public function save($datas, $entity = null)
	{
		
		$entity = $this->handleEntity($entity);
		 
		if (null === ($id = $entity->getPrimaryValue()   )) {
			$datas[$entity->getPrimaryKey()] = $this->sequence() + 1;
			$datas = $this->foreignMapper($datas);
			parent::save($entity, $datas, self::SAVE_INSERT,$datas[$entity->getPrimaryKey()]);
		} else {
			parent::save($entity, $datas, self::SAVE_UPDATE);
		}
	}
	
	/**
	 * Delete entry if not publish
	 * @param AbstractEntity $entity
	 * @param int $id
	 * @throws IsPublishEntityException
	 */
	public function delete($entity, $id)
	{
		$entity = $this->handleEntity($entity);
		
		if ( isset($entity->publish) && 'yes' == $entity->publish ){
			throw new IsPublishEntityException('This entry is not published and therefore can not be deleted');
		} else {
			$this->deleteEntry($entity, $id);
		}
	}
	
	/**
	 * Handle entity make it available
	 * @param AbstractEntity $entity
	 * @throws MissEntityMapperException
	 * @return $entity
	 */
	protected function handleEntity($entity)
	{
		if (null === $entity && null === $this->getEntity()){
			throw new MissEntityMapperException('It must be passed an entity');
		}
		
		if (null === $entity){
			$entity = $this->getEntity();
		}

		if (null === $this->getEntity()){
			$this->setEntity($entity);
		}		
		
		return $entity;
	}
	
	/**
	 * Set traget entities before insert 
	 * @param array $datas valid form user inputs
	 * @return array
	 */
	protected function foreignMapper($datas)
	{
		if ( ! empty($this->targetEntities) ){
			$dataKeys = array_keys($datas);
			foreach ($dataKeys as $key){
				if (true == ($entityName = $this->getTargetEntities($key)   )){
					$em = $this->getStorage();
					$em->clear($entityName);
					$datas[$key] = $em->find($entityName, $datas[$key]);
				}
			}
		}
		return $datas;
	}
}