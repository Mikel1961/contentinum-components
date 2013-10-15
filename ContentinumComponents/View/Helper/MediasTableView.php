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
namespace ContentinumComponents\View\Helper;

use Zend\View\Helper\AbstractHelper;
use ContentinumComponents\Html\HtmlTable;
use ContentinumComponents\Html\Table\FactoryTable;

/**
 * Media files table view
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class MediasTableView extends AbstractHelper
{

    public function __invoke($entries)
    {
        if (empty($entries)) {
            $html = '<p>' . $this->view->translate('No documents or images found') . '</p>';
        } else {
            $tableFactory = new HtmlTable(new FactoryTable());
            $tableFactory->setAttributes('class', 'table table-hover table-nomargin table-bordered');
            $i = 0;
            $iClass = 0;
            $headlines = array(
                'Filename' => array(),
                'Size' => array(
                    'head' => array(
                        'class' => 'hide-for-small text-right'
                    ),
                    'body' => array(
                        'class' => 'hide-for-small text-right'
                    )
                ),
                'Date' => array(
                    'head' => array(
                        'class' => 'hide-for-small text-right'
                    ),
                    'body' => array(
                        'class' => 'hide-for-small text-right'
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
            
            if (null != $this->view->currentFolder) {
                
                foreach ($entries as $entry) {
                    if ('..' == $entry->filename) {
                        
                        $i ++;
                        $rowContent = array();
                        $up = '';
                        if ($this->view->currentFolder) {
                            $array = explode(DS, $this->view->currentFolder);
                            if (null != array_pop($array)) {
                                $up = '/' . implode('_', $array);
                            }
                        }
                        $rowContent[] = '<a href="/mcwork/medias' . $up . '" class="small button"><i class="icon-arrow-up"></i></a>';
                        $rowContent[] = '&nbsp;';
                        $rowContent[] = '&nbsp;';
                        $tableFactory->setHtmlContent($rowContent);
                        break;
                    }
                }
            }
            
            foreach ($entries as $entry) {
                if ('.' != $entry->filename && '..' != $entry->filename && 'dir' == $entry->type) {
                    
                    $i ++;
                    $rowContent = array();
                    $down = $entry->filename;
                    if ($this->view->currentFolder) {
                        $down = $this->view->currentFolder . DS . $entry->filename;
                    }
                    
                    $rowContent[] = '<a href="/mcwork/medias/' . str_replace(DS, '_', $down) . '"><i class="icon-folder-close"></i> ' . $entry->filename . '</a>'; // . $this->mcworkTableEdit ( $tbl );
                    $rowContent[] = '&nbsp;';
                    $rowContent[] = date("d.m.Y H:i:s", $entry->time);
                    $tableFactory->setHtmlContent($rowContent);
                }
            }
            
            foreach ($entries as $entry) {
                if ('.' != $entry->filename && '..' != $entry->filename && 'file' == $entry->type && 'index.html' != $entry->filename) {
                    
                    $i ++;
                    $rowContent = array();
                    
                    $rowContent[] = '<i class="icon-file"></i> ' . $entry->filename; // . $this->mcworkTableEdit ( $tbl );
                    $size = '';
                    if ($entry->width && $entry->height) {
                        $size = '(' . $entry->width . ' x ' . $entry->height . ' px) ';
                    }
                    
                    $rowContent[] = $size . $this->view->filesize($entry->size);
                    $rowContent[] = date("d.m.Y H:i:s", $entry->time);
                    $tableFactory->setHtmlContent($rowContent);
                }
            }
            
            $html = $tableFactory->display();
            
            $element = new \Zend\Form\Element\Hidden('current-folder');
            $element->setAttribute('id', 'current-folder');
            if ($this->view->currentFolder) {
                $element->setValue($this->currentFolder);
            }
            $html .= $this->view->formhidden($element);
            $html .= $this->view->translate($this->view->mcworkContent($this->view->page, $this->view->pagecontent, 'content'));
        }
        return $html;
    }
}