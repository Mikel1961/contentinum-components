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
use ContentinumComponents\Filter\Url\Prepare;

class ExpressServices extends AbstractHelper
{

    public function __invoke(array $entry, $medias, $template)
    {
        if ('ausgabe' == $this->view->category){
            return $this->displayAusgabe($entry['modulContent']);
        } else {
            return $this->ausgaben($entry['modulContent']);
        }
    }
    
    protected function displayAusgabe($entries)
    {
        
        
        $html = '<dl id="accordionEidlienst" class="accordion" data-accordion>';
        
        $filter = new Prepare();
        $i = 0;
        $id = false;
        $issue = '';
        $topic = '';
        $panelId = '';
        $headline = '';
        foreach ($entries as $entry) {
            //if (false === $id){
            $dateTime = '<time>' . $this->view->dateFormat(new \DateTime($entry->webEildienst->publishUp), \IntlDateFormatter::FULL) . '</time>';
            $headline = '<h3>' . $entry->webEildienst->name . '</h3><p>' . $dateTime . '</p>';
            //}
        
        
            if ($id != $entry->id && false !== $id){
                $html .= '<dd class="accordion-navigation">';
                $html .= $issue;
                $html .= '<div id="'.$panelId.'" class="content">' . $topic . '</div>';
                $html .= '</dd>';
            }
        
            if ($id != $entry->id){
                $id = $entry->id;
                $topic = '';
                $issue = '';
                $panelId = $filter->filter($entry->name);
        
                $issue = '<a class="eildienst-panel" href="#' . $panelId . '" role="tab" tabindex="0" aria-selected="false" controls="' . $panelId . '"><span class="eildienst-panel-name">' . $entry->name . '</span> <i class="fa fa-arrow-circle-o-down fa-2x right"></i></a>';
        
                $topic .= '<h5>' . $entry->webContent->title . '</h5>';
                $topic .= $entry->webContent->content;
            } else {
                $topic .= '<h5>' . $entry->webContent->title . '</h5>';
                $topic .= $entry->webContent->content;
            }
        }
        
        $html .= '<dd class="accordion-navigation">';
        $html .= $issue;
        $html .= '<div id="'.$panelId.'" class="content">' . $topic . '</div>';
        $html .= '</dd>';
        
        
        
        
        
        $html .= '</dl>';
        $html .= '<p><a class="button" href="/'. $this->view->pageurl.'">Zur&uuml;ck</a></p>';     
        $html = $headline . $html;
        return $html;
    }

    protected function ausgaben($entries)
    {
        $this->view->headLink()->appendStylesheet('/assets/app/css/vendor/datatables/jquery.dataTables.min.css');
        $this->view->headLink()->appendStylesheet('/assets/app/css/vendor/datatables/dataTables.foundation.css');
        $this->view->inlinescript()->offsetSetFile(100, '/assets/app/js/vendor/datatables/jquery.dataTables.min.js');
        $this->view->inlinescript()->offsetSetFile(101, '/assets/app/js/vendor/datatables/dataTables.foundation.js');
        $this->view->inlinescript()->offsetSetFile(102, '/assets/app/js/vendor/datatables/datatable.js');
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
            $columns[] = $this->view->translate($column);
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
        foreach ($entries as $entry) {
            if ($id != $entry->webEildienst->id && false !== $id) {
                $rowContent = array();
                $rowContent[] = $issue;
                $rowContent[] = '<ul>' . $topic . '</ul>';
                $tableFactory->setHtmlContent($rowContent);
            }
            
            if ($id != $entry->webEildienst->id) {
                $topic = '';
                $id = $entry->webEildienst->id;
                $dateTime = '<time>' . $this->view->dateFormat(new \DateTime($entry->webEildienst->publishUp), \IntlDateFormatter::FULL) . '</time>';
                $issue = '<h3>' . $entry->webEildienst->name . '</h3><p>' . $dateTime . '</p><p><a class="button" href="/'.$this->view->pageurl.'/ausgabe/';
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