<?php

namespace Iksi\Compress;

/**
 * Compress Response Component
 */
class Response extends \Kirby\Component\Response {

  public function compress($input) {

    // Uses Alan Moore’s regular expression: 
    // http://stackoverflow.com/questions/5312349/minifying-final-html-output-using-regular-expressions-with-codeigniter
    
    // Collapse whitespace everywhere but in blacklisted elements.
    $pattern = '%
      (?>             # Match all whitespans other than single space.
        [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
      | \s{2,}        # or two or more consecutive-any-whitespace.
      )               # Note: The remaining regex consumes no text at all...
      (?=             # Ensure we are not in a blacklist tag.
        [^<]*+        # Either zero or more non-"<" {normal*}
        (?:           # Begin {(special normal*)*} construct
          <           # or a < starting a non-blacklist tag.
          (?!/?(?:textarea|pre|script|style)\b)
          [^<]*+      # more non-"<" {normal*}
        )*+           # Finish "unrolling-the-loop"
        (?:           # Begin alternation group.
          <           # Either a blacklist start tag.
          (?>textarea|pre|script|style)\b
        | \z          # or end of file.
        )             # End alternation group.
      )               # If we made it here, we are not in a blacklist tag.
    %Six';

    return preg_replace($pattern, '', $input);
  }

  public function make($response) {

    if(is_string($response)) {
      return $this->compress($this->kirby->render(page($response)));
    } else if(is_array($response)) {
      $this->kirby->render(page($response[0]), $response[1]);
    } else if(is_a($response, 'Page')) {
      return $this->compress($this->kirby->render($response));
    } else if(is_a($response, 'Response')) {
      if($response->format() === 'html') {
        return $this->compress($response);
      }
      return $response;
    } else {
      return null;
    }

  }

}
