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
 * @package Html
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 1.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Html\Table;

/**
 * Interface html table
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
interface InterfaceTable
{

    /**
     * Returns the string of a complete html table
     *
     * @return void
     */
    public function createTable ();

    /**
     * Returns the string of a html table caption
     *
     * @param string $content content of this html tags
     * @param string $attribute a poosible tag attribute
     * @param string $tag the tag
     * @return void
     */
    public function createCaption ($content, $attribute = false, $tag = false);

    /**
     * Returns the string of a complete html element (th, td)
     *
     * @return void
     */
    public function createElement ($tag = false, $attribute = false);

    /**
     * Returns the string of a html table header
     *
     * @return void
     */
    public function createHeader ();

    /**
     * Returns the string of a html table footer
     *
     * @return void
     */
    public function createFooter ();

    /**
     * Returns the string of a html table row with content
     *
     * @param string $content content of this html tags
     * @param string $attribute a poosible tag attribute
     * @param string $tag the tag
     * @return void
     */
    public function createRowElement ($content, $attribute = false, $tag = false);
}