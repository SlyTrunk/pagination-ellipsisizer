Pagination Ellipsisizer!
========================

A PHP utility to annotate an ordered list (think list of page links/pagination) with ellipses.

Overview
----

We commonly need to add ellipses to the navigation of paginated data. I couldn't find a nice utility, so I made one.

Features/behavior:

* The number of visible items is customizable
* The first item is always visible
* The last item is always visible
* The remaining visible items will surround the selected item, favoring looking forwards
* Optional annotations to enable simple client side (JS) ellipsis manipulation

``` html
15 items, 7 items visible, selected item in square brackets:

[1] 2 3 4 5 6 ... 15

1 2 3 [4] 5 6 ... 15

1 ... 3 4 [5] 6 7 ... 15

1 ... 5 6 [7] 8 9 ... 15

1 ... 9 10 [11] 12 13 ... 15

1 ... 10 11 12 13 14 [15]
```

``` html
15 items, 6 items visible, selected item in square brackets:

[1] 2 3 4 5 ... 15

1 ... 3 [4] 5 6 ... 15

1 ... 4 [5] 6 7 ... 15

1 ... 6 [7] 8 9 ... 15

1 ... 10 [11] 12 13 ... 15

1 ... 11 12 13 14 [15]
```

Usage
-----

* Put your navigation items in an array. Make sure each item is keyed into the array using a common prefix and integer index, zero based. E.g. my prefix could be "navItem", and my keys would look like "navItem0", "navItem1" etc.
This is necessary to differentiate between nav items that we should consider, and those we shouldn't such as "Previous", "Next" buttons. The indices need to be contiguous, and complete, starting at 0.

An example of correctly keyed items, mixed with items that we do not wish to consider for the purposes of ellipsisizing.

``` html
$items = array(array('title' => "Previous")
               "page-0" => array('title' => "Page 1"),
               "page-1" => array('title' => "Page 2"),
               "page-2" => array('title' => "Page 3"),
               "page-3" => array('title' => "Page 4"),
               "page-4" => array('title' => "Page 5"),
               "page-5" => array('title' => "Page 6"),
               array('title' => "Next")
               );
```

* Call ellipsisize with your array. Specify the "selected" item, that is the key of the item whose page we're on. Also specify the number of visible items you want. You can ask for JS annotations, and also overide the key prefix we mentioned earlier.

Example call to ellipsisize: We're on "Page 4", whose key is "page-3. We want 4 visible items, we don't want JS annotations, and our key prefix is "page-".

``` html
$items = Ellipsisizer::ellipsisize("page-3", $items, 4, false, "page-");
```

* Handle the ellipsis annotations as you render your items. You'll need to style the "ellipsis" and "hidden" items.

More Pagination Ellipsis Resources
----------------------------------

* Thoughts on pagination design: http://v1.wolfslittlestore.be/in-search-of-the-ultimate-pagination

Author
------

**Ben Dalziel @ Sly Trunk**

+ http://bendalziel.com/
+ http://slytrunk.com/

Demo
----

A demo file is included. Here is the output, showing what it can do:

``` html
$ php demo.php
Non-JS Version: 
Key: "page-0". Class: ""
Key: "page-1". Class: "ellipsis"
Key: "page-2". Class: "hidden"
Key: "page-3". Class: "hidden"
Key: "page-4". Class: ""
Key: "page-5". Class: ""
Key: "page-6". Class: ""
Key: "page-7". Class: "ellipsis"
Key: "page-8". Class: "hidden"
Key: "page-9". Class: ""
JS Version: 
Key: "page-0". Class: "ellipL-0 ellipU-3 ellipM-9"
Key: "page-1". Class: "ellipsis ellipL-0 ellipU-3 ellipM-9"
Key: "page-2". Class: "hidden ellipL-1 ellipU-3 ellipM-9"
Key: "page-3". Class: "hidden ellipL-2 ellipU-4 ellipM-9"
Key: "page-4". Class: "ellipL-3 ellipU-5 ellipM-9"
Key: "page-5". Class: "ellipL-4 ellipU-6 ellipM-9"
Key: "page-6". Class: "ellipL-5 ellipU-7 ellipM-9"
Key: "page-7". Class: "ellipsis ellipL-6 ellipU-8 ellipM-9"
Key: "page-8". Class: "hidden ellipL-6 ellipU-9 ellipM-9"
Key: "page-9". Class: "ellipL-6 ellipU-9 ellipM-9"
```