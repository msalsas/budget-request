<?php

namespace App\Helper;

class CategorySuggestionHelper
{
    // Category keys
    const HEATING_CAT_KEY = "heating";
    const KITCHEN_REFORMS_CAT_KEY = "kitchen";
    const BATHROOM_REFORMS_CAT_KEY = "bathroom";
    const AIR_CONDITIONING_CAT_KEY = "air-conditioning";
    const HOUSE_CONSTRUCTIONS_CAT_KEY = "house";

    // Categories
    const HEATING_CAT = "Calefacción";
    const KITCHEN_REFORMS_CAT = "Reformas cocinas";
    const BATHROOM_REFORMS_CAT = "Reformas baños";
    const AIR_CONDITIONING_CAT = "Aire acondicionado";
    const HOUSE_CONSTRUCTIONS_CAT = "Construcción de casas";

    // Heating Keys
    const NATURAL_GAS_KEY = "GAS NATURAL";
    const HEATING_KEY = "CALEFACCION";
    const BOILER_KEY = "CALDERA";
    const THERMOS_KEY = "TERMO";

    // Kitchen Keys
    const KITCHEN_KEY = "COCINA";
    const BELL_KEY = "CAMPANA";
    const WORKTOP_KEY = "ENCIMERA";

    // Bathroom Keys
    const BATHROOM_KEY = "BAÑO";
    const SHOWER_KEY = "DUCHA";
    const BATH_KEY = "BAÑERA";

    // Air Conditioning Keys
    const AIR_CONDITIONING_KEY = "AIRE ACONDICIONADO";
    const COLD_KEY = "FRIO";
    const GAS_LEAK_KEY = "PERDIDA DE GAS";
    const HEAT_PUMP_KEY = "BOMBA DE CALOR";

    // House construction Keys
    const CONSTRUCT_KEY = "CONSTRUIR";
    const CONSTRUCTION_KEY = "CONSTRUCCION";
    const CONSTRUCTED_KEY = "CONSTRUIDO";
    const STRUCTURE_KEY = "ESTRUCTURA";
    const ENCLOSURE_KEY = "CERRAMIENTO";
    const JOIST_KEY = "VIGA";
    const JOIST_TYPO_KEY = "BIGA";
    const HOUSE_KEY = "CASA";

    public static function getMatchedCategoryText(string $description)
    {
        $description = self::removeAccents($description);
        $categories = self::getCategories();

        foreach ($categories as &$category) {
            $matches = 0;
            foreach ($category['keys'] as $key) {
                $regExp = "/$key?/i";
                $matches += preg_match($regExp, $description);
            }
            $category['matches'] = $matches;
        }

        $matchedCategory = array_reduce($categories, function ($a, $b) {
            return $a['matches'] > $b['matches'] ? $a : $b ;
        });

        return $matchedCategory['text'];
    }

    private static function getCategories()
    {
        return array(
            self::HEATING_CAT_KEY => array(
                'text' => self::HEATING_CAT,
                'keys' => self::getHeatingKeys(),
            ),
            self::KITCHEN_REFORMS_CAT_KEY => array(
                'text' => self::KITCHEN_REFORMS_CAT,
                'keys' => self::getKitchenKeys(),
            ),
            self::BATHROOM_REFORMS_CAT_KEY => array(
                'text' => self::BATHROOM_REFORMS_CAT,
                'keys' => self::getBathroomKeys(),
            ),
            self::AIR_CONDITIONING_CAT_KEY => array(
                'text' => self::AIR_CONDITIONING_CAT,
                'keys' => self::getAirConditioningKeys(),
            ),
            self::HOUSE_CONSTRUCTIONS_CAT_KEY => array(
                'text' => self::HOUSE_CONSTRUCTIONS_CAT,
                'keys' => self::getHouseConstructionKeys(),
            ),
        );
    }

    private static function getHeatingKeys()
    {
        return array(
            self::NATURAL_GAS_KEY,
            self::HEATING_KEY,
            self::BOILER_KEY,
            self::THERMOS_KEY,
        );
    }

    private static function getKitchenKeys()
    {
        return array(
            self::KITCHEN_KEY,
            self::BELL_KEY,
            self::WORKTOP_KEY,
        );
    }

    private static function getBathroomKeys()
    {
        return array(
            self::BATHROOM_KEY,
            self::SHOWER_KEY,
            self::BATH_KEY,
        );
    }

    private static function getAirConditioningKeys()
    {
        return array(
            self::AIR_CONDITIONING_KEY,
            self::COLD_KEY,
            self::GAS_LEAK_KEY,
            self::HEAT_PUMP_KEY,
        );
    }

    private static function getHouseConstructionKeys()
    {
        return array(
            self::CONSTRUCT_KEY,
            self::CONSTRUCTION_KEY,
            self::CONSTRUCTED_KEY,
            self::STRUCTURE_KEY,
            self::ENCLOSURE_KEY,
            self::JOIST_KEY,
            self::JOIST_TYPO_KEY,
            self::HOUSE_KEY,
        );
    }

    private static function removeAccents($string){
        $string = utf8_encode($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $string
        );

        return $string;
    }
}