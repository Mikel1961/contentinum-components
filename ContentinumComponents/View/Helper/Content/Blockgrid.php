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

use ContentinumComponents\Html\HtmlElements;
use ContentinumComponents\Html\Element\FactoryElement;

class Blockgrid extends AbstractContentHelper
{

    protected $row;

    protected $grid;

    protected $properties = array(
        'row',
        'grid'
    );

    public function __invoke(array $content, array $template, $medias, $widgets, array $specified = null)
    {
        $this->setTemplate($template);
        
        $row = $this->getTemplateProperty('row', 'element');
        $factory = new HtmlElements(new FactoryElement());
        $factory->setEncloseTag($row);
        $factory->setAttributes(false, $this->getTemplateProperty('row', 'attr'));
        
        $grid = $this->getTemplateProperty('grid', 'element');
        $attr = $this->getTemplateProperty('grid', 'attr');
        $i = 0;
        foreach ($content['entries'] as $row) {

            $factory->setContentTag($grid);
            $factory->setTagAttributtes(false, $attr, $i);
            $factory->setHtmlContent($this->view->contribution(array(
                'entries' => array(
                    $row
                )
            ), $medias, $widgets));
            $i ++;
            
        }
        return $factory->display();
    }
}