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
 * @package Entity
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace Contentinum\Entity;

use Contentinum\Entity\Exeption\MethodNotExistsException;

/**
 * Abstract class for the entities
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
abstract class AbstractEntity
{

	/**
	 * Get the entity name
	 * warper method for get_class
	 */
	abstract public function getEntityName();
	
	/**
	 * Get all properties from current class as an array
	 * warper method for get_object_vars
	 */
	abstract public function getProperties ();

	/**
	 * Get the primary property
	 * Is the same as the primary value
	*/
	abstract public function getPrimaryValue ();

	/**
	 * Get the primary table key name
	*/
	abstract public function getPrimaryKey ();

	/**
	 * Set options value if correct method avaibale
	 *
	 * @param array $options
	 * @return void
	*/
	public function setOptions (array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) {
			$parts = explode('_', $key);
			$method = 'set';
			foreach ($parts as $part) {
				$method .= ucfirst($part);
			}
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}
		return $this;
	}

	/**
	 * Return an associative array from object or entity properties
	 *
	 * @return array
	 */
	public function toArray ()
	{
		$array = array();
		$properties = $this->getProperties();
		foreach ($properties as $key => $value) {
			if ('_' == substr($key,0, 1)) {
				$key = substr_replace($key, '', 0, 1);
			}
			switch ($key) {
				case 'register_date':
				case 'up_date':
					if ($value instanceof \DateTime) {
						$array[$key] = $value->format('YYYY-MM-dd HH:mm:ss');
					} else {
						$array[$key] = $value;
					}
					break;
				default:
					$array[$key] = $value;
			}
		}
		return $array;
	}

	/**
	 * Interzeptor method __set
	 *
	 * @param string $name method name
	 * @param mixed $value values to set
	 * @throws Contentinum_Model_Exception
	 */
	public function __set ($name, $value)
	{
		$parts = explode('_', $name);
		$method = 'set';
		foreach ($parts as $part) {
			$method .= ucfirst($part);
		}
		if (! method_exists($this, $method)) {
			throw new MethodNotExistsException('Invalid ' . get_class($this) . ' property');
		}
		$this->$method($value);
	}

	/**
	 * Interzeptor method __get
	 *
	 * @param string $name method name
	 * @throws Contentinum_Model_Exception
	 */
	public function __get ($name)
	{
		$parts = explode('_', $name);
		$method = 'get';
		foreach ($parts as $part) {
			$method .= ucfirst($part);
		}
		if (! method_exists($this, $method)) {
			throw new MethodNotExistsException('Invalid ' . get_class($this) . ' property');
		}
		return $this->$method();
	}
}