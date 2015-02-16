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

class NewsActual extends AbstractNewsHelper
{

    const VIEW_TEMPLATE = 'newsactual';

    public function __invoke(array $entries, $medias, $template)
    {
        $viewTemplate = $this->view->groupstyles[$this->getLayoutKey()];
        if (isset($viewTemplate[static::VIEW_TEMPLATE])) {
            $this->setTemplate($viewTemplate[static::VIEW_TEMPLATE]);
        }
        
        $labelReadMore = $this->labelReadMore->toArray();
        
        $url = $entries['modulContent']['url'];
        $html = '';
        foreach ($entries['modulContent']['news'] as $entry) {
            if (0 === $entry->webContent->overwrite) {
                
                $article = '';
                $head = '';
                $head .= $this->deployRow($this->publishDate, $entry->webContent->publishDate);
                if (strlen($entry->webContent->publishAuthor) > 1) {
                    $head .= $this->deployRow($this->publishAuthor, $entry->webContent->publishAuthor);
                }
                
                if (null !== $this->toolbar) {
                    $head .= $this->view->contenttoolbar(array(
                        'pdf' => array(
                            'href' => '/' . $entry->webContent->id
                        )
                    ), $medias, $this->toolbar->toArray());
                }
                
                $head .= $this->deployRow($this->headline, $entry->webContent->headline);
                $article .= $this->deployRow($this->header, $head);
                
                $labelReadMore["grid"]["attr"]['href'] = '/' . $url . '/' . $entry->webContent->source;
                $labelReadMore["grid"]["attr"]['title'] = $entry->webContent->labelReadMore . ' zu ' . $entry->webContent->headline;
                
                if (strlen($entry->webContent->contentTeaser) > 1) {
                    $article .= $entry->webContent->contentTeaser;
                    $article .= $this->deployRow($labelReadMore, $entry->webContent->labelReadMore);
                } else {
                    $content = $entry->webContent->content;
                    if ($entry->webContent->numberCharacterTeaser > 0 && strlen($content) > $entry->webContent->numberCharacterTeaser) {
                        $content = substr($content, 0, $entry->webContent->numberCharacterTeaser);
                        $content = substr($content, 0, strrpos($content, " "));
                        $content = $content . ' ...</p>';
                        $article .= $content;
                        $article .= $this->deployRow($labelReadMore, $entry->webContent->labelReadMore);
                    }
                }
                
                $html .= $this->deployRow($this->news, $article);
            }
        }
        
        if (null !== $this->wrapper) {
            $html = $this->deployRow($this->wrapper, $html);
        }
        
        return $html;
    }
}