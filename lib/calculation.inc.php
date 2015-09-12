<?php

function calcBearing($lat1, $lon1, $lat2, $lon2)
{
    // Input sind Breite/Laenge in Altgrad
    // Der Fall lat/lon1 == lat/lon2 sollte vorher abgefangen werden,
    // zB. ueber die Abfrage der Distanz, dass Bearing nur bei Distanz > 5m
    // geholt wird, sonst = " - " gesetzt wird...
    if ($lat1 == $lat2 && $lon1 == $lon2) {
        return '-';
    } else {
        $pi = 3.141592653589793238462643383279502884197;

        if ($lat1 == $lat2)
            $lat1 += 0.0000166;
        if ($lon1 == $lon2)
            $lon1 += 0.0000166;

        $rad_lat1 = $lat1 / 180.0 * $pi;
        $rad_lon1 = $lon1 / 180.0 * $pi;
        $rad_lat2 = $lat2 / 180.0 * $pi;
        $rad_lon2 = $lon2 / 180.0 * $pi;

        $delta_lon = $rad_lon2 - $rad_lon1;
        $bearing = atan2(sin($delta_lon) * cos($rad_lat2), cos($rad_lat1) * sin($rad_lat2) - sin($rad_lat1) * cos($rad_lat2) * cos($delta_lon));
        $bearing = 180.0 * $bearing / $pi;

        // Output Richtung von lat/lon1 nach lat/lon2 in Altgrad von -180 bis +180
        // wenn man Output von 0 bis 360 haben moechte, kann man dies machen:
        if ($bearing < 0.0)
            $bearing = $bearing + 360.0;

        return $bearing;
    }
}

function Bearing2Text($parBearing, $parShortText = 0)
{
    if ($parShortText == 0) {
        if ($parBearing == '-') {
            return 'N/A';
        } elseif (($parBearing < 11.25) || ($parBearing > 348.75))
            return tr('bearinglong_n');
        elseif ($parBearing < 33.75)
            return tr('bearinglong_nne');
        elseif ($parBearing < 56.25)
            return tr('bearinglong_ne');
        elseif ($parBearing < 78.75)
            return tr('bearinglong_ene');
        elseif ($parBearing < 101.25)
            return tr('bearinglong_e');
        elseif ($parBearing < 123.75)
            return tr('bearinglong_ese');
        elseif ($parBearing < 146.25)
            return tr('bearinglong_se');
        elseif ($parBearing < 168.75)
            return tr('bearinglong_sse');
        elseif ($parBearing < 191.25)
            return tr('bearinglong_s');
        elseif ($parBearing < 213.75)
            return tr('bearinglong_ssw');
        elseif ($parBearing < 236.25)
            return tr('bearinglong_sw');
        elseif ($parBearing < 258.75)
            return tr('bearinglong_wsw');
        elseif ($parBearing < 281.25)
            return tr('bearinglong_w');
        elseif ($parBearing < 303.75)
            return tr('bearinglong_wnw');
        elseif ($parBearing < 326.25)
            return tr('bearinglong_nw');
        elseif ($parBearing <= 348.75)
            return tr('bearinglong_nnw');
        else
            return 'N/A';
    }
    else {
        if ($parBearing == '-') {
            return 'N/A';
        } elseif (($parBearing < 11.25) || ($parBearing > 348.75))
            return tr('bearingshort_n');
        elseif ($parBearing < 33.75)
            return tr('bearingshort_nne');
        elseif ($parBearing < 56.25)
            return tr('bearingshort_ne');
        elseif ($parBearing < 78.75)
            return tr('bearingshort_ene');
        elseif ($parBearing < 101.25)
            return tr('bearingshort_e');
        elseif ($parBearing < 123.75)
            return tr('bearingshort_ese');
        elseif ($parBearing < 146.25)
            return tr('bearingshort_se');
        elseif ($parBearing < 168.75)
            return tr('bearingshort_sse');
        elseif ($parBearing < 191.25)
            return tr('bearingshort_s');
        elseif ($parBearing < 213.75)
            return tr('bearingshort_ssw');
        elseif ($parBearing < 236.25)
            return tr('bearingshort_sw');
        elseif ($parBearing < 258.75)
            return tr('bearingshort_wsw');
        elseif ($parBearing < 281.25)
            return tr('bearingshort_w');
        elseif ($parBearing < 303.75)
            return tr('bearingshort_wnw');
        elseif ($parBearing < 326.25)
            return tr('bearingshort_nw');
        elseif ($parBearing <= 348.75)
            return tr('bearingshort_nnw');
        else
            return 'N/A';
    }
}

function calcDistance($latFrom, $lonFrom, $latTo, $lonTo, $distanceMultiplier = 1)
{
    $distance = acos(cos((90 - $latFrom) * 3.14159 / 180) * cos((90 - $latTo) * 3.14159 / 180) + sin((90 - $latFrom) * 3.14159 / 180) * sin((90 - $latTo) * 3.14159 / 180) * cos(($lonFrom - $lonTo) * 3.14159 / 180)) * 6370 * $distanceMultiplier;
    if ($distance < 0)
        $distance = 0;
    return $distance;
}

