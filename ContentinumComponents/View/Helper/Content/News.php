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

class News extends AbstractHelper
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

    /**
     *
     * @var unknown
     */
    private $header;

    /**
     *
     * @var unknown
     */
    private $footer;

    /**
     *
     * @var unknown
     */
    private $publishAuthor;

    /**
     *
     * @var unknown
     */
    private $labelReadMore;

    private $properties = array(
        'row',
        'grid',
        'header',
        'footer',
        'publishAuthor',
        'labelReadMore'
    );

    public function __invoke(array $content, $medias, $template = null, $teasers = true)
    {
        $this->setTemplate($template);
        
        $header = $this->getTemplateProperty('header', 'row');
        $footer = $this->getTemplateProperty('footer', 'row');
        
        $newselement = $this->getTemplateProperty('row', 'element');
        $grid = $this->getTemplateProperty('grid', 'element');
        $cut = true;
        if (false !== $this->view->article && isset($content['entries'][$this->view->article]) ){
            $content['entries'] = array($content['entries'][$this->view->article]);
            $cut = false; 
        }
        
        $html = '';
        
        if (isset($content['groupName']) && '_default' !== $content['groupName']){
            $html .= '<h1>' . $content['groupName'] . '</h1>';
        }
        
        foreach ($content['entries'] as $row) {
            $newsrow = '';
            if ('1' !== $row['id']) {
                $head = '<time>' . $this->view->dateFormat(new \DateTime($row['publishDate']), \IntlDateFormatter::FULL) . '</time>';
                $head .= '<h2>' . $row['headline'] . '</h2>';
                $attr = array();
                if ($header) {
                    if (isset($header['attr'])) {
                        $attr = $header['attr'];
                    }
                    $newsrow .= $this->view->contentelement($header['element'], $head, $attr);
                    $attr = array();
                } else {
                    $newsrow .= $head;
                }
                if (true === $cut){
                    if (strlen($row['contentTeaser']) > 1) {
                        $newsrow .= $row['contentTeaser'];
                        $newsrow .= $this->readMoreLink($row);
                    } else {
                        $content = $row['content'];
                        if (strlen($row['numberCharacterTeaser']) > 0) {
                            $content = substr($content, 0, $row['numberCharacterTeaser']);
                            $content = substr($content, 0, strrpos($content, " "));
                            $content = $content . ' ...</p>';
                        }
                        $newsrow .= $content;
                        $newsrow .= $this->readMoreLink($row);
                    }
                } else {
                    $newsrow .= $row['contentTeaser'];
                    $newsrow .= $row['content'];
                }
                
                
                if ($footer) {
                    if (isset($footer['attr'])) {
                        $attr = $footer['attr'];
                    }
                    $newsrow .= $this->view->contentelement($footer['element'], $this->formatPublishAuthor($row), $attr);
                    if (false === $cut){
                        $newsrow .= $this->backLink($row);
                    }
                    $attr = array();
                } else {
                    $newsrow .= $this->formatPublishAuthor($row);
                    if (false === $cut){
                        $newsrow .= $this->backLink($row);
                    }                    
                }                
                $attr = array();
                if ($grid){
                    if (false !== $this->getTemplateProperty('grid', 'attr')){
                        $attr = $this->getTemplateProperty('grid', 'attr');
                    }
                    $html .= $this->view->contentelement($grid, $newsrow, $attr );
                } else {
                    $html .= $newsrow;
                }
                
            }
        }
        
        if (false !== $newselement){
            $html = $this->view->contentelement($newselement, $html, $this->getTemplateProperty('row', 'attr'));
        }
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

    protected function setTemplate($template)
    {
            foreach ($template as $key => $values) {
                if (in_array($key, $this->properties)) {
                    $this->{$key} = $values;
                }
            }
    }

    /**
     *
     * @param unknown $row
     * @return unknown
     */
    protected function formatPublishAuthor($row)
    {
        if (strlen($row['publishAuthor']) > 1) {
            if ($this->publishAuthor) {
                $elm = $this->getTemplateProperty('publishAuthor', 'row');
                $grid = $this->getTemplateProperty('publishAuthor', 'grid');
                $publishAuthor = $row['publishAuthor'];
                if (isset($grid['element'])) {
                    if (isset($grid['attr'])) {
                        $attr = $grid['attr'];
                    }
                    $publishAuthor = $this->view->contentelement($grid['element'], $publishAuthor, $attr);
                    $attr = array();
                }
                if (isset($elm['attr'])) {
                    $attr = $elm['attr'];
                }
                $publishAuthor = $this->view->contentelement($elm['element'], $publishAuthor, $attr);
            } else {
                $publishAuthor = $row['publishAuthor'];
            }
            return $publishAuthor;
        } else {
            return '';
        }
    }
    
    /**
     *
     * @param unknown $row
     * @return string
     */
    protected function backLink($row)
    {
            if ($this->labelReadMore) {
                $elm = $this->getTemplateProperty('labelReadMore', 'row');
                $grid = $this->getTemplateProperty('labelReadMore', 'grid');
                $attr['href'] = '/'. $this->view->pageurl;
                if ($this->view->category){
                    $attr['href'] .= '/archive/' . $this->view->category;
                }
                $attr['title'] = 'Back';
                $readMore = 'Back';
                if (isset($grid['element'])) {
                    if (isset($grid['attr'])) {
                        $attr = array_merge($grid['attr'], $attr);
                    }
                    $readMore = $this->view->contentelement($grid['element'], $readMore, $attr);
                    $attr = array();
                }
                if (isset($elm['attr'])) {
                    $attr = $elm['attr'];
                }
                $readMore = $this->view->contentelement($elm['element'], $readMore, $attr);
            } else {
                $href = '/'. $this->view->pageurl;
                if ($this->view->category){
                    $href .= '/archive/' . $this->view->category;
                }                
                $readMore = '<a href="' . $href . '" title="Back">'. $this->view->translate('Back') .'</a>';
            }
            return $readMore;
    }    

    /**
     *
     * @param unknown $row
     * @return string
     */
    protected function readMoreLink($row)
    {
        if (strlen($row['labelReadMore']) > 1) {
            if ($this->labelReadMore) {
                $elm = $this->getTemplateProperty('labelReadMore', 'row');
                $grid = $this->getTemplateProperty('labelReadMore', 'grid');
                $attr['href'] = '/'. $this->view->pageurl . '/' . $row['source'];
                if ($this->view->category){
                    $attr['href'] .= '/' . $this->view->category;
                }
                $attr['title'] = $row['labelReadMore'] . ' ' . $row['headline'];
                $readMore = $row['labelReadMore'];
                if (isset($grid['element'])) {
                    if (isset($grid['attr'])) {
                        $attr = array_merge($grid['attr'], $attr);
                    }
                    $readMore = $this->view->contentelement($grid['element'], $readMore, $attr);
                    $attr = array();
                }
                if (isset($elm['attr'])) {
                    $attr = $elm['attr'];
                }
                $readMore = $this->view->contentelement($elm['element'], $readMore, $attr);
            } else {
                $readMore = '<a href="/' . $row['source'] . '" title="' . $row['labelReadMore'] . ' ' . $row['headline'] . '">';
                $readMore .= $row['labelReadMore'] . '</a>';
            }
            return $readMore;
        } else {
            return '';
        }
    }
}