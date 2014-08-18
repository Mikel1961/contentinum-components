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

    CONST MEDIA_DIR_PATH = '/mcwork/medias/file';

    public function __invoke($entries, $mediasTable = array())
    {
        if (empty($entries)) {
            $html = '<p>' . $this->view->translate('No documents or images found') . '</p>';
        } else {
            $url = 'http://'.$this->view->host.'/mcwork/medias/download/';
            $disablefolder = $this->view->disablefolder;
            if (! is_array($disablefolder)){
                $disablefolder = array('_alternate');
            }
            $cddownload = '';
            if ($this->view->currentFolder) {
                $cddownload = str_replace(DS, $this->view->seperator, $this->view->currentFolder);
            }
            $tableFactory = new HtmlTable(new FactoryTable());
            $tableFactory->setAttributes('class', 'table table-hover table-nomargin table-bordered');
            $i = 0;
            $iClass = 0;
            $headlines = array(
                '#' => array(),
                'Filename' => array(
                    'body' => array(
                        'class' => 'filename'
                    )
                ),
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
                ),
                '&nbsp;' => array(
                    'head' => array(
                        'class' => 'cellToolbar'
                    ),
                    'body' => array(
                        'class' => 'cellToolbar'
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
                        $rowContent[] = '&nbsp;';
                        $up = '';
                        if ($this->view->currentFolder) {
                            $array = explode(DS, $this->view->currentFolder);
                            if (null != array_pop($array)) {
                                $up = implode($this->view->seperator, $array);
                            }
                        }
                        $rowContent[] = '<a href="' . self::MEDIA_DIR_PATH . '/' . $up . '" class="small button"><i class="fa fa-arrow-up"></i></a>';
                        $rowContent[] = '&nbsp;';
                        $rowContent[] = '&nbsp;';
                        $rowContent[] = '&nbsp;';
                        $tableFactory->setHtmlContent($rowContent);
                        break;
                    }
                }
            }
            
            foreach ($entries as $entry) {
                if ('.' != $entry->filename && '..' != $entry->filename && ! in_array($entry->filename, $disablefolder)  && 'dir' == $entry->type ) {
                    
                    $i ++;
                    $rowContent = array();
                    $dataAttribInUse = 'data-inuse="0"';
                    $label = '';
                    $keys = preg_grep('/' . $entry->filename . '/', array_keys($mediasTable));
                    foreach ($keys as $values) {
                    	if (isset($mediasTable[$values]) && 1 == $mediasTable[$values]['mediaInUse']) {
                    		$label = '<span class="label round alert">contains files in use</span>';
                    		$dataAttribInUse = 'data-inuse="1"';
                    		break;
                    	}
                    }                    
                    
                    $rowContent[] = '<input type="checkbox" value="' . $entry->filename . '" name="cb[]" data-type="dir" '.$dataAttribInUse.'>';
                    $down = $entry->filename;
                    if ($this->view->currentFolder) {
                        $down = $this->view->currentFolder . DS . $entry->filename;
                    }
                    
                    if (true == $entry->childs){
                        $icon = '<i class="fa fa-folder-open"></i>';
                    } else {
                        $icon = '<i class="fa fa-folder"></i>';
                    }
                                      
                    $rowContent[] = '<a href="' . self::MEDIA_DIR_PATH . '/' . str_replace(DS, $this->view->seperator, $down) . '">'. $icon .' ' . $entry->filename . '</a>' . $label . $this->numbersItems($entry->counts);
                    $rowContent[] = '&nbsp;';
                    $rowContent[] = date("d.m.Y H:i:s", $entry->time);
                    
                    $btn = '<button class="tbl-info tiny" data-time="' . date("d.m.Y H:i:s", $entry->time) . '" ';
                    $btn .= $dataAttribInUse . ' ';
                    $btn .= 'data-ident="folder" ';
                    
                    if (true == $entry->childs){
                        $btn .= 'data-childs="y" ';
                    } else {
                    	$btn .= 'data-childs="n" ';
                    }
                    
                    $btn .= 'data-originalname="' . $entry->filename . '" ';
                    $btn .= 'data-crypt="' . $entry->filename . '" data-name="' . $entry->filename . '" ';
                    $btn .= 'data-type="dir" type="button"><i class="fa fa-gear"></i></button>';
                    $rowContent[] = $btn;
                    $tableFactory->setHtmlContent($rowContent);
                }
            }
            
            foreach ($entries as $entry) {
                if ('.' != $entry->filename && '..' != $entry->filename && 'file' == $entry->type && 'index.html' != $entry->filename) {
                    
                    $i ++;
                    $rowContent = array();
                    $label = '';
                    $pathname = \ContentinumComponents\Path\Clean::get($entry->pathname);
                    $compareItem = str_replace($this->view->docroot, '', $pathname);
                    if (isset($mediasTable[$compareItem]) && 1 == $mediasTable[$compareItem]['mediaInUse']) {
                    	$label = '<span class="label round alert">In use</span>';
                    	$dataAttribInUse = 'data-inuse="1"';
                    } else {
                        $dataAttribInUse = 'data-inuse="0"';
                    }  
                    $dataIdent = '';
                    if (isset($mediasTable[$compareItem]) && isset($mediasTable[$compareItem]['id']) ){
                    	$dataIdent = 'data-ident="' . $mediasTable[$compareItem]['id'] . '" ';
                    }                    
                    
                    
                    $rowContent[] = '<input type="checkbox" value="' . $entry->filename . '" name="cb[]" data-type="file" '.$dataIdent.$dataAttribInUse.'>';
                    
                    switch ($entry->mimetype) {
                        case 'application/zip':
                            $icon = '<i class="fa fa-archive"></i> ';
                            break;
                        case 'image/jpeg':
                            $icon = '<i class="fa fa-picture-o"></i> ';
                            break;
                        default:
                            $icon = '<i class="fa fa-file"></i> ';
                    }
                    
                    switch ($entry->extension) {
                        case 'wmv':
                        case 'mp4':
                        case 'ogv':
                        case 'webm':
                            $icon = '<i class="fa-film"></i> ';
                            break;
                        case 'jpeg':
                        case 'jpg':
                        case 'png':
                        case 'JPG':
                        case 'JPEG':
                            $icon = '<i class="fa fa-picture-o"></i> ';
                            break;
                        default:
                    }

                    
                    $rowContent[] = $icon . $entry->filename . $label;
                    $size = '';
                    if ($entry->width && $entry->height) {
                        $size = '(' . $entry->width . ' x ' . $entry->height . ' px) ';
                    }
                    $filesize = $this->view->filesize($entry->size);
                    $rowContent[] = $size . $filesize;
                    $rowContent[] = date("d.m.Y H:i:s", $entry->time);
                    
                    $btn = '<button class="tbl-info tiny" data-time="' . date("d.m.Y H:i:s", $entry->time) . '" ';
                    $btn .= $dataIdent;
                    if (isset($mediasTable[$compareItem]) && isset($mediasTable[$compareItem]['mediaName']) ){
                    	$btn .= 'data-originalname="' . $mediasTable[$compareItem]['mediaName'] . '" ';
                    }  
                    $btn .= $dataAttribInUse . ' ';                  
                    $btn .= 'data-size="' . $filesize . '" ';
                    $btn .= 'data-crypt="' . $entry->filename . '" data-name="' . $entry->filename . '" ';
                    $btn .= 'data-link="' . $pathname . '" ';
                    $btn .= 'data-childs="file" ';
                    $btn .= 'data-download="' . $url . $entry->filename . '/' . $cddownload . '" ';
                    $btn .= 'data-type="' . $entry->mimetype . '" type="button"><i class="fa fa-gear"></i></button>';
                    
                    $rowContent[] = $btn;
                    $tableFactory->setHtmlContent($rowContent);
                }
            }
            $html = $tableFactory->display();
            $element = new \Zend\Form\Element\Hidden('current-folder');
            $element->setAttribute('id', 'current-folder');
            if ($this->view->currentFolder) {
                $element->setValue($this->view->currentFolder);
            }
            $html .= $this->view->formhidden($element);
        }
        return $html;
    }
    
    /**
     * Directory summary
     * @param array $counts
     */
    private function numbersItems($counts)
    {
        $str = '';
        if (is_array($counts) && ! empty($counts)){
            $str = '<br /><em class="number-folder-items">';
            foreach ($counts as $k => $v){
                switch ($k){
                	case 'all':
                	    $str .= ' ' . $this->view->translate('item_num_total') . ': ' . $v . ',';
                	    break;
                	case 'directorys':
                	    $str .= ' ' . $this->view->translate('Directorys') . ': ' . $v . ',';
                	    break;
                	case 'files':
                	    $str .= ' ' . $this->view->translate('Files') . ': ' . $v . ',';
                	    break;
                	case 'links':
                	    $str .= ' ' . $this->view->translate('Symlinks') . ': ' . $v . ',';
                	    break;
                	case 'size':
                	    $str .= ' ' . $this->view->translate('file_size_total') . ': ' . $this->view->filesize($v);
                	    break;
                	default:
                	    break;
                }
            }
            $str .= '</em>';
        }
        return $str;
    }
}