function getCalcDistanceSqlFormula($modEnabled, $lonFrom, $latFrom, $maxDistance, $distanceMultiplier = 1, $lonField = 'longitude', $latField = 'latitude', $tableName = 'caches', $modLonField = 'longitude', $modLatField = 'latitude', $modTableName = 'cache_mod_cords')
{
    if ($modEnabled) {
        return getSqlDistanceFormulaForModCoords($lonFrom, $latFrom, $maxDistance, $distanceMultiplier, $lonField, $latField, $tableName, $modLonField, $modLatField, $modTableName);
    } else {
        return getSqlDistanceFormula($lonFrom, $latFrom, $maxDistance, $distanceMultiplier, $lonField, $latField, $tableName);
    }
}

function getSqlDistanceFormulaForModCoords($lonFrom, $latFrom, $maxDistance, $distanceMultiplier = 1, $lonField = 'longitude', $latField = 'latitude', $tableName = 'caches', $modLonField = 'longitude', $modLatField = 'latitude', $modTableName = 'cache_mod_cords')
{
    $lonFrom = $lonFrom + 0;
    $latFrom = $latFrom + 0;
    $maxDistance = $maxDistance + 0;
    $distanceMultiplier = $distanceMultiplier + 0;

    if (!mb_ereg_match('^[a-zA-Z][a-zA-Z0-9_]{0,59}$', $lonField))
        die('Fatal Error: invalid lonField');
    if (!mb_ereg_match('^[a-zA-Z][a-zA-Z0-9_]{0,59}$', $latField))
        die('Fatal Error: invalid latField');
    if (!mb_ereg_match('^[a-zA-Z][a-zA-Z0-9_]{0,59}$', $tableName))
        die('Fatal Error: invalid tableName');
    if (!mb_ereg_match('^[a-zA-Z][a-zA-Z0-9_]{0,59}$', $modLonField))
        die('Fatal Error: invalid modLonField');
    if (!mb_ereg_match('^[a-zA-Z][a-zA-Z0-9_]{0,59}$', $modLatField))
        die('Fatal Error: invalid modLatField');
    if (!mb_ereg_match('^[a-zA-Z][a-zA-Z0-9_]{0,59}$', $modTableName))
        die('Fatal Error: invalid modTableName');

    $b1_rad = sprintf('%01.5F', (90 - $latFrom) * 3.14159 / 180);
    $l1_deg = sprintf('%01.5F', $lonFrom);

    $lonField = '`' . $tableName . '`.`' . $lonField . '`';
    $latField = '`' . $tableName . '`.`' . $latField . '`';

    $modLonField = '`' . $modTableName . '`.`' . $modLonField . '`';
    $modLatField = '`' . $modTableName . '`.`' . $modLatField . '`';

    $r = 6370 * $distanceMultiplier;

    $retval = 'acos(cos(' . $b1_rad . ') * cos((90-IFNULL(' . $modLatField . ', ' . $latField . ')) * 3.14159 / 180) + sin(' . $b1_rad . ') * sin((90-IFNULL(' . $modLatField . ', ' . $latField . ')) * 3.14159 / 180) * cos((' . $l1_deg . ' - IFNULL(' . $modLonField . ', ' . $lonField . ')) * 3.14159 / 180)) * ' . $r;

    return $retval;
}

function getSqlDistanceFormula($lonFrom, $latFrom, $maxDistance, $distanceMultiplier = 1, $lonField = 'longitude', $latField = 'latitude', $tableName = 'caches')
{
    $lonFrom = $lonFrom + 0;
    $latFrom = $latFrom + 0;
    $maxDistance = $maxDistance + 0;
    $distanceMultiplier = $distanceMultiplier + 0;

    if (!mb_ereg_match('^[a-zA-Z][a-zA-Z0-9_]{0,59}$', $lonField))
        die('Fatal Error: invalid lonField');
    if (!mb_ereg_match('^[a-zA-Z][a-zA-Z0-9_]{0,59}$', $latField))
        die('Fatal Error: invalid latField');
    if (!mb_ereg_match('^[a-zA-Z][a-zA-Z0-9_]{0,59}$', $tableName))
        die('Fatal Error: invalid tableName');

    $b1_rad = sprintf('%01.5F', (90 - $latFrom) * 3.14159 / 180);
    $l1_deg = sprintf('%01.5F', $lonFrom);

    $lonField = '`' . $tableName . '`.`' . $lonField . '`';
    $latField = '`' . $tableName . '`.`' . $latField . '`';

    $r = 6370 * $distanceMultiplier;

    $retval = 'acos(cos(' . $b1_rad . ') * cos((90-' . $latField . ') * 3.14159 / 180) + sin(' . $b1_rad . ') * sin((90-' . $latField . ') * 3.14159 / 180) * cos((' . $l1_deg . '-' . $lonField . ') * 3.14159 / 180)) * ' . $r;

    return $retval;
}

function getMaxLat($lon, $lat, $distance, $distanceMultiplier = 1)
{
    return $lat + $distance / (111.12 * $distanceMultiplier);
}

function getMinLat($lon, $lat, $distance, $distanceMultiplier = 1)
{
    return $lat - $distance / (111.12 * $distanceMultiplier);
}

function getMaxLon($lon, $lat, $distance, $distanceMultiplier = 1)
{
    return $lon + $distance * 180 / (abs(sin((90 - $lat) * 3.14159 / 180)) * 6378 * $distanceMultiplier * 3.14159);
}

function getMinLon($lon, $lat, $distance, $distanceMultiplier = 1)
{
    return $lon - $distance * 180 / (abs(sin((90 - $lat) * 3.14159 / 180)) * 6378 * $distanceMultiplier * 3.14159);
}

?>
