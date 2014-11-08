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
namespace ContentinumComponents\View\Helper\Mcwork;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * Handle content
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class IsAllowed extends AbstractHelper
{

    protected $createdBy = 'created_by';

    protected $updateBy = 'update_by';

    public function __invoke($entry, $identity, $isArray = false)
    {
        if (false === $isArray){
            return $this->validate($entry, $identity);
        } else {
            return $this->validateArray($entry, $identity);
        }
        
    }

    protected function validate($entry, $identity)
    {
        if (1 === $entry->createdBy) {
            return true;
        }
        
        if ('admin' === $this->view->role) {
            return true;
        } else {
            if ($identity->userid === $entry->createdBy) {
                return true;
            } else {
                if ($identity->usergroups && ! empty($identity->usergroups)  ){
                    $groups = $this->view->usergroups->toArray();
                    foreach ($identity->usergroups as $usrgrp){
                        if (isset($groups[$usrgrp]) && in_array($identity->userid, $groups[$usrgrp])){
                            return true;
                        }
                    }
                }
                return false;
            }
        }        
    }
    
    protected function validateArray($entry, $identity)
    {
        if (1 == $entry['created_by']) {
            return true;
        }
    
        if ('admin' == $this->view->role) {
            return true;
        } else {
            if ($identity->userid == $entry['created_by']) {
                return true;
            } else {
                if ($identity->usergroups && ! empty($identity->usergroups)  ){
                    $groups = $this->view->usergroups->toArray();
                    foreach ($identity->usergroups as $usrgrp){
                        if (isset($groups[$usrgrp]) && in_array($identity->userid, $groups[$usrgrp])){
                            return true;
                        }
                    }
                }
                return false;
            }
        }
    }    
}