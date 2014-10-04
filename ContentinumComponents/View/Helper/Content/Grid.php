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

class Grid extends AbstractHelper
{
    private $grids;
    
    private $row;
    
    private $attribute;
    
    private $grid;
    
    private $gridAttribute;
    
    private $content;
    
    
    
    
    public function __invoke(array $content, array $template, array $specified = null)
    {
        
        $this->setTemplate($template);
        if (null !== $specified){
            $this->setSpecified($specified);
        }
        $number = ( $this->grids / count($content['entries']));
        $i = 0;
        $factory = new HtmlElements(new FactoryElement());
        $factory->setEncloseTag($this->row);
        $factory->setAttributes(false, $this->attribute);
        foreach ($content['entries'] as $row){
            if (isset($row['element']) && strlen($row['element']) > 0){
                $element = $row['element'];
                if (isset($row['elementAttribute']) && strlen($row['elementAttribute']) > 0){
                    $attribute = $row['elementAttribute'];
                } else {
                    $attribute = $this->getReplaceStdAttribute($i, $number);
                }
            } else {
                if (isset( $this->grid[i] )){
                    $element = $this->grid[i];
                } else {
                    $element = $this->grid[0];
                }
                $attribute = $this->getReplaceStdAttribute($i, $number);
            }
            $factory->setContentTag($element);
            $factory->setTagAttributtes(false, $attribute, $i);
            $factory->setHtmlContent($row['content']);
            $i++;            
        }
        return $factory->display();
    }
    
    
    protected function getReplaceStdAttribute($i,$number)
    {
        if (isset($this->gridAttribute[$i])){
            $attribute = $this->gridAttribute[$i];
        } else {
            $attribute = $this->gridAttribute[0];
            $attribute['class'] = str_replace($this->grids, $number, $attribute['class']);
        }
        return $attribute;
    }
    
    protected function setSpecified($specified)
    {
        $keys = get_object_vars($this);
        foreach ($specified as $key => $values){
            if (in_array($key, $keys)){
                if (is_array($this->{$key})){
                    $this->{$key} = array_merge($this->{$key},$values);
                } else {
                    $this->{$key} = $values;
                }
            }
        }
        
    }
    
    protected function setTemplate($template)
    {
        $keys = get_object_vars($this);
        foreach ($template as $key => $values){
            if (in_array($key, $keys)){
                $this->{$key} = $values;
            }
        }
    }
    
    
    
}