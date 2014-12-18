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

use Zend\Form\View\Helper\AbstractHelper;

class Filegroup extends AbstractHelper
{

    /**
     * 
     * @param array $entry
     * @param unknown $medias
     * @param unknown $template
     * @return string
     */
    public function __invoke(array $entry, $medias, $template)
    {
        $list = '';
        $desc = '';
        foreach ($entry['modulContent'] as $ident => $fileRow) {
            $headline = $fileRow["headline"];
            $desc = $fileRow["description"];
            $list .= '<dd class="filegroup-list-element">';
            
            if (strlen($fileRow['attr']['headline']) > 1) {
                // $list .= '<h4>' . $fileRow['attr']['headline'] . '</h4>';
            }
            
            $list .= '<p><a href="/mcwork/medias/download/' . $ident . '" class="filegroup-list-element-link"';
            
            if (strlen($fileRow['attr']['linkname']) > 1) {
                $label = $fileRow['attr']['linkname'];
            } else {
                $label = $fileRow['mediaName'];
            }
            $list .= ' data-tooltip aria-haspopup="true" class="has-tip tip-top" role="tooltip" title="Zum Download von ' . $label . ' bitte hier klicken">';
            
            switch ($fileRow['mediaType']) {
                case 'application/pdf':
                    $icon = '<i class="fa fa-file-pdf-o"></i>';
                    break;
                case 'application/msexcel':
                    $icon = '<i class="fa fa-file-excel-o"></i>';
                    break;
                default:
                    $icon = '<i class="fa fa-file"></i>';
                    break;
            }
            
            $list .= $icon . ' ' . $label;
            
            $list .= '</a></p>';
            $list .= '</dd>';
        }
        
        $html = '<dl class="filegroup-list"><dt class="filegroup-list-headline">' . $headline . '</dt>';
        if (strlen($desc) > 2) {
            $html .= '<dd class="filegroup-list-description">' . $desc . '</dd>';
        }
        $html .= $list;
        $html .= '</dl>';
        return $html;
    }
}