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



class Maps extends AbstractHelper
{
    private $mapJsFile = '/assets/app/js/modul.maps.js';

    /**
     * 
     * @param unknown $entries
     * @param unknown $medias
     * @param string $template
     * @return string
     */
    public function __invoke($entries, $medias, $template = null)
    {

        $jsMarker = '';
        $i = 0;
        foreach ($entries['modulContent'] as $marker => $entry){
            $headline = $entry->webMaps->headline;
            $centerLatitude = (float) $entry->webMaps->centerlatitude;
            $centerLongitude = (float) $entry->webMaps->centerlongitude;
            $startzoom = (int) $entry->webMaps->startzoom;
            $js = '"' . $entry->name . '",';
            $js .= '"' . (float) $entry->latitude . '",';
            $js .= '"' . (float)  $entry->longitude . '",';
            $js .= '1,';
            $js .= '"",';
            $js .= '"' . $entry->street . '",';
            $js .= '"' . $entry->city . '",';
            $js .= '"","","",""';
            $jsMarker .= '[[' . $js . ']],';
            $i++;
        }
        
        
        
        $script = 'var centerLatitude = '.$centerLatitude.'; var centerLongitude = '.$centerLongitude.'; var startZoom = '.$startzoom.';';
        $script .= 'var mapMarker = ['.$jsMarker.'];';
        $this->view->inlinescript()->appendScript($script);
        $this->view->inlinescript()->appendFile('http://maps.googleapis.com/maps/api/js?sensor=false');
        $this->view->inlinescript()->prependFile($this->mapJsFile);
        $html = '<h2>' . $headline . '</h2>';
        $html .= '<div id="map_canvas"> </div>';
        return $html;        
    }    
}