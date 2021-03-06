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
 * @since contentinum version 5.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Mapper;

use ContentinumComponents\Mapper\AbstractMapper;
use ContentinumComponents\Entity\AbstractEntity;
use ContentinumComponents\Mapper\Exeption\NoDataMapperException;
use Doctrine\ORM\EntityManager;
use ContentinumComponents\Mapper\Exeption\SaveMapperException;

/**
 * Contains methods to insert, update and delete data records in a database
 * Also a summary from different methods to get data rocords
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
class Worker extends AbstractMapper 
{
	const SAVE_INSERT = 'insert';
	const SAVE_UPDATE = 'update';
	
	/**
	 * Service Manager
	 *
	 * @var use Zend\ServiceManager\ServiceLocatorInterface;
	 */
	protected $sl;	
	
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
	 * Storage from entities
	 * @var array
	 */
	protected $targetEntities = array();	
	
	/**
	 * Parameter to build query
	 * @var boolen|array
	 */
	protected $hasEntriesParams = false;
	
	/**
	 * Customer configuration parameter
	 * @var Zend\Config\Config
	 */
	protected $configuration;	
	
	/**
	 * User identity
	 * @var object
	 */
	protected $identity;
	
	/**
	 * Language
	 * @var string
	 */
	protected $language;
	
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
     * @return \Zend\ServiceManager\ServiceLocatorInterface $sl
     */
    public function getSl()
    {
        return $this->sl;
    }

	/**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $sl
     */
    public function setSl($sl)
    {
        $this->sl = $sl;
    }

	/**
	 * @return the $hasEntriesParams
	 */
	public function getHasEntriesParams($key = null) 
	{
		if (isset($this->hasEntriesParams[$key])){
		    return $this->hasEntriesParams[$key];
		}
	    return $this->hasEntriesParams;
	}
	
	/**
	 * @param Ambigous <\ContentinumComponents\Mapper\boolen, multitype:> $hasEntriesParams
	 */
	public function addHasEntriesParams($key,$param)
	{
		$this->hasEntriesParams[$key] = $param;
	}	

	/**
	 * @param Ambigous <\ContentinumComponents\Mapper\boolen, multitype:> $hasEntriesParams
	 */
	public function setHasEntriesParams($hasEntriesParams) 
	{
		$this->hasEntriesParams = $hasEntriesParams;
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
	 * Get target entities
	 * @param string $key
	 * @return multitype:|array
	 */
	public function getTargetEntities($key = null)
	{
		if ( isset($this->targetEntities[$key]) ){
			return $this->targetEntities[$key];
		}
		return $this->targetEntities;
	}
	
	/**
	 * Get a target entity if available
	 * @param string $key
	 * @return multitype:|NULL
	 */
	public function getTargetEntity($key)
	{
		if ( isset($this->targetEntities[$key]) ){
			return $this->targetEntities[$key];
		}
		return false;
	}

	
    /**
     * Add a target entity
     * @param string $key form field name
     * @param string $entity entity name
     */
	public function addTargetEntities($key, $entity)
	{
		$this->targetEntities[$key] = $entity;
	}	
	
	/**
	 * Set target entities
	 * @param array $entity
	 */
	public function setTargetEntities(array $entity)
	{
		$this->targetEntities = $entity;
	}
	
	/**
	 * Unset a target entity
	 * @param string $key form field name
	 */
	public function unsetTargetEntities($key = null)
	{
	   if (null === $key){
	       $this->targetEntities = array();
	   } else {
	       if (isset($this->targetEntities[$key])){
	           unset($this->targetEntities[$key]);
	       }
	   }
	}	
	
	
	/**
	 * @return the $configuration
	 */
	public function getConfiguration()
	{
		return $this->configuration;
	}
	
	/**
	 * @param \Zend\Config\Config $configuration
	 */
	public function setConfiguration($configuration)
	{
		$this->configuration = $configuration;
	}	
	
	/**
	 * @return the $identity
	 */
	public function getIdentity()
	{
	    return $this->identity;
	}
	
	/**
	 * @param Zend\Auth $identity
	 */
	public function setIdentity($identity)
	{
	    $this->identity = $identity;
	}

	/**
     * @return the $language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
	 * Get current user ident
	 * @return number
	 */
	public function getUserIdent()
	{
	    return ($this->identity) ? $this->identity->userid : 1 ;
	}
	
    /**
     * Database connection to execute a native sql query
     *
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        $em = $this->getStorage();
        return $em->getConnection();
    }

	/**
	 * Get the last primary id value
	 *        	
	 * @param string $key        	
	 * @param string $value        	
	 * @param string $column        	
	 * @return string number last insert id
	 */
	public function sequence( $key = null, $value = null, $column = 'id',$clear = false) 
	{
	    
	    $em = $this->getStorage();
	    
	    if (true === $clear) {
	        $em->clear($this->getEntityName());
	    }		
	    
	    
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
	 * Native sql: Get the last primary id value
	 *        	
	 * @param string $key        	
	 * @param string $value        	
	 * @param string $column        	
	 * @return string number last insert id
	 */
	public function fetchSequence($key = null, $value = null, $column = 'id')
	{
	    $em = $this->getStorage();
	    $em->clear($this->getEntityName());
	    $tableName = $em->getClassMetadata($this->getEntityName())->getTableName();
	    $sql = "SELECT MAX({$column}) AS sequence FROM {$tableName}";
	    $conn = $em->getConnection();
	    return $conn->query($sql)->fetch();
	}
	
	/**
	 * Processed save (INSERT or UPDATE) a data table row
	 *
	 * @param object $entity AbstractEntity
	 * @param array $data        	
	 * @param string $stage        	
	 * @param int $id        	
	 */
	public function save($datas, $entity = null, $stage = '', $id = null)
	{
		if (empty ( $datas )) {
			throw new NoDataMapperException ( 'It was passed an empty array' );
		}
		try {
			$em = $this->getStorage ();
			switch ($stage) {
				case self::SAVE_INSERT :
					$msg = 'New data record successfully saved';
					$datas = $this->unsetFields ( $datas, $stage );
					$datas = $this->processedSave ( $entity, $datas, $stage );
					$entity = $this->setEntityOptions ( $entity, $datas );
					$em->persist ( $entity );
					$em->flush ();
					if ($id) {
						$this->setLastInsertId ( $id );
					}
					break;
				case self::SAVE_UPDATE :
					$msg = 'Data record successfully updated';
					$datas = $this->unsetFields ( $datas, $stage );
					$datas = $this->processedSave ( $entity, $datas, $stage );
					$entity = $this->setEntityOptions ( $entity, $datas );
					$em->persist ( $entity );
					$em->flush ();
					break;
				default :
					break;
			}
			// log if available
			if (true === $this->hasLogger()) {
				$this->logger->info($msg . ' to ' . $this->getEntityName () );
			}
			unset($datas);
			return $msg;
		} catch ( \Exception $e ) {
			// log if available
			if (true === $this->hasLogger()) {
				$this->logger->crit($this->getEntityName () . ' ' . $stage . ' database table ' . $e->getMessage () );
			}
			throw new SaveMapperException ( $this->getEntityName () . ' ' . $stage . ' database table ' . $e->getMessage () );
		}
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
	 * Fetch database entry, get result as array
	 * Render foreign entities if get result in a array
	 * 
	 * @param int|string $id primary key
	 * @param boolen $toArray get db result as array
	 * @return multitype
	 */
	public function fetchPopulateValues($id, $toArray = true)
	{
		$em = $this->getStorage(null);
		$builder = $em->createQueryBuilder();
		$builder->add('select', 'main')->add('from', $this->getEntityName() . ' AS main');
		$builder->add('where', 'main.' .  $this->getEntity()->getPrimaryKey() . ' = ?' . 1);
		$builder->setParameter(1, $id);	
		$query = $builder->getQuery();
		$row = $query->getSingleResult();
		if (true === $toArray) {
			$row = $row->toArray();
			$metas = $em->getClassMetadata($this->getEntityName());
			$targetMap = $this->getJoinColumns($metas->getAssociationMappings());
			if ( ! empty($targetMap) ){
				foreach ($targetMap as $field => $ref){
					if ( isset($row[$field]) ){
						$row[$field] = $row[$field]->$ref;
					}
				}
			}			
		}	
		return $row;		
	}
	
	/**
	 * Populate further form datas to update a data row
	 * @param string $entityName name entity
	 * @param string $column name column query parameter
	 * @param int $id value for query parameter
	 * @param array $columns columns to merge in data array
	 * @param array $datas base form datas
	 * @return Ambigous <multitype:, boolean, string, unknown>
	 */
	public function populateFurtherEntities($entityName, $column, $id, $columns, $datas)
	{
	    $datas = $this->fetchEntries($entityName, $column, $id);
	    if (isset($datas[0])){
	        $datas = $datas[0]->toArray();
	    }
	    $result = array();
	    foreach ($columns as $colName){
	        if (isset($datas[$colName])){
	            $result[$colName] = $datas[$colName];
	        }
	    }
	    if (!empty($result)){
	        $datas = array_merge($datas,$result);
	    }
	    return $datas;
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
			if (false !== ($log = $this->getLogger())) {
				$log->info($msg . ' in ' . $entity->getEntityName());
			}
			return $msg;
		} else {
			if (false !== ($log = $this->getLogger())) {
				$log->crit('Found no clear status or wrong parameter to delete in ' . $entity->getEntityName());
			}
			throw new NoDataMapperException('Found no clear status or wrong parameter to delete');
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
	
	/**
	 * Check if data records are available
	 * @param string $tableEntity 
	 * @param string $column entity referer column name
	 * @param string|int $value column value
	 * @return boolean
	 */
	public function hasEntries($tableEntity, $column, $value)
	{
	    $em = $this->getStorage(null);
	    $builder = $em->createQueryBuilder();
	    $builder->add('select', 'main')->add('from', $tableEntity . ' AS main');
	    $builder->add('where', 'main.'.$column.' = ?' . 1);
	    $builder->setParameter(1, $value);
	    $query = $builder->getQuery();	    
	    if (!$query->getResult()){
	        return false;
	    } else {	        
	        return true;
	    }	    
	}
	
	/**
	 * Fetch entries
	 * @param string $entityName 
	 * @param string $column entity referer column name
	 * @param string|int $value column value
	 * @return boolean|string
	 */
	public function fetchEntries($entityName, $column, $value)
	{
	    $em = $this->getStorage(null);
	    $builder = $em->createQueryBuilder();
	    $builder->add('select', 'main')->add('from', $entityName . ' AS main');
	    $builder->add('where', 'main.'.$column.' = ?' . 1);
	    $builder->setParameter(1, $value);
	    $query = $builder->getQuery();
	    if (!($result = $query->getResult())){
	    	return false;
	    } else {
	    	return $result;
	    }	    
	}
	
	/**
	 * Native sql query - fetch all
	 * @param string $sql
	 * @return multitype: array|null
	 */
	public function fetchAll($sql)
	{
	    $conn = $this->getConnection();
	    return $conn->query($sql)->fetchAll();	    
	}
	
	/**
	 * Native sql query - fetch a row
	 * @param string $sql sql query string
	 * @return multitype: array|null
	 */
	public function fetchRow($sql)
	{
	    $conn = $this->getConnection();
	    return $conn->query($sql)->fetch();
	}
	
	/**
	 * Native sql query - insert a row
	 * @param string $tableName database tablename
	 * @param array $inserts
	 * @return number affected rows
	 */
	public function insertQuery($tableName, array $inserts)
	{
	    $conn = $this->getConnection();
	    return $conn->insert($tableName, $inserts);
	}	
	
	/**
	 * Native sql query - execute query
	 * UPDATE, DELETE etc.
	 * @param string $sql sql query string
	 * @param array $parameter sql query parameters
	 * @return boolean|number affected rows
	 */
	public function executeQuery($sql, array $parameter = null)
	{
	    $conn = $this->getConnection();
	    return $conn->prepare($sql)->execute($parameter);
	}
	
	/**
	 * Publishing data row
	 * @param int $id data row id
	 * @param boolen $clear clear db cache
	 * @throws NoDataMapperExceptio error messages
	 * @return string success messages
	 */
	public function publish($id, $clear = false)
	{
	    $status = $this->find($id, $clear);
	    switch ($status->publish){
	        case 'no': //$datas, $entity = null, $stage = '', $id = null)
	            self::save(array('publish' => 'yes', 'updateflag' => '1'),$status, self::SAVE_UPDATE,$id );
	            $logmsg = 'Data record published successfully';
	            $msg = 'Changed publication status of a data record successfully';
	            break;
	        case 'yes':
	            self::save(array('publish' => 'no', 'updateflag' => '1'),$status, self::SAVE_UPDATE,$id );
	            $logmsg = 'Data record unpublished successfully';
	            $msg = 'Changed publication status of a data record successfully';
	            break;
	        default:
	            if (false !== ($log = $this->getLogger())) {
	                $log->crit('No published status found in ' . $this->getEntityName ());
	            }
	            throw new NoDataMapperException('No published status found');
	    }
	    // log if available
	    if (false !== ($log = $this->getLogger())) {
	        $log->info($logmsg . ' in ' . $this->getEntityName ());
	    }
	    return $msg;
	}	
	
	/**
	 * Unset fields before prepare datas to INSERT or UPDATE a table row
	 *
	 * @param array $datas
	 * @param string $stage
	 * @return array $datas
	 */
	protected function unsetFields($datas, $stage)
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
	protected function processedSave(AbstractEntity $entity, array $datas, $stage)
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
			switch ($value){
				case 'datenow' :
					(array_key_exists ( $field, $props )) ? $datas [$field] = new \DateTime () : null;
					break;
				default :
					if ( 'updateBy' == $field || 'createdBy' == $field ){
					    if ($this->identity){
					        (array_key_exists ( $field, $props )) ? $datas [$field] = $this->identity->userid : 1;
					    } else {
					        (array_key_exists ( $field, $props )) ? $datas [$field] = $value : null;
					    }					    
					} else {
					    (array_key_exists ( $field, $props )) ? $datas [$field] = $value : null;
					}
			}
		}
		return $datas;
	}
	
	/**
	 * Get join columns from target entity
	 * @param array $map
	 * @return multitype:NULL |multitype:array
	 */
	protected function getJoinColumns($map = array()) 
	{
		if (! empty ( $map )) {
			$targetMap = array ();
			foreach ( $map as $fieldName ) {
				
				if (isset ( $fieldName ['joinColumns'] [0] ['referencedColumnName'] )) {
					$targetMap [$fieldName ["fieldName"]] = $fieldName ['joinColumns'] [0] ['referencedColumnName'];
				}
			}
			return $targetMap;
		}
		
		return array ();
	}
	
	/**
	 *
	 * @param AbstractEntity $entity
	 * @param array $datas
	 * @return AbstractEntity
	 */
	protected function setEntityOptions(AbstractEntity $entity, $datas)
	{
		$entity->setOptions ( $datas );
		return $entity;
	}
	
}