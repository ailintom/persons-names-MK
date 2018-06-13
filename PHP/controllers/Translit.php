<?php

/*
 * MIT License
 *
 * Copyright (c) 2017 Alexander Ilin-Tomich (unless specified otherwise for individual source files and documents)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PNM;

/**
 * Description of Translit
 *
 * This is a static class handling Egyptian transliteration
 */
class Translit
{

    // This function coverts user inputs into the pseudocodes used in the database to search in the transliteration fields
    // (This is required because MySQL does not search for Unicode transliteration values correctly without a custom
    // user-defined UCA collation. And creating a user-defined UCA collation requires root rights).
    // Compare the MySQL function `search_transl`
    public static function searchVal($input)
    {
        if (empty($input)) {
            return null;
        }
        $replacechars = array("Ꜣ" => "a",
            "ꜣ" => "a",
            "A" => "a",
            "j" => "b",
            "ı" => "b",
            "i" => "b",
            "y" => "b",
            "J" => "b",
            "I" => "b",
            "Y" => "b",
            "͗" => "",
            "҆" => "",
            "̱" => "",
            "Ꜥ" => "c",
            "ꜥ" => "c",
            "a" => "c",
            "w" => "d",
            "W" => "d",
            "b" => "e",
            "B" => "e",
            "p" => "f",
            "P" => "f",
            "f" => "g",
            "F" => "g",
            "m" => "h",
            "M" => "h",
            "n" => "i",
            "N" => "i",
            "r" => "j",
            "R" => "j",
            "h" => "k",
            //  H
            "ḥ" => "l",
            "Ḥ" => "l",
            "ḫ" => "m",
            "Ḫ" => "m",
            "x" => "m",
            //ẖ
            "ẖ" => "n",
            "H̱" => "n",
            "X" => "n",
            "z" => "p", // NB: the database does not include z, hence, z is matched with s
            "Z" => "p",
            "s" => "p",
            "ś" => "p",
            "Ś" => "p",
            // //  S
            "š" => "q",
            "Š" => "q",
            "q" => "r",
            "Q" => "r",
            "o" => "r",
            "O" => "r",
            "ḳ" => "r",
            "Ḳ" => "r",
            "k" => "s",
            "K" => "s",
            "g" => "t",
            "G" => "t",
            "t" => "u",
            //T
            "ṯ" => "v",
            "Ṯ" => "v",
            "d" => "w",
            //D
            "ḏ" => "x",
            "Ḏ" => "x",
            "?" => "",
            "(" => "",
            ")" => "",
            "[" => "",
            "]" => "",
            "." => "",
            "=" => "",
            "⸗" => "",
            "/" => " ",
            "-" => " "
        );
        $replacecharsUnicode = array(
            "H" => "k",
            "S" => "p",
            "T" => "u",
            "D" => "w"
        );
        $replacecharsMdC = array(
            "H" => "l",
            "S" => "q",
            "T" => "v",
            "D" => "x"
        );
        if (preg_match('/[śŚšŠḳḲṯṮḏḎ⸗ꜣꜢı͗ꜤꜥḥḤḫḪẖ̱]/', $input)) {
            //if the input string has any Unicode-specific characters,
            // tread H, S, T, D as upper-case letters, otherwise treat them as
            // MdC codes for ḥ, š, ṯ, ḏ
            return strtr($input, array_merge($replacechars, $replacecharsUnicode));
        } else {
            return strtr($input, array_merge($replacechars, $replacecharsMdC));
        }
    }
}
