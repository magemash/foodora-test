Foodora Test
============

Assumptions

There are not multiple of the same date in special days

If there is no entry for a special day then the existing scedule for that day is to be used



Requirements
============

PHP >= 5.3.9
PHPUnit (for tests)
MySQL

Usage
=====

Command to copy vendor_schedule table to tmp table then move the special days into the vendor schedule

php console.php specialdays:copy

Command to revert the original changes by deleting the new table and changing the name of the tmp table

php console.php specialdays:revert


Info
====


