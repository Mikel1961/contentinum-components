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
use ContentinumComponents\Html\HtmlList;
use ContentinumComponents\Html\Lists\FactoryList;

class Navigation extends AbstractHelper
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
    
    
    private $list;
    
    
    private $listelements;
    

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
        'list',
        'listelements',
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
    public function __invoke(array $nav, $content, $medias, array $template)
    {
        $this->setTemplate($template);
        $html = '';
        $factory = false;
        
        $row = $this->getTemplateProperty('row', 'element');
        $grid = $this->getTemplateProperty('grid', 'element');
        $list = $this->getTemplateProperty('list', 'element');
        
        if ($list){
            $factory = new HtmlList(new FactoryList());
            $factory->setListTag($list);
            $attr = $this->getTemplateProperty('list', 'attr');
            if ($attr){
                $factory->setAttributes(false, $attr);
                
            }
            $i = 0;
            if (isset($content['brand'])){
                $brand = $this->getTemplateProperty('listelements', '0');
                $factory->setContentTag($brand['element']);
                $label = $content['brand'];
                if (isset($content['brandlink'])){
                    $label = '<a href="'.$content['brandlink'].'">' . $label . '</a>';
                }
                $factory->setHtmlContent($label);
                $factory->setTagAttributtes(false, $brand['attr'], $i);
                $i++;
            }
            
            if (isset($content['menuelabel'])){
                $mLabel = $this->getTemplateProperty('listelements', '1');
                $factory->setContentTag($mLabel['element']);
                $factory->setHtmlContent('<a href="#"><span>'.$content['menuelabel'].'</span></a>');
                $factory->setTagAttributtes(false, $mLabel['attr'], $i);
            }
            
            $html = $factory->display();
        }
        
        

 
        $this->unsetProperties();
        return $html;
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
        foreach ($this->properties as $prop){
            $this->{$prop} = null;
        }
    }
}