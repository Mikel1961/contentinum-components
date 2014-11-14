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
    public function __invoke(array $content, $medias, $template = null, $teasers = true)
    {
        $html = '';
        foreach ($content['entries'] as $row) {
            $html .= '<h2>' . $row['headline'] . '</h2>';
            if (strlen($row['contentTeaser']) > 1){
                $html .= $row['contentTeaser'];
            } else {
                $content = $row['content'];
                if (strlen($row['numberCharacterTeaser']) > 0){
                    $content = substr($content, 0, $row['numberCharacterTeaser']);
                    $content = substr($content, 0, strrpos($content, " "));
                    $content = $content . ' ...</p>';                    
                }
                $html .= $content;
            }
            $footer = '';
            if (strlen($row['publishAuthor']) > 1 ){
                $footer .= '<p><span>' . $row['publishAuthor'] . '</span>';
            }
            
            if (strlen($row['publishDate']) > 1){
                if (strlen($footer) == 0){
                    $footer .= '<p>';
                }
                $footer .= '<span>' . $row['publishDate'] . '</span>';
            }
            if (strlen($footer) > 1){
                $footer .= '</p>';
            }   
            $html .= $footer;         
        }
        return $html;
    }
}