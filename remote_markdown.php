<?php
/**


 */

// namespace Joomla\Plugin\Content;

// No direct access
defined( '_JEXEC' ) or die('');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Language\Factory;
use Joomla\CMS\Plugin\CMSPlugin;

//use Joomla\Plugin\Content\Remote_markdown\Parsedown\Parsedown;
use Joomla\Plugin\Content\Remote_markdown\Parsedown\Parsedown;


class PlgContentRemote_markdown extends CMSPlugin
{

    /**
     * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
     * If you want to support 3.0 series you must override the constructor
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

	// ToDo used const string 'remotemarkdown'

    /**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 *
     * @since       3.x
	 *
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
    /**/

	/**
	 * @param	string $context	The context of the content being passed to the plugin.
	 * @param	object $article	The article object.  Note $article->text is also available
	 * @param	object $params 	The article params
	 * @param	int $page 		The 'page' number ($limitstart)
     * @return bool
     *
     * @since       3.x
     */
	public function onContentPrepare($context, &$article, &$params, $page = 0) {

		// Don't run if there is no text property (in case of bad calls) or it is empty
		if (empty($article->text))
		{
			return;
		} 

		// Simple performance check to determine whether bot should process further 
		if (strpos($article->text, 'remotemarkdown') === false) {
			return true;
		}

		try {
			// Define the regular expression for the bot.
            //$regex = "#{rsg2_display\:*(.*?)}#s";
            $regex = "#{remotemarkdown:*(.*?)}#s";

			// Perform the replacement.
			$article->text = preg_replace_callback($regex, array(&$this, '_replacer'), $article->text);
		}
		catch(Exception $e) {
			$msg = Text::_('PLG_CONTENT_REMOTE_MARKDOWN') . ' Error (01): ' . $e->getMessage();
            $app = Factory::getApplication();
			$app->enqueueMessage($msg,'error');
			return false;
		}

		return true;
	}	

	/**
	 * Replaces the matched tags.
	 *
	 * @param	array	$matches An array of matches (see preg_match_all)
     * @return bool|string
     * @throws Exception
     */
	function _replacer ( $matches ) {
//		$app = Factory::getApplication();

		if( ! $matches )
		{
			return false;
		}

		// second match contains data
		if( ! count($matches) > 1 )
		{
			return false;
		}

		try {
			$outTxt = ""; // Debug text

			//--- user parameter and link part ------------------

			$paraAndLink =  $matches[1];
			$link = $paraAndLink;

			//--- optional parameters --------------------------------------------

			// optional parameters  have to be separated by '>' character and written before the HTML link

			// Excluded US-ASCII Characters disallowed within the URI syntax:
			// delims      = "<" | ">" | "#" | "%" | <">
			//  "#" is excluded because it is used to delimit a URI from a fragment identifier.
			//  "%" is excluded because it is used for the encoding of escaped characters
			//  "#" and "%" are reserved characters that must be used in a specific context

			$separator = '>';

			$userParams = [];

			$pos = strpos ($paraAndLink, $separator);
			if($pos)
			{
				$userParameters = substr ($paraAndLink, 0, $pos -1);
				$outTxt .= '<br>$userParameters: "' . $userParameters . '"';

				// Link is behind separator
				$link = substr ($paraAndLink, $pos +1);

				foreach (explode (';', $userParameters) as $userParameter)
				{
					$param = array_map('trim', explode(";", $userParameter));

					if (!count($param) > 0)
					{
						if (!count($param) > 1)
						{
							$userParams [$param[0]] = $param[1];
						}
						else
						{
							$userParams [$param[0]] = 1;
						}
					}
				}
			}

			$outTxt .= '<br>$link: "' . $link . '"';

			foreach ($userParams as $key => $value)
			{
				$outTxt .= '<br>...$userParameter: "' . $key . '": ' . $value . '"';
			}

			//--- Remote file  --------------------------------------------

			$mdText = file_get_contents($link);

			// secure link
			// Valid in link
			// $ValidChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~:/?#[]@!$&'()*+,;='
			// regex for html /^([!#$&-;=?-[]_a-z~]|%[0-9a-fA-F]{2})+$/


//			return '<div style="color:darkblue;"> <strong>remotemarkdown found </strong></div>';

			$parseDown = new parsedown();

			//$parseDown->setBreaksEnabled(true); # enables automatic line breaks
			//$parseDown->setMarkupEscaped(true); # escapes markup (HTML)
			//$parseDown->setUrlsLinked(true); # prevents automatic linking of URLs

			$outTxt .= '<br>' . '-------------------' . '<br>';

			//$html = $parseDown->text('**Hello _Parsedown**');
			$html = $parseDown->text($mdText);

			return $outTxt . $html;
		}
		catch(Exception $e) {
			$msg = Text::_('PLG_CONTENT_REMOTE_MARKDOWN') . ' Error (02): ' . $e->getMessage();
            $app = Factory::getApplication();
			$app->enqueueMessage($msg,'error');
			return false;
		}

        return false;
	}

	/**
	 * Generate a search pattern based on link and text.
	 *
	 * @param   string  $link  The target of an email link.
	 * @param   string  $text  The text enclosed by the link.
	 *
	 * @return  string	A regular expression that matches a link containing the parameters.
	 */
	 
	/** ToDo: use a pattern function for completed link ....
	protected function _getPattern($link, $text)
	{
		$pattern = '~(?:<a ([^>]*)href\s*=\s*"mailto:' . $link . '"([^>]*))>' . $text . '</a>~i';

		return $pattern;
	}
	/**/
}

/** Cache preparing  *
$cache_file = 'URI to cache file';
$cache_life = '120'; //caching time, in seconds

$filemtime = @filemtime($cache_file);  // returns FALSE if file does not exist
if (!$filemtime or (time() - $filemtime >= $cache_life)){
	ob_start();
	resource_consuming_function();
	file_put_contents($cache_file,ob_get_flush());
}else{
	readfile($cache_file);
}
/**/


