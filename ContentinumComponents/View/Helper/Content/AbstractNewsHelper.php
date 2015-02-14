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


abstract class AbstractNewsHelper extends AbstractContentHelper
{
    const VIEW_TEMPLATE = 'news';

    /**
     *
     * @var array
     */
    private $row;
    
    /**
     *
     * @var array
     */
    private $grid;
    
    /**
     *
     * @var array
     */
    private $header;
    
    /**
     *
     * @var array
     */
    private $footer;
    
    /**
     *
     * @var array
     */
    private $media;
    
    /**
     *
     * @var array
     */
    private $mediateaser;
    
    /**
     *
     * @var array
     */
    private $mediateaserleft;
    
    /**
     *
     * @var array
     */
    private $mediateaserright;
    
    /**
     *
     * @var array
     */
    private $publishAuthor;
    
    /**
     *
     * @var array
     */
    private $groupParams;
    
    /**
     *
     * @var string
     */
    private $groupName;
    
    /**
     *
     * @var array
     */
    private $labelReadMore;
    
    /**
     *
     * @var integer
     */
    private $teaserLandscapeSize;
    
    /**
     *
     * @var integer
     */
    private $teaserPortraitSize;
    
    /**
     *
     * @var integer
     */
    private $iTotal = 10;
    
    /**
     * 
     * @var unknown
     */
    private $toolbar;
    
    /**
     *
     * @var array
     */
    private $properties = array(
        'row',
        'grid',
        'header',
        'footer',
        'media',
        'mediateaserleft',
        'mediateaserright',
        'publishAuthor',
        'labelReadMore',
        'teaserLandscapeSize',
        'teaserPortraitSize',
        'toolbar'
    );    
}