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
namespace ContentinumComponents\View\Helper\Content;

use Zend\View\Helper\AbstractHelper;
use ContentinumComponents\Html\HtmlElements;
use ContentinumComponents\Html\Element\FactoryElement;
use ContentinumComponents\Filter\Url\Prepare;
use ContentinumComponents\Html\HtmlAttribute;

class Tabs extends AbstractHelper
{

    private $header;

    private $body;

    private $headercontent;

    private $properties = array(
        'header',
        'body',
        'headercontent'
    );

    public function __invoke(array $content, array $template, $medias, $widgets, array $specified = null)
    {
        $this->setTemplate($template);
        if (null !== $specified) {
            $this->setSpecified($specified);
        }
        $str = '';
        $i = 0;
        $filter = new Prepare();
        $grid = $this->getTemplateProperty('body', 'grid', 'element');
        $attr = $this->getTemplateProperty('body', 'grid', 'attr');
        foreach ($content['entries'] as $entry) {
            $panelId = $filter->filter($entry['title']);
            $this->headercontent[] = '<a href="#' . $panelId . '">' . $entry['title'] . '</a>';
            
            $str .= '<' . $grid;
            if (0 === $i) {
                $attr['class'] = $attr['class'] . ' active';
            }
            
            $attr['id'] = $panelId;
            $str .= HtmlAttribute::attributeArray($attr) . '>';
            $str .= $this->view->contribution(array(
                'entries' => array(
                    $entry
                )
            ), $medias, $widgets);
            $str .= '</' . $grid . '>';
            $i ++;
        }
        $attr = false;
        
        $factory = new HtmlElements(new FactoryElement());
        $row = $this->getTemplateProperty('body', 'row', 'element');
        
        if ($row) {
            $factory->setContentTag($row);
            $attr = $this->getTemplateProperty('body', 'row', 'attr');
            if (false !== $attr) {
                $factory->setTagAttributtes(false, $attr);
                $attr = false;
            }
        }
        
        $factory->setHtmlContent($str);
        $str = '';
        $tabBody = $factory->display();
        $factory = new HtmlElements(new FactoryElement());
        $row = $this->getTemplateProperty('header', 'row', 'element');
        
        if ($row) {
            $factory->setContentTag($row);
            $attr = $this->getTemplateProperty('header', 'row', 'attr');
            if (false !== $attr) {
                $factory->setTagAttributtes(false, $attr);
                $attr = false;
            }
        }
        
        $grid = $this->getTemplateProperty('header', 'grid', 'element');
        $attr = $this->getTemplateProperty('header', 'grid', 'attr');
        $i = 0;
        foreach ($this->headercontent as $headerentry) {
            $str .= '<' . $grid;
            if (0 === $i) {
                $attr['class'] = 'active';
            }
            
            $str .= HtmlAttribute::attributeArray($attr);
            $str .= '>';
            $str .= $headerentry;
            $str .= '</' . $grid . '>';
            $i ++;
        }
        $factory->setHtmlContent($str);
        
        return $factory->display();
    }

    protected function setSpecified($specified)
    {
        foreach ($specified as $key => $values) {
            if (in_array($key, $this->properties)) {
                if (is_array($this->{$key})) {
                    $this->{$key} = array_merge($this->{$key}, $values);
                } else {
                    $this->{$key} = $values;
                }
            }
        }
    }

    /**
     *
     * @param unknown $prop
     * @param unknown $key
     * @return boolean
     */
    protected function getTemplateProperty($prop, $key, $element)
    {
        if (isset($this->{$prop}[$key])) {
            $part = $this->{$prop}[$key];
            if (isset($part[$element])) {
                return $part[$element];
            }
        } else {
            return false;
        }
    }

    protected function setTemplate($template)
    {
        foreach ($template as $key => $values) {
            if (in_array($key, $this->properties)) {
                $this->{$key} = $values;
            }
        }
    }
}