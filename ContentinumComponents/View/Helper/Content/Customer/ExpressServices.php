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
namespace ContentinumComponents\View\Helper\Content\Customer;

use Zend\View\Helper\AbstractHelper;
use ContentinumComponents\Html\HtmlTable;
use ContentinumComponents\Html\Table\FactoryTable;

class ExpressServices extends AbstractHelper
{

    public function __invoke(array $entry, $medias, $template)
    {
        return 'Eildienst';
    }

    protected function ausgaben($entries)
    {
        // prepare content, create a table
        $tableFactory = new HtmlTable(new FactoryTable());
        // set table tag attributes
        $tableFactory->setAttributes('class', 'table tblNoSort display compact');
        $tableFactory->setAttributes('id', 'mcworkTables');
        $i = 0;
        $iClass = 0;
        $headlines = array(
            'Eildienst' => array(
                'head' => array(
                    'class' => 'width25'
                ),
                'body' => array(
                    'class' => 'width25'
                )
            ),
            'Themen' => array(
                'head' => array(
                    'class' => 'width75'
                ),
                'body' => array(
                    'class' => 'width75'
                )
            )
        );
        $ihead = count($headlines);
        foreach ($headlines as $column => $attributes) {
            $columns[] = $this->translate($column);
            if (is_array($attributes) && ! empty($attributes)) {
                foreach ($attributes as $area => $attribute) {
                    switch ($area) {
                        case 'head':
                            $tableFactory->setHeadlineAttributtes('class', $attribute['class'], $i);
                            break;
                        case 'body':
                            $tableFactory->setTagAttributtes('class', $attribute['class'], $i);
                            break;
                        default:
                            break;
                    }
                }
            }
            $i ++;
        }
        $tableFactory->setHeadline($columns);
        $i = 0;
        $id = false;
        $issue = '';
        $topic = '';
        foreach ($this->entries as $entry) {
            if ($id != $entry->webEildienst->id && false !== $id) {
                $rowContent = array();
                $rowContent[] = $issue;
                $rowContent[] = '<ul>' . $topic . '</ul>';
                $tableFactory->setHtmlContent($rowContent);
            }
            
            if ($id != $entry->webEildienst->id) {
                $topic = '';
                $id = $entry->webEildienst->id;
                $dateTime = '<time>' . $this->dateFormat(new \DateTime($entry->webEildienst->publishUp), \IntlDateFormatter::FULL) . '</time>';
                $issue = '<h3>' . $entry->webEildienst->name . '</h3><p>' . $dateTime . '</p><p><a class="button" href="/eildienst/ausgabe/category/';
                $issue .= $entry->webEildienst->id . '">Diesen Eildienst anzeigen</a></p>';
                $topic .= '<li>' . $entry->name . ', ' . $entry->webContent->headline . '</li>';
            } else {
                $topic .= '<li>' . $entry->name . ', ' . $entry->webContent->headline . '</li>';
            }
        }
        $rowContent = array();
        $rowContent[] = $issue;
        $rowContent[] = $topic;
        $tableFactory->setHtmlContent($rowContent);
        
        $html = $tableFactory->display();
        return $html;
    }
}