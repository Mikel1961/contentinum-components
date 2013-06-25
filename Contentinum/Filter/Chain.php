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
 * @package Filter
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace Contentinum\Filter;

/**
 * Create a list of filter to a filter chain
 * @author mike
 */
class Chain implements FiltersInterface
{
    const CHAIN_APPEND  = 'append';
    const CHAIN_PREPEND = 'prepend';

    /**
     * Filter chain
     *
     * @var array
     */
    protected $_filters = array();
    
    /**
     * Add filter to filter list
     * const CHAIN_APPEND - Add filter at the end of the list
     * const CHAIN_PREPEND - Add filter at the beginning of the list 
     * 
     * @param FiltersInterface $filter
     * @param string $placement use const
     */
    public function addFilter(FiltersInterface $filter, $placement = self::CHAIN_APPEND)
    {
    	if ($placement == self::CHAIN_PREPEND){
    		array_unshift($this->_filters, $filter);
    	} else {
    		$this->_filters[] = $filter;
    	}
    }
    
	/**
	 * Add filter at the end of the list
	 * @param FiltersInterface $filter
	 */
    public function addFilterAppend(FiltersInterface $filter)
    {
    	$this->addFilter($filter, self::CHAIN_APPEND);
    }
    
    /**
     * Add filter at the beginning of the list
     * @param FiltersInterface $filter
     */
    public function addFilterPrepend(FiltersInterface $filter)
    {
    	$this->addFilter($filter,self::CHAIN_PREPEND);
    }
    
    /**
     * @see \Contentinum\Filter\FiltersInterface::filter()
     */
    public function filter($value)
    {
    	$filterValue = $value;
    	foreach ($this->_filters as $filter){
    		$filterValue = $filter->filter($value);
    	}
    	return $filterValue;
    }
}