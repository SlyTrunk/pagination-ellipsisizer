<?php

class Ellipsisizer {

    public static function ellipsisize($selected, 
                                       $listItems, 
                                       $maxItemCount, 
                                       $jsEnabled = false, 
                                       $itemKeyPrefix = "page-") {

        $pageCount = 0;
        foreach ($listItems as $key => $listItem) {
            if (strpos($key, $itemKeyPrefix) !== false) {
                $pageCount++;
            }
        }

        // Get the indices of the window bounding the selected item
        $lastItemIndex = ($pageCount - 1);
        $windowIndices = self::getItemEllipsisWindowIndices(substr($selected, strlen($itemKeyPrefix)), 
                                                            $lastItemIndex, 
                                                            $maxItemCount);
        $hasEllipses = (bool) $windowIndices && is_array($windowIndices) && count($windowIndices) == 2;
        if (!$hasEllipses) {
            return $listItems;
        }
        list($selectedWindowLowerBoundIndex, $selectedWindowUpperBoundIndex) = $windowIndices;

        $prevHidden = false;
        foreach ($listItems as $key => &$listItem) {
            $ellipsisClasses = array();
            $itemIndex = substr($key, strlen($itemKeyPrefix));
            if (!self::isItemVisible($itemIndex, 
                                     $lastItemIndex, 
                                     $selectedWindowLowerBoundIndex, 
                                     $selectedWindowUpperBoundIndex)) {
                $hiddenClass = 'hidden';
                if (!$prevHidden) {
                    // First hidden entry - make it an ellipsis
                    $hiddenClass = 'ellipsis';
                    $prevHidden = true;
                }
                $ellipsisClasses[] = $hiddenClass;
            }
            else {
                $prevHidden = false;
            }

            if ($jsEnabled) {
                // Classes to enable us to update ellipses via JS
                list($itemWinL, $itemWinU) = self::getItemEllipsisWindowIndices($itemIndex, 
                                                                                $lastItemIndex, 
                                                                                $maxItemCount);
                $ellipsisClasses[] = 'ellipL-' . $itemWinL;
                $ellipsisClasses[] = 'ellipU-' . $itemWinU;
                $ellipsisClasses[] = 'ellipM-' . $lastItemIndex;
            }
            $listItem['itemAttributes']['class'] = implode(' ', $ellipsisClasses);
        }
        return $listItems;
    }

    /*
     * Determines whether the item will be visible, given the indices of the
     * window of visible nav items surrounding the selected item
     *
     * @return  boolean
     */
    protected static function isItemVisible ($itemIndex, $lastItemIndex, 
                                             $selectedWindowLowerBoundIndex, 
                                             $selectedWindowUpperBoundIndex) {

        if ($itemIndex == 0 || $itemIndex == $lastItemIndex) {
            // First or last item
            return true;
        }
        if ($itemIndex >= $selectedWindowLowerBoundIndex && 
            $itemIndex <= $selectedWindowUpperBoundIndex) {
            // In the window surrounding the selected item
            return true;
        }

        return false;
    }

    /*
     * Provides the indices describing a window of items surrounding the input item,
     * useful for inserting ellipses into pagination navigations.
     * Configurable via the maxItemCount to abbreviate the navigation more or less.
     *
     * @param   int $itemIndex     The index of the item we're inquiring about.
     *                             Think of this as the index of the seleted item
     *                             in the list
     * @param   int $lastItemIndex The index of the last item in the list
     * @param   int $maxItemCount  The desired number of items viewable following the introduction of ellipses
     * @return  array/boolean      Containing two ints:
     *                              [0] => (int) the index of the lower bound of the window
     *                              [1] => (int) the index of the upper bound of the window
     *                             Or <bool>false if the data describes a scenario that doesn't
     *                             need ellipses
     */
    protected static function getItemEllipsisWindowIndices ($itemIndex, $lastItemIndex, $maxItemCount) {

        if (!$maxItemCount || $maxItemCount < 3) {
            // Not supported, we have to have at least 3 items visible
            // 1st, selected, and last
            return false;
        }
        if ($lastItemIndex < $maxItemCount) {
            // Too few items for ellipsis
            return false;
        }

        // One for each of: selected item, 1st item, last item
        $deductions = 3;
        if ($itemIndex == 0 || $itemIndex == $lastItemIndex) {
            // Selected item is the same as the first || last item
            $deductions--;
        }

        $windowSize = $maxItemCount - $deductions;
        $windowSizeToLeftOfSelected  = (int) floor($windowSize / 2);
        $windowSizeToRightOfSelected = (int)  ceil($windowSize / 2);

        $selectedWindowLowerBoundIndex = $itemIndex - $windowSizeToLeftOfSelected;
        $selectedWindowUpperBoundIndex = $itemIndex + $windowSizeToRightOfSelected;

        // If the window encompasses the first or last item, and the
        // selected item is neither of those, the size of the window
        // may grow:
        $windowGrowth = 0;
        if (!($itemIndex == 0 || $itemIndex == $lastItemIndex)) {
            $windowGrowth = (floor(($windowSize + 1) / 2) - $windowSizeToLeftOfSelected) + 
                (ceil(($windowSize + 1) / 2) - $windowSizeToRightOfSelected);
        }

        if ($selectedWindowLowerBoundIndex <= 0) {
            // Add excess to upperbound, and set lower to 0;
            // The first item is now included in the window
            $selectedWindowUpperBoundIndex = 
                min($lastItemIndex, 
                    $selectedWindowUpperBoundIndex + (0 - $selectedWindowLowerBoundIndex) + $windowGrowth);
            $selectedWindowLowerBoundIndex = 0;
        }
        if ($selectedWindowUpperBoundIndex >= $lastItemIndex) {
            // Add excess to lowerbound, and set to lastItemIndex
            // The last item is now included in the window
            $selectedWindowLowerBoundIndex = 
                max(0, 
                    $selectedWindowLowerBoundIndex - ($selectedWindowUpperBoundIndex - $lastItemIndex) - $windowGrowth);
            $selectedWindowUpperBoundIndex = $lastItemIndex;
        }

        return array($selectedWindowLowerBoundIndex,
                     $selectedWindowUpperBoundIndex);
    }

}

?>
