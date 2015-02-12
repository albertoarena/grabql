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
    var a = 'text'

    # numbers
    var b1 = 123456
    var b2 = 3.14

    # regular expression
    var c = '/([a-z]*)/'

    # objects
    var e1 = {'text', 123456, '/[a-z]*'}
    var e2 = {"a": 'text', "b1": 123456, "c": '/[a-z]*/'}

## Commands
### Echo

    echo [...]

### Select

    select [what]
    to [destination]
    from [url | variable]
    where [xpath]
    limit [from], [to]
