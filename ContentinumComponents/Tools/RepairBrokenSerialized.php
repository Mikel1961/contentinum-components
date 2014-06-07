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
 * Try to rpair a broken serialized string
 * Return the repaird serialized string
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
class RepairBrokenSerialized
{
    
    /**
     * Try repair broken serialize string and
     * return the repaired string or false
     * @param unknown $str
     * @return boolean|Ambigous <NULL, mixed>
     */
    public function tryRepair($str)
    {
    	$str = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $str);
    	if (false === $this->isSerialized($str)){
    		$str = null;
    		return false;
    	}
    	return $str;
    }    

    /**
     * Check is serialized
     * @param string $value
     * @return boolean
     */
    public function isSerialized($value)
    {
        // Bit of a give away this one
        if (! is_string($value)) {
            return false;
        }
        
        // Serialized false, return true. unserialize() returns false on an
        // invalid string or it could return false if the string is serialized
        // false, eliminate that possibility.
        if ('b:0;' === $value) {
            $result = false;
            return true;
        }
        
        $length = strlen($value);
        $end = '';
        
        switch ($value[0]) {
            case 's':
                if ('"' !== $value[$length - 2]) {
                    return false;
                }
            case 'b':
            case 'i':
            case 'd':
                // This looks odd but it is quicker than isset()ing
                $end .= ';';
            case 'a':
            case 'O':
                $end .= '}';
                
                if (':' !== $value[1]) {
                    return false;
                }
                
                switch ($value[2]) {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                        break;
                    
                    default:
                        return false;
                }
            case 'N':
                $end .= ';';
                
                if ($value[$length - 1] !== $end[0]) {
                    return false;
                }
                break;
            
            default:
                return false;
        }
        
        if (false === ($result = @unserialize($value))) {
            $result = null;
            return false;
        }
        return true;
    }
}