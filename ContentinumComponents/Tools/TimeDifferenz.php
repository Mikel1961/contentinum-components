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
 * @package Tools
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Tools;


/**
 * time differenz
 *
 * @category   Contentinum
 * @package    Html
 * @author     Michael Jochum <michael.jochum@jochum-mediaservices.de>
 * @copyright  Copyright (c) 2005-2008 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license    http://www.contentinum.de
 * See COPYRIGHT.php for copyright details.
 */
class TimeDifferenz
{
    /**
     * Timestamp
     * @var int
     */
    protected $_first = 0;
    /**
     * Timestamp
     * @var int
     */
    protected $_second = 0;
    /**
     * Construct
     * @param int $first timestamp
     * @param int $second timestamp
     */
    public function __construct ($first, $second)
    {
        $this->_first = $first;
        $this->_second = $second;
    }
    /**
     * Caluculate and set labels
     */
    public function render()
    {
        return $this->makeDifferenz($this->_first,$this->_second);
    }
    /**
     * Calculate and set results in array for day, hour, min and sec
     * @param int $first sec/timestamp
     * @param int $second sec/timestamp
     */
    public function makeDifferenz ($first, $second)
    {
        $td = array();
        if ($first > $second) {
            $td['dif'][0] = $first - $second;
        } else {
            $td['dif'][0] = $second - $first;
        }
        $td['sec'][0] = $td['dif'][0] % 60; // 67 = 7
        $td['min'][0] = (($td['dif'][0] - $td['sec'][0]) / 60) % 60;
        $td['std'][0] = (((($td['dif'][0] - $td['sec'][0]) / 60) - $td['min'][0]) / 60) % 24;
        $td['day'][0] = floor(((((($td['dif'][0] - $td['sec'][0]) / 60) - $td['min'][0]) / 60) / 24));
        $td = $this->setLabels($td);
        return $td;
    }
    /**
     * set labels
     * @param array $td
     */
    public function setLabels ($td)
    {
        if ($td['sec'][0] == 1) {
            $td['sec'][1] = 'Sekunde';
        } else {
            $td['sec'][1] = 'Sekunden';
        }
        if ($td['min'][0] == 1){
            $td['min'][1] = 'Minute';
        } else {
            $td['min'][1] = 'Minuten';
        }
        if ($td['std'][0] == 1) {
            $td['std'][1] = 'Stunde';
        } else {
            $td['std'][1] = 'Stunden';
        }
        if ($td['day'][0] == 1) {
            $td['day'][1] = 'Tag';
        } else {
            $td['day'][1] = 'Tage';
        }
        return $td;
    }
    /**
     * Set a time differenz string
     * @param array $td
     */
    public function setString($td, $spacer = ' ',$sec = false)
    {
        $str = '';
        if ( isset($td['day'][0]) && $td['day'][0] > 0 ){
            $str = $str . $td['day'][0] . ' ' . $td['day'][1] . $spacer;
        }
        if ( isset($td['std'][0]) && $td['std'][0] > 0 ){
            $str = $str . $td['std'][0] . ' ' . $td['std'][1] . $spacer;
        }
        if ( isset($td['min'][0]) && $td['min'][0] > 0 ){
            $str = $str . $td['min'][0] . ' ' . $td['min'][1];
        }
        if ( isset($td['sec'][0]) && $td['sec'][0] > 0 && false !== $sec){
            $str = $str . $spacer . $td['sec'][0] . ' ' . $td['sec'][1];
        }
        return $str;
    }
}