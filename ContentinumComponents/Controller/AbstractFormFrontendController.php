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


use ContentinumComponents\Controller\AbstractFrontendController;
use ContentinumComponents\Forms\AbstractForms;
use Zend\Mvc\MvcEvent;
use Zend\View\Helper\ViewModel;




abstract class AbstractFormFrontendController extends AbstractFrontendController
{
    
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
	 * Construct set abstract forms
	 * @param AbstractForms $factory
	 */
	public function __construct( AbstractForms $factory) 
	{
		$this->formFactory = $factory;
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
     * Steps running form display, validation and output status message
     * @param MvcEvent $e
     * @return \Zend\View\Model\ViewModel
     */
    public function onDispatch(MvcEvent $e)
    {
        if (method_exists ( $this, 'prepare' )) {
            $this->prepare ();
        }
    
        $uri = $this->getRequest()->getUri();
        $this->setXmlHttpRequest($this->getRequest()->isXmlHttpRequest());
        $routeMatch = $e->getRouteMatch ();
        $page = '';
    
        if ($this->getRequest ()->isPost ()) {
    
            $this->form->setInputFilter ( $this->form->getInputFilter () );
            $this->form->setData ( $this->getRequest ()->getPost () );
            	
            if (false === $this->formFactory->getValidation()){
                $routeMatch->setParam ( 'action', 'process' );
                $return = $this->process ();
            } elseif ($this->form->isValid ()) {
                $routeMatch->setParam ( 'action', 'process' );
                $return = $this->process ();
            } else {
                $routeMatch->setParam ( 'action', 'error' );
                //$return = $this->error ($ctrl, $page, $mcworkpages, $this->getServiceLocator ()->get ( 'Contentinum\Acl\DefaultRole' ), $this->getServiceLocator ()->get ( 'Contentinum\Acl\Acl' ));
            }
        } else {
            	
            if (method_exists ( $this, 'populate' )) {
                $this->populate ();
            }
            	
            $routeMatch->setParam ( 'action', 'show' );
            $return = $this->show ($page, $this->getServiceLocator ()->get ( 'Contentinum\Acl\DefaultRole' ), $this->getServiceLocator ()->get ( 'Contentinum\Acl\Acl' ) );
        }
    
        $e->setResult ( $return );
        return $return;
    }    
    
    /**
     * abstract class process form datas and insert or update database entry
     */
    abstract protected function process();

    /**
     * Show form
     * @return \Zend\View\Model\ViewModel
     */
    protected function show($page, $role = null, $acl = null)
    {

         
        $this->frontendlayout($this->layout(), $page, $role, $acl, $this->getServiceLocator()->get('viewHelperManager'));
        return $this->buildView (array('form' => $this->form));
    }   

    protected function buildView (array $variables)
    {
        $view = new ViewModel($variables);
    
        return $view;
    }    
       
}