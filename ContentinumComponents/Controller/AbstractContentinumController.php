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
 * @package Controller
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 5.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Contentinum Abstract Controller
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
abstract class AbstractContentinumController extends AbstractActionController
{
	/**
	 * Worker
	 * @var Process
	 */
	protected $worker;
	
	/**
	 * AbstractEntity
	 * @var AbstractEntity
	 */
	protected $entity;
	
	/**
	 * Contentinum\Service\Applog
	 * @var Contentinum\Service\Applog
	 */
	protected $logger;
	
	/**
	 * Customer configuration parameter
	 * @var Zend\Config\Config
	 */
	protected $configuration;
	
	/**
	 * Get mapper worker
	 * @return \Contentinum\Mapper\Process
	 */
	public function getWorker()
	{
		return $this->worker;
	}
	
	/**
	 * Set mapper worker
	 * @param Process $worker
	 */
	public function setWorker($worker)
	{
		$this->worker = $worker;
	}
	
	/**
	 * Return entity
	 * @return \Contentinum\Entity\AbstractEntity
	 */
	public function getEntity()
	{
		return $this->entity;
	}
	
	/**
	 * Set Entity
	 * @param \Contentinum\Entity\AbstractEntity $entity
	 */
	public function setEntity($entity)
	{
		$this->entity = $entity;
	}
	
	/**
	 * Get application logger
	 * @return object $logger
	 */
	public function getLogger() 
	{
		return $this->logger;
	}

	/**
	 * Set application logger
	 * @param object $logger
	 */
	public function setLogger($logger = null, $factory = null) 
	{
		if ($logger){
			$this->logger = $logger;
		}		
		if (null === $logger && null !== $factory){
			$this->logger = $this->getServiceLocator()->get($factory);
		}

	}
	
	/**
	 * @return the $configuration
	 */
	public function getConfiguration() 
	{
		if (null == $this->configuration){
		    $this->configuration = $this->getServiceLocator()->get('Contentinum\Customer');
		}
	    return $this->configuration;
	}

	/**
	 * @param \Zend\Config\Config $configuration
	 */
	public function setConfiguration($configuration) 
	{
		$this->configuration = $configuration;
	}

	
}