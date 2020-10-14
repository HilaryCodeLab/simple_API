<?php
/**********************************************************
 * Project:     hsst-cc-portfolio-p2
 * File:        classes/Utils.php
 * Author:      Hilary Soong
 * Date:        2020-06-13
 * Version:     1.0.0
 * Description: Utility Functions
 **********************************************************/

$targetDirectory = "shared/uploads";

class Utils
{

    /**
     * Sanitize method
     *
     * Accept a test string and pass it through the htmlspecialcharacters and strip_tags
     * methods to remove HTML/XML type tags, and escape / replace characters such as the
     * backtick `, tick ', and so forth with their encodes equivalents.
     *
     * This forms a level of security to the application, but it is not perfect.
     *
     * @param  $text
     * @return string
     */
    public static function sanitize($text): string
    {
        return trim(htmlspecialchars(strip_tags($text))) ?? '';
    }


    /**
     * Display list of messages
     *
     * The message list is an array of messages.
     *
     * Each message in the list has (and array) key-value pair.
     *      Key - the type of message (warning, danger, success, info)
     *      Value - the message text itself.
     *
     *      $messages = [
     *          0 => [ 'danger' => 'Critical error message' ],
     *          1 => [ 'success' => 'Success message' ],
     *          2 => [ 'info' => 'Informational message' ],
     *          3 => [ 'warning' => 'Warning message' ],
     *      ]
     *
     *      There are also other alert/message types such as:
     *          primary, secondary, light and dark - they may be of use
     *
     * @param  array  $messageList
     */
    public static function messages(array $messageList)
    {
        foreach ($messageList as $message) {
            foreach ($message as $type => $text) {
                echo "<p class='alert alert-{lower($type)}'>{$type}: {$text}</p>";
            }
        }
    }

    /* End of Utils Class */
}
