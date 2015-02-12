# GrabQL (Grabbing Query Language)

GrabQL (Grabbing Query Language) is a SQL-like language to be used for web-scraping. It is developed in PHP because
one of its requirements is to be used embedded in a PHP application.

**This is not yet a stable version, work in progress! More details coming soon.**

## Install GrabQL

    composer update
    chmod +x ./public/gql.php

## Run the first sample GrabQL script

    ./public/gql.php ./sample/sample1.gql

It will run the `sample1.gql` script that contains the following GrabQL code:

    # Variables
    var url = 'albertoarena.co.uk'
    var results = {}
    var what = {'href', '@value'}

    # Query using xPath
    select what from url to results where '//a[@class="post-link"]'

    # Format results using JSON format
    echo 'Query results:' results:json

And produce a JSON list of posts in the main page of my blog!

    --- GQL Interpreter 0.1 ---
    Query results:
    [{"href":"\/grabql-a-query-language-for-data-scraping","@value":"GrabQL, a query language for data scraping"},{"href":"\/is-it-really-an-integer","@value":"Is it really an integer?"},{"href":"\/i-moved-to-jekyll","@value":"I moved to Jekyll"},{"href":"\/a-simple-javascript-gettersetter","@value":"A simple Javascript Getter\/Setter"},{"href":"\/installing-store-kit-in-titanium-studio","@value":"Installing Store Kit in Titanium Studio"},{"href":"\/special-effects-in-css3","@value":"Special effects in CSS3"},{"href":"\/sharing-your-house-factory-method-pattern-can-help-you","@value":"Sharing your house? Factory method pattern can help you"}]
