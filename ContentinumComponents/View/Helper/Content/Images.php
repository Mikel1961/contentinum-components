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
use ContentinumComponents\Tools\HandleSerializeDatabase;
use ContentinumComponents\Html\HtmlElements;
use ContentinumComponents\Html\Element\FactoryElement;

class Images extends AbstractHelper
{

    private $sizes = array(
        'max',
        'thumbnail',
        'mobile',
        's',
        'l',
        'xl'
    );

    private $desktop = array(
        's',
        'l',
        'xl'
    );

    private $mobil = array(
        'mobile',
        's'
    );

    public function __invoke($id, $medias, $template = null)
    {
        //var_dump($id);
        //print '<pre>';
        // var_dump($medias[$id]);//->{$id});
        $factory = false;
        if (isset($medias[$id]) && ! empty($medias[$id])) {
            $medias = $medias[$id];
            $src = $medias['mediaLink'];
            
            $unserialize = new HandleSerializeDatabase();
            $mediaAlternate = $unserialize->execUnserialize($medias['mediaAlternate']);
            $mediaMetas = $unserialize->execUnserialize($medias['mediaMetas']);
            
            switch ($this->view->useragent) {
                case 'desktop':
                default:
                    foreach ($this->desktop as $size) {
                        if (isset($mediaAlternate[$size])) {
                            $src = $mediaAlternate[$size]['mediaLink'];
                        }
                    }
                    break;
            }
            
            $img = '<img src="' . $src . '"';
            if ( isset($mediaMetas['alt']) && strlen($mediaMetas['alt']) > 1 ){
                $img .= ' alt="' . $mediaMetas['alt'] . '"';
            }
            if ( isset($mediaMetas['title']) && strlen($mediaMetas['title']) > 1 ){
                $img .= ' title="' . $mediaMetas['title'] . '"';
            }    

            $img .= ' />';
            
            if (null !== $template && isset($template['image'])){
                $template = $template['image'];

                if ( isset($template['element']) ){
         
                    
                    $factory = new HtmlElements(new FactoryElement());
                    $factory->setContentTag($template['element']);
                    if ( isset($template['attr']) ){
                        $factory->setTagAttributtes(false, $template['attr'], 0);
                    }
                    $caption = '';
                    if ( isset($mediaMetas['caption']) && strlen($mediaMetas['caption']) > 1 && $template['caption'] ){
                        $caption .= '<' . $template['caption'];
                        $caption .= '>' . $mediaMetas['caption'];
                        $caption .= '</' . $template['caption'] . '>';
                    }
                    
                    
                    $factory->setHtmlContent($img.$caption);
                    $content = $factory->display();
                }
            } else {
                $content = $img;
            }
            
            
            
            //var_dump($mediaAlternate);
            //var_dump($mediaMetas);
        }
        

            return $content;
     
    }
}