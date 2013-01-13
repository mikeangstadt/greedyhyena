greedyhyena
===========

written by: Michael Angstadt
built on: CodeIgniter PHP Framework 2.1
third-party software usage: An extended version of SimpleLogin adapted for usage in CI 2.1+

Description
----------- 
Greedy Hyena is a multi-variant testing system that enables users to run web design tests with sophisticated decision logic simply and easily.

Theory Summary
---------------
Greedy Hyena is an extension of the Epsilon Greedy selection strategy.  Epsilon Greedy, or the Multi-arm Bandit - is a dynamic learning algorithm that
avoids several drawbacks of standard a/b testing including

* The need to wait until the end of the testing period to make informed decisions
* The limit of testing only 2 variants at a time
* Ambiguity to data threshold required to be able to make informed decisions

By considering the available data in multiple dimensions, Greedy Hyena includes a 'revenue' factor in it's calculations (not just whether a user converted or not)
which more accurately guides champion selection.

Not only that, but we can also use the standard deviation of the real-time data to know exactly how many impressions or conversions we need to start using the data!

Mathmatically speaking, we're dynamically computing the statistical significant population at a user specified acceptable error threshold and branching logic behind
the scenes.  Using this information we can accurately predict given a website visitors sees a variant the likelihood they'll convert AND THEN given a website visitor whom
converted predict the amount of revenue they'll generate.

System
------
Core functionality for the hosted version of Greedy Hyena is available at www.epsilongreedy.com, however, this codebase can be modified to be used natively on any
LAMP stack environment.  For the purposes of demonstration, the primary branch of this codebase will point to www.epsilongreedy.com.
The system uses embedded JavaScript to provide user-side functionality via snippet inclusion and api key / username provision and jsonp ajax postbacks to 
www.epsilongreedy.com to pull real-time data dynamically using trickle-down decision logic.

Usage
-----
Greedy Hyena is written in PHP & JavaScript build on the Code Igniter Framework.  The workflow of usage includes a website owner (GH user) signing up at www.epsilongreedy.com
and receiving a valid developer api key.
Using the online dashboard the GH user can create tests, edit their account settings, and add variants in the form of HTML snippets.
Using the api key and user name included in a script provided once the GH user confirms their email address the Greedy Hyena JavaScript library is included from www.epsilongreedy.com
ie, <script type='text/javascript' src='http://www.epsilongreedy.com/api?apiKey=providedapikey&userID=userID'></script>
The Greedy Hyena libraries will replace all properly labeled elements on the GH user page(s) with a variant selected using the system logic and record and impressions.
The GH user includes another snippet on any conversion target page and/or can directly post to the conversion recording postback function.
