<?php
/**
 * Created by PhpStorm.
 * User: wenjiang
 * Date: 9/18/16
 * Time: 8:34 PM
 */

# set default password length as 4
const default_value = 4;

# if there is no words,csv file then crawl the web to get one
if (!file_exists("words.csv")) {
    loadWordsToFile();
}

# load content of words.csv to an array
$words = file_get_contents("words.csv");

$words_array = explode(",", $words);

$password = getNextRandomWord($words_array);

# if # of words field is empty, just set default to 4 words long password without showing error
$notice = 'Now showing default 4 words long password';

# form
foreach($_POST as $key=>$value) {

    if ($key == "length") {

        if ($value != "") {
            # data validation
            $number = formDataVerification($value);

            if ($number != false) {
                $password = getNextRandomWord($words_array, $number);
                unset($notice);
            } else {
                $error = 'Please provide a valid number great than 0 less than 10';
            }
        }
    } else if ($key == 'contain_number') {
        # check whether checkbox contain number
        if ($value == true) {
            # genereate a random number between 0 and 9
            $password .= rand(0,9);
        }
    } else if ($key == 'contain_symbol') {
        if ($value == true) {
            # attach @ symbol
            $password .= '@';
        }
    }
}

/**
 * get $number words long password form words array
 *
 * @param $words_array array contains words dictionary
 * @param $number      length of password, default to 4
 *
 * @return string generated password
 */
function getNextRandomWord($words_array, $number = default_value)
{
    $generated_password = '';

    $size = sizeof($words_array);

    for ($i = 0; $i < $number; $i ++) {

        $index = rand(0, $size - 1);

        while (!ctype_alpha($words_array[$index])) {
            $index = rand(0, $size - 1);
        }

        $generated_password .= str_replace("'", "", $words_array[$index]);

        if ($i < ($number-1)) {
            $generated_password .= '-';
        }
    }

    return $generated_password;
}

/*
 * data validation
 *
 * @param $value user input to validate
 *
 * @return mixed number or bool
 */
function formDataVerification($value) {
    if (is_numeric($value)) {

        $number = intval($value);

        if ($number > 0 && $number < 10) {
            return $number;
        }
    }
    return false;
}

/*
 * loads a word dictionary from crawling paulnoll page
 */
function loadWordsToFile() {
    $words = [];

    for ($i= 1; $i < 30; $i=$i+2) {
        $name = 'http://www.paulnoll.com/Books/Clear-English/words-' . sprintf("%02d", $i) . '-' . sprintf("%02d", ($i + 1)) . '-hundred.html';

        $array_words = file_get_contents($name);

        preg_match_all("~<li>(.*?)</li>~s", $array_words, $matches, PREG_PATTERN_ORDER);

        foreach($matches[1] as $value) {
            $words[] = trim($value);
        }
    }

    $result = implode(",", $words);

    file_put_contents("words.csv", $result);
}

