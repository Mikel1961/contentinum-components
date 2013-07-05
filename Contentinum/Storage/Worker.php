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

use Contentinum\Entity\AbstractEntity;
use Contentinum\Storage\Exeption\NoDataException;
use Doctrine\ORM\EntityManager;

/**
 * Contains methods to insert, update and delete data records in a database
 * Also a summary from different methods to get data rocords
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
class Worker extends AbstractDatabase
{
	const SAVE_INSERT = 'insert';
	const SAVE_UPDATE = 'update';
	 
	/**
	 * Fields that are processed before insert
	 * 
	 * @var array
	 */
	public $preInsertFields = array (
			'createdBy' => 1,
			'updateBy' => 1,
			'registerDate' => 'datenow',
			'upDate' => 'datenow' 
	);
	
	/**
	 * Fields are unset before insert
	 * 
	 * @var array
	 */
	public $unsetInsertFields = array (
			'send' 
	);
	
	/**
	 * Last insert id
	 * 
	 * @var int
	 */
	public $lastInsertId;
	
	/**
	 * Fields that are processed before update
	 * 
	 * @var array
	 */
	public $preUpdateFields = array (
			'updateBy' => 1,
			'upDate' => 'datenow' 
	);
	
	/**
	 * Fields are unset before update
	 * 
	 * @var array
	 */
	public $unsetUpdateFields = array (
			'send',
			'registerDate',
			'createdBy' 
	);
	
	/**
	 * Construct
	 * @param EntityManager $storage
	 * @param string $charset
	 */
	public function __construct(EntityManager $storage, $charset = 'UTF8')
	{
		$this->setStorage($storage,$charset);
	}	
		
	/**
	 * Get the fields they are processed before insert
	 * 
	 * @return array $preInsertFields
	 */
	public function getPreInsertFields() 
	{
		return $this->preInsertFields;
	}
	
	/**
	 * Set fields that are processed before insert
	 * 
	 * @param array $preInsertFields        	
	 */
	public function setPreInsertFields(array $preInsertFields) 
	{
		$this->preInsertFields = $preInsertFields;
	}
	
	/**
	 * Set fields that are processed before insert
	 * 
	 * @param array $preInsertFields        	
	 */
	public function addPreInsertFields(array $preInsertFields) 
	{
		if (is_array ( $preInsertFields ) && ! empty ( $preInsertFields )) {
			foreach ( $preInsertFields as $k => $v ) {
				$this->preInsertFields [$k] = $v;
			}
		}
	}
	
	/**
	 * Get the fields they are unset before insert
	 * 
	 * @return array $unsetInsertFields
	 */
	public function getUnsetInsertFields() 
	{
		return $this->unsetInsertFields;
	}
	
	/**
	 * Set fields they are unset before insert
	 * 
	 * @param array $unsetInsertFields        	
	 */
	public function setUnsetInsertFields(array $unsetInsertFields) 
	{
		$this->unsetInsertFields = $unsetInsertFields;
	}
	
	/**
	 * Get the last table row insert id
	 * 
	 * @return int $lastInsertId
	 */
	public function getLastInsertId() 
	{
		return $this->lastInsertId;
	}
	
	/**
	 * Set the last table row id
	 * 
	 * @param int $lastInsertId        	
	 */
	public function setLastInsertId($id) 
	{
		$this->lastInsertId = $id;
	}
	
	/**
	 * Get fields they are processed before update
	 * 
	 * @return array $preUpdateFields
	 */
	public function getPreUpdateFields() 
	{
		return $this->preUpdateFields;
	}
	
	/**
	 * Set fields that are processed before update
	 * 
	 * @param array $preUpdateFields        	
	 */
	public function setPreUpdateFields(array $preUpdateFields) 
	{
		$this->preUpdateFields = $preUpdateFields;
	}
	
	/**
	 * Get fields they are unset before update
	 * 
	 * @return array $unsetUpdateFields
	 */
	public function getUnsetUpdateFields() 
	{
		return $this->unsetUpdateFields;
	}
	
	/**
	 * Set fields are unset before update
	 * 
	 * @param array $unsetUpdateFields        	
	 */
	public function setUnsetUpdateFields(array $unsetUpdateFields) 
	{
		$this->unsetUpdateFields = $unsetUpdateFields;
	}
	
	/**
	 * Unset fields before prepare datas to INSERT or UPDATE a table row
	 * 
	 * @param array $datas        	
	 * @param string $stage        	
	 * @return array $datas
	 */
	protected function _unsetFields($datas, $stage) 
	{
		switch ($stage) {
			case self::SAVE_INSERT :
				$fields = $this->unsetInsertFields;
				break;
			case self::SAVE_UPDATE :
				$fields = $this->unsetUpdateFields;
				break;
			default :
				return $datas;
		}
		foreach ( $fields as $field ) {
			if (array_key_exists ( $field, $datas )) {
				unset ( $datas [$field] );
			}
		}
		return $datas;
	}
	
	/**
	 * Prepare datas before INSERT or UPDATE a table row
	 *
	 * @param AbstractEntity $entity
	 * @param array $datas insert datas
	 * @return array process data
	 */
	protected function _processedSave(AbstractEntity $entity, array $datas, $stage) 
	{
		$author = 1;
		$props = $entity->getProperties ();
		
		switch ($stage) {
			case self::SAVE_INSERT :
				$fields = $this->preInsertFields;
				break;
			case self::SAVE_UPDATE :
				$fields = $this->preUpdateFields;
				break;
			default :
				return $datas;
		}
		
		foreach ( $fields as $field => $value ) {
			switch ($value) {
				case 'datenow' :
					(array_key_exists ( $field, $props )) ? $datas [$field] = new \DateTime () : null;
					break;
				default :
					(array_key_exists ( $field, $props )) ? $datas [$field] = $value : null;
			}
		}
		return $datas;
	}
	
	/**
	 *
	 * @param AbstractEntity $entity
	 * @param array $datas        	
	 * @return AbstractEntity
	 */
	protected function _setEntityOptions(AbstractEntity $entity, $datas) 
	{
		$entity->setOptions ( $datas );
		return $entity;
	}
	
	/**
	 * Get the last primary id value
	 *        	
	 * @param string $key        	
	 * @param string $value        	
	 * @param string $column        	
	 * @return string number last insert id
	 */
	public function sequence( $key = null, $value = null, $column = 'id') 
	{
		$em = $this->getStorage();
		$builder = $em->createQueryBuilder ();
		$builder->add ( 'select', 'MAX(main.' . $column . ') AS number' );
		$builder->add ( 'from', $this->getEntityName() . ' AS main' );
		if ($key && $value) {
			$builder->add ( 'where', 'main.' . $key . ' = ?1' )->setParameter ( 1, $value );
		}
		// prepare
		$query = $builder->getQuery ();
		// query
		return $query->getSingleScalarResult ();
	}
	
	/**
	 * Processed save (INSERT or UPDATE) a data table row
	 * 
	 * @param AbstractEntity $entity
	 *        	AbstractEntity
	 * @param array $data        	
	 * @param string $stage        	
	 * @param int $id        	
	 */
	public function save( AbstractEntity $entity, array $datas, $stage, $id = null) 
	{
		if ( empty($datas) ){
			throw new NoDataException('It was passed an empty array');
		}
		
		$em = $this->getStorage();
		switch ($stage) {
			case self::SAVE_INSERT :
				$msg = 'New data record successfully saved';
				$datas = $this->_unsetFields ( $datas, $stage );
				$datas = $this->_processedSave ( $entity, $datas, $stage );
				$entity = $this->_setEntityOptions ( $entity, $datas );
				$em->persist ( $entity );
				$em->flush ();
				if ($id) {
					$this->setLastInsertId ( $id );
				}
				break;
			case self::SAVE_UPDATE :
				$msg = 'Data record successfully updated';
				$datas = $this->_unsetFields ( $datas, $stage );
				$datas = $this->_processedSave ( $entity, $datas, $stage );
				$entity = $this->_setEntityOptions ( $entity, $datas );
				$em->persist ( $entity );
				$em->flush ();
				break;
			default :
		}
		return true;
	}
	
	/**
	 * Find/Fetch one dbtable data row
	 *
	 * @param int|string $id primary id
	 * @return AbstractEntity
	 */
	public function find ($id, $clear = false)
	{
		$em = $this->getStorage(); // entity manager
		if (true === $clear) {
			$em->clear($this->getEntityName());
		}
		return $em->find($this->getEntityName(), $id);
	}
	
	/**
	 * Find/Fetch one dbtable data row and returns array
	 *
	 * @param int|string $id primary id
	 * @return array data
	 */
	public function fetchEntry ($id, $clear = false)
	{
		$em = $this->getStorage(null);
		if (true === $clear) {
			$em->clear($this->getEntityName());
		}
		$row = $em->find($this->getEntityName(), $id);
		return is_null($row) ? null : $row->toArray();
	}
	
	/**
	 * Delete a data record
	 * @param AbstractEntity $entity
	 * @param int $id primary key value
	 * @throws NoDataException
	 * @return string
	 */
	public function deleteEntry(AbstractEntity $entity, $id)
	{
		// prepare ...
		$sql = 'DELETE ' . $entity->getEntityName() . ' main';
		$sql .= ' WHERE main.'.$entity->getPrimaryKey() .' = ' . $id;
		// prepare ...
		$em = $this->getStorage();
		$row = $em->createQuery($sql)->execute();
		if (1 >= $row){
			$msg = 'Delete data record succesfully';
			if (false !== ($log = $this->getLog())) {
				$log->info($msg . ' in ' . $entity->getEntityName());
			}
			return $msg;
		} else {
			if (false !== ($log = $this->getLog())) {
				$log->crit('Found no clear status or wrong parameter to delete in ' . $entity->getEntityName());
			}
			throw new NoDataException('Found no clear status or wrong parameter to delete');
		}
	}

	/**
	 * Delete data record(s)
	 * @param AbstractEntity $entity
	 * @param array $where
	 * @return string
	 */
	public function deleteRecords(AbstractEntity $entity,array $where = null)
	{
		$em = $this->getStorage();
		$metas = $em->getClassMetadata($entity->getEntityName());
		$conn = $em->getConnection();
		$sql = 'DELETE FROM '. $metas->getTableName();
		if ($where & !empty($where)){
			$conditions = '';
			$i = 1;
			foreach ($where as $column => $value){
				if ( 1 == $i){
					$sql .= ' WHERE ' . $column . ' = "' . $value . '"';
				} else {
					$sql .= ' AND ' . $column . ' = "' . $value . '"';
				}
				$i++;
			}
		}
		return $conn->exec($sql);
	}	
}