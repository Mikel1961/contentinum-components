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
namespace ContentinumComponents\Forms\View\Helper;

use Zend\View\Helper\AbstractHelper;
use ContentinumComponents\Html\HtmlAttribute;

/**
 * Build and create a html form (Backend)
 * Render form elements and form html fieldsets
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
class FormBuild extends AbstractHelper
{

    /**
     * Build, create form
     * 
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
     * 
     * @param array $fieldsets            
     * @return string
     */
    private function renderFieldsets($fieldsets)
    {
        $html = '';
        
        foreach ($fieldsets as $fieldset) {
            if (count($fieldset->getFieldsets()) > 0) {
                $html .= $this->renderFieldsets($fieldset->getFieldsets());
            }
            
            $html .= $this->renderElements($fieldset->getElements());
        }
        
        return $html;
    }

    /**
     * Render a array from form field elements
     * 
     * @param array $elements array from form field elements
     * @return string
     */
    private function renderElements($elements)
    {
        $html = '';
        foreach ($elements as $element) {
            $tmp[] = $this->renderElement($element);
            if (true == ($fieldset = $element->getOption('fieldset'))) {
                if (isset($fieldset['nofieldset'])) {
                    foreach ($tmp as $element) {
                        $html .= $element;
                    }
                    $tmp = array();
                } else {
                    $attributes = '';
                    if (isset($fieldset['attributes'])) {
                        $attributes = HtmlAttribute::attributeArray($fieldset['attributes']);
                    }
                    $html .= '<fieldset' . $attributes . '>';
                    if (isset($fieldset['legend'])) {
                        $html .= '<legend>' . $this->view->translate($fieldset['legend']) . '</legend>';
                    }
                    foreach ($tmp as $element) {
                        $html .= $element;
                    }
                    $html .= '</fieldset>';
                    $tmp = array();
                }
            }
        }
        foreach ($tmp as $element) {
            $html .= $element;
        }
        return $html;
    }

    /**
     * Render a form field row
     */
    private function renderElement($element)
    {
        $html = '';
        $error = '';
        $type = $element->getAttribute('type');
        $formLabel = $this->view->plugin('formLabel');
        if ($element->getOption('label')) {
            $html .= $formLabel->openTag();
            $html .= $this->view->translate($element->getOption('label'));
            $html .= $formLabel->closeTag();
        }
        $html .= $this->view->formElement($element);
        if ($type == 'submit') {
            $abortDeco = $element->getOption('deco-abort-btn');
            $label = 'Button';
            if (isset($abortDeco['label'])) {
                $label = $abortDeco['label'];
            }
            if ($this->view->abortation) {
                $html .= $this->renderButton($label, $abortDeco);
            }
        }

        $error =  $this->renderErrors($element);
        $html .= $error;
        $html .= $this->renderDescription($element->getOption('description'),$element);
        $name = $element->getAttribute('name');
        if (true == ($deco = $element->getOption('deco-row'))) {
            if (isset($deco['tags'])) {
                $html = $this->buildDecco($deco, $html);
            } else {
                $deco['attributes']['id'] = 'field' . $name;
                if ( strlen($error) > 0 ){
                    $style = '';
                    if (isset($deco['attributes']['class'])){
                        $style = $deco['attributes']['class']. ' ';
                    }
                    $deco['attributes']['class'] = $style . 'error';
                }
                $attributes = '';
                if (isset($deco['attributes'])) {
                    $attributes = HtmlAttribute::attributeArray($deco['attributes']);
                }
                $html = '<' . $deco['tag'] . '' . $attributes . '>' . $html . '</' . $deco['tag'] . '>';
            }
        }
        
        return $html;
    }

    /**
     * Set form element deco row
     * @param unknown $deco
     * @param unknown $html
     * @return string
     */
    private function buildDecco($deco, $html)
    {
        $start = $end = '';
        foreach ($deco['tags'] as $element) {
            $attributes = '';
            if (isset($element['attributes'])) {
                $attributes = HtmlAttribute::attributeArray($element['attributes']);
            }
            
            $start .= '<' . $element['tag'] . $attributes . '>';
            $end .= '</' . $element['tag'] . '>';
        }
        return $start . $html . $end;
    }

    /**
     * Build form field error messages
     * 
     * @param FormElement $element            
     * @return string
     */
    private function renderErrors($element)
    {
        $html = false;
        $tag = false;
        $attributes = false;
        if ($this->view->formElementErrors()) {
            $tag = 'span';
            $err = $this->view->formElementErrors();
            $err->setMessageOpenFormat('<span class="server-error" role="alert">');
            $err->setMessageCloseString('</span>');
            $err->setMessageSeparatorString('<br />');
            $html = $err->render($element);
        }
        
        if (false === $html) {
            if (true == ($msg = $element->getOption('deco-error-msg'))) {
                if (true == ($deco = $element->getOption('deco-error'))) {
                    if (isset($deco['tag'])) {
                        $tag = $deco['tag'];
                    }
                    if (isset($deco['attributes'])) {
                        $attributes = HtmlAttribute::attributeArray($deco['attributes']);
                    }
                    $html = '<' . $tag . '' . $attributes . '>' . $msg . '</' . $tag . '>';
                }
            }
        }
        return $html;
    }

    /**
     * Build field description
     * 
     * @param array $desc            
     * @return string
     */
    private function renderDescription($desc, $element)
    {
        $html = '';
        if ($desc && strlen($desc) > 1) {
            
            $tag = 'span';
            $attributes = '';
            if (true == ($deco = $element->getOption('deco-desc') )) {
                if (isset($deco['tag'])) {
                    $tag = $deco['tag'];
                }
                if (isset($deco['attributes'])) {
                    $attributes = HtmlAttribute::attributeArray($deco['attributes']);
                }
            }
            
            $html = '<' . $tag . '' . $attributes . '>' . $this->view->translate($desc) . '</' . $tag . '>';
        }
        return $html;
    }

    /**
     * Build and create a button
     * 
     * @param string $label
     *            button label
     * @param array $decorator
     *            button attributtes and row tag
     * @return string
     */
    private function renderButton($label, $decorator = null)
    {
        $attributtes = '';
        $html = '';
        $endTag = '';
        if ($decorator) {
            if (isset($decorator['attributes']) && is_array($decorator['attributes'])) {
                $attributtes = HtmlAttribute::attributeArray($decorator['attributes']);
            }
            
            if (isset($decorator['tag'])) {
                $html .= '<' . $decorator['tag'];
            }
            
            if (isset($decorator['tag-attribs']) && is_array($decorator['tag-attribs'])) {
                $html .= HtmlAttribute::attributeArray($decorator['tag-attribs']);
            }
            
            if (isset($decorator['tag'])) {
                $html .= '>';
                $endTag = '</' . $decorator['tag'] . '>';
            }
        }
        $html .= '<a' . $attributtes;
        $html .= ' href="' . $this->view->abortation . '">';
        $html .= $label . '</a>' . $endTag;
        return $html;
    }
}