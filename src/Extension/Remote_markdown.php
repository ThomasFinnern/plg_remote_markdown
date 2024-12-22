<?php

namespace Finnern\Plugin\Content\Remote_markdown\Extension;

use Finnern\Plugin\Content\Remote_markdown\Extension\Parsedown\Parsedown;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;

// no direct access
defined('_JEXEC') or die;

class Remote_markdown extends CMSPlugin implements SubscriberInterface
{
    protected const MARKER = 'remotemarkdown:';

    // optional parameters  have to be separated by '>' character and written before the HTML link
    // Excluded US-ASCII Characters disallowed within the URI syntax:
    // delims      = "<" | ">" | "#" | "%" | <">
    //  "#" is excluded because it is used to delimit a URI from a fragment identifier.
    //  "%" is excluded because it is used for the encoding of escaped characters
    //  "#" and "%" are reserved characters that must be used in a specific context
    protected const PARA_SEPARATOR = '>';


    public static function getSubscribedEvents(): array
    {
        return [
            'onContentPrepare' => 'replaceByRemoteMarkdown',
            //               'onContentAfterTitle' => 'addShortcodeSubtitle',
        ];
    }

    // this will be called whenever the onContentPrepare event is triggered
    public function replaceByRemoteMarkdown(Event $event) : void
    {
        /* This function processes the text of an article being presented on the site.
         * It replaces any text of the form "{remotemarkdown:https://...}" where 
         * the string between : and } will be used as external url pointing to a
         * markdown file
         *
         *
         */

        // The line below restricts the functionality to the site (ie not on api)
        // You may not want this, so you need to consider this in your own plugins
        if (!$this->getApplication()->isClient('site')) {
            return;
        }

        // use this format to get the arguments for both Joomla 4 and Joomla 5
        // In Joomla 4 a generic Event is passed
        // In Joomla 5 a concrete ContentPrepareEvent is passed
        [$context, $article, $params, $page] = array_values($event->getArguments());
//        if ($context !== "com_content.article" && $context !== "com_content.featured")
        if ($context !== "com_content.article"
            && $context !== "com_content.featured"
            && $context !== "com_content.category"
        )
        {
            return;
        }
        if ($article->text == null)
        {
            return;
        }

        $text = $article->text; // text of the article

        // find opening curly brackets ...
        $offset = 0;
        while (($start = strpos($text, "{", $offset)) !== false) {

            // find the corresponding closing bracket and extract the "userText"
            if ($end = strpos($text, "}", $start)) {

                $userText = substr($text, $start+1, $end - $start - 1);

                //--- Matches marker ----------------------------

                if (str_starts_with(strtolower($userText), self::MARKER)) {

                    //--- Extract url and parameter) ----------------------

                    $userData = substr ($userText, strlen (self::MARKER));
                    [$fileUrl, $userParams] = $this->extractUrlAndParameter ($userData);

                    //--- Remote file  --------------------------------------------

                    $mdText = file_get_contents($fileUrl);

                    //--- convert to html ----------------------

                    // ? prepared $userParams ?

                    // ToDo: test $parseDown = new parsedown(true);
                    $parseDown = new parsedown();

                    //Test output: $html = $parseDown->text('**Hello _Parsedown_**');
                    $html = $parseDown->text($mdText);

                    //--- insert replacement -----------------------------------------------

                    if (strlen($html) > 0) {

                        // $text = substr_replace($text, htmlspecialchars($html), $start, $end - $start + 1);
                    } else {

                        $html = '{start of remotemarkdown found but file: "' . $fileUrl . '" or data not found }';
                    }
                    $text = substr_replace($text, $html, $start, $end - $start + 1);

                    $offset = $start + strlen($html);
                }
            }

            $offset = $end;
        }

/**
        // ToDo: show link as option
        // $outTxt .= '<br>$link: "' . $link . '"';

        // ToDo: show user parameter as option
        // ToDo: Tell user parameter
        foreach ($userParams as $key => $value)
        {
            $outTxt .= '<br>...$userParameter: "' . $key . '": ' . $value . '"';
        }
/**/



        /**
         * // if no match found replace it with an error string
         * if (!$match_found) {
         * $this->loadLanguage();  // you need to load the plugin's language constants before using them
         * // (alternatively you can set:  protected $autoloadLanguage = true; and Joomla will load it for you)
         * $text = substr_replace($text, Text::_('PLG_CONTENT_SHORTCODES_NO_MATCH'), $start, $end - $start + 1);
         * }
         * /**/

        // now update the article text with the processed text
        $article->text = $text;
    }

    private function extractUrlAndParameter($userData)
    {
        $fileUrl = '';
        $userParams = [];

        //--- optional parameters --------------------------------------------

        // optional parameters  have to be separated by '>' character and written before the HTML link

        // Excluded US-ASCII Characters disallowed within the URI syntax:
        // delims      = "<" | ">" | "#" | "%" | <">
        //  "#" is excluded because it is used to delimit a URI from a fragment identifier.
        //  "%" is excluded because it is used for the encoding of escaped characters
        //  "#" and "%" are reserved characters that must be used in a specific context
        $separator = '>';

        // find end separator
        $pos = strpos ($userData, self::PARA_SEPARATOR);
        if ($pos)
        {
            // Link is behind separator
            $fileUrl = substr ($userData, $pos +1);
            $userParameters = substr ($userData, 0, $pos -1);

            // $outTxt = '<br>$userParameters: "' . $userParameters . '"';

            $userParams = $this->extractParameters($userParameters);
        } else {

            $fileUrl = $userData;
        }


        return [$fileUrl, $userParams];
    }

    /**
     * User parameter prepared for extraction but not for use
     *
     *
     * @param string $userParameters
     * @param array $userParams
     * @return array
     */
    public function extractParameters(string $userParameters): array
    {
        $userParams = [];

        foreach (explode(';', $userParameters) as $userParameter) {

            $param = array_map('trim', explode(";", $userParameter));

            if (!count($param) > 0) {
                if (!count($param) > 1) {
                    $userParams [$param[0]] = $param[1];
                } else {
                    $userParams [$param[0]] = 1;
                }
            }
        }

        return $userParams;
    }

}


