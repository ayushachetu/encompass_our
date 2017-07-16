<?php

namespace PhantomInstaller;

class PhantomBinary
{
    const BIN = 'C:\xampp\htdocs\encompass\bin\phantomjs.exe';
    const DIR = 'C:\xampp\htdocs\encompass\bin';

    public static function getBin() {
        return self::BIN;
    }

    public static function getDir() {
        return self::DIR;
    }
}
