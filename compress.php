<?php

/**
 * compress
 *
 * @author Iksi <info@iksi.cc>
 * @version 1.0.0
 */
 
if (c::get('compress') !== true) return;

function compress($string) {
    
    // test if we have an html/xml page:
    if ( ! preg_match('/^<[\s\S]+>$/', trim($string))) return $string;

    $pattern = '%               # Collapse whitespace everywhere but in blacklisted elements.
        (?>                     # Match all whitespans other than single space.
            [^\S ]\s*           # Either one [\t\r\n\f\v] and zero or more ws,
        | (?<=[>])\s{2,}(?=[<]) # or two or more consecutive-any-whitespace between tags.
        )                       # Note: The remaining regex consumes no text at all...
        (?=                     # Ensure we are not in a blacklist tag.
            [^<]*+              # Either zero or more non-"<" {normal*}
            (?:                 # Begin {(special normal*)*} construct
                <               # or a < starting a non-blacklist tag.
                (?!/?(?:textarea|pre|script|style)\b)
                [^<]*+          # more non-"<" {normal*}
            )*+                 # Finish "unrolling-the-loop"
            (?:                 # Begin alternation group.
                <               # Either a blacklist start tag.
                (?>textarea|pre|script|style)\b
            | \z                # or end of file.
            )                   # End alternation group.
        )                       # If we made it here, we are not in a blacklist tag.
    %Six';

    return preg_replace($pattern, '', string);
}

ob_start('compress');
