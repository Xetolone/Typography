Typography
==========

Presentation
------------

Typography is a PHP function that correct typographic errors.

I have a lot of contributions from visitors in a web project and I am using this function as pre-processing before publishing the contributions.

It's working but it's a work in progress.
There are a lot of separate preg_replace rules. It's on purpose to allow the user to easily disable a specific correction.

Issues
------

* numbers arround ponctuation
* ponctuation arround DOUBLE ANGLE QUOTATION MARKS

TODO
----

* Make the code be "encoding proof" (exept fot the comments)


