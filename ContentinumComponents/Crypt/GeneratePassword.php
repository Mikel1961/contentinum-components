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
 * @package Entity
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Crypt;

/**
 * Password generate
 *
 * @category contentinum library
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum
 *            (http://www.jochum-mediaservices.de)
 * @license http://www.contentinum-library.de/licenses BSD License
 */
class GeneratePassword
{
	public static $letters = array("a" , "b" , "c" , "d" , "e" , "f" , "g" , "h" , "k" , "m" , "n" , "p" , "q" , "r" , "s" , "t" , "u" , "v" , "w" , "x" , "y" , "z", "A" , "B" , "C" , "D" , "E" , "F" , "G" , "H" , "K" , "M" , "N" , "P" , "Q" , "R" , "S" , "T" , "U" , "V" , "W" , "X" , "Y" , "Z");
	public static $numbers = array("2" , "3" , "4" , "5" , "6" , "7" , "8" , "9");
	public static $character = array("#" , "!" , "%" , "&" , "=" , "?");
	/**
	 * Get passwort
	 * @param unknown_type $laenge
	*/
	public static function get ($laenge = 10)
	{
		for ($i = 0, $Passwort = ""; strlen($Passwort) < $laenge; $i ++) {
			if (rand(0, 2) == 0 && isset( self::$letters )) {
				$Passwort .= self::$letters[rand(0, count(self::$letters))];
			} elseif (rand(0, 2) == 1 && isset(self::$numbers)) {
				$Passwort .= self::$numbers[rand(0, count(self::$numbers))];
			} elseif (rand(0, 2) == 2 && isset(self::$character)) {
				$Passwort .= self::$character[rand(0, count(self::$character))];
			}
		}
		return $Passwort;
	}
}