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
class Translit {

    //put your code here

    static function sortfromMdCorUnicode($input) {
        if (empty($input)) {
            return NULL;
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

/*
 * 
 * WHEN  curChar = "Ꜣ" COLLATE utf8mb4_bin OR curChar = "ꜣ" COLLATE utf8mb4_bin THEN SET result = CONCAT(result,"a");
WHEN  curChar = "j" or curChar = "ı" or curChar = "i" or curChar = "y" THEN SET result = CONCAT(result,"b");
WHEN  curChar = "͗" COLLATE utf8mb4_bin OR curChar = "҆" COLLATE utf8mb4_bin OR curChar =  "̱"  COLLATE utf8mb4_bin THEN SET result = CONCAT(result,""); 
WHEN  curChar = "b" THEN SET result = CONCAT(result,"e");
WHEN  curChar = "p" THEN SET result = CONCAT(result,"f");
WHEN  curChar = "f" THEN SET result = CONCAT(result,"g");
WHEN  curChar = "m" THEN SET result = CONCAT(result,"h");
WHEN  curChar = "n" THEN SET result = CONCAT(result,"i");
WHEN  curChar = "r" THEN SET result = CONCAT(result,"j");
WHEN  curChar = "h" COLLATE utf8mb4_bin or curChar = "H" COLLATE utf8mb4_bin  THEN 
	IF  pos+1 <= endPos THEN  
		IF  SUBSTRING(transl, pos+1, 1) = "̱" COLLATE utf8mb4_bin THEN 
			SET result = CONCAT(result, "n");
		ELSE 
			SET result = CONCAT(result, "k");
		END IF;
	ELSE 
		SET result = CONCAT(result, "k");
	END IF;
WHEN  curChar = "ḥ" COLLATE utf8mb4_bin or curChar = "Ḥ" COLLATE utf8mb4_bin THEN SET result = CONCAT(result,"l");
WHEN  curChar = "ḫ" COLLATE utf8mb4_bin or curChar = "Ḫ" COLLATE utf8mb4_bin THEN SET result = CONCAT(result,"m");
WHEN  curChar = "ẖ" COLLATE utf8mb4_bin  THEN SET result = CONCAT(result,"n");
WHEN  curChar = "z"  THEN SET result = CONCAT(result,"o");
WHEN  curChar = "s" COLLATE utf8mb4_bin or curChar = "S" COLLATE utf8mb4_bin or curChar = "ś" COLLATE utf8mb4_bin or curChar = "Ś" COLLATE utf8mb4_bin THEN SET result = CONCAT(result,"p");
WHEN  curChar = "š" COLLATE utf8mb4_bin or curChar = "Š" COLLATE utf8mb4_bin THEN SET result = CONCAT(result,"q");
WHEN  curChar = "q" or curChar = "ḳ" COLLATE utf8mb4_bin or curChar = "Ḳ" COLLATE utf8mb4_bin THEN SET result = CONCAT(result,"r");
WHEN  curChar = "k" COLLATE utf8mb4_bin or curChar = "k" COLLATE utf8mb4_bin THEN SET result = CONCAT(result,"s");
WHEN  curChar = "g" THEN SET result = CONCAT(result,"t");
WHEN  curChar = "t" COLLATE utf8mb4_bin or curChar = "T" COLLATE utf8mb4_bin THEN SET result = CONCAT(result,"u");
WHEN  curChar = "ṯ" COLLATE utf8mb4_bin or curChar = "Ṯ" COLLATE utf8mb4_bin THEN SET result = CONCAT(result,"v");
WHEN  curChar = "d" COLLATE utf8mb4_bin or curChar = "D" COLLATE utf8mb4_bin THEN SET result = CONCAT(result,"w");
WHEN  curChar = "ḏ" COLLATE utf8mb4_bin or curChar = "Ḏ" COLLATE utf8mb4_bin THEN SET result = CONCAT(result,"x");
WHEN  curChar = "?" or curChar = "(" or curChar = ")" or curChar = "[" or curChar = "]" or curChar = "."  THEN SET result = result;
WHEN  curChar = " " or curChar = "⸗" or curChar = "/"  or curChar = "-"  THEN SET result = CONCAT(result," ");
 */

/*
 *     if ($alephayin === 'small') {
                        $aleph = "ꜣ";
                        $ayin = "ꜥ";
                    } else {
                        $aleph = "Ꜣ";
                        $ayin = "Ꜥ";
                    }
                    if ($yod === 'i00690357') {
                        $yodsmall = "i͗";
                        $yodcap = "I͗";
                    } elseif ($yod === 'i00690486') {
                        $yodsmall = "i҆";
                        $yodcap = "I҆";
                    } elseif ($yod === 'i0069032F') {
                        $yodsmall = "i̯";
                        $yodcap = "I̯";
                    } else {
                        $yodsmall = "ı͗";
                        $yodcap = "I͗";
                    }
                    if ($format === 'Convert from Transliteration') {
                        $findchars = array('&quot;', 'x', 'A', 'a', 'i', 'H', 'X', 'c', 'S', 'q', 'T', 'D', 'o', '!', '@', '#', '$', '%', '^', '', '*', '_', '+', 'Q', 'I', 'O', 'C', 'V', 'v', '=');
                        //$replacechars = array('&#x1E2B', '&#xA722', '&#xA724', '&#x0131&#x0357','&#x1E25', '&#x1E96', '&#x015B', '&#x0161', '&#x1E33', '&#x1E6F', '&#x1E0F', 'q');
                        $replacechars = array('&quot;', 'ḫ', $aleph, $ayin, $yodsmall, 'ḥ', 'ẖ', 'ś', 'š', 'ḳ', 'ṯ', 'ḏ', 'q', 'H', 'Ḥ', 'Ḫ', 'H̱', 'S', 'Š', 'T', 'Ṯ', 'D', 'Ḏ', 'Ḳ', $yodcap, 'Q', 'Ś', 'h̭', 'ṱ', '⸗');
                    } elseif ($format === 'From Trlit_CG Times') {
                        $findchars = array('&quot;', 'x', 'A', 'a', 'i', 'H', 'X', 'c', 'S', 'q', 'T', 'D', 'o', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'Q', 'I', 'O', 'C', 'V', 'v', '', '!', 'L', '=');
                        $replacechars = array('&quot;', 'ḫ', $aleph, $ayin, $yodsmall, 'ḥ', 'ẖ', 'ś', 'š', 'ḳ', 'ṯ', 'ḏ', 'q', 'H', 'Ḥ', 'Ḫ', 'H̱', 'S', 'Š', 'T', 'Ṯ', 'D', 'Ḏ', 'Ḳ', $yodcap, 'Q', 'Ś', 'Ṱ', 'ṱ', '&amp;', 'Ú', '⸥', '⸗');
                    } elseif ($format === 'From Umschrift_TTn') {
                        $findchars = array('&quot;', '~', 'X', '#', 'o', '|', 'H', 'x', 'È', 'Q', 'T', 'D', '!', '@', '$', '%', '^', '', '_', '+', 'O', 'V', 'v', '=', 'e', 'A', "'", '\\', 'c', '³', '²', 'E', '¢', '¦', '§', 'ß', '¾', 'µ', 'À', '', 'ƒ', '†', '‡', '‰', 'Š', '™', 'š', '¡', '£', '¥', '©', '®', '¯', '°', '±', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Ü', 'à', 'á', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'ï', 'ñ', 'ò', 'ó', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'þ', 'S', 'C');
                        $replacechars = array('Ḥ', 'ï', 'ḫ', $aleph, $ayin, $yodsmall, 'ḥ', 'ẖ', 'ś', 'ḳ', 'ṯ', 'ḏ', 'H', 'č̣', 'H̱', 'Ḫ', '(', '⸢', 'u̯', 'i̯', 'Ḥ', 'Ṯ', 'T', '⸗', 'D', 'ʾ', 'ʾ', '⸣', 'S', 'ṭ', 'č', 'Ḏ', 'Ǧ', 'ı͗', 'h̭', 'ṱ', 'ḍ', 'E', 'A', '|', 'ǧ', 'c', '²', 'T', '~', $aleph, 'ʕ', '_', 'Ṱ', 'e', 'h̭', 'ˉ́', 'ˉ', '˘', '˘́', 'ā́', 'Ẓ', 'ẓ', 'Q', 'Ġ', 'ṭ', 'Č', 'ḗ', $yodcap, '+', '³', 'ī́', $yodsmall, 'ŏ́', 'R̂', 'O', 'o', 'Ṣ', 'ṣ', 'Ḳ', 'Č̣', 'ū́', 'Ś', 'ắ', 'ă', 'ā', 'ġ', 'Ṭ', 'č', 'ĕ́', 'ĕ', 'e', 'ē', 'ĭ́', 'ĭ', 'ī', 'r̂', 'ŏ́', 'ŏ', 'ṓ', 'ō', 'ŭ́', 'ŭ', 'ḳ', 'ū', 'ắ', 'š', 'Š');
                    } elseif ($format === 'From Unicode') {
                        $findchars = array('ı̓', 'ı͗', 'ı҆', 'i̓', 'i͗', 'i҆', 'ỉ', 'I̓', 'I͗', 'I҆', 'Ỉ', 'ꜣ', 'Ꜣ', 'ȝ', 'Ȝ', 'Ꜥ', 'ꜥ', 'ʿ', '', '', '', '', '', '', '', '', '', '');
                        $replacechars = array($yodsmall, $yodsmall, $yodsmall, $yodsmall, $yodsmall, $yodsmall, $yodsmall, $yodcap, $yodcap, $yodcap, $yodcap, $aleph, $aleph, $aleph, $aleph, $ayin, $ayin, $ayin, 'č̣', 'H̱', 'H̭', 'h̭', $aleph, $ayin, 'i̯', 'u̯', $yodsmall, $yodcap);
                    }
                    //$res = $result = str_replace($findchars, $replacechars, $input);
                    $res = strtr($input, array_combine($findchars, $replacechars));
                  
 */
