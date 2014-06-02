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
 * @package Storage
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 3.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Path;

/**
 * Function to strip additional / or \ in a path name
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
class Permissions implements InterfacePath
{
    /**
     * Get the permissions of the file/folder at a give path
     *
     * @param	string	$path path of a file/folder
     * @return	string	Filesystem permissions
     */
    public static function get ($path, $cfg = null)
    {
    	$path = Clean::get($path);
    	$mode = @ decoct(@ fileperms($path) & 0777);
    	if (strlen($mode) < 3) {
    		return '---------';
    	}
    	$parsed_mode = '';
    	for ($i = 0; $i < 3; $i ++) {
    		// read
    		$parsed_mode .= ($mode{$i} & 04) ? "r" : "-";
    		// write
    		$parsed_mode .= ($mode{$i} & 02) ? "w" : "-";
    		// execute
    		$parsed_mode .= ($mode{$i} & 01) ? "x" : "-";
    	}
    	return $parsed_mode;
    }    
}