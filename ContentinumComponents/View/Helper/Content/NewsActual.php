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

class NewsActual extends AbstractHelper
{

    /**
     *
     * @var unknown
     */
    private $row = array(
        'element' => 'ul',
        'attr' => array(
            'class' => 'small-block-grid-1 medium-block-grid-2 large-block-grid-3 account-member-list'
        )
    )
    ;

    /**
     *
     * @var unknown
     */
    private $grid = array(
        'element' => 'li'
    )
    ;

    /**
     *
     * @var unknown
     */
    private $properties = array(
        'row',
        'grid'
    );

    public function __invoke(array $entry, $medias, $template)
    {
        $grid = $this->getTemplateProperty('grid', 'element');
        $url = $entry['modulContent']['url'];
        $html = '';
        foreach ($entry['modulContent']['news'] as $entry) {
            if (0 === $entry->webContent->overwrite) {
                $html .= '<article class="news-article">';
                $head = '<time>' . $this->view->dateFormat(new \DateTime($entry->webContent->publishDate), \IntlDateFormatter::FULL) . '</time>';
                if (strlen($entry->webContent->publishAuthor) > 1) {
                    $head .= '- <span class="news-article-author">' . $entry->webContent->publishAuthor . '</span>';
                }
                $head .= '<h2>' . $entry->webContent->headline . '</h2>';
                $html .= $this->newsheader($head);
                
                if (strlen($entry->webContent->contentTeaser) > 1) {
                    $html .= $entry->webContent->contentTeaser;
                    $html .= $this->readMoreLink($entry,$url);
                } else {
                    $content = $entry->webContent->content;
                    if (strlen($entry->webContent->numberCharacterTeaser) > 0) {
                        $content = substr($content, 0, $entry->webContent->numberCharacterTeaser);
                        $content = substr($content, 0, strrpos($content, " "));
                        $content = $content . ' ...</p>';
                    }
                    $html .= $content;
                    $html .= $this->readMoreLink($entry,$url);
                }
                $html .= '</article>';
            }
        }
        return '<section class="news">' .  $html . '</section>';
    }

    /**
     *
     * @param unknown $row
     * @return string
     */
    protected function readMoreLink($entry,$url)
    {
        if (strlen($entry->webContent->labelReadMore) > 1) {
            
            $readMore = '<p class="news-article-readmore"><a class="button" href="/'.$url.'/'.$entry->webContent->source.'" title="' . $entry->webContent->labelReadMore . ' ' . $entry->webContent->headline . '">';
            $readMore .= $entry->webContent->labelReadMore . '</a></p>';
            
            return $readMore;
        } else {
            return '';
        }
    }

    protected function newsheader($str)
    {
        return '<header class="news-article-header">' . $str . '</header>';
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