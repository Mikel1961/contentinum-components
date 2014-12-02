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
 * Contentinum Frontend Abstract Controller
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
abstract class AbstractMcworkController extends AbstractContentinumController
{

    /**
     * Hostname
     * 
     * @var string
     */
    protected $host;

    /**
     *
     * @var string
     */
    protected $defaultService;

    /**
     * PageOptions
     *
     * @var Contentinum\Options\PageOptions
     */
    protected $pageOptions;

    /**
     * Default host configurations
     *
     * @var array
     */
    protected $uri;

    /**
     * Page attribute
     * 
     * @var array
     */
    protected $attribute;
    
    /**
     * Page services
     * @var array
     */
    protected $services;

    /**
     *
     * @param unknown $pageOptions
     * @param unknown $preferences
     * @param unknown $defaults
     */
    public function __construct($pageOptions, $uri, $attribute = array())
    {
        $this->pageOptions = $pageOptions;
        $this->uri = $uri;
        $this->attribute = $attribute;
    }

    /**
     *
     * @return the $host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     *
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     *
     * @return the $defaultService
     */
    public function getDefaultService()
    {
        return $this->defaultService;
    }

    /**
     *
     * @param string $defaultService
     */
    public function setDefaultService($defaultService)
    {
        $this->defaultService = $defaultService;
    }

    /**
     *
     * @return the $pageOptions
     */
    public function getPageOptions()
    {
        return $this->pageOptions;
    }

    /**
     *
     * @param \ContentinumComponents\Controller\Contentinum\Options\PageOptions $pageOptions
     */
    public function setPageOptions($pageOptions)
    {
        $this->pageOptions = $pageOptions;
    }

    /**
     * Get services for this page
     * @return the $services
     */
    public function getServices()
    {
        return $this->services;
    }

	/**
	 * Set services for this page
     * @param multitype: $services
     */
    public function setServices($services)
    {
        $this->services = $services;
    }

	/**
     * Steps running form display, validation and output status message
     *
     * @param MvcEvent $e
     * @return \Zend\View\Model\ViewModel
     */
    public function onDispatch(MvcEvent $e)
    {
        $uri = $this->getRequest()->getUri();
        $this->setHost($uri->getHost());
        
        if ('index' !== $this->pageOptions->resource) {
            $authService = $this->getServiceLocator()->get('User\Authentication');
            if (! $authService->hasIdentity()) {
                return $this->redirect()->toUrl('/login');
            } else {
                $this->setIdentity($authService->getIdentity());
            }
        }
        
        $defaultRole = $this->getServiceLocator()->get('Contentinum\Acl\DefaultRole');
        $acl = $this->getServiceLocator()->get('Contentinum\Acl\Acl');
        
        $this->setXmlHttpRequest($this->getRequest()
            ->isXmlHttpRequest());
        $routeMatch = $e->getRouteMatch();
        if ($this->getRequest()->isPost()) {
            
            return $this->process($this->getPageOptions(), $defaultRole, $acl);
        } else {
            $e->getRouteMatch()->setParam('action', 'application');
            $app = $this->application($this->getPageOptions(), $defaultRole, $acl);
        }
        $e->setResult($app);
        return $app;
    }

    /**
     * Application method
     *
     * @param array $pageOptions array
     * @param string $role current user role
     * @param Zend\Acl\Acl $acl
     */
    abstract protected function application($pageOptions, $role = null, $acl = null);
}