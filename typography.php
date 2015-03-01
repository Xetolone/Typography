<?php
/**
 * Typography - Typographic corrector
 * Copyright 2015 Xetolone
 * 
 * Typography is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Typography is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * 
 */


/**
 * $string	: string to process
 * $lang	: "fr" for french or "en" for english
 * return	: the $string after processing
 */
function typography($string, $lang) {
	
	/**
	 * utf-8 code units in hex for character used in this file
	 * 
	 * \xC5\x93		: LATIN SMALL LIGATURE OE (U+0153) (œ)
	 * \xC2\xA0		: NO-BREAK SPACE (U+00A0) ( )
	 * \xE2\x80\x99	: GUILLEMET-APOSTROPHE (U+2019) (‘)
	 * \xC2\xAB\	: LEFT-POINTING DOUBLE ANGLE QUOTATION MARK (U+00AB) («)
	 * \xC2\xBB		: RIGHT-POINTING DOUBLE ANGLE QUOTATION MARK (U+00BB) (»)
	 * \xE2\x80\xA6	: HORIZONTAL ELLIPSIS (U+2026) (…)
	 * \xE2\x80\x9C	: LEFT DOUBLE QUOTATION MARK (U+201C) (“)
	 * \xE2\x80\x9D	: RIGHT DOUBLE QUOTATION MARK (U+201D) (”)
	 * 
	 * Utf-8 code units in hex are understood by PHP and converted
	 * into character because of the double quotes.
	 * 
	 * Unicode code point are understood by preg_ function with
	 * the synthax \x{FFFF}. Then the double quotesare not
	 * necessary anymore.
	 * 
	 * Example in a regex : NO-BREAK SPACE "\xC2\xA0" is equivalent to '\x{00A0}'
	 * and *not* '\x{C2A0}'
	 * 
	 */
	
	
	// Common typographic errors
	if ($lang == "fr") {
		// Replace "ca" by "ça"
		$string = preg_replace("/\bca\b/ui", 'ça', $string);
		// Replace "soeur" by "sœur"
		$pattern[0] = '/soeur/iu';
		$replacement[0] = 'sœur';
		// Replace "coeur" by "cœur"
		$pattern[1] = '/coeur/iu';
		$replacement[1] = 'cœur';
		// Replace "voeu" by "vœu"
		$pattern[2] = '/voeu/iu';
		$replacement[2] = 'vœu';
		// Replace "gout" by "goût"
		$pattern[3] = '/\bgout\b/iu';
		$replacement[3] = 'goût';
		// Replace "oeil" by "œil"
		$pattern[4] = '/\boeil\b/iu';
		$replacement[4] = 'œil';

		$string = preg_replace($pattern, $replacement, $string);
	} else {
		// Replace " i " by " I "
		$string = preg_replace("/\bi\b/u", 'I', $string);
	}

	// Replace unbreakable and tab space by space
	$string = preg_replace("/\xC2\xA0|\t/u", ' ', $string);
	// Replace multiple space by one space
	$string = preg_replace('/ +/u', ' ', $string);
	// Remove spaces at the beginning and the end of the string
	$string = trim($string);

	// replace "'" by '’'
	$string = str_replace("'", "\xE2\x80\x99", $string);
	// replace "`" by '’'
	$string = str_replace("`", "\xE2\x80\x99", $string);
	
	// Supress space around ponctuation
	$string = preg_replace("/ *([.,;:?!\"']) */u", '$1', $string);

	// supress tripple letters or more
	$string = preg_replace('/(\p{L})\1{3,}/u', "$1$1", $string);

	// Supress double ponctuation
	$string = preg_replace('/,+/u', ',', $string);
	$string = preg_replace('/;+/u', ';', $string);
	$string = preg_replace('/:+/u', ':', $string);
	$string = preg_replace("/'+/u", "'", $string);
	$string = preg_replace('/"+/u', '"', $string);
	// Replace two or more points by three points
	$string = preg_replace('/\.{2,}/u', "\xE2\x80\xA6", $string);

	// Capitalize letters after  '.', '?', '!' or '[' but not after '...'
	$string = preg_replace('/([^.][.?!\[]\s*\p{L})/ue', 'mb_strtoupper("$1")', $string);
	// Capitalize the first letter
	$string = preg_replace('/^(\p{L})/ue', 'mb_strtoupper("$1")', $string);
	

	// Add a space after ',', '.', ':', ';', '?', '…' and '!' if they are followed by text
	$string = preg_replace('/([.,;:?!\x{2026}])([^:;?!.\n\r\]])/u', '$1 $2', $string);

	
	if ($lang == "fr") {
		// Add an unbreakable space before ':', ';', '?' and '!' if they follow a text
		//$string = preg_replace('/(\w)([:;?!])/u', "$1\xC2\xA0$2", $string);
		// Add an unbreakable space before ':', ';', '?' and '!' if they don't follow ponctuation sign
		$string = preg_replace('/([^:;?!.])([:;?!])/u', "$1\xC2\xA0$2", $string);
	}

	// '!!!????!!?!?' -> '?!'
	$string = preg_replace('/[!?]*((\?!)|(!\?))[!?]*/u', '?!', $string);
	// Replace two or more '!' by two '!'
	$string = preg_replace('/!{2,}/u', '!!', $string);
	// Replace two or more '?' by two '?'
	$string = preg_replace('/\?{2,}/u', '??', $string);

	if ($lang == "fr") {
		// This one is a bit risky
		// '"text"' -> '« text »'
		$string = preg_replace('/"([^"]*)"/u', " \xC2\xAB\xC2\xA0$1\xC2\xA0\xC2\xBB", $string);
		// Add a space after '»' only if it is followed by text
		$string = preg_replace("/\xC2\xBB(\p{L})/u", "\xC2\xBB $1", $string);
	}
	if ($lang == "en") {
		// This one is a bit risky
		// '"text"' -> '“text”'
		$string = preg_replace('/"([^"]*)"/u', " \xE2\x80\x9C$1\xE2\x80\x9D", $string);
		// Add a space after '”' only if it is followed by text
		$string = preg_replace("/\x{201D}(\p{L})/u", "\xC2\xBB $1", $string);
	}
	
	// Add a point at the end if there is no ponctuation
	if(!preg_match("/[.!?]$/u", $string)) {
		$string .= ".";
	}

	return $string;
}
?>
