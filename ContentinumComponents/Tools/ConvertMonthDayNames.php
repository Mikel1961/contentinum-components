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
 * Convert month numbers and weekday numbers in german names
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
class ConvertMonthDayNames
{

    /**
     * German month names
     * 
     * @var array
     */
    protected $monthsname = array(
        '01' => 'Januar',
        '02' => 'Februar',
        '03' => 'März',
        '04' => 'April',
        '05' => 'Mai',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'August',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Dezember'
    );

    /**
     * German day names
     * 
     * @var array
     */
    protected $dayname = array(
        '1' => 'Montag',
        '2' => 'Dienstag',
        '3' => 'Mittwoch',
        '4' => 'Donnerstag',
        '5' => 'Freitag',
        '6' => 'Samstag',
        '7' => 'Sonntag'
    );

    /**
     *
     * @param unknown $value
     * @param string $prop
     */
    public function get($value, $prop = 'monthsname')
    {
        if (isset($this->{$prop}[$value])) {
            return $this->{$prop}[$value];
        } else {
            return $value;
        }
    }
}