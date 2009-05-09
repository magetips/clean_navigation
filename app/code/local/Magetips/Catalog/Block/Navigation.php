<?php
/**
 * Magetips Layout Modifications
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available online at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Magetips
 * @package    Magetips_Layout
 * @author     Simon Young
 * @copyright  Copyright (c) 2009 Simon Young (http://magetips.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * LEGAL DISCLAIMER
 *
 * Please note particularly the disclaimer in section 8 of Open Software License ("OSL") v. 3.0
 *
 * Limitation of Liability. Under no circumstances and under no legal theory, whether in tort
 * (including negligence), contract, or otherwise, shall the Licensor be liable to anyone for
 * any indirect, special, incidental, or consequential damages of any character arising as a
 * result of this License or the use of the Original Work including, without limitation, damages
 * for loss of goodwill, work stoppage, computer failure or malfunction, or any and all other
 * commercial damages or losses. This limitation of liability shall not apply to the extent
 * applicable law prohibits such limitation.
 *
 * EXTENSION INFORMATION
 *
 * This extension creates an additional function to enable the generation of a 'clean'
 * main navigation structure - i.e. removing class names and javascript calls from default
 * Magento drawItem function
 *
 **/

class Magetips_Catalog_Block_Navigation extends Mage_Catalog_Block_Navigation
{

    public function drawItemClean($category, $level=0, $last=false)
    {
        $html = '';
        if (!$category->getIsActive()) {
            return $html;
        }
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = $category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = $children && $childrenCount;
        $html.= '<li';
		$html.= ' id="nav-'.str_replace('/', '-', Mage::helper('catalog/category')->getCategoryUrlPath($category->getRequestPath())).'"';
        $html.= ' class="level'.$level;
        if ($this->isCategoryActive($category)) {
            $html.= ' active';
        }
        if ($last) {
            $html .= ' last';
        }
        if ($hasChildren) {
            $cnt = 0;
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $cnt++;
                }
            }
            $html .= ' parent';
        }
        $html.= '">'."\n";
        $html.= '<a href="'.$this->getCategoryUrl($category).'" title="'.$this->htmlEscape($category->getName()).'">'.$this->htmlEscape($category->getName()).'</a>'."\n";

        if ($hasChildren){

            $j = 0;
            $htmlChildren = '';
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $htmlChildren.= $this->drawItemClean($child, $level+1, ++$j >= $cnt);
                }
            }

            if (!empty($htmlChildren)) {
                $html.= '<ul class="sub-nav" id="sub-nav-'.str_replace('/', '-', Mage::helper('catalog/category')->getCategoryUrlPath($category->getRequestPath())).'">'."\n"
                        .$htmlChildren
                        .'</ul>';
            }

        }
        $html.= '</li>'."\n";
        return $html;
    }

}