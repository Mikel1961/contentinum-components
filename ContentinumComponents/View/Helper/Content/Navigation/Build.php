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
namespace ContentinumComponents\View\Helper\Content\Navigation;

use Zend\View\Helper\AbstractHelper;


class Build extends AbstractHelper
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
    public function __invoke(array $entry, $medias, $template)
    {
        if (isset($entry['modulFormat']) && strlen($entry['modulFormat']) > 1){
            if (isset($template[$entry['modulFormat']])){
                $this->setTemplate($template[$entry['modulFormat']]->toArray());
            }
        }
        $html = '';
        if (isset($entry['modulContent']['headline'])){
            $html = '<' . $entry['modulContent']['tags'] . '>' . $entry['modulContent']['headline'] . '</' . $entry['modulContent']['tags'] . '>';
        }
        
        $navlist = $entry['modulContent']['nav'];
        $ulClass = 'navigation-list';
        $attr = $this->getTemplateProperty('list', 'attr');
        if (isset($attr['class'])){
            $ulClass = $attr['class'];
        }
        
        $container = new \Zend\Navigation\Navigation($navlist);
        $html .= $this->view->navigationcontentinum($container)->setAcl($this->view->acl)->setRole($this->view->role)->setUlClass($ulClass);
        
        $row = $this->getTemplateProperty('row', 'element');
        $grid = $this->getTemplateProperty('grid', 'element');
        
        if ($grid){
            $html = $this->view->contentelement($grid, $html, $this->getTemplateProperty('grid', 'attr'));
        }
        
        if ($row){
            $html = $this->view->contentelement($row, $html, $this->getTemplateProperty('row', 'attr'));
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
    
    /**
     * 
     */
    protected function unsetProperties()
    {
        foreach ($this->properties as $prop){
            $this->{$prop} = null;
        }
    }
}