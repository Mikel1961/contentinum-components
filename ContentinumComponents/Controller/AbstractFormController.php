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
use ContentinumComponents\Forms\AbstractForms;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Contentinum Abstract Backend Form Controller
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
abstract class AbstractFormController extends AbstractContentinumController
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
	 * Populate form standard values 
	 * @var array
	 */
	protected $addPopulate;
	
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
	public function __construct(AbstractForms $factory) 
	{
		$this->formFactory = $factory;
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
		$routeMatch = $e->getRouteMatch ();
		$ctrl = $routeMatch->getParam ( 'controller' );
		$page = str_replace ( '\\', '_', $ctrl );
		$uripath = str_replace ( '/', '_',$uri->getPath());
		$page = substr($uripath,1,strlen($uripath));
		$spliturl = $this->getServiceLocator ()->get ( 'Mcwork\PagesUrlSplit' );
		$page = $spliturl->split($page);
		$mcworkpages = $this->getServiceLocator ()->get ( 'Mcwork\Pages' );		

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
				$return = $this->error ($ctrl, $page, $mcworkpages, $this->getServiceLocator ()->get ( 'Contentinum\Acl\DefaultRole' ), $this->getServiceLocator ()->get ( 'Contentinum\Acl\Acl' ));
			}
		} else {
			
			if (method_exists ( $this, 'populate' )) {
				$this->populate ();
			}
			
			$routeMatch->setParam ( 'action', 'show' );
			$return = $this->show ($ctrl, $page, $mcworkpages, $this->getServiceLocator ()->get ( 'Contentinum\Acl\DefaultRole' ), $this->getServiceLocator ()->get ( 'Contentinum\Acl\Acl' ) );
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
	protected function show($ctrl, $page, $mcworkpages, $role = null, $acl = null) 
	{
		$content = false;
	    if ($mcworkpages->$page) {
	    	$content = $mcworkpages->$page;
	    }
	    
	    $this->adminlayout($this->layout(), $mcworkpages, $page, $role, $acl, $this->getServiceLocator()->get('viewHelperManager'));
	    $model = $this->buildView (array('form' => $this->form,'page' => $page,'pagecontent' => $content), $content, $mcworkpages);
	    
		if (null !== $this->toRoute){
			$model->setVariable('abortation', $this->toRoute);
		}
		return $model;
	}
	
	/**
	 * Show form errors
	 * @return \Zend\View\Model\ViewModel
	 */
	protected function error($ctrl, $page, $mcworkpages, $role = null, $acl = null) 
	{
	    $content = false;
	    if ($mcworkpages->$page) {
	    	$content = $mcworkpages->$page;
	    }	    
	    
	    $this->adminlayout($this->layout(), $mcworkpages, $page, $role, $acl, $this->getServiceLocator()->get('viewHelperManager'));
	    return $this->buildView (array('form' => $this->form,'page' => $page,'pagecontent' => $content), $content, $mcworkpages);	    
	}
	
	/**
	 * Set more customer form tag attributtes
	 */
	protected function formTagAttributes()
	{
		$formFactory = $this->formFactory;
		if (true == ($deco = $formFactory->getDecorators($formFactory::DECO_FORM)) ){
		    if ( isset($deco['form-attributtes']) && is_array($deco['form-attributtes']) ){
		        foreach ($deco['form-attributtes'] as $attribute => $value){
		            $this->form->setAttribute($attribute,$value);
		        }
		    }
		}
		unset($formFactory);		
	}
	
	/**
	 * Configure and preapre template view
	 *
	 * @param array $variables view template variables
	 * @param \Zend\Config\Config $content page content
	 * @param \Zend\Config\Config $mcworkpages
	 * @return \Zend\View\Model\ViewModel
	 */
	protected function buildView (array $variables, $content, $mcworkpages)
	{
		$view = new ViewModel($variables);
	
		// get html widget, if specified ...
		$widget = false;
		if (isset($content->template_widget) && strlen($content->template_widget) >= 3) {
			$widget = $content->template_widget;
		}
	
		if (false === $widget && isset($mcworkpages->_defaults->template_widget) && strlen($mcworkpages->_defaults->template_widget) >= 3) {
			$widget = $mcworkpages->_defaults->template_widget;
		}
	
		if (false !== $widget) { // ... and set this if not false
			$view->setVariable('widget', $widget);
		}
		return $view;
	}	
	
	/**
	 * Get contentinum abstract form factory
	 * @return \Contentinum\Forms\AbstractForms $formFactory
	 */
	public function getFormFactory() 
	{
		return $this->formFactory;
	}
	
	/**
	 * Set contentinum abstract form factory
	 * @param \Contentinum\Forms\AbstractForms $formFactory        	
	 */
	public function setFormFactory($formFactory) 
	{
		$this->formFactory = $formFactory;
	}
	
	/**
	 * Set Zend Form
	 * @param \Zend\Form $form \Zend\Form
	 */
	public function setForm($form) 
	{
		$this->form = $form;
	}
	
	/**
	 * Get Zend Form
	 * @return \Zend\Form
	 */
	public function getForm() 
	{
		return $this->form;
	}
	
	/**
	 * Get form action url
	 * @return string $formAction
	 */
	public function getFormAction()
	{
		return $this->formAction;
	}
	
	/**
	 * Set form action url
	 * @param string $formAction
	 */
	public function setFormAction($formAction)
	{
		$this->formAction = $formAction;
	}
	
	/**
	 * Get form standard values to populate
	 * @return the $addPopulate
	 */
	public function getAddPopulate() 
	{
		return $this->addPopulate;
	}

	/**
	 * Set form standard values to populate
	 * @param multitype: $addPopulate
	 */
	public function setAddPopulate($addPopulate) 
	{
		$this->addPopulate = $addPopulate;
	}

	/**
	 * Get form action method
	 * @return string $formMethod
	 */
	public function getFormMethod()
	{
		return $this->formMethod;
	}
	
	/**
	 * Set form action method, default is POST
	 * @param string $formMethod
	 */
	public function setFormMethod($formMethod = 'post')
	{
		$this->formMethod = $formMethod;
	}
	
	/**
	 * Get redirect route
	 * @return string $toRoute
	 */
	public function getToRoute()
	{
		return $this->toRoute;
	}
	
	/**
	 * Set redirect route
	 * @param string $toRoute
	 */
	public function setToRoute($toRoute)
	{
		$this->toRoute = $toRoute;
	}
	
	/**
	 * Get the redirect url
	 * @return the $toUrl
	 */
	public function getToUrl() 
	{
		return $this->toUrl;
	}

	/**
	 * Set redirect url
	 * @param string $toUrl
	 */
	public function setToUrl($toUrl) 
	{
		$this->toUrl = $toUrl;
	}
}