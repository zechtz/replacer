<?php

namespace Mtabe;

class Replacer
{

    const MESSAGE_ERROR_DESTRUCTIVE_WORDS = "SQL QUERY CONTAINS THE FOLLOWING DESCTRUCTIVE WORDS";
    const MESSAGE_ERROR_UNREPLACED_PARAMS = "THE FOLLOWING PARAMS DON'T HAVE REPLACEMENTS";
    /**
     *
     * return sql query without placeholder variable
     * e.g given the following sql $query = 'select * from where code = $P{code}'
     * replace($query, ['name' => 'John Doe', 'age' => 20, 'code' => 322])
     * should return select * form where code = 322
     * @param string $query
     * @param array $params
     * @return $query
     *
     */
    public static function replaceWithParams($query, $params = [])
    {
        // select * from tabel where code = #P{value}
        // $query = select * from tabel where code = #P{value}
        // $query = preg_replace("/(\$\w+\{\w+\})/", #value, $query);
        // Matches any value inside {} and saves in $matches array
        // preg_match returns 1 if there's a match or 0 if no match
        // then saves the match in the $matches array

        // remove new line characters and tabs and all two or more spaces with single space

        // check if query contains either drop, update, delete, truncate and return error message if it does
        $query = self::queryHasDestructiveWords($query)? self::queryHasDestructiveWords($query) : $query;

        $result = preg_replace_callback(
            '/\#P\{(.*?)\}/',
                function($el) use ($params) { return isset($params[$el[1]]) ? $params[$el[1]] : $el[0];},
                    $query
                );

        $result = self::allParamsHaveNotBeenReplaced($result)? self::allParamsHaveNotBeenReplaced($result) : $result;
        return $result;
    }

    private static function queryHasDestructiveWords($query)
    {
        if (preg_match_all('/(insert|update\b|drop|delete\b|truncate|add|create|insert|constraint|set)/i', $query, $matches)) {
            $badWords = array_pop($matches);
            throw new \Exception(MESSAGE_ERROR_DESTRUCTIVE_WORDS. " \n".join("\r\n", $badWords));
        } else {
            return $query;
        }
    }

    private  static function allParamsHaveNotBeenReplaced($query)
    {
        if (preg_match_all('/(\#P\{.*?\})/', $query, $matches)) {

            $unReplacedPlaceholders = array_pop($matches);

            throw new \Exception(
                MESSAGE_ERROR_UNREPLACED_PARAMS. " \n".join("\r\n",
                $unReplacedPlaceholders)
            );
        } else {
            return $query;
        }
    }
}
