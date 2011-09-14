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

Usage
-----

1. Put your navigation items in an array. Make sure each item is keyed into the array using a common prefix and integer index, zero based. E.g. my prefix could be "navItem", and my keys would look like "navItem0", "navItem1" etc.
This is necessary to differentiate between nav items that we should consider, and those we shouldn't such as "Previous", "Next" buttons. The indices need to be contiguous, and complete, starting at 0.

2. Call ellipsisize with your array. Specify the "selected" item, that is the key of the item whose page we're on. Also specify the number of visible items you want. You can ask for JS annotations, and also overide the key prefix we mentioned earlier.

3. Handle the ellipsis annotations as you render your items. You'll need to style the "ellipsis" and "hidden" items.


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