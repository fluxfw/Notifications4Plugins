<?php


/**
 * Class srTextAttributeParser
 *
 * This parser takes a text with placeholders to be parsed and a replacements array containing the values for
 * each placeholder.
 *
 * Example:
 * =====================================================================================================================
 * Hello {{user.title}}, you joined {{course.title}} on {{today}}
 *
 * The text above contains three placeholders that should be replaced.
 * A placeholder can be a plain string or take a form of "subject.property". In the latter case,
 * the subject is bound to an implementation of the interface srAttributesProvider. There exist two standard
 * implementations for the ilias Course and User object (srCourseAttributesProvider, srUserAttributesProvider).
 *
 * The example text can be parsed like this:
 *
 * $parser = new srTextAttributeParser();
 * $text = 'Hello {{user.title}}, you joined {{course.title}} on {{today}}';
 * $replacements = array(
 *      'user' => new srUserAttributesProvider(new ilObjUser(6)),
 *      'course' => new srCourseAttributesProvider(new ilObjCourse(100)),
 *      'today' => date('d.m.Y'),
 * );
 *
 * $parsed = $parser->parse($text, $replacements);
 * =====================================================================================================================
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srTextAttributeParser
{

    /**
     * @var string
     */
    protected $separator = '.';

    /**
     * @var string
     */
    protected $start_tags = '{{';

    /**
     * @var string
     */
    protected $end_tags = '}}';

    /**
     * @var string
     */
    protected $error_message = '## No replacement found for attribute "{attribute}" ##';


    /**
     * @param string $text
     * @param array $replacements
     * @return string
     */
    public function parse($text, array $replacements = array())
    {
        // Quick exit if nothing to replace
        if (!count($replacements)) {
            return $text;
        }

        // First we parse all the placeholders that need to be replaced
        preg_match_all('/' . $this->start_tags . '([^' . $this->end_tags .']*)' . $this->end_tags . '/', $text, $result);

        // This array contains the placeholders
        $placeholders_tags = array_map('trim', $result[0]);
        $placeholders = array_map('trim', $result[1]);

        // Build the replacements array
        $_replacements = array();
        foreach ($placeholders as $placeholder) {
            if (strpos($placeholder, $this->separator) !== false) {
                // If the placeholder is of type subject.property, then we look up the property form the srAttributeProvider object
                list($subject, $attribute) = explode($this->separator, $placeholder);
                if (!isset($replacements[$subject]) || !$replacements[$subject] instanceof srAttributesProvider) {
                    $_replacements[] = $this->getParsedErrorMessage($attribute);
                    continue;
                }
                /** @var srAttributesProvider $provider */
                $provider = $replacements[$subject];
                $value = $provider->getAttribute($attribute);
            } else {
                // The placeholder is a plain string, replace directly
                $value = isset($replacements[$placeholder]) ? $replacements[$placeholder] : null;
            }

            $_replacements[] = (is_null($value)) ? $this->getParsedErrorMessage($attribute) : $value;
        }

        return str_replace($placeholders_tags, $_replacements, $text);
    }


    /**
     * @param string $attribute
     * @return string
     */
    protected function getParsedErrorMessage($attribute)
    {
        return str_replace('{attribute}', $attribute, $this->getErrorMessage());
    }


    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }


    /**
     * @param string $separator
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }


    /**
     * @return string
     */
    public function getStartTags()
    {
        return $this->start_tags;
    }


    /**
     * @param string $start_tags
     */
    public function setStartTags($start_tags)
    {
        $this->start_tags = $start_tags;
    }


    /**
     * @return string
     */
    public function getEndTags()
    {
        return $this->end_tags;
    }


    /**
     * @param string $end_tags
     */
    public function setEndTags($end_tags)
    {
        $this->end_tags = $end_tags;
    }


    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }


    /**
     * @param string $error_message
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }


}