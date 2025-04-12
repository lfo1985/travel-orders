<?php

use Illuminate\Database\Eloquent\Collection;

if (!function_exists('except')) {
    /**
     * Throw an exception with a custom message and code.
     * 
     * @param string $message
     * @param int $code
     * @param string $class
     * @throws \Exception
     */
    function except(string $message, int $code, string $class = '')
    {
        if ($class == '') {
            $class = Exception::class;
            $code = 500;
        }
        throw new $class($message, $code);
    }
}

if (!function_exists('slug')) {
    /**
     * @param string $string
     * @param string $separator
     * @return string The generated slug.
     */
    function slug(string $string, string $separator = '-'): string
    {
        return \Illuminate\Support\Str::slug($string, $separator);
    }
}

if(!function_exists('sendError')) 
{
    /**
     * Send an error response in JSON format.
     * 
     * @param int $code
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    function sendError(int $code, string $message, array $data = []): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            [
                'success' => false,
                'message' => $message,
                'data' => $data
            ],
            $code
        );
    }
}

if(!function_exists('sendSuccess')) 
{
    /**
     * Send a success response in JSON format.
     * 
     * @param int $code
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    function sendSuccess(int $code, string $message, array $data = []): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            [
                'success' => true,
                'message' => $message,
                'data' => $data
            ],
            $code
        );
    }
}

if(!function_exists('sendData')) 
{
    /**
     * Send a data response in JSON format.
     * 
     * @param int $code
     * @param array|Collection $data
     * @return \Illuminate\Http\JsonResponse
     */
    function sendData($data, int $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            [
                'success' => true,
                'data' => $data
            ],
            $code
        );
    }
}

if (!function_exists('padLeft')) {
    /**
     * Pad a string on the left side with a specified character to a specified length.
     * 
     * @param string $value
     * @param string $pad
     * @param int $length
     * @return \Illuminate\Support\Stringable
     */
    function padLeft(string $value, string $pad, int $length)
    {
        return \Illuminate\Support\Str::of($value)->padLeft($length, $pad);
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat(string $date): string
    {
        if(\Illuminate\Support\Str::contains($date, '/')) {
            list($day, $month, $year) = explode('/', $date);
            $date = $year . '-' . $month . '-' . $day;
        }
        return $date;
    }
}