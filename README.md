Behat analysis extension
========================

Introduction
------------

Well, I always start asking "why?" when encoutering some crazy project.
So, I will start by answering this trivial question.

I've simply realised that the more a project grows, the more its `FeatureContext` also grows!
Some of the ones I've worked on have more than 1000 lines of code and it suffers from the same maintainability issues than any other huge class.
Many great tools already exists to provide solution against this drawback: OOP, [SubContexts](http://docs.behat.org/guides/4.context.html#using-subcontexts), [PageObjectContext](https://github.com/sensiolabs/BehatPageObjectExtension), ...

But still, sometimes you need to look at your past and try to improve what you've done and that's why I've done this extension!

This extension provides some basic analysis about your step definitions.
For the moment, it focuses on rarely used steps and similar ones.

Installation
------------

1. Define dependencies in your `composer.json`:

``` javascript
{
    "require": {
        ...

            "gquemener/behat-analysis-extension": "*"
    }
}
```

2. Install/update your vendors:

``` bash
    $ curl http://getcomposer.org/installer | php
    $ php composer.phar install
```

3. Activate extension by specifying its class in your ``behat.yml``:

``` yaml
    # behat.yml
    default:
        # ...
        extensions:
            Behat\AnalysisExtension\Extension: ~
```

Usage
----

After installation, a new `analysis` formatter should be available.
Then, run your feature suite using it:

``` bash
    $ bin/behat -fanalysis --dry-run
```

NB: It's recommended (but not mandatory) to run the analysis using the `--dry-run` option to accelerate the report generation.

Example
-------

```bash
    $ bin/behat -fanalysis --dry-run
        ---------------------------------------------------------------------- 70
        ---------------------------------------------------------------------- 140
        ---------------------------------------------------------------------- 210
        ---------------------------------------------------------------------- 280
        ---------------------------------------------------------------------- 350
        ------------------------
        
        58 scenarios (58 skipped)
        374 steps (374 skipped)
        
        Behat Steps Analysis
        ====================
        40 steps were used once.
        The most used step is /^I am logged in as "([^"]*)"$/ with 58 calls.
        Some steps might be merged to reduce their implementation redundancy:
          - /^I am on the "([^"]*)" attribute page$/ and /^I am on the "([^"]*)" product page$/
          - /^I enable the product$/ and /^I disable the product$/
          - /^I enable the product$/ and /^I save the product$/
          - /^an enabled "([^"]*)" product$/ and /^a disabled "([^"]*)" product$/
```

Contribution
------------
It is more than welcome as always!

Feel free to contact me on twitter [@GildasQ](http://www.twitter.com/GildasQ) or through [the issue system](https://github.com/gquemener/behat-analysis-extension/issues/new).
