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
 * @package View
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 5.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace Contentinum\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Contentinum\Html\HtmlAttribute;

/**
 * Build and create a html form
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
class RenderForm extends AbstractHelper
{

	/**
	 * Build, create form
	 * @param unknown $form
	 * @return string html form
	 */
	public function __invoke($form)
	{
		$form->prepare();
		$html = $this->view->form()->openTag($form);
		$html .= $this->renderFieldsets($form->getFieldsets());
		$html .= $this->renderElements($form->getElements());
		$html .= $this->view->form()->closeTag($form);
		return $html;
	}	
	
	/**
	 * Render a array of field sets
	 * @param array $fieldsets
	 * @return string
	 */
	private function renderFieldsets($fieldsets)
	{
		$html = '';
	
		foreach($fieldsets as $fieldset)
		{
			if(count($fieldset->getFieldsets()) > 0) {
				$html .= $this->renderFieldsets($fieldset->getFieldsets());
			}
	
			$html .= $this->renderElements($fieldset->getElements());
		}
	
		return $html;
	}
	
	/**
	 * Render a array from form field elements
	 * @param array $elements array from form field elements
	 * @return string
	 */
	private function renderElements($elements)
	{
		$html = '';
	
		foreach($elements as $element) {
			$html .= $this->renderElement($element);
		}
	
		return $html;
	}
	
	/**
	 * 
	 * Render a form field row
	 * 
	 */
	private function renderElement($element)
	{
		$html = '';
		$type = $element->getAttribute('type');
		$formLabel = $this->view->plugin('formLabel');
		if ( $element->getOption('label') ){
			$html .= $formLabel->openTag();
			$html .= $element->getOption('label');
			$html .= $formLabel->closeTag();
		}
		
		$html .= $this->view->formElement($element);
		if( $type == 'submit') {
			$abortDeco = $element->getOption('deco-abort-btn');
			$label = 'Button';
			if ( isset($abortDeco['label']) ){
				$label = $abortDeco['label'];
			}
			if ($this->view->abortation){
				$html = $this->renderButton($label,$abortDeco);
			}
		}
		
		$html .= $this->renderErrors($element);

		$html .= $this->renderDescription($element->getOption('description'));
		
		if (true == ($deco = $element->getOption('deco-row'))) {
			$attributes = '';
			if ( isset($deco['attributes'])){
				$attributes = HtmlAttribute::attributeArray($deco['attributes']);
			}			
			$html = '<' . $deco['tag'] . '' . $attributes .'>' . $html .'</' . $deco['tag'] . '>';  
			
		}
		
		return $html;
		
	}	
	
	/**
	 * Build form field error messages
	 * @param FormElement $element
	 * @return string
	 */
	private function renderErrors($element)
	{
		$html = '';
		$tag = false;
		$attributes = false;
		if (true == ($deco = $element->getOption('deco-error'))) {
			if ( isset($deco['tag']) ){
				$tag = $deco['tag'];
			}
			if ( isset($deco['attributes'])){
				$attributes = HtmlAttribute::attributeArray($deco['attributes']);
			}
			
			$err = $this->view->formElementErrors();
			
			if (false !== $tag){
				$err->setMessageOpenFormat('<'.$tag.''.$attributes. '>');
				$err->setMessageCloseString('</' . $tag . '>');
			}
			
			if (isset($deco['separator'])){
				$err->setMessageSeparatorString($deco['separator']);
			}
			$html = $err->render($element); 
		}
		return $html;

	}
	
	/**
	 * Build field description
	 * @param array $desc
	 * @return string
	 */
	private function renderDescription($desc)
	{
		$html = '';
		if ( is_array($desc) && !empty($desc) ){
			
			$tag = 'span';
			$attributes = '';
			if (true == ($deco = $desc['deco-desc'])) {
				if ( isset($deco['tag']) ){
					$tag = $deco['tag'];
				}
				if ( isset($deco['attributes'])){
					$attributes = HtmlAttribute::attributeArray($deco['attributes']);
				}
			}
			
			$html = '<' . $tag . '' . $attributes . '>' . $desc['text'] . '</' . $tag . '>';
		}
		return $html;		
	}
	
	/**
	 * Build and create a button
	 * @param string $label button label
	 * @param array $decorator button attributtes and row tag
	 * @return string
	 */
	private function renderButton($label, $decorator = null)
	{
		$attributtes = '';
		$html = '';
		$endTag = '';
		if ( $decorator ){
			if (isset($decorator['attributes']) && is_array($decorator['attributes'])){
				$attributtes = HtmlAttribute::attributeArray($decorator['attributes']);
			}
				
			if (isset($decorator['tag'])){
				$html .= '<'. $decorator['tag'];
			}
				
			if (isset($decorator['tag-attribs']) && is_array($decorator['tag-attribs'])){
				$html .= HtmlAttribute::attributeArray($decorator['tag-attribs']);
			}
				
			if (isset($decorator['tag'])){
				$html .= '>';
				$endTag = '</'. $decorator['tag'] . '>';
			}
		}
		$html .= '<a' . $attributtes;
		$html .= ' href="' . $this->view->url($this->view->abortation) . '">';
		$html .= $label . '</a>' . $endTag;
		return $html;
	}	

}