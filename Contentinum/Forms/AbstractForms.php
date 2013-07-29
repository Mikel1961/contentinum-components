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
namespace Contentinum\Forms;

use Zend\Form\Factory;
use Contentinum\Forms\Exception\ParamNotExistsException;

/**
 * Abtract Form class for mcwork modul
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
abstract class AbstractForms 
{
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
	 * Display this form for delete
	 * @var boolen
	 */
	protected $delete = false;
	
	/**
	 * Construct
	 * Initiate \Zend\Form\Factory if no other factory
	 * The EntityManager is only required for RecordExists or RecordNoExists validators
	 * 
	 * @param object $storage EntityManager
	 * @param object $factory Form factory
	 */
	public function __construct($storage = null, $factory = null, $delete = false) 
	{
		$this->storage = $storage;
		if (null === $factory) {
			$this->factory = new Factory ();
		}
		$this->delete = $delete;
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