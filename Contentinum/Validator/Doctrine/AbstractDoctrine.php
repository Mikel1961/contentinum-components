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
 * @package Validator
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace Contentinum\Validator\Doctrine;

use Zend\Validator\AbstractValidator;
use Contentinum\Storage\Worker;
use Contentinum\Validator\Exeption\InvalidValueException;

/**
 * Abstract class for databse validation with Doctrine ORM
 * Original is Zend_Validate_Db_Abstract from Copyright (c) 2005-2011 Zend
 * Technologies USA Inc.
 * (http://www.zend.com)
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
abstract class AbstractDoctrine extends AbstractValidator
{
	/**
	 * Error constants
	 */
	const ERROR_NO_RECORD_FOUND = 'noRecordFound';
	
	const ERROR_RECORD_FOUND = 'recordFound';
	
	/**
	 *
	 * @var array Message templates
	 */
	protected $messageTemplates = array(
			self::ERROR_NO_RECORD_FOUND => "No record matching '%value%' was found",
			self::ERROR_RECORD_FOUND => "A record matching '%value%' was found");
	
	/**
	 *
	 * @var string
	*/
	protected $_entity = '';
	
	/**
	 *
	 * @var string
	 */
	protected $_field = '';
	
	/**
	 *
	 * @var mixed
	 */
	protected $_exclude = null;
	
	/**
	 * Contentinum\Storage\Worker
	 *
	 * @var Contentinum\Storage\Worker
	 */
	protected $worker;
	
	/**
	 * Construct
	 * @param array $options
	 * @param string $orm
	 * @throws InvalidValueException
	 */
	public function __construct (array $options)
	{
		if (! array_key_exists('storage', $options)) {
	        throw new InvalidValueException('EntityManger is missed');
	    } else {
			$this->worker = new Worker($options['storage']);
		}
	
		if (! array_key_exists('entity', $options)) {
			throw new InvalidValueException('Entity is missed');
		} else {
			$this->setEntity($options['entity']);
		}
	
		if (! array_key_exists('field', $options)) {
			throw new InvalidValueException('Name of table field is missed');
		} else {
			$this->setField($options['field']);
		}
	
		if (array_key_exists('exclude', $options)) {
			$this->setExclude($options['exclude']);
		}
	}	
	
	/**
	 *
	 * @return the $_entity
	 */
	public function getEntity ()
	{
		return $this->_entity;
	}
	
	/**
	 *
	 * @param string $entity
	 * @return Zend_Validate_Abstract
	 */
	public function setEntity ($entity)
	{
		$this->_entity = $entity;
		return $this;
	}
	
	/**
	 *
	 * @return the $_field
	 */
	public function getField ()
	{
		return $this->_field;
	}
	
	/**
	 *
	 * @param string $field
	 * @return Zend_Validate_Abstract
	 */
	public function setField ($field)
	{
		$this->_field = $field;
		return $this;
	}
	
	/**
	 *
	 * @return the $_exclude
	 */
	public function getExclude ()
	{
		return $this->_exclude;
	}
	
	/**
	 *
	 * @param mixed $exclude
	 * @return Zend_Validate_Abstract
	 */
	public function setExclude ($exclude)
	{
		$this->_exclude = $exclude;
		return $this;
	}
	
	/**
	 *
	 * @return EntityManager
	 */
	public function getStorage ()
	{
		return $this->worker->getStorage();
	}
	
	/**
	 *
	 * @param string $value
	 */
	protected function query ($value)
	{
		$sql = 'SELECT main FROM ' . $this->_entity . ' main WHERE main.' . $this->_field . ' = :value';
		if (is_array($this->_exclude)) {
			if (isset($this->_exclude['field']) && isset($this->_exclude['value'])) {
				$sql .= ' AND main.' . $this->_exclude['field'] . ' != ' . $this->_exclude['value'];
			}
		}
	
		$em = $this->getStorage();
		$query = $em->createQuery($sql);
		$query->setParameter('value', $value);
		return $query->getResult();
	}	
}