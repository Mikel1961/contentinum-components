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

use ContentinumComponents\Html\HtmlAttribute;

class Toolbar extends AbstractContentHelper
{

    /**
     *
     * @var array
     */
    protected $row;

    /**
     *
     * @var array
     */
    protected $grid;

    /**
     *
     * @var array
     */
    protected $elements;

    /**
     *
     * @var array
     */
    protected $properties = array(
        'row',
        'grid',
        'elements'
    )
    ;

    /**
     *
     * @param array $entry
     * @param unknown $medias
     * @param unknown $template
     * @return unknown
     */
    public function __invoke(array $entry, $medias, $template)
    {
        $this->setTemplate($template);
        $grid = $this->getTemplateProperty('grid', 'element');
        $attr = array();
        if (false !== ($attr = $this->getTemplateProperty('grid', 'attr'))) {
            $attr = $this->getTemplateProperty('grid', 'attr');
        }
        $html = '';
        foreach ($this->elements as $element => $row) {
            if (isset($entry[$element])) {
                if (isset($entry[$element]['href'])) {
                    $row['href'] = $row['href'] . $entry[$element]['href'];
                }
                if (isset($entry[$element]['attr'])) {
                    if (isset($row['attr'])) {
                        $row['attr'] = array_merge($entry[$element]['attr'], $row['attr']);
                    }
                }
            }
            
            $configure = $row;
            $html .= $this->view->contentelement($grid, $this->buildLink($configure), $attr);
        }
        $row = $this->getTemplateProperty('row', 'element');
        $attr = array();
        if (false !== ($attr = $this->getTemplateProperty('row', 'attr'))) {
            $attr = $this->getTemplateProperty('row', 'attr');
        }
        
        $html = $this->view->contentelement($row, $html, $attr);
        return $html;
    }

    /**
     *
     * @param unknown $link
     * @return string
     */
    protected function buildLink($link)
    {
        $ln = '<a href="' . $link['href'] . '"';
        
        if (isset($link['attr'])) {
            $ln .= HtmlAttribute::attributeArray($link['attr']);
        }
        
        $ln .= '>';
        $label = '';
        if (isset($link['icon'])) {
            $label .= $link['icon'];
        }
        if (isset($link['content'])) {
            if (strlen($label) > 1) {
                $label .= ' ';
            }
            $label .= $link['content'];
        }
        $ln .= $label . '</a>';
        return $ln;
    }
}