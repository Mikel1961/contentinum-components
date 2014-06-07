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
 * Secoud possibility to repair a broken serialized string
 * Return the repaird serialized string
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
class RepairSerializedArray
{
    /**
     * Extract what remains from an unintentionally truncated serialized string
     *
     * Example Usage:
     *
     * the native unserialize() function returns false on failure
     * $data = @unserialize($serialized); // @ silences the default PHP failure notice
     * if ($data === false) // could not unserialize
     * {
     *   $data = repairSerializedArray($serialized); // salvage what we can
     * }
     *
     * $data contains your original array (or what remains of it).
    
     * @param string The serialized array
     */
    public function repair($serialized)
    {
    	$tmp = preg_replace('/^a:\d+:\{/', '', $serialized);
    	return $this->repairSerializedArray_R($tmp); // operates on and whittles down the actual argument
    }
    
    /**
     * The recursive function that does all of the heavy lifing. Do not call directly.
     * @param string The broken serialzized array
     * @return string Returns the repaired string
     */
    private function repairSerializedArray_R(&$broken)
    {
    	// array and string length can be ignored
    	// sample serialized data
    	// a:0:{}
    	// s:4:"four";
    	// i:1;
    	// b:0;
    	// N;
    	$data       = array();
    	$index      = null;
    	$len        = strlen($broken);
    	$i          = 0;
    
    	while(strlen($broken))
    	{
    		$i++;
    		if ($i > $len)
    		{
    			break;
    		}
    
    		if (substr($broken, 0, 1) == '}') // end of array
    		{
    			$broken = substr($broken, 1);
    			return $data;
    		}
    		else
    		{
    			$bite = substr($broken, 0, 2);
    			switch($bite)
    			{
    				case 's:': // key or value
    					$re = '/^s:\d+:"([^\"]*)";/';
    					if (preg_match($re, $broken, $m))
    					{
    						if ($index === null)
    						{
    							$index = $m[1];
    						}
    						else
    						{
    							$data[$index] = $m[1];
    							$index = null;
    						}
    						$broken = preg_replace($re, '', $broken);
    					}
    					break;
    
    				case 'i:': // key or value
    					$re = '/^i:(\d+);/';
    					if (preg_match($re, $broken, $m))
    					{
    						if ($index === null)
    						{
    							$index = (int) $m[1];
    						}
    						else
    						{
    							$data[$index] = (int) $m[1];
    							$index = null;
    						}
    						$broken = preg_replace($re, '', $broken);
    					}
    					break;
    
    				case 'b:': // value only
    					$re = '/^b:[01];/';
    					if (preg_match($re, $broken, $m))
    					{
    						$data[$index] = (bool) $m[1];
    						$index = null;
    						$broken = preg_replace($re, '', $broken);
    					}
    					break;
    
    				case 'a:': // value only
    					$re = '/^a:\d+:\{/';
    					if (preg_match($re, $broken, $m))
    					{
    						$broken         = preg_replace('/^a:\d+:\{/', '', $broken);
    						$data[$index]   = $this->repairSerializedArray_R($broken);
    						$index = null;
    					}
    					break;
    
    				case 'N;': // value only
    					$broken = substr($broken, 2);
    					$data[$index]   = null;
    					$index = null;
    					break;
    			}
    		}
    	}
    
    	return $data;
    }    
}