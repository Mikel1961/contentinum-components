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

abstract class AbstractContentHelper extends AbstractHelper
{
    /**
     * 
     * @var string
     */
    protected $layoutKey;
    
    /**
     * 
     * @var array
     */
    protected $properties = array();
    
    
    
    protected function getLayoutKey()
    {
        if (null === $this->layoutKey){
            $this->layoutKey = $this->view->htmllayouts[$this->view->templateKey]->layoutkey;
        }
        return $this->layoutKey;
    }
    
    /**
     * 
     * @param unknown $prop
     * @return boolean
     */
    protected function getProperty($prop)
    {
        if ( isset($this->properties[$prop]) ){
            return $this->{$prop};
        } else {
            return false;
        }
    }
    
    /**
     * 
     * @param unknown $prop
     * @param unknown $value
     * @return unknown|boolean
     */
    protected function setProperty($prop, $value)
    {
        if ( isset($this->properties[$prop]) ){
            return $this->{$prop} = $value;
        } else {
            return false;
        }
    } 

    /**
     * 
     */
    protected function unsetProperties()
    {
        foreach ($this->properties as $prop) {
            $this->{$prop} = null;
        }
    }    
    
    /**
     * 
     * @param unknown $prop
     * @param unknown $key
     * @param unknown $value
     * @return boolean
     */
    protected function setTemplateProperty($prop, $key, $value)
    {
        if (isset($this->{$prop}[$key])) {
            $this->{$prop}[$key] = $value;
        } else {
            return false;
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
        foreach ($template as $key => $values) {
            if (in_array($key, $this->properties)) {
                $this->{$key} = $values;
            }
        }
    } 

    
    protected function getTemplate()
    {
        $template = array();
        foreach ($this->properties as $key){
            $template['key'] = $this->{$key};
        }
        return $template;
    }
    
    /**
     * 
     * @param unknown $pattern
     * @param unknown $content
     * @param string $beforeGrid
     */
    protected function deployRow($pattern, $content, $beforeGrid = '')
    {
        $html = '';
        if (null !== $pattern){
            $pattern = $pattern->toArray();
            $html .= $beforeGrid;
            if (isset($pattern['grid'])){
                $attr = array();
                
                if (isset($pattern['grid']['attr']) && !empty($pattern['grid']['attr'])){
                    $attr = $pattern['grid']['attr'];
                }
                $str = '';
                if (isset($pattern['grid']['content'])){
                    $str = $pattern['grid']['content'];
                }                
                $html .= $this->view->contentelement($pattern['grid']['element'],$content . $str,$attr);
            }
            
            
            if (isset($pattern['row'])){
                $attr = array();
                
                if (isset($pattern['row']['attr']) && !empty($pattern['row']['attr'])){
                    $attr = $pattern['row']['attr'];
                }
                $html = $this->view->contentelement($pattern['row']['element'],$html,$attr);                
            }
        }
        return $html;
    }
    
    /**
     * 
     * @param unknown $files
     */
    protected function deployFiles($files)
    {
        foreach ($files as $index => $file){
            $this->view->inlinescript()->offsetSetFile($index,$file);
        }
    }
    
}