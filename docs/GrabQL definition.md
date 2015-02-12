# GrabQL definition

GrabQL (Grab Query Language) is a SQL-like language used to grab data from websites or HTML/XML documents.

The first implementation is done in PHP.

## Comments
One line comments introduced by hashtag (`#`).

    # This is a comment.

## Variables
Variables are implicitly declared when used, have got dynamic typing and can be of several types: string, number,
regular expression, stream, list (array or JSON object)

    # string
    $a = 'text'

    # numbers
    $b1 = 123456
    $b2 = 3.14

    # regular expression
    $c = /([a-z]*)/

    # stream
    $d = select * from 'http://www.google.com'

    # lists
    $e1 = ['text', 123456, /[a-z]*]
    $e2 = {"a": 'text', "b1": 123456, "c": /[a-z]*/}

## User functions

Short definition of a user function:

	# short form
	def [function]: [params]
		[grabql | nativecall | bash]
	enddef
	
Long definition of a user function:

	# long format
	def [function]
		:format
		[format]	
		:code
		[grabql | nativecall | bash]
	enddef

In the long definition, optional code is preceded by ?.

| Type | Explanation |
| ---- | ----------- |
| `grabpql` | grabQL code |
| `nativecall` | PHP (or other language) native code, introduced by ! |
| `bash` | bash call, introduced by $ |

E.g.

	# Echo function
	def echo: args
	    $ echo args
	enddef

	# Select function
	def select
		:format
		select $what
		from $src
		?where $condition
		?limit $from, $to
		
		:code
		! new GrabQL($what, $src, $condition, array($from, $to))
	enddef

### Select

```
select [xpath | regular_expression]
from [url | variable]
where [condition]
limit [from], [to]
```



[xpath] e.g. /bookstore/book/title
[regular_expression] e.g. 

[url] e.g. bookmark.xml, http://www.google.com
[variable]