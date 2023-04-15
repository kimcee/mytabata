<?php

namespace App\System;

class Config 
{
    // Template Directory
    const TEMPLATES_DIR = 'Views';
    const ROUTES_FILE = '.routes.yaml';

    // debug
    const DEBUG     = true; // used in debug class and error_reporting (boolean)
    
    // database
    const DB_HOST   = DB_HOST;   // database host
    const DB_USER   = DB_USER;   // database user
    const DB_PASS   = DB_PASS;   // database password
    const DB_NAME   = DB_NAME;   // database name
    const DB_PREFIX = DB_PREFIX; // [p] in database class gets replaced with this value
}   