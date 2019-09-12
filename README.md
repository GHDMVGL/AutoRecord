# ValidateAgainstRegex

This is a REDcap module for enabling regular expression validation on Textbox field.

It create two action tags:
* @REGEX 
* @REGEX_MSG

## Usage

You must use both tags, without double quotes in it. **Space must be replaced by \u0020**: 

Legal usage :
```bash
@REGEX=^[0-9]+
@REGEX="^[0-9]+"
@REGEX=^[0-9]\u0020+

@REGEX_MSG="ERROR"
@REGEX_MSG=ERROR
@REGEX_MSG=ERROR\u0020MESSAGE
```

Illegal usage :
```bash
@REGEX=^[0-9] +
@REGEX=^[0-9]" "+
@REGEX_MSG=ERROR MESSAGE
```

