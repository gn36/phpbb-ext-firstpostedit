# phpBB 3.1 Extension - gn36 - Customize first post edit permissions

This Extension allows differenciating editing permissions for the first post. It also allows bypassing the max edit time setting for the first or all posts on a per-forum basis. If you use other extensions that change users edit permissions, please check thoroughly whether they still work correctly in combination with this extension.

## Installation

Clone into ext/gn36/firstpostedit:

    git clone https://github.com/gn36/phpbb-ext-firstpostedit ext/gn36/firstpostedit

Go to "ACP" > "Customise" > "Extensions" and enable the "gn36 - Allow edit first post" extension.

## Development

If you find a bug, please report it on https://github.com/gn36/phpbb-ext-firstpostedit

## Automated Testing

We use automated unit tests including functional tests to prevent regressions. Check out our travis build below:

master: [![Build Status](https://travis-ci.org/gn36/phpbb-ext-firstpostedit.png?branch=master)](http://travis-ci.org/gn36/phpbb-ext-firstpostedit)

## License

[GPLv2](license.txt)
