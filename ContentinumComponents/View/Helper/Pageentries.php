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
namespace ContentinumComponents\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Set page main content
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class Pageentries extends AbstractHelper
{

	public function __invoke($entry, $entryStructurItems, $entryStructurElements)
	{
		$contribution = '';
		foreach ($entryStructurItems['element'] as $item) {
			switch ($item['type']) {
				case 'get': // get contents and put them into nested html elements
					if ($entryStructurElements->$item['value']) {
						$getEntryStructurElements = $entryStructurElements->$item['value'];
						$sequence = $getEntryStructurElements->sequence->toArray();
						$getItems = $sequence['items']; // sequence entries
						$getElements = $sequence['element']; // sequence nested elements
						$getcontent = $this->view->pageentries($entry, $getItems, $getEntryStructurElements);
						$contribution .= $this->view->pagecontent($getElements, $getEntryStructurElements, $getcontent);
						unset($getItems, $getElements, $getEntryStructurElements, $getcontent);
					}
					break;
				case 'tag': // get content and put into html elements
					if ($entry->$item['value'] && $entryStructurElements->$item['value']) {
						$additional = '';
						$content = array();
						if ($entryStructurElements->$item['value']->including) {
							$including = $entryStructurElements->$item['value']->including->toArray();
							foreach ($including as $key => $tag) {
								if ($entry->$key) {
									$tag = $this->view->tagvalues($entry->$key, $tag);
									$incAttribs = array();
									if (isset($tag['attributes']) && is_array($tag['attributes'])) {
										$incAttribs = $tag['attributes'];
									}
									$additional .= $this->view->taghtml($tag['content'], $tag['tag'], $incAttribs);
								}
							}
						}
						$escape = true;
						if (isset($entryStructurElements->$item['value']->escape)) {
							if ('no' == $entryStructurElements->$item['value']->escape) {
								$escape = false;
							}
						}
		
						$content = $this->view->tagvalues($entry->$item['value'], $entryStructurElements->$item['value']->toArray(), $escape);
						if (! isset($content['attributes'])) {
							$content['attributes'] = array();
						}
						$contribution .= $this->view->taghtml($content['content'], $entryStructurElements->$item['value']->tag, $content['attributes'], $additional);
					}
					break;
				case 'set': // encloses content with html block if set in xml file
					if (isset($entryStructurElements->$item['value']->attributes)) {
						$attributes = $entryStructurElements->$item['value']->attributes->toArray();
					} else {
						$attributes = false;
					}
					if (isset($entryStructurElements->$item['value']->tag)) {
						$setTag = $entryStructurElements->$item['value']->tag;
					} else {
						$setTag = false;
					}
					$contribution = $this->view->taghtml($contribution, $setTag, $attributes);
					break;
				default:
			}
		}
		return $contribution;   
	}

}