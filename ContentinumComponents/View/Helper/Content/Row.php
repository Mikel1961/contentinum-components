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

class Row extends AbstractHelper
{

    /**
     *
     * @var unknown
     */
    private $row;

    /**
     *
     * @var unknown
     */
    private $grid;

    /**
     *
     * @var unknown
     */
    private $media;

    /**
     *
     * @var unknown
     */
    private $content;

    /**
     *
     * @var unknown
     */
    private $properties = array(
        'row',
        'grid',
        'media',
        'content'
    );

    /**
     *
     * @param array $content
     * @param unknown $medias
     * @param array $template
     * @return Ambigous <string, multitype:>
     */
    public function __invoke($entry, $content, $template = null)
    {
        $html = '';
        $factory = false;
        
        $this->assignTemplate($entry, $template);
        $row = $this->getTemplateProperty('row', 'element');
        $grid = $this->getTemplateProperty('grid', 'element');
        
        if ($row || $grid) {
            $factory = new HtmlElements(new FactoryElement());
        }
        
        if ($row) {
            $factory->setEncloseTag($row);
            $attr = $this->getTemplateProperty('row', 'attr');
            if (false !== $attr) {
                $factory->setAttributes(false, $attr);
                $attr = false;
            }
        }
        
        if ($grid) {
            $factory->setContentTag($grid);
            $attr = $this->getTemplateProperty('grid', 'attr');
            $factory->setTagAttributtes(false, $attr, 0);
        }
        
        if (false === $factory) {
            $html .= $content;
        } else {
            $factory->setHtmlContent($content);
            $html .= $factory->display();
        }
        
        $this->unsetProperties();
        return $html;
    }

    protected function assignTemplate($row, $template)
    {
        if (isset($row['htmlwidgets'])) {
            if (isset($template[$row['htmlwidgets']])) {
                $this->setTemplate($template[$row['htmlwidgets']]->toArray());
            } else {
                $this->unsetProperties();
            }
        }
    }

    /**
     *
     * @param unknown $prop
     * @param unknown $key
     * @return boolean
     */
    protected function getTemplateProperty($prop, $key)
    {
        if (isset($this->{$prop}[$key])) {
            return $this->{$prop}[$key];
        } else {
            return false;
        }
    }

    /**
     *
     * @param unknown $template
     */
    protected function setTemplate($template)
    {
        if (null !== $template) {
            
            foreach ($template as $key => $values) {
                if (in_array($key, $this->properties)) {
                    $this->{$key} = $values;
                }
            }
        }
    }

    protected function unsetProperties()
    {
        foreach ($this->properties as $prop) {
            $this->{$prop} = null;
        }
    }
}