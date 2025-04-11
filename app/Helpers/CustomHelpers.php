<?php

if (!function_exists('except')) {
    /**
     * Throw an exception with a message and optional class and data.
     * 
     * @param string $message
     * @param string $class
     * @param array $data
     * @return void
     */
    function except(string $message, string $class = '', array $data = [])
    {
        if ($class == '') {
            $class = Exception::class;
        }
        if (count($data)) {
            throw new $class($message, $data);
        } else {
            throw new $class($message);
        }
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