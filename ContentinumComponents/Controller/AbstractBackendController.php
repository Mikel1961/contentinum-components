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

use ContentinumComponents\Controller\AbstractContentinumController;
use Zend\Mvc\MvcEvent;

/**
 * Contentinum Backend Abstract Controller
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
abstract class AbstractBackendController extends AbstractContentinumController 
{

	/**
	 * Get current controller/page request
	 * Set/Get general settings requirements of page
	 * Start application
	 * 
	 * @see \Zend\Mvc\Controller\AbstractActionController::onDispatch()
	 */
	public function onDispatch(MvcEvent $e) 
	{
	    $uri = $this->getRequest()->getUri();
	    $ctrl = $e->getRouteMatch ()->getParam ( 'controller' );
		$page = str_replace ( '\\', '_', $ctrl );
		$uripath = str_replace ( '/', '_',$uri->getPath());
		$page = substr($uripath,1,strlen($uripath));
		$spliturl = $this->getServiceLocator ()->get ( 'Mcwork\PagesUrlSplit' );
		$page = $spliturl->split($page);
		$mcworkpages = $this->getServiceLocator ()->get ( 'Mcwork\Pages' );
		
		switch ($this->getAction($mcworkpages, $page)) {
			case 'contenthandle' :
				$e->getRouteMatch ()->setParam ( 'action', 'contenthandle' );
				$apps = $this->contenthandle ( $ctrl, $page, $mcworkpages, $this->getServiceLocator ()->get ( 'Contentinum\Acl\DefaultRole' ), $this->getServiceLocator ()->get ( 'Contentinum\Acl\Acl' ) );
				break;			
			case 'downloadcontent' :
				$e->getRouteMatch ()->setParam ( 'action', 'downloadcontent' );
				$apps = $this->downloadcontent ( $ctrl, $page, $mcworkpages, $this->getServiceLocator ()->get ( 'Contentinum\Acl\DefaultRole' ), $this->getServiceLocator ()->get ( 'Contentinum\Acl\Acl' ) );
				break;			
			case 'displaycontent' :
				$e->getRouteMatch ()->setParam ( 'action', 'displaycontent' );
				$apps = $this->displaycontent ( $ctrl, $page, $mcworkpages, $this->getServiceLocator ()->get ( 'Contentinum\Acl\DefaultRole' ), $this->getServiceLocator ()->get ( 'Contentinum\Acl\Acl' ) );
				break;
			default :
				$e->getRouteMatch ()->setParam ( 'action', 'application' );
				$apps = $this->application ( $ctrl, $page, $mcworkpages, $this->getServiceLocator ()->get ( 'Contentinum\Acl\DefaultRole' ), $this->getServiceLocator ()->get ( 'Contentinum\Acl\Acl' ) );
		}
		
		$e->setResult ( $apps );
	}
	
	/**
	 *
	 * @param \Zend\Config\Config $mcworkpages        	
	 * @param string $page        	
	 * @return string
	 */
	protected function getAction($mcworkpages, $page) 
	{
		if (isset ( $mcworkpages->$page ) && isset ( $mcworkpages->$page->action )) {
			if (strlen ( $mcworkpages->$page->action ) > 1) {
				return $mcworkpages->$page->action;
			}
		}
		return '';
	}	
	
	/**
	 * Application method
	 * 
	 * @param string $page controller/page name
	 * @param string $role current user role
	 * @param Zend\Acl\Acl $acl        	
	 */
	abstract protected function application($ctrl, $page, $mcworkpages, $role = null, $acl = null);
	
	/**
	 *
	 * @param string $ctrl controller       	
	 * @param string $page controller name        	
	 * @param \Zend\Config\Config $mcworkpages        	
	 * @param string $role user role        	
	 * @param string $acl user access list       	
	 */
	abstract protected function displaycontent($ctrl, $page, $mcworkpages, $role = null, $acl = null);
	
	/**
	 *
	 * @param string $ctrl controller
	 * @param string $page controller name
	 * @param \Zend\Config\Config $mcworkpages
	 * @param string $role user role
	 * @param string $acl user access list
	 */
	abstract protected function downloadcontent($ctrl, $page, $mcworkpages, $role = null, $acl = null);	
	
	/**
	 *
	 * @param string $ctrl controller
	 * @param string $page controller name
	 * @param \Zend\Config\Config $mcworkpages
	 * @param string $role user role
	 * @param string $acl user access list
	 */
	abstract protected function contenthandle($ctrl, $page, $mcworkpages, $role = null, $acl = null);	
}