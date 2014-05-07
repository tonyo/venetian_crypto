#!/bin/bash

DB_FILE="db.sqlite"
SCHEMA_FILE="schema.sql"

rm -f $DB_FILE
cat $SCHEMA_FILE | sqlite3 $DB_FILE

