<?php namespace Envoy\MultipartForm;

class Processer {
    
    /**
     * Adapted from http://www.chlab.ch/blog/archives/php/manually-parse-raw-http-data-php
     * 
     * Parse raw HTTP request data
     *
     * Pass in $a_data as an array. This is done by reference to avoid copying
     * the data around too much.
     *
     * Any files found in the request will be added by their field name to the
     * $data['files'] array.
     *
     * @param   array  Empty array to fill with data
     * @return  array  Associative array of request data
     */
    public static function parse(array &$a_data) {
        // read incoming data
        /* PUT data comes in on the stdin stream */
        $input_handler = fopen("php://input", "r");
        
        /* Open a file for writing */
        $input = '';
        
        /* Read the data 1 KB at a time
           and write to the file */
        while ($data = fread($input_handler, 1024)) {
          $input .= $data;
        }
        
        /* Close the streams */
        fclose($input_handler);
        
        if(!isset($_SERVER['CONTENT_TYPE'])) {
            return $a_data;
        }
        
        // grab multipart boundary from content type header
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
        // content type is probably regular form-encoded
        if (!count($matches)) {
            // we expect regular puts to containt a query string containing data
            parse_str(urldecode($input), $a_data);
            return $a_data;
        }
        
        $boundary = $matches[1];
        
        // split content by boundary and get rid of last -- element
        $a_blocks = preg_split("/-+$boundary/", $input);
        array_pop($a_blocks);
        
        // loop data blocks
        foreach ($a_blocks as $id => $block) {
            if (empty($block)) {
                continue;
            }
            
            // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char
            // parse uploaded files
            //$a_data = $block;return;
            if (strpos($block, 'filename="') !== false) {
                // match "name", then everything after "stream" (optional) except for prepending newlines
                preg_match("/name=\"([^\"]*)\".*filename=\"([^\"]*)\".*Content-Type: (.*?)[\n\r]+(.*)$/s", $block, $matches);
                
                if(isset($matches[4]) && !empty($matches[4])) {
                    $a_data['files'][] = array('form_name' => $matches[1], 'file_name' => $matches[2], 'content-type' => $matches[3], 'file' => $matches[4]);
                }
            } else {
                // parse all other fields
                // match "name" and optional value in between newline sequences
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
                $a_data[$matches[1]] = isset($matches[2]) ? $matches[2] : null;
            }
        }
    }
}