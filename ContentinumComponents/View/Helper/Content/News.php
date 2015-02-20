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

use ContentinumComponents\Tools\HandleSerializeDatabase;

class News extends AbstractNewsHelper
{

    /**
     * 
     * @param array $content
     * @param unknown $medias
     * @param string $template
     * @param string $teasers
     * @return string
     */
    public function __invoke(array $content, $medias, $template = null, $teasers = true)
    {
        $viewTemplate = $this->view->groupstyles[$this->getLayoutKey()];
        if (isset($viewTemplate[static::VIEW_TEMPLATE])){
            $this->setTemplate($viewTemplate[static::VIEW_TEMPLATE]);
        }   
        
        $cut = true;
        $labelReadMore = $this->labelReadMore->toArray();

        if (false !== $this->view->article && ! empty($this->view->articlecontent) ){
            foreach($this->view->articlecontent as $entry){
                $this->groupName = $entry->name;
                $this->convertParams($entry->groupParams);
                $entries = $entry->webContent->toArray();
                $entries['medias'] = $entries['webMediasId']->id;
                $content['entries'] = array($entries);  
                break;
            }
            $backLink = $this->backlink->toArray();
            $cut = false; 
        }
        $html = '';
        $i = 0;
        if ('archive' == $this->view->article){
            $this->iTotal = 999;
        }
        foreach ($content['entries'] as $row) {
            $newsrow = '';
            if ('1' === $row['id']) {
                $this->convertParams($row['groupParams']);
                if (isset($this->groupParams['numbernews'])) {
                    $this->iTotal = $this->groupParams['numbernews'];
                }
            }
            if ('1' !== $row['id']) {
                if (null == $this->groupName){
                    $this->groupName = $row['groupName'];
                }

                
                $head = '';
                $head .= $this->deployRow($this->publishDate, $row['publishDate']);
                if (strlen($row['publishAuthor']) > 1) {
                    $head .= $this->deployRow($this->publishAuthor, $row['publishAuthor']);
                }
                
                if (null !== $this->toolbar){
                    $links['pdf'] = array('href' => '/' . $row['id']);
                    $links['facebook'] = array('href' => '?u=' .  urlencode('http://' . $this->view->host . '/' . $this->view->pageurl . '/' . $row['source'])  );                    
                    $head .= $this->view->contenttoolbar($links,$medias, $this->toolbar->toArray());
                
                }
                
                $head .= $this->deployRow($this->headline, $row['headline']);
                $newsrow .= $this->deployRow($this->header, $head);                
                
                
                
                if (true === $cut){
                    
                    $labelReadMore["grid"]["attr"]['href'] = '/' . $this->view->pageurl . '/' . $row['source'];
                    if ($this->view->category){
                        $labelReadMore["grid"]["attr"]['href'] .= '/' . $this->view->category;
                    }                    
                    
                    $labelReadMore["grid"]["attr"]['title'] = $row['labelReadMore'] . ' zu ' .  $row['headline'];                    
                    
                    if (isset($row['medias']) && $row['medias'] > 1) {
                        if ('mediateaserright' == $row['htmlwidgets']){
                            $mediaTemplate = $this->mediateaserright->toArray();
                        } else {
                            $mediaTemplate = $this->mediateaserleft->toArray();
                        }
                        $setSizes = array('landscape' => $this->teaserLandscapeSize, 'portrait' => $this->teaserPortraitSize);
                        $newsrow .= $this->view->images($row, $medias, $mediaTemplate, $setSizes);
                    }                    
                    
                    if (strlen($row['contentTeaser']) > 1) {
                        $newsrow .= $row['contentTeaser'];
                        $newsrow .= $this->deployRow($labelReadMore, $row['labelReadMore']);
                    } else {
                        $content = $row['content'];
                        if ($row['numberCharacterTeaser'] > 0 && strlen($content) > $row['numberCharacterTeaser']) {
                            $content = substr($content, 0, $row['numberCharacterTeaser']);
                            $content = substr($content, 0, strrpos($content, " "));
                            $content = $content . ' ...</p>';
                        }
                        $newsrow .= $content;
                        $newsrow .= $this->deployRow($labelReadMore, $row['labelReadMore']);
                    }
                } else {
                    $newsrow .= $row['contentTeaser'];
                    if (isset($row['medias']) && $row['medias'] > 1) {
                        $newsrow .= $this->view->images($row, $medias, $this->media->toArray());
                    }                    
                    $newsrow .= $row['content'];
                    
                    switch ($row['modul']) {
                        case 'mediagroup':
                            if (isset($this->view->plugins['mediagroup'][$row['id']])) {
                                $plugin = array_merge($row,$this->view->plugins['mediagroup'][$row['id']]);
                                $newsrow .= $this->view->mediagroup($plugin, $medias, $template);
                            }
                            break;  
                        case 'filegroup':
                            if (isset($this->view->plugins['filegroup'][$row['id']])) {
                                $plugin = array_merge($row,$this->view->plugins['filegroup'][$row['id']]);
                                $newsrow .= $this->view->filegroup($plugin, $medias, $template);
                            }
                            break; 
                        default:
                            break;
                    }

                    $backLink["grid"]["attr"]['href'] = '/' . $this->view->pageurl;
                    if ($this->view->category){
                        $backLink["grid"]["attr"]['href'] .= '/archive/' . $this->view->category;
                    }
                    
                    $backLink["grid"]["attr"]['title'] = $this->view->translate('Back');                
                    $foot = $this->deployRow($backLink, $this->view->translate('Back'));
                    
                    if (null !== $this->footer){
                        $newsrow .= $this->deployRow($this->footer, $foot);
                    }
                }
                $html .= $this->deployRow($this->news,$newsrow);
                $i++;
                if ($this->iTotal == $i){
                    break;
                }
            }
        }
        
        if (null !== $this->wrapper){
            $html = $this->deployRow($this->wrapper, $html);
        }
        
        if (isset($this->groupParams['headlineImages']) && strlen($this->groupParams['headlineImages']) > 1){
            $styles = '';
            if (isset($this->groupParams['imageStyles']) && strlen($this->groupParams['imageStyles']) > 1){
                $styles = $this->groupParams['imageStyles'];
            }          
            $html = $this->view->images(array('medias' => $this->groupParams['headlineImages'], 'mediaStyle' => $styles), $medias, $this->media) . $html;
        }
        
        if (isset($this->groupParams['headline']) && strlen($this->groupParams['headline']) > 1){
            $html = '<h1>' . $this->groupParams['headline'] . '</h1>' . $html;
        } else {
            if (null !== $this->groupName){
                $html = '<h1>' . $this->groupName . '</h1>' . $html;
            }
        }
        
        
        return $html;
    }
    
    /**
     * 
     * @param unknown $params
     */
    protected function convertParams($params)
    {
        if (strlen($params) > 4){
            $mcSerialize = new HandleSerializeDatabase();
            $this->groupParams = $mcSerialize->execUnserialize($params);
        } else {
            $this->groupParams = array();
        }
    }
}