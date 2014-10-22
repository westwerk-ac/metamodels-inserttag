<?php
/**
 * Created by PhpStorm.
 * User: Oli
 * Date: 05/08/14
 * Time: 12:27
 */
namespace Westwerk;

use MetaModels\Factory;
use MetaModels\FrontendIntegration\InsertTags;
use MetaModels\IMetaModel;

class InsertHelper extends InsertTags
{

    /**
     * @var string[] | null
     */
    protected static $insertTags = null;

    /**
     * Evaluate an insert tag.
     *
     * @param string $replaceTag The tag to evaluate.
     *
     * @return bool|string
     */
    public function replaceTags($replaceTag)
    {
        $elements = explode('::', $replaceTag);

        // Check if we have the mm tags.
        if ($elements[0] != 'mm') {
            return false;
        }
        $tags = $this->getTags();

        try {

            /*
             * mm::tagName::id::[renderType]
             *
             *
             * link_url
             * link::label
             * item
             *
             */

            if (!isset($tags[$elements[1]])) {
                return false;
            }

            $itemId = $elements[2];
            $tag = $tags[$elements[1]];

            $type = (isset($elements[3]) ? $elements[3] : 'link_url');


            // Call the fitting function.
            switch ($type) {
                // Count for mod or ce elements.
                case 'link_url':
                    return $this->jumpTo($tag['mm'], $itemId, $tag['rendersetting']);
                case 'item':
                    return $this->replaceInsertTags($this->getItem($tag['mm'], $itemId, $tag['rendersetting']));
                case 'link':
                    $metaModel = Factory::byId($tag['mm']);
                    $label = isset($elements[4]) ? $elements[4] : $this->getDetailsCaption($metaModel, $tag['rendersetting']);
                    return '<a href="' . $this->jumpTo($tag['mm'], $itemId, $tag['rendersetting']) . '" >' . $label . '</a>';

                default:
                    return false;
            }
        } catch (\Exception $exc) {
            $this->log('Error by replace tags: ' . $exc->getMessage(), __CLASS__ . ' | ' . __FUNCTION__, TL_ERROR);
        }

        return false;
    }


    /**
     * Retrieve the caption text for the "Show details" link.
     *
     * This message is looked up in the following order:
     * 1. $GLOBALS['TL_LANG']['MSC'][<mm tablename>][<render settings id>]['details']
     * 2. $GLOBALS['TL_LANG']['MSC'][<mm tablename>]['details']
     * 3. $GLOBALS['TL_LANG']['MSC']['details']
     *
     * @param IMetaModel $metaModel
     * @param int $renderSettingId
     * @return string
     */
    protected function getDetailsCaption($metaModel, $renderSettingId)
    {
        if (!empty($renderSettingId)
            && isset($GLOBALS['TL_LANG']['MSC'][$metaModel->getTableName()][$renderSettingId]['details'])
        ) {
            return $GLOBALS['TL_LANG']['MSC'][$metaModel->getTableName()][$renderSettingId]['details'];
        } elseif (isset($GLOBALS['TL_LANG']['MSC'][$metaModel->getTableName()]['details'])) {
            return $GLOBALS['TL_LANG']['MSC'][$metaModel->getTableName()]['details'];
        }

        return $GLOBALS['TL_LANG']['MSC']['details'];
    }


    protected function getTags()
    {

        if (is_null(self::$insertTags)) {
            self::$insertTags = array();

            $result = \Database::getInstance()
                ->prepare('SELECT * FROM tl_metamodel_rendersettings WHERE LENGTH(insertTag) > 0')
                ->execute();

            while ($result->next()) {
                self::$insertTags[$result->insertTag] =
                    array(
                        'rendersetting' => $result->id,
                        'mm' => $result->pid
                    );


            }
        }
        return self::$insertTags;


    }

}