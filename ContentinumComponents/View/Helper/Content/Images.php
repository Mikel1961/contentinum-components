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
use ContentinumComponents\Tools\HandleSerializeDatabase;
use ContentinumComponents\Html\HtmlAttribute;

class Images extends AbstractHelper
{

    private $stdTemplate = array(
        'row' => array(
            'element' => 'figure',
            'attr' => array(
                'class' => 'imageitem'
            )
        ),
        'grid' => array(
            'element' => 'figcaption',
            'attr' => array(
                'class' => 'imagecaption'
            )
        )
    );

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

    public function __invoke($article, $medias, $template = null)
    {
        $this->setTemplate($template);
        $size = $article['mediaStyle'];
        $id = $article['medias'];
        
        // expensive loop !!
        foreach ($medias as $mediaRow){
            if ($mediaRow->id == $id ){
                $medias = array();
                $medias[$id] = $mediaRow->toArray();
                break;
            }
        }        
        
        
        $factory = false;
        if (isset($medias[$id])) {
            $medias = $medias[$id];
            $src = $medias['mediaLink'];
            
            $unserialize = new HandleSerializeDatabase();
            $mediaAlternate = $unserialize->execUnserialize($medias['mediaAlternate']);
            $mediaMetas = $unserialize->execUnserialize($medias['mediaMetas']);
            
            if (isset($mediaAlternate[$size])) {
                $src = $mediaAlternate[$size]['mediaLink'];
            }
            
            $img = '<img src="' . $src . '"';
            if (isset($mediaMetas['alt']) && strlen($mediaMetas['alt']) > 1) {
                $img .= ' alt="' . $mediaMetas['alt'] . '"';
            }
            if (isset($mediaMetas['title']) && strlen($mediaMetas['title']) > 1) {
                $img .= ' title="' . $mediaMetas['title'] . '"';
            }
            
            $img .= ' />';
            
            if (isset($article['mediaLinkUrl']) && strlen($article['mediaLinkUrl']) > 0){
                $img = '<a href="' . $article['mediaLinkUrl'] . '">' . $img . '</a>';
            }
            
            $caption = $this->caption($mediaMetas);
            $row = $this->getTemplateProperty('row', 'element');
            $grid = $this->getTemplateProperty('grid', 'element');
            
            if ($row && $grid) {
                $content = $this->format($row, $grid, $img, $caption);
            } else {
                if (false !== $caption){
                    $this->setTemplate($this->stdTemplate);
                    $content = $this->format($this->getTemplateProperty('row', 'element'), $this->getTemplateProperty('grid', 'element'), $img, $caption);
                } else {
                    $content = $img;
                }
            }
        }
        $this->unsetProperties();
        return $content;
    }

    protected function caption($mediaMetas)
    {
        if (isset($mediaMetas['caption']) && strlen($mediaMetas['caption']) > 1) {
            return $mediaMetas['caption'];
        } else {
            return false;
        }
    }
    
    protected function format($row, $grid, $img,$caption, $mediaStyle)
    {
        $html = '<' . $row;
        $attr = $this->getTemplateProperty('row', 'attr');

        if (strlen($mediaStyle) > 1){
            $class = '';
            if (isset($attr['class'])){
                $class = $attr['class'] . ' ';
            }
            $attr['class'] = $class . $mediaStyle;            
        }
        
        
        if ($attr) {
            $html .= HtmlAttribute::attributeArray($attr);
        }
        $attr = null;
        $html .= '>' . $img;
        
        if (false !== $caption) {
            if ($grid) {
                $html .= '<' . $grid;
                $attr = $this->getTemplateProperty('grid', 'attr');
                if ($attr) {
                    $html .= HtmlAttribute::attributeArray($attr);
                }
                $html .= '>';
                $html .= $caption;
                $html .= '</' . $grid . '>';
            }
        }
        $html .= '</' . $row . '>';  
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
        foreach ($this->properties as $prop) {
            $this->{$prop} = null;
        }
    }
}