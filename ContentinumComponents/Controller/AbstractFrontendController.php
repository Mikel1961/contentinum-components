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
abstract class AbstractFrontendController extends AbstractContentinumController 
{
    /**
     * Hostname
     * @var string
     */
    protected $host;
    
    /**
     * Default host configurations
     * @var array
     */
    protected $preferences;
    
    /**
     * Default page configurations
     * @var unknown
     */
    protected $defaults;
        
    /**
     * Current page
     * @var array
     */
    protected $page;
    
    /**
     * Layout script key
     * @var string
     */
    protected $layout;
    
    /**
     * Template script key
     * @var string
     */
    protected $template;
    
    /**
     * AbstractForms
     * @var AbstractForms
     */
    protected $formFactory;
    
    /**
     *
     * @var Zend\Form
     */
    protected $form;
    
    /**
     * Form action
     * @var string
     */
    protected $formAction;
    
    /**
     * Form action method
     * Default is POST
     * @var string
     */
    protected $formMethod = 'post';
    
    /**
     * Redirect route
     * @var string
     */
    protected $toRoute;
    
    /**
     * Redirect to url
     * @var string
     */
    protected $toUrl;    
    
    /**
     * @return the $host
     */
    public function getHost()
    {
        return $this->host;
    }
    
    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }
    
    /**
     * @return the $defaults
     */
    public function getDefaults()
    {
        return $this->defaults;
    }
    
    /**
     * @param array $defaults
     */
    public function setDefaults($defaults)
    {
        $this->defaults = $defaults;
    }
    
    /**
     * @return the $page
     */
    public function getPage()
    {
        return $this->page;
    }
    
    /**
     * @param multitype: $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }
    
    /**
     * @return the $preferences
     */
    public function getPreferences()
    {
        return $this->preferences;
    }    
    
    /**
     * @param multitype: $preferences
     */
    public function setPreferences($preferences)
    {
        $this->preferences = $preferences;
    }   
    
    /**
     * @return the $layout
     */
    public function getLayout()
    {
        return $this->layout;
    }

	/**
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

	/**
     * @return the $template
     */
    public function getTemplate()
    {
        return $this->template;
    }

	/**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }
    
	/**
     * @return the $formFactory
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }
    
    /**
     * @param \ContentinumComponents\Controller\AbstractForms $formFactory
     */
    public function setFormFactory($formFactory)
    {
        $this->formFactory = $formFactory;
    }
    
    /**
     * @return the $form
     */
    public function getForm()
    {
        return $this->form;
    }
    
    /**
     * @param \ContentinumComponents\Controller\Zend\Form $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }
    
    /**
     * @return the $formAction
     */
    public function getFormAction()
    {
        return $this->formAction;
    }
    
    /**
     * @param string $formAction
     */
    public function setFormAction($formAction)
    {
        $this->formAction = $formAction;
    }
    
    /**
     * @return the $formMethod
     */
    public function getFormMethod()
    {
        return $this->formMethod;
    }
    
    /**
     * @param string $formMethod
     */
    public function setFormMethod($formMethod)
    {
        $this->formMethod = $formMethod;
    }
    
    /**
     * @return the $toRoute
     */
    public function getToRoute()
    {
        return $this->toRoute;
    }
    
    /**
     * @param string $toRoute
     */
    public function setToRoute($toRoute)
    {
        $this->toRoute = $toRoute;
    }
    
    /**
     * @return the $toUrl
     */
    public function getToUrl()
    {
        return $this->toUrl;
    }
    
    /**
     * @param string $toUrl
     */
    public function setToUrl($toUrl)
    {
        $this->toUrl = $toUrl;
    }    
        
    /**
     * Get html structur xml file
     * @param string $key
     * @return Zend\Config
     */
    public function getHtmllayouts($key = null)
    {
        if (null == $key){
            $key = 'Contentinum\Htmllayouts';
        }
        return $this->getServiceLocator()->get($key);
    }
    
    /**
     * Get html widgets xml file
     * @param string $key
     * @return Zend\Config
     */
    public function getHtmlwidgets($key = null)
    {
        if (null == $key){
            $key = 'Contentinum\Htmlwidgets';
        }
        return $this->getServiceLocator()->get($key);
    } 

    
    /**
     * Default user role
     *
     * @return Ambigous <object, multitype:, \Contentinum\Acl\DefaultRole>
     */
    public function getDefaultRole()
    {
        return $this->getServiceLocator()->get('Contentinum\Acl\DefaultRole');
    }

    /**
     * Acl configuration
     *
     * @return Ambigous <object, multitype:, \Contentinum\Acl\Acl>
     */
    public function getAclService()
    {
        return $this->getServiceLocator()->get('Contentinum\Acl\Acl');
    }
    
    
    
    /**
     * Steps running form display, validation and output status message
     * @param MvcEvent $e
     * @return \Zend\View\Model\ViewModel
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->setXmlHttpRequest($this->getRequest()->isXmlHttpRequest());
        $routeMatch = $e->getRouteMatch ();
        if ($this->getRequest ()->isPost ()) {
            
            if ($this->form){
                $this->form->setInputFilter ( $this->form->getInputFilter () );
                $this->form->setData ( $this->getRequest ()->getPost () );
            
            
            
                if (false === $this->formFactory->getValidation()){
                    $formprocess = $this->process ();
                } elseif ($this->form->isValid ()) {
                    $formprocess = $this->process ();
                } else {
                    //$routeMatch->setParam ( 'action', 'error' );
                    //$return = $this->error ($ctrl, $page, $mcworkpages, $this->getServiceLocator ()->get ( 'Contentinum\Acl\DefaultRole' ), $this->getServiceLocator ()->get ( 'Contentinum\Acl\Acl' ));
                } 

                $e->getRouteMatch ()->setParam ( 'action', 'application' );
                $app = $this->application($this->getPreferences(), $this->getDefaults(), $this->getPage());                
            }
            
        } else {
            $e->getRouteMatch ()->setParam ( 'action', 'application' );
            $app = $this->application($this->getPreferences(), $this->getDefaults(), $this->getPage());
        }
		$e->setResult ( $app );
		return $app;
    }    
}