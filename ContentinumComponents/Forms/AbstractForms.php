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
 * @package Forms
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 5.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Forms;

use Zend\Form\Factory;
use Zend\ServiceManager\ServiceLocatorInterface;
use ContentinumComponents\Forms\Exception\ParamNotExistsException;
use ContentinumComponents\Entity\Exeption\InvalidValueEntityException;

/**
 * Abtract Form class for mcwork modul
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
abstract class AbstractForms 
{
	const DECO_FORM = 'deco-form';
	const DECO_ROW_BUTTON = 'deco-row-button';
	const DECO_ROW_RADIO = 'deco-row-radio';
	const DECO_ROW_CHECK = 'deco-row-check';
	const DECO_ROW_SELECT = 'deco-row-select';
	const DECO_ROW = 'deco-row';
	const DECO_TAB_ROW = 'deco-tab-row';
	const DECO_DESC = 'deco-desc';
	const DECO_ERROR = 'deco-error';
	const DECO_ABORT_BTN = 'deco-abort-btn';
	
	const PATTERN_DECCO_ROW = '/deco-row/';
	/**
	 * Decorator keys storage
	 * @var array
	 */
	protected $decoStorageKeys = array('deco-form' => self::DECO_FORM, 'deco-row-button' => self::DECO_ROW_BUTTON, 'deco-row-radio' => self::DECO_ROW_RADIO, 'deco-row-check' => self::DECO_ROW_CHECK, 'deco-row-select' => self::DECO_ROW_SELECT, 'deco-row' => self::DECO_ROW, 'deco-tab-row' => self::DECO_TAB_ROW, 'deco-desc' => self::DECO_DESC, 'deco-error' => self::DECO_ERROR, 'deco-abort-btn' => self::DECO_ABORT_BTN);
	
	/**
	 * \Zend\Form\Factory
	 * 
	 * @var \Zend\Form\Factory
	 */
	protected $factory;
	/**
	 * \Doctrine\ORM\EntityManager
	 * 
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $storage;
	/**
	 * Array exclude columns
	 * 
	 * @var array
	 */
	protected $exclude = array ();
	
	/**
	 * Switch on|off form validation
	 * @var boolen
	 */
	protected $validation = true;
	
	/**
	 * Service Manager
	 * 
	 * @var use Zend\ServiceManager\ServiceLocatorInterface;
	 */
	protected $sl;
		
	/**
	 * Decoration form fields
	 * @var array
	 */
	protected $decorators = array( 'deco-row' => array('tag' => 'div', 'attributes' => array('class' => 'form-element')), 
	                               'deco-tab-row' => array('tag' => 'p'),
			                       'deco-desc' => array('tag' => 'span', 'attributes' => array('class' => 'desc')),
	                               'deco-error' => array('tag' => 'span', 'separator' => '<br />', 'attributes' => array('class' => 'error', 'role' => 'alert')),
	                               'deco-abort-btn' => array('label' => 'Cancel', 'attributes' => array('class' => 'button', 'id' => 'btnCancel')));
	
	/**
	 * Construct
	 * Initiate \Zend\Form\Factory if no other factory
	 * The EntityManager is only required for RecordExists or RecordNoExists validators
	 * 
	 * @param object $storage EntityManager
	 * @param boolean $validation switch of on form validation
	 * @param object $factory Form factory
	 */
	public function __construct($storage = null, $validation = true, $factory = null) 
	{
		$this->storage = $storage;
		if (null === $factory) {
			$this->factory = new Factory ();
		}
		$this->validation = $validation;
	}
	/**
	 * Returns \Zend\Form\Factory
	 * 
	 * @return \Zend\Form\Factory $factory
	 */
	public function getFactory() 
	{
		return $this->factory;
	}
	
	/**
	 * Set a form factory
	 * 
	 * @param \Zend\Form\Factory $factory        	
	 */
	public function setFactory($factory) 
	{
		$this->factory = $factory;
	}
	
	/**
	 * Return \Doctrine\ORM\EntityManager
	 * 
	 * @return \Doctrine\ORM\EntityManager $storage
	 */
	public function getStorage() 
	{
		return $this->storage;
	}
	
	/**
	 * Set Entity Manager
	 * 
	 * @param \Doctrine\ORM\EntityManager $storage        	
	 */
	public function setStorage($storage) 
	{
		$this->storage = $storage;
	}
	
	/**
	 * Get exclude columns for RecordExists or RecordNoExists validators
	 * 
	 * @return the $exclude
	 */
	public function getExclude() 
	{
		return $this->exclude;
	}
	
	/**
	 * Set exclude paramters for RecordExists or RecordNoExists validators
	 * 
	 * @param array $exclude        	
	 */
	public function setExclude($exclude) 
	{
		if (! array_key_exists ( 'field', $exclude )) {
			throw new ParamNotExistsException ( 'A column identifier is required!' );
		}
		if (! array_key_exists ( 'value', $exclude )) {
			throw new ParamNotExistsException ( 'A column value is required!' );
		}
		
		$this->exclude = $exclude;
	}
	
	/**
	 * Get status validation is on or off
	 * @return boolean $validation
	 */
	public function getValidation() 
	{
		return $this->validation;
	}

	/**
	 * Set validation switch on or off
	 * @param \Contentinum\Forms\boolen $validation
	 */
	public function setValidation($validation) 
	{
		$this->validation = $validation;
	}
	
	/**
	 * @return ServiceLocatorInterface
	 */
	public function getServiceLocator ()
	{
	    return $this->sl;
	}
	
	/**
	 * Set ServiceLocatorInterface
	 * @param ServiceLocatorInterface $sl
	 */
	public function setServiceLocator (ServiceLocatorInterface $sl)
	{
	    $this->sl = $sl;
	}

	/**
	 * @return the $decoration
	 */
	public function getDecorators($key = null) 
	{
		if (null !== $key){
			if ( preg_match(self::PATTERN_DECCO_ROW, $key) ){
				if ( isset($this->decorators[$key])){
					return $this->decorators[$key];
				} else {
					return $this->decorators[self::DECO_ROW];
				}
			} elseif ( isset($this->decorators[$key])){
					return $this->decorators[$key];
 
			} else {
				return array();
			}
		}
		return $this->decorators;
	}

	/**
	 * @param multitype:multitype:string multitype:string    $decoration
	 */
	public function setDecorators(array $decorators) 
	{
		foreach ($decorators as $key => $row ){
			if ( array_key_exists($key, $this->decoStorageKeys) ){
				$this->decorators[$key] = $row;
			}
		}
	}
	
	/**
	 * Get options for form element select from database
	 * 
	 * @param string $fieldName targetentity key
	 * @param array $columns database table columns to query
	 * @param array $where refine your query
	 * @param string $entityName database table reference
	 * @param string $dist query build distinct yes=true, no=fales
	 * @param array $options select option add before query result
	 * @throws InvalidValueEntityException
	 * @return multitype:unknown
	 */
	public function getSelectOptions($fieldName,$columns = array('value' => 'id', 'label' => 'name'), array $where = null, $entityName = null, $dist = false, $options = array())
	{
		$em = $this->storage->getStorage();
		if (null === $entityName){
    		$entityName = $this->storage->getTargetEntity($fieldName);
    		if (false === $entityName){
    			throw new InvalidValueEntityException('Entity can not be found or is not available!');
    		}
		}
		$builder = $em->createQueryBuilder ();
		$builder->add ( 'select', 'main.' . implode(', main.', $columns) );
		$builder->add ( 'from', $entityName . ' AS main' );
		if ( is_array($where) && !empty($where) ){
		    foreach ($where as $conditions){
		        $builder->where($conditions['cond']);
		        $builder->setParameter($conditions['param'][0],$conditions['param'][1]);
		    }
		}
		if (true === $dist){
		    $builder->distinct();
		}
		$query = $builder->getQuery();
		$datas = $query->getResult();
		
		if (!is_array($options) || !empty($options) ) {
		    $options = array();
		}
		if ( is_array($datas) ){	
			foreach ($datas as $row){
				$options[$row[$columns['value']]] = $row[$columns['label']];
			}
		}
		return $options;
	}	
	
	/**
	 * Get options from xml data files
	 * @param string $key
	 * @param array $options
	 * @return multitype:NULL
	 */
	public function getOptions($key, $options = array())
	{
		$entries = $this->sl->get($key);
		if (!is_array($options) || !empty($options) ) {
			$options = array();
		}
		foreach ($entries as $key => $entry){
			$options[$key] = $entry->name;
		}
		return $options;
	}	

	/**
	 * Create the form
	 * @return \Zend\Form\Factory
	 */
	abstract public function getForm();
	
	/**
	 * Abstract function contains form elements in a array
	 * @return array
	 */
	abstract public function elements();
	
	/**
	 * Abstract function contains form elements filters and validators in a array
	 * @return array
	 */
	abstract public function filter();
}