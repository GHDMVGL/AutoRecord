# Autorecord

This module is for autoincrement the record_id to to the next free one.


# Manual installation

- Clone this repository in the redcap `modules` directory.
- rename the Autorecord plugin to fit modules folders convention (ex: autorecord_v2.0.0)
- enable the module in the REDCap control center

## Usage

It only work on Project with the default primary field "record_id". The files must be a csv and must have column 'record_id' filled with some id (they dont have to be correct).
You will be automagically redirected to the data import Tool page with the data updated.


