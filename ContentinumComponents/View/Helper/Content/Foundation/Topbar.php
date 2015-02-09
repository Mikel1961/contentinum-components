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
namespace ContentinumComponents\View\Helper\Content\Foundation;

use Zend\View\Helper\AbstractHelper;
use ContentinumComponents\Html\HtmlElements;
use ContentinumComponents\Html\Element\FactoryElement;
use ContentinumComponents\Html\HtmlList;
use ContentinumComponents\Html\Lists\FactoryList;

class Topbar extends AbstractHelper
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
    private $brand;
    
    /**
     *
     * @var unknown
     */
    private $mobilemenue;
    
    /**
     *
     * @var unknown
     */
    private $direction;    

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
        'brand',
        'mobilemenue',
        'direction'
    );

    /**
     *
     * @param array $content
     * @param unknown $medias
     * @param array $template
     * @return Ambigous <string, multitype:>
     */
    public function __invoke($entry, $template, $medias)
    {
        $this->assignTemplate($entry, $template);
        $html = '';
        $brand = '';
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
            $brand = $this->getTemplateProperty('listelements', '0');
            $factory->setContentTag($brand['element']);
            if (isset($entry['modulDisplay']) && strlen($entry['modulDisplay']) > 0){
                $label = $entry['modulDisplay'];                
                if (isset($entry['modulConfig']) && $entry['modulConfig'] > '0'){
                    $label = $this->view->images(array('medias' => $entry['modulConfig'], 'mediaStyle' => ''), $medias);
                }

                if ($this->brand){
                    $label = str_replace('%s1', $label, $this->brand);
                }

                if (isset($entry['modulLink'])){
                    if ($this->brand){
                        $label = str_replace('#', $entry['modulLink'], $label);
                    }
                }
                $factory->setHtmlContent($label);
                $factory->setTagAttributtes(false, $brand['attr'], $i);
                $i++;
            } else {
                $factory->setHtmlContent('');
                $factory->setTagAttributtes(false, $brand['attr'], $i);
                $i++;
            }
                      
            $mLabel = $this->getTemplateProperty('listelements', '1');
            $factory->setContentTag($mLabel['element']);
            if ($this->mobilemenue){
                $mobilemenue = str_replace('%s1', 'Menue', $this->mobilemenue);
            } else {
                $mobilemenue = '';
            }
            $factory->setHtmlContent($this->mobilemenue);
            $factory->setTagAttributtes(false, $mLabel['attr'], $i);
       
            
            $brand = $factory->display();
            $factory = false;
        }
        
        $container = new \Zend\Navigation\Navigation($entry['modulContent']);
        $topbar = $this->view->navigationcontentinum($container)->setAcl($this->view->acl)->setRole($this->view->role)->setUlClass($this->direction);
        
        if ($grid){
            $factory = new HtmlElements(new FactoryElement());
            $factory->setContentTag($grid);
            $attr = $this->getTemplateProperty('grid', 'attr');
            $factory->setTagAttributtes(false, $attr, 0); 
            $factory->setHtmlContent($topbar);
            $topbar = $factory->display();  
            $factory = false;
        }
        
        if ($row){
            $factory = new HtmlElements(new FactoryElement());
            $factory->setContentTag($row);
            $attr = $this->getTemplateProperty('row', 'attr');
            $factory->setTagAttributtes(false, $attr, 0);
            $factory->setHtmlContent( $brand . $topbar);
            $html = $factory->display();
            $factory = false;
        }        

        $this->unsetProperties();
        return $html;
    }
    
    protected function assignTemplate($row, $template)
    {
        if (isset($row['modulFormat'])) {
            if (isset($template[$row['modulFormat']])) {
                $this->setTemplate($template[$row['modulFormat']]->toArray());
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
        foreach ($this->properties as $prop){
            $this->{$prop} = null;
        }
    }
